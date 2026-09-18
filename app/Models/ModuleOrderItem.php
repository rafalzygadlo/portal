<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleOrderItem extends Model
{
    protected $fillable = [
        'module_order_id',
        'module',
        'amount',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(ModuleOrder::class);
    }
}