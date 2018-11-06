<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class AdminLoginController extends Controller
{
    //Allow only users that are not logged as admin to access the functions
    public function __construct(){
        $this->middleware('guest:admin')->except('adminLogout');
    }

    public function showLoginForm(){
        return view('auth.admin-login');
    }

    public function login(Request $request){
        
        //Validate the form data
        $this->validate($request, array(
            'email' => 'required|email',
            'password' => 'required|min:6'
        ));

        //Attempt to log the user in with the admin guard, using the attempt function with the login parameters and the remember
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)){ 
            //If successful, then redirect to intended destination
            return redirect()->intended(route('admin.dashboard'));
        } else {
            //Else, redirect back to login with the form data
            return redirect()->back()->withInput($request->only('email', 'remember'));
        }
    }

    public function adminLogout(){
        Auth::guard('admin')->logout();
        return redirect('/');
    }
}
