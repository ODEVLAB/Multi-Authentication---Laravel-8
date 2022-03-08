<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    function checkCredentials(Request $request){
        $request->validate([
            'email' => 'required|email|exists:admins,email',
            'password'=>'required|min:5|max:30',
                ],[
                'email.exists' => 'Oops Account Not Available'
                ]);
                $credentials = $request->only('email','password');
                if(Auth::guard('admin')->attempt($credentials) ){
                    Toastr::success('Administrator Login Successful (-_-)', 'Success');
                    return redirect()->route('admin.home');
                }else{
                    Toastr::error('Oops!! Incorrect Credentials', 'Error');
                    return redirect()->route('admin.login');
                }

    }

    function logout() {
        Auth::guard('admin')->logout();
        Toastr::success('Administrator Logged Out Successfully (-_-', 'Success');
        return redirect('/');
    }
}
