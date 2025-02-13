<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return View
     */
    public function dexin(Request $request)
    {
        // Get search values from request
        $name = $request->input('name');
        $email = $request->input('email');
        $dob_from = $request->input('dob_from');
        $dob_to = $request->input('dob_to');

     
        $query = User::query();

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

        $users = $query->paginate(10);

        return view('users.dexin', compact('users'));
    }

    /**
     * @param int $id
     * @return redirect
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (Auth::id() === $user->id) {
            return redirect()->route('users.dexin')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

      
        return redirect()->route('users.dexin')->with('success', 'User deleted successfully');
    }
 
    /**
     * 
     * @return redirect
     */
    public function showRegistrationForm()
    {
        if (Auth::check()) {
            if (Auth::user()->type == 0) { 
                return view('auth.register'); 
            } else { 
                return redirect()->route('home')->with('error', 'You are not authorized to create users.');
            }
        }

        return redirect()->route('users')->with('error', 'Please log in first.');
    }
    /**
     * @param array $data
     * @return Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        info('********');
        info($data);
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'type' => ['required', 'integer', 'in:0,1'],
            'phone' => ['nullable', 'string', 'max:15'], 
            'dob' => ['nullable', 'date'],
            'address' => ['nullable', 'string', 'max:255'],
            'image' => ['required', 'file', 'mimes:jpeg,png,jpg,gif', 'max:8048'],
        ], [
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
    /**
     *
     * @return View
     */
    public function edit()
    {
        $user = Auth::user(); 
        return view('users.profileedit', compact('user')); 
    }
    /**
     * @param Request $request
     * @param int $id
     * @return redirect
     */
    public function updateProfile(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:15',
            'dob' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8048',
        ]);

        $user = User::findOrFail($id);

        if (Auth::user()->type == 0) {
            $user->type = $request->input('type');
        }

        $isEdited = $user->name !== $request->input('name') ||
            $user->email !== $request->input('email') ||
            $user->phone !== $request->input('phone') ||
            $user->dob !== $request->input('dob') ||
            $user->address !== $request->input('address') ||
            $request->hasFile('profile');  // Check for profile image change

        // Update user fields
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->dob = $request->input('dob');
        $user->address = $request->input('address');

        if ($request->hasFile('profile')) {
            if ($user->profile) {
                Storage::delete('public/' . $user->profile);
            }

            $image = $request->file('profile')->store('profiles', 'public');
            $user->profile = $image;
        }

      
        if ($isEdited) {
            $user->updated_user_id = auth()->id();
            $user->updated_at = now();
        }


        $user->save();

        return redirect()->route('users.dexin')->with('success', 'Profile updated successfully!');
    }
    /**
     * @param Request $request
     * @return View
     */

    public function confirm(Request $request)
    {
        info($request->all());

        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        $profilePath = null;
        if ($request->hasFile('image')) {
            $profilePath = $request->file('image')->store('profiles', 'public');
        }


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
    /**
     * 
     * @return redirect
     */
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
    /**
     * 
     * @return View
     */
    public function showChangePasswordForm()
    {
        info("ah shit! Here we go");
        return view('auth.change-password');
    }

    /**
     * @param Request $request
     * @return redirect
     */

    public function changePassword(Request $request)
    {
        // Validate the password inputs
        info($request->all());
        $validated = $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Check if the current password matches the stored password
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Update the password using bcrypt (Hash::make)
        Auth::user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('users.dexin')->with('success', 'Password changed successfully.');
    }
}
