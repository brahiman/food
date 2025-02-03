<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function adminLogin(){
        return view('admin.login');
    }
    //end Method

    public function adminDashboard(){
        return view('admin.dashboard');
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
}
