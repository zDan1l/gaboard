<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        $allowedRoles = explode(',', $roles);

        if (!$user->role || !in_array($user->role->slug, $allowedRoles)) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses untuk fitur ini.');
        }

        return $next($request);
    }
}
