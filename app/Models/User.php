<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use App\Models\Business;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'avatar',
        'password',
        'user_type',
        'subdomain',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
    
    public function businesses(): BelongsToMany
    {
        return $this->belongsToMany(Business::class, 'business_user');
    }

    public function ownedBusinesses(): BelongsToMany
    {
        return $this->businesses()->wherePivot('owner', true);
    }

    public function worksBusiness(Business $business): bool
    {
        return $this->businesses()->where('business_id', $business->id)->exists();
    }
    
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

}
