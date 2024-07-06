<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $userType): Response
    {
        logger('check user type');
        $client = auth()->guard('client')->user();

        if (empty($client)) return abort(401);

        if ($client->type !== $userType) return abort(401);


        return $next($request);
    }
}
