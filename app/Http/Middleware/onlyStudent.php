<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class onlyStudent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            if ($request->user()->student()->exists()) {
                return $next($request);
            } else {
                return redirect('/admin/dashboard');
            }
        } else {
            return redirect('/');
        }
    }
}
