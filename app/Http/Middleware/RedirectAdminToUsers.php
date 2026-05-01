<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectAdminToUsers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Se o usuário é Admin (nível 5), redireciona para a página de usuários
        if (auth()->check() && auth()->user()->nivel === User::NIVEL_ADMINISTRADOR) {
            // Se está tentando acessar a dashboard, redireciona para users
            if ($request->routeIs('dashboard')) {
                return redirect()->route('admin.users.index');
            }
            
            // Bloqueia acesso a outras páginas que não sejam admin
            if ($request->routeIs('solicitacoes.*', 'roteiros.*', 'insumos.*', 'equipamentos.*', 'laboratorios.*')) {
                abort(403, 'Admins não têm acesso a esse recurso.');
            }
        }

        return $next($request);
    }
}
