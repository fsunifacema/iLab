<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SolicitacaoController;
use App\Http\Controllers\RoteiroController;
use App\Http\Controllers\LaboratorioController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\EquipamentoController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::match(['get', 'post'], '/register', function () {
    return redirect('/login');
})->name('register');

Route::get('/roteiro-pratica/{roteiro}', [RoteiroController::class, 'pdf'])->name('roteiros.pdf');

// ==============================================================================
// GRUPO DE ROTAS AUTENTICADAS
// ==============================================================================
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // 🚫 ÁREA DE USUÁRIOS (Bloqueada para Admin via 'redirect.admin')
    Route::middleware('redirect.admin')->group(function () {
        
        // 1. DASHBOARD (Todos acessam)
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard')
            ->middleware('nivel:1,2,3,4');


        // 2. GRUPO: LABORATÓRIOS
        // Quem acessa: Técnico (2), Coords (3, 4)
        // Bloqueado para: Professor (1)
        Route::middleware('nivel:2,3,4')->group(function () {
            Route::resource('laboratorios', LaboratorioController::class);
        });


        // 3. GRUPO: INSUMOS, EQUIPAMENTOS, ROTEIROS
        // Quem acessa: Todos (1, 2, 3, 4)
        // (Removi solicitações daqui)
        Route::middleware('nivel:1,2,3,4')->group(function () {
            Route::resource('insumos', InsumoController::class);
            Route::resource('equipamentos', EquipamentoController::class);
            Route::resource('roteiros', RoteiroController::class);

            // Rotas auxiliares de Roteiros
            Route::get('/api/roteiros/eventos', [RoteiroController::class, 'getRoteirosAsEvents'])->name('api.roteiros.eventos');
            Route::get('/roteiros/criar/{codigo_solicitacao}', [RoteiroController::class, 'createFromSolicitacao'])->name('roteiros.criar-solicitacao');
            Route::post('/roteiros/armazenar-solicitacao', [RoteiroController::class, 'storeFromSolicitacao'])->name('roteiros.armazenar-solicitacao');
        });


        // 4. GRUPO: SOLICITAÇÕES (AQUI ESTÁ A CORREÇÃO)
        // Quem acessa: Professor (1), Coords (3, 4)
        // Bloqueado para: Técnico (2) -> Note que o número 2 NÃO está na lista abaixo
        Route::middleware('nivel:1,3,4')->group(function () {
            Route::resource('solicitacoes', SolicitacaoController::class);
        });


        // 5. ROTAS DE AÇÕES ESPECÍFICAS
        
        // Estoque (Só Técnico - Nível 2)
        Route::get('insumos/{insumo}/estoque', [InsumoController::class, 'estoque'])
            ->name('insumos.estoque')
            ->middleware('nivel:2');

        // Aprovações (Coordenadores)
        Route::post('/solicitacoes/{id}/aprovar-coordenador-curso', [SolicitacaoController::class, 'aprovarCoordenadorCurso'])
            ->name('solicitacoes.aprovar.coordenador.curso')->middleware('nivel:3');
        Route::post('/solicitacoes/{id}/recusar-coordenador-curso', [SolicitacaoController::class, 'recusarCoordenadorCurso'])
            ->name('solicitacoes.recusar.coordenador.curso')->middleware('nivel:3');

        Route::post('/solicitacoes/{id}/aprovar-coordenador-lab', [SolicitacaoController::class, 'aprovarCoordenadorLab'])
            ->name('solicitacoes.aprovar.coordenador.lab')->middleware('nivel:4');
        Route::post('/solicitacoes/{id}/recusar-coordenador-lab', [SolicitacaoController::class, 'recusarCoordenadorLab'])
            ->name('solicitacoes.recusar.coordenador.lab')->middleware('nivel:4');
    });


    // 🛡️ ÁREA ADMIN (Nível 5)
    Route::prefix('admin')->name('admin.')->middleware('nivel:5')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

});