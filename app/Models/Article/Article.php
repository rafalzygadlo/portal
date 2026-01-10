<?php

namespace App\Models\Article;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Article\Report;
use App\Models\Article\Category;
use App\Models\User;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'content',
        'image_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function getRankAttribute()
    {
        $voteCount = $this->votes()->count();
        return self::withCount('votes')->pluck('votes_count')->filter(function ($count) use ($voteCount) {
            return $count > $voteCount;
        })->count() + 1;
    }
}
