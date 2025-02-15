<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OnAttempt
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $exists = $request->user()
            ->student
            ->attempt()
            ->exists();

        return match ($exists) {
            true => response()->json([
                'message' => 'Kamu sedang mengerjakan kuis yang lain'
            ], 409),
            false => $next($request),
        };
    }
}
