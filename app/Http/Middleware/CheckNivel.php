<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckNivel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$niveis)
    {
        if (! in_array(auth()->user()->nivel, $niveis)) {
            abort(403, 'Acesso Negado');
        }

        return $next($request);
    }
}
