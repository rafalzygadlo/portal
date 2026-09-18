<?php

namespace App\Modules\Parking;

use App\Modules\Module;

class ParkingModule extends Module
{
    public function slug(): string
    {
        return 'parking';
    }

    public function name(): string
    {
        return 'Parking';
    }

    public function description(): string
    {
        return 'Zarządzanie miejscami parkingowymi i rezerwacjami.';
    }

    public function icon(): string
    {
        return '🅿️';
    }

    public function price(): int
    {
        return 2900;
    }

    public function dependencies(): array
    {
        return [
            'reservations',
            'payments',
        ];
    }
}