<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Announcement\Category;
use App\Traits\Voteable;
use App\Traits\Favoritable;

class Announcement extends Model
{
    use HasFactory, Voteable, Favoritable;

    protected $fillable = [
        'user_id',
        'announcement_category_id',
        'title',
        'content',
        'price',
        'salary',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'announcement_category_id');
    }

    public function photos()
    {
        return $this->hasMany(AnnouncementPhoto::class);
    }
}
