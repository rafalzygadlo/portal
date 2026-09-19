<?php

namespace App\Modules\Crm;

use App\Modules\Module;

class CrmModule extends Module
{
    public function slug(): string
    {
        return 'crm';
    }

    public function name(): string
    {
        return 'CRM';
    }

    public function description(): string
    {
        return 'Zarządzanie relacjami z klientami.';
    }

    public function icon(): string
    {
        return '📇';
    }

    public function price(): int
    {
        return 3900;
    }

    public function dependencies(): array
    {
        return [
            'ai',
            'payments',
        ];
    }
}