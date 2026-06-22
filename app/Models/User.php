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
use App\Models\Offer;
use App\Models\Article;
use App\Models\Poll\Poll;
use App\Models\Comment;

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


    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function todos(): HasMany
    {
        return $this->hasMany(Todo::class);
    }   
    
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function polls(): HasMany
    {
        return $this->hasMany(Poll::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

}
