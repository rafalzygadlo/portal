<?php

namespace App\Modules;

use App\Models\Company;
use App\Modules\Crm\CrmModule;
use App\Modules\Parking\ParkingModule;

class ModuleManager
{
    public function all(): array
    {
        return [
            ParkingModule::class,
            CrmModule::class,
        ];
    }

    public function instances(): array
    {
        $instances = [];

        foreach ($this->all() as $module) {
            $instances[] = app($module);
        }

        return $instances;
    }

    public function find(string $slug): ?Module
    {
        foreach ($this->instances() as $module) {
            if ($module->slug() === $slug) {
                return $module;
            }
        }

        return null;
    }

    public function enabledFor(Company $company): array
    {
        return array_filter(
            $this->instances(),
            fn(Module $module) => $company->hasModule($module->slug())
        );
    }

    public function isEnabled(Company $company, string $slug): bool
    {
        return $company->hasModule($slug);
    }
}