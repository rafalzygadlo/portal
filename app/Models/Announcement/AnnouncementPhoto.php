<?php

namespace App\Models\Announcement;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnouncementPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'announcement_id',
        'path',
    ];

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }
}
