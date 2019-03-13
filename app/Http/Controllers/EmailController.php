<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DB;
use Webklex\IMAP\Client;

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



    function inbox()
    {
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
        $aMessage = $oFolder->messages()->all()->get();
        $paginator = $aMessage->paginate();

        echo '<table border="1" style="margin-left:50px; margin-top:50px;;max-width:800px;padding: 20px 20px 20px 20px">
   <thead style="padding-bottom:30px;color:blue">
   <tr>
    <td style="width:60%">Message</td>
    <td style="width:10%;padding:20px 20px 20px 20px">Date</td>
    <td style="width:10%;padding:20px 20px 20px 20px;">Emetteur</td>
    <td style="width:10%;padding:20px 20px 20px 20px;">Sujet</td>
    <td style="width:10%;padding:20px 20px 20px 20px;">Pièces jointes</td>
   </tr>
   </thead><tbody>';
        foreach($aMessage as $oMessage){
            //echo 'Sujet: '. $oMessage->getSubject().'<br />';
            //echo 'pièces jointes: '.$oMessage->getAttachments()->count().'<br />';
            //echo $oMessage->getHTMLBody(true);

            echo '
   <tr style="border-bottom:1px solid gray; margin-bottom:20px;">
    <td style="width:60%;">'.$oMessage->getHTMLBody(true).'</td>
    <td style=";width:10%;padding:20px 20px 20px 20px;">'. $oMessage->getDate().'</td>
    <td style="padding:20px 20px 20px 20px;;width:10%">'.$oMessage->getFrom()[0]->mail.'</td>
    <td style="width:10%;padding:20px 20px 20px 20px;">'. $oMessage->getSubject().'</td>
    <td style="width:10%;padding:20px 20px 20px 20px;;">'.$oMessage->getAttachments()->count().'</td>
   </tr>';
            if ($oMessage->hasAttachments()) {
                $aAttachment = $oMessage->getAttachments();
                //  $aAttachment->getContent();

                $aAttachment->each(function ($oAttachment) {
                    //////  $oAttachment->save('C:\Adobe');
                    //$contenu=   $oAttachment->getContent();
                    //echo 'Contenu : '.$contenu;
                    $name=   $oAttachment->getName();
                    echo 'Name : '.$name ;
                    $extension=   $oAttachment->getExtension();
                    echo 'Extension : '.$extension ;
                    $type=   $oAttachment->getType();
                    echo 'TYPE : '.$type;

                });

            }

            //Move the current Message to 'INBOX.read'
            ///if($oMessage->moveToFolder('INBOX.read') == true){
            ///echo 'Message has ben moved';
            ///}else{
            /// echo 'Message could not be moved';
            ///}

        }
        echo  '   </tbody>    </table>';


        return view('emails.inbox', ['paginator'=>$paginator,'aMessage'=>$aMessage]);

    } /// end inbox





}