<?php

namespace App\Traits;

use App\Models\Business;
use Illuminate\Support\Facades\Auth;

trait ResolvesCurrentBusiness
{
    protected function resolveCurrentBusiness(): Business
    {
        $user = Auth::user();

        if (! $user) {
            abort(403);
        }

        $query = $user->businesses();
        $subdomain = request()->route('subdomain');

        if ($subdomain) {
            return $query->where('subdomain', $subdomain)->firstOrFail();
        }

        return $query->firstOrFail();
    }
}
