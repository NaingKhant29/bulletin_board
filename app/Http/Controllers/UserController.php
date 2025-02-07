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
   protected function validator(array $data)
{
    info('********');
    info($data);
    return Validator::make($data, [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'type' => ['required', 'integer', 'in:0,1'], // Admin (0) or User (1)
        'phone' => ['nullable', 'string', 'max:15'], // Optional phone, max 15 characters
        'dob' => ['nullable', 'date'], // Optional, must be a valid date
        'address' => ['nullable', 'string', 'max:255'], // Optional address
        'image' => ['required', 'file', 'mimes:jpeg,png,jpg,gif', 'max:8048'],
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
        
        'image.required' => 'Please upload a profile picture.',
        'image.file' => 'The profile picture must be a file.',
        'image.mimes' => 'The profile picture must be a JPEG, PNG, JPG, or GIF image.',
        'image.max' => 'The profile picture cannot be larger than 8MB.',
    ]);
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
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8048',
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
            $image = $request->file('profile')->store('profiles', 'public');
            $user->profile = $image;
        }

        // Save user
        $user->save();

        // Redirect with success message
        return redirect()->route('users.dexin')->with('success', 'Profile updated successfully!');

    }

    public function confirm(Request $request)
    {
        info($request->all());
    
        // Validate using the existing validator method
        $validator = $this->validator($request->all());
    
        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // Retrieve validated data
        $validated = $validator->validated();
    
        // Handle Profile Image Upload
        $profilePath = null;
        if ($request->hasFile('image')) {
            $profilePath = $request->file('image')->store('profiles', 'public');
        }
    
        // Store data in session
        session([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'password_confirm' => $request->input('password_confirmation'),
            'type' => $validated['type'],
            'phone' => $validated['phone'] ?? null,
            'dob' => $validated['dob'] ?? null,
            'address' => $validated['address'] ?? null,
            'image' => $profilePath,
        ]);
    
        // Return the view with the data, allowing the user to confirm
        return view('auth.confirm', [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'password_confirm' => $request->input('password_confirmation'),
            'type' => $validated['type'],
            'phone' => $validated['phone'] ?? null,
            'dob' => $validated['dob'] ?? null,
            'address' => $validated['address'] ?? null,
            'image' => $profilePath,
        ]);
    }
    public function new()
    {

    
        // Retrieve data from session
        $data = session()->all();
    
        // Now you can use this data as needed, for example:
        $name = $data['name'];
        $email = $data['email'];
        $password = $data['password'];
        $password_confirm = $data['password_confirm'];
        $type = $data['type'];
        $phone = $data['phone'];
        $dob = $data['dob'];
        $address = $data['address'];
        $image = $data['image'];
    
        // Create the new user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password), // Always hash the password
            'type' => $type,
            'phone' => $phone,
            'dob' => $dob,
            'address' => $address,
            'profile' => $image ? $image : null, 
            'created_user_id' => Auth::id(),
            'updated_user_id' => Auth::id(),

        ]);
    
        // You can also redirect or return a response after saving
        return redirect()->route('users.dexin')->with('success', 'User registered successfully!');
    }   
}    
