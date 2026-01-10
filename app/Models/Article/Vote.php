<?php

namespace App\Models\Article;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $table = 'article_votes';
    protected $fillable = ['user_id', 'article_id'];
}