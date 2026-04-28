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
   protected function redirectTo($request)
{
    if ($request->expectsJson()) {
        return null;
    }

    $host = $request->getHost();
    $domain = config('app.business_domain'); // np. 'localhost' lub 'mojastrona.pl'

    // Sprawdzamy czy to subdomena (zakładając że $domain nie ma kropki na początku)
    if (Str::endsWith($host, '.' . $domain)) {
        $subdomain = Str::before($host, '.' . $domain);

        // Wykluczamy subdomenę 'app' (główną aplikację)
        if ($subdomain !== 'app') {
            return route('business.login', ['subdomain' => $subdomain]);
        }
    }

    // Domyślne logowanie dla domeny głównej lub subdomeny 'app'
    return route('login');
}
}
