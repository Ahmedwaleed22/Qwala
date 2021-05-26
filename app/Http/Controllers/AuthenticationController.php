<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    public function login() {
        return view("login");
    }

    public function authenticate(Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'max:255'],
            'password' => ['required', 'max:255'],
        ]);

        if (Auth::attempt($validated)) {
            return redirect()->route("home");
        }
        return redirect()->back()->with('message', 'Please Check Your Username/Password');
    }

    public function register() {
        return view("register");
    }

    public function store(Request $request) {
        $request->validate([
            'name' => ['required', 'unique:users', 'max:255'],
            'email' => ['required', 'unique:users', 'max:255'],
            'password' => ['required', 'max:255'],
        ]);

        $user = User::create();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        return redirect()->route("login");
    }

    public function logout() {
        Auth::logout();
        return redirect()->route("login");
    }
}
