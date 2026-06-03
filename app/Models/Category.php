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

    /**
     * Get the breadcrumb path for the current category.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getBreadcrumbs(): \Illuminate\Support\Collection
    {
        $path = collect();
        $current = $this;
        while ($current) {
            $path->prepend($current);
            $current = $current->parent;
        }
        return $path;
    }

    /**
     * Pobiera ID podanej kategorii oraz wszystkich jej potomków (rekurencyjnie).
     * Optymalizacja: Wykorzystuje Recursive CTE, aby wykonać tylko jedno zapytanie SQL.
     *
     * @param int|string $parentId
     * @return array
     */
    public static function getAllChildrenIds($parentId): array
    {
        $results = \DB::select("
            WITH RECURSIVE descendants AS (
                SELECT id FROM categories WHERE id = ?
                UNION ALL
                SELECT c.id FROM categories c
                INNER JOIN descendants d ON c.parent_id = d.id
            )
            SELECT id FROM descendants
        ", [$parentId]);

        return array_map(fn($row) => (int) $row->id, $results);
    }
}
