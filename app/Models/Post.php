<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'status',
        'create_user_id',
        'updated_user_id',
        'deleted_user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'create_user_id');
    }
    public function updated_user()
    {
        return $this->belongsTo(User::class, 'updated_user_id');
    }
    public function toggleStatus()
    {
        $this->status = $this->status === 1 ? 0 : 1;
        $this->save();
    }
    public function reactions() {
        return $this->hasMany(Reaction::class);
    }
    
}
