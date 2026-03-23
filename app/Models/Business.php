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
        'name',
        'subdomain',
        'description',
        'address',
        'phone',
        'business_hours',
    ];

    protected $casts = [
        'business_hours' => 'array',
        'is_approved' => 'boolean',
    ];

    /**
     * The owners that belong to the business.
     */
    public function owners()
    {
        return $this->belongsToMany(User::class)
                ->wherePivot('owner', true);
    }

    /**
     * Get the owner of the business.
     */
    public function getOwnerAttribute()
    {
        return $this->owners()->first();
    }

    /**
     * The users that belong to the business.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'business_user');
    }

    /**
     * The categories that belong to the business.
     */
    public function categories()
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }

    /**
     * Get all comments for the business.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Usługi rezerwacji oferowane przez biznes.
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Rezerwacje dla biznesu.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Zasoby biznesu.
     */
    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
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
