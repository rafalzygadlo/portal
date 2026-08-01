<?php

namespace App\Support;

use Illuminate\Http\Request;

class Domain
{
    public static function businessDomain(): string
    {
        return (string) config('app.business_domain');
    }

    public static function subdomainFromRequest(?Request $request = null): ?string
    {
        $request ??= request();
        $domain = self::businessDomain();

        if ($domain === '') {
            return null;
        }

        $host = $request->getHost();

        if ($host === $domain) {
            return null;
        }

        if (! str_ends_with($host, '.'.$domain)) {
            return null;
        }

        return str($host)->before('.'.$domain)->toString();
    }

    public static function isSubdomainRequest(?Request $request = null): bool
    {
        return self::subdomainFromRequest($request) !== null;
    }

    public static function defaultRedirectUrl(?Request $request = null): string
    {
        $subdomain = self::subdomainFromRequest($request);

        if ($subdomain) {
            return route('business.domain', ['subdomain' => $subdomain]);
        }

        return route('main.index');
    }
}
