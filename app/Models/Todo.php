<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;
use App\Models\User;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'description', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function votes()
    {
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
