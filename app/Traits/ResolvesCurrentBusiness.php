<?php

namespace App\Traits;

use App\Models\Business;
use Illuminate\Support\Facades\Auth;

trait ResolvesCurrentBusiness
{
    protected function resolveCurrentBusiness(): Business
    {
        $query = Business::query();
        $subdomain = request()->route('subdomain');

        if ($subdomain) 
        {
            return $query->where('subdomain', $subdomain)->firstOrFail();
        }

        return $query->firstOrFail();
    }
}
