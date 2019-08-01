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
use Lebenlabs\SimpleCMS\Http\Controllers\ImagenesController;
use Lebenlabs\SimpleCMS\Http\Controllers\MenuItemsController;
use Lebenlabs\SimpleCMS\Http\Controllers\MenusController;
use Lebenlabs\SimpleCMS\Http\Controllers\PublicacionesController;


Route::group(['prefix' => 'simplecms', 'as' => 'simplecms.'], function () {

    // Menus and Menu Items
    Route::resource(
        'menus',
        MenusController::class,
        [
            'only' => ['index', 'edit', 'update', 'create', 'store', 'destroy']
        ]
    );
    Route::resource(
        'menus.menu_items',
        MenuItemsController::class,
        [
            'only' => ['index', 'edit', 'update', 'create', 'store', 'destroy']
        ]
    );

    // Publicaciones
    Route::resource(
        'publicaciones',
        PublicacionesController::class,
        [
            'only' => ['index', 'edit', 'update', 'create', 'store', 'destroy']
        ]
    );
    Route::get(
        'publicaciones/{id}/imagenes',
        [
            'uses' => ImagenesController::class . '@create',
            'as' => 'imagenes.create'
        ]
    );
    Route::post(
        'publicaciones/{id}/imagenes',
        [
            'uses' => ImagenesController::class . '@store',
            'as' => 'imagenes.store'
        ]
    );
    Route::delete(
        'imagenes/{id}',
        [
            'uses' => ImagenesController::class . '@destroy',
            'as' => 'imagenes.destroy'
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
    Route::post(
        'categorias/ajaxStore',
        [
            'uses' => CategoriasController::class . '@ajaxStore',
            'as' => 'categorias.ajaxStore'
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

    Route::put(
        'archivos/{id}/exclusivo',
        [
            'uses' => ArchivosController::class . '@exclusivo',
            'as' => 'archivos.exclusivo'
        ]
    );

});

// El prefijo esta para evitar problemas en CBSF y permitir funcionar ambos SimpleCMS
Route::group(['prefix' => 'simplecms/public','as' => 'simplecms.public.'], function () {

    Route::get(
        'publicaciones',
        [
            'uses'  => PublicacionesController::class . '@publicIndex',
            'as'    => 'publicaciones.index'
        ]
    );

    Route::get(
        'publicaciones/{slug}',
        [
            'uses'  => PublicacionesController::class . '@publicShow',
            'as'    => 'publicaciones.show'
        ]
    );

    Route::get(
        'publicaciones/categoria/{slug}',
        [
            'uses'  => PublicacionesController::class . '@publicIndexByCategoriaSlug',
            'as'    => 'publicaciones.indexByCategoriaSlug'
        ]
    );

    Route::get('/archivos/{id}', [
        'uses' => ArchivosController::class . '@show',
        'as' => 'archivos.show'
    ]);
});


