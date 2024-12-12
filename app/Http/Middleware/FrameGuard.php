<?php

namespace App\Http\Middleware;

use Closure;

class FrameGuard
{
    /**
     * Handle the given request and get the response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, Closure $next)
    {
        // $request->headers->remove('X-Frame-Options');
        // $request->headers->set('X-Frame-Options', '', true);
        $response = $next($request);

        // $response->headers->remove('X-Frame-Options');
        // $response->headers->set('X-Frame-Options', '', true);
        // $response->headers->set('X-XSS-Protection', '1; mode=block', false);
        // $response->headers->set('X-Content-Type-Options', 'nosniff', false);
        // // $response->headers->set('X-Frame-Options', '');
        // $response->headers->set('Content-Security-Policy', "frame-ancestors 'none'", false);

        return $response;
    }
}
