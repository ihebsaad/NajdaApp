<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

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

        //     $oClient = Client::account('default');
        //       $oClient->connect();

        $oClient = new Client([
            'host'          => 'imap.ionos.com',
            'port'          => 993,
            'encryption'    => 'ssl',
            'validate_cert' => true,
            'username'      => 'test@enterpriseesolutions.com',
            'password'      => 'I-saad2014',
            'protocol'      => 'imap'
        ]);

        //  I-saad2014
        /* Alternative by using the Facade
        $oClient = Webklex\IMAP\Facades\Client::account('default');
        */

//Connect to the IMAP Server
        $oClient->connect();

//Get all Mailboxes
        /** @var \Webklex\IMAP\Support\FolderCollection $aFolder */
        $aFolder = $oClient->getFolders();
        $oFolder = $oClient->getFolder('INBOX');

//Loop through every Mailbox
        /** @var \Webklex\IMAP\Folder $oFolder */
//foreach($aFolder as $oFolder){

        //Get all Messages of the current Mailbox $oFolder
        /** @var \Webklex\IMAP\Support\MessageCollection $aMessage */
        // $aMessage = $oFolder->query()->since('25.02.2019')->limit(10, 2)->get();


        $aMessage = $oFolder->messages()->all()->get();
        // Recherche
     /*   $aMessage = $oFolder->query()->text('tesssst')->get();*/

        $paginator = $aMessage->paginate();
        // $paginator = $oFolder->search()
        //     ->since(\Carbon::now()->subDays(14))->get()
        //      ->paginate($perPage = 5, $page = null, $pageName = 'autocomplete.blade');
        //  $aMessage = $oFolder->messages()->all()->get();

        /** @var \Webklex\IMAP\Message $oMessage */

//}



        return view('emails.index', ['paginator'=>$paginator]);
    }

// voir la liste des emails par dossier
    function folder( $foldername)
    {

        //     $oClient = Client::account('default');
        //       $oClient->connect();

        $oClient = new Client([
            'host'          => 'imap.ionos.com',
            'port'          => 993,
            'encryption'    => 'ssl',
            'validate_cert' => true,
            'username'      => 'test@enterpriseesolutions.com',
            'password'      => 'I-saad14',
            'protocol'      => 'imap'
        ]);

        //  I-saad2014
        /* Alternative by using the Facade
        $oClient = Webklex\IMAP\Facades\Client::account('default');
        */

//Connect to the IMAP Server
        $oClient->connect();

//Get all Mailboxes
        /** @var \Webklex\IMAP\Support\FolderCollection $aFolder */
         $oFolder = $oClient->getFolder($foldername);
    ///    $oFolder = $oClient->getFolder('test');

//Loop through every Mailbox
        /** @var \Webklex\IMAP\Folder $oFolder */
//foreach($aFolder as $oFolder){

        //Get all Messages of the current Mailbox $oFolder
        /** @var \Webklex\IMAP\Support\MessageCollection $aMessage */
        // $aMessage = $oFolder->query()->since('25.02.2019')->limit(10, 2)->get();


        $aMessage = $oFolder->messages()->all()->get();

        // Recherche
        /*   $aMessage = $oFolder->query()->text('tesssst')->get();*/

        $paginator = $aMessage->paginate();
        // $paginator = $oFolder->search()
        //     ->since(\Carbon::now()->subDays(14))->get()
        //      ->paginate($perPage = 5, $page = null, $pageName = 'autocomplete.blade');
        //  $aMessage = $oFolder->messages()->all()->get();

        /** @var \Webklex\IMAP\Message $oMessage */

//}

        return view('emails.folder', ['paginator'=>$paginator,'aMessage'=>$aMessage]);
    }


    function inbox()
    {
        $oClient = new Client([
            'host'          => 'imap.ionos.com',
            'port'          => 993,
            'encryption'    => 'ssl',
            'validate_cert' => true,
            'username'      => 'test@enterpriseesolutions.com',
            'password'      => 'I-saad14',
            'protocol'      => 'imap'
        ]);

        //  I-saad2014
        /* Alternative by using the Facade
        $oClient = Webklex\IMAP\Facades\Client::account('default');
        */

//Connect to the IMAP Server
        $oClient->connect();

//Get all Mailboxes
        /** @var \Webklex\IMAP\Support\FolderCollection $aFolder */
        $aFolder = $oClient->getFolders();
        $oFolder = $oClient->getFolder('INBOX');
        $aMessage = $oFolder->messages()->all()->get();
        $paginator = $aMessage->paginate();


        return view('emails.inbox', ['paginator'=>$paginator,'aMessage'=>$aMessage]);

    } /// end inbox


    function check()
    {


        $oClient = new Client([
            'host'          => 'imap.ionos.com',
            'port'          => 993,
            'encryption'    => 'ssl',
            'validate_cert' => true,
            'username'      => 'test@enterpriseesolutions.com',
            'password'      => 'I-saad14',
            'protocol'      => 'imap'
        ]);

        //  I-saad2014
        /* Alternative by using the Facade
        $oClient = Webklex\IMAP\Facades\Client::account('default');
        */

//Connect to the IMAP Server
        $oClient->connect();
        $aFolder = $oClient->getFolders();

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
                    ]);

                    $entree->save();
                } else {
                // error
                echo 'error';
                }


         //  $aMessage = $oFolder->query()->text('tesssst')->get();

                $dossiers = DB::table('dossiers')->pluck('ref');

                foreach ($dossiers as $ref) {
                   $data = DB::table('entrees')
                        ->where('sujet', 'LIKE', "%{$ref}%")
                        ->orwhere('contenu', 'LIKE', "%{$ref}%")
                        ->get();

                 //  echo $data ;

/*
                    Entree::where(function ($query,$ref) {
                        $query
                            ->where('sujet', 'LIKE', "%{$ref}%")
                            ->orWhere('contenu', 'LIKE', "%{$ref}%");
                    })->where(function ($query) {
                        $query->where('statut', '=', 0);
                     });
                    */

                }


            }
         return view('emails.check');

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

    }

}