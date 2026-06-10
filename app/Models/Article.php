<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Report;
use App\Models\Category;
use App\Models\User;
use App\Models\Comment;
use App\Traits\Voteable;
use App\Traits\Favoritable;
use App\Models\Image;

class Article extends Model
{
    use HasFactory;
    use Voteable;
    use Favoritable;

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


        public function reports()
        {
            return $this->hasMany(Report::class);
        }

        public function comments()
        {
            return $this->morphMany(Comment::class, 'commentable');
        }

        public function categories()
        {
            return $this->morphToMany(Category::class, 'categoryable');
        }

        public function images()
        {
            return $this->morphMany(Image::class, 'imageable');
        }
}
