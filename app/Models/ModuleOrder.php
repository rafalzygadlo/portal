<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModuleOrder extends Model
{
    protected $fillable = [
        'company_id',
        'amount',
        'billing_period',
        'status',
        'payment_provider',
        'payment_id',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ModuleOrderItem::class);
    }
}