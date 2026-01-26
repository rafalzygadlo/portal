<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'reservation_service_id',
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
     * Biznes do którego należy rezerwacja.
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Usługa rezerwacji.
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(ReservationService::class, 'reservation_service_id');
    }

    /**
     * Użytkownik (jeśli zalogowany).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Sprawdzenie dostępności slotu.
     */
    public static function isTimeSlotAvailable(
        int $businessId,
        int $serviceId,
        string $startTime,
        string $endTime,
        ?int $excludeId = null
    ): bool {
        $query = self::where('business_id', $businessId)
            ->where('reservation_service_id', $serviceId)
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
