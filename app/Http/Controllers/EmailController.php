<?php

namespace App\Http\Controllers;
use App\Email;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use DB;
use Swift_Mailer;
use Swift_SmtpTransport;
use Webklex\IMAP\Client;
use App\Entree ;
use App\Dossier ;
use App\Attachement ;
use Mail;
use Spatie\PdfToText\Pdf;
use App;
Use Redirect;
use App\Envoye ;
use PDF as PDF2;
use Illuminate\Support\Facades\Auth;
use Twilio\Rest\Client as Client2;
use Twilio\Twiml;
use App\Notifications\Notif_Suivi_Doss;
 use Notification;
use Auth as auth2 ;
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

                if ( ! ((   strpos($sujet,'Undelivered Mail Returned' )!==false)   || (  strpos($sujet,'Mail delivery failed :' )!==false) )){
                 

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
                    'emetteur' => trim($from),
                    'sujet' => trim($sujet),
                    'contenu'=> utf8_encode($contenu) ,
                    'reception'=> $date,
                    'nb_attach'=> $nbattachs,
                    'type'=> 'email',
                     'mailid'=> $mailid,
                     'viewed'=>0,
                     'dossier'=>$refdossier,
                     'statut'=>$statut,

                ]);


                $entree->save();
                // Dispatching
                //$this->disp();
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

                } // check undelivered message

            } else {
                // error
                echo 'error';
            }


        }
        return $firstid;
       // return view('emails.check');

    } /// end check



    // dispaching des entrees
    function disp()
    {

        //  $aMessage = $oFolder->query()->text('tesssst')->get();

        $dossiers = DB::table('dossiers')->pluck('reference_medic');

        foreach ($dossiers as $ref) {


             $data = DB::table('entrees')
                ->where('statut', '=', 0)
                ->where('dossier', '=', null)
                ->where('sujet', 'LIKE', "%{$ref}%")

                ->orWhere(function($query)  use($ref)
                {
                    $query->where('statut', '=', 0)
                        ->where('dossier', '=', null)
                         ->where('contenu', 'LIKE', "%{$ref}%");
                })
                ->get();


          if(count ($data)>0)
          {

              $id=$data->pluck('id');
              $entree = Entree::find($id)->first();
              $entree->dossier = $ref ;
              $entree->statut = 1; // 1 dispatché
              $entree->save();

          }



        }

        return view('emails.disp');

    }

    public function sending()
    {
        $dossiers = Dossier::all();

        return view('emails.sending');
    }

    public function envoimail($id)
    {

        $ref=app('App\Http\Controllers\DossiersController')->RefDossierById($id);
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
            ->orderBy('created_at', 'desc')
            ->get();

        return view('emails.envoimail',['attachements'=>$attachements,'doss'=>$id]);
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



    function sendfax (Request $request)
    {

        /*  $request->validate([
              'g-recaptcha-response' => 'required|captcha'
          ]);
  */
        $doss = $request->get('dossier');

         $nom = $request->get('nom');
         $numero = $request->get('numero');
      //  $contenu = $request->get('contenu');
        $attachs = $request->get('attachs');

        $to='ihebsaad@gmail.com';
       // $to='envoifax@najda-assistance.com';
         $sujet='1234,Najda,najda,'.$nom.'@'.$numero.'';
        //$sujet=' test';

    /*   config(['mail.username' => 'saadiheb@gmail.com']);
        config(['mail.password' => 'ihebssss']);

        Config::set('mail.username', 'saadiheb@gmail.com');
        Config::set('mail.password', 'ihebssss');
*/

        //$swiftTransport = Swift_SmtpTransport::newInstance(env('MAIL2_HOST'), env('MAIL2_PORT'), env('MAIL2_ENCRYPTION'))
      /*    $swiftTransport =  new \Swift_SmtpTransport( env('MAIL2_HOST'), env('MAIL2_PORT'), env('MAIL2_ENCRYPTION'));
        $swiftTransport->setUsername(env('MAIL2_USERNAME' ));
            $swiftTransport->setPassword(env('MAIL2_PASSWORD' ));
*/
      /*  $swiftTransport =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '587', 'tls');
        $swiftTransport->setUsername('faxnajdassist@najda-assistance.com');
        $swiftTransport->setPassword('e-solutions2019');

        $swiftMailer = new Swift_Mailer($swiftTransport);

        Mail::setSwiftMailer($swiftMailer);
*/
        try{
          Mail::send([], [], function ($message) use ($to,$sujet,$attachs,$doss) {
            $message
                 ->to($to)
                ->subject($sujet)
             //   ->setBody($contenu, 'text/html');
            ->setBody('Fax Najda', 'text/html');

            $count=0;


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

                        'type'=>$ext,'path' => $fullpath, 'nom' => $name,'boite'=>1,'dossier'=>$doss
                    ]);
                    $attachement->save();


                }
            }


            $urlapp=env('APP_URL');

            if (App::environment('local')) {
                // The environment is local
                $urlapp='http://localhost/najdaapp';
            }
            $urlsending=$urlapp.'/emails/envoifax/'.$doss;
            if (Mail::failures()) {
                //     echo ('<script> window.location.href = "http://localhost/najdaapp/emails/sending";</script>') ;
                //    return redirect('http://localhost/najdaapp/emails/sending')->with('fail', ' Echec ! ');


            }else{
// save email sent

                $par=Auth::id();
                $envoye = new Envoye([
                    'emetteur' => 'test@najda-assistance.com', //env('emailenvoi')
                    'destinataire' => $to,
                    'par'=> $par,
                    'sujet'=> $sujet,
                    'contenu'=> '',
                    'attachements'=> $count,
                    'statut'=> 1,
                    'type'=> 'email',
                    // 'reception'=> date('d/m/Y H:i:s'),

                ]);

                $envoye->save();
                $id=$envoye->id;
                $this->export_pdf_send($id);

                echo ('<script> window.location.href = "'.$urlsending.'";</script>') ;
                return redirect($urlsending)->with('success', '  Envoyé ! ');


            }


          });

} catch (Exception $ex) {
    // Debug via $ex->getMessage();

    return "We've got errors!";

}

    }// end send




    function send (Request $request)
    {

      /*  $request->validate([
            'g-recaptcha-response' => 'required|captcha'
        ]);
*/
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



        try{
            Mail::send([], [], function ($message) use ($to,$sujet,$contenu,$files,$tot,$cc,$cci,$attachs,$doss) {
            $message
                ->to($to)
              ->cc($cc  ?: [])
                ->bcc($cci ?: [])
                ->subject($sujet)
         ->setBody($contenu, 'text/html');
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

                    'type'=>$ext,'path' => $path, 'nom' => $file->getClientOriginalName(),'boite'=>1,'dossier'=>$doss
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

                       'type'=>$ext,'path' => $fullpath, 'nom' => $name,'boite'=>1,'dossier'=>$doss
               ]);
                    $attachement->save();


            }
         }


      //  this works :
     /*  $path='C:\wamp2\www\najdaapp\storage\Envoyes\19\envoi.pdf';
        // $fich=$path->getRealPath();
         $message->attach($path , array(
                'as' => 'envoi.pdf',
                 'mime' => 'application/pdf'
              )
         );
*/

     $urlapp=env('APP_URL');

    if (App::environment('local')) {
        // The environment is local
        $urlapp='http://localhost/najdaapp';
    }
    $urlsending=$urlapp.'/emails/envoimail/'.$doss;
                $dossier= $this->RefDossierById($doss);////;

           $par=Auth::id();
           $envoye = new Envoye([
               'emetteur' => 'test@najda-assistance.com', //env('emailenvoi')
               'destinataire' => $to,
               'par'=> $par,
               'sujet'=> $sujet,
               'contenu'=> $contenu,
               'attachements'=> $count,
               'cc'=> $cc,
               'cci'=> $cci,
               'statut'=> 1,
               'type'=> 'email',
               'dossier'=> $dossier
              // 'reception'=> date('d/m/Y H:i:s'),

           ]);

           $envoye->save();
           $id=$envoye->id;
           $this->export_pdf_send($id);

            echo ('<script> window.location.href = "'.$urlsending.'";</script>') ;
                return redirect($urlsending)->with('success', '  Envoyé ! ');



     });

       } catch (Exception $ex) {
    // Debug via $ex->getMessage();
     return "We've got errors!";
     }

}// end send


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

        // If you want to store the generated pdf to the server then you can use the store function
        $pdf->save($path.$id.'/envoi.pdf');
        // Finally, you can download the file using download function
        //    return $pdf->download('reception.pdf');
    }
    function test()
    {
        $dossiers = Dossier::all();

/*
        $entree = new Entree([
            'emetteur' => 'sms',
            'sujet' => 'sms',
            'contenu'=> 'sms content' ,
          //  'reception'=> $date,
          //  'nb_attach'=> $nbattachs,
            'type'=> 'sms',
           'mailid'=> rand(50, 30000),

        ]);
        $entree->save();
*/

        /*
      if(\Gate::allows('isAdmin'))
      {

          return view('emails.test', ['dossiers' => $dossiers]);
       }
      else {
          // redirect
          return redirect('/')->with('success', 'droits insuffisants');


      }*/


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



        /*
                // SENDING

        // Your Account SID and Auth Token from twilio.com/console

        //test cred
                $sid = 'ACcd91fcfa5db064d6822d015be0c27a76';
                $token = 'a03a42703b75a79cb1cd370bc8b00926';
                // global test num  +15005550006

                //live cred
        $sid = 'ACa8d667427a2a2d4dfa58e23851804943';
        $token = 'a0257ac989f3f41bc81cbc3bf22ec18f';
        $client = new Client2($sid, $token);

        // Use the client to do fun stuff like send text messages!
        $client->messages->create(
        // the number you'd like to send the message to
            '+21650658586',
            array(
                // A Twilio phone number you purchased at twilio.com/console
                'from' => '+13342316588',
                // the body of the text message you'd like to send
                'body' => 'Hey iheb! this is a test from twilio!'
            )
        );
        */


// Receive

        /*
        $response = new Twiml;
        $response->message("The Robots are coming! Head for the hills!");
        print $response;

        */

        return view('emails.test', ['dossiers' => $dossiers]);

    }



    function sendsms(Request $request)
    {
        $to = trim($request->get('destinataire'));
        $message = trim( $request->get('message'));

        // Your Account SID and Auth Token from twilio.com/console
        $sid = 'ACbc8e777727bd13888701ffab59cd069f';
        //$sid = 'PN6d89ac7547a9548cbc5a4344ae097a0b';
        $token = '7b1d67de61c82c6aa10bdf01710f9147';
        $client = new Client2($sid, $token);

     //   $from='+14804473614';
        $from='+18479062370';

// Use the client to do fun stuff like send text messages!
        $client->messages->create(
        // the number you'd like to send the message to
            $to,
            array(
                // A Twilio phone number you purchased at twilio.com/console
                'from' => $from,
                // the body of the text message you'd like to send
                'body' => $message
            )
        );

        $par=Auth::id();

        $envoye = new Envoye([
            'emetteur' => $from,
            'destinataire' => $to,
            'sujet' => 'SMS',
            'contenu'=> $message,
            'statut'=> 1,
             'par'=> $par,
            'type'=>'sms'
        ]);

        $envoye->save();

        return redirect('/emails/sms')->with('success', 'SMS Envoyé !');

    }

    function sms( )
    {

        $dossiers = Dossier::all();

        return view('emails.sms', ['dossiers' => $dossiers]);

    }



    function sendwhatsapp(Request $request)
    {
        $to = trim($request->get('destinataire'));
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