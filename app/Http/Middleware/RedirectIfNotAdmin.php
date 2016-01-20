<?php

namespace ECEPharmacyTree\Http\Middleware;

use Closure;

class RedirectIfNotAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if( !$request->user()->isSuperAdmin() ){
            return redirect("/");
        }

        return $next($request);
    }
}
