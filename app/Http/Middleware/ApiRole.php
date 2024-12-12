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

class ApiRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        // dd($request->user());
        if ($request->user()->role?->id != 1) {
            if ($request->user()->hasPermission(Route::currentRouteName()) === false) {
                return SQ::response([], ResponseCode::HTTP_FORBIDDEN, ResponseCode::HTTP_FORBIDDEN_MESSAGE, 1);
            }
        }

        return $next($request);
    }
}
