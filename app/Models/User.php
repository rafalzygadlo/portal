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
        'current_business_id',
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


    /**
     * Głosy oddane przez użytkownika na artykuły.
     */
    public function articleVotes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * Powiadomienia użytkownika.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Biznesy, którymi zarządza użytkownik.
     */
    public function ownedBusinesses(): HasMany
    {
        return $this->hasMany(Business::class);
    }

    /**
     * Biznesy, w których użytkownik pracuje.
     */
    public function employeeBusinesses(): BelongsToMany
    {
        return $this->belongsToMany(
            Business::class,
            'business_employees',
            'user_id',
            'business_id'
        )->withPivot('role', 'is_active')->withTimestamps();
    }

    /**
     * Aktualnie wybrany biznes.
     */
    public function currentBusiness_old(): BelongsTo
    {
        return $this->belongsTo(Business::class, 'current_business_id');
    }

    /**
     * Rezerwacje użytkownika.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Sprawdź, czy użytkownik jest właścicielem biznesu.
     */
    public function isBusinessOwner(Business $business): bool
    {
        return $this->id === $business->user_id;
    }

    /**
     * Sprawdź, czy użytkownik pracuje w biznesie.
     */
    public function worksBusiness(Business $business): bool
    {
        return $this->employeeBusinesses()->where('business_id', $business->id)->exists();
    }
}
