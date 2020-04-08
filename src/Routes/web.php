<?php
/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */


use Lebenlabs\SimpleCMS\Http\Controllers\ArchivosController;
use Lebenlabs\SimpleCMS\Http\Controllers\CategoriasController;
use Lebenlabs\SimpleCMS\Http\Controllers\PublicacionesController;


Route::group(['prefix' => 'simplecms', 'as' => 'simplecms.'], function () {

    // Publicaciones
    Route::resource(
        'publicaciones',
        PublicacionesController::class,
        [
            'only' => ['index', 'edit', 'update', 'create', 'store', 'destroy']
        ]
    );

    // Categorias
    Route::resource(
        'categorias',
        CategoriasController::class,
        [
            'only' => ['index', 'edit', 'update', 'create', 'store', 'destroy']
        ]
    );

    // Archivos
    Route::resource(
        'archivos',
        ArchivosController::class,
        [
            'only' => ['create', 'store', 'destroy']
        ]
    );

    Route::put('archivos/{id}/exclusivo', [ArchivosController::class, 'updateExclusivo'])->name('archivos.updateExclusivo');

});

// El prefijo esta para evitar problemas en CBSF y permitir funcionar ambos SimpleCMS
Route::group(['prefix' => 'simplecms/public', 'as' => 'simplecms.public.'], function () {

    Route::get('archivos/{id}/exclusivo', [ArchivosController::class, 'show'])->name('archivos.show');

});


