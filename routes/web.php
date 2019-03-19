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
/*Route::get('/', function () {
    return view('najda');
});*/
Route::get('/', array('as' => 'home','uses' => 'HomeController@index'));
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



/*** Dossiers **/
Route::resource('/dossiers','DossiersController');
Route::post('/dossiers/saving','DossiersController@saving')->name('dossiers.saving');
Route::get('/dossiers/view/{id}', 'DossiersController@view');




Route::get('/welcomeemail0', function () {


    Mail::send('emails.test', [], function ($message) {
        $message->to('ihebsaad@gmail.com', 'iheb')->subject('Welcome!')
           // ->from('iheb@enterpriseesolutions.com', 'Houba')
         ;
    });

/*
    Mail::send('emails.test', [], function ($message) {
        $message
           // ->from('iheb@enterpriseesolutions.com', 'Houba')
            ->to('ihebsaad@gmail.com', 'iheb')
            ->subject('From laravel with ❤');
    });
*/
    // return redirect()->back();
});




Route::get('/welcomeemail', function () {

    Mail::send('emails.send', [], function ($message) {
        $message
          //  ->from('iheb@enterpriseesolutions.com', 'Houba')
            ->to('ihebsaad@gmail.com', 'iheb')
            ->subject('From SparkPost with ❤');
    });

    // return redirect()->back();
});

