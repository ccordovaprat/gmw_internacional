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

//Rutas Laravel
Route::get('/home', 'HomeController@index')->name('home');

//Controlador Geolocalizacion
Route::get('/geolocalizacion', 'GeolocalizacionController@geo')->name('geo');

//Controlador Paciente
Route::get('/hora', 'PacienteController@horaPaciente')->name('hora')->middleware('auth');
Route::get('/hora/{pais}', 'PacienteController@porPais')->name('horaPais');
Route::post('/hora.grabar', 'PacienteController@grabarDatosGeo')->name('hora.grabar');
Route::post('/hora.grabarPorIp', 'PacienteController@grabarDatosPorIp')->name('hora.grabarPorIp');



//Controlador Login
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::get('/geodatos', 'Auth\LoginController@geoDatos')->name('geoDatos');
Route::get('/geodatos/{pais}', 'Auth\LoginController@porPais')->name('porPais');

