<?php

namespace App\Models\Announcement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnouncementCategory extends Model
{
    use HasFactory;

    protected $table = 'announcement_categories';

    protected $fillable = [
        'name',
        'slug',
    ];

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }
}
