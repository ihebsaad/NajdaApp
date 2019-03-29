<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
 use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use DB;
use Webklex\IMAP\Client;
use App\Entree ;
use App\Dossier ;
use App\Attachement ;
use Mail;
use Spatie\PdfToText\Pdf;


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

            $sujet=$oMessage->getSubject()  ;
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
                $entree = new Entree([
                    'emetteur' => $from,
                    'sujet' => $sujet,
                    'contenu'=> utf8_encode($contenu) ,
                    'reception'=> $date,
                    'nb_attach'=> $nbattachs,
                    'type'=> 'email',
                    'mailid'=> $mailid,

                ]);

                $entree->save();

                $id=$entree->id;

                if($storeid==false){
                    $firstid=$id;
                    $storeid=true;
                }
                $aAttachment = $oMessage->getAttachments();

                $aAttachment->each(function ($oAttachment) use ($id){
                    $path= storage_path()."\\Emails\\";
                    /** @var \Webklex\IMAP\Attachment $oAttachment */
                    if (!file_exists($path.$id)) {
                        mkdir($path.$id, 0777, true);
                    }
                    // save in folder
                    $oAttachment->save($path.$id);
                    // save in DB


                    $nom = $oAttachment->getName();

                // verifier si l'attachement pdf contient des mots de facturation
/*
                    $path=$path.$id."\\".$nom;
                    $path=realpath($path);
                          $text = (new Pdf())
                             ->setPdf($path )
                                ->text();
                          $facturation='';
                               $string='applications';
                                 if(strpos($text,$string)!==false)
                                 {
                                     $facturation=$string;
                                  }
*/
                   $path2= '/Emails/'.$id.'/'.$nom ;

                    $type=  $oAttachment->getExtension();
                    $attach = new Attachement([
                        'nom' => $nom,
                        'type' => $type,
                         'path'=> $path2,
                         'parent'=> $id,
                  //      'facturation'=> $facturation,

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



    // dispaching des entrees
    function disp()
    {

        //  $aMessage = $oFolder->query()->text('tesssst')->get();

        $dossiers = DB::table('dossiers')->pluck('ref');

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

        return view('emails.disp',['dossiers' => $dossiers]);

    }

    public function sending()
    {
        $dossiers = Dossier::all();

        return view('emails.sending',['dossiers' => $dossiers]);
    }


    function send (Request $request)
    {
        $to = $request->get('destinataire');
        $sujet = $request->get('sujet');
        $contenu = $request->get('contenu');
        $files = $request->file('files');
       $tot= count($_FILES['files']['name']);

     if (   Mail::send([], [], function ($message) use ($to,$sujet,$contenu,$files,$tot) {
            $message
                ///  ->from('iheb@enterpriseesolutions.com', 'Houba')
              //  ->to('ihebsaad@gmail.com', 'iheb')
                ->to($to)
                ->subject($sujet)
         ->setBody($contenu);

         if(isset($files )) {

             foreach($files as $file) {
                 $message->attach($file->getRealPath(), array(
                         'as' => $file->getClientOriginalName(), // If you want you can chnage original name to custom name
                         'mime' => $file->getMimeType())
                 );
             }
         }

     }) ){

          redirect('/emails/boite')->with('success', 'Envoyé avec succès ');


     }

    }// end sed

    function test()
    {
        /*
      if(\Gate::allows('isAdmin'))
      {
          $dossiers = Dossier::all();

          return view('emails.test', ['dossiers' => $dossiers]);
       }
      else {
          // redirect
          return redirect('/')->with('success', 'droits insuffisants');


      }*/

        $path=storage_path()."\\Emails\\".'50\wordpress.pdf';
        echo 'Path : '. $path;
        $path=realpath($path);
        $text = (new Pdf())
            ->setPdf($path )
            ->text();
        $facturation='';
        $string='applications';
        if(strpos($text,$string)!==false)
        {
            $facturation=$string;
            echo  'Facturation'.$facturation;
        }

        return view('emails.test', ['dossiers' => $dossiers]);

    }


    }