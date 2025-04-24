<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\frontend\auth\LoginRequest;
use App\Http\Requests\frontend\auth\RegisterRequest;
use App\Mail\SendEmail;
use App\Models\EmailTemplate;
use App\Models\Profile;
use App\Models\User;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail as Email;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        if (Auth::check()) {
            $user = User::findOrFail(Auth::user()->id);
            if ($user->hasRole('user')) {
                return redirect()->route('frontend.dashboard');
            }else{
                return redirect()->route('dashboard');
            }
        } else {
            return view('frontend.auth.signin');
        }
    }

    public function login(Request $request)
    {
        // dd($request->all());
        $rules = [
            'user_email' => 'required|string|exists:users,email',
            'user_password' => 'required|string'
        ];

        // If captcha is used
        if (config('captcha.version') !== 'no_captcha') {
            $rules['g-recaptcha-response'] = 'required|captcha';
        } else {
            $rules['g-recaptcha-response'] = 'nullable';
        }

        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput($request->all())->with('error', 'Validation Error!');
        }
        try {
            $user = User::where("email", $request->user_email)->firstOrFail();
            // Check if the user has the 'USER' role

            $intendedUrl = session('url.intended', route('frontend.dashboard'));

            $credentials = [
                'email' => $request->user_email,
                'password' => $request->user_password
            ];
            if (Auth::attempt($credentials)) {
                if (!$user->hasRole('user')) {
                    return redirect()->route('dashboard')->with('success', 'Login Successfully!');
                    // return redirect()->back()->withErrors(["user_email" => "Only users can log in!"])->with('error', 'Only User can login here!');
                }else{
                    return redirect()->route('frontend.dashboard')->with('success', 'Login Successfully!');
                }
            }else{
                return back()->with('error', 'Invalid email or password.');
            }

        } catch (\Throwable $th) {
            Log::error("Failed to User Login:" . $th->getMessage());
            return redirect()->back()->withInput($request->all())->with('error', "Something went wrong! Please try again later");
        }


    }


    public function register(RegisterRequest $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => 'required|min:8',
            'phone' => 'required|integer',
            'password_confirmation' => 'required|same:password',
        ];

        // Make 'g-recaptcha-response' nullable if CAPTCHA is not enabled
        if (config('captcha.version') !== 'no_captcha') {
            $rules['g-recaptcha-response'] = 'required|captcha';
        } else {
            $rules['g-recaptcha-response'] = 'nullable';
        }

        $validate = Validator::make($request->all(), $rules);
        if($validate->fails()){
            return redirect()->back()->withErrors($validate)->withInput($request->all())->with('error', 'Validation Error!');
        }

        DB::beginTransaction();
        try {

            // $this->userRepository->registerUser($input, 'user');
            $user = new User();
            $user->name = $request['name']; // Ensure 'name' is part of the input
            $user->email = $request['email']; // Ensure 'email' is part of the input
            $user->phone = $request['phone']; // Ensure 'email' is part of the input
            $user->password = Hash::make($request['password']); // Hash the password using Hash facade
            $user->save();

            $profile = new Profile();
            $profile->user_id = $user->id;
            $profile->save();

            $user->assignRole('user');

            DB::commit();

            return redirect()->back()->with('success', 'Registered successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to User Registration:" . $e->getMessage());
            // return $e->getMessage();
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function logout()
    {

        Auth::logout();
        return redirect()->route('frontend.signin');
    }

    public function submitForgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $user = User::where('email', $request->email)->first();

        $token = Str::random(64);
        //echo $token.'@123@';
        try {
            // Attempt to insert or update the token
            $token_var = DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                ['token' => $token, 'created_at' => Carbon::now()]
            );

            // Send email
            $this->sendEmailVerificationEmail($user, $token);

            return redirect()->route('frontend.signin')->with('success', 'Password reset link has been sent to your mail');
        } catch (\Exception $e) {
            // Catch any other unexpected errors
            return redirect()->back()->with('success', $e->getMessage());
        }
    }

    private function sendEmailVerificationEmail($user, $token)
    {
        //sending email for verification

        //echo $token.'@456@';
        $pwd = DB::table('password_reset_tokens')->where('email', $user->email)->first();
        //print_r( $pwd);
        //$token = $pwd->token;



        $mail_template      = EmailTemplate::where('email_type', 'reset_password')->first();
        $message            = $mail_template->content;
        $search = array('[name]', '[link]', '[site_name]', '[logo]');
        $replace = array($user->name, route("reset.password", [$token]), 'Construction Tshirt',  url("assets/frontend/images/logo.png"));
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
        ], [
            'password.required' => 'The password field is required.',
            'password.string' => 'The password must be a valid string.',
            'password.min' => 'The password must be at least 6 characters.',
            'password.confirmed' => 'Password and confirm password does not match.',
            'password_confirmation.required' => ' The Confirm password field is required .'
        ]);


        try {

            $updatePassword = DB::table('password_reset_tokens')
                ->where([
                    'token' => $request->token
                ])
                ->first();
            //dd( $updatePassword);

            if (!$updatePassword) {
                return redirect()->back()->withInput()->with('error', 'Invalid token!');
            }
            $user = User::where('email', $updatePassword->email)->first();
            if (!$user) {
                return redirect()->back()->withInput()->with('error', 'Invalid User!');
            }


            $user->update(['password' => Hash::make($request->password)]);

            DB::table('password_reset_tokens')->where(['token' => $request->token])->delete();
            return redirect()->route('frontend.signin')->with('success','Password changed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
