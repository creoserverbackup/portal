<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson() && empty($request->header('webshop'))) {
            return abort(401);
        }
    }

    public function handle($request, Closure $next, ...$guards)
    {
        if ($request->header('webshop')) {
            return $next($request);
        } else {
            return parent::handle($request, $next);
        }
    }


}
