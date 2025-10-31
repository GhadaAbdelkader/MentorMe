<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->role !== 'admin') {

            Log::warning("Unauthorized access attempt by user ID: {$user->id}");
            abort(403, 'You are not authorized to access this area.');
        }

        return $next($request);
    }
}
