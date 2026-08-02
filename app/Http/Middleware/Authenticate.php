<?php

namespace App\Http\Middleware;

use App\Support\Domain;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        $subdomain = $request->route('subdomain') ?? Domain::subdomainFromRequest($request);

        if ($subdomain) {
            $domain = Domain::businessDomain();
            $host = $subdomain.'.'.$domain;

            return $request->getScheme().'://'.$host.'/login';
        }

        return route('login');
    }
}
