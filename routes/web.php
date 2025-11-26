<?php

use App\Http\Controllers\EquipamentoController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\LaboratorioController;
use App\Http\Controllers\ReservaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoteiroController;
use App\Http\Controllers\SolicitacaoController;
use App\Http\Controllers\DashboardController; // <<<< ADICIONADO: Importação do DashboardController

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});

// Desabilita registro público: redireciona qualquer tentativa de /register para login
Route::match(['get', 'post'], '/register', function () {
    return redirect('/login');
})->name('register');


/*
 * * Rota para teste roteiro-pratica
 */
Route::get('/roteiro-pratica/{roteiro}', [RoteiroController::class, 'pdf'])->name('roteiros.pdf');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // ROTA CORRIGIDA: Aponta para o DashboardController@index, que carrega $notifications.
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/api/roteiros/eventos', [RoteiroController::class, 'getRoteirosAsEvents'])->name('api.roteiros.eventos');

    // rotas de maneira publica podendo ter visibilidade alteradas futuramente
    Route::resource('laboratorios', LaboratorioController::class);
    Route::resource('insumos', InsumoController::class);
    Route::resource('equipamentos', EquipamentoController::class);
    Route::resource('roteiros', RoteiroController::class);
    Route::resource('solicitacoes', SolicitacaoController::class);

    // Coordenador de Curso (nível 3)
    Route::post('/solicitacoes/{id}/aprovar-coordenador-curso', [SolicitacaoController::class, 'aprovarCoordenadorCurso'])
        ->name('solicitacoes.aprovar.coordenador.curso')
        ->middleware('nivel:3');

    Route::post('/solicitacoes/{id}/recusar-coordenador-curso', [SolicitacaoController::class, 'recusarCoordenadorCurso'])
        ->name('solicitacoes.recusar.coordenador.curso')
        ->middleware('nivel:3');

    // Coordenador de Laboratório (nível 4)
    Route::post('/solicitacoes/{id}/aprovar-coordenador-lab', [SolicitacaoController::class, 'aprovarCoordenadorLab'])
        ->name('solicitacoes.aprovar.coordenador.lab')
        ->middleware('nivel:4');

    Route::post('/solicitacoes/{id}/recusar-coordenador-lab', [SolicitacaoController::class, 'recusarCoordenadorLab'])
        ->name('solicitacoes.recusar.coordenador.lab')
        ->middleware('nivel:4');

    // Rotas para gerenciamento interno de usuários (apenas nível 5)
    Route::get('/admin/users/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])
        ->name('admin.users.create')
        ->middleware('nivel:5');

    Route::post('/admin/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])
        ->name('admin.users.store')
        ->middleware('nivel:5');

    // Lista, editar, atualizar e excluir usuários (admin interno)
    Route::get('/admin/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])
        ->name('admin.users.index')
        ->middleware('nivel:5');

    Route::get('/admin/users/{user}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])
        ->name('admin.users.edit')
        ->middleware('nivel:5');

    Route::put('/admin/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])
        ->name('admin.users.update')
        ->middleware('nivel:5');

    Route::delete('/admin/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])
        ->name('admin.users.destroy')
        ->middleware('nivel:5');
});
