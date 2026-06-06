<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = ['user_id', 'commentable_id', 'commentable_type', 'parent_id', 'content', 'deletion_reason'];

    public function delete($reason = null)
    {
        if ($reason) {
            $this->update(['deletion_reason' => $reason]);
        }

        return parent::delete();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
