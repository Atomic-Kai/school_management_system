<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthenticationController extends Controller
{
    public function loginForm(){
        return view('login');
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|regex:/(.+)@(.+)\.(.+)/i',
            'password' => ['required', Password::min(8)],
        ]);

        if($validator->fails()){
            $errors = $validator->messages();
            $messsage = implode(", ", $errors->all());
            return back()->with("Error", $messsage);
        }

       if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            return redirect()->route('home');
       }
       else{
            return back()->with("Error", "Email or password is invalid");
       }

       return redirect()->route('login');

    }

    public function logout(){

        Auth::logout(); //logout
        return redirect()->route('login');
    }
}
