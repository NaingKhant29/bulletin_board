<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if the admin user already exists
        if (User::where('email', 'admin@example.com')->doesntExist()) {
            // Create a new admin user (without referencing created_user_id and updated_user_id initially)
            $admin = User::create([
                'id' => 1,
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'profile' => 'admin_profile',
                'status' => 0, // Admin type (0 for admin)
                'phone' => null,
                'address' => null,
                'dob' => null,
                'created_user_id' => 1, // Initially set to null
                'updated_user_id' => 1, // Initially set to null
                'deleted_user_id' => null, // Optional
            ]);


            // After the admin is created, update created_user_id and updated_user_id
            $admin->created_user_id = $admin->id;
            $admin->updated_user_id = $admin->id;
            $admin->save();
            
        }
    }
}
