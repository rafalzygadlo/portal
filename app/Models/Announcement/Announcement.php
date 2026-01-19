<?php

namespace App\Models\Announcement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Announcement\Category;
use App\Traits\Voteable;

class Announcement extends Model
{
    use HasFactory, Voteable;

    protected $fillable = [
        'user_id',
        'announcement_category_id',
        'title',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'announcement_category_id');
    }
}
