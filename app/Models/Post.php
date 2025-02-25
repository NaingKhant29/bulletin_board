<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'status',
        'category_id',
        'create_user_id',
        'updated_user_id',
        'deleted_user_id',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'create_user_id');
    }

    /**
     * @return BelongsTo
     */
    public function updated_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_user_id');
    }

    /**
     * @return void
     */
    public function toggleStatus():void
    {
        $this->status = $this->status === 1 ? 0 : 1;
        $this->save();
    }

    /**
     * @return HasMany
     */
    public function reactions():HasMany
    {
        return $this->hasMany(Reaction::class);
    }

    /**
     * @return HasMany
     * */
    public function comments():HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @return BelongsTo
     */
    public function category():BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
