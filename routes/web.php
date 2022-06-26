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

use App\Http\Controllers\DiadesController;

Route::get('/', function () {return view('index');});

Route::get('/diades', 'DiadesController@index');
Route::post('/diades', 'DiadesController@index');

Route::get('/diades/create', 'DiadesController@create');
Route::post('/diades/store', 'DiadesController@store');

Route::get('/diades/temporades', 'DiadesController@compTemp');
Route::post('/diades/temporades', 'DiadesController@compTemp');

//Route::get('/dashboard', function () {return view('index');});
//Route::get('/diades', function () {return view('index');});
//Route::get('/temporades', function () {return view('index');});
//Route::get('/mapa', function () {return view('index');});
