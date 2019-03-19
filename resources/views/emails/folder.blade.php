<?php

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
        <td style=";width:10%;padding:20px 20px 20px 20px;">'.$oMessage->getDate().'</td>
        <td style="padding:20px 20px 20px 20px;;width:10%">'.$oMessage->getFrom()[0]->mail.'</td>
        <td style="width:10%;padding:20px 20px 20px 20px;">'.$oMessage->getSubject().'</td>
        <td style="width:10%;padding:20px 20px 20px 20px;;">'.$oMessage->getAttachments()->count().'</td>
    </tr>';
    if ($oMessage->hasAttachments()) {
        $aAttachment = $oMessage->getAttachments();
        //  $aAttachment->getContent();
        /*
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
        */
    }


}
echo  '   </tbody>    </table>';

?>