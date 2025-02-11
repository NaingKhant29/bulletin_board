<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Log;

class ResetPasswordController extends Controller
{
    // Show the reset password form
    public function showResetForm($token)
    {
        return view('auth.reset_password', compact('token'));
    }

    
   
    public function reset(Request $request)
    {
        // Validate the request
        $request->validate([
            'password' => 'required|confirmed|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*?&]/',
            'token' => 'required',
        ]);
    
        // Find the reset record by the token
        $passwordReset = PasswordReset::where('token', $request->token)->first();
    
        // Check if the token exists and is valid (including expiration)
        if (!$passwordReset) {
            return redirect()->route('password.request')->withErrors(['email' => 'The reset link has expired or is invalid. Please try again.']);
        }
    
        // Log the created_at time and the current time for debugging
        Log::info("Token created at: " . $passwordReset->created_at);
        Log::info("Current time: " . now());
    
        // Check if the token has expired (e.g., 60 minutes expiration)
        if ($passwordReset->created_at->isBefore(now()->subMinutes(60))) {
            // Token expired, delete it
            $passwordReset->delete();
            return redirect()->route('password.request')->withErrors(['email' => 'This password reset token has expired.']);
        }
    
        // Find the user associated with the reset request
        $user = User::where('email', $passwordReset->email)->first();
    
        if (!$user) {
            return redirect()->route('password.request')->withErrors(['email' => 'No user found with this email address.']);
        }
    
        // Reset the user's password
        try {
            $user->password = Hash::make($request->password); // Hash the password
            $user->save();
    
            // Log the new password hash (for debugging purposes)
            Log::info("New password hash saved: " . $user->password);
        } catch (\Exception $e) {
            Log::error("Error saving password: " . $e->getMessage());
            return redirect()->route('password.request')->withErrors(['email' => 'An error occurred while resetting your password. Please try again later.']);
        }
    
        // Delete the password reset record (so it can't be reused)
        $passwordReset->delete();
    
        // Redirect the user to their dashboard or intended page
        return redirect()->intended(route('dashboard'))->with('status', 'Your password has been reset successfully.');
    }   
    
}
