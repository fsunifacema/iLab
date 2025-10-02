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
        // Verifica se o usuário está autenticado
        if (!auth()->check()) {
            // Redireciona para login ou retorna erro 403 se o usuário não estiver autenticado
            abort(403, 'Acesso negado: usuário não autenticado.');
        }

        // Recupera o nível do usuário autenticado
        $nivelUsuario = auth()->user()->nivel;

        // Verifica se o nível do usuário está entre os níveis permitidos
        if (!in_array($nivelUsuario, $niveis)) {
            // Bloqueia o acesso com erro 403
            abort(403, 'Acesso negado: nível de permissão insuficiente.');
        }

        // Permite o acesso à rota
        return $next($request);
    }
}
