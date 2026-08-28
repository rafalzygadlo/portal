<?php

namespace App\Models;

use App\Models\Article;
use App\Models\Business;
use App\Models\Offer;
use App\Models\Todo;
use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    protected $table = 'feed';

    protected $primaryKey = 'item_id';

    public $incrementing = false;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'item_id' => 'integer',
            'user_id' => 'integer',
            'is_promoted' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    public function getItemAttribute(): ?Model
    {
        $modelConfig = [
            'article' => [Article::class, ['categories', 'images']],
            'todo' => [Todo::class, []],
            'business' => [Business::class, ['categories', 'images']],
            'offer' => [Offer::class, ['categories', 'images']],
        ];

        if (! isset($modelConfig[$this->type])) {
            return null;
        }

        [$model, $relations] = $modelConfig[$this->type];

        return $model::with($relations)->find($this->item_id);
    }
}
