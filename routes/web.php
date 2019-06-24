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
Route::get('/entrees/sendpdf/{id}', 'EntreesController@sendpdf');
Route::get('/entrees/export_pdf/{id}', 'EntreesController@export_pdf');
Route::get('/entrees/destroy/{id}', 'EntreesController@destroy');
Route::get('/entrees/archiver/{id}', 'EntreesController@archiver');
Route::get('/entrees/archive/', 'EntreesController@archive')->name('entrees.archive');
Route::post('/entrees/savecomment','EntreesController@savecomment')->name('entrees.savecomment');



/*** Emails **/

Route::post('/emails/send','EmailController@send');
/* envoie d'un email */
Route::get('/emails/sending','EmailController@sending')->name('emails.sending');
Route::get('/emails/envoimail/{id}/{type}','EmailController@envoimail')->name('emails.envoimail');
Route::get('/emails/envoimail/{id}/{type}/{prest}','EmailController@envoimail')->name('emails.envoimail');
Route::get('/emails/envoimailbr/{id}','EmailController@envoimailbr')->name('emails.envoimailbr');
Route::get('/emails/envoifax/{id}','EmailController@envoifax')->name('emails.envoifax');
//Route::post('/emails/searchprest','EmailController@searchprest')->name('emails.searchprest');
Route::get('/emails/searchprest','EmailController@searchprest')->name('emails.searchprest');
Route::get('/emails', 'EmailController@index');
/* unreaded emails and not checked */
Route::get('/emails/inbox', 'EmailController@inbox');
/* mark as readed and save to database */
Route::get('/emails/check', 'EmailController@check');
Route::get('/emails/checkboite2', 'EmailController@checkboite2');
Route::get('/emails/checkfax', 'EmailController@checkfax');
Route::get('/emails/checksms', 'EmailController@checksms');
Route::get('/emails/checkboiteperso', 'EmailController@checkboiteperso');
Route::get('/emails/folder/{foldername}', 'EmailController@folder');
Route::post('/emails/accuse', 'EmailController@accuse')->name('emails.accuse');


Route::get('/emails/test', 'EmailController@test');
Route::get('/emails/sms/{id}', 'EmailController@sms');
Route::post('/emails/sendsms', 'EmailController@sendsms')->name('emails.sendsms');
Route::post('/emails/sendfax', 'EmailController@sendfax')->name('emails.sendfax');
Route::get('/emails/whatsapp', 'EmailController@whatsapp');
Route::post('/emails/sendwhatsapp', 'EmailController@sendwhatsapp')->name('emails.sendwhatsapp');
//Route::put('/emails/sendwhatsapp', 'EmailController@sendwhatsapp')->name('emails.sendwhatsapp');
//Route::get('/emails/sendwhatsapp', 'EmailController@sendwhatsapp')->name('emails.sendwhatsapp');
Route::get('/emails/maboite', 'EmailController@maboite');
Route::get('/emails/open/{id}', 'EmailController@open');


/****** Boite Personnelle ****/
Route::get('/boites/',  'BoitesController@index')->name('boites');;
Route::get('/boites/show/{id}', 'BoitesController@show');



/*** Envoyes : Email envoyées et brouillons  **/
//Route::resource('/envoyes',  'EnvoyesController');
Route::get('/envoyes', array('as' => 'envoyes','uses' => 'EnvoyesController@index'));
Route::post('/envoyes/saving','EnvoyesController@saving')->name('envoyes.saving');
Route::post('/envoyes/savingbr','EnvoyesController@savingbr')->name('envoyes.savingbr');
Route::post('/envoyes/updatingbr','EnvoyesController@updatingbr')->name('envoyes.updatingbr');
Route::post('/envoyes/updatingbr','EnvoyesController@updatingbr')->name('envoyes.updatingbr');
Route::get('/envoyes/view/{id}', 'EnvoyesController@view');
Route::get('/envoyes/show/{id}', 'EnvoyesController@show');
Route::get('/envoyes/destroy/{id}', 'EnvoyesController@destroy');
Route::get('/envoyes/brouillons', 'EnvoyesController@brouillons')->name('envoyes.brouillons');


/*** Dossiers **/
Route::resource('/dossiers',  'DossiersController');
Route::get('/dossiers', array('as' => 'dossiers','uses' => 'DossiersController@index'));
Route::post('/dossiers/saving','DossiersController@saving')->name('dossiers.saving');
Route::post('/dossiers/updating','DossiersController@updating')->name('dossiers.updating');
Route::post('/dossiers/updating2','DossiersController@updating2')->name('dossiers.updating2');
Route::post('/dossiers/updating3','DossiersController@updating3')->name('dossiers.updating3');
Route::get('/dossiers/view/{id}', 'DossiersController@view')->name('dossiers.view');
Route::get('/dossiers/manage/{id}', 'DossiersController@manage')->name('dossiers.manage');
Route::post('/dossiers/addemail','DossiersController@addemail')->name('dossiers.addemail');
Route::post('/dossiers/attribution','DossiersController@attribution')->name('dossiers.attribution');
Route::post('/dossiers/listepres','DossiersController@ListePrestataireCitySpec')->name('dossiers.listepres');
Route::post('/dossiers/addressadd','DossiersController@addressadd')->name('dossiers.addressadd');
Route::post('/dossiers/addressadd2','DossiersController@addressadd2')->name('dossiers.addressadd2');


/*** Clients **/
//Route::resource('/clients',  'ClientsController');
Route::get('/clients', array('as' => 'clients','uses' => 'ClientsController@index'));
Route::get('/clients/saving','ClientsController@saving')->name('clients.saving');
Route::post('/clients/saving','ClientsController@saving')->name('clients.saving');
Route::post('/clients/updating','ClientsController@updating')->name('clients.updating');
Route::post('/clients/addressadd','ClientsController@addressadd')->name('clients.addressadd');
Route::post('/clients/addressadd2','ClientsController@addressadd2')->name('clients.addressadd2');
Route::post('/clients/addressadd3','ClientsController@addressadd3')->name('clients.addressadd3');
Route::post('/clients/updatingnature','ClientsController@updatingnature')->name('clients.updatingnature');
Route::post('/clients/removenature','ClientsController@removenature')->name('clients.removenature');
Route::get('/clients/view/{id}', 'ClientsController@view');


/*** Cities -> Gouvernorats  **/
Route::resource('/cities',  'CitiesController');
Route::get('/cities', array('as' => 'cities','uses' => 'CitiesController@index'));
Route::post('/cities/saving','CitiesController@saving')->name('cities.saving');
Route::post('/cities/updating','CitiesController@updating')->name('cities.updating');
Route::get('/cities/view/{id}', 'CitiesController@view');


/*** Actualites   **/
//Route::resource('/actualites',  'ActualitesController');
Route::get('/actualites', array('as' => 'actualites','uses' => 'ActualitesController@index'));
Route::post('/actualites/saving','ActualitesController@saving')->name('actualites.saving');
Route::post('/actualites/updating','ActualitesController@updating')->name('actualites.updating');
Route::get('/actualites/view/{id}', 'ActualitesController@view');
Route::get('/actualites/destroy/{id}', 'ActualitesController@destroy');
 

/*** Voitures ->Véhicules   **/
Route::resource('/voitures',  'VoituresController');
Route::get('/voitures', array('as' => 'voitures','uses' => 'VoituresController@index'));
Route::post('/voitures/saving','VoituresController@saving')->name('voitures.saving');
Route::post('/voitures/updating','VoituresController@updating')->name('voitures.updating');
Route::get('/voitures/view/{id}', 'VoituresController@view');


/*** Equipements -> equipements   **/
Route::resource('/equipements',  'VoituresController');
Route::get('/equipements', array('as' => 'equipements','uses' => 'EquipementsController@index'));
Route::post('/equipements/saving','EquipementsController@saving')->name('equipements.saving');
Route::post('/equipements/updating','EquipementsController@updating')->name('equipements.updating');
Route::post('/equipements/updating2','EquipementsController@updating2')->name('equipements.updating2');
Route::get('/equipements/view/{id}', 'EquipementsController@view');



/*** personnes -> personnels   **/
Route::resource('/personnes',  'PersonnesController');
Route::get('/personnes', array('as' => 'personnes','uses' => 'PersonnesController@index'));
Route::post('/personnes/saving','PersonnesController@saving')->name('personnes.saving');
Route::post('/personnes/updating','PersonnesController@updating')->name('personnes.updating');
Route::get('/personnes/view/{id}', 'PersonnesController@view');





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
Route::post('/prestataires/removespec','PrestatairesController@removespec')->name('prestataires.removespec');
Route::post('/prestataires/createspec','PrestatairesController@createspec')->name('prestataires.createspec');
Route::get('/prestataires/view/{id}', 'PrestatairesController@view');
Route::post('/prestataires/addeval','PrestatairesController@addeval')->name('prestataires.addeval');
Route::post('/prestataires/addemail','PrestatairesController@addemail')->name('prestataires.addemail');
Route::post('/prestataires/addressadd','PrestatairesController@addressadd')->name('prestataires.addressadd');


/*** Prestations **/
Route::resource('/prestations',  'PrestationsController');
Route::get('/prestations', array('as' => 'prestations','uses' => 'PrestationsController@index'));
 Route::post('/prestations/saving','PrestatairesController@saving')->name('prestations.saving');
 Route::post('/prestations/updating','PrestatairesController@updating')->name('prestations.updating');
Route::get('/prestations/view/{id}', 'PrestationsController@view');
Route::post('/prestations/updating','PrestationsController@updating')->name('prestations.updating');




/*** Intervenants **/
Route::resource('/intervenants',  'IntervenantsController');
Route::get('/intervenants', array('as' => 'intervenants','uses' => 'IntervenantsController@index'));
Route::post('/intervenants/saving','IntervenantsController@saving')->name('intervenants.saving');
Route::post('/intervenants/updating','IntervenantsController@updating')->name('intervenants.updating');
Route::get('/intervenants/view/{id}', 'IntervenantsController@view');
Route::post('/intervenants/updating','IntervenantsController@updating')->name('intervenants.updating');



/*** Type Prestations  **/
//Route::resource('/typeprestations',  'TypePrestationsController');
Route::get('/typeprestations', array('as' => 'typeprestations','uses' => 'TypePrestationsController@index'));
 Route::post('/typeprestations/saving','TypePrestationsController@saving')->name('typeprestations.saving');
Route::post('/typeprestations/updating','TypePrestationsController@updating')->name('typeprestations.updating');;
Route::get('/typeprestations/view/{id}', 'TypePrestationsController@view');
 
/*** Specialités **/
Route::resource('/specialites',  'SpecialitesController');
Route::get('/specialites', array('as' => 'specialites','uses' => 'SpecialitesController@index'));
 Route::post('/specialites/saving','SpecialitesController@saving')->name('specialites.saving');
Route::post('/specialites/updating','SpecialitesController@updating')->name('specialites.updating');;
Route::get('/specialites/view/{id}', 'SpecialitesController@view');


/*** Notes **/
Route::resource('/notes',  'NotesController');
Route::get('/notes', array('as' => 'notes','uses' => 'NotesController@index'));
Route::post('/notes/updating','NotesController@updating')->name('notes.updating');
Route::get('/notes/view/{id}', 'NotesController@view');
Route::post('/Note/store','NotesController@store')->name('Note.store');
Route::get('/getNotesAjax','NotesController@getNotesAjax');
Route::get('/getNotesAjaxModal','NotesController@getNotesAjaxModal');
Route::get('/SupprimerNoteAjax/{id}','NotesController@SupprimerNoteAjax');
Route::get('/SupprimerNote/{id}','NotesController@SupprimerNote');
Route::get('/ReporterNote/{id}','NotesController@ReporterNote');


 
/*** Missions**/
Route::resource('/Missions',  'MissionController');
Route::get('/Missions', array('as' => 'Missions','uses' => 'MissionController@index'));
Route::post('/Missions/saving','MissionController@saving')->name('Missions.saving');
Route::post('/Missions/store','MissionController@store')->name('Missions.store');
Route::get('/Missions/view/{id}', 'MissionController@view');
Route::get('/Mission/workflow/{dossid}/{id}', 'MissionController@getWorkflow');
Route::post('/Mission/updateworkflow/', 'MissionController@updateWorkflow');
//Route::post('/Mission/updateworkflow/{dossid}/{id}', 'MissionController@updateWorkflow');
Route::get('/Mission/RendreInactive/{id}/{dossid}', 'MissionController@RendreInactive');
Route::get('/Mission/RendreAchevee/{id}/{dossid}', 'MissionController@RendreAchevee');
Route::get('/Mission/getAjaxWorkflow/{id}', 'MissionController@getAjaxWorkflow');
Route::get('/dossier/Mission/AnnulerMissionCourante/{iddoss}/{idact}/{idsousact}' , 'MissionController@AnnulerMissionCourante');



/*** Action**/
Route::resource('/Actions',  'ActionController');
Route::get('/Actions', array('as' => 'actions','uses' => 'ActionController@index'));
Route::post('/Actions/saving','ActionController@saving')->name('Actions.saving');
Route::get('/Actions/view/{id}', 'ActionController@view');
Route::get('/dossier/Mission/TraitementAction/{iddoss}/{idact}/{idsousact}', 
	'ActionController@TraitementAction');
Route::get('/dossier/Mission/TraitercommentAction/{iddoss}/{idact}/{idsousact}',
  'ActionController@TraitercommentAction');

Route::post('/dossier/Mission/TraitercommentActionAjax/{iddoss}/{idact}/{idsousact}',
  'ActionController@TraitercommentActionAjax');

Route::get('dossier/Mission/EnregistrerEtAllerSuivante/{iddoss}/{idact}/{idsousact}',
  'ActionController@EnregistrerEtAllerSuivante');

Route::get('dossier/Mission/AnnulerEtAllerSuivante/{iddoss}/{idact}/{idsousact}',
  'ActionController@AnnulerEtAllersuivante');

Route::get('dossier/Mission/EnregistrerEtAllerPrecedente/{iddoss}/{idact}/{idsousact}',
  'ActionController@EnregistrerEtAllerPrecedente');

Route::get('dossier/Mission/FinaliserMission/{iddoss}/{idact}/{idsousact}',
  'ActionController@FinaliserMission');

Route::get('dossier/Mission/ReporterAction/{iddoss}/{idact}/{idsousact}',
  'ActionController@ReporterAction');





/*** TypeMission**/
Route::resource('/typesMissions',  'TypeMissionController');
Route::get('/typesMissions', array('as' => 'Missions','uses' => 'TypeMissionController@index'));
Route::post('/typesMissions/saving','TypeMissionController@saving')->name('typeMissions.saving');
Route::get('/typesMissions/view/{id}', 'TypeMissionController@view');

Route::post('/TypeMissionAutocomplte','TypeMissionController@getTypeMissionAjax')->name('typeMission.autocomplete');

/*** EtapeTypeMission**/
/*Route::resource('/etapestypesMissions',  'EtapesTypeMissionController');
Route::get('/etapestypesMissions', array('as' => 'Missions','uses' => 'EtapesTypeMissionController@index'));
Route::post('/etapestypesMissions/saving','EtapesTypeMissionController@saving')->name('etapestypesMissions.saving');
Route::get('/etapestypesMissions/view/{id}', 'EtapesTypeMissionController@view');*/



/*** recherche ***/

Route::post('/RechercheMultiAutocomplete','RechercheController@rechercheMultiAjax')->name('RechercheMulti.autocomplete');

Route::post('/testRechercheMultiAutocomplete','RechercheController@test')->name('RechercheMulti.test');


/*** Users **/

//Route::resource('/users',  'UsersController');
Route::get('/users', array('as' => 'users','uses' => 'UsersController@index'));
Route::get('/users/create','UsersController@create')->name('users.create');
Route::post('/users/saving','UsersController@saving')->name('users.saving');
Route::post('/users/updating','UsersController@updating')->name('users.updating');
Route::get('/users/view/{id}', 'UsersController@view');
Route::get('/users/profile/{id}', 'UsersController@profile')->name('profile');
Route::post('/users/createuserrole', 'UsersController@createuserrole')->name('users.createuserrole');
Route::post('/users/removeuserrole', 'UsersController@removeuserrole')->name('users.removeuserrole');
Route::post('/users/sessionroles', 'UsersController@sessionroles')->name('users.sessionroles');

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

/*** Documents ***/

Route::post('/documents/adddocument','DocumentsController@adddocument')->name('documents.adddocument');
Route::post('/documents/htmlfilled','DocumentsController@htmlfilled')->name('documents.htmlfilled');
Route::post('/documents/historique','DocumentsController@historique')->name('documents.historique');
Route::post('/documents/canceldoc','DocumentsController@canceldoc')->name('documents.canceldoc');
 
 
