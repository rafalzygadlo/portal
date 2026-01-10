<?php

namespace App\Models\Article;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'article_categories';
    protected $fillable = [
        'name',
        'slug',
    ];
    
}
