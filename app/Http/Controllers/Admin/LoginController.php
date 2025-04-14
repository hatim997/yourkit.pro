<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class LoginController extends Controller
{
    public function index(){
        return view('admin.auth.login');
    }

    public function login(LoginRequest $request){
        // return $request->all();

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {

            if(!Auth::guard('admin')->user()->hasRole('admin')){
                Auth::guard('admin')->logout();
                Toastr::error('No User Found ','Error');
            }

            return redirect()->route('admin.dashboard');
        }

        Toastr::error('WRONG USERNAME OR PASSWORD ','Error');
        return back();
    }

    public function logout(){

        Auth::guard('admin')->logout();
        Toastr::success('Logout successfully :)','Success');
        return redirect()->route('admin.login');
    }
    
    public function changePassword()
    {
        return view('admin.auth.change_password');
    }

    public function changePasswordPost(Request $request)
    {
        //dd(0);
        try { 
          $values = $request->validate([
    "old_password" => "required",
    "new_password" => "required|min:6|different:old_password",
    "con_password" => "required|same:new_password",
], [
    "old_password.required" => "The old password field is required.",
    "new_password.required" => "The new password field is required.",
    "new_password.min" => "The new password must be at least 6 characters long.",
    "new_password.different" => "The new password must be different from the old password.",
    "con_password.required" => "The confirm password field is required.",
    "con_password.same" => "Password and confirm password does not match."
]);


        if (!\Hash::check($request->old_password, auth()->user()->password)) {
            Toastr::error("Old Password Doesn't match!");
            return redirect()->back();
        }


        #Update the new Password
        User::whereId(auth()->user()->id)->update([
            'password' => \Hash::make($request->new_password)
        ]);
        Toastr::success('Password changed successfully.', 'Success');
        return redirect()->route('admin.dashboard');
    }catch (\Illuminate\Validation\ValidationException $e) {
        // Capture validation errors and display them as toast notifications
        foreach ($e->validator->errors()->all() as $error) {
            Toastr::error($error);
        }
        
        return redirect()->back()->withInput();
    }
    }
}
