<?php

namespace App\Traits;

use App\Models\Company;
use Illuminate\Support\Facades\Auth;

trait ResolvesCurrentCompany
{
    protected function resolveCurrentCompany(): Company
    {
        $query = Company::query();
        $subdomain = request()->route('subdomain');

        if ($subdomain) 
        {
            return $query->where('subdomain', $subdomain)->firstOrFail();
        }

        return $query->firstOrFail();
    }
}
