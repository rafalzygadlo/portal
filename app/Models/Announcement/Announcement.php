<?php

namespace App\Models\Announcement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Announcement\AnnouncementCategory;

class Announcement extends Model
{
    use HasFactory;

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
        return $this->belongsTo(AnnouncementCategory::class, 'announcement_category_id');
    }
}
