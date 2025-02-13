<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Notifications\CustomResetPasswordNotification;

class ForgotPasswordController extends Controller
{
    /**
     * show link request form
     * @return View
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function sendPasswordResetLink(Request $request)
    {
      
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email'),
            function ($user, $token) {
                $user->notify(new CustomResetPasswordNotification($token));
            }
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }
}
