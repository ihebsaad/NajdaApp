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
 
Route::get('/', array('as' => 'home','uses' => 'HomeController@index'));

/** Demo page */
Route::get('/demo', 'DemoController@index');
Route::post('/demo/fetch', 'DemoController@fetch')->name('demo.fetch');


/*** Entrees **/
Route::get('/entrees/boite','EntreesController@boite');
Route::resource('/entrees','EntreesController');
Route::post('/entrees/saving','EntreesController@saving')->name('entrees.saving');
Route::get('/entrees/view/{id}', 'EntreesController@view');
Route::get('/entrees/show/{id}', 'EntreesController@show');



/*** Emails **/

Route::post('/emails/send','EmailController@send');
Route::get('/emails/sending','EmailController@sending');
Route::get('/emails', 'EmailController@index');
Route::get('/emails/inbox', 'EmailController@inbox');
Route::get('/emails/check', 'EmailController@check');
Route::get('/emails/folder/{foldername}', 'EmailController@folder');
Route::get('/emails/disp', 'EmailController@disp');
Route::get('/emails/test', 'EmailController@test');
Route::get('/emails/maboite', 'EmailController@maboite');
Route::get('/emails/open/{id}', 'EmailController@open');



/*** Dossiers **/
Route::resource('/dossiers','DossiersController');
Route::post('/dossiers/saving','DossiersController@saving')->name('dossiers.saving');
Route::get('/dossiers/view/{id}', 'DossiersController@view');

/**** LOGS  ****/
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
Route::get('errors', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@errors');
 