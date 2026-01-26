<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BusinessEmployee extends Pivot
{
    protected $table = 'business_employees';

    protected $fillable = [
        'business_id',
        'user_id',
        'role',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
