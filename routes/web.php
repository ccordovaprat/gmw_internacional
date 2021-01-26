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

//Route::get('/', function () {
//    return view('welcome');

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/geolocalizacion', 'GeolocalizacionController@geo')->name('geo');
Route::get('/hora', 'PacienteController@horaPaciente')->name('hora')->middleware('auth');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::get('/geodatos', 'Auth\LoginController@geoDatos')->name('geoDatos');
Route::get('/geodatos/{pais}', 'Auth\LoginController@porPais')->name('porPais');

