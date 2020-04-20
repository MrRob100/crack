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

Route::get('/dl', 'DashboardController@dl');

//index
Route::get('/', 'DashboardController@index')->name('dashboard');

//upload
Route::post('/', 'DashboardController@upload')->name('upload-song');

Route::get('/del', 'DashboardController@delete');
Route::get('/get', 'DashboardController@getMarker');
Route::get('/set', 'DashboardController@setMarker');



//unused
Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
