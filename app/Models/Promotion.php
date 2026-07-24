<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'promotable_id',
        'promotable_type',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * en: Get the parent model (Offer, Article, etc.) that is being promoted.
     * de: Hole das übergeordnete Modell (Offer, Article, etc.), das beworben wird.
     */
    public function promotable(): MorphTo
    {
        return $this->morphTo();
    }
}
