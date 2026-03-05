<?php

namespace App\Models\Offer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Voteable;
use App\Models\Category;
use App\Models\User;
use App\Models\Comment;

class Offer extends Model
{
    use HasFactory, Voteable;

    protected $fillable = [
        'user_id',
        'offer_category_id',
        'title',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->morphMany(Category::class, 'offer_category_id');
    }

    /**
     * Get all comments for the offer.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
