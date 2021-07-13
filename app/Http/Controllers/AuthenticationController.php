<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|size:8',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard')
            ->withSuccess('Signed in');
        }

        return redirect("/dashboard")->withSuccess('Login details are not valid');
    }

    public function register(Request $request)
    {  
        $request->validate([
            'name' => 'required|string|max:255|alpha_dash',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|size:8',
        ]);

        $data = $request->all();
        $user = $this->create_user($data);

        Auth::login($user);

        return redirect("/dashboard")->withSuccess('You have signed-in');

    }


    public function create_user(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
    } 

    public function logout() 
    {
        Session::flush();
        Auth::logout();

        return Redirect('/');
    }   
}
