<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    function create(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password'=>'required|min:5|max:30',
            'confirm_password'=>'required|min:5|max:30|same:password'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = \Hash::make($request->password);
        $save = $user->save();

        if($save)
        {
            Toastr::success('Registration successfully (-_-)','Success');
            return redirect()->route('user.login');
        }
        else
        {
            Toastr::warning('Error Occurred (-_-)','Warning');
            return redirect()->back();
        }

    }


    function checkCredentials(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password'=>'required|min:5|max:30',
                ],[
                'email.exists' => 'Oops Account Not Available'
                ]);
                $credentials = $request->only('email','password');
                if(Auth::guard('web')->attempt($credentials) ){
                    Toastr::success('User Login Successful (-_-)', 'Success');
                    return redirect()->route('user.home');
                }else{
                    Toastr::error('Oops!! Incorrect Credentials', 'Error');
                    return redirect()->route('user.login');
                }

    }

    function logout() {
        Auth::guard('web')->logout();
        Toastr::success('User Logged Out Successfully (-_-', 'Success');
        return redirect('/');
    }

}
