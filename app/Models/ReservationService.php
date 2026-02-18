<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReservationService extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'resource_id',
        'name',
        'description',
        'price',
        'duration_minutes',
        'buffer_minutes',
        'is_active',
        'sort_order',
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
     * Zasób, który świadczy tę usługę.
     */
    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }

    /**
     * Rezerwacje dla tej usługi.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
