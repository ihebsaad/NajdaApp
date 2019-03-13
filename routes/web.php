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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/demo', 'DemoController@index');
Route::post('/demo/fetch', 'DemoController@fetch')->name('demo.fetch');

Route::resource('/entrees','EntreesController');
Route::post('/entrees/saving','EntreesController@saving')->name('entrees.saving');

Route::get('/emails', 'EmailController@index');
Route::get('/emails/inbox', 'EmailController@inbox');

