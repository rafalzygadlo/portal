<?php

namespace App\Models;

use App\Traits\Voteable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Business extends Model
{
    use HasFactory, Voteable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'subdomain',
        'description',
        'address',
        'phone',
        'website',
        'latitude',
        'longitude',
        'business_hours',
        'booking_slot_duration',
        'is_approved',
    ];

    protected $casts = [
        'business_hours' => 'array',
        'is_approved' => 'boolean',
    ];

    /**
     * Get the user that owns the business.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The categories that belong to the business.
     */
    public function categories()
    {
        return $this->morphToMany(\App\Models\Category::class, 'categorizable');
    }

    /**
     * Get all comments for the business.
     */
    public function comments()
    {
        return $this->morphMany(\App\Models\Comment::class, 'commentable');
    }

    /**
     * Pracownicy biznesu.
     */
    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'business_employees',
            'business_id',
            'user_id'
        )->withPivot('role', 'is_active')->withTimestamps();
    }

    /**
     * Usługi rezerwacji oferowane przez biznes.
     */
    public function services(): HasMany
    {
        return $this->hasMany(ReservationService::class);
    }

    /**
     * Rezerwacje dla biznesu.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Przesunięcie godzin pracy dla danego dnia (0 = poniedziałek).
     */
    public function getBusinessHours(): array
    {
        return $this->business_hours ?? $this->getDefaultBusinessHours();
    }

    /**
     * Domyślne godziny pracy.
     */
    public function getDefaultBusinessHours(): array
    {
        return [
            'mon' => ['open' => '09:00', 'close' => '17:00', 'closed' => false],
            'tue' => ['open' => '09:00', 'close' => '17:00', 'closed' => false],
            'wed' => ['open' => '09:00', 'close' => '17:00', 'closed' => false],
            'thu' => ['open' => '09:00', 'close' => '17:00', 'closed' => false],
            'fri' => ['open' => '09:00', 'close' => '17:00', 'closed' => false],
            'sat' => ['open' => '10:00', 'close' => '14:00', 'closed' => false],
            'sun' => ['closed' => true],
        ];
    }
}
