<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/home', function() {
    return view('home');
})->name('home')->middleware('auth');

Route::group(['middleware' => ['auth']], function() {
    Route::get('variable', 'VariableController@index')->name('variable');
    Route::post('variable/load', 'VariableController@load')->name('variable.load');
    Route::get('variable/{id}/edit','VariableController@edit')->name('variable.edit');
    Route::delete('variable/{id}','VariableController@delete')->name('variable.delete');

    
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('dashboard/apilamientotable', 'DashboardController@apilamientotable')->name('dashboard.apilamientotable');
});
