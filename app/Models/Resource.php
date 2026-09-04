<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resource extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'company_id',
        'name',
        'type',
        'hourly_rate',
        'user_id',
        'assigned_user_id',
        'is_active',
        'working_hours',
        'unavailable_periods',
    ];

    /**
     * The company that this resource belongs to.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * The services provided by this resource.
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class );
    }

    /**
     * The user associated with this resource, if any.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function bookings()
    {
        return $this->hasMany(ResourceBooking::class);
    }

    protected $casts = [
        'is_active' => 'boolean',
        'working_hours' => 'array',
        'unavailable_periods' => 'array',
        'hourly_rate' => 'decimal:2',
    ];

    public function getWorkingHours(): array
    {
        return $this->working_hours ?: $this->company->getCompanyHours();
    }

    public function isAvailableAt(Carbon $start, Carbon $end): bool
    {
        if ($start->toDateString() !== $end->toDateString() || $start->isPast()) {
            return false;
        }

        $hours = $this->getWorkingHours()[strtolower($start->format('D'))] ?? ['closed' => true];

        if (($hours['closed'] ?? false)
            || $start->format('H:i') < ($hours['open'] ?? '00:00')
            || $end->format('H:i') > ($hours['close'] ?? '00:00')) {
            return false;
        }

        foreach ($this->unavailable_periods ?? [] as $period) {
            $periodStart = Carbon::parse($period['start'])->startOfDay();
            $periodEnd = Carbon::parse($period['end'])->endOfDay();

            if ($start->lt($periodEnd) && $end->gt($periodStart)) {
                return false;
            }
        }

        return true;
    }
}
