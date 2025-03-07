<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Interface\Service\User\UserServiceInterface;
use Illuminate\View\View;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\View\View
     */

    public function index(Request $request): View
    {
        $filters = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'dob_from' => $request->input('dob_from'),
            'dob_to' => $request->input('dob_to'),
            'type' => $request->input('type', 'all')
        ];

        // Fetch filtered users
        $users = $this->userService->getFilteredUsers($filters);

        return view('users.index', compact('users', 'filters'));
    }

    /**
     * @param int $id
     * @return redirect
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ((int) Auth::id() === (int) $user->id) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        if (Auth::check() && Auth::user()->type == 0) {
            return view('auth.register');
        }

        return redirect()->route(Auth::check() ? 'home' : 'users')
            ->with('error', Auth::check() ? 'You are not authorized to create users.' : 'Please log in first.');
    }

    /**
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function confirm(Request $request)
    {
        $result = $this->userService->confirmUser($request->all());

        if (isset($result['errors'])) {
            return redirect()->back()->withErrors($result['errors'])->withInput();
        }

        return view('auth.confirm', $result);
    }


    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function new()
    {
        $this->userService->registerUser();
        return redirect()->route('users.index')->with('success', 'User registered successfully!');
    }

    /**
     *
     * @return View
     */

    public function edit()
    {
        $user = $this->userService->editProfile();
        return view('users.profileedit', compact('user'));
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:15',
            'dob' => 'required|date|before_or_equal:today',
            'address' => 'nullable|string|max:255',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8048',
        ]);

        if ($this->userService->updateProfile($id, $validatedData)) {
            return redirect()->route('users.index')->with('success', 'Profile updated successfully!');
        }

        return redirect()->back()->with('error', 'Failed to update profile.');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(Request $request)
    {
        $validatedData = $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!$this->userService->changePassword($validatedData)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        return redirect()->route('users.index')->with('success', 'Password changed successfully.');
    }
}
