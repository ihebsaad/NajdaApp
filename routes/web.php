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


Route::get('/', array('as' => 'home','uses' => 'DemoController@index'));
Route::post('/demo/fetch', 'DemoController@fetch')->name('demo.fetch');
Route::post('/home/fetch', 'HomeController@fetch')->name('home.fetch');


/*** Entrees **/
/* tous les emails (tous les entrees) dans la base */
Route::get('/entrees/boite', array('as' => 'boite','uses' => 'EntreesController@boite'));
Route::resource('/entrees','EntreesController');
Route::post('/entrees/saving','EntreesController@saving')->name('entrees.saving');
Route::get('/entrees/view/{id}', 'EntreesController@view');
Route::get('/entrees/show/{id}', 'EntreesController@show');



/*** Emails **/

Route::post('/emails/send','EmailController@send');
/* envoie d'un email */
Route::get('/emails/sending','EmailController@sending');
Route::get('/emails', 'EmailController@index');
/* unreaded emails and not checked */
Route::get('/emails/inbox', 'EmailController@inbox');
/* mark as readed and save to database */
Route::get('/emails/check', 'EmailController@check');
Route::get('/emails/folder/{foldername}', 'EmailController@folder');
/* le dispatching */
Route::get('/emails/disp', 'EmailController@disp');
Route::get('/emails/test', 'EmailController@test');
Route::get('/emails/maboite', 'EmailController@maboite');
Route::get('/emails/open/{id}', 'EmailController@open');



/*** Dossiers **/
Route::resource('/dossiers',  'DossiersController');
Route::get('/dossiers', array('as' => 'dossiers','uses' => 'DossiersController@index'));
Route::post('/dossiers/saving','DossiersController@saving')->name('dossiers.saving');
Route::get('/dossiers/view/{id}', 'DossiersController@view');

/**** LOGS  ****/
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
Route::get('errors', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@errors');
 
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
