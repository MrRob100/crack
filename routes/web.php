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


//ctx test
Route::get('/ctx', 'DashboardController@ctx');


Route::get('/dl', 'DashboardController@dl');

Route::get('/del', 'DashboardController@delete');
Route::get('/get', 'DashboardController@getMarker');
Route::get('/set', 'DashboardController@setMarker');

//index
Route::get('/{para?}', 'DashboardController@index')->name('dashboard');

//upload
Route::post('/{para?}', 'DashboardController@upload')->name('upload-song');


//unused

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
