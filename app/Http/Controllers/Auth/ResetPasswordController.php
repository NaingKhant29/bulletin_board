<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class ResetPasswordController extends Controller
{
    /**
     * @param string $token
     * @return View
     */
    public function showResetForm($token)
    {
        return view('auth.reset_password', ['token' => $token]);
    }


    /**
     * @param Request $request
     * @return redirect
     */
    public function reset(Request $request)
    {
        $validatedData = $request->validate([
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required',
        ]);

        $isUserExist = DB::table('password_resets')->where('email', $request->email)->first();
        if (!$isUserExist) {
            return redirect()->back()->withErrors(['token' => 'This password reset token is invalid.']);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'No user found with this email.']);
        }

        if ($user && Hash::check($request->token, $isUserExist->token)) {
            info("I'm here");
            $user->password = Hash::make($request->password);

            $user->save();
        }

        DB::table('password_resets')->where('email', $user->email)->delete();

        return redirect()->route('login')->with('status', 'Your password has been successfully reset.');
    }
}
