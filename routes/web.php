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


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');
$this->get('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
$this->post('register', 'Auth\RegisterController@register');


// Password Reset Routes...
$this->get('password/request', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');

$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');;
$this->post('password/reset', 'Auth\ResetPasswordController@reset');
$this->post('password/reset/{token}', 'Auth\ResetPasswordController@reset');




Route::get('/', array('as' => 'home','uses' => 'HomeController@index'));
Route::post('/demo/fetch', 'DemoController@fetch')->name('demo.fetch');
Route::get('/demo/test/{Body}/{From}', 'DemoController@test')->name('demo.test');
Route::get('/demo/test/', 'DemoController@test')->name('demo.test');
Route::put('/demo/test/', 'DemoController@test')->name('demo.test');
Route::post('/demo/test/', 'DemoController@test')->name('demo.test');
Route::post('/home/fetch', 'HomeController@fetch')->name('home.fetch');



/*** Entrees **/
/* tous les emails (tous les entrees) dans la base */
Route::get('/entrees/boite', array('as' => 'boite','uses' => 'EntreesController@boite'));
Route::post('/entrees/saving','EntreesController@saving')->name('entrees.saving');
Route::get('/entrees/view/{id}', 'EntreesController@view');
Route::get('/entrees/show/{id}', 'EntreesController@show');
Route::get('/entrees/destroy/{id}', 'EntreesController@destroy');
Route::get('/entrees/archiver/{id}', 'EntreesController@archiver');
Route::get('/entrees/archive/', 'EntreesController@archive')->name('entrees.archive');



/*** Emails **/

Route::post('/emails/send','EmailController@send');
/* envoie d'un email */
Route::get('/emails/sending','EmailController@sending')->name('emails.sending');
Route::get('/emails', 'EmailController@index');
/* unreaded emails and not checked */
Route::get('/emails/inbox', 'EmailController@inbox');
/* mark as readed and save to database */
Route::get('/emails/check', 'EmailController@check');
Route::get('/emails/folder/{foldername}', 'EmailController@folder');
/* le dispatching */
Route::get('/emails/disp', 'EmailController@disp');

Route::get('/emails/test', 'EmailController@test');
Route::get('/emails/sms', 'EmailController@sms');
Route::post('/emails/sendsms', 'EmailController@sendsms')->name('emails.sendsms');
Route::get('/emails/whatsapp', 'EmailController@whatsapp');
Route::post('/emails/sendwhatsapp', 'EmailController@sendwhatsapp')->name('emails.sendwhatsapp');
//Route::put('/emails/sendwhatsapp', 'EmailController@sendwhatsapp')->name('emails.sendwhatsapp');
//Route::get('/emails/sendwhatsapp', 'EmailController@sendwhatsapp')->name('emails.sendwhatsapp');
Route::get('/emails/maboite', 'EmailController@maboite');
Route::get('/emails/open/{id}', 'EmailController@open');



/*** Envoyes : Email envoyées et brouillons  **/
//Route::resource('/envoyes',  'EnvoyesController');
Route::get('/envoyes', array('as' => 'envoyes','uses' => 'EnvoyesController@index'));
Route::post('/envoyes/saving','EnvoyesController@saving')->name('envoyes.saving');
Route::post('/envoyes/savingBR','EnvoyesController@savingBR')->name('envoyes.savingBR');
Route::get('/envoyes/view/{id}', 'EnvoyesController@view');
Route::get('/envoyes/show/{id}', 'EnvoyesController@show');
Route::get('/envoyes/destroy/{id}', 'EnvoyesController@destroy');
Route::get('/envoyes/brouillons', 'EnvoyesController@brouillons')->name('envoyes.brouillons');


/*** Dossiers **/
Route::resource('/dossiers',  'DossiersController');
Route::get('/dossiers', array('as' => 'dossiers','uses' => 'DossiersController@index'));
Route::post('/dossiers/saving','DossiersController@saving')->name('dossiers.saving');
Route::get('/dossiers/view/{id}', 'DossiersController@view');


/*** Users **/
Route::resource('/users',  'UsersController');
Route::get('/users', array('as' => 'users','uses' => 'UsersController@index'));
Route::post('/users/saving','UsersController@saving')->name('users.saving');
Route::get('/users/view/{id}', 'UsersController@view');


/**** LOGS  ****/
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
Route::get('errors', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@errors');
 
