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
use carbon\carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password; 

class ResetPasswordController extends Controller
{
    public function showResetForm($token)
    {
        return view('auth.reset_password', ['token' => $token]);
    }
    

    
    public function reset(Request $request)
    {
        // info(request()->all());
        
        $validatedData = $request->validate([
            'password' => 'required|string|min:8|confirmed',  // Password validation rules
            'token' => 'required',  // Validate token
        ]);
        
        // Step 2: Find the user by the token
        $isUserExist = DB::table('password_resets')->where('email', $request->email)->first();
        // info([$user]);

        if (!$isUserExist) {
            return redirect()->back()->withErrors(['token' => 'This password reset token is invalid.']);
        }

        // Step 3: Find the user by their email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'No user found with this email.']);
        }

        if ($user && Hash::check($request->token, $isUserExist->token)) {
            info("I'm here");
            $user->password = Hash::make($request->password); // Hash the password before saving

        $user->save();
        }
        // Step 6: Optionally, delete the reset token to prevent reuse
        DB::table('password_resets')->where('email', $user->email)->delete();

        // Step 7: Redirect or return a response after successful reset
        return redirect()->route('login')->with('status', 'Your password has been successfully reset.');
    }
}
