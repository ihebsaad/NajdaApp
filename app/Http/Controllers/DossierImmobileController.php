<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dossier;
use App\DossierImmobile;
use App\Client;
use App\Adresse;
use App\Envoye;

use Swift_Mailer;
use DB;
use Mail;
use App\Parametre; 



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

    public static function getDatecalcul()
    {
      return Parametre::first()->date_calcul;
    }
    public static function setDatecalcul($da)
    {
        Parametre::where('id', 1)->update(array('date_calcul' => $da));

    }

    public static function getCalculDossImm()
    {
      return Parametre::first()->calcul_doss_imm;
    }

    public static function setCalculDossImm($v)
    {
        Parametre::where('id', 1)->update(array('calcul_doss_imm' => $v));
      
    }

    public static function envoi_khaled_mail()
    {
        // test@najda-assistance.com
        //Mot de passe: Rem2018@najda1

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

                 //dd($dossier->customer_id.' '.$dossier->id.' '.$dossier->reference_medic);
                $cli=Client::where('id', $dossier->customer_id)->first();

              $arrayeml=Adresse::where('parent', $dossier->customer_id)->where('nature','email')->pluck('champ')->toArray();
              $arrayemails=array();
              $arraykbs=array();
              if(count($arrayeml)>0)
              {

                for($i=0; $i<count($arrayeml) ; $i++)
                {
                $arrayemails[]= '('.$arrayeml[$i].');';
                $arraykbs[]=Envoye::where('type','email')->where('destinataire','like', '%' .$arrayeml[$i].'%')->first();
                }
                  
              }
                //$adr=$arraykbs->latest()->first();
              usort($arraykbs, function($a, $b) {
                return $a['id'] <=> $b['id'];
               });
             //dd($arraykbs);
              $adresseStock='';
              if($arraykbs && count($arraykbs)>0)
              {
             $adresseStock=$arraykbs[count($arraykbs)-1]['destinataire'];
             $destinataires=$arraykbs[count($arraykbs)-1]['destinataire'];
             $destinataires = str_replace(array( '(', ')' ), '', $destinataires);
             $destinataires = str_replace(' ', '', $destinataires);
             $dests = explode(";", $destinataires); 
              }
            // dd($dests);


               /*$adr=Envoye::where('type','email')->where('dossier',$dossier->reference_medic)->whereIn('destinataire',$arrayemails)->orderBy('id', 'DESC')->first();*/
             //$adr=Envoye::where('type','email')->whereIn('destinataire',$arrayemails)->get();
               //dd($adr);

              //$adrcc=Envoye::where('type','email')->where('dossier',$dossier->reference_medic)->whereIn('destinataire',$arrayemails)->orderBy('id', 'DESC')->first()->cc;

                   // $adr=Adresse::where('parent', $dossier->id)->where(->first();
                   /* $env = Envoye::where('client',$idc)->where('type','email')->where('dossier',$dossier->reference_medic)->orderBy('id','desc')->first();
                    if (isset($env['destinataire'])) {
                    if($env['destinataire']>0){ 
                     $adr=$env['destinataire'] ; 
                      }
                      else
                        {
                          $adr='';
                         }
                      }
                      else{
                       $adr='';
                      }

                      //adresse cc au besoin
                   $env = Envoye::where('client',$idc)->where('type','email')->where('dossier',$dossier->reference_medic)->orderBy('id','desc')->first();
                    if (isset($env['cc'])) {
                     if($env['cc']!='')
                      {  $adrcc=$env['cc'] ;  
                    }else
                    $adrcc='';}
                     }else
                     {
                      $adrcc='';
                    }*/



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

                        if($adresseStock && $adresseStock !='')
                        {
                          $climail=$adresseStock;
                        }
                        else
                        {
                           $climail= null;
                        }

                       /* if($cli->mail != null && $cli->mail != '')
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

                        }*/


                    }


                    /*$nouv= new DossierImmobile ([
                      'dossier_id'=>$dossier->id,
                      'reference_doss' =>$dossier->reference_medic,
                      'client_id' =>$dossier->customer_id,
                      'client_name' =>$cliname,
                      'client_adresse' =>$climail,
                      'langue_client'=>$clilang,
                      'mail_auto_envoye' =>'Non',
                      'reponse_client' =>null,
                      'date_envoi_mail' =>null,
                      'updatedmiss_at'=>$dossier->updatedmiss_at

                    ]);

                    $nouv->save();*/

                 }

                }
           }

      }

        // pour chaque dossier immobile dont le client n'a pas reçu un email ; envoyer un email

        $dossim=DossierImmobile::get();
       // dd($dossim);
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
       // dd('verif');
        foreach ($dossim as $dm ) {
        
        if( self::checkImmobile3Days($dm->updatedmiss_at)==true )
         {
          //dd('verif');
           if($dm->client_adresse !=null )
           {

            if($dm->mail_auto_envoye !='Oui' )
             {

            if($dm->langue_client=='Fr')
            {
                $sujet = 'Clôture du dossier '.$dm->reference_doss;
                $contenu = "Bonjour de Najda,<br>
                Le dossier ".$dm->reference_doss." n'a vu aucune action ni instruction de votre part depuis 72 heures. Merci de nous indiquer si nous devons le clôturer, ou si vous avez de nouvelles instructions le concernant?<br>
                (Signé): Mail généré automatiquement";
            $contenu=$contenu.'<br><br>Cordialement <br> Najda <br><br><hr style="float:left;"><br><br>';


            }
            else
            {

             $sujet = 'Close the file '.$dm->reference_doss;
             $contenu = "Hello from Najda,<br>
              The file ".$dm->reference_doss." has seen no action or instruction from you for 72 hours. Please let us know if we need to close it, or if you have any new instructions concerning it?<br>
          (Signed): Mail generated automatically";
            $contenu=$contenu.'<br><br>Best regards <br> Najda <br><br><hr style="float:left;"><br><br>';


            }
            //$to=$dm->client_adresse ;
            //$cc = 'nejib.karoui@gmail.com';

            // $to='kbskhaled@gmail.com' ;
            // $cc = 'kbskhaledfb@gmail.com';
            $cc=array();

            $destinataires = null;
            $dests = null;
             if($dm->client_adresse)
             {

              $destinataires=$dm->client_adresse ;
              $destinataires = str_replace(array( '(', ')' ), '', $destinataires);
              $destinataires = str_replace(' ', '', $destinataires);
              $dests = explode(";", $destinataires); 

             }

             if(count($dests)>0 && $dests[0])
             {

              $to=$dests[0];
              
               if(count($dests)>1)
               {
                 // $cc='';
                  for($i=1 ;$i<count($dests) ; $i++)
                  {
                    if($dests[$i])
                    {
                      //$cc=$cc.$dests[$i].',';
                      array_push($cc,$dests[$i]);
                    }
  
                  }
  
               }
  
             }
             else
             {
               $to=$dm->client_adresse ; // null;
               $cc=null; 
             }

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
                'remarques' => 'dossier immobile plus que 4 jours mais le mail n\'est pas envoyé car l\'adresse mail est inexistante'             
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
