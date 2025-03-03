<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
            'catalog/product',
            'cart/pay/request',
            '/cart/pay/request',
    ];

    public function handle($request, Closure $next)
    {
        return $next($request);
    }

}
