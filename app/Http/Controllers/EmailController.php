<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
 use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use DB;
use Webklex\IMAP\Client;
use App\Entree ;
use App\Dossier ;
use Mail;


class EmailController extends Controller
{

    function index()
    {
        Log::info('opening emails index');

        //     $oClient = Client::account('default');
        //       $oClient->connect();

        $oClient = new Client([
            'host'          =>  env('hostreception'),
            'port'          =>  env('portreception'),
        //    'encryption'    => env('encreception'),
            'validate_cert' => true,
            'username'      => env('emailreception'),
            'password'      => env('passreception'),
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



        return view('emails.index', ['paginator'=>$paginator]);
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



        return view('emails.folder', ['paginator'=>$paginator,'aMessage'=>$aMessage]);
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


        $oFolder = $oClient->getFolder('INBOX');
        $oMessage = $oFolder->getMessage($uid);


        // Recherche
        /*   $aMessage = $oFolder->query()->text('tesssst')->get();*/



        return view('emails.open', ['oMessage'=>$oMessage]);
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


        $oFolder = $oClient->getFolder('INBOX');


        $aMessage = $oFolder->messages()->all()->limit(5, 1)->get();
      //  $aMessage = $oFolder->query()->unseen()->get();

        //->limit(10, 2)
        //->limit(10, 2)
        // Recherche
        /*   $aMessage = $oFolder->query()->text('tesssst')->get();*/

        $paginator = $aMessage->paginate();


        return view('emails.maboite', ['paginator'=>$paginator,'aMessage'=>$aMessage]);
    }

    function inbox()
    {

     $oClient = new Client([
            'host'          => 'ssl0.ovh.net',// env('hostreception'),
            'port'          => '993',// env('portreception'),
        //    'encryption'    => '',//env('encreception'),
            'validate_cert' => true,
            'username'      => env('emailreception'),
            'password'      => env('passreception'),
            'protocol'      => 'imap'
        ]);

/*
        $oClient = new Client([
            'host'          => 'ssl0.ovh.net',// env('hostreception'),
            'port'          => '993',// env('portreception'),
            //    'encryption'    => '',//env('encreception'),
            'validate_cert' => true,
            'username'      =>'test@najda-assistance.com',
            'password'      => 'esol@2109',
            'protocol'      => 'imap'
        ]);
*/
//Connect to the IMAP Server
        $oClient->connect();


        $oFolder = $oClient->getFolder('INBOX');
        $aMessage = $oFolder->messages()->all()->get();
        $paginator = $aMessage->paginate();


        return view('emails.inbox', ['paginator'=>$paginator,'aMessage'=>$aMessage]);

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
            $from= $oMessage->getFrom()[0]->mail;
            $date= $oMessage->getDate();
            $mailid=$oMessage->getUid();

            //Move the current Message to 'INBOX.read'
            if ($oMessage->moveToFolder('read') == true) {
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
                    $path= storage_path().'/Emails/';
                    /** @var \Webklex\IMAP\Attachment $oAttachment */
                    if (!file_exists($path.$id)) {
                        mkdir($path.$id, 0777, true);
                    }
                    $oAttachment->save($path.$id);
                });

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

        return view('emails.disp');

    }

    public function sending()
    {
        return view('emails.sending');
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
                ->to('ihebsaad@gmail.com', 'iheb')
               // ->to($to)
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
        return view('emails.test');

    }


    }