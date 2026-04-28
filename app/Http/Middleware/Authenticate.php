<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

        $host = $request->getHost();
        $domain = config('app.business_domain');

        if (Str::endsWith($host, $domain) && !Str::startsWith($host, 'app' . $domain)) {
            $subdomain = $request->route('subdomain') ?? Str::before($host, $domain);
            return route('business.login', ['subdomain' => $subdomain]);
        }

        return route('login');
    }
}
