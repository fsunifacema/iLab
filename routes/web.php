<?php

use App\Http\Controllers\EquipamentoController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\LaboratorioController;
use Illuminate\Support\Facades\Route;

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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');


    // rotas de maneira publica podendo ter visibilidade alteradas futuramente
    Route::get('laboratorios', [LaboratorioController::class, 'index']);
    Route::get('laboratorios/{laboratorio}', [LaboratorioController::class, 'show']);
    

    Route::get('insumos', [InsumoController::class, 'index']);
    Route::get('insumos/{insumo}', [InsumoController::class, 'show']);

    Route::get('equipamentos', [EquipamentoController::class, 'index']);
    Route::get('equipamentos/{equipamento}', [EquipamentoController::class, 'show']);

    // controlando acesso de acordo com o nivel, neste caso apenas o tecnico nível(2) terá acesso as respectivas rotas
    Route::middleware('nivel:2,3,4')->group(function () {
        // tirando a index e a show ja que estão como publicas
        Route::resource('insumos', InsumoController::class)->except('index', 'show');
        Route::resource('laboratorios', LaboratorioController::class)->except('index', 'show');
        Route::resource('equipamentos', EquipamentoController::class)->except('index', 'show');
    });
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
