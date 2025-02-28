<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    /**
     * Where to redirect users after login.
     */
    protected $redirectTo = '/posts';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle the login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
            $remember = $request->has('remember');
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            if ($remember) {
                Cookie::queue('email', $request->email, 10080);
                Cookie::queue('password', $request->password, 10080); 
            } else {
                Cookie::queue(Cookie::forget('email'));
                Cookie::queue(Cookie::forget('password'));
            }
            return redirect()->intended($this->redirectTo);
        }
        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->withInput($request->only('email'));
    }

    /**
     * Handle the logout request.
     * @param Request $request
     * 
     * @return RedirectResponse
     */
    public function logout(Request $request)
    { 
        $email = Cookie::get('email');
        $password = Cookie::get('password');
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($email !== "" && $password !== "") {
            return redirect('/login')->withCookie('email', $email, 1)
                                   ->withCookie('password', $password, 1);
        }
        return redirect('/login');
    }
}
