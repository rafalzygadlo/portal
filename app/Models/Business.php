<?php

namespace App\Models;

use App\Traits\Voteable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Business extends Model
{
    use HasFactory, Voteable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'address',
        'phone',
        'website',
        'latitude',
        'longitude',
        'is_approved',
    ];

    /**
     * Get the user that owns the business.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The categories that belong to the business.
     */
    public function categories()
    {
        return $this->morphToMany(\App\Models\Category::class, 'categorizable');
    }

    /**
     * Get all comments for the business.
     */
    public function comments()
    {
        return $this->morphMany(\App\Models\Comment::class, 'commentable');
    }
}
