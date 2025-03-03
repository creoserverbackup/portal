<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiShop
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('webshop')) {
            return $next($request);
        } else {
            return response(['message' => 'Access denied']);
        }
    }
}
