<?php

namespace Workdo\CustomerLogin\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Exists;

class CustomerLogin
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
        if (moduleIsActive('CustomerLogin')) {
            return redirect()->route('register');
        }
        return $next($request);
    }
}
