<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'voteable_id',
        'voteable_type',
        'value',
        ];

    public function voteable()
    {
        return $this->morphTo();
    }
}