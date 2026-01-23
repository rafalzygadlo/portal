<?php

namespace App\Models\Offer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Voteable;

class Category extends Model
{
    use HasFactory;

    protected $table = 'offer_categories';

    protected $fillable = [
        'name',
        'slug',
    ];

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }
}
