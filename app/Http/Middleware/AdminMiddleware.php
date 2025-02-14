<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * 
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in and is an admin (type = 0)
        if (!Auth::check() || Auth::user()->type !== 0) {
            return redirect()->route('posts.index')->with('error', 'Access Denied!');
        }

        return $next($request);
    }
}

