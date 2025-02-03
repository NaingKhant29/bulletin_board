<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /*
    |---------------------------------------------------------------------------
    | Register Controller
    |---------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default, this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        if (Auth::check()) {
            return redirect()->route('home'); // Redirect to home or wherever you want
        }
        return view('auth.register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'type' => ['required', 'integer', 'in:0,1'], // Admin (0) or User (1)
            'phone' => ['nullable', 'string', 'max:15'], // Optional phone, max 15 characters
            'dob' => ['nullable', 'date'], // Optional, must be a valid date
            'address' => ['nullable', 'string', 'max:255'], // Optional address
            'profile' => ['required', 'file', 'mimes:jpeg,png,jpg,gif', 'max:8048'], // Optional profile, max 2MB, jpg/jpeg/png/gif only
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(Request $request)
{
    if ($request->hasFile('profile') && $request->file('profile')->isValid()) {
        $profilePath = $request->file('profile')->store('profiles', 'public');
        info('Profile file successfully uploaded. Path: ' . $profilePath);
    } else {
        info('No valid profile file uploaded.');
        $profilePath = 'profiles/default-profile.jpg'; // Use a default profile image
    }
    
    // Get the authenticated user's ID or a fallback value if not authenticated
    if (Auth::check()) {
        // If authenticated, use the logged-in user's ID for created_user_id and updated_user_id
        $userId = Auth::id();
    } else {
        // If not authenticated, set a fallback ID (e.g., 1 for admin)
        $userId = 1; // Ensure this ID exists in the database, typically the admin user ID
    }

    // Create the user with the profile image path and user IDs
    return User::create([
        'name' => $request['name'],
        'email' => $request['email'],
        'password' => Hash::make($request['password']),
        'type' => $request['type'],
        'phone' => $request['phone'],
        'date_of_birth' => $request['dob'],
        'address' => $request['address'],
        'profile' => $profilePath,
        'create_user_id' => $userId, // Set the create_user_id to the current user's ID or fallback value
        'updated_user_id' => $userId, // Set the updated_user_id to the same value
    ]);
    return redirect()->route('users.dexin')->with('success', 'User register successfully.');

}


}
