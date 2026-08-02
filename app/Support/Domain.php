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

        $candidates = array_filter([
            $request->getHost(),
            $request->getHttpHost(),
            $request->header('host'),
            $request->server->get('HTTP_HOST'),
        ]);

        $uri = $request->getUri();
        if (is_string($uri) && preg_match('#https?://([^/]+)#', $uri, $matches)) {
            $candidates[] = $matches[1];
        }

        foreach ($candidates as $host) {
            $host = (string) $host;

            if ($host === $domain) {
                continue;
            }

            if (! str_ends_with($host, '.'.$domain)) {
                continue;
            }

            return str($host)->before('.'.$domain)->toString();
        }

        return null;
    }

    public static function isSubdomainRequest(?Request $request = null): bool
    {
        return self::subdomainFromRequest($request) !== null;
    }

    public static function defaultRedirectUrl(?Request $request = null): string
    {
        $request ??= request();
        $subdomain = self::subdomainFromRequest($request);

        if ($subdomain) {
            $scheme = $request->getScheme();
            $host = $request->getHost();

            if (str_contains($host, '.')) {
                return $scheme.'://'.$host.'/';
            }

            return route('business.domain', ['subdomain' => $subdomain]);
        }

        return route('main.index');
    }
}
