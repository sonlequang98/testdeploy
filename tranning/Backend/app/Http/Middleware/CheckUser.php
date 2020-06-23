<?php

namespace Backend\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Backend\Enums\UserRole;
use Frontend\User;

class CheckUser
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
        if (!auth('api')->user()) {
            return response()->json('Not Allow');
        } else if (auth('api')->user()->role_id == UserRole::Member) {
            return response()->json('Not Allow');
        }
        return $next($request);
    }
}
