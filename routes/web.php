<?php

use App\Http\Controllers\EquipamentoController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\LaboratorioController;
use App\Http\Controllers\ReservaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoteiroController;
use App\Http\Controllers\SolicitacaoController; // Controller de Fluxo

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});


/*
 * Rota para teste roteiro-pratica (PDF)
 */
Route::get('/roteiro-pratica/{roteiro}', [RoteiroController::class, 'pdf'])->name('roteiros.pdf');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // =====================================================================
    // ROTAS DE FLUXO DE TRABALHO (SOLICITAÇÕES)
    // =====================================================================
    Route::get('/api/roteiros/eventos', [RoteiroController::class, 'getRoteirosAsEvents'])->name('api.roteiros.eventos');    // Rota Principal: Caixa de entrada de solicitações pendentes (filtradas por nível)
    
    // Rota Principal: Caixa de entrada de solicitações pendentes (filtradas por nível)
    Route::get('/solicitacoes', [SolicitacaoController::class, 'indexSolicitacoes'])->name('solicitacoes.index');
    
    // Rotas de CRUD/Ações da Solicitação
    Route::get('/solicitacoes/criar', [SolicitacaoController::class, 'createSolicitacao'])->name('solicitacoes.create');
    Route::post('/solicitacoes', [SolicitacaoController::class, 'storeSolicitacao'])->name('solicitacoes.store');
    Route::get('/solicitacoes/{solicitacao}/editar', [SolicitacaoController::class, 'edit'])->name('solicitacoes.edit');
    // Rota de Processamento de Fluxo (Aprovar/Rejeitar)
    Route::put('/solicitacoes/{solicitacao}', [SolicitacaoController::class, 'update'])->name('solicitacoes.update');
    
    // A ROTA API PARA O CALENDÁRIO FOI REMOVIDA
    
    // =====================================================================
    // ROTAS RESOURCE PADRÃO (Administração / CRUD do Documento)
    // =====================================================================

    // rotas de maneira publica podendo ter visibilidade alteradas futuramente
    Route::resource('laboratorios', LaboratorioController::class);
    Route::resource('insumos', InsumoController::class);
    Route::resource('equipamentos', EquipamentoController::class);
    Route::resource('roteiros', RoteiroController::class);
});


/*  PADRÃO DE ROTAS RESOURCE
VERB 	    URI	                    ACTION	    ROUTE NAME
GET	        /photos	                index	    photos.index
GET	        /photos/create	        create	    photos.create
POST	    /photos	                store	    photos.store
GET	        /photos/{photo}	        show	    photos.show
GET	        /photos/{photo}/edit	edit	    photos.edit
PUT/PATCH	/photos/{photo}	        update	    photos.update
DELETE	    /photos/{photo}	        destroy	    photos.destroy
*/
