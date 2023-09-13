<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Contracts\Permission;

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
    Route::get('dashboard_mine', 'DashboardController@index2')->name('dashboard_mine');
    Route::get('dashboard/procesostable', 'DashboardController@procesostable')->name('dashboard.procesostable');
    Route::get('dashboard/{date}/getpdfprocesostable', 'DashboardController@getpdfprocesostable')->name('dashboard.getpdfprocesostable'); 
    Route::get('dashboard/minatable', 'DashboardController@minatable')->name('dashboard.minatable'); 
    Route::get('dashboard/{date}/getpdfminatable', 'DashboardController@getpdfminatable')->name('dashboard.getpdfminatable'); 
    Route::post('dashboard/load', 'DashboardController@load')->name('dashboard.load');
    Route::post('dashboard/edit','DashboardController@edit')->name('dashboard.edit'); 
    Route::get('dashboard/{id}/complete_value','DashboardController@complete_value')->name('dashboard.complete_value');
    //PDF Full
    Route::post('getpdfcompleto', 'ReportesController@getpdfcompleto')->name('dashboard.getpdfcompleto');
    
       
    Route::get('historial', 'HistorialController@index')->name('historial');    
       
    Route::get('conciliado', 'ConciliadoController@index')->name('conciliado'); 
    Route::post('conciliado/getvariables', 'ConciliadoController@getvariables')->name('conciliado.getvariables'); 
    Route::post('conciliado/load', 'ConciliadoController@load')->name('conciliado.load');  

    Route::post('comentario/load', 'ComentarioController@load')->name('comentario.load');
    Route::get('comentario/comentariostable', 'ComentarioController@comentariostable')->name('comentario.comentariostable'); 
    Route::get('comentario/{id}/edit','ComentarioController@edit')->name('comentario.edit');
    Route::delete('comentario/{id}','ComentarioController@delete')->name('comentario.delete');

    Route::get('historial_por_variable', 'HistorialVariableController@index')->name('historial_por_variable');
    Route::post('valores.variables', 'HistorialVariableController@getValores')->name('valores.variables');
    Route::get('historial/{id}/edit','HistorialVariableController@edit')->name('historial.edit');

    Route::get('historialvariables', 'HistorialVariablesController@index')->name('historialvariables');
    Route::post('historialvariables/getvariables', 'HistorialVariablesController@getvariables')->name('historialvariables.getvariables');
    Route::post('historialvariables/getvalores', 'HistorialVariablesController@getvalores')->name('historialvariables.getvalores');
    Route::post('historialvariables/getcolumnas','HistorialVariablesController@getcolumnas')->name('historialvariables.getcolumnas');

   

});

/**
 * Solo los Directores,Jefes y Supervisores de cada area tendran acceso a este modulo
 * Requerido por SSOMA 
 */
Route::group(['middleware' => ['auth', 'permission:ssoma module']], function() {

    // /**
    //  * QUEDA EN STANDA BY ESTE MODULO PARA UN FUTURO DESARROLO SOBRE CAPACITACION INTELEX
    //  * Creacion de la route para la capacitacion
    //  */
    // Route::get('capacitacion', 'SsomaCapacitacionController@index')->name('capacitacion');
    // Route::post('capacitacion/aproved','SsomaCapacitacionController@aproved')->name('capacitacion.aproved');//este es un post en donde directamente hace la aprobacion o desaprobacion del empleado
    // Route::post('capacitacion/empleados', 'SsomaCapacitacionController@empleados')->name('permisos.empleados');
    // Route::post('capacitacion/capacitacion', 'SsomaCapacitacionController@capacitacion')->name('permisos.capacitacion');
    // Route::post('capacitacion.getvalores', 'SsomaCapacitacionController@getValores')->name('capacitacion.getvalores');
    // Route::post('capacitacion/ABEmpleados', 'SsomaCapacitacionController@ABEmpleados')->name('capacitacion.ABEmpleados');
    // Route::post('capacitaciom/AMCapacitacion','SsomaCapacitacionController@AMCapacitacion')->name('capacitacion.AMCapacitacion');
    // Route::get('capacitacion/{id}/edit','SsomaCapacitacionController@edit')->name('capacitacion.edit');
    // Route::delete('capacitacion/{id}','SsomaCapacitacionController@delete')->name('capacitacion.delete');

    /**
     * Creacion de la route para la CAPACITACION DE PERFORMANCE
     */
    Route::get('capacitacion_performance', 'SsomaCapacitacionPerformanceController@index')->name('capacitacion_performance');
    Route::post('capacitacion_performance/load', 'SsomaCapacitacionPerformanceController@load')->name('capacitacion_performance.load');
    Route::get('capacitacion_performance/{id}/edit','SsomaCapacitacionPerformanceController@edit')->name('capacitacion_performance.edit');
    Route::delete('capacitacion_performance/{id}','SsomaCapacitacionPerformanceController@delete')->name('capacitacion_performance.delete');

    /**
     * Creacion de la route para la OST
     */
    Route::get('ost', 'SsomaOstController@index')->name('ost');
    Route::post('ost/load', 'SsomaOstController@load')->name('ost.load');
    Route::get('ost/{id}/edit','SsomaOstController@edit')->name('ost.edit');
    Route::delete('ost/{id}','SsomaOstController@delete')->name('ost.delete');

    /**
     * Creacion de la route para la ATS
     */
    Route::get('ats', 'SsomaAtsController@index')->name('ats');
    Route::post('ats/load', 'SsomaAtsController@load')->name('ats.load');
    Route::get('ats/{id}/edit','SsomaAtsController@edit')->name('ats.edit');
    Route::delete('ats/{id}','SsomaAtsController@delete')->name('ats.delete');

     /**
     * Creacion de la route para INSPECCIONES
     */
    Route::get('inspeccion', 'SsomaInspeccionesController@index')->name('inspeccion');
    Route::post('inspeccion/load', 'SsomaInspeccionesController@load')->name('inspeccion.load');
    Route::get('inspeccion/{id}/edit','SsomaInspeccionesController@edit')->name('inspeccion.edit');
    Route::delete('inspeccion/{id}','SsomaInspeccionesController@delete')->name('inspeccion.delete');

});

Route::group(['middleware' => ['auth', 'permission:budget module']], function() {

    Route::get('budget', 'BudgetController@index')->name('budget');
    Route::post('budget/getvariables', 'BudgetController@getvariables')->name('budget.getvariables');
    Route::post('budget.getvalores', 'BudgetController@getValores')->name('budget.getvalores');
    Route::post('budget/load', 'BudgetController@load')->name('budget.load');

});

Route::group(['middleware' => ['auth', 'permission:forecast module']], function() {
    Route::get('forecast_individual', 'ForecastController@FI_index')->name('forecast_individual');
    Route::post('forecast_individual/getvariables', 'ForecastController@FI_getVariables')->name('forecast_individual.getvariables');
    Route::post('forecast_individual/getvalores', 'ForecastController@FI_getValores')->name('forecast_individual.getvalores');
    Route::get('forecast_individual/{id}/edit','ForecastController@FI_edit')->name('forecast_individual.edit');
    Route::post('forecast_individual/load', 'ForecastController@FI_load')->name('forecast_individual.load');

    Route::get('forecast_group', 'ForecastController@FG_index')->name('forecast_group');
    Route::post('forecast_group/getvariables', 'ForecastController@FG_getVariables')->name('forecast_group.getvariables');
    Route::post('forecast_group/getvalores', 'ForecastController@FG_getValores')->name('forecast_group.getvalores');
    Route::post('forecast_group/getcolumnas','ForecastController@FG_getColumnas')->name('forecast_group.getcolumnas');


    /**
     * Comento esta area de codigo por que al ingresar al histoial de varibles da un erro 403:Forbidden
     */
    // Route::get('historialvariables', 'HistorialVariablesController@index')->name('historialvariables');
    // Route::post('historialvariables/getvariables', 'HistorialVariablesController@getvariables')->name('historialvariables.getvariables');
    // Route::post('historialvariables/getvalores', 'HistorialVariablesController@getvalores')->name('historialvariables.getvalores');
    // Route::post('historialvariables/getcolumnas','HistorialVariablesController@getcolumnas')->name('historialvariables.getcolumnas');



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