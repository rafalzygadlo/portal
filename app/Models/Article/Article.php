<?php

namespace App\Models\Article;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Article\Report;
use App\Models\Article\Category;
use App\Models\User;
use App\Models\Comment;
use App\Models\Vote;

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
        //return $this->hasMany(Vote::class);
        return $this->morphMany(Vote::class, 'voteable');
    }   
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }   
    public function upvotes()
    {
        return $this->morphMany(Vote::class, 'voteable')->where('value', 1);
    }

    public function downvotes()
    {
        return $this->morphMany(Vote::class, 'voteable')->where('value', -1);
    }

     

    public function getScore()
    {
        $totalVotes = $this->votes()->count();

        if ($totalVotes === 0) {
            return 0;
        }

        // (Upvotes - Downvotes) / Total votes
        return $this->votes()->sum('value');
    }

   
}
