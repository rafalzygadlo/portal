<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
        'description',
        'price',
        'duration',
        'buffer',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Biznes do którego należy ta usługa.
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Zasoby, które mogą świadczyć tę usługę.
     */
    public function resources(): BelongsToMany
    {
        return $this->belongsToMany(Resource::class, 'resource_service');
    }

    /**
     * Rezerwacje dla tej usługi.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
