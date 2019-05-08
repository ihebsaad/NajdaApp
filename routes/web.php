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
use App\Template_doc ;

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/roles', 'HomeController@roles')->name('roles');

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
Route::get('/entrees/',  'EntreesController@index');
Route::post('/entrees/saving','EntreesController@saving')->name('entrees.saving');
Route::get('/entrees/view/{id}', 'EntreesController@view');
Route::get('/entrees/show/{id}', 'EntreesController@show');
Route::get('/entrees/pdf/{id}', 'EntreesController@pdf');
Route::get('/entrees/export_pdf/{id}', 'EntreesController@export_pdf');
Route::get('/entrees/destroy/{id}', 'EntreesController@destroy');
Route::get('/entrees/archiver/{id}', 'EntreesController@archiver');
Route::get('/entrees/archive/', 'EntreesController@archive')->name('entrees.archive');
Route::post('/entrees/savecomment','EntreesController@savecomment')->name('entrees.savecomment');




/*** Emails **/

Route::post('/emails/send','EmailController@send');
/* envoie d'un email */
Route::get('/emails/sending','EmailController@sending')->name('emails.sending');
Route::get('/emails/envoimail/{id}','EmailController@envoimail')->name('emails.envoimail');
Route::get('/emails/envoifax/{id}','EmailController@envoifax')->name('emails.envoifax');
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
Route::post('/emails/sendfax', 'EmailController@sendfax')->name('emails.sendfax');
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
Route::post('/dossiers/updating','DossiersController@updating')->name('dossiers.updating');
Route::get('/dossiers/view/{id}', 'DossiersController@view')->name('dossiers.view');
Route::post('/dossiers/addemail','DossiersController@addemail')->name('dossiers.addemail');


/*** Clients **/
Route::resource('/clients',  'ClientsController');
Route::get('/clients', array('as' => 'clients','uses' => 'ClientsController@index'));
Route::post('/clients/saving','ClientsController@saving')->name('clients.saving');
Route::post('/clients/updating','ClientsController@updating')->name('clients.updating');
Route::post('/clients/updatingnature','ClientsController@updatingnature')->name('clients.updatingnature');
Route::post('/clients/removenature','ClientsController@removenature')->name('clients.removenature');
Route::get('/clients/view/{id}', 'ClientsController@view');




/*** Cities  Gouvernorats  **/
Route::resource('/cities',  'CitiesController');
Route::get('/cities', array('as' => 'cities','uses' => 'CitiesController@index'));
Route::post('/cities/saving','CitiesController@saving')->name('cities.saving');
Route::post('/cities/updating','CitiesController@updating')->name('cities.updating');
Route::get('/cities/view/{id}', 'CitiesController@view');



/*** Groupes Clients **/
Route::resource('/clientgroupes',  'ClientGroupesController');
Route::get('/clientgroupes', array('as' => 'clientgroupes','uses' => 'ClientGroupesController@index'));
Route::post('/clientgroupes/saving','ClientGroupesController@saving')->name('clientgroupes.saving');
Route::post('/clientgroupes/updating','ClientGroupesController@updating')->name('clientgroupes.updating');
Route::get('/clientgroupes/view/{id}', 'ClientGroupesController@view');


/*** Prestataires **/
Route::resource('/prestataires',  'PrestatairesController');
Route::get('/prestataires', array('as' => 'prestataires','uses' => 'PrestatairesController@index'));
 Route::post('/prestataires/saving','PrestatairesController@saving')->name('prestataires.saving');
 Route::post('/prestataires/updating','PrestatairesController@updating')->name('prestataires.updating');
Route::post('/prestataires/removetypeprest','PrestatairesController@removetypeprest')->name('prestataires.removetypeprest');
Route::post('/prestataires/createtypeprest','PrestatairesController@createtypeprest')->name('prestataires.createtypeprest');
Route::post('/prestataires/removecitieprest','PrestatairesController@removecitieprest')->name('prestataires.removecitieprest');
Route::post('/prestataires/createcitieprest','PrestatairesController@createcitieprest')->name('prestataires.createcitieprest');
Route::get('/prestataires/view/{id}', 'PrestatairesController@view');
Route::post('/prestataires/addeval','PrestatairesController@addeval')->name('prestataires.addeval');


/*** Prestations **/
Route::resource('/prestations',  'PrestationsController');
Route::get('/prestations', array('as' => 'prestations','uses' => 'PrestationsController@index'));
 Route::post('/prestations/saving','PrestatairesController@saving')->name('prestations.saving');
 Route::post('/prestations/updating','PrestatairesController@updating')->name('prestations.updating');
Route::get('/prestations/view/{id}', 'PrestationsController@view');
Route::post('/prestations/updating','PrestationsController@updating')->name('prestations.updating');

/*** Type Prestations  **/
//Route::resource('/typeprestations',  'TypePrestationsController');
Route::get('/typeprestations', array('as' => 'typeprestations','uses' => 'TypePrestationsController@index'));
 Route::post('/typeprestations/saving','TypePrestationsController@saving')->name('typeprestations.saving');
Route::post('/typeprestations/updating','TypePrestationsController@updating')->name('typeprestations.updating');;
Route::get('/typeprestations/view/{id}', 'TypePrestationsController@view');

/*** Notes **/
Route::resource('/notes',  'NotesController');
Route::get('/notes', array('as' => 'notes','uses' => 'NotesController@index'));
Route::post('/notes/updating','NotesController@updating')->name('notes.updating');
Route::get('/notes/view/{id}', 'NotesController@view');
 
/*** Actions**/
Route::resource('/actions',  'ActionController');
Route::get('/actions', array('as' => 'actions','uses' => 'ActionController@index'));
Route::post('/actions/saving','ActionController@saving')->name('actions.saving');
Route::post('/actions/store','ActionController@store')->name('actions.store');
Route::get('/actions/view/{id}', 'ActionController@view');
Route::get('/action/workflow/{dossid}/{id}', 'ActionController@getWorkflow');
Route::post('/action/updateworkflow/', 'ActionController@updateWorkflow');
//Route::post('/action/updateworkflow/{dossid}/{id}', 'ActionController@updateWorkflow');
Route::get('/action/RendreInactive/{id}/{dossid}', 'ActionController@RendreInactive');
Route::get('/action/RendreAchevee/{id}/{dossid}', 'ActionController@RendreAchevee');

/*** SousAction**/
Route::resource('/sousactions',  'SousActionController');
Route::get('/sousactions', array('as' => 'actions','uses' => 'SousActionController@index'));
Route::post('/sousactions/saving','SousActionController@saving')->name('sousactions.saving');
Route::get('/sousactions/view/{id}', 'SousActionController@view');

/*** TypeAction**/
Route::resource('/typesactions',  'TypeActionController');
Route::get('/typesactions', array('as' => 'actions','uses' => 'TypeActionController@index'));
Route::post('/typesactions/saving','TypeActionController@saving')->name('typeactions.saving');
Route::get('/typesactions/view/{id}', 'TypeActionController@view');

/*** EtapeTypeAction**/
/*Route::resource('/etapestypesactions',  'EtapesTypeActionController');
Route::get('/etapestypesactions', array('as' => 'actions','uses' => 'EtapesTypeActionController@index'));
Route::post('/etapestypesactions/saving','EtapesTypeActionController@saving')->name('etapestypesactions.saving');
Route::get('/etapestypesactions/view/{id}', 'EtapesTypeActionController@view');*/



/*** Users **/

//Route::resource('/users',  'UsersController');
Route::get('/users', array('as' => 'users','uses' => 'UsersController@index'));
Route::get('/users/create','UsersController@create')->name('users.create');
Route::post('/users/saving','UsersController@saving')->name('users.saving');
Route::get('/users/view/{id}', 'UsersController@view');
Route::get('/users/profile/{id}', 'UsersController@profile')->name('profile');
Route::post('/users/createuserrole', 'UsersController@createuserrole')->name('users.createuserrole');
Route::post('/users/removeuserrole', 'UsersController@removeuserrole')->name('users.removeuserrole');

Route::get('/users/destroy/{id}', 'UsersController@destroy');
//Route::get('/edit/{id}','UsersController@edit');
Route::post('/edit/{id}','UsersController@update');


/**** LOGS  ****/
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('logs');;
Route::get('errors', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@errors');

/**** TAGS  ****/
Route::post('/tags/addnew','TagsController@addnew')->name('tags.addnew');
Route::post('/tags/deletetag','TagsController@deletetag')->name('tags.deletetag');

/*** Generate doc ***/
Route::get('docgen', function () {
		//$file = public_path('rtf_templates\PC_Dedouannement.rtf');
		$arrfile = Template_doc::where('nom', 'like', 'PC_Dedouannement')->first();
		//print_r($arrfile);
		$file=public_path($arrfile['path']);
		if (file_exists($file)) {

			setlocale (LC_TIME, 'fr_FR.utf8','fra'); 
			$datees = strftime("%d %B %Y".", "."%H:%M"); 

			$refdoss = '00N00001';
			
			$array = array(
				'[N_ABONNEE]' => 'Ben Foulen',
				'[P_ABONNEE]' => 'Flen',
				'[NREF_DOSSIER]' => $refdoss,
				'[DATE_PREST]' => '10/01/2020',
				'[LIEU_DED]' => 'Tunis',
				'[TYPEVE_IMMAT]' => 'Mercedes 125-4568',
				'[LIEU_IMMOB]' => 'Tunis',
				'[LTA]' => 'ExLTA',
				'[CORD_VOL]' => '001VOL100120',
				'[DATE_HEURE]' => $datees,
			);

			$name_file = 'PC_Dedouannement_'.$refdoss.'.doc';
			
			return WordTemplate::export($file, $array, $name_file);
		}
		else {return 'fichier template non existant';}
	});
 
