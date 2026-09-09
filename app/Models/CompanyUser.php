<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CompanyUser extends Pivot
{
    protected $table = 'company_user';

    public $incrementing = true;

    protected $fillable = [
        'company_id',
        'user_id',
        'owner',
        'display_name',
        'working_hours',
        'unavailable_periods',
    ];

    protected $casts = [
        'owner' => 'boolean',
        'working_hours' => 'array',
        'unavailable_periods' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The services this employee is able to perform for the company.
     * Foreign/related pivot keys must be explicit: Pivot::getForeignKey() only
     * returns a value when set by the belongsToMany relation that created it.
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'company_user_service', 'company_user_id', 'service_id');
    }

    public function getDisplayNameAttribute(): string
    {
        return ($this->attributes['display_name'] ?? '') ?: $this->user->name;
    }

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
