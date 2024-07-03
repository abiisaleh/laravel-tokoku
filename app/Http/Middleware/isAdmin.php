<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //kalau user mau ke panel selain logout, profile dan promp email
        if (!in_array(url()->current(), [
            filament()->getLoginUrl(),
            filament()->getRegistrationUrl(),
            filament()->getLogoutUrl(),
            filament()->getProfileUrl()
        ])) {
            if (auth()->id() != 1)
                return redirect('/');
        }

        return $next($request);
    }
}
