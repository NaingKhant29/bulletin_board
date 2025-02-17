<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable // Keep this extending Authenticatable for authentication
{
    use HasFactory, SoftDeletes;
    use Notifiable;


    // Define the table name if it doesn't follow Laravel's convention
    protected $table = 'users';

    // The attributes that are mass assignable
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile',
        'type',
        'phone',
        'address',
        'dob',
        'created_user_id',
        'update_user_id',
        'delete_user_id',
        'remember_token',
    ];

    // The attributes that should be hidden for serialization
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // The attributes that should be cast
    protected $casts = [
        'email_verified_at' => 'datetime',
        'dob' => 'date', // Assuming dob is a date field
    ];

    // User.php

    public function createUser()
    {
        return $this->belongsTo(User::class, 'created_user_id');
    }

    public function updatedUser()
    {
        return $this->belongsTo(User::class, 'updated_user_id');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
    public function reactions() {
        return $this->hasMany(Reaction::class);
    }
    
   
}
