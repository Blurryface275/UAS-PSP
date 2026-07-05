<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Pasang HTTP Security Headers ngikuti standar OWASP
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN'); // Mencegah clickjacking
        $response->headers->set('X-Content-Type-Options', 'nosniff'); // Mencegah browser menginterpretasikan file sebagai tipe lain
        $response->headers->set('X-XSS-Protection', '1; mode=block'); // Mencegah serangan XSS
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains'); // Mencegah serangan man-in-the-middle
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin'); // Mencegah kebocoran informasi

        // Content-Security-Policy (CSP) yang melindungi web tapi tetep ngijinin font dan bootstrap jsdlivr
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://use.fontawesome.com; " .
               "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://use.fontawesome.com https://fonts.googleapis.com; " .
               "font-src 'self' https://cdn.jsdelivr.net https://use.fontawesome.com https://fonts.gstatic.com data:; " .
               "img-src 'self' data: https:;";

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
