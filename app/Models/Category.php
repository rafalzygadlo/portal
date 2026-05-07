<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Offer\Offer;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'slug',
        'parent_id',
    ];

    public function categoryable()
    {
        return $this->morphTo();
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get all of the articles that are assigned this category.
     */
    public function articles()
    {
        return $this->morphedByMany(Article::class, 'categoryable');
    }

    /**
     * Get all of the businesses that are assigned this category.
     */
    public function businesses()
    {
        return $this->morphedByMany(Business::class, 'categoryable');
    }

    /**
     * Get all of the offers that are assigned this category.
     */
    public function offers()
    {
        return $this->morphedByMany(Offer::class, 'categoryable');
    }
}
