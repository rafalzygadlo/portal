<?php

/**
 * DEPRECATED - Booking module temporarily disabled
 * This model is part of the booking system that is not being developed in the current iteration.
 * DO NOT USE - Consider archiving or removing in future cleanup.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class BookingFlow extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'id',
        'company_id',
        'status',
        'data',
        'expires_at',
    ];

    protected $casts = [
        'data' => 'array',
        'expires_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
