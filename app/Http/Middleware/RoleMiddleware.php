<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Kalau belum login sama sekali, usir ke login page
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Mengecek apa rolenya yg login
        if (in_array(Auth::user()->role, $roles)) {
            // kalo coock lanjut ke route berikutnya
            return $next($request);
        }

        // kalo ga cocok, nanti kasih pesan 403 Forbidden
        abort(403, 'Anda tidak punya akses ke halaman ini');
    }
}
