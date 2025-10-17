<?php

use App\Http\Controllers\EquipamentoController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\LaboratorioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoteiroController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/* Route::get('/', function () {
    return view('welcome');
}); */

Route::get('/', function () {
    return redirect('/login');
});


/*
 * * Rota para teste roteiro-pratica
 */
Route::get('/roteiro-pratica', function () {
    // Dados para preencher na view
    return view('pdf.roteiro-pratica', [
        'curso' => 'Curso Exemplo',
        'unidade' => 'Unidade Exemplo',
        'professor' => 'Professor Exemplo',
        'periodo' => '2025/1',
        'quantidade_alunos' => 20,
        'data' => '20/10/2025',
        'horario' => '08:00',
        'carga_horaria' => '2h',
        'numero_praticas' => 1,
        'laboratorio' => 'Laboratório 101',
        'conteudo' => 'Conteúdo da aula prática...',
        'tema' => 'Tema da aula prática...',
        'objetivos' => 'Objetivos da aula prática...',
        'metodologia' => 'Metodologia da aula prática...',
        'recursos' => 'Recursos utilizados...',
        'avaliacao' => 'Avaliação...',
        'observacoes' => 'Observações...',
        'data_assinatura' => '20 de Outubro de 2025',
    ]);
});
/*

*/

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');


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
