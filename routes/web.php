<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', function () {
    return redirect('home');
});


Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);

Route::get('/home', function() {
    return redirect()->route('dashboard');
})->name('home')->middleware('auth');

Route::group(['middleware' => ['auth']], function() {

    
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('dashboard/procesostable', 'DashboardController@procesostable')->name('dashboard.procesostable'); 
    Route::post('dashboard/load', 'DashboardController@load')->name('dashboard.load');
    Route::post('dashboard/edit','DashboardController@edit')->name('dashboard.edit');
    
    Route::get('historial', 'HistorialController@index')->name('historial');

    Route::post('comentario/load', 'ComentarioController@load')->name('comentario.load');
    Route::get('comentario/comentariostable', 'ComentarioController@comentariostable')->name('comentario.comentariostable'); 
    Route::get('comentario/{id}/edit','ComentarioController@edit')->name('comentario.edit');
    Route::delete('comentario/{id}','ComentarioController@delete')->name('comentario.delete');
});

Route::group(['middleware' => ['auth', 'role:Admin']], function() {

    Route::get('area', 'AreaController@index')->name('area');
    Route::post('area/load', 'AreaController@load')->name('area.load');
    Route::get('area/{id}/edit','AreaController@edit')->name('area.edit');
    Route::delete('area/{id}','AreaController@delete')->name('area.delete');

    Route::get('categoria', 'CategoriaController@index')->name('categoria');
    Route::post('categoria/load', 'CategoriaController@load')->name('categoria.load');
    Route::get('categoria/{id}/edit','CategoriaController@edit')->name('categoria.edit');
    Route::delete('categoria/{id}','CategoriaController@delete')->name('categoria.delete');

    Route::get('subcategoria', 'SubcategoriaController@index')->name('subcategoria');
    Route::post('subcategoria/load', 'SubcategoriaController@load')->name('subcategoria.load');
    Route::get('subcategoria/{id}/edit','SubcategoriaController@edit')->name('subcategoria.edit');
    Route::delete('subcategoria/{id}','SubcategoriaController@delete')->name('subcategoria.delete');
    Route::post('subcategoria/getcategoria', 'SubcategoriaController@getcategoria')->name('subcategoria.getcategoria');

    Route::get('variable', 'VariableController@index')->name('variable');
    Route::post('variable/load', 'VariableController@load')->name('variable.load');
    Route::get('variable/{id}/edit','VariableController@edit')->name('variable.edit');
    Route::delete('variable/{id}','VariableController@delete')->name('variable.delete');
    Route::post('variable/getcategoria', 'VariableController@getcategoria')->name('variable.getcategoria');
    Route::post('variable/getsubcategoria', 'VariableController@getsubcategoria')->name('variable.getsubcategoria');

    Route::get('permisos', 'PermisosController@index')->name('permisos')->name('permisos');
    Route::post('permisos/getuservbles', 'PermisosController@getuservbles')->name('permisos.getuservbles');
    Route::post('permisos/getvariables', 'PermisosController@getvariables')->name('permisos.getvariables');
    Route::post('permisos/check', 'PermisosController@check')->name('permisos.check');
    Route::post('permisos/load', 'PermisosController@load')->name('permisos.load');
    //Route::get('permisos/{id}', 'PermisosController@load')->name('permisos.load');

    Route::get('comentario_area', 'ComentarioAreaController@index')->name('comentario_area');
    Route::post('comentario_area/load', 'ComentarioAreaController@load')->name('comentario_area.load');
    Route::get('comentario_area/{id}/edit','ComentarioAreaController@edit')->name('comentario_area.edit');
    Route::delete('comentario_area/{id}','ComentarioAreaController@delete')->name('comentario_area.delete');
});