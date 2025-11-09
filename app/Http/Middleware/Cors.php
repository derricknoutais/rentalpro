<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{
    /**
     * Handle an incoming request and take care of preflight checks.
     */
    public function handle(Request $request, Closure $next)
    {
        $config = config('cors', []);
        $allowedOrigins = $config['allowed_origins'] ?? ['*'];
        $supportsCredentials = (bool) ($config['supports_credentials'] ?? false);
        $originHeader = $request->headers->get('Origin');

        $origin = $this->resolveOrigin($originHeader, $allowedOrigins, $supportsCredentials);

        $headers = [
            'Access-Control-Allow-Origin' => $origin,
            'Access-Control-Allow-Methods' => $this->formatHeaderValue($config['allowed_methods'] ?? ['*']),
            'Access-Control-Allow-Headers' => $this->formatHeaderValue($config['allowed_headers'] ?? ['*']),
        ];

        if (! empty($config['exposed_headers'] ?? [])) {
            $headers['Access-Control-Expose-Headers'] = $this->formatHeaderValue($config['exposed_headers']);
        }

        if (! empty($config['max_age'] ?? 0)) {
            $headers['Access-Control-Max-Age'] = (string) $config['max_age'];
        }

        if ($supportsCredentials) {
            $headers['Access-Control-Allow-Credentials'] = 'true';
        }

        if ($request->isMethod('OPTIONS')) {
            return response('', 204, $headers);
        }

        $response = $next($request);

        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        return $response;
    }

    protected function formatHeaderValue(array $values): string
    {
        return $values === ['*'] ? '*' : implode(', ', $values);
    }

    protected function resolveOrigin(?string $originHeader, array $allowedOrigins, bool $supportsCredentials): string
    {
        if ($originHeader && in_array($originHeader, $allowedOrigins, true)) {
            return $originHeader;
        }

        if (in_array('*', $allowedOrigins, true) && ! $supportsCredentials) {
            return '*';
        }

        if ($originHeader && in_array('*', $allowedOrigins, true)) {
            return $originHeader;
        }

        return $allowedOrigins[0] ?? '*';
    }
}
