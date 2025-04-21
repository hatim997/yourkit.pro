<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\UpdateProfileRequest;
use App\Models\Contact;
use App\Repositories\ProfileRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{

    protected $profileRepository;

    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function index(){
        $user = $this->profileRepository->getProfile();
        return view('frontend.auth.dashboard', compact('user'));
    }

    public function profile(){

    }

    public function editProfile(){
        $user = $this->profileRepository->getProfile();
        // return $user;
        return view('frontend.profile.edit', compact('user'));
    }

    public function updateProfile(UpdateProfileRequest $request){

        $input = $request->all();

        DB::beginTransaction();

        try{

            $this->profileRepository->updateProfile($input);

            DB::commit();


            // Toastr::success("Profile updated successfully", "Welcome");
            return redirect()->back()->with('success', 'Profile updated successfully');

        } catch(\Exception $e){

            DB::rollBack();
            return $e->getMessage();
            return back()->with('error', 'Something went wrong!');

        }

    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();

       // $file = $this->uploadFile($data['image'], 'banners');
        $imagePath = $request->file('image')->store('uploads', 'public');


        $user->image = $imagePath;
        $user->save();

        return response()->json(['status' => 'success', 'image_path' => url('storage/' . $imagePath)]);
    }


    public function contact(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'phone' => 'required|regex:/^[0-9]+$/|min:10',
            'email' => 'required|email',
            'message' => 'required|string|max:500',
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all())->with('error', 'Validation Error!');
        }


         Contact::create($request->all());


        // Mail::raw("Message from: {$request->name}\n\n{$request->message}", function($msg) use ($request) {
        //     $msg->to('admin@example.com')->subject('New Contact Form Submission');
        // });

        // Toastr::success("Your message has been sent successfully!", "success");
        return redirect()->back()->with('success', 'Your message has been sent successfully!');
    }
}
