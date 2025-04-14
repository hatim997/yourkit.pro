<?php

namespace App\Http\Controllers;

use App\Http\Requests\Frontend\auth\LoginRequest;
use App\Http\Requests\Frontend\auth\RegisterRequest;
use App\Models\EmailTemplate;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\Role;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail as Email;

class AuthController extends Controller
{

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(){
        return view('frontend.auth.signin');
    }

    public function login(LoginRequest $request)
{
    $credentials = [
        'email' => $request->user_email,
        'password' => $request->user_password
    ];

    $user = User::with("role")->where("email", $request->user_email)->first();

    if ($user?->role[0]?->pivot?->role_id != Role::USER) {
        Toastr::error("Only users can log in.", "Error");
        return redirect()->back()->withErrors(["user_email" => "Only users can log in."]);
    }

    $intendedUrl = session('url.intended', route('dashboard'));

    if (Auth::attempt($credentials)) {
        Toastr::success("Login successful!", "Welcome");
        return redirect($intendedUrl);
    }

    
    Toastr::error("Invalid email or password.", "Login Failed");
    return back()->with('error', 'Invalid email or password.');
}


    public function register(RegisterRequest $request){
        $input = $request->all();

        //return $input;

        DB::beginTransaction();
        try {

            $this->userRepository->registerUser($input, 'user');
            DB::commit();

            return back()->with('success', 'Registered successfully');

        } catch(\Exception $e){

            DB::rollBack();

            return $e->getMessage();

            return back()->with('error', 'Something went wrong!');
        }
    }

    public function logout(){

        Auth::logout();
        return redirect()->route('signin');
    }

    public function submitForgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        $token = \Str::random(64);
   //echo $token.'@123@';
        try {
            // Attempt to insert or update the token
           $token_var= DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                ['token' => $token, 'created_at' => Carbon::now()] 
            );
   
            // Send email
            $this->sendEmailVerificationEmail($user,$token);
    
            Toastr::success('Password reset link has been sent to your mail.', 'Success');
        } catch (\Exception $e) {
            // Catch any other unexpected errors
            Toastr::error($e->getMessage(), 'Error');
        }
    
        return redirect()->route('signin');
    }

    private function sendEmailVerificationEmail($user,$token)
    {
        //sending email for verification

        //echo $token.'@456@';
        $pwd = DB::table('password_reset_tokens')->where('email', $user->email)->first();
        //print_r( $pwd);
        //$token = $pwd->token;



        $mail_template      = EmailTemplate::where('email_type', 'reset_password')->first();
        $message            = $mail_template->content;
        $search = array('[name]', '[link]', '[site_name]', '[logo]');
        $replace = array($user->name, route("reset.password", [$token]), 'Construction Tshirt',  url("assets/frontend/images/logo.png") );
        $message = str_replace($search, $replace, $message);

        $data['name'] = $user->name;
        $data['email'] = $user->email;
        $data['attachments'] = '';
        $data['link'] = route("reset.password", [$token]);
        $data['subject'] = $mail_template->subject;
        $data['content'] = $message;
        //echo $message;die;
        $data['template_file_path'] = "frontend.mails.email_template";
        Email::to($data['email'])->send(new SendEmail($data));
    }


    public function showResetPassword($token)
    {
        $data['token'] = $token;

        return view('frontend.auth.reset-password', $data);
    }

    public function submitResetPassword(Request $request)
    {


        $request->validate([

            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ],[
            'password.required' => 'The password field is required.',
            'password.string' => 'The password must be a valid string.',
            'password.min' => 'The password must be at least 6 characters.',
            'password.confirmed' => 'Password and confirm password does not match.',
            'password_confirmation.required' => ' The Confirm password field is required .'
        ]);


  try{

        $updatePassword = DB::table('password_reset_tokens')
            ->where([
                'token' => $request->token
            ])
            ->first();
//dd( $updatePassword);

        if (!$updatePassword) {
                Toastr::error('Invalid token! ','Error');
            return back()->withInput()->with('error', 'Invalid token!');
        }
        $user = User::where('email', $updatePassword->email)->first();
        if (!$user) {
                Toastr::error('Invalid User! ','Error');
            return back()->withInput()->with('error', 'Invalid User!');
        }


        $user->update(['password' => \Hash::make($request->password)]);

        DB::table('password_reset_tokens')->where(['token' => $request->token])->delete();
        Toastr::success('Password changed successfully.', 'Success');
        return redirect()->route('signin');
        
    }  catch(\Exception $e){

            DB::rollBack();

            return $e->getMessage();

            Toastr::error('Something went wrrong! ','Error');
            return back();

        }
    }

}
