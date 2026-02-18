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
     * The businesses that the user belongs to.
     */
    public function businesses(): BelongsToMany
    {
        return $this->belongsToMany(Business::class);
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
     * Rezerwacje użytkownika.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Sprawdź, czy użytkownik pracuje w biznesie.
     */
    public function worksBusiness(Business $business): bool
    {
        return $this->employeeBusinesses()->where('business_id', $business->id)->exists();
    }
}
