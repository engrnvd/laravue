<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // no need to verify email if they are logging in with microsoft teams account
        if ($request->user()->ms_account_updated) return $next($request);

        if ($request->fullUrl() != route('user.verification') &&
            (!$request->user() || !$request->user()->hasVerifiedEmail())) {
            throw new AuthorizationException('Unauthorized, your email address ' . $request->user()->email . ' is not verified.');
        }
        return $next($request);
    }
}
