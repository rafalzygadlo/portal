<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'company_id',
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
     * The company that owns this service.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Resources that can provide this service.
     */
    public function resources(): BelongsToMany
    {
        return $this->belongsToMany(Resource::class, 'resource_service');
    }

    /**
     * Reservations for this service.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
