<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'service_id',
        'user_id',
        'client_name',
        'client_email',
        'client_phone',
        'start_time',
        'end_time',
        'notes',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Business to which the reservation belongs.
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Service rezerwacji.
     */
    public function service(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
    }
    /**
     * User (if logged in).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check slot availability.
     */
    public static function isTimeSlotAvailable(
        int $businessId,
        int $serviceId,
        string $startTime,
        string $endTime,
        ?int $excludeId = null
    ): bool {
        
        if (Carbon::parse($startTime)->setTimezone('Europe/Warsaw')->isPast()) {
            return false;
        }

        $query = self::where('business_id', $businessId)
            ->where('service_id', $serviceId)
            ->where('status', '!=', 'cancelled')
            ->where(function ($q) use ($startTime, $endTime) {
                $q->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($subQ) use ($startTime, $endTime) {
                        $subQ->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return !$query->exists();
    }
}
