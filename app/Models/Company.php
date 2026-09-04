<?php

namespace App\Models;

use App\Traits\Voteable;
use App\Traits\Favoritable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Company extends Model
{
    use HasFactory, Voteable, Favoritable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'subdomain',
        'description',
        'address',
        'phone',
        'company_hours',
    ];

    protected $casts = [
        'company_hours' => 'array',
        'is_approved' => 'boolean',
    ];

    /**
     * The user who owns the company.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The owners that belong to the company.
     */
    public function owners()
    {
        return $this->belongsToMany(User::class)
                ->wherePivot('owner', true);
    }

    /**
     * Get the owner of the company.
     */
    public function getOwnerAttribute()
    {
        return $this->owners()->first();
    }

    /**
     * The users that belong to the company.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'company_user');
    }

    /**
     * The categories that belong to the company.
     */
    public function categories()
    {
        return $this->morphToMany(Category::class, 'categoryable');
    }
    
    
    /**
     * Get all images for the company.
     */
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }   
    
    /**
     * Get all comments for the company.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Booking services offered by the company.
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Reservations for the company.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Company resources.
     */
    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }

    public function resourceBookings(): HasMany
    {
        return $this->hasMany(ResourceBooking::class);
    }

    /**
     * Shift of company hours for a given day (0 = Monday).
     */
    public function getCompanyHours(): array
    {
        return $this->company_hours ?? $this->getDefaultCompanyHours();
    }

    /**
     * Default opening hours.
     */
    public function getDefaultCompanyHours(): array
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
