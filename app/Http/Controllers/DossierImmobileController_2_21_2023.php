<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dossier;
use App\DossierImmobile;
use App\Client;
use App\Adresse;
use App\Envoye;
use App\Entree;
use App\EmailAuto;
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
            $bcc=array();
                   array_push($bcc,'finances@medicmultiservices.com');
           $swiftMailer = new Swift_Mailer($swiftTransport);
                Mail::setSwiftMailer($swiftMailer);      
                Mail::send([], [], function ($message) use ($to, $sujet, $contenu, $cc,$from,$fromname) {
               $message        
               ->to($to)
               ->cc($cc ?: [])
               ->bcc($bcc ?: [])
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
        $dtc = (new \DateTime())->format('2021-01-01 00:00:00');
        $dossiers = Dossier::where('sub_status', 'immobile')
            ->where('current_status','!=','Cloture')
            ->whereNotNull('updatedmiss_at')
            ->whereNotNull('created_at')
            ->where('created_at','>=', $dtc)
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
                      'updatedmiss_at'=>$dossier->updatedmiss_at

                    ]);

                    $nouv->save();

                 }

                }
           }

      }

        // pour chaque dossier immobile dont le client n'a pas reçu un email ; envoyer un email

        $dossim=DossierImmobile::get();
       // dd($dossim);
        $parametres = DB::table('parametres')->where('id','=', 1 )->first();
       // $swiftTransport =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '465', 'ssl');
        $swiftTransport =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '465', 'ssl');
        
        $swiftTransport->setUsername('24ops@najda-assistance.com');
       // $swiftTransport->setUsername('test@najda-assistance.com');
        $swiftTransport->setPassword($parametres->pass_N);
        //$swiftTransport->setPassword('esol@2109');
        $fromname="Najda Assistance (test email auto. doss. immobiles)";
       $from='24ops@najda-assistance.com';
        //$from='test@najda-assistance.com';
       $signatureNajda=$parametres->signature;
        

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
               /* $sujet = 'Clôture du dossier '.$dm->reference_doss;
                $contenu = "Bonjour de Najda,<br>
                Le dossier ".$dm->reference_doss." n'a vu aucune action ni instruction de votre part depuis 72 heures. Merci de nous indiquer si nous devons le clôturer, ou si vous avez de nouvelles instructions le concernant?<br>
                (Signé): Mail généré automatiquement";
            $contenu=$contenu.'<br><br>Cordialement <br> Najda Assistance<br><br><hr style="float:left;"><br><br>';*/

            $sujet = 'Clôture du dossier '.$dm->reference_doss;
            $contenu = "Bonjour de Najda,<br><br>
                    Nous avons constaté qu’aucune action n’a été entreprise dans ce dossier depuis 3 jours, et aucune action n’y est programmée.<br><br> Merci de nous indiquer s’il y a lieu de le clôturer ou si nous devons le garder ouvert. Et dans ce dernier cas quelles sont vos instructions pour la suite ?<br><br>
                (Signé): Ceci est un email généré automatiquement par le système de gestion de Najda Assistance.";
            $contenu=$contenu.'<br><br>Cordialement <br><br>'.$signatureNajda.' <br><br><hr style="float:left;"><br><br>';

            }
            else
            {

             /*$sujet = 'Close the file '.$dm->reference_doss;
             $contenu = "Hello from Najda,<br>
              The file ".$dm->reference_doss." has seen no action or instruction from you for 72 hours. Please let us know if we need to close it, or if you have any new instructions concerning it?<br>
            (Signed): Mail generated automatically";
            $contenu=$contenu.'<br><br>Best regards <br> Najda Assistance <br><br><hr style="float:left;"><br><br>';*/
             $sujet = 'Close the file '.$dm->reference_doss;
             $contenu = "Hello from Najda,<br><br>
              We noticed that no action has been taken on this file for 3 days, and there is no action scheduled for the upcoming days.<br><br>
              Please let us know whether we should close the file or keep it open. In that case what are your following instructions?<br><br>
            Best regards,
            <br><br>
            (Signed): This is an automatically generated email by the management system of Najda Assistance.";
            $contenu=$contenu.'<br><br>Best regards <br><br>'.$signatureNajda.' <br><br><hr style="float:left;"><br><br>';

            }
            //$to=$dm->client_adresse ;
            //$cc = 'nejib.karoui@gmail.com';

            // $to='kbskhaled@gmail.com' ;
            // $cc = 'kbskhaledfb@gmail.com';
            $cc=array();
            $bcc=array();

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
                    // cc nejib karoui; 
                   array_push($bcc,'nejib.karoui@gmail.com');
                   array_push($bcc,'kbskhaled@gmail.com');
  array_push($bcc,'finances@medicmultiservices.com');
               }
  
             }
             else
             {
               $to=$dm->client_adresse ; // null;
                // cc nejib karoui; 
                  array_push($bcc,'nejib.karoui@gmail.com');
                  array_push($bcc,'kbskhaled@gmail.com');
  array_push($bcc,'finances@medicmultiservices.com');
               //$cc=null; 
             }

           $swiftMailer = new Swift_Mailer($swiftTransport);
                Mail::setSwiftMailer($swiftMailer);      
                Mail::send([], [], function ($message) use ($to, $sujet, $contenu, $cc,$bcc,$from,$fromname) {
               $message        
               ->to($to)
               ->cc($cc ?: [])
               ->bcc($bcc ?: [])
               ->subject($sujet)
               ->setBody($contenu, 'text/html')
               ->setFrom([$from => $fromname]);
               });


             $dm->update([

                'mail_auto_envoye'=> 'Oui',
                'date_envoi_mail' =>$dateSys ,
                'reponse_client'  =>null
             
             ]); 

            $emaiautodestcc = implode(";", $cc);

             // sauvgarder dans la table d'envoi mail auto 

            
                $emaiauto=new EmailAuto ([
                      'dossierid'=>$dm->dossier_id,
                      'dossier' =>$dm->reference_doss,
                      'client' =>$dm->client_name,
                      'destinataire' =>$dm->client_adresse,
                      'emetteur'=>$from,
                      'cc'=>$emaiautodestcc,
                      'sujet'=>$sujet, 
                      'contenutxt' =>$contenu,
                      'type'=>'dossier_immobile'                    

                    ]);

              $emaiauto->save();


            }


         }
         else
         {
         // adresse null pas d'envoi 

              $dm->update([
                'mail_auto_envoye'=> 'Non',
                'date_envoi_mail' =>$dateSys  ,
                'remarques' => 'dossier immobile plus que 4 jours mais le mail n\'est pas encore envoyé car l\'adresse mail destinataire est inexistante'             
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

    public static function checkImmobile3Daysv2($date, $refdoss)
    {
        $format = "Y-m-d H:i:s";
        $dernier_date_envoi_mail=Envoye::where('dossier','like','%'.$refdoss.'%')->orderBy('created_at','desc')->first();
         if($dernier_date_envoi_mail)
		 {
	       $dernier_date_envoi_mail=$dernier_date_envoi_mail->created_at;
		   $dernier_date_envoi_mail=\DateTime::createFromFormat($format, $dernier_date_envoi_mail);
		 }
		 
		 
        $dernier_date_recep_mail=Entree::where('dossier','like','%'.$refdoss.'%')->orderBy('created_at','desc')->first();
       
	  if($dernier_date_recep_mail)
		 {  
		$dernier_date_recep_mail=$dernier_date_recep_mail->created_at;
        
        $dernier_date_recep_mail=\DateTime::createFromFormat($format, $dernier_date_recep_mail);
		 }

        
     //   $dtc = (new \DateTime())->format('Y-m-d H:i:s');


        $dtc = (new \DateTime())->modify('-3 days')->format($format);

        $dateSys = \DateTime::createFromFormat($format, $dtc);

        $dateDoss ='';
		if($date)
		{
        $dateDoss = (\DateTime::createFromFormat($format, $date) );
	    }
		
		if(!dernier_date_envoi_mail && !dernier_date_recep_mail)
		{
			if($dateDoss)
			{
				if($dateDoss <= $dateSys)
				{
					return true;
				}
				else	
				{
					return false;
					
				}
					
			}	
			else{
				return false;
			}	
		}
		if($dateDoss && $dernier_date_envoi_mail && $dernier_date_recep_mail)
		{

        if($dateDoss <= $dateSys && ($dernier_date_envoi_mail <= $dateSys && $dernier_date_recep_mail <= $dateSys) )
        {
            return true;
        }else{
            return false ;
          }
		}
		
		return false; 


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

    public static function mettreAjourTableDossImmobile_version2()    
    {
		
		//dd('b');
        // la mise à jour des liste des dossiers immobile se fait dans la page role.blade à la fin de la page

        // lire la liste de dossiers immobiles depuis DossierController
        $dtc = (new \DateTime())->format('2021-01-01 00:00:00');
        $dossiers = Dossier::where('sub_status', 'immobile')
            ->where('current_status','!=','Cloture')
            ->whereNotNull('updatedmiss_at')
            ->whereNotNull('created_at')
            ->where('created_at','>=', $dtc)
            ->get();
      //   $dossiers = Dossier::where('id',41389 )->orWhere('id',41388)->get();
        // dossier immobile depuis 3 jours 

    $dossimm=array();

        $dossierTous=DossierImmobile::pluck('dossier_id')->toArray();
        //dd($dossierTous);
        //$somme=array_merge($dossierDormants,$dossierActifs);
       // $dossierImmobiles=array_diff($dossierTous,$somme);
        // test pluck
         //$arrayeml=Adresse::where('parent', 2)->where('nature','email')->pluck('champ')->toArray();
         //dd($arrayeml);
         // fin test 
    foreach($dossiers as $dossier)
        {
         if( !in_array($dossier->id,$dossierTous))            
            {
                if($dossier->updatedmiss_at) {

                 if( self::checkImmobile3Days($dossier->updatedmiss_at)==true)
                 {

                 //dd($dossier->customer_id.' '.$dossier->id.' '.$dossier->reference_medic);
                $cli=Client::where('id', $dossier->customer_id)->first();
             $arrayeml=[];
             if(trim($dossier->type_dossier)=="Medical" || trim($dossier->type_dossier)=="Transport")
             {
              $arrayeml=Adresse::where('parent', $dossier->customer_id)->where('nature','email')->where('type','like', '%medical%')->pluck('champ')->toArray();
             }

             if(trim($dossier->type_dossier)=="Technique")
             {
              $arrayeml=Adresse::where('parent', $dossier->customer_id)->where('nature','email')->where('type','like', '%technique%')->pluck('champ')->toArray();
             }

             if(trim($dossier->type_dossier)=="Mixte")
             {
              $arrayeml=Adresse::where('parent', $dossier->customer_id)->where('nature','email')->where('type','like', '%commun%')->pluck('champ')->toArray();

               if(! $arrayeml || count($arrayeml)==0 )
               {

                  $arrayeml=Adresse::where('parent', $dossier->customer_id)->where('nature','email')->where(function($q) {                             
                               $q->where('type','like', '%technique%')->orWhere('type','like', '%medical%');
                                })->pluck('champ')->toArray();
               }
             }
			 // get last email
			 
			  $dernier_envoi_mail=Envoye::where('dossier','like','%'.$dossier->reference_medic.'%')->orderBy('created_at','desc')->first();
              if($dernier_envoi_mail)
			  {
				$dernier_envoi_mail=$dernier_envoi_mail->destinataire;
			  }
			  
              $dernier_recep_mail=Entree::where('dossier','like','%'.$dossier->reference_medic.'%')->orderBy('created_at','desc')->first();
              if($dernier_recep_mail)
			  {
			    $dernier_recep_mail=$dernier_recep_mail->emetteur;
			  }
			  
               if(! $arrayeml || count($arrayeml)==0 )
               {
				   if($dernier_envoi_mail)
				   {
					   $arrayeml[]=$dernier_envoi_mail;
				   }
			   }
			   
			    if(! $arrayeml || count($arrayeml)==0 )
               {
				   if($dernier_recep_mail)
				   {
					   $arrayeml[]=$dernier_recep_mail;
				   }
			   }
			  

            // dd($arrayeml);
             for($i=0; $i<count($arrayeml); $i++)
             {
                 if(stripos($arrayeml[$i],'@')==false)
                 {
                    unset($arrayeml[$i]);                    
                 }

             }
             $adresseStock= implode(" ", $arrayeml);
            // dd($adresseStock);
              //dd($arrayeml);

             // $arrayeml=Adresse::where('parent', $dossier->customer_id)->where('nature','email')->pluck('champ')->toArray();
              //$adr=$arraykbs->latest()->first();

             // ancien version decodage mail
             /* $arrayemails=array();
              $arraykbs=array();
              if(count($arrayeml)>0)
              {

                for($i=0; $i<count($arrayeml) ; $i++)
                {
                $arrayemails[]= '('.$arrayeml[$i].');';
                $arraykbs[]=Envoye::where('type','email')->where('destinataire','like', '%' .$arrayeml[$i].'%')->first();
                }
                  
              }
                            
              usort($arraykbs, function($a, $b) {
                return $a['id'] <=> $b['id'];
               });
              $adresseStock='';
              if($arraykbs && count($arraykbs)>0)
              {
             $adresseStock=$arraykbs[count($arraykbs)-1]['destinataire'];
             $destinataires=$arraykbs[count($arraykbs)-1]['destinataire'];
             $destinataires = str_replace(array( '(', ')' ), '', $destinataires);
             $destinataires = str_replace(' ', '', $destinataires);
             $dests = explode(";", $destinataires); 
              }
*/
             // fin ancien version 

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

                       
                    }
                     
                     $nom_ass='';
                     $prenom_ass='';
                     $nom_prenom='';
              
                    $nom_ass=$dossier->subscriber_name;
                    $prenom_ass=$dossier->subscriber_lastname; 
              
                    if( $nom_ass && $prenom_ass)
                    {
                     $nom_prenom=$prenom_ass.' '.$nom_ass ; 
                    }
                    // à décommenter
                    /*$nouv= new DossierImmobile ([
                      'dossier_id'=>$dossier->id,
                      'reference_doss' =>$dossier->reference_medic,
                      'nom_assure' =>$nom_prenom,
                      'ref_client'=>$dossier->reference_customer,
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
//dd(" ok ");
        // pour chaque dossier immobile dont le client n'a pas reçu un email ; envoyer un email

        $dossim=DossierImmobile::get();
        $parametres = DB::table('parametres')->where('id','=', 1 )->first();
        $swiftTransport =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '465', 'ssl');
        $swiftTransport->setUsername('24ops@najda-assistance.com');
        $swiftTransport->setPassword($parametres->pass_N);
        $fromname="Najda Assistance (test email auto. doss. immobiles)";
        $from='24ops@najda-assistance.com';
        $signatureNajda=$parametres->signature;

         $parametres = DB::table('parametres')->where('id','=', 1 )->first();
      $swiftTransport_n =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '465', 'ssl');
      $swiftTransport_n->setUsername('24ops@najda-assistance.com');
      $swiftTransport_n->setPassword($parametres->pass_N);
      $fromname_n="Najda Assistance (test email auto)";
      $from_n='24ops@najda-assistance.com';
      $signatureNajda=$parametres->signature;

      // instancier swift for finances

      $swiftTransport_f =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '587', '');
      $swiftTransport_f->setUsername('finances@najda-assistance.com');
      $swiftTransport_f->setPassword($parametres->pass_Finances);
      $fromname_f="Najda Finances (test email auto)";
      $from_f='finances@najda-assistance.com';
      $signatureFinances_f=$parametres->signature10;
     //dd($signatureFinances);
      //$x=1/0;

       /*$adr=Adresse::where('parent', 10000)->where('nature','emailinterv')->orderBy('id')->pluck('champ')->toArray();
       $ccea=self::return_dest_cc($adr);       
       $ccea = implode(";", $ccea);
       dd($ccea);
       $toea=self::return_dest_to($adr);
       //dd($toea);*/

           $from_tpa='tpa@najda-assistance.com';
           $pass_TPA=$parametres->pass_TPA ;
          //  $swiftTransport =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '587', '');
            $swiftTransport_tpa=  new \Swift_SmtpTransport( 'ssl0.ovh.net', '465', 'ssl');
            $swiftTransport_tpa->setUsername('tpa@najda-assistance.com');
            $swiftTransport_tpa->setPassword($pass_TPA);
            $fromname_tpa="Najda Assistance (TPA)";
            $signatureentite_tpa= $parametres->signature7 ;


           $from_TN='taxi@najda-assistance.com';
           $pass_TN=$parametres->pass_TN ;
          //  $swiftTransport =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '587', '');
            $swiftTransport_TN =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '465', 'ssl');
            $swiftTransport_TN->setUsername('taxi@najda-assistance.com');
            $swiftTransport_TN->setPassword($pass_TN);
            $fromname_TN="Najda Transport";
            $signatureentite_TN= $parametres->signature8 ;

       
           $from_XP='x-press@najda-assistance.com';
           $pass_XP=$parametres->pass_XP ;
            $swiftTransport_XP =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '465', 'ssl');
            $swiftTransport_XP->setUsername('x-press1@najda-assistance.com');
            $swiftTransport_XP->setPassword($pass_XP);
            $fromname_XP="X-Press remorquage";
            $signatureentite_XP= $parametres->signature9 ;


            $from_V='hotels.vat@medicmultiservices.com';
            $pass_VAT=$parametres->pass_VAT ;
            $swiftTransport_V =  new \Swift_SmtpTransport( 'smtp.tunet.tn', '25', '');
            $swiftTransport_V->setUsername('hotels.vat@medicmultiservices.com');
            $swiftTransport_V->setPassword($pass_VAT);
            $fromname_V="VAT hôtels";
            $signatureentite_V= $parametres->signature2 ;

            $from_M='assistance@medicmultiservices.com';
            $pass_MEDIC =$parametres->pass_MEDIC ;
            $swiftTransport_M =  new \Swift_SmtpTransport( 'smtp.tunet.tn', '25', '');
            $swiftTransport_M->setUsername('assistance@medicmultiservices.com');
            $swiftTransport_M->setPassword($pass_MEDIC);
            $fromname_M="Medic' Multiservices";
            $signatureentite_M= $parametres->signature3 ;


            $from_TM='ambulance.transp@medicmultiservices.com';
            $pass_TM=$parametres->pass_TM ;
          // $swiftTransport =  new \Swift_SmtpTransport( 'mail.bmail.tn', '25');
            $swiftTransport_TM =  new \Swift_SmtpTransport( 'smtp.tunet.tn', '25','');
            $swiftTransport_TM->setUsername('ambulance.transp@medicmultiservices.com');
            $swiftTransport_TM->setPassword($pass_TM);
            $fromname_TM="Transport Medic";
            $signatureentite_TM= $parametres->signature4 ;

       
            $from_TV='vat.transp@medicmultiservices.com';
            $pass_TV=$parametres->pass_TV ;
            $swiftTransport_TV =  new \Swift_SmtpTransport( 'smtp.tunet.tn', '25', '');
            $swiftTransport_TV->setUsername('vat.transp@medicmultiservices.com');
            $swiftTransport_TV->setPassword($pass_TV);
            $fromname_TV="Transport VAT";
            $signatureentite_TV= $parametres->signature5 ;

       
            $from_MI='operations@medicinternational.tn';      
            $pass_MI=$parametres->pass_MI ;
            $swiftTransport_MI =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '465', 'ssl');
            $swiftTransport_MI->setUsername('operations@medicinternational.tn');
            $swiftTransport_MI->setPassword($pass_MI);
            $fromname_MI="Medic International";
            $signatureentite_MI= $parametres->signature6 ;
     
   $swiftTransport='';
   $from='';
   $fromname='';
   $signature='';
   $entete='';
        

       $format = "Y-m-d H:i:s";
     //   $dtc = (new \DateTime())->format('Y-m-d H:i:s');

        $dtc = (new \DateTime())->format($format);

        $dateSys = \DateTime::createFromFormat($format, $dtc);
       // dd('debut envoi');
	    $nbemail=0;
        foreach ($dossim as $dm ) {
        
        if( self::checkImmobile3Daysv2($dm->updatedmiss_at)==true )
         {
          //dd('verif');
           if($dm->client_adresse !=null )
           {

            if($dm->mail_auto_envoye !='Oui' )
             {
			if($nbemail <=10)
			{
			 $nbemail++; 
               $doss_ref=$dm->reference_doss;
              //oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo
               if (stripos($doss_ref, 'TM') !== FALSE) {
                   $swiftTransport=$swiftTransport_TM;
                   $from=$from_TM;
                   $fromname=$fromname_TM;
                   $signature=$signatureentite_TM;
                   $entete="Transport Medic";
                }
                else
                {
                    if (stripos($doss_ref, 'TN') !== FALSE) {
                      $swiftTransport=$swiftTransport_TN;
                      $from=$from_TN;
                      $fromname=$fromname_TN;
                      $signature=$signatureentite_TN;
                      $entete="Najda assistance Transport";
                    }
                    else
                    {
                        if (stripos($doss_ref, 'TV') !== FALSE) {
                           $swiftTransport=$swiftTransport_TV;
                           $from=$from_TV;
                           $fromname=$fromname_TV;
                           $signature=$signatureentite_TV;
                           $entete="Voyages Assistance Tunisie (transport)";
                        }

                        else
                        {

                        if (stripos($doss_ref, 'MI') !== FALSE) {
                            $swiftTransport=$swiftTransport_MI;
                           $from=$from_MI;
                           $fromname=$fromname_MI;
                           $signature=$signatureentite_MI;
                           $entete="Medic International";
                        }
                        else
                        { 
                            if (stripos($doss_ref, 'M') !== FALSE) {
                             $swiftTransport=$swiftTransport_M;
                             $from=$from_M;
                             $fromname=$fromname_M;
                             $signature=$signatureentite_M;
                             $entete="Medic Multiservices";
                            }
                            else
                            {
                              if (stripos($doss_ref, 'V') !== FALSE) {
                                $swiftTransport=$swiftTransport_V;
                                $from=$from_V;
                                $fromname=$fromname_V;
                                $signature=$signatureentite_V;
                                $entete="Voyages Assistance Tunisie (hébergement)";
                              }
                              else
                              {
                                if (stripos($doss_ref, 'XP') !== FALSE) {
                                       $swiftTransport=$swiftTransport_XP;
                                       $from=$from_XP;
                                       $fromname=$fromname_XP;
                                       $signature=$signatureentite_XP;
                                       $entete="X-Press Remorquage";
                                }
                                else
                                {
                                  if (stripos($doss_ref, 'TPA') !== FALSE) {
                                     $swiftTransport=$swiftTransport_tpa;
                                     $from=$from_tpa;
                                     $fromname=$fromname_tpa;
                                     $signature=$signatureentite_tpa;
                                     $entete="Najda Assistance TPA";
                                  }
                                  else
                                  {
                                   
                                       if (stripos($doss_ref, 'N') !== FALSE) {
                                        $swiftTransport=$swiftTransport_n;
                                         $from=$from_n;
                                       $fromname=$fromname_n;
                                       $signature=$signatureNajda;
                                      $entete="Najda Assistance";

                                    }
                                    else
                                    {
                                       $swiftTransport=$swiftTransport_n;
                                       $from=$from_n;
                                       $fromname=$fromname_n;
                                       $signature=$signatureNajda;
                                       $entete="Najda Assistance";
                                      // sécurité
                                    }
                                 }
                              }
                             }
                          }
                        }
                      }
                  }
                }
//=======================================================================================================
             

            if($dm->langue_client=='Fr')
            {
               /* $sujet = 'Clôture du dossier '.$dm->reference_doss;
                $contenu = "Bonjour de Najda,<br>
                Le dossier ".$dm->reference_doss." n'a vu aucune action ni instruction de votre part depuis 72 heures. Merci de nous indiquer si nous devons le clôturer, ou si vous avez de nouvelles instructions le concernant?<br>
                (Signé): Mail généré automatiquement";
            $contenu=$contenu.'<br><br>Cordialement <br> Najda Assistance<br><br><hr style="float:left;"><br><br>';*/

            $sujet = 'Dossier immobile '.$dm->nom_assure.' - '.$dm->ref_client.' - '.$dm->reference_doss;
            $contenu = "Bonjour de ".$entete.",<br><br>
                    Nous avons constaté qu’aucune action n’a été entreprise dans ce dossier depuis 3 jours, et aucune action n’y est programmée.<br><br> Merci de nous indiquer s’il y a lieu de le clôturer ou si nous devons le garder ouvert. Et dans ce dernier cas quelles sont vos instructions pour la suite ?<br><br>
                (Signé): Ceci est un email généré automatiquement par le système de gestion de Najda Assistance.";
            $contenu=$contenu.'<br><br>Cordialement <br><br>'.$signature.' <br><br><hr style="float:left;"><br><br>';

            }
            else
            {

             /*$sujet = 'Close the file '.$dm->reference_doss;
             $contenu = "Hello from Najda,<br>
              The file ".$dm->reference_doss." has seen no action or instruction from you for 72 hours. Please let us know if we need to close it, or if you have any new instructions concerning it?<br>
            (Signed): Mail generated automatically";
            $contenu=$contenu.'<br><br>Best regards <br> Najda Assistance <br><br><hr style="float:left;"><br><br>';*/
             $sujet = 'Motionless file '.$dm->nom_assure.' - '.$dm->ref_client.' - '.$dm->reference_doss;
             $contenu = "Hello from ".$entete.",<br><br>
              We noticed that no action has been taken on this file for 3 days, and there is no action scheduled for the upcoming days.<br><br>
              Please let us know whether we should close the file or keep it open. In that case what are your following instructions?<br><br>
            Best regards,
            <br><br>
            (Signed): This is an automatically generated email by the management system of Najda Assistance.";
            $contenu=$contenu.'<br><br>Best regards <br><br>'.$signature.' <br><br><hr style="float:left;"><br><br>';

            }
            //$to=$dm->client_adresse ;
            //$cc = 'nejib.karoui@gmail.com';

            // $to='kbskhaled@gmail.com' ;
            // $cc = 'kbskhaledfb@gmail.com';
            $cc=array();
            $bcc=array();
            $destinataires = null;
            $dests = null;
             if($dm->client_adresse)
             {

              $destinataires=$dm->client_adresse ;
              //$destinataires = str_replace(array( '(', ')' ), '', $destinataires);
              //$destinataires = str_replace(' ', '', $destinataires);
              $dests = explode(" ", $destinataires); 

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
                    // cc nejib karoui; 
                  // array_push($bcc,'nejib.karoui@medicmultiservices.com');
                  //array_push($bcc,'kbskhaled@gmail.com');
                  // array_push($bcc,'24ops@najda-assistance.com');
  
               
                 array_push($bcc,'nejib.karoui@medicmultiservices.com');
                 array_push($bcc,'kbskhaled@gmail.com');
                 array_push($bcc,'24ops@najda-assistance.com');
array_push($bcc,'finances@medicmultiservices.com');
             }
             else
             {
               $to=$dm->client_adresse ; // null;
                // cc nejib karoui; 
                   array_push($bcc,'nejib.karoui@medicmultiservices.com');
                   array_push($bcc,'kbskhaled@gmail.com');
                   array_push($bcc,'24ops@najda-assistance.com');
  array_push($bcc,'finances@medicmultiservices.com');
               //$cc=null; 
             }
          // dd($bcc);
		   // à désecommenter
         /*  $swiftMailer = new Swift_Mailer($swiftTransport);
                Mail::setSwiftMailer($swiftMailer);      
                Mail::send([], [], function ($message) use ($to, $sujet, $contenu, $cc,$bcc,$from,$fromname) {
               $message        
               ->to($to)
               ->cc($cc ?: [])
               ->bcc($bcc ?: [])
               ->subject($sujet)
               ->setBody($contenu, 'text/html')
               ->setFrom([$from => $fromname]);
               });


             $dm->update([

                'mail_auto_envoye'=> 'Oui',
                'date_envoi_mail' =>$dateSys ,
                'reponse_client'  =>null
             
             ]); */

            $emaiautodestcc = implode(";", $cc);

             // sauvgarder dans la table d'envoi mail auto 

             // à désecommenter
               /* $emaiauto=new EmailAuto ([
                      'dossierid'=>$dm->dossier_id,
                      'dossier' =>$dm->reference_doss,
                      'client' =>$dm->client_name,
                      'destinataire' =>$dm->client_adresse,
                      'emetteur'=>$from,
                      'cc'=>$emaiautodestcc,
                      'sujet'=>$sujet, 
                      'contenutxt' =>$contenu,
                      'type'=>'dossier_immobile'                    

                    ]);

              $emaiauto->save();*/


            }
			
			 }//fin if($nbemail <10)
				 
			 dd("envoi 10 emails auto dossier immobile");
         
		  } /// fin  if($dm->mail_auto_envoye !='Oui' )

         }
         else
         {
         // adresse null pas d'envoi 
           // à désecommenter
         /*
              $dm->update([
                'mail_auto_envoye'=> 'Non',
                'date_envoi_mail' =>$dateSys  ,
                'remarques' => 'dossier immobile plus que 4 jours mais le mail n\'est pas encore envoyé car l\'adresse mail destinataire est inexistante'             
             ]);
           */

         }


        }

		//} //fin if(nbemail <10)
            
        }// fin foreach ($dossim as $dm )


/*Bonjour de Najda,
Ce dossier n'a vu aucune action ni instruction de votre part depuis 72 heures. Merci nous indiquer si nous devons le clôturer, ou si vous avez de nouvelles instructions le concernant?
(Signé): Mail généré automatiquement
Et le reste de la signature de l'entité*/


/*Hello from Najda,
This file has seen no action or instruction from you for 72 hours. Please let us know if we need to close it, or if you have any new instructions regarding it?
(Signed): Mail generated automatically
And the rest of the entity signature*/


    dd('fin ft');
        
    }// fin fonction


   
}
