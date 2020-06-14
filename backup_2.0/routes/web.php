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
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/home/fetchDistricts', 'HomeController@fetchDistricts')->name('dropdown.fetchDistricts');
Route::post('/home/fetchAmphures', 'HomeController@fetchAmphures')->name('dropdown.fetchAmphures');

Route::get('/fullcalendareventmaster','FullCalendarEventMasterController@index');
Route::post('/fullcalendareventmaster/create','FullCalendarEventMasterController@create');
Route::post('/fullcalendareventmaster/update','FullCalendarEventMasterController@update');
Route::post('/fullcalendareventmaster/delete','FullCalendarEventMasterController@destroy');
