<?php

namespace App\Http\Controllers;
use App\Adresse;
use App\Boite;
use App\Email;
use App\Notifications\Notif_Suivi_Doss;
use App\Prestation;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use DB;
use Swift_Mailer;
use Webklex\IMAP\Client;
use App\Entree ;
use App\Dossier ;
use App\User ;
use App\Attachement ;
use Mail;
use Spatie\PdfToText\Pdf;
use App;
Use Redirect;
use App\Envoye ;
use PDF as PDF2;
use Illuminate\Support\Facades\Auth;

 use Auth as auth2 ;
use App\Parametre;
use Notification;


class EmailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

    }



    function index()
    {
        Log::info('opening emails index');

        //     $oClient = Client::account('default');
        //       $oClient->connect();
        $oClient = new Client([
            'host'          => 'ssl0.ovh.net',// env('hostreception'),
            'port'          => '993',// env('portreception'),
            //    'encryption'    => '',//env('encreception'),
            'validate_cert' => true,
            'username'      =>'test@najda-assistance.com',
            'password'      => 'esol@2109',
            'protocol'      => 'imap'
        ]);


//Connect to the IMAP Server
        $oClient->connect();

        $oFolder = $oClient->getFolder('INBOX');


        $aMessage = $oFolder->messages()->all()->get();
        // Recherche
     /*   $aMessage = $oFolder->query()->text('tesssst')->get();*/

        $paginator = $aMessage->paginate();

//}

        $dossiers = Dossier::all();


        return view('emails.index', ['paginator'=>$paginator,'dossiers' => $dossiers]);
    }

// voir la liste des emails par dossier
    function folder( $foldername)
    {

        //     $oClient = Client::account('default');
        //       $oClient->connect();
/*
        $oClient = new Client([
            'host'          =>  env('hostreception'),
            'port'          =>  env('portreception'),
         //   'encryption'    => env('encreception'),
            'validate_cert' => true,
            'username'      => env('emailreception'),
            'password'      => env('passreception'),
            'protocol'      => 'imap'
        ]);

*/
        $oClient = new Client([
            'host'          => 'ssl0.ovh.net',// env('hostreception'),
            'port'          => '993',// env('portreception'),
            //    'encryption'    => '',//env('encreception'),
            'validate_cert' => true,
            'username'      =>'test@najda-assistance.com',
            'password'      => 'esol@2109',
            'protocol'      => 'imap'
        ]);
//Connect to the IMAP Server
        $oClient->connect();

        $oFolder = $oClient->getFolder($foldername);


        $aMessage = $oFolder->messages()->all()->get();
        // Recherche
        /*   $aMessage = $oFolder->query()->text('tesssst')->get();*/

        $paginator = $aMessage->paginate();


        $dossiers = Dossier::all();

        return view('emails.folder', ['paginator'=>$paginator,'aMessage'=>$aMessage,'dossiers' => $dossiers]);
    }

    // voir la liste des emails par dossier

    function open( $uid)
    {/*
        // lire les informations depuis le compte de l utilisateur (db)
        $email="test@najda-assistance.com";
        $pass="esol@2019";
        $oClient = new Client([
            'host'          =>  env('hostreception'),
            'port'          =>  env('portreception'),
            //   'encryption'    => env('encreception'),
            'validate_cert' => true,
            'username'      => env('emailreception'),
            'password'      => env('passreception'),
            'protocol'      => 'imap'
        ]);

*/

        $oClient = new Client([
            'host'          => 'ssl0.ovh.net',// env('hostreception'),
            'port'          => '993',// env('portreception'),
            //    'encryption'    => '',//env('encreception'),
            'validate_cert' => true,
            'username'      =>'test@najda-assistance.com',
            'password'      => 'esol@2109',
            'protocol'      => 'imap'
        ]);
//Connect to the IMAP Server
        $oClient->connect();


        //$oFolder = $oClient->getFolder('INBOX');
        $oFolder = $oClient->getFolder('test');
         $oMessage = $oFolder->getMessage($uid);


        // Recherche
        /*   $aMessage = $oFolder->query()->text('tesssst')->get();*/

        $dossiers = Dossier::all();


        return view('emails.open', ['oMessage'=>$oMessage,'dossiers' => $dossiers]);
    }

    function maboite( )
    {
        // lire les informations depuis le compte de l utilisateur (db)
        $email="test@najda-assistance.com";
        $pass="esol@2109";
        /*
        $oClient = new Client([
            'host'          =>  env('hostreception'),
            'port'          =>  env('portreception'),
        //    'encryption'    => env('encreception'),
            'validate_cert' => true,
            'username'      => $email,
            'password'      => $pass,
            'protocol'      => 'imap'
        ]);
*/
        $oClient = new Client([
            'host'          => 'ssl0.ovh.net',// env('hostreception'),
            'port'          => '993',// env('portreception'),
            //    'encryption'    => '',//env('encreception'),
            'validate_cert' => true,
            'username'      =>'test@najda-assistance.com',
            'password'      => 'esol@2109',
            'protocol'      => 'imap'
        ]);


//Connect to the IMAP Server
        $oClient->connect();


        //$oFolder = $oClient->getFolder('INBOX');
        $oFolder = $oClient->getFolder('test');


        $aMessage = $oFolder->messages()->all()->limit(5, 1)->get();
      //  $aMessage = $oFolder->query()->unseen()->get();

        //->limit(10, 2)
        //->limit(10, 2)
        // Recherche
        /*   $aMessage = $oFolder->query()->text('tesssst')->get();*/

        $paginator = $aMessage->paginate();
        $dossiers = Dossier::all();


        return view('emails.maboite', ['paginator'=>$paginator,'aMessage'=>$aMessage,'dossiers' => $dossiers]);
    }

    function inbox()
    {

    /* $oClient = new Client([
            'host'          => 'ssl0.ovh.net',// env('hostreception'),
            'port'          => '993',// env('portreception'),
        //    'encryption'    => '',//env('encreception'),
            'validate_cert' => true,
            'username'      => env('emailreception'),
            'password'      => env('passreception'),
            'protocol'      => 'imap'
        ]);
*/

        $oClient = new Client([
            'host'          => 'ssl0.ovh.net',// env('hostreception'),
            'port'          => '993',// env('portreception'),
            //    'encryption'    => '',//env('encreception'),
            'validate_cert' => true,
            'username'      =>'test@najda-assistance.com',
            'password'      => 'esol@2109',
            'protocol'      => 'imap'
        ]);

//Connect to the IMAP Server
        $oClient->connect();


        $oFolder = $oClient->getFolder('INBOX');
        $aMessage = $oFolder->messages()->all()->get();
        $paginator = $aMessage->paginate();

        $dossiers = Dossier::all();

        return view('emails.inbox', ['paginator'=>$paginator,'aMessage'=>$aMessage,'dossiers' => $dossiers]);

    } /// end inbox


    function check()
    {
/*
        $oClient = new Client([
            'host'          =>  env('hostreception'),
            'port'          =>  env('portreception'),
     //       'encryption'    => env('encreception'),
            'validate_cert' => true,
            'username'      => env('emailreception'),
            'password'      => env('passreception'),
            'protocol'      => 'imap'
        ]);
*/
        $oClient = new Client([
            'host'          => 'ssl0.ovh.net',// env('hostreception'),
            'port'          => '993',// env('portreception'),
            //    'encryption'    => '',//env('encreception'),
            'validate_cert' => true,
            'username'      =>'test@najda-assistance.com',
            'password'      => 'esol@2109',
            'protocol'      => 'imap'
        ]);

//Connect to the IMAP Server
        $oClient->connect();
        $aFolder = $oClient->getFolders();
        $storeid=false;$firstid=0;

        //Get all Messages of the current Mailbox $oFolder
        /** @var \Webklex\IMAP\Support\MessageCollection $aMessage */
        $oFolder = $oClient->getFolder('INBOX');
        $aMessage = $oFolder->messages()->all()->get();
        /** @var \Webklex\IMAP\Message $oMessage */
        foreach ($aMessage as $oMessage) {
            //  $nbattachs=10;

            $sujet=($oMessage->getSubject())  ;

            $nbattachs= intval($oMessage->getAttachments()->count()) ;
            $contenu= utf8_encode($oMessage->getHTMLBody(true));
          //  $from= $oMessage->getFrom()[0]->mail;
            $from= $oMessage->getSender()[0]->mail;
            $date= $oMessage->getDate();
            $mailid=$oMessage->getUid();

            //Move the current Message to 'INBOX.read'
            if ($oMessage->moveToFolder('read') == true) {
                // get last id
                $lastid= DB::table('entrees')->orderBy('id', 'desc')->first();
                // message moved

        // dispatch
        $dossiers = DB::table('dossiers')->pluck('reference_medic');
        $refdossier='';
        $statut = 0;
        foreach ($dossiers as $ref) {

                if (   (strpos($sujet, $ref )!==false) || (strpos($contenu, $ref )!==false)    )
                {
                    $refdossier = $ref;
                    $statut = 1;
                    break;
                }
        }




                $entree = new Entree([
                    'destinataire' => 'test@najda-assistance.com',
                    'emetteur' => ($from),
                    'sujet' =>  ($sujet),
                  //  'contenu'=> utf8_encode($contenu) ,
                    'contenu'=> ($contenu) ,
                    'reception'=> $date,
                    'nb_attach'=> $nbattachs,
                    'type'=> 'email',
                     'mailid'=> $mailid,
                     'viewed'=>0,
                     'dossier'=>$refdossier,
                     'statut'=>$statut,

                ]);

                $entree->save();


                /*********************/
                if($refdossier!= ''){


                    $iddossier = app('App\Http\Controllers\DossiersController')->IdDossierByRef($refdossier);
                    $userid = app('App\Http\Controllers\DossiersController')->ChampById('affecte', $iddossier);
                 
              //  $user=  DB::table('users')->where('id','=', $userid )->first();
                     $user = User::find($userid);

                    $user->notify(new Notif_Suivi_Doss($entree));
                // Notification::send($user, new Notif_Suivi_Doss($entree));
                  
                }
                else{
                     $seance =  DB::table('seance')
                        ->where('id','=', 1 )->first();
                    $disp=$seance->dispatcheur ;

                    $user = User::find($disp);
                   // $user=  DB::table('users')->where('id','=', $disp )->first();
                    $user->notify(new Notif_Suivi_Doss($entree));

                  //  Notification::send( $user, new Notif_Suivi_Doss($entree));
                  
                }

                //$user= User::get();
                // Notification::send($user, new Notif_Suivi_Doss($entree));


                // Dispatching
                //$this->disp();
                $id=$entree->id;

                  //   auth2::user()->notify(new Notif_Suivi_Doss($entree));

                    if($storeid==false){
                    $firstid=$id;
                    $storeid=true;
                }
                $aAttachment = $oMessage->getAttachments();

                $aAttachment->each(function ($oAttachment) use ($id){
                    $path= storage_path()."/Emails/";
                    /** @var \Webklex\IMAP\Attachment $oAttachment */
                    if (!file_exists($path.$id)) {
                        mkdir($path.$id, 0777, true);
                    }
                    // save in folder
                    $oAttachment->save($path.$id);
                    // save in DB

                    $nom = $oAttachment->getName();
                    $facturation='';
                    $type=  $oAttachment->getExtension();

                     // verifier si l'attachement pdf contient des mots de facturation
                    if ( App::environment() === 'production') {

                        if ($type=='pdf')
                        {
                    $path=$path.$id."/".$nom;
                    $path=realpath($path);
                          $text = (new Pdf())
                             ->setPdf($path )
                                ->text();

                        if(strpos($text,'facturation')!==false)
                         {
                                     $facturation='facturation';
                         }
                        if(strpos($text,'invoice')!==false)
                        {
                            $facturation=$facturation.' , '.'invoice';
                        }

                        if(strpos($text,'plafond')!==false)
                        {
                            $facturation=$facturation.' , '.'plafond';
                        }

                        if(strpos($text,'gop')!==false)
                        {
                            $facturation=$facturation.' , '.'gop';
                        }


                      } // end if pdf
                    } // end if  production


                   $path2= '/Emails/'.$id.'/'.$nom ;

                    $attach = new Attachement([
                        'nom' => $nom,
                        'type' => $type,
                        'path'=> $path2,
                         'parent'=> $id,
                         'entree_id'=> $id,
                        'facturation'=> $facturation,
                        'boite'=> 0,  // 0 = reception, 1 = envoi

                    ]);

                    $attach->save();

                });
                // récupérer last id une autre fois pour vérifier l'enregistrement
                $lastid2= DB::table('entrees')->orderBy('id', 'desc')->first();
                // si lemail n'est pas enregistré dépalcer une autre fois vers l inbox
                if($lastid==$lastid2)
                {
                    $oMessage->moveToFolder('INBOX') ;
                }



            } else {
                // error
                echo 'error';
            }


        }
        return $firstid;
       // return view('emails.check');

    } /// end check


    function checkboite2()
    {


        $oClient = new Client([
            'host'          => 'ssl0.ovh.net',// env('hostreception'),
            'port'          => '993',// env('portreception'),
            //    'encryption'    => '',//env('encreception'),
            'validate_cert' => true,
            'username'      =>'faxnajdassist@najda-assistance.com',
            'password'      => 'e-solutions2019',
            'protocol'      => 'imap'
        ]);

//Connect to the IMAP Server
        $oClient->connect();
        $aFolder = $oClient->getFolders();
        $storeid=false;$firstid=0;

        //Get all Messages of the current Mailbox $oFolder
        /** @var \Webklex\IMAP\Support\MessageCollection $aMessage */
        $oFolder = $oClient->getFolder('INBOX');
        $aMessage = $oFolder->messages()->all()->get();
        /** @var \Webklex\IMAP\Message $oMessage */
        foreach ($aMessage as $oMessage) {
            //  $nbattachs=10;

            $sujet=strval($oMessage->getSubject())  ;
            $nbattachs= intval($oMessage->getAttachments()->count()) ;
            $contenu= $oMessage->getHTMLBody(true);
            //  $from= $oMessage->getFrom()[0]->mail;
            $from= $oMessage->getSender()[0]->mail;
            $date= $oMessage->getDate();
            $mailid=$oMessage->getUid();

            //Move the current Message to 'INBOX.read'
            if ($oMessage->moveToFolder('read') == true) {
                // get last id
                $lastid= DB::table('entrees')->orderBy('id', 'desc')->first();
                // message moved


                // dispatch
                $dossiers = DB::table('dossiers')->pluck('reference_medic');
                $refdossier='';
                $statut = 0;
                foreach ($dossiers as $ref) {

                    if (   (strpos($sujet, $ref )!==false) || (strpos($contenu, $ref )!==false)    )
                    {
                        $refdossier = $ref;
                        $statut = 1;
                        break;
                    }
                }

                $entree = new Entree([
                    'destinataire' => 'faxnajdassist@najda-assistance.com',
                    'emetteur' => ($from),
                    'sujet' =>   $sujet ,
                 //   'contenu'=> utf8_encode($contenu) ,
                    'contenu'=>  ($contenu) ,
                    'reception'=> $date,
                    'nb_attach'=> $nbattachs,
                    'type'=> 'email',
                    'mailid'=> 'b2-'.$mailid,
                    'viewed'=>0,
                    'dossier'=>$refdossier,
                    'statut'=>$statut,

                ]);

                $entree->save();
                $id=$entree->id;

                ///   auth2::user()->notify(new Notif_Suivi_Doss($entree));

                if($storeid==false){
                    $firstid=$id;
                    $storeid=true;
                }
                $aAttachment = $oMessage->getAttachments();

                $aAttachment->each(function ($oAttachment) use ($id){
                    $path= storage_path()."/Emails/";
                    /** @var \Webklex\IMAP\Attachment $oAttachment */
                    if (!file_exists($path.$id)) {
                        mkdir($path.$id, 0777, true);
                    }
                    // save in folder
                    $oAttachment->save($path.$id);
                    // save in DB

                    $nom = $oAttachment->getName();
                    $facturation='';
                    $type=  $oAttachment->getExtension();

                    // verifier si l'attachement pdf contient des mots de facturation
                    if ( App::environment() === 'production') {

                        if ($type=='pdf')
                        {
                            $path=$path.$id."/".$nom;
                            $path=realpath($path);
                            $text = (new Pdf())
                                ->setPdf($path )
                                ->text();

                            if(strpos($text,'facturation')!==false)
                            {
                                $facturation='facturation';
                            }
                            if(strpos($text,'invoice')!==false)
                            {
                                $facturation=$facturation.' , '.'invoice';
                            }

                            if(strpos($text,'plafond')!==false)
                            {
                                $facturation=$facturation.' , '.'plafond';
                            }

                            if(strpos($text,'gop')!==false)
                            {
                                $facturation=$facturation.' , '.'gop';
                            }


                        } // end if pdf
                    } // end if  production


                    $path2= '/Emails/'.$id.'/'.$nom ;

                    $attach = new Attachement([
                        'nom' => $nom,
                        'type' => $type,
                        'path'=> $path2,
                        'parent'=> $id,
                        'entree_id'=> $id,
                        'facturation'=> $facturation,
                        'boite'=> 0,  // 0 = reception, 1 = envoi

                    ]);

                    $attach->save();

                });
                // récupérer last id une autre fois pour vérifier l'enregistrement
                $lastid2= DB::table('entrees')->orderBy('id', 'desc')->first();
                // si lemail n'est pas enregistré dépalcer une autre fois vers l inbox
                if($lastid==$lastid2)
                {
                    $oMessage->moveToFolder('INBOX') ;
                }



            } else {
                // error
                echo 'error';
            }


        }
        return $firstid;
        // return view('emails.check');

    }



    function checkboiteperso()
    {
        // get username & password
        $iduser=Auth::id();

        $boite=app('App\Http\Controllers\UsersController')->ChampById('boite',$iduser);
        $passboite=app('App\Http\Controllers\UsersController')->ChampById('passboite',$iduser);

        if (($boite !='')&&($passboite!=''))
        {
        $oClient = new Client([
            'host'          => 'ssl0.ovh.net',// env('hostreception'),
            'port'          => '993',// env('portreception'),
            //    'encryption'    => '',//env('encreception'),
            'validate_cert' => true,
            'username'      => $boite,
            'password'      => $passboite,
            'protocol'      => 'imap'
        ]);

//Connect to the IMAP Server
        $oClient->connect();
        $aFolder = $oClient->getFolders();
        $storeid=false;$firstid=0;

        //Get all Messages of the current Mailbox $oFolder
        /** @var \Webklex\IMAP\Support\MessageCollection $aMessage */
        $oFolder = $oClient->getFolder('INBOX');
        $aMessage = $oFolder->messages()->all()->get();
        /** @var \Webklex\IMAP\Message $oMessage */
        foreach ($aMessage as $oMessage) {
            //  $nbattachs=10;

            $sujet=strval($oMessage->getSubject())  ;
            $nbattachs= intval($oMessage->getAttachments()->count()) ;
            $contenu= $oMessage->getHTMLBody(true);
            //  $from= $oMessage->getFrom()[0]->mail;
            $from= $oMessage->getSender()[0]->mail;
            $date= $oMessage->getDate();
            $mailid=$oMessage->getUid();

            //Move the current Message to 'INBOX.read'
            if ($oMessage->moveToFolder('read') == true) {
                // get last id
                $lastid= DB::table('boites')->orderBy('id', 'desc')->first();
                // message moved


                $boite = new Boite([


                    'destinataire' =>  'Boite Perso',
                    'emetteur' =>  ($from),
                    'sujet' =>  ($sujet),
                    'contenu'=> utf8_encode($contenu) ,
                    'mailid'=>  $mailid,
                    'viewed'=>0,
                    'statut'=>0,
                    'nb_attach'=>$nbattachs,
                    'user'=>$iduser,
                    'reception'=>$date

                ]);

                $boite->save();
                $id=$boite->id;

                ///   auth2::user()->notify(new Notif_Suivi_Doss($entree));

                if($storeid==false){
                    $firstid=$id;
                    $storeid=true;
                }
                $aAttachment = $oMessage->getAttachments();

                $aAttachment->each(function ($oAttachment) use ($id){
                    $path= storage_path()."/Boites/";
                    /** @var \Webklex\IMAP\Attachment $oAttachment */
                    if (!file_exists($path.$id)) {
                        mkdir($path.$id, 0777, true);
                    }
                    // save in folder
                    $oAttachment->save($path.$id);
                    // save in DB

                    $nom = $oAttachment->getName();

                    $type=  $oAttachment->getExtension();



                    $path2= '/Boites/'.$id.'/'.$nom ;

                    $attach = new Attachement([
                        'nom' => $nom,
                        'type' => $type,
                        'path'=> $path2,
                        'parent'=> $id,

                        'boite'=> 02,  // 0 = reception, 1 = envoi // 2 personnelle

                    ]);

                    $attach->save();

                });
                // récupérer last id une autre fois pour vérifier l'enregistrement
                $lastid2= DB::table('boites')->orderBy('id', 'desc')->first();
                // si lemail n'est pas enregistré dépalcer une autre fois vers l inbox
                if($lastid==$lastid2)
                {
                    $oMessage->moveToFolder('INBOX') ;
                }

            } else {
                // error
                echo 'error';
            }

        }
        return $firstid;
        // return view('emails.check');
        } // endif

    }

    function checksms()
    {


            $oClient = new Client([
                'host'          => 'ssl0.ovh.net',// env('hostreception'),
                'port'          => '993',// env('portreception'),
                //    'encryption'    => '',//env('encreception'),
                'validate_cert' => true,
                'username'      => 'sms@najda-assistance.com',
                'password'      => 'eSolutions-2019!',
                'protocol'      => 'imap'
            ]);



            $oClient->connect();

            $storeid=false;$firstid=0;

            $oFolder = $oClient->getFolder('INBOX');
            $aMessage = $oFolder->messages()->all()->get();

            foreach ($aMessage as $oMessage) {


                $sujet=strval($oMessage->getSubject())  ;

                $contenu= $oMessage->getHTMLBody(true);

                $date= $oMessage->getDate();
                $mailid=$oMessage->getUid();

                //Move the current Message to 'INBOX.read'
                if ($oMessage->moveToFolder('read') == true) {
                    // get last id
                    $lastid= DB::table('entrees')->orderBy('id', 'desc')->first();
                    // message moved

                    $dossiers = DB::table('dossiers')->pluck('reference_medic');

                    $refdossier='';
                    $statut = 0;
                    foreach ($dossiers as $ref) {

                        if (   (strpos($sujet, $ref )!==false) || (strpos($contenu, $ref )!==false)    )
                        {
                            $refdossier = $ref;
                            $statut = 1;
                            break;
                        }
                    }


                    $entree = new Entree([


                        'destinataire' =>  'SMS Najda',
                        'emetteur' =>  ($sujet),
                        'sujet' =>  ($sujet),
                        'contenu'=>  ($contenu) ,
                        'mailid'=>  'sms-'.$mailid,
                        'viewed'=>0,
                        'statut'=>0,
                        'nb_attach'=>0,
                        'reception'=>$date,
                        'type'=>'sms',
                         'dossier'=>$refdossier,
                         'statut'=>$statut,

                    ]);

                    $entree->save();
                    $id=$entree->id;

                    ///   auth2::user()->notify(new Notif_Suivi_Doss($entree));

                    if($storeid==false){
                        $firstid=$id;
                        $storeid=true;
                    }

                    // récupérer last id une autre fois pour vérifier l'enregistrement
                    $lastid2= DB::table('entrees')->orderBy('id', 'desc')->first();
                    // si lemail n'est pas enregistré dépalcer une autre fois vers l inbox
                    if($lastid==$lastid2)
                    {
                        $oMessage->moveToFolder('INBOX') ;
                    }

                } else {
                    // error
                    echo 'error';
                }

            }
            return $firstid;
            // return view('emails.check');


    }



    function checkfax()
    {

        $oClient = new Client([
            'host'          => 'ssl0.ovh.net',// env('hostreception'),
            'port'          => '993',// env('portreception'),
            //    'encryption'    => '',//env('encreception'),
            'validate_cert' => true,
            'username'      =>'testfax@najda-assistance.com',
            'password'      => 'TesT@2019',
            'protocol'      => 'imap'
        ]);

//Connect to the IMAP Server
        $oClient->connect();

        $storeid=false;$firstid=0;

        $oFolder = $oClient->getFolder('INBOX');
        $aMessage = $oFolder->messages()->all()->get();
        /** @var \Webklex\IMAP\Message $oMessage */
        foreach ($aMessage as $oMessage) {


            $sujet=strval($oMessage->getSubject())  ;
            $nbattachs= intval($oMessage->getAttachments()->count()) ;
            $contenu= $oMessage->getHTMLBody(true);
            //  $from= $oMessage->getFrom()[0]->mail;
            $from= $oMessage->getSender()[0]->mail;
            $date= $oMessage->getDate();
            $mailid=$oMessage->getUid();

            //Move the current Message to 'INBOX.read'
            if ($oMessage->moveToFolder('read') == true) {
                // get last id
                $lastid= DB::table('entrees')->orderBy('id', 'desc')->first();
                // message moved


                // dispatch
                $dossiers = DB::table('dossiers')->pluck('reference_medic');
                $refdossier='';
                $statut = 0;
                foreach ($dossiers as $ref) {

                    if (   (strpos($sujet, $ref )!==false) || (strpos($contenu, $ref )!==false)    )
                    {
                        $refdossier = $ref;
                        $statut = 1;
                        break;
                    }
                }

                $entree = new Entree([
                    'destinataire' => 'envoifax@najda-assistance.com',
                    'emetteur' => trim($from),
                    'sujet' => utf8_encode($sujet),
                    'contenu'=> utf8_encode($contenu) ,
                    'reception'=> $date,
                    'nb_attach'=> $nbattachs,
                    'type'=> 'fax',
                    'mailid'=> 'FX-'.$mailid,
                    'viewed'=>0,
                    'dossier'=>$refdossier,
                    'statut'=>$statut,

                ]);

                $entree->save();
                $id=$entree->id;

                ///   auth2::user()->notify(new Notif_Suivi_Doss($entree));

                if($storeid==false){
                    $firstid=$id;
                    $storeid=true;
                }
                $aAttachment = $oMessage->getAttachments();

                $aAttachment->each(function ($oAttachment) use ($id){
                    $path= storage_path()."/Emails/";
                    /** @var \Webklex\IMAP\Attachment $oAttachment */
                    if (!file_exists($path.$id)) {
                        mkdir($path.$id, 0777, true);
                    }
                    // save in folder
                    $oAttachment->save($path.$id);
                    // save in DB

                    $nom = $oAttachment->getName();
                    $facturation='';
                    $type=  $oAttachment->getExtension();

                    // verifier si l'attachement pdf contient des mots de facturation
                    if ( App::environment() === 'production') {

                        if ($type=='pdf')
                        {
                            $path=$path.$id."/".$nom;
                            $path=realpath($path);
                            $text = (new Pdf())
                                ->setPdf($path )
                                ->text();

                            if(strpos($text,'facturation')!==false)
                            {
                                $facturation='facturation';
                            }
                            if(strpos($text,'invoice')!==false)
                            {
                                $facturation=$facturation.' , '.'invoice';
                            }

                            if(strpos($text,'plafond')!==false)
                            {
                                $facturation=$facturation.' , '.'plafond';
                            }

                            if(strpos($text,'gop')!==false)
                            {
                                $facturation=$facturation.' , '.'gop';
                            }


                        } // end if pdf
                    } // end if  production


                    $path2= '/Emails/'.$id.'/'.$nom ;

                    $attach = new Attachement([
                        'nom' => $nom,
                        'type' => $type,
                        'path'=> $path2,
                        'parent'=> $id,
                        'entree_id'=> $id,
                        'facturation'=> $facturation,
                        'boite'=> 0,  // 0 = reception, 1 = envoi

                    ]);

                    $attach->save();

                });
                // récupérer last id une autre fois pour vérifier l'enregistrement
                $lastid2= DB::table('entrees')->orderBy('id', 'desc')->first();
                // si lemail n'est pas enregistré dépalcer une autre fois vers l inbox
                if($lastid==$lastid2)
                {
                    $oMessage->moveToFolder('INBOX') ;
                }



            } else {
                // error
                echo 'error';
            }


        }
        return $firstid;


    }



    public function sending()
    {
        $dossiers = Dossier::all();

        return view('emails.sending');
    }

    public function envoimail($id,$type,$prest=null)
    {
        if (isset($prest)){$prest=$prest;}else{$prest=0;}
        $ref=app('App\Http\Controllers\DossiersController')->RefDossierById($id);
        $nomabn=app('App\Http\Controllers\DossiersController')->NomAbnDossierById($id);
        $refdem=app('App\Http\Controllers\DossiersController')->RefDemDossierById($id);
        $entrees =   Entree::where('dossier', $ref)->get();
        $envoyes =   Envoye::where('dossier', $ref)->get();

        $listeemails=array();
        $prestataires=array();


        if($type=='client')
        {
        // trouver id client à partir de la référence
            $cl=app('App\Http\Controllers\DossiersController')->ClientDossierById($id);

            $mail=app('App\Http\Controllers\ClientsController')->ClientChampById('mail',$cl);
            if($mail!='') { array_push($listeemails,$mail);}

            $mail2=app('App\Http\Controllers\ClientsController')->ClientChampById('mail2',$cl);
            if($mail2!='') { array_push($listeemails,$mail2);}

            $mail3=app('App\Http\Controllers\ClientsController')->ClientChampById('mail3',$cl);
            if($mail3!='') { array_push($listeemails,$mail3);}

            $mail4=app('App\Http\Controllers\ClientsController')->ClientChampById('mail4',$cl);
            if($mail4!='') { array_push($listeemails,$mail4);}

            $mail5=app('App\Http\Controllers\ClientsController')->ClientChampById('mail5',$cl);
            if($mail5!='') { array_push($listeemails,$mail5);}

            $mail6=app('App\Http\Controllers\ClientsController')->ClientChampById('mail6',$cl);
            if($mail6!='') { array_push($listeemails,$mail6);}

            $mail7=app('App\Http\Controllers\ClientsController')->ClientChampById('mail7',$cl);
            if($mail7!='') { array_push($listeemails,$mail7);}

            $mail8=app('App\Http\Controllers\ClientsController')->ClientChampById('mail8',$cl);
            if($mail8!='') { array_push($listeemails,$mail8);}

            $mail9=app('App\Http\Controllers\ClientsController')->ClientChampById('mail9',$cl);
            if($mail9!='') { array_push($listeemails,$mail9);}

            $mail10=app('App\Http\Controllers\ClientsController')->ClientChampById('mail10',$cl);
            if($mail10!='') { array_push($listeemails,$mail10);}

            $gestion_mail1=app('App\Http\Controllers\ClientsController')->ClientChampById('gestion_mail1',$cl);
            if($gestion_mail1!='') { array_push($listeemails,$gestion_mail1);}

            $gestion_mail2=app('App\Http\Controllers\ClientsController')->ClientChampById('gestion_mail2',$cl);
            if($gestion_mail2!='') { array_push($listeemails,$gestion_mail2);}

            $qualite_mail1=app('App\Http\Controllers\ClientsController')->ClientChampById('qualite_mail1',$cl);
            if($qualite_mail1!='') { array_push($listeemails,$qualite_mail1);}

            $qualite_mail2=app('App\Http\Controllers\ClientsController')->ClientChampById('qualite_mail2',$cl);
            if($qualite_mail2!='') { array_push($listeemails,$qualite_mail2);}

            $reseau_mail1=app('App\Http\Controllers\ClientsController')->ClientChampById('reseau_mail1',$cl);
            if($reseau_mail1!='') { array_push($listeemails,$reseau_mail1);}

            $reseau_mail2=app('App\Http\Controllers\ClientsController')->ClientChampById('reseau_mail2',$cl);
            if($reseau_mail2!='') { array_push($listeemails,$reseau_mail2);}



            $emails =   Adresse::where('nature', 'email')
                ->where('parent',$cl)
                ->pluck('champ');

            $emails =  $emails->unique();

            if (count($emails)>0) {
                foreach ($emails as $m) {
                    array_push($listeemails, $m);

                }
            }

         }
        if($type=='prestataire')
        {
            $prestataires =   Prestation::where('dossier_id', $id)->pluck('prestataire_id');
            $prestataires = $prestataires->unique();



            $listeemails=array();


            $mails=array();


        if ($prest!='')
        {
            $mail = app('App\Http\Controllers\PrestatairesController')->ChampById('mail', $prest);
            if ($mail != '') {
                 array_push($mails, $mail);
            }
            $mail2 = app('App\Http\Controllers\PrestatairesController')->ChampById('mail2', $prest);
            if ($mail2 != '') {
                 array_push($mails, $mail2);

            }

            $mail3 = app('App\Http\Controllers\PrestatairesController')->ChampById('mail3', $prest);
            if ($mail3 != '') {
                 array_push($mails, $mail3);

            }

            $mail4 = app('App\Http\Controllers\PrestatairesController')->ChampById('mail4', $prest);
            if ($mail4 != '') {
                 array_push($mails, $mail4);

            }

            $mail5 = app('App\Http\Controllers\PrestatairesController')->ChampById('mail5', $prest);
            if ($mail5 != '') {
                 array_push($mails, $mail5);

            }


           // $emails =   Email::where('parent', $prest)->pluck('champ');

            $emails =   Adresse::where('nature', 'email')
                ->where('parent',$prest)
                ->pluck('champ');

            $emails =  $emails->unique();

            if (count($emails)>0){
            foreach ( $emails as $m)
            {
                array_push($mails,$m);

            }

            }
            $listeemails=$mails;

        } // if isset


        } // prestataire

        if($type=='assure')
        {

            $mail=app('App\Http\Controllers\DossiersController')->ChampById('mail',$id);
            if($mail!='') { array_push($listeemails,$mail);}


            $subscriber_mail1=app('App\Http\Controllers\DossiersController')->ChampById('subscriber_mail1',$id);
            if($subscriber_mail1!='') { array_push($listeemails,$subscriber_mail1);}

            $subscriber_mail2=app('App\Http\Controllers\DossiersController')->ChampById('subscriber_mail2',$id);
            if($subscriber_mail2!='') { array_push($listeemails,$subscriber_mail2);}

            $subscriber_mail3=app('App\Http\Controllers\DossiersController')->ChampById('subscriber_mail3',$id);
            if($subscriber_mail3!='') { array_push($listeemails,$subscriber_mail3);}

           // $emails =   Email::where('parent', $id)->pluck('champ');

            $emails =   Adresse::where('nature', 'emaildoss')
                ->where('parent',$id)
                ->pluck('champ');

            $emails =  $emails->unique();

            if (count($emails)>0){
                foreach ( $emails as $m)
                {
                    array_push($listeemails,$m);

                }

             }

        }

         $identr=array();
        $idenv=array();
        foreach ($entrees as $entr)
        {
            array_push($identr,$entr->id );

        }

        foreach ($envoyes as $env)
        {
            array_push($idenv,$env->id );

        }


        $attachements= DB::table('attachements')
            /*->whereIn('entree_id',$identr )
            ->orWhereIn('envoye_id',$idenv )*/
            ->Where('dossier','=',$id )
            ->orderBy('created_at', 'desc')
            ->distinct()
            ->get();

        return view('emails.envoimail',['prest'=>$prest, 'attachements'=>$attachements,'doss'=>$id,'ref'=>$ref,'nomabn'=>$nomabn,'refdem'=>$refdem,'listeemails'=>$listeemails,'prestataires'=>$prestataires,'type'=>$type]);
    }

    public function envoimailbr($id)
    {
        $envoye =   Envoye::find($id);

       // $ref=app('App\Http\Controllers\DossiersController')->RefDossierById($id);
        $ref=$envoye['dossier'];
        $entrees =   Entree::where('dossier', $ref)->get();
        $envoyes =   Envoye::where('dossier', $ref)->get();

        $emails =   Email::where('parent', $id)->get();

        $emailsdoss= Email::where('parent', $id)->get();
        $identr=array();
        $idenv=array();
        foreach ($entrees as $entr)
        {
            array_push($identr,$entr->id );

        }

        foreach ($envoyes as $env)
        {
            array_push($idenv,$env->id );

        }

        $attachements= DB::table('attachements')
            ->whereIn('entree_id',$identr )
            ->orWhereIn('envoye_id',$idenv )
            ->orWhere('dossier',$ref )
            ->orderBy('created_at', 'desc')
            ->get();

        return view('emails.envoimailbr',['attachements'=>$attachements,'envoye'=>$envoye,'doss'=>$id]);
    }

    public function envoifax($id)
    {

        $ref=app('App\Http\Controllers\DossiersController')->RefDossierById($id);
        $entrees =   Entree::where('dossier', $ref)->get();
        $envoyes =   Envoye::where('dossier', $ref)->get();

         $identr=array();
        $idenv=array();
        foreach ($entrees as $entr)
        {
            array_push($identr,$entr->id );

        }

        foreach ($envoyes as $env)
        {
            array_push($idenv,$env->id );

        }

        $attachements= DB::table('attachements')
            ->whereIn('entree_id',$identr )
            ->orWhereIn('envoye_id',$idenv )
            ->orderBy('created_at', 'desc')
            ->get();

        return view('emails.envoifax',['attachements'=>$attachements,'doss'=>$id]);
    }

    function send (Request $request)
    {

        $request->validate([
            'g-recaptcha-response' => 'required|captcha'
        ]);

        $envoyeid = $request->get('envoye');
        $doss = $request->get('dossier');
        $to = $request->get('destinataire');
        $cc = $request->get('cc');
        $cci = $request->get('cci');
        $sujet = $request->get('sujet');
        $contenu = $request->get('contenu');
        $files = $request->file('files');
        $attachs = $request->get('attachs');
        $tot= count($_FILES['files']['name']);
      //  $tot2= count($attachs);

        $ccimails=array();
        if(isset($cci )) {
            foreach($cci as $ccimail) {
                array_push($ccimails,$ccimail );

            }
            }

        //  array_push($ccimails,'ihebs001@gmail.com' );
        // ajout de l'addrese de Mr Najib
        //  array_push($ccimails,'medic.multiservices@topnet.tn' );

       // $to = explode(',', $to);

        try{
            Mail::send([], [], function ($message) use ($to,$sujet,$contenu,$files,$tot,$cc,$cci,$attachs,$doss,$envoyeid,$ccimails) {
            $message

              //  ->to('saadiheb@gmail.com')
             // ->to()

                ->cc($cc  ?: [])
                ->bcc($ccimails ?: [])
                ->subject($sujet)
         ->setBody($contenu, 'text/html');
                if(isset($to )) {

                    foreach ($to as $t) {
                        $message->to($t);
                     }
               }
               /********** add nom email  *****************************************************
                PrestatairesController::NomByEmail( $mail)

               $tos= implode("|",app('App\Http\Controllers\PrestatairesController')->NomByEmail( $to) .' < '.$to.' >');

                *******************/
               $tos='';
               if (count($to)>1){

                  // $tos= implode("|",$to .'');

                   foreach ($to as $t2) {
                       $tos.= app('App\Http\Controllers\PrestatairesController')->NomByEmail( $t2) .' ('.$t2.'); ';
                   }


               }else {
                  // $tos =  $to[0];
                 //  $tos =  $to[0];
                   $tos.= app('App\Http\Controllers\PrestatairesController')->NomByEmail( $to[0]) .' ('.$to[0].'); ';

               }

         $count=0;

         if(isset($files )) {
             foreach($files as $file) {
                 $count++;
                 $path=$file->getRealPath();
               //  $chemin=$file->Path();
                 $ext= $file->extension();
               //  $name=$file->name();


                 $message->attach($path, array(
                         'as' => $file->getClientOriginalName(), // If you want you can chnage original name to custom name
                         'mime' => $file->getMimeType())
                 );

           // save external files here

                 $attachement = new Attachement([

                    'type'=>$ext,'path' => $path, 'nom' => $file->getClientOriginalName(),'boite'=>1,'dossier'=>$doss,'envoye_id'=>$envoyeid
                 ]);

                 $attachement->save();

             }
         }

/// attach here
///

            if(isset($attachs )) {

                foreach($attachs as $attach) {
                    $count++;
                 $path=$this->PathattachById($attach);
                  $fullpath=storage_path().$path;
                    $path_parts = pathinfo($fullpath);
                  $ext=  $path_parts['extension'];

    $name=basename($fullpath);
      $mime_content_type=mime_content_type ($fullpath);
                 $message->attach($fullpath, array(
                         'as' =>$name,
                         'mime' => $mime_content_type)
                );

              // DB::table('attachements')->insert([
                   $attachement = new Attachement([

                       'type'=>$ext,'path' => $path, 'nom' => $name,'boite'=>1,'dossier'=>$doss,'parent'=>$envoyeid
               ]);
                    $attachement->save();


            }
         }


     $urlapp=env('APP_URL');

    if (App::environment('local')) {
        // The environment is local
        $urlapp='http://localhost/najdaapp';
    }
             //   $urlsending=$urlapp.'/emails/envoimail/'.$doss;
                $urlsending=$urlapp.'/envoyes';
                $dossier= $this->RefDossierById($doss);////;

           $par=Auth::id();
           /// $tos=    implode( ", ", $to );

                //   $envoye
           Envoye::where('id', $envoyeid)->update(array(
            //   $champ => $val
           //));

                //  $envoye = new Envoye([
               'emetteur' => 'test@najda-assistance.com', //env('emailenvoi')
                  'destinataire' => $tos,
            //      'destinataire' =>'iheb test',
                'par'=> $par,
               'sujet'=> $sujet,
               'contenu'=> $contenu,
               'nb_attach'=> $count,
               'cc'=> $cc,
             //  'cci'=> $cci,
               'statut'=> 1,
               'type'=> 'email',
               'dossier'=> $dossier
              // 'reception'=> date('d/m/Y H:i:s'),

           ));

          // $envoye->save();
           //$id=$envoye->id;
          if($envoyeid>0){ $this->export_pdf_send($envoyeid);};

            echo ('<script> window.location.href = "'.$urlsending.'";</script>') ;
                return redirect($urlsending)->with('success', '  Envoyé ! ');

     });
            var_dump( Mail:: failures());

       } catch (Exception $ex) {
    // Debug via $ex->getMessage();
     echo '<script>alert("Erreur !") </script>' ;
     }

}// end send




    function sendfax (Request $request)
    {

               /*  $request->validate([
                      'g-recaptcha-response' => 'required|captcha'
                  ]);
                 */

        $doss = $request->get('dossier');

        $nom = $request->get('nom');
        $description = $request->get('description');
        $numero = $request->get('numero');
        //  $contenu = $request->get('contenu');
        $attachs = $request->get('attachs');

        $cc='ihebsaad@gmail.com';
        //  $cc='';
           $to='ihebsaad@gmail.com';
      ////////////  $to='envoifax@najda-assistance.com';
        $sujet='1234,Najda,najda,'.$nom.'@'.$numero.'';


        $swiftTransport =  new \Swift_SmtpTransport( 'smtp.gmail.com', '587', 'tls');
        $swiftTransport->setUsername('najdassist@gmail.com');
        $swiftTransport->setPassword('nejibgyh9kkq');

        $swiftMailer = new Swift_Mailer($swiftTransport);

        Mail::setSwiftMailer($swiftMailer);


        try{
            Mail::send([], [], function ($message) use ($to,$sujet,$attachs,$doss,$cc,$numero,$description,$nom) {
                $message
                    ->to($to)
                    //   ->cc($cc  ?: [])
                    ->subject($sujet)
                    //   ->setBody($contenu, 'text/html');
                    ->setBody('Fax Najda', 'text/html');

                $count=0;

               $date=date('d/m/Y H:i');

                 $filename=$nom.'-'.$date.' cover';

                $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);

               $this->garde_pdf($description,$numero,$date,$nom);
                $fullpath=storage_path().'/Covers/'.$name.'.pdf';

                $name=basename($fullpath);
                $mime_content_type=mime_content_type ($fullpath);

                $message->attach($fullpath, array(
                    'as' =>$name,
                    'mime' => $mime_content_type));

                if(isset($attachs )) {

                    foreach($attachs as $attach) {
                        $count++;
                        $path=$this->PathattachById($attach);
                        $fullpath=storage_path().$path;
                        $path_parts = pathinfo($fullpath);
                        $ext=  $path_parts['extension'];

                        $name=basename($fullpath);
                        $mime_content_type=mime_content_type ($fullpath);
                        $message->attach($fullpath, array(
                                'as' =>$name,
                                'mime' => $mime_content_type)
                        );

                        // DB::table('attachements')->insert([


                        $attachement = new Attachement([

                            'type'=>$ext,'path' => $path, 'nom' => $name,'boite'=>1,'dossier'=>$doss
                        ]);
                        $attachement->save();


                    }
                }


                $urlapp=env('APP_URL');

                if (App::environment('local')) {
                    // The environment is local
                    $urlapp='http://localhost/najdaapp';
                }
                // $urlsending=$urlapp.'/emails/envoifax/'.$doss;
                $urlsending=$urlapp.'/envoyes';

                if (Mail::failures()) {
                    //     echo ('<script> window.location.href = "http://localhost/najdaapp/emails/sending";</script>') ;
                    //    return redirect('http://localhost/najdaapp/emails/sending')->with('fail', ' Echec ! ');


                }else{
// save email sent

                    $par=Auth::id();
                    $envoye = new Envoye([
                        'emetteur' => 'najdassist@gmail.com', //env('emailenvoi')
                        'destinataire' => $numero .'-'.$doss,
                        'par'=> $par,
                        'sujet'=> 'Fax Najda',
                        'contenu'=> '',
                        'attachements'=> $count,
                        'statut'=> 1,
                        'type'=> 'fax',
                        'nb_attach'=> $count,
                        'description'=> $description,
                        // 'reception'=> date('d/m/Y H:i:s'),

                    ]);

                    $envoye->save();
                    $id=$envoye->id;
                    //// $this->export_pdf_send($id);

                    echo ('<script> window.location.href = "'.$urlsending.'";</script>') ;
                    return redirect($urlsending)->with('success', '  Envoyé ! ');


                }


            });

        } catch (Exception $ex) {
            // Debug via $ex->getMessage();

            return "We've got errors!";

        }

    }// end send



    function accuse (Request $request)
    {

         $request->validate([
            'g-recaptcha-response' => 'required|captcha'
        ]);

        $entree = $request->get('entree');
        $mess = $request->get('message');
        $refdossier = app('App\Http\Controllers\EntreesController')->ChampById('dossier',$entree);
        $iddossier = app('App\Http\Controllers\DossiersController')->IdDossierByRef($refdossier);
        $clientid = app('App\Http\Controllers\DossiersController')->ClientDossierById($iddossier);
        $langue = app('App\Http\Controllers\ClientsController')->ClientChampById('langue1',$clientid);

        $nomabn=app('App\Http\Controllers\DossiersController')->NomAbnDossierById($iddossier);

        $refclient=app('App\Http\Controllers\ClientsController')->ClientChampById('reference',$clientid);

        $to=  app('App\Http\Controllers\EntreesController')->ChampById('emetteur',$entree);


         $message = Parametre::find(1);
        $signature = $message["signature"];

        if ($langue=='francais'){

            $sujet=  $nomabn.'  - V/Réf: '.$refclient .' - N/Réf: '.$refdossier ;

        }else{

            $sujet=  $nomabn.'  - Y/Ref: '.$refclient .' - O/Ref: '.$refdossier ;

        }

        $contenu=$mess.'<br><br>'.$signature;
        try{
            Mail::send([], [], function ($message) use ($to,$sujet,$contenu) {
                $message

                      ->to($to)

                    ->subject($sujet)
                    ->setBody($contenu, 'text/html');

            });

            var_dump( Mail:: failures());

        } catch (Exception $ex) {
            // Debug via $ex->getMessage();
            echo '<script>alert("Erreur !") </script>' ;
        }

    }// end accuse



    public function export_pdf_send($id)
    {
        // Fetch all customers from database
        $envoye = Envoye::find($id);
         // Send data to the view using loadView function of PDF facade
        $pdf = PDF2::loadView('entrees.pdfsend', ['envoye' => $envoye])->setPaper('a4', '');

        $path= storage_path()."/Envoyes/";

        if (!file_exists($path.$id)) {
            mkdir($path.$id, 0777, true);
        }
         $filename=$envoye->description;
        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);
        $name='ENV - '.$name;
        // If you want to store the generated pdf to the server then you can use the store function
        $pdf->save($path.$id.'/'.$name.'.pdf');
        $path2='/Envoyes/'.$id.'/'.$name.'.pdf';

        $attachement = new Attachement([

            'type'=>'pdf','path' => $path2, 'nom' => $name.'.pdf','boite'=>1,'envoye_id'=>$id,'parent'=>$id,
        ]);
        $attachement->save();
    }


    public function garde_pdf($sujet,$fax,$date,$nom)
    {
        // Fetch all customers from database
        // Send data to the view using loadView function of PDF facade
        $pdf = PDF2::loadView('envoyes.garde', ['date' => $date,'sujet'=>$sujet,'fax'=>$fax,'nom'=>$nom  ])->setPaper('a4', '');

        $path= storage_path()."/Covers/";

        $filename=$nom.'-'.$date.' cover';
        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', ' ', $filename);


        $pdf->save($path.'/'.$name.'.pdf');

        $path2='/Covers/'.$name.'.pdf';

        $attachement = new Attachement([

            'type'=>'pdf','path' => $path2, 'nom' => $name,'boite'=>4, 'parent'=>$sujet.'date-'.$date,
        ]);

        $attachement->save();

    }

    function test()
    {

/*
        // Your Account SID and Auth Token from twilio.com/console
        $sid = 'ACaa5ce5753047f8399d2d3226bfdc4eb7';
        $token = 'ba7e3af173bcd22f27a6ea248ec30be7';
        $client = new Client2($sid, $token);

// Use the client to do fun stuff like send text messages!
        $client->messages->create(
        // the number you'd like to send the message to
            '+13342316588',
            array(
                // A Twilio phone number you purchased at twilio.com/console
                'from' => '+14804473614',
                // the body of the text message you'd like to send
                'body' => 'Hey iheb! test message avec enregistrement dans la base'
            )
        );

*/
        return view('emails.test');
    }


    function sendsms(Request $request)
    {
        $request->validate([
            'g-recaptcha-response' => 'required|captcha'
        ]);


        $num = trim($request->get('destinataire'));
        $contenu = trim( $request->get('message'));
        $description = trim( $request->get('description'));
        $doss = trim( $request->get('dossier'));
        $dossier= $this->RefDossierById($doss);////;

        $from='SMS Najda +216 21 433 463';
        $par=Auth::id();

        try{
            Mail::send([], [], function ($message) use ($contenu,$dossier,$par,$description,$num,$from) {
                $message
                     //  ->to('ihebsaad@gmail.com')
                     ->to('ecom_plus@tcs.com.tn')

                    ->subject('sms '.$num.' ECOM1')
                    ->setBody($contenu );


        $envoye = new Envoye([
            'emetteur' => $from,
            'destinataire' => $num,
            'sujet' => $description,
            'description' => $description,
            'contenu'=> $contenu,
            'statut'=> 1,
            'par'=> $par,
            'dossier'=>$dossier,
            'type'=>'sms'
        ]);

        $envoye->save();

                $urlapp=env('APP_URL');

                if (App::environment('local')) {
                    // The environment is local
                    $urlapp='http://localhost/najdaapp';
                }
                //   $urlsending=$urlapp.'/emails/envoimail/'.$doss;
                $urlsending=$urlapp.'/envoyes';
                 echo ('<script> window.location.href = "'.$urlsending.'";</script>') ;
                return redirect($urlsending)->with('success', '  Envoyé ! ');

            });

        } catch (Exception $ex) {
            // Debug via $ex->getMessage();
         }

    }// end send


    function sms( $id)
    {
        $dossiers = Dossier::all();


        return view('emails.sms', ['doss' => $id,'dossiers'=>$dossiers]);

    }


    function sendwhatsapp(Request $request)
    {
/*        $to = trim($request->get('destinataire'));
        $message = trim( $request->get('message'));

        // Your Account SID and Auth Token from twilio.com/console
        $sid = 'ACa8d667427a2a2d4dfa58e23851804943';
        $token = 'a0257ac989f3f41bc81cbc3bf22ec18f';
        $twliio = new Client2($sid, $token);



        $message = $twliio->messages
            ->create('whatsapp:'.$to, // to
                array(
                    "from" => "whatsapp:+14155238886",
                    "body" => $message
                )
            );

        $par=Auth::id();

        $envoye = new Envoye([
            'emetteur' => 'WhatsApp',
            'destinataire' => $to,
            'sujet' => 'Whatsapp',
            'contenu'=> $message,
            'statut'=> 1,
            'par'=> $par,
            'type'=>'whatsapp'
        ]);

        $envoye->save();
*/
        return redirect('/emails/whatsapp')->with('success', 'SMS Whatsapp Envoyé !');

    }

    function whatsapp( )
    {

        $dossiers = Dossier::all();

        return view('emails.whatsapp', ['dossiers' => $dossiers]);

    }

    public static function PathattachById($id)
    {
        $attach = Attachement::find($id);

        if (isset($attach['path'])) {
            return $attach['path'];
        }else{return '';}
    }

    public static function RefDossierById($id)
    {
        $dossier = Dossier::find($id);
        if (isset($dossier['reference_medic'])) {
            return $dossier['reference_medic'];
        }else{return '';}

    }


    public function searchprest(Request $request)
    {
        if($request->ajax()){
            $data = DB::table('prestataires')->where('id',$request->prest)->pluck("mail","id")->all();
           // $data = view('emails.envoimail',compact('listeemails'))->render();
            return response()->json(['options'=>$data]);
        }
        if($request->ajax()){
            $prest = $request->get('prest');

           // <option></option>
            $data="";$mails=array();
            $mail=app('App\Http\Controllers\PrestatairesController')->ChampById('mail',$prest);
            if($mail!='') {
                $data.= '<option value="'.$mail.'">'.$mail.'</option>';
                array_push($mails,$mail);
            }
            $mail2=app('App\Http\Controllers\PrestatairesController')->ChampById('mail2',$prest);
            if($mail2!='') {
                $data.='<option value="'.$mail2.'">'.$mail2.'</option>';
                array_push($mails,$mail2);

            }

            $mail3=app('App\Http\Controllers\PrestatairesController')->ChampById('mail3',$prest);
            if($mail3!='') {
                $data.='<option value="'.$mail3.'">'.$mail3.'</option>';
                array_push($mails,$mail3);

            }

            $mail4=app('App\Http\Controllers\PrestatairesController')->ChampById('mail4',$prest);
            if($mail4!='') {
                $data.='<option value="'.$mail4.'">'.$mail4.'</option>';
                array_push($mails,$mail4);

            }

            $mail5=app('App\Http\Controllers\PrestatairesController')->ChampById('mail5',$prest);
            if($mail5!='') {
                $data.='<option value="'.$mail5.'">'.$mail5.'</option>';
                array_push($mails,$mail5);

            }

       //    return ($data) ;
            return  response()->json(['options'=>$mails]);
          //  return '50000';

        }

    }

    /*
           $('.itemName').select2({
               placeholder: 'Sélectionner',
               ajax: {
                   url: "{{ route('emails.fetch') }}",
                   delay: 250,
                   processResults: function (data) {
                       return {
                           results:  $.map(data, function (attachements) {
                               return {
                                   text: attachements.nom,
                                   id: attachements.id
                               }
                           })
                       };
                   },
                   cache: true
               }
           });



    public function fetch(Request $request)
    {

        $data = [];

        if($request->has('q')){
            $search = $request->q;


            $data = DB::table("apps_countries")
                ->select("id","country_name")
                ->where('country_name','LIKE',"%$search%")
                ->get();
        }


        return response()->json($data);
    }

    */

}
