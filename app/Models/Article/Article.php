<?php

namespace App\Models\Article;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Article\Vote;
class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'image_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}
