<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Saidqb\LaravelSupport\SQ;
use Saidqb\LaravelSupport\ResponseCode;
use Illuminate\Support\Facades\Route;

class ApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {

        if ($request->header('api-key') != config('api.api_key')) {
            return SQ::response([], ResponseCode::HTTP_UNAUTHORIZED, 'Unauthorized', 1);
        }

        return $next($request);
    }
}
