<?php

namespace App\Http\Controllers;

use App\Mail\Websitemail;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function showAdminLoginForm(){
        return view('admin.login');
    }
    //end Method

    public function adminDashboard(){
        return view('admin.index');
    }
    //end Method

    public function adminLoginSubmit(Request $request){
        $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);
        $check = $request->all();
        $data = [
            'email'=>$check['email'],
            'password'=>$check['password']
        ];
        if (Auth::guard('admin')->attempt($data)) {
            return redirect()->route('admin.dashboard')->with('success','Connexion réussie');
        }else{
            return redirect()->route('admin.login')->with('error','Identifiants incorrects');
        }
    }
    //end Method


    public function adminLogout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login')->with('success','Deconnexion réussie');
    }
    //end Method
    public function showAdminForgetPasswordForm()
    {
        return view('admin.forgetpassword');
    }
    //end Method
    public function adminForgetPasswordSubmit(Request $request){
        $request->validate([
            'email'=>'required|email'
        ]);
        $admin_data = Admin::where('email', $request->email)->first();
        if(!$admin_data){
            return redirect()->back()->with('error','Email incorrect');
        }
        $token = hash("sha256",time());
        $admin_data->token = $token;
        $admin_data->update();

        $reset_link = url('admin/reset-password/'.$token.'/'.$admin_data->email);
        $subject = "Reset password";
        $message = "<p>Dear Admin, Pleace click on belon link to reset your password</p>";
        $message .= "<p><a href='".$reset_link."'>".$reset_link."</a></p>";
        $message .= "<p>Regards, Team Team</p>";

        \Mail::to($request->email)->send(new Websitemail($subject, $message));
        return redirect()->back()->with('success','Reset password link sent on your email');

    }
    //end Method
    public function showAdminResetPasswordForm($token,$email){
        $admin_data = Admin::where('token', $token)->where('email', $email)->first();
        if(!$admin_data){
            return redirect()->back()->with('error','Token or Email incorrect');
        }
        return view('admin.resetpassword', compact('token','email'));
    }
    //end Method
    public function adminResetPasswordSubmit(Request $request){
        $request->validate([
            'password'=>'required',
            'password_confirmation'=>'required|same:password'
        ]);
        $admin_data = Admin::where('token', $request->token)->where('email', $request->email)->first();
        $admin_data->password = Hash::make($request->password);
        $admin_data->token = "";
        $admin_data->update();

        return redirect()->route('admin.login')->with('success','Password reset successfully');
    }
    //end Method
}
