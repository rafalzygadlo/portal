<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;
use App\Models\User;
use App\Traits\Voteable;
use App\Traits\Favoritable;

class Todo extends Model
{
    use HasFactory;
    use Voteable;
    use Favoritable;

    protected $fillable = 
    [
        'user_id', 
        'title', 
        'description', 
        'slug',
        'status'
    ];

    public function getStatusColor()
    {
        return match($this->status) {
            'pending'   => 'warning', // Żółty - czeka
            'planned'   => 'info',    // Niebieski - zaplanowane
            'completed' => 'success', // Zielony - zrobione
            default     => 'secondary'
        };
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
