<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
    
    }
