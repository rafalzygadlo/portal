<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Get all of the articles that are assigned this category.
     */
    public function articles()
    {
        return $this->morphedByMany(Article\Article::class, 'categorizable');
    }

    /**
     * Get all of the businesses that are assigned this category.
     */
    public function businesses()
    {
        return $this->morphedByMany(Business::class, 'categorizable');
    }
}
