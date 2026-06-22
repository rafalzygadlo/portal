<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Voteable;
use App\Traits\Favoritable;
use App\Models\Category;
use App\Models\User;
use App\Models\Comment;
use App\Models\Image;

class Offer extends Model
{
    use HasFactory, Voteable, Favoritable;

    protected $fillable = [
        'user_id',
        'offer_category_id',
        'title',
        'content',
        'slug',
        'price'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function categories()
    {
        return $this->morphToMany(Category::class, 'categoryable');
    }
}

