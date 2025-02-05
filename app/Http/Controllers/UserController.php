<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    public function dexin(Request $request)
    {
        // Get search values from request
        $name = $request->input('name');
        $email = $request->input('email');
        $dob_from = $request->input('dob_from');
        $dob_to = $request->input('dob_to');
    
        // Start query
        $query = User::query();
    
        // Apply filters if values are provided
        if ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        }
    
        if ($email) {
            $query->where('email', 'like', '%' . $email . '%');
        }
    
        if ($dob_from && $dob_to) {
            $query->whereBetween('dob', [$dob_from, $dob_to]);
        } elseif ($dob_from) {
            $query->where('dob', '>=', $dob_from);
        } elseif ($dob_to) {
            $query->where('dob', '<=', $dob_to);
        }
    
        // Paginate results, showing 10 users per page
        $users = $query->paginate(10);
    
        // Return the view with users data
        return view('users.dexin', compact('users'));
    }

    // Add the destroy method for deleting a user
    public function destroy($id)
    {
        // Find the user by ID or fail if not found
        $user = User::findOrFail($id);

        // Check if the user is trying to delete themselves (you may want to prevent that)
        if (Auth::id() === $user->id) {
            return redirect()->route('users.dexin')->with('error', 'You cannot delete your own account.');
        }

        // Delete the user
        $user->delete();

        // Redirect back to the user list page with success message
        return redirect()->route('users.dexin')->with('success', 'User deleted successfully');
    }
    // public function __construct()
    // {
    //     $this->middleware('guest');
    // }


    public function showRegistrationForm()
    {
        if (Auth::check()) {
            if (Auth::user()->type == 0) { // Admin user
                return view('auth.register'); // Allow access to the registration form
            } else { // Regular user
                return redirect()->route('home')->with('error', 'You are not authorized to create users.');
            }
        }
        
        return redirect()->route('users')->with('error', 'Please log in first.');
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
    ], [
        // Custom messages
        'name.required' => 'Please provide your full name.',
        'name.string' => 'The name must be a valid string.',
        'name.max' => 'The name cannot be longer than 255 characters.',
        
        'email.required' => 'We need your email to contact you.',
        'email.email' => 'Email Format is invalid',
        'email.max' => 'The email cannot be longer than 255 characters.',
        'email.unique' => 'The email is already taken.',
        
        'password.required' => 'Password is required.',
        'password.min' => 'Password must be at least 8 characters.',
        'password.confirmed' => 'Password and Password confirmation does not match.',
        
        'type.required' => 'Please select the user type.',
        'type.in' => 'The user type must be either Admin (0) or User (1).',
        
        'phone.max' => 'Phone number cannot be longer than 15 characters.',
        
        'dob.date' => 'Please provide a valid date of birth.',
        
        'address.max' => 'The address cannot be longer than 255 characters.',
        
        'profile.required' => 'Please upload a profile picture.',
        'profile.file' => 'The profile picture must be a file.',
        'profile.mimes' => 'The profile picture must be a JPEG, PNG, JPG, or GIF image.',
        'profile.max' => 'The profile picture cannot be larger than 8MB.',
    ]);
}



    protected function create(Request $request)
    {
        info('Submitted Registration Data:', $request->all());

        $validatedData = $this->validator($request->all())->validate();
    
        // Handle the profile image upload
        if ($request->hasFile('profile') && $request->file('profile')->isValid()) {
            $profilePath = $request->file('profile')->store('profiles', 'public');
            info('Profile file successfully uploaded. Path: ' . $profilePath);
        } else {
            info('No valid profile file uploaded.');
            $profilePath = 'profiles/default-profile.jpg'; // Default profile image
        }
    
        // Get the authenticated user's ID or a fallback value if not authenticated
        $userId = Auth::check() ? Auth::id() : 1; // Default to 1 if not authenticated (ensure admin exists with ID 1)
    
        // Create the user with the profile image path and user IDs
        $user = User::create([
            'name' => $validatedData['name'], // Ensure this is passed properly from the form
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'type' => $validatedData['type'],
            'phone' => $validatedData['phone'],
            'dob' => $validatedData['dob'],
            'address' => $validatedData['address'],
            'profile' => $profilePath,
            'create_user_id' => $userId,
            'updated_user_id' => $userId,
        ]);
    
        // Redirect after successful registration
        return redirect()->route('users.dexin')->with('success', 'User registered successfully.');
    }
    public function edit()
    {
        $user = Auth::user(); // Get the currently authenticated user
        return view('users.profileedit', compact('user')); // Pass the user to the view
    }
    public function updateProfile(Request $request, $id)
    {
        info("I am here");
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:15',
            'dob' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Find user
        $user = User::findOrFail($id);

        // Only Admin can change type
        if (Auth::user()->type == 0) {
            $user->type = $request->input('type');
        }

        // Update other fields
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->dob = $request->input('dob');
        $user->address = $request->input('address');

        // Handle profile image upload
        if ($request->hasFile('profile')) {
            // Delete old profile if exists
            if ($user->profile) {
                Storage::delete('public/' . $user->profile);
            }

            // Store new profile
            $profilePath = $request->file('profile')->store('profiles', 'public');
            $user->profile = $profilePath;
        }

        // Save user
        $user->save();

        // Redirect with success message
        return redirect()->route('users.dexin')->with('success', 'Profile updated successfully!');

    }
        
    
}
