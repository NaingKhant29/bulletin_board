<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Notifications\CustomResetPasswordNotification;

class ForgotPasswordController extends Controller
{
    // Other existing methods...

    /**
     * Show the form to request a password reset link.
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password'); // Return the view for the password reset request
    }

    /**
     * Send a reset password link to the given user.
     */
    public function sendPasswordResetLink(Request $request)
    {
        // Validate the email input
        $request->validate(['email' => 'required|email']);

        // Send the reset password notification (using the custom notification)
        $status = Password::sendResetLink(
            $request->only('email'),
            function ($user, $token) {
                // Use the custom notification
                $user->notify(new CustomResetPasswordNotification($token));
            }
        );

        // Return appropriate response based on the status
        return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
    }
}
