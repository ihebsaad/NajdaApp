<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dossier;
use App\DossierImmobile;
use App\Client;

use Swift_Mailer;
use DB;
use Mail;



class DossierImmobileController extends Controller
{
   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public static function envoi_khaled_mail()
    {


        $parametres = DB::table('parametres')->where('id','=', 1 )->first();
        $swiftTransport =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '465', 'ssl');
        $swiftTransport->setUsername('24ops@najda-assistance.com');
        $swiftTransport->setPassword($parametres->pass_N);
        $fromname="Najda Assistance";
        $from='24ops@najda-assistance.com';

       for($i=0; $i < 2; $i++)
         {
         $sujet = 'Clôture du dossier '.'19TM0001'.$i;
                $contenu = "Bonjour de Najda,<br>
                Ce dossier n'a vu aucune action ni instruction de votre part depuis 72 heures. Merci de nous indiquer si nous devons le clôturer, ou si vous avez de nouvelles instructions le concernant?<br>
                (Signé): Mail généré automatiquement";
            $contenu=$contenu.'<br><br>Cordialement <br> Najda <br><br><hr style="float:left;"><br><br>';


   $to='kbskhaled@gmail.com' ;
             $cc = 'kbskhaledfb@gmail.com';

           $swiftMailer = new Swift_Mailer($swiftTransport);
                Mail::setSwiftMailer($swiftMailer);      
                Mail::send([], [], function ($message) use ($to, $sujet, $contenu, $cc,$from,$fromname) {
               $message        
               ->to($to)
               ->cc($cc ?: [])
               ->subject($sujet)
               ->setBody($contenu, 'text/html')
               ->setFrom([$from => $fromname]);
               });
            }
    }
    

     public static function mettreAjourTableDossImmobile()
    
    {
        // la mise à jour des liste des dossiers immobile se fait dans la page role.blade à la fin de la page

        // lire la liste de dossiers immobiles depuis DossierController
        $dossiers = Dossier::where('sub_status', 'immobile')
            ->where('current_status', 'inactif')
            ->get();

        // dossier immobile depuis 3 jours 

    $dossimm=array();

        $dossierTous=DossierImmobile::pluck('dossier_id')->toArray();
        //dd($dossierTous);
        //$somme=array_merge($dossierDormants,$dossierActifs);
       // $dossierImmobiles=array_diff($dossierTous,$somme);

    foreach($dossiers as $dossier)
        {
         if( !in_array($dossier->id,$dossierTous))            
            {
                if($dossier->updatedmiss_at) {

                 if( self::checkImmobile3Days($dossier->updatedmiss_at)==true)
                 {


                    $cli=Client::where('id', $dossier->customer_id)->first();

                    $cliname=null;
                    $climail=null;
                    $clilang=null;

                    if($cli)
                    {
                        $cliname=$cli->name;

                        if($cli->langue1=="francais" || stristr($cli->langue1, "fran"))
                        {
                            $clilang="Fr";

                        }
                        else
                        {

                            $clilang="Ang";

                        }

                        if($cli->mail != null && $cli->mail != '')
                        {
                            $climail=$cli->mail;
                        }
                       if(!$climail)
                        {
                           if($cli->mail2 != null && $cli->mail2 != '')
                           {
                            $climail=$cli->mail2;
                           }

                        }

                         if(!$climail)
                        {
                           if($cli->mail3 != null && $cli->mail3 != '')
                           {
                            $climail=$cli->mail3;
                           }

                        }

                        if($climail)
                        {
                           if(!stristr($climail, "@"))
                           {
                            $climail=null;
                           }

                        }


                    }


                    $nouv= new DossierImmobile ([
                      'dossier_id'=>$dossier->id,
                      'reference_doss' =>$dossier->reference_medic,
                      'client_id' =>$dossier->customer_id,
                      'client_name' =>$cliname,
                      'client_adresse' =>$climail,
                      'langue_client'=>$clilang,
                      'mail_auto_envoye' =>'Non',
                      'reponse_client' =>null,
                      'date_envoi_mail' =>null,
                      'updatedmiss_at'=>$dossier->pdatedmiss_at

                    ]);

                    $nouv->save();

                 }

                }
           }

      }

        // pour chaque dossier immobile dont le client n'a pas reçu un email ; envoyer un email

        $dossim=DossierImmobile::get();

        $parametres = DB::table('parametres')->where('id','=', 1 )->first();
        $swiftTransport =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '465', 'ssl');
        $swiftTransport->setUsername('24ops@najda-assistance.com');
        $swiftTransport->setPassword($parametres->pass_N);
        $fromname="Najda Assistance";
        $from='24ops@najda-assistance.com';

       $format = "Y-m-d H:i:s";
     //   $dtc = (new \DateTime())->format('Y-m-d H:i:s');

        $dtc = (new \DateTime())->format($format);

        $dateSys = \DateTime::createFromFormat($format, $dtc);

        foreach ($dossim as $dm ) {

        if( self::checkImmobile4Days($dm->updatedmiss_at)==true )
         {
           if($dm->client_adresse !=null )
           {

            if($dm->mail_auto_envoye !='Oui' )
             {

            if($dm->langue_client=='Fr')
            {
                $sujet = 'Clôture du dossier '.$dm->reference_doss;
                $contenu = "Bonjour de Najda,<br>
                Ce dossier n'a vu aucune action ni instruction de votre part depuis 72 heures. Merci de nous indiquer si nous devons le clôturer, ou si vous avez de nouvelles instructions le concernant?<br>
                (Signé): Mail généré automatiquement";
            $contenu=$contenu.'<br><br>Cordialement <br> Najda <br><br><hr style="float:left;"><br><br>';


            }
            else
            {

             $sujet = 'Close the file '.$dm->reference_doss;
             $contenu = "Hello from Najda,<br>
              This file has seen no action or instruction from you for 72 hours. Please let us know if we need to close it, or if you have any new instructions concerning it?<br>
          (Signed): Mail generated automatically";
            $contenu=$contenu.'<br><br>Best regards <br> Najda <br><br><hr style="float:left;"><br><br>';


            }
            //$to=$dm->client_adresse ;
            //$cc = 'nejib.karoui@gmail.com';

             $to='kbskhaled@gmail.com' ;
             $cc = 'kbskhaledfb@gmail.com';

           $swiftMailer = new Swift_Mailer($swiftTransport);
                Mail::setSwiftMailer($swiftMailer);      
                Mail::send([], [], function ($message) use ($to, $sujet, $contenu, $cc,$from,$fromname) {
               $message        
               ->to($to)
               ->cc($cc ?: [])
               ->subject($sujet)
               ->setBody($contenu, 'text/html')
               ->setFrom([$from => $fromname]);
               });


             $dm->update([

                'mail_auto_envoye'=> 'Oui',
                'date_envoi_mail' =>$dateSys  ,
                'reponse_client'  =>null
             
             ]);  
            }


         }
         else
         {
         // adresse null pas d'envoi 

              $dm->update([
                'mail_auto_envoye'=> 'Non',
                'date_envoi_mail' =>$dateSys  ,
                'remarques' => 'dossier dormant plus que 4 jours mais le mail n\'est pas envoyé car l\'adresse mail est inexistante'             
             ]);


         }


        }


            
        }

        
        
         
    


      


/*Bonjour de Najda,
Ce dossier n'a vu aucune action ni instruction de votre part depuis 72 heures. Merci nous indiquer si nous devons le clôturer, ou si vous avez de nouvelles instructions le concernant?
(Signé): Mail généré automatiquement
Et le reste de la signature de l'entité*/


/*Hello from Najda,
This file has seen no action or instruction from you for 72 hours. Please let us know if we need to close it, or if you have any new instructions regarding it?
(Signed): Mail generated automatically
And the rest of the entity signature*/

        
    }// fin fonction


    public static function checkImmobile3Days($date)
    {

        $format = "Y-m-d H:i:s";
     //   $dtc = (new \DateTime())->format('Y-m-d H:i:s');

        $dtc = (new \DateTime())->modify('-3 days')->format($format);

        $dateSys = \DateTime::createFromFormat($format, $dtc);


        $dateDoss = (\DateTime::createFromFormat($format, $date) );


        if($dateDoss <= $dateSys)
        {
            return true;
        }else{
            return false ;
          }


    }



    public static function checkImmobile4Days($date)
    {

        $format = "Y-m-d H:i:s";
     //   $dtc = (new \DateTime())->format('Y-m-d H:i:s');

        $dtc = (new \DateTime())->modify('-4 days')->format($format);

        $dateSys = \DateTime::createFromFormat($format, $dtc);


        $dateDoss = (\DateTime::createFromFormat($format, $date) );


        if($dateDoss <= $dateSys)
        {
            return true;
        }else{
            return false ;
    }


    }

   
}
