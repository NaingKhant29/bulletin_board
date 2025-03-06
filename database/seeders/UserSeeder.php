<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (User::where('email', 'admin@example.com')->doesntExist()) {
            $admin = User::create([
                'id' => 1,
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'profile' => 'admin_profile',
                'type' => '0',
                'phone' => null,
                'address' => null,
                'dob' => null,
                'created_user_id' => 1,
                'updated_user_id' => 1,
                'deleted_user_id' => null,
            ]);
            $admin->created_user_id = $admin->id;
            $admin->updated_user_id = $admin->id;
            $admin->save();
        }
    }
}
