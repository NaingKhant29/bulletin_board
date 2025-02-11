<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    // Define the table associated with the model
    protected $table = 'password_resets';

    // If you want to use timestamps (created_at, updated_at), add the following line:
    public $timestamps = false;

    // If you want to allow mass assignment, specify the fillable fields
    protected $fillable = ['email', 'token', 'created_at'];
}
