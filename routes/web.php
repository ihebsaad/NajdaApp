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

 
Route::get('/logs', 'HomeController@logs')->name('logs');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/roles', 'HomeController@roles')->name('roles');
Route::get('/pause', 'HomeController@pause')->name('pause');
//Route::get('/changerroles', 'HomeController@changerroles')->name('changerroles');
Route::get('/parametres', 'HomeController@parametres')->name('parametres');
Route::get('/supervision', 'HomeController@supervision')->name('supervision');
Route::get('/affectations', 'HomeController@affectation')->name('affectation');
Route::get('/affectations2', 'HomeController@affectation2')->name('affectation2');
Route::get('/affectations3', 'HomeController@affectation3')->name('affectatFion3');
Route::get('/missions', 'HomeController@missions')->name('missions');
Route::get('/Calendriermissions7', 'HomeController@Calendriermissions7')->name('Calendriermissions7');
Route::get('/actionsactives30min', 'HomeController@actionsactives30min')->name('actionsactives30min');
Route::get('/notifs', 'HomeController@notifs')->name('notifs');
Route::get('/transport', 'HomeController@transport')->name('transport');
Route::get('/transport2', 'HomeController@transport2')->name('transport2');
Route::get('/transporth', 'HomeController@transporth')->name('transporth');
Route::get('/transporttous', 'HomeController@transporttous')->name('transporttous');
Route::get('/transportsemaine', 'HomeController@transportsemaine')->name('transportsemaine');
Route::post('/parametring', 'HomeController@parametring')->name('home.parametring');
Route::post('/parametring2', 'HomeController@parametring2')->name('home.parametring2');
Route::post('/demande', 'HomeController@demande')->name('home.demande');
Route::post('/demandepause', 'HomeController@demandepause')->name('home.demandepause');
Route::post('/reponsepause', 'HomeController@reponsepause')->name('home.reponsepause');
Route::post('/removereponse', 'HomeController@removereponse')->name('home.removereponse');
Route::post('/removereponsepause', 'HomeController@removereponsepause')->name('home.removereponsepause');
Route::post('/affecterrole', 'HomeController@affecterrole')->name('home.affecterrole');
Route::get('/checkdemandes', 'HomeController@checkdemandes')->name('checkdemandes');
Route::get('/checkreponses', 'HomeController@checkreponses')->name('checkreponses');
Route::post('/updateattach', 'HomeController@updateattach')->name('updateattach');
Route::post('/deleteattach', 'HomeController@deleteattach')->name('deleteattach');
Route::get('/home/destroy/{id}', 'HomeController@destroy');
Route::get('/home/traiter/{id}', 'HomeController@traiter');
Route::post('/home/updating','HomeController@updating')->name('home.updating');




Route::get('/notifications/checknotifs', 'NotificationsController@checkNewNotifs')->name('notifications.checknotifs');




// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');
$this->get('logout', 'Auth\LoginController@logout')->name('logout');
$this->post('changerposte', 'Auth\LoginController@changerposte')->name('changerposte');
$this->get('changerposte', 'Auth\LoginController@changerposte')->name('changerposte');

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
Route::post('/home/deconnecter', 'HomeController@deconnecter')->name('home.deconnecter');

//affectation dossier dispartcheur
Route::get('/affectation/',  'AffectDossController@Interface_Affectation_DossierDispatcheur'); 
//Route::post('/affecterDossier',  'AffectDossController@affecterDossier')->name('affectation.dossier');
Route::post('/affecterDossier',  'DossiersController@attribution')->name('affectation.dossier');
Route::post('dossiers/attribution2',  'DossiersController@attribution2')->name('dossiers.attribution2');
Route::get('/getNotificationAffectationDoss/{userConnect}', 'AffectDossController@getNotificationAffectationDoss');

// delegation mission
Route::post('/deleguerMission/','DeleguerMissionController@deleguerMission')->name('Deleguer.Mission');
Route::get('/getNotificationDeleguerMiss/{userConnect}', 'DeleguerMissionController@getNotificationDeleguerMiss'); 

// delegation action
Route::post('/deleguerAction/','DeleguerActionController@deleguerAction')->name('Deleguer.Action');
Route::get('/getNotificationDeleguerAct/{userConnect}', 'DeleguerActionController@getNotificationDeleguerAct'); 
/***attachements***/

Route::post('/attachements/savecomment','AttachementsController@savecomment')->name('attachements.savecomment');
/*** Entrees **/
/* tous les emails (tous les entrees) dans la base */
Route::get('/entrees/boite', array('as' => 'boite','uses' => 'EntreesController@boite'));
Route::get('/entrees/',  'EntreesController@index')->name('entrees.index');
Route::get('/entrees/finances',  'EntreesController@finances')->name('entrees.finances');
Route::post('/entrees/saving','EntreesController@saving')->name('entrees.saving');
Route::get('/entrees/dispatching','EntreesController@dispatching')->name('entrees.dispatching');
Route::get('/entrees/enregistrements','EntreesController@enregistrements')->name('entrees.enregistrements');
Route::get('/entrees/view/{id}', 'EntreesController@view');
Route::get('/entrees/show/{id}', 'EntreesController@show')->name('entrees.show');
Route::get('/entrees/showdisp/{id}', 'EntreesController@showdisp')->name('entrees.showdisp');
Route::get('/entrees/pdf/{id}', 'EntreesController@pdf');
Route::get('/entrees/sendpdf/{id}', 'EntreesController@sendpdf');
Route::get('/entrees/export_pdf/{id}', 'EntreesController@export_pdf');
Route::get('/entrees/destroy/{id}', 'EntreesController@destroy');
Route::get('/entrees/destroy2/{id}', 'EntreesController@destroy2');
Route::get('/entrees/spam/{id}', 'EntreesController@spam');
Route::get('/entrees/archiver/{id}', 'EntreesController@archiver');
Route::get('/entrees/traiter/{id}', 'EntreesController@traiter');
Route::get('/entrees/archive/', 'EntreesController@archive')->name('entrees.archive');
Route::post('/entrees/savecomment','EntreesController@savecomment')->name('entrees.savecomment');
Route::post('/entrees/dispatchf','EntreesController@dispatchf')->name('entrees.dispatchf');
Route::post('/entrees/dispatchf2','EntreesController@dispatchf2')->name('entrees.dispatchf2');
Route::post('/entrees/ajoutcompter','EntreesController@AjoutCompteRendu')->name('entrees.ajoutcompter');
Route::get('/entrees/countnotifs','EntreesController@countnotifs')->name('entrees.countnotifs');
Route::get('/entrees/countnotifsrouge','EntreesController@countnotifsrouge')->name('entrees.countnotifsrouge');
Route::get('/entrees/countnotifsorange','EntreesController@countnotifsorange')->name('entrees.countnotifsorange');


/*** Emails **/

Route::post('/emails/send','EmailController@send');
Route::post('/emails/sendall','EmailController@sendall');
Route::post('/emails/sendallgroup','EmailController@sendallgroup');
/* envoie d'un email */
Route::get('/emails/sending','EmailController@sending')->name('emails.sending');
Route::get('/emails/envoimail/{id}/{type}','EmailController@envoimail')->name('emails.envoimail');
Route::get('/emails/envoimailtous','EmailController@envoimailtous')->name('emails.envoimailtous');
Route::get('/emails/envoimail/{id}/{type}/{prest}','EmailController@envoimail')->name('emails.envoimail');
Route::get('/emails/envoimailenreg/{id}/{type}/{prest}/{entreeid}/{envoyeid}','EmailController@envoimailenreg')->name('emails.envoimailenreg');
Route::get('/emails/envoimailbr/{id}','EmailController@envoimailbr')->name('emails.envoimailbr');
#Route::get('/emails/envoifax/{id}','EmailController@envoifax')->name('emails.envoifax');
Route::get('/emails/envoifax/{id}/{type}','EmailController@envoifax')->name('emails.envoifax');
Route::get('/emails/envoifax/{id}/{type}/{prest}','EmailController@envoifax')->name('emails.envoifax');
Route::get('/emails/sms/{id}/{type}/{prest}','EmailController@sms')->name('emails.sms');

//Route::post('/emails/searchprest','EmailController@searchprest')->name('emails.searchprest');
Route::get('/emails/searchprest','EmailController@searchprest')->name('emails.searchprest');
Route::get('/emails', 'EmailController@index');
/* unreaded emails and not checked */
Route::get('/emails/inbox', 'EmailController@inbox');
/* mark as readed and save to database */
Route::get('/emails/check', 'EmailController@check');
Route::get('/emails/checkfinances', 'EmailController@checkfinances');
Route::get('/emails/checkboite1', 'EmailController@checkboite1');
Route::get('/emails/checkboite2', 'EmailController@checkboite2');
Route::get('/emails/checkboite3', 'EmailController@checkboite3');
Route::get('/emails/checkboite4', 'EmailController@checkboite4');
Route::get('/emails/checkboite5', 'EmailController@checkboite5');
Route::get('/emails/checkboite6', 'EmailController@checkboite6');
Route::get('/emails/checkboite7', 'EmailController@checkboite7');
Route::get('/emails/checkboite8', 'EmailController@checkboite8');
Route::get('/emails/checkboite9', 'EmailController@checkboite9');
Route::get('/emails/checkfax', 'EmailController@checkfax');
Route::get('/emails/checksms', 'EmailController@checksms');
Route::get('/emails/checksmsxml', 'EmailController@checksmsxml');
Route::get('/emails/checkboiteperso', 'EmailController@checkboiteperso');
Route::get('/emails/folder/{foldername}', 'EmailController@folder');
Route::post('/emails/accuse', 'EmailController@accuse')->name('emails.accuse');
Route::post('/emails/createpdf', 'EmailController@export_pdf_send2')->name('emails.createpdf');

Route::get('/emails/test', 'EmailController@test');
Route::get('/emails/sms/{id}', 'EmailController@sms');
Route::post('/emails/sendsms', 'EmailController@sendsms')->name('emails.sendsms');
Route::post('/emails/sendsmsxml', 'EmailController@sendsmsxml')->name('emails.sendsmsxml');
Route::post('/emails/sendfax', 'EmailController@sendfax')->name('emails.sendfax');
Route::get('/emails/whatsapp', 'EmailController@whatsapp');
Route::post('/emails/sendwhatsapp', 'EmailController@sendwhatsapp')->name('emails.sendwhatsapp');

Route::get('/emails/maboite', 'EmailController@maboite');
Route::get('/emails/open/{id}', 'EmailController@open');


/****** Boite Personnelle ****/
Route::get('/boites/',  'BoitesController@index')->name('boites');
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
Route::get('/envoyes/tous', 'EnvoyesController@tous')->name('envoyes.tous');


/*** Dossiers **/
//Route::resource('/dossiers',  'DossiersController');
Route::get('/dossiers', array('as' => 'dossiers','uses' => 'DossiersController@index'));
Route::post('/dossiers/saving','DossiersController@saving')->name('dossiers.saving');

Route::post('/dossiers/save','DossiersController@save')->name('dossiers.save');
Route::post('/dossiers/sendaccuse','DossiersController@sendaccuse')->name('dossiers.sendaccuse');
Route::get('/dossiers/create/{identree}','DossiersController@create')->name('dossiers.create');
Route::get('/dossiers/add/','DossiersController@add')->name('dossiers.add');
Route::post('/dossiers/updating','DossiersController@updating')->name('dossiers.updating');
Route::post('/dossiers/updating2','DossiersController@updating2')->name('dossiers.updating2');
Route::post('/dossiers/updating3','DossiersController@updating3')->name('dossiers.updating3');
Route::get('/dossiers/view/{id}', 'DossiersController@view')->name('dossiers.view');
Route::get('/dossiers/fiche/{id}', 'DossiersController@fiche')->name('dossiers.fiche');
Route::get('/dossiers/update/{id}', 'DossiersController@update')->name('dossiers.update');
Route::post('/dossiers/addemail','DossiersController@addemail')->name('dossiers.addemail');
Route::post('/dossiers/attribution','DossiersController@attribution')->name('dossiers.attribution');
Route::post('/dossiers/listepres','DossiersController@ListePrestataireCitySpec')->name('dossiers.listepres');
Route::post('/dossiers/listepresm','DossiersController@ListePrestataireCitySpec2')->name('dossiers.listepresm');
Route::post('/dossiers/addressadd','DossiersController@addressadd')->name('dossiers.addressadd');
Route::post('/dossiers/addressadd2','DossiersController@addressadd2')->name('dossiers.addressadd2');
Route::post('/dossiers/checkexiste','DossiersController@checkexiste')->name('dossiers.checkexiste');
Route::get('/searchprest','DossiersController@searchprest')->name('searchprest');
Route::get('/dossiers/dossiersactifs','DossiersController@DossiersActifs')->name('dossiers.dossiersactifs');
Route::get('/dossiers/dossiersinactifs','DossiersController@DossiersInactifs')->name('dossiers.dossiersinactifs');
Route::get('/dossiers/dossiersImmobiles','DossiersController@DossiersImmobiles')->name('dossiers.dossiersimmobiles');
Route::get('/dossiers/dossiersDormants','DossiersController@DossiersDormants')->name('dossiers.dossiersdormants');
//Route::get()
Route::post('/dossiers/rendreactif','DossiersController@rendreActif')->name('dossiers.rendreactif');
Route::get('/dossiers/inactifs','DossiersController@inactifs')->name('inactifs');
Route::get('/dossiers/activerdossiers','DossiersController@ActiverDossiers')->name('activerdossiers');
Route::post('/dossiers/changestatut','DossiersController@changestatut')->name('dossiers.changestatut');
Route::get('/dossiers/affectclassique','DossiersController@affectclassique')->name('dossiers.affectclassique');
Route::post('/ExternefileUpload/upload', 'DossiersController@uploadExterneFile')->name('Upload.ExterneFile');
Route::get('/Dossier/historiqueaffectation/{id}', 'DossiersController@historiqueAffectation')->name('historique.affectation');
Route::get('/dossiers/details/{id}', 'DossiersController@details')->name('dossiers.details');
Route::get('/dossiers/details2', 'DossiersController@details2')->name('dossiers.details2');
Route::get('/dossiers/fermeture/{id}', 'DossiersController@fermeture')->name('dossiers.fermeture');
Route::get('/listeUsersDoss/{iddoss}', 'DossiersController@users_work_on_folder');
Route::post('/addappel', 'DossiersController@addappel')->name('addappel');




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
//Route::get('/clients/view/{id}', 'ClientsController@view');
Route::get('/clients/view2', 'ClientsController@view2');
Route::get('/clients/view/{id}', 'ClientsController@view');
Route::get('/clients/destroy/{id}', 'ClientsController@destroy');
Route::get('/clients/deleteaddress/{id}', 'ClientsController@deleteaddress')->name('clients.deleteaddress');
Route::post('/clients/updateaddress', 'ClientsController@updateaddress')->name('clients.updateaddress');
Route::get('/clients/dossiers/{id}', 'ClientsController@dossiers')->name('clients.dossiers');
Route::get('/clients/ouverts/{id}', 'ClientsController@ouverts')->name('clients.ouverts');
Route::get('/clients/mailsclients', 'ClientsController@mailsclients')->name('clients.mails');


/*** Cities -> Gouvernorats  **/
//Route::resource('/cities',  'CitiesController');
Route::get('/cities', array('as' => 'cities','uses' => 'CitiesController@index'));
Route::post('/cities/saving','CitiesController@saving')->name('cities.saving');
Route::post('/cities/updating','CitiesController@updating')->name('cities.updating');
Route::get('/cities/view/{id}', 'CitiesController@view');
Route::get('/cities/destroy/{id}', 'CitiesController@destroy');


/*** Factures  **/

Route::get('/factures', array('as' => 'factures','uses' => 'FacturesController@index'));
Route::post('/factures/saving','FacturesController@saving')->name('factures.saving');
Route::post('/factures/updating','FacturesController@updating')->name('factures.updating');
Route::get('/factures/view/{id}', 'FacturesController@view');
Route::get('/factures/destroy/{id}', 'FacturesController@destroy');
Route::post('/factures/updatingCheck','FacturesController@updatingCheck')->name('factures.updatingCheck');





/*** Actualites  **/
//Route::resource('/actualites',  'ActualitesController');
Route::get('/actualites', array('as' => 'actualites','uses' => 'ActualitesController@index'));
Route::post('/actualites/saving','ActualitesController@saving')->name('actualites.saving');
Route::post('/actualites/updating','ActualitesController@updating')->name('actualites.updating');
Route::post('/actualites/updating2','ActualitesController@updating2')->name('actualites.updating2');
Route::get('/actualites/view/{id}', 'ActualitesController@view');
Route::get('/actualites/destroy/{id}', 'ActualitesController@destroy');
 

/*** Voitures ->Véhicules   **/
//Route::resource('/voitures',  'VoituresController');
Route::get('/voitures', array('as' => 'voitures','uses' => 'VoituresController@index'));
Route::post('/voitures/saving','VoituresController@saving')->name('voitures.saving');
Route::post('/voitures/updating','VoituresController@updating')->name('voitures.updating');
Route::get('/voitures/view/{id}', 'VoituresController@view');
Route::get('/voitures/destroy/{id}', 'VoituresController@destroy');


/*** Equipements -> equipements   **/
//Route::resource('/equipements',  'VoituresController');
Route::get('/equipements', array('as' => 'equipements','uses' => 'EquipementsController@index'));
Route::post('/equipements/saving','EquipementsController@saving')->name('equipements.saving');
Route::post('/equipements/updating','EquipementsController@updating')->name('equipements.updating');
Route::post('/equipements/updating2','EquipementsController@updating2')->name('equipements.updating2');
Route::get('/equipements/view/{id}', 'EquipementsController@view');
Route::get('/equipements/destroy/{id}', 'EquipementsController@destroy');



/*** personnes -> personnels   **/
//Route::resource('/personnes',  'PersonnesController');
Route::get('/personnes', array('as' => 'personnes','uses' => 'PersonnesController@index'));
Route::post('/personnes/saving','PersonnesController@saving')->name('personnes.saving');
Route::post('/personnes/updating','PersonnesController@updating')->name('personnes.updating');
Route::get('/personnes/view/{id}', 'PersonnesController@view');
Route::get('/personnes/destroy/{id}', 'PersonnesController@destroy');
Route::get('/personnes/mailspersonnes', 'PersonnesController@mailspersonnes')->name('personnes.mailspersonnes');


/*** docs -> documents à signer   **/
//Route::resource('/docs',  'DocsController');
Route::get('/docs', array('as' => 'docs','uses' => 'DocsController@index'));
Route::post('/docs/saving','DocsController@saving')->name('docs.saving');
Route::post('/docs/updating','DocsController@updating')->name('docs.updating');
Route::get('/docs/view/{id}', 'DocsController@view');
Route::post('/docs/removespec','DocsController@removespec')->name('docs.removespec');
Route::post('/docs/createspec','DocsController@createspec')->name('docs.createspec');
Route::post('/docs/removedocdossier','DocsController@removedocdossier')->name('docs.removedocdossier');
Route::post('/docs/createdocdossier','DocsController@createdocdossier')->name('docs.createdocdossier');
Route::get('/docs/destroy/{id}', 'DocsController@destroy');


/*** contrats -> Contrats Clients   **/

Route::get('/contrats', array('as' => 'contrats','uses' => 'ContratsController@index'));
Route::get('/contrats/add','ContratsController@add')->name('contrats.add');
Route::post('/contrats/saving','ContratsController@saving')->name('contrats.saving');
Route::post('/contrats/adding','ContratsController@adding')->name('contrats.adding');
Route::post('/contrats/updating','ContratsController@updating')->name('contrats.updating');
Route::post('/contrats/changing','ContratsController@changing')->name('contrats.changing');
Route::get('/contrats/view/{id}', 'ContratsController@view');
Route::get('/contrats/nature/{id}', 'ContratsController@nature');
Route::post('/contrats/removespec','ContratsController@removespec')->name('contrats.removespec');
Route::post('/contrats/createspec','ContratsController@createspec')->name('contrats.createspec');
Route::post('/contrats/removedocdossier','ContratsController@removedocdossier')->name('contrats.removedocdossier');
Route::post('/contrats/createdocdossier','ContratsController@createdocdossier')->name('contrats.createdocdossier');
Route::get('/contrats/destroy/{id}', 'ContratsController@destroy');




/*** Groupes Clients **/
//Route::resource('/clientgroupes',  'ClientGroupesController');
Route::get('/clientgroupes', array('as' => 'clientgroupes','uses' => 'ClientGroupesController@index'));
Route::post('/clientgroupes/saving','ClientGroupesController@saving')->name('clientgroupes.saving');
Route::post('/clientgroupes/updating','ClientGroupesController@updating')->name('clientgroupes.updating');
Route::get('/clientgroupes/view/{id}', 'ClientGroupesController@view');
Route::get('/clientgroupes/destroy/{id}', 'ClientGroupesController@destroy');



/*** Garanties  **/
 Route::get('/garanties', array('as' => 'garanties','uses' => 'GarantiesController@index'));
Route::post('/garanties/saving','GarantiesController@saving')->name('garanties.saving');
Route::post('/garanties/savingRB','GarantiesController@savingRB')->name('garanties.savingRB');
Route::post('/garanties/updating','GarantiesController@updating')->name('garanties.updating');
Route::post('/garanties/updaterubrique','GarantiesController@updaterubrique')->name('garanties.updaterubrique');
Route::get('/garanties/view/{id}', 'GarantiesController@view');
Route::get('/garanties/destroy/{id}', 'GarantiesController@destroy');
Route::get('/garanties/deleterubrique/{id}', 'GarantiesController@deleterubrique');
Route::post('/garanties/addgr', 'GarantiesController@addgr')->name('garanties.addgr');
Route::post('/garanties/removegr', 'GarantiesController@removegr')->name('garanties.removegr');
Route::post('/garanties/inforubrique','GarantiesController@inforubrique')->name('garanties.inforubrique');

 Route::get('/rubriques', array('as' => 'rubriques','uses' => 'RubriquesController@index'));
Route::post('/rubriques/saving','RubriquesController@saving')->name('rubriques.saving');
Route::get('/rubriques/view/{id}', 'RubriquesController@view');
Route::post('/rubriques/updating','RubriquesController@updating')->name('rubriques.updating');
Route::get('/rubriques/destroy/{id}', 'RubriquesController@destroy');

/*** Prestataires **/
//Route::resource('/prestataires',  'PrestatairesController');
Route::get('/prestataires', array('as' => 'prestataires','uses' => 'PrestatairesController@index'));
Route::get('/prestataires/mails','PrestatairesController@mails')->name('prestataires.mails');
Route::post('/prestataires/saving','PrestatairesController@saving')->name('prestataires.saving');
Route::post('/prestataires/saving2','PrestatairesController@saving2')->name('prestataires.saving2');
Route::post('/prestataires/updating','PrestatairesController@updating')->name('prestataires.updating');
Route::post('/prestataires/updaterating','PrestatairesController@updaterating')->name('prestataires.updaterating');
Route::post('/prestataires/removetypeprest','PrestatairesController@removetypeprest')->name('prestataires.removetypeprest');
Route::post('/prestataires/createtypeprest','PrestatairesController@createtypeprest')->name('prestataires.createtypeprest');
Route::post('/prestataires/removecitieprest','PrestatairesController@removecitieprest')->name('prestataires.removecitieprest');
Route::post('/prestataires/createcitieprest','PrestatairesController@createcitieprest')->name('prestataires.createcitieprest');
Route::post('/prestataires/removespec','PrestatairesController@removespec')->name('prestataires.removespec');
Route::post('/prestataires/createspec','PrestatairesController@createspec')->name('prestataires.createspec');
Route::get('/prestataires/view/{id}', 'PrestatairesController@view');
Route::get('/ratings/view/{id}', 'PrestatairesController@view_rating');
Route::post('/prestataires/addeval','PrestatairesController@addeval')->name('prestataires.addeval');
Route::post('/prestataires/addrating','PrestatairesController@addrating')->name('prestataires.addrating');
Route::post('/prestataires/addemail','PrestatairesController@addemail')->name('prestataires.addemail');
Route::post('/prestataires/addressadd','PrestatairesController@addressadd')->name('prestataires.addressadd');
Route::post('/prestataires/NomPrestatireById','PrestatairesController@NomPrestatireById')->name('prestataires.NomPrestatireById');
Route::post('/prestataires/activer','PrestatairesController@activer')->name('prestataires.activer');


Route::get('/prestataires/destroy/{id}', 'PrestatairesController@destroy');
Route::get('/prestataires/create/{id}', 'PrestatairesController@create')->name('prestataires.create');
Route::post('/prestataires/checkexiste', 'PrestatairesController@checkexiste')->name('prestataires.checkexiste');
Route::post('/prestataires/checkexisteprname', 'PrestatairesController@checkexistePrName')->name('prestataires.checkexisteprname');
Route::post('/prestataires/listesprest', 'PrestatairesController@listesprest')->name('prestataires.listesprest');
Route::post('/prestataires/listetypes', 'PrestatairesController@listetypes')->name('prestataires.listetypes');


/*** Prestations **/
//Route::resource('/prestations',  'PrestationsController');
Route::get('/prestations', array('as' => 'prestations','uses' => 'PrestationsController@index'));
 Route::post('/prestations/saving','PrestationsController@saving')->name('prestations.saving');
 Route::post('/prestations/updating','PrestationsController@updating')->name('prestations.updating');
Route::get('/prestations/view/{id}', 'PrestationsController@view');
Route::post('/prestations/updating','PrestationsController@updating')->name('prestations.updating');
Route::post('/prestations/updatestatut','PrestationsController@updatestatut')->name('prestations.updatestatut');
Route::post('/prestations/valide','PrestationsController@valide')->name('prestations.valide');
Route::get('/prestations/destroy/{id}', 'PrestationsController@destroy');
Route::get('/prestations/deleteeval/{id}', 'PrestationsController@deleteeval');
Route::post('/prestations/updatepriorite', 'PrestationsController@updatepriorite')->name('prestations.updatepriorite');
Route::post('/prestations/updateevaluation', 'PrestationsController@updateevaluation')->name('prestations.updateevaluation');
Route::post('/prestation/updatingParvenu','PrestationsController@updatingParvenu')->name('Prestation.updatingParvenu');




/*** Intervenants **/
//Route::resource('/intervenants',  'IntervenantsController');
Route::get('/intervenants', array('as' => 'intervenants','uses' => 'IntervenantsController@index'));
Route::post('/intervenants/saving','IntervenantsController@saving')->name('intervenants.saving');
Route::post('/intervenants/updating','IntervenantsController@updating')->name('intervenants.updating');
Route::get('/intervenants/view/{id}', 'IntervenantsController@view');
Route::post('/intervenants/updating','IntervenantsController@updating')->name('intervenants.updating');
Route::get('/intervenants/destroy/{id}', 'IntervenantsController@destroy');



/*** Type Prestations  **/
//Route::resource('/typeprestations',  'TypePrestationsController');
Route::get('/typeprestations', array('as' => 'typeprestations','uses' => 'TypePrestationsController@index'));
 Route::post('/typeprestations/saving','TypePrestationsController@saving')->name('typeprestations.saving');
Route::post('/typeprestations/updating','TypePrestationsController@updating')->name('typeprestations.updating');;
Route::get('/typeprestations/view/{id}', 'TypePrestationsController@view');
Route::get('/typeprestations/destroy/{id}', 'TypePrestationsController@destroy');

/*** Specialités **/
//Route::resource('/specialites',  'SpecialitesController');
Route::get('/specialites', array('as' => 'specialites','uses' => 'SpecialitesController@index'));
 Route::post('/specialites/saving','SpecialitesController@saving')->name('specialites.saving');
Route::post('/specialites/updating','SpecialitesController@updating')->name('specialites.updating');;
Route::post('/specialites/createspec','SpecialitesController@createspec')->name('specialites.createspec');
Route::post('/specialites/removespec','SpecialitesController@removespec')->name('specialites.removespec');
Route::get('/specialites/view/{id}', 'SpecialitesController@view');
Route::get('/specialites/destroy/{id}', 'SpecialitesController@destroy');


/*** Notes **/
//Route::resource('/notes',  'NotesController');
Route::get('/notes', array('as' => 'notes','uses' => 'NotesController@index'));
Route::post('/notes/updating','NotesController@updating')->name('notes.updating');
Route::get('/notes/view/{id}', 'NotesController@view');
Route::post('/Note/store','NotesController@store')->name('Note.store');
Route::get('/getNotesReporteesAjax','NotesController@getNotesReporteesAjax');
Route::get('/getNotesEnvoyeesAjax/','NotesController@getNotesEnvoyeesAjax');
Route::get('/getAjaxUsersNote/{id}','NotesController@getAjaxUsersNote');
Route::get('/EnvoyerNote/','NotesController@EnvoyerNote')->name('Envoyer.Note');

Route::get('/getNotesAjaxModal','NotesController@getNotesAjaxModal');
Route::get('/SupprimerNoteAjax/{id}','NotesController@SupprimerNoteAjax');
Route::get('/SupprimerNote/{id}','NotesController@SupprimerNote');
Route::get('/ReporterNote/{id}','NotesController@ReporterNote');
Route::get('/notes/destroy/{id}', 'NotesController@destroy');


 
/*** Missions**/
Route::post('/Missions/storeMissionByAjax', ['uses'=>'MissionController@storeMissionByAjax',
'as'=>'Mission.StoreMissionByAjax']);
//Route::resource('/Missions',  'MissionController');
Route::get('/Missions', array('as' => 'Missions','uses' => 'MissionController@index'));
Route::post('/Missions/saving','MissionController@saving')->name('Missions.saving');
Route::post('/Missions/store','MissionController@store')->name('Missions.store');
Route::post('/Missions/storeActionsEnCours','MissionController@storeTableActionsEnCours')->name('Missions.storeActionsEC');
Route::get('/Missions/view/{id}', 'MissionController@view');
Route::get('/Mission/workflow/{dossid}/{id}', 'MissionController@getWorkflow');
Route::post('/Mission/updateworkflow/', 'MissionController@updateWorkflow');

Route::get('/Mission/RendreInactive/{id}/{dossid}', 'MissionController@RendreInactive');
Route::get('/Mission/RendreAchevee/{id}/{dossid}', 'MissionController@RendreAchevee');
Route::get('/Mission/getAjaxWorkflow/{id}', 'MissionController@getAjaxWorkflow');
Route::get('/Mission/getAjaxWorkflowMach/{id}', 'MissionController@getAjaxWorkflowMach');
Route::get('/dossier/Mission/AnnulerMissionCourante/{iddoss}/{idact}/{idsousact}' , 'MissionController@AnnulerMissionCourante');
Route::get('/getMissionAjaxModal', 'MissionController@getMissionsAjaxModal');
Route::get('/Mission/getAjaxDeleguerMission/{id}', 'MissionController@getAjaxDeleguerMission');
Route::get('/Mission/AnnulerMissionCouranteByAjax/{id}', 'MissionController@AnnulerMissionCouranteByAjax');
Route::get('/Mission/getMailGenerator/{id}', 'MissionController@getMailGeneratorByAjax');
Route::get('/Mission/getMailGeneratorMAch/{id}', 'MissionController@getMailGeneratorByAjaxMAch');

Route::get('/Mission/getDescriptionMissionAjax/{id}', 'MissionController@getDescriptionMissionAjax');

Route::get('/dossiers/view/CreerOM/{id}/{idmiss}', 'MissionController@viewDossierMission');
Route::get('dossiers/view/CreerDoc/{id}/{idmiss}', 'MissionController@viewDossierMission');
/*Route::post('/Missions/storeMissionByAjax','MissionController@storeMissionByAjax')->name('Mission.StoreMissionByAjax');*/

Route::post('/Missions/storeMissionLieByAjax','MissionController@storeMissionLieByAjax')->name('Mission.StoreMissionLieByAjax');
Route::get('/ReporterMission/','MissionController@ReporterMission')->name('Mission.ReporterMission');
Route::get('/missions/calendrier','MissionController@calendrierMissions')->name('missions.calendriermissions');
Route::get('/missions/statistiques','MissionController@missionsStatistiques')->name('missions.statistiques');
Route::get('/missions/actionsstatistiques/{idmiss}','MissionController@actionsStatistiques')->name('actions.statistiques');





/*** Action**/
Route::resource('/Actions','ActionController');
Route::get('/Actions', array('as' => 'actions','uses' => 'ActionController@index'));
Route::post('/Actions/saving','ActionController@saving')->name('Actions.saving');
Route::get('/Actions/view/{id}', 'ActionController@view');
Route::get('/dossier/Mission/TraitementAction/{iddoss}/{idact}/{idsousact}', 
	'ActionController@TraitementAction');
Route::get('/dossier/Mission/TraitercommentAction/{iddoss}/{idact}/{idsousact}',
  'ActionController@TraitercommentAction');

Route::get('/dossier/Mission/TraitercommentActionAjax/{iddoss}/{idact}/{idsousact}',
  'ActionController@TraitercommentActionAjax');

/*Route::get('dossier/Mission/EnregistrerEtAllerSuivante/{iddoss}/{idact}/{idsousact}',
  'ActionController@EnregistrerEtAllerSuivante');*/
  Route::get('dossier/Mission/Fait/{iddoss}/{idact}/{idsousact}',
  'ActionController@BoutonFait');

Route::get('dossier/Mission/AnnulerEtAllerSuivante/{iddoss}/{idact}/{idsousact}',
  'ActionController@AnnulerEtAllersuivante');

Route::get('dossier/Mission/EnregistrerEtAllerPrecedente/{iddoss}/{idact}/{idsousact}',
  'ActionController@EnregistrerEtAllerPrecedente');

Route::get('dossier/Mission/FinaliserMission/{iddoss}/{idact}/{idsousact}',
  'ActionController@FinaliserMission');

Route::get('dossier/Mission/ReporterAction/{iddoss}/{idact}/{idsousact}',
  'ActionController@ReporterAction');

Route::get('dossier/Mission/RappelAction/{iddoss}/{idact}/{idsousact}',
  'ActionController@RappelAction');

Route::get('/getActionAjaxModal','ActionController@getActionsAjaxModal');


Route::get('/activerActionsReporteeOuRappelee','ActionController@activerActionsReporteeOuRappelee');

Route::get('/activerAct_des_dates_speciales','ActionController@activerAct_des_dates_speciales');

Route::post('/traitementsBoutonsActions/{iddoss}/{idmiss}/{idsousact}/{bouton}',
    'ActionController@Bouton_Faire1_ignorer2_reporter3_rappeler4');

Route::get('/annulerAttenteReponseAction/{idact}','ActionController@annulerAttenteReponseAction');

Route::post('/Actions/traiterDatesSpecifiques','ActionController@traiterDatesSpecifiques')->name('Action.dateSpecifique');

Route::get('/reactiveraction/{idact}','ActionController@reactiverAction');


/*** TypeMission**/
//Route::resource('/typesMissions',  'TypeMissionController');
Route::get('/typesMissions', array('as' => 'Missions','uses' => 'TypeMissionController@index'));
Route::post('/typesMissions/saving','TypeMissionController@saving')->name('typeMissions.saving');
Route::get('/typesMissions/view/{id}', 'TypeMissionController@view');

Route::post('/TypeMissionAutocomplte','TypeMissionController@getTypeMissionAjax')->name('typeMission.autocomplete');
Route::post('/typesmissions/loading','TypeMissionController@loading')->name('typesmissions.loading');
Route::post('/typesmissions/updatedesc','TypeMissionController@updatedesc')->name('typesmissions.updatedesc');
Route::post('/typesmissions/updatedescact','TypeMissionController@updatedescact')->name('typesmissions.updatedescact');
Route::post('/typesmissions/updatecharge','TypeMissionController@updatecharge')->name('typesmissions.updatecharge');

/*** EtapeTypeMission**/
/*Route::resource('/etapestypesMissions',  'EtapesTypeMissionController');
Route::get('/etapestypesMissions', array('as' => 'Missions','uses' => 'EtapesTypeMissionController@index'));
Route::post('/etapestypesMissions/saving','EtapesTypeMissionController@saving')->name('etapestypesMissions.saving');
Route::get('/etapestypesMissions/view/{id}', 'EtapesTypeMissionController@view');*/



/*** recherche ***/

Route::post('/RechercheMultiAutocomplete','RechercheController@rechercheMultiAjax')->name('RechercheMulti.autocomplete');

Route::post('/testRechercheMultiAutocomplete','RechercheController@test')->name('RechercheMulti.test');

Route::get('/pageRechercheAvancee','RechercheController@pageRechercheAvancee')->name('page_recherche.avancee');

Route::get('/RecherchePrestataireAvancee','RechercheController@RecherchePrestataireAvancee')->name('recherchePrestataire.avancee');
Route::get('/prestataire/tousprestataires', 'RechercheController@touslesprestataires');

Route::get('/Recherchemissions','RechercheController@RechercheMissions')->name('recherchemissions.avancee');

  

/*** Users **/

//Route::resource('/users',  'UsersController');
Route::get('/users', array('as' => 'users','uses' => 'UsersController@index'));
Route::get('/users/create','UsersController@create')->name('users.create');
Route::post('/users/saving','UsersController@saving')->name('users.saving');
Route::post('/users/updating','UsersController@updating')->name('users.updating');
Route::get('/users/view/{id}', 'UsersController@view');
Route::get('/users/stats/{id}', 'UsersController@stats');
Route::get('/users/profile/{id}', 'UsersController@profile')->name('profile');
Route::post('/users/createuserrole', 'UsersController@createuserrole')->name('users.createuserrole');
Route::post('/users/removeuserrole', 'UsersController@removeuserrole')->name('users.removeuserrole');
Route::post('/users/sessionroles', 'UsersController@sessionroles')->name('users.sessionroles');
Route::post('/changestatut', 'UsersController@changestatut')->name('users.changestatut');
Route::get('/users/mails', 'UsersController@mails')->name('users.mails');

Route::get('/users/destroy/{id}', 'UsersController@destroy');
//Route::get('/edit/{id}','UsersController@edit');
Route::post('/edit/{id}','UsersController@update');


/**** LOGS  ****/
//Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('logs');;
Route::get('errors', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@errors');

/**** TAGS  ****/
Route::post('/tags/addnew','TagsController@addnew')->name('tags.addnew');
Route::post('/tags/deletetag','TagsController@deletetag')->name('tags.deletetag');
Route::post('/tags/historique','TagsController@historique')->name('tags.historique');
Route::post('/tags/entreetags','TagsController@entreetags')->name('tags.entreetags');
Route::post('/tags/entreetags1','TagsController@entreetags1')->name('tags.entreetags1');
Route::post('/tags/infotag','TagsController@infotag')->name('tags.infotag');

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
Route::post('/documents/attachdocs','DocumentsController@attachdocument')->name('documents.attachdocs');

/*** Ordre Missions  ***/

Route::post('/ordremissions/export_pdf_odmtaxi','OrdreMissionsController@export_pdf_odmtaxi')->name('ordremissions.export_pdf_odmtaxi');
Route::post('/ordremissions/historique','OrdreMissionsController@historique')->name('ordremissions.historique');
Route::post('/ordremissions/valide','OrdreMissionsController@valide')->name('ordremissions.valide');
//Route::get('/entrees/sendpdf/{id}', 'EntreesController@sendpdf');
Route::get('/ordremissions/pdfodmtaxi','OrdreMissionsController@pdfodmtaxi')->name('ordremissions.pdfodmtaxi');
Route::post('/ordremissions/cancelom','OrdreMissionsController@cancelom')->name('ordremissions.cancelom');
 
Route::get('/ordremissions/pdfodmambulance','OrdreMissionsController@pdfodmambulance')->name('ordremissions.pdfodmambulance');
Route::post('/ordremissions/export_pdf_odmambulance','OrdreMissionsController@export_pdf_odmambulance')->name('ordremissions.export_pdf_odmambulance');
Route::get('/ordremissions/pdfodmremorquage','OrdreMissionsController@pdfodmremorquage')->name('ordremissions.pdfodmremorquage');
Route::post('/ordremissions/export_pdf_odmremorquage','OrdreMissionsController@export_pdf_odmremorquage')->name('ordremissions.export_pdf_odmremorquage'); 
Route::get('/ordremissions/pdfodmmedicinternationnal','OrdreMissionsController@pdfodmmedicinternationnal')->name('ordremissions.pdfodmmedicinternationnal');
Route::post('/ordremissions/export_pdf_odmmedicinternationnal','OrdreMissionsController@export_pdf_odmmedicinternationnal')->name('ordremissions.export_pdf_odmmedicinternationnal');
Route::post('/ordremissions/attachoms','OrdreMissionsController@attachordremission')->name('ordremissions.attachoms');
Route::post('/ordremissions/verifdossiers','OrdreMissionsController@verifdossierexistant')->name('ordremissions.verifdossiers');


Route::get('/update_time_miss', function () {

	$dosss=App\Dossier::get();
         $dtc = (new \DateTime())->modify('-4 days')->format('Y-m-d H:i:s');                         
         $format = "Y-m-d H:i:s";
         $dateSys  = \DateTime::createFromFormat($format, $dtc);
         
         foreach ($dosss as $dos) {        	
        
         if($dos)
         {
              $dos->update(array('updatedmiss_at'=>$dateSys));
             
          }

          }


	});



Route::get('/update_immobile_non', function () {

	$dosss=App\DossierImmobile::get();

       
         
         foreach ($dosss as $dos) {        	
        
         if($dos)
         {
             
           $dos->update(array('mail_auto_envoye'=>'Oui'));
             
          }

          }


	});

