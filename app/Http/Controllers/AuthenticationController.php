<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;
use Carbon\Carbon;
use Mail;

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

        $fieldType = filter_var($request->name, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        if (Auth::attempt(array($fieldType => $request->name, 'password' => $request->password))) {
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

    public function forgetpassword() {
        return view("forgetpassword");
    }

    public function resetpassword(Request $request) {
        $request->validate([
            'email' => 'email|required',
        ]);

        $user = DB::table('users')->where('email', '=', $request->email)->first();

        //Check if the user exists
        if ($user == '') {
            return redirect()->back()->with('message', 'User does not exist');
        }

        //Create Password Reset Token
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => str_random(60),
            'created_at' => Carbon::now()
        ]);
        //Get the token just created above
        $tokenData = DB::table('password_resets')
            ->where('email', $request->email)->first();

        if ($this->sendResetEmail($request->email, $tokenData->token)) {
            return redirect()->back()->with('status', trans('A reset link has been sent to your email address.'));
        } else {
            return redirect()->back()->withErrors(['error' => trans('A Network Error occurred. Please try again.')]);
        }

        return redirect()->route("forgetpassword");
    }

    private function sendResetEmail($email, $token) {
        //Retrieve the user from the database
        $user = DB::table('users')->where('email', $email)->select('name', 'email')->first();
        //Generate, the password reset link. The token generated is embedded in the link
        $link = config('base_url') . 'password/reset/' . $token . '?email=' . urlencode($user->email);

        try {
            Mail::send(
                'email.forget',
                ['token' => $token],
                function ($message) use ($email) {
                    $message->to($email);
                    $message->subject('Your Qwala Password Reset Link');
                }
            );

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function reset_password_with_token(Request $request) {
        $request->validate([
            'token' => 'string'
        ]);

        $tokenData = DB::table('password_resets')->where('token', $request->token)->first();

        if ($tokenData == null) {
            abort(404);
            return;
        }

        return view('resetpassword', ['token' => $request->token]);
    }

    public function confirmresetpassword(Request $request) {
        $request->validate([
            'password' => 'required|string',
            'resettoken' => 'required|string'
        ]);

        $tokenData = DB::table('password_resets')->where('token', $request->resettoken)->first();

        $user = User::whereEmail($tokenData->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_resets')->where('email', $user->email)
        ->delete();

        $email = $user->email;

        try {
            Mail::send(
                'email.success_reset',
                ['email' => $email],
                function ($message) use ($email) {
                    $message->to($email);
                    $message->subject('Your Qwala Password Reset Successfully');
                }
            );
        } catch (\Exception $e) {
            return abort(500);
        }


        return redirect()->route('login')->with('message', 'Password successfully reset');
    }
}
