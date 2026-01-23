<?php

namespace App\Models\Announcement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Announcement\Category;
use App\Traits\Voteable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Announcement extends Model implements HasMedia
{
    use HasFactory, Voteable, InteractsWithMedia;

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
