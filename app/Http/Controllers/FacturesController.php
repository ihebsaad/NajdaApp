<?php

namespace App\Http\Controllers;
use App\User;
use  DB;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Entree ;
use App\Dossier ;
use App\Prestataire ;
use App\Prestation ;
use App\TypePrestation;
use App\Facture;
use App\Adresse;
use App\Client;
use App\EmailAuto;
use App\Envoye;


 use Illuminate\Support\Facades\Auth;
 use Swift_Mailer;
 use Mail;
use App\Historique;


class FacturesController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        if( \Gate::allows('isAdmin')  || \Gate::allows('isFinancier')   )
        {
            $factures = Facture::orderBy('id', 'desc')->paginate(1000);
            return view('factures.index', compact('factures'));

        }
        else {
            // redirect
            return redirect('/')->with('success', 'droits insuffisants');

        }


    }

 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    
        return view('factures.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $factures = new Facture([
             'nom' =>trim( $request->get('nom'))

             // 'par'=> $request->get('par'),

        ]);

        $factures->save();
        return redirect('/factures')->with('success', ' ajouté avec succès');

    }


    public function saving(Request $request)
    {
        
           $userid = Auth::id() ;
          $user = User::find($userid);
         
        if( ($request->get('date_arrive') !=null || $request->get('reference') !=null)) {

            $facture = new Facture([
                'iddossier' => $request->get('dossier'),
                'date_arrive' => $request->get('date_arrive'),
                'reference' => $request->get('reference'),
                'par' => $userid,

            ]);
            if ($facture->save())
            { $id=$facture->id;
        $nomuser=$user->name.' '.$user->lastname;
 		
	  $desc='Ajout de facture '.$request->get('reference') ;		
	 $hist = new Historique([
              'description' => $desc,
            'user' => $nomuser,
             'user_id'=>auth::user()->id,
        ]);$hist->save();
		

                return url('/factures/view/'.$id)/*->with('success', 'Dossier Créé avec succès')*/;
            }

            else {
                return url('/factures');
            }
        }

    }

    public function updating(Request $request)
    {

        $id= $request->get('facture');
        $champ= strval($request->get('champ'));
       $val= $request->get('val');
      //  $dossier = Dossier::find($id);
       // $dossier->$champ =   $val;
        Facture::where('id', $id)->update(array($champ => $val));

      //  $dossier->save();

     ///   return redirect('/dossiers')->with('success', 'Entry has been added');

    }
    
     public function updatingCheck(Request $request)
    {

        $id= $request->get('facture');
        $champ= strval($request->get('champ'));
       $val= $request->get('val');
      //  $dossier = Dossier::find($id);
       // $dossier->$champ =   $val;
        Facture::where('id', $id)->update(array($champ => $val));

      //  $dossier->save();

     ///   return redirect('/dossiers')->with('success', 'Entry has been added');

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {

        if( \Gate::allows('isAdmin')  || \Gate::allows('isFinancier')  )
        {
            $clients = DB::table('clients')->select('id', 'name')->get();
            $facture = Facture::find($id);
            return view('factures.view' ,compact('facture'), ['clients'=>$clients] );

        }
        else {
            // redirect
            return redirect('/')->with('success', 'droits insuffisants');

        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $factures = Facture::find($id);

        
        return view('factures.edit', compact('factures'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $factures = Factures::find($id);

       // if( ($request->get('ref'))!=null) { $factures->name = $request->get('ref');}
       // if( ($request->get('type'))!=null) { $factures->email = $request->get('type');}
       // if( ($request->get('affecte'))!=null) { $factures->user_type = $request->get('affecte');}

        $factures->save();

        return redirect('/factures')->with('success', 'mise à jour avec succès');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $factures = Facture::find($id);
        $factures->delete();

        return redirect('/factures')->with('success', '  Supprimé ');
    }

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


    public static function envoi_mail($swiftTransport,$adresses, $sujet, $contenu,$from,$fromname)
    {
            
            $cc=array();
            $bcc=array();
            $destinataires = null;
            $dests = null;
             /*if($adresses)
             {
              $destinataires=$adresses ;
              $destinataires = str_replace(array( '(', ')' ), '', $destinataires);
              $destinataires = str_replace(' ', '', $destinataires);
              $dests = explode(";", $destinataires); 
             }*/
             if(is_array($adresses))
             {
                $dests=$adresses;
             }
             else
             {
                $dests=array($adresses);
             }
             
             //dd($dests);
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
                //nejib.karoui@gmail.com
                   array_push($bcc,'nejib.karoui@medicmultiservices.com');
                   array_push($bcc,'kbskhaled@gmail.com');
                   array_push($bcc,'24ops@najda-assistance.com');
                  //array_push($cc,'nbsnajoua@gmail.com');
  
               }
                else
               {
               $to=$dests[0]; // null;
                //nejib.karoui@gmail.com
                array_push($bcc,'nejib.karoui@medicmultiservices.com');
                   array_push($bcc,'kbskhaled@gmail.com');
                   array_push($bcc,'24ops@najda-assistance.com');
                  //array_push($cc,'nbsnajoua@gmail.com');
 
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
  
             }
                    

    }

 public static function envoi_mail_automatique_factures()    
    {
        $contenu='';
        $id_prest_test=array(10000,11000);
        $id_fact_test=array(22,23);
        $email_prestataires=array();
        $email_client=array();

        $dtc = (new \DateTime())->format('2020-09-01 00:00:00');

         
      $factures=Facture::where('honoraire',1)->whereNotNull('date_email')->where('created_at','>=', $dtc)->get();
     // dd($factures);
     // $prestations=Prestation::whereNotNull('date_prestation')->where('parvenu','<>',1)->get();
     $prestations=Prestation::where('parvenu','<>',1)->whereNotNull('date_prestation')->whereNotNull('created_at')->where('created_at','>=', $dtc)->get();
      //dd($prestations);
      $format = "Y-m-d H:i:s";
      $dtc30=(new \DateTime())->modify('-30 days')->format($format);
      $dtc45=(new \DateTime())->modify('-45 days')->format($format);
      $dtc60=(new \DateTime())->modify('-60 days')->format($format);
      $dateSys30 = \DateTime::createFromFormat($format, $dtc30);
      $dateSys45 = \DateTime::createFromFormat($format, $dtc45);
      $dateSys60 = \DateTime::createFromFormat($format, $dtc60);

      $parametres = DB::table('parametres')->where('id','=', 1 )->first();
      $swiftTransport2 =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '465', 'ssl');
      $swiftTransport2->setUsername('24ops@najda-assistance.com');
      $swiftTransport2->setPassword($parametres->pass_N);
      $fromname2="Najda Assistance (test email auto)";
      $from2='24ops@najda-assistance.com';
      //$signatureNajda=$parametres->signature10;

      // instancier swift for fiances

      $swiftTransport =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '587', '');
      $swiftTransport->setUsername('finances@najda-assistance.com');
      $swiftTransport->setPassword($parametres->pass_Finances);
      $fromname="Najda Finances (test email auto)";
      $from='finances@najda-assistance.com';
      $signatureFinances=$parametres->signature10;
     //dd($signatureFinances);
      //$x=1/0;

       /*$adr=Adresse::where('parent', 10000)->where('nature','emailinterv')->orderBy('id')->pluck('champ')->toArray();
       $ccea=self::return_dest_cc($adr);       
       $ccea = implode(";", $ccea);
       dd($ccea);
       $toea=self::return_dest_to($adr);
       //dd($toea);*/
   
   if($prestations && $prestations->count()>0 )
   {
      
      foreach ($prestations as $p ) {

              $dateCreation1=str_replace('/','-',$p->date_prestation);   
              //$dateCreation = \DateTime::createFromFormat($p->date_prestation);
              $dateCreation = new \DateTime($dateCreation1);
              $dss=Dossier::where('id',$p->dossier_id)->first();
               $doss_ref='';
              $nom_ass='';
              $prenom_ass='';
              if($dss)
              {
              $doss_ref=$dss->reference_medic;
              $nom_ass=$dss->subscriber_name;
              $prenom_ass=$dss->subscriber_lastname;
              }

               //dd($dateCreation);
              // si la date actuelle+30 jours ou la dte actuelle + 45j supérieur à date creation sans la date actuelle +60 jours dépasse la creation alors on envoie la email au prestataire
             if(($dateSys30>= $dateCreation ||  $dateSys45>= $dateCreation) &&  $dateSys60< $dateCreation)
              {
                
               //extraction d'adresse email
                if($p->prestataire_id)
                {
                 $adr=Adresse::where('parent', $p->prestataire_id)->where('nature','emailinterv')->pluck('champ')->toArray();

                

                 if($p->mail_30_env==0 || $p->mail_45_env==0)
                 {

                    if($p->mail_30_env==0 && $dateSys45 < $dateCreation  )
                    {
                        //dd("ok");
                     $contenu = "Bonjour,<br>
                     Pour le dossier : ".$doss_ref." (dont l'assuré est".$prenom_ass." ".$nom_ass." et la date de prestation est ".$dateCreation1."), votre facture n'est pas encore reçue depuis une période qui dépasse 30 jours<br>
                     (Signé): Mail généré automatiquement";

                    }
                    else
                    {
                    
                      if($p->mail_45_env==0 && $dateSys45 >= $dateCreation)
                        {
                          $contenu = "Bonjour,<br>
                         Pour le dossier : ".$doss_ref." (dont l'assuré est ".$prenom_ass." ".$nom_ass." et la date de prestation est ".$dateCreation1."), votre facture n'est pas encore reçue depuis une période qui dépasse 45 jours<br>
                        (Signé): Mail généré automatiquement";
                       }

                    }
                

                   $sujet = 'Facture Prestataire';
                 
                  $contenu=$contenu.'<br><br>Cordialement <br> '.$signatureFinances.'<br><br><hr style="float:left;"><br><br>';

                  if($adr && count($adr)>0)
                   {
                    //dd('ok envoi au prestataire ');
                    //
                     //dd('ok envoi au prestataire ');
                     if($dateSys30>= $dateCreation && $dateSys45 < $dateCreation && $p->mail_30_env==0 )
                            {
                              $email_prestataires[]=$adr[0];
                              self::envoi_mail($swiftTransport,$adr,$sujet,$contenu,$from, $fromname);
                              Prestation::where('id', $p->id)->update(['mail_30_env'=>1]);
                              $ccea=self::return_dest_cc($adr);
                              $ccea = implode(";", $ccea);
                              $toea=self::return_dest_to($adr);
                              $presname='';
                              $prenom='';
                              $presname=Prestataire::where('id',$p->prestataire_id)->first();
                              if($presname)
                              {
                              $presname=Prestataire::where('id',$p->prestataire_id)->first()->name;
                              $prenom=Prestataire::where('id',$p->prestataire_id)->first()->prenom;
                                if($prenom)
                                {
                                  $presname=$prenom.' '.$presname;
                                }
                              }

                              $emaiauto=new EmailAuto ([ 
                             'dossierid'=>$p->dossier_id,
                             'dossier' =>$doss_ref,
                             'prestataire' =>$presname,
                             'destinataire' =>$toea,
                             'emetteur'=>$from,
                             'cc'=>$ccea,
                             'sujet'=>$sujet, 
                             'contenutxt' =>$contenu,
                             'type'=>'facture_prestataire'                    

                             ]);

                            $emaiauto->save();


                            }
                            else
                            {
                                 if($dateSys45 >= $dateCreation && $p->mail_45_env==0)
                                 {
                                   $email_prestataires[]=$adr[0];
                               self::envoi_mail($swiftTransport,$adr,$sujet,$contenu,$from, $fromname);
                                Prestation::where('id', $p->id)->update(['mail_45_env'=>1]);
                                    $ccea=self::return_dest_cc($adr);
                                      $ccea = implode(";", $ccea);
                                      $toea=self::return_dest_to($adr);
                                      $presname='';
                                      if($presname=Prestataire::where('id',$p->prestataire_id)->first())
                                      {
                                      $presname=Prestataire::where('id',$p->prestataire_id)->first()->name;
                                      }

                                      $emaiauto=new EmailAuto ([ 
                                     'dossierid'=>$p->dossier_id,
                                     'dossier' =>$doss_ref,
                                     'prestataire' =>$presname,
                                     'destinataire' =>$toea,
                                     'emetteur'=>$from,
                                     'cc'=>$ccea,
                                     'sujet'=>$sujet, 
                                     'contenutxt' =>$contenu,
                                     'type'=>'facture_prestataire'                    

                                     ]);

                                    $emaiauto->save();

                                 }

                            }
                           

                   }                    


                 }

                }
                 

            }
              else
              {
                if($dateSys60>= $dateCreation) // alerte le financier
                {

                    $sujet = 'Facture Prestataire';
                    $adr='finances@najda-assistance.com';
                   // $adr='kbskhaled@gmail.com';

                      $presname='';
                      $prenom='';
                      $presname=Prestataire::where('id',$p->prestataire_id)->first();
                      if($presname)
                      {
                      $presname=Prestataire::where('id',$p->prestataire_id)->first()->name;
                      $prenom=Prestataire::where('id',$p->prestataire_id)->first()->prenom;
                        if($prenom)
                        {
                          $presname=$prenom.' '.$presname;
                        }
                      }

                    //dd("envoi alerte au financier cas prestataire");
                    $contenu = "Alerte pour le financier<br>
                    la facture du prestataire ". $presname." pour la prestation concernant le dossier ".$doss_ref." (dont l'assuré ".$prenom_ass." ".$nom_ass." et la date de prestation est ".$dateCreation1."), n'est pas encore reçue depuis une période qui dépasse 60 jours<br>
                    (Signé): Mail généré automatiquement";
                    
                    $email_prestataires[]=$adr;
                    self::envoi_mail($swiftTransport2,$adr,$sujet,$contenu,$from2, $fromname2);
                    Prestation::where('id', $p->id)->update(['mail_60_env'=>1]);
                    //adresse financier
                    //$adr= fianancier
                              $ccea=self::return_dest_cc($adr);
                              $ccea = implode(";", $ccea);
                              $toea=self::return_dest_to($adr);
                             

                              $emaiauto=new EmailAuto ([ 
                             'dossierid'=>$p->dossier_id,
                             'dossier' =>$doss_ref,
                             'prestataire' =>$presname,
                             'destinataire' =>$toea,
                             'emetteur'=>$from,
                             'cc'=>$ccea,
                             'sujet'=>$sujet, 
                             'contenutxt' =>$contenu,
                             'type'=>'Alerte_finanier_facture_prestataire'                    

                             ]);

                            $emaiauto->save();


                }

              }


        }
    }
   //dd('fin envoi mail au prestataire');
    //$testadr=array(4364,4371);
    if($factures && $factures->count()>0 )
        {

      
      foreach ($factures as $f ) 
      {
        if($f->honoraire == 1) // cas client
        {
                
            if($f->regle==0)// facture non réglée
            {
               // dd('ok3');
                //$format = "Y/m/d"; 
                $dateEmail=str_replace('/','-',$f->date_email) ;
                 $dateEnvoi= new \DateTime($dateEmail);
                 $dossier=Dossier::where('id',$f->iddossier)->first(); 
                 $cli=Client::where('id', $dossier->customer_id)->first();
                  $doss_ref='';
                  $nom_ass='';
                  $prenom_ass='';
              if($dossier)
              {
              $doss_ref=$dossier->reference_medic;
              $nom_ass=$dossier->subscriber_name;
              $prenom_ass=$dossier->subscriber_lastname;
              }


               // dd( date_format(strtotime($f->date_email),"Y/m/d H:i:s"));          
             // $dateEnvoi = (\date::createFromFormat($format, $f->date_email) );
           //  dd($dateEnvoi);
              if($dateSys30>= $dateEnvoi  &&  $dateSys45< $dateEnvoi)
              {
               
                //extraction d'adresse email
                if($f->iddossier)
                {
                 
                 if($f->mail_30_env==0 && $dateSys45< $dateEnvoi)
                 {
                      //dd('ok7');

                     /*$contenu = "Bonjour de Najda,<br>
                     Votre facture n'est pas encore reçue depuis une période qui dépasse 30 jours<br>
                     (Signé): Mail généré automatiquement";*/

                   
              
                 //dd( $dossier->id) ;
                //$adr=Adresse::where('parent', $dossier->customer_id)->where('nature','gestion')->pluck('mail')->toArray();
                //$cli=Client::where('id', $dossier->customer_id)->first();
                
                $adr=Adresse::where('parent',$dossier->customer_id)->where('nature','gestion')->orderBy('id')->pluck('mail')->toArray();
                //dd($adr);
                $clilang=null;
                 if($adr && count($adr)>0)
                   {
                     $sujet = '';
                     $contenu ='';
                    if($cli->langue1=="francais" || stristr($cli->langue1, "fran"))
                        {
                            $clilang="Fr";

                        }
                        else
                        {

                            $clilang="Ang";

                        }
                    if($clilang=="Fr")
                    {
                    $sujet = 'Facture client';
                     $contenu = "Bonjour ,<br>
                    Pour le dossier ".$dossier->reference_medic."(dont l'assuré : ".$prenom_ass." ".$nom_ass.", réfernce ".$f->reference." et la date ".$dateEmail."), votre facture n'est pas encore réglée<br>
                     (Signé): Mail généré automatiquement";
                    $contenu=$contenu.'<br><br>Cordialement <br>'.$signatureFinances.'<br><br><hr style="float:left;"><br><br>';
                    $email_client[]=$adr[0];
                   self::envoi_mail($swiftTransport,$adr,$sujet,$contenu,$from, $fromname);
                    Facture::where('id', $f->id)->update(['mail_30_env'=>1]);
                    //dd('ok envoi au gestionnaire client');
                    }
                    else
                    {
                      $sujet = 'Customer invoice';
                     $contenu = "Hello , <br>
                     For the File ".$dossier->reference_medic." (including the insured: ". $prenom_ass." ".$nom_ass.", refers ". $f->reference." and the date ". $dateEmail."), your bill has not been paid yet<br>
                      (Signed): Mail generated automatically ";
                    $contenu=$contenu.'<br><br>cordially <br>'.$signatureFinances.'<br><br><hr style="float:left;"><br><br>';
                    $email_client[]=$adr[0];
                  self::envoi_mail($swiftTransport,$adr,$sujet,$contenu,$from, $fromname);
                    Facture::where('id', $f->id)->update(['mail_30_env'=>1]);
                   //dd('ok send to the manager of customer');



                    }

                            $ccea=self::return_dest_cc($adr);
                              $ccea = implode(";", $ccea);
                              $toea=self::return_dest_to($adr);
                             

                              $emaiauto=new EmailAuto ([ 
                             'dossierid'=>$dossier->id,
                             'dossier' =>$dossier->reference_medic,
                             'client' =>$cli->name,
                             'destinataire' =>$toea,
                             'emetteur'=>$from,
                             'cc'=>$ccea,
                             'sujet'=>$sujet, 
                             'contenutxt' =>$contenu,
                             'type'=>'facture_client'                    

                             ]);

                            $emaiauto->save();
                   }

                     
                 }

                }
              }
              else
              {
                if($dateSys45>= $dateEnvoi && $f->mail_45_env==0 ) // alerte le financier
                {
                      //dd('ok6');
                    //adresse financier
                    //$adr= fianancier
                     $sujet = 'Facture Client';
                    $adr='finances@najda-assistance.com';

                     $contenu = "Alerte pour le financier<br>
                    la facture du client ".$cli->name." pour le dossier ".$dossier->reference_medic." n'est pas encore réglée  depuis une période qui dépasse 45 jours<br>
                    (Signé): Mail généré automatiquement";

                 // dd("envoi alerte au financier cas client");
                        $email_client[]=$adr;
                        self::envoi_mail($swiftTransport2,$adr,$sujet,$contenu,$from2, $fromname2);
                         Facture::where('id', $f->id)->update(['mail_45_env'=>1]);

                         $ccea=self::return_dest_cc($adr);
                              $ccea = implode(";", $ccea);
                              $toea=self::return_dest_to($adr);
                             

                              $emaiauto=new EmailAuto ([ 
                             'dossierid'=>$dossier->id,
                             'dossier' =>$dossier->reference_medic,
                             'client' =>$cli->name,
                             'destinataire' =>$toea,
                             'emetteur'=>$from,
                             'cc'=>$ccea,
                             'sujet'=>$sujet, 
                             'contenutxt' =>$contenu,
                             'type'=>'Alerte_financier_facture_client'                    

                             ]);

                            $emaiauto->save();
                      


                }

              }// fin else

                
            }// fin if reg

        } //fin if honoraire

      } // fin foreach ($factures as $f )

   } // fin if  $factures
      



    }//fin fonction


    // version 2 envoi_mail_automatique_factures version 2

    public static function envoi_mail_automatique_factures_version2()    
    {
        $contenu='';
        $id_prest_test=array(10000,11000);
        $id_fact_test=array(22,23);
        $email_prestataires=array();
        $email_client=array();

      $dtc = (new \DateTime())->format('2020-09-01 00:00:00');
      $factures=Facture::where('id',22)->orWhere('id',23)->get();
      //$factures=Facture::where('honoraire',1)->whereNotNull('date_email')->whereIn('id',$id_fact_test)->where('created_at','>=', $dtc)->get();
      //dd($factures);
     // $prestations=Prestation::whereNotNull('date_prestation')->where('parvenu','<>',1)->get();
     $prestations=Prestation::where('parvenu','<>',1)->whereNotNull('date_prestation')->whereNotNull('created_at')->where('created_at','>=', $dtc)->whereIn('prestataire_id',$id_prest_test)->get();
      //dd($prestations);
      $format = "Y-m-d H:i:s";
      $dtc30=(new \DateTime())->modify('-30 days')->format($format);
      $dtc45=(new \DateTime())->modify('-45 days')->format($format);
      $dtc60=(new \DateTime())->modify('-60 days')->format($format);
      $dtc75=(new \DateTime())->modify('-75 days')->format($format);


      $dtc40=(new \DateTime())->modify('-40 days')->format($format);
      $dtc55=(new \DateTime())->modify('-55 days')->format($format);
      $dtc70=(new \DateTime())->modify('-70 days')->format($format);
      $dtc85=(new \DateTime())->modify('-85 days')->format($format);

      $dateSys30 = \DateTime::createFromFormat($format, $dtc30);
      $dateSys45 = \DateTime::createFromFormat($format, $dtc45);
      $dateSys60 = \DateTime::createFromFormat($format, $dtc60);
      $dateSys75 = \DateTime::createFromFormat($format, $dtc75);

      $dateSys40 = \DateTime::createFromFormat($format, $dtc40);
      $dateSys55 = \DateTime::createFromFormat($format, $dtc55);
      $dateSys70 = \DateTime::createFromFormat($format, $dtc70);
      $dateSys85 = \DateTime::createFromFormat($format, $dtc85);


      $parametres = DB::table('parametres')->where('id','=', 1 )->first();
      $swiftTransport2 =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '465', 'ssl');
      $swiftTransport2->setUsername('24ops@najda-assistance.com');
      $swiftTransport2->setPassword($parametres->pass_N);
      $fromname2="Najda Assistance (test email auto)";
      $from2='24ops@najda-assistance.com';
      //$signatureNajda=$parametres->signature10;

      // instancier swift for fiances

      $swiftTransport =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '587', '');
      $swiftTransport->setUsername('finances@najda-assistance.com');
      $swiftTransport->setPassword($parametres->pass_Finances);
      $fromname="Najda Finances (test email auto)";
      $from='finances@najda-assistance.com';
      $signatureFinances=$parametres->signature10;
     //dd($signatureFinances);
      //$x=1/0;

       /*$adr=Adresse::where('parent', 10000)->where('nature','emailinterv')->orderBy('id')->pluck('champ')->toArray();
       $ccea=self::return_dest_cc($adr);       
       $ccea = implode(";", $ccea);
       dd($ccea);
       $toea=self::return_dest_to($adr);
       //dd($toea);*/
   
   if($prestations && $prestations->count()>0 )
   {
      
      foreach ($prestations as $p ) {

              $dateCreation1=str_replace('/','-',$p->date_prestation);   
              //$dateCreation = \DateTime::createFromFormat($p->date_prestation);
              $dateCreation = new \DateTime($dateCreation1);
              $dss=Dossier::where('id',$p->dossier_id)->first();
               $doss_ref='';
              $nom_ass='';
              $prenom_ass='';
              if($dss)
              {
              $doss_ref=$dss->reference_medic;
              $nom_ass=$dss->subscriber_name;
              $prenom_ass=$dss->subscriber_lastname;
              }
//=======================================================================================================

              if(($dateSys30>= $dateCreation ||  $dateSys45>= $dateCreation) &&  $dateSys60< $dateCreation)
              {
                
               //extraction d'adresse email
                if($p->prestataire_id)
                {
                 $adr=Adresse::where('parent', $p->prestataire_id)->where('nature','emailinterv')->pluck('champ')->toArray();
                 $typeprestation='';
                 if($p->type_prestations_id && $p->type_prestations_id != 0)
                 {
                   $typeprestation=TypePrestation::where('id',$p->type_prestations_id)->first()->name;
                 }

                 if($p->mail_30_env==0 || $p->mail_45_env==0)
                 {

                    if($p->mail_30_env==0 && $dateSys45 < $dateCreation  )
                    {
                        //dd("ok");
                     /*$contenu = "Bonjour,<br>
                     Pour le dossier : ".$doss_ref." (dont l'assuré est".$prenom_ass." ".$nom_ass." et la date de prestation est ".$dateCreation1."), votre facture n'est pas encore reçue depuis une période qui dépasse 30 jours<br>
                     (Signé): Mail généré automatiquement";*/

                     $contenu = "Bonjour du service financier de Najda Assistance,<br><br>
                     Vous avez réalisé pour Najda Assistance en date du ".$dateCreation1." le service suivant :<br><br>
                      Le service ".$typeprestation."<br><br>
                      Or à ce jour nous n’avons toujours pas reçu votre facture pour ce service rendu il y’a maintenant une trentaine de jours. Si vous ne l’avez pas encore envoyée, nous vous prions de bien vouloir le faire dans les meilleurs délais en précisant notre référence de dossier référence ".$doss_ref.". Si toutefois vous l’avez déjà envoyée et que nous ne l’avons pas encore reçue, veuillez ne pas tenir compte de ce mail.<br><br>
                        Avec nos remerciements pour votre collaboration.<br><br>
                        (Signé): Ceci est un email généré automatiquement par le système de gestion de Najda Assistance (Rappel 1)";  
                     
                    }
                    else
                    {
                    
                      if($p->mail_45_env==0 && $dateSys45 >= $dateCreation)
                        {
                          /*$contenu = "Bonjour,<br>
                         Pour le dossier : ".$doss_ref." (dont l'assuré est ".$prenom_ass." ".$nom_ass." et la date de prestation est ".$dateCreation1."), votre facture n'est pas encore reçue depuis une période qui dépasse 45 jours<br>
                        (Signé): Mail généré automatiquement";*/
                        $contenu = "Bonjour du service financier de Najda Assistance,<br><br>
                          Ceci est le deuxième rappel pour le service rendu à Najda Assistance en date du  ".$dateCreation1." et qui consiste en:<br><br>
                           Le service: ".$typeprestation."  dans le cadre de notre dossier de référence ".$doss_ref.".<br><br>
                             Or à ce jour nous n’avons toujours pas reçu votre facture pour ce service rendu il y’a maintenant 45 jours. Si vous ne l’avez pas encore envoyée, nous vous prions de bien vouloir le faire dans les meilleurs délais en précisant notre référence. Passé 60 jours après le service rendu, nous ne pourrons plus garantir son règlement.<br><br>
                         Si toutefois vous l’avez déjà envoyée et que nous ne l’avons pas encore reçue, veuillez ne pas tenir compte de ce mail.
                          Avec nos remerciements pour votre collaboration<br><br>
                          (Signé): Ceci est un email généré automatiquement par le système de gestion de Najda Assistance (Rappel 2)";

                       }

                    }
                
                  $sujet = 'Facture Prestataire';
                 
                  $contenu=$contenu.'<br><br>Cordialement <br><br> '.$signatureFinances.'<br><br><hr style="float:left;"><br><br>';

                  if($adr && count($adr)>0)
                   {
                    //dd('ok envoi au prestataire ');
                    //
                     //dd('ok envoi au prestataire ');
                     if($dateSys30>= $dateCreation && $dateSys45 < $dateCreation && $p->mail_30_env==0 )
                            {
                              $email_prestataires[]=$adr[0];
                              self::envoi_mail($swiftTransport,$adr,$sujet,$contenu,$from, $fromname);
                              Prestation::where('id', $p->id)->update(['mail_30_env'=>1]);
                              $ccea=self::return_dest_cc($adr);
                              $ccea = implode(";", $ccea);
                              $toea=self::return_dest_to($adr);
                              $presname='';
                              $prenom='';
                              $presname=Prestataire::where('id',$p->prestataire_id)->first();
                              if($presname)
                              {
                              $presname=Prestataire::where('id',$p->prestataire_id)->first()->name;
                              $prenom=Prestataire::where('id',$p->prestataire_id)->first()->prenom;
                                if($prenom)
                                {
                                  $presname=$prenom.' '.$presname;
                                }
                              }

                              $emaiauto=new EmailAuto ([ 
                             'dossierid'=>$p->dossier_id,
                             'dossier' =>$doss_ref,
                             'prestataire' =>$presname,
                             'destinataire' =>$toea,
                             'emetteur'=>$from,
                             'cc'=>$ccea,
                             'sujet'=>$sujet, 
                             'contenutxt' =>$contenu,
                             'type'=>'facture_prestataire'                    

                             ]);

                            $emaiauto->save();


                            }
                            else
                            {
                                 if($dateSys45 >= $dateCreation && $p->mail_45_env==0)
                                 {
                                   $email_prestataires[]=$adr[0];
                               self::envoi_mail($swiftTransport,$adr,$sujet,$contenu,$from, $fromname);
                                Prestation::where('id', $p->id)->update(['mail_45_env'=>1]);
                                    $ccea=self::return_dest_cc($adr);
                                      $ccea = implode(";", $ccea);
                                      $toea=self::return_dest_to($adr);
                                      $presname='';
                                      if($presname=Prestataire::where('id',$p->prestataire_id)->first())
                                      {
                                      $presname=Prestataire::where('id',$p->prestataire_id)->first()->name;
                                      }

                                      $emaiauto=new EmailAuto ([ 
                                     'dossierid'=>$p->dossier_id,
                                     'dossier' =>$doss_ref,
                                     'prestataire' =>$presname,
                                     'destinataire' =>$toea,
                                     'emetteur'=>$from,
                                     'cc'=>$ccea,
                                     'sujet'=>$sujet, 
                                     'contenutxt' =>$contenu,
                                     'type'=>'facture_prestataire'                    

                                     ]);

                                    $emaiauto->save();

                                 }

                            }
                           

                   }
                   else// pour les sms automatiques
                   {

                     if($p->mail_30_env==0 && $dateSys45 < $dateCreation  )
                      {


                      }
                    else
                     {
                      if($dateSys45 >= $dateCreation && $p->mail_45_env==0)
                        {


                        }
                      }

                   }                    


                 }

                }
                 

            }
              else
              {
                if($dateSys60>= $dateCreation) // alerte le financier
                {

                    $sujet = 'Facture Prestataire - Alerte pour le financier';
                   // $adr='finances@najda-assistance.com';
                    $adr='kbskhaled@gmail.com';
                    //dd($adr);
                      $presname='';
                      $prenom='';
                      $presname=Prestataire::where('id',$p->prestataire_id)->first();
                      if($presname)
                      {
                      $presname=Prestataire::where('id',$p->prestataire_id)->first()->name;
                      $prenom=Prestataire::where('id',$p->prestataire_id)->first()->prenom;
                        if($prenom)
                        {
                          $presname=$prenom.' '.$presname;
                        }
                      }

                    //dd("envoi alerte au financier cas prestataire");
                      if($dateSys60 >= $dateCreation && $dateCreation > $dateSys75 )
                      {
                        if($p->mail_60_env==0)
                          {
                            $contenu = "Alerte pour le financier<br>
                    la facture du prestataire ". $presname." pour la prestation concernant le dossier ".$doss_ref." (dont l'assuré ".$prenom_ass." ".$nom_ass." et la date de prestation est ".$dateCreation1."), n'est pas encore reçue depuis une période qui dépasse 60 jours<br>
                    (Signé): Mail généré automatiquement";
                      
                  //$dateCreation = \DateTime::createFromFormat($p->date_prestation);
                                     
               // $date_rapp2= \DateTime::createFromFormat($format, $date_rapp);
     // $dtc30=(new \DateTime())->modify('-30 days')->format($format);

                  $email_prestataires[]=$adr;
                    self::envoi_mail($swiftTransport2,$adr,$sujet,$contenu,$from2, $fromname2);
                    Prestation::where('id', $p->id)->update(['mail_60_env'=>1]);
                    //adresse financier
                    //$adr= fianancier
                              $ccea=self::return_dest_cc($adr);
                              $ccea = implode(";", $ccea);
                              $toea=self::return_dest_to($adr);
                             

                              $emaiauto=new EmailAuto ([ 
                             'dossierid'=>$p->dossier_id,
                             'dossier' =>$doss_ref,
                             'prestataire' =>$presname,
                             'destinataire' =>$toea,
                             'emetteur'=>$from,
                             'cc'=>$ccea,
                             'sujet'=>$sujet, 
                             'contenutxt' =>$contenu,
                             'type'=>'Alerte_finanier_facture_prestataire'                    

                             ]);

                            $emaiauto->save();
                      $date_rapp = (new \DateTime($dateCreation1))->modify('+60 days');
                      Prestation::where('id', $p->id)->update(['date_rapp_15'=>$date_rapp]);
                       }
                     }
                     else
                     {
                      if($dateSys75 > $dateCreation )
                      {

                        if($p->date_rapp_15)
                        {

                            $date_rapp = new \DateTime($p->date_rapp_15);
                            //dd($date_rapp);
                            //$date_rapp2= \DateTime::createFromFormat($format, $date_rapp);

                            $dtc=(new \DateTime())->format($format);
                            $dateSys = \DateTime::createFromFormat($format, $dtc);
                            //dd($dateSys->diff($date_rapp)->d);

                            if( $dateSys >  $date_rapp && intval($dateSys->diff($date_rapp)->d)>15)
                            {

                               $contenu = "Alerte pour le financier- Rappel periodique de 15 jours <br>
                              la facture du prestataire ". $presname." pour la prestation concernant le dossier ".$doss_ref." (dont l'assuré ".$prenom_ass." ".$nom_ass." et la date de prestation est ".$dateCreation1."), n'est pas encore reçue depuis une période qui dépasse 60 jours<br>
                              (Signé): Mail généré automatiquement";

                               $email_prestataires[]=$adr;
                                self::envoi_mail($swiftTransport2,$adr,$sujet,$contenu,$from2, $fromname2);
                                 Prestation::where('id', $p->id)->update(['mail_60_env'=>1]);
                               //adresse financier
                              //$adr= fianancier
                              $ccea=self::return_dest_cc($adr);
                              $ccea = implode(";", $ccea);
                              $toea=self::return_dest_to($adr);
                             

                              $emaiauto=new EmailAuto ([ 
                             'dossierid'=>$p->dossier_id,
                             'dossier' =>$doss_ref,
                             'prestataire' =>$presname,
                             'destinataire' =>$toea,
                             'emetteur'=>$from,
                             'cc'=>$ccea,
                             'sujet'=>$sujet, 
                             'contenutxt' =>$contenu,
                             'type'=>'Alerte_finanier_facture_prestataire'                    

                             ]);

                            $emaiauto->save();

                            $date_rapp->modify('+15 days');
                              Prestation::where('id', $p->id)->update(['date_rapp_15'=>$date_rapp]);

                            }


                        }



                      }

                     }
                                     

                }

              }

//=======================================================================================================
         
        }// end foreach prestation
    }// end if prestation

  //dd('fin envoi mail au prestataire');
    //$testadr=array(4364,4371);
    if($factures && $factures->count()>0 )
        {
      //dd($factures);
      foreach ($factures as $f ) 
      {
       
        if($f->honoraire == 1) // cas client
        {
                
            if($f->regle==0)// facture non réglée
            {
               
                //$format = "Y/m/d"; 
                if($f->date_email)
                {
                  $dateEmail=str_replace('/','-',$f->date_email);
                }
                else
                {
                  if($f->date_poste)
                  {
                     $dateEmail=str_replace('/','-',$f->date_poste);
                  }

                }
                 $dateEnvoi= new \DateTime($dateEmail);
                 $dossier=Dossier::where('id',$f->iddossier)->first(); 
                 $cli=Client::where('id', $dossier->customer_id)->first();
                  $doss_ref='';
                  $nom_ass='';
                  $prenom_ass='';
              if($dossier)
              {
              $doss_ref=$dossier->reference_medic;
              $doss_ref_cus=$dossier->reference_customer;
              $nom_ass=$dossier->subscriber_name;
              $prenom_ass=$dossier->subscriber_lastname;
              }


               // dd( date_format(strtotime($f->date_email),"Y/m/d H:i:s"));          
             // $dateEnvoi = (\date::createFromFormat($format, $f->date_email) );
           //  dd($dateEnvoi);
              //nature telinterv
////////----------------------------------------------------------------------------------------------
// 30->40 ; 45->55 ; 60->70; 
if(($dateSys40>= $dateEnvoi ||  $dateSys55>=  $dateEnvoi) &&  $dateSys70<  $dateEnvoi)
              {
                //dd('kk');
               //extraction d'adresse email
                if($f->iddossier)
                {
                 $adr=Adresse::where('parent',$dossier->customer_id)->where('nature','gestion')->orderBy('id')->pluck('mail')->toArray();
                 $clilang=null;
                
                     $sujet = '';
                     $contenu ='';
                    if($cli->langue1=="francais" || stristr($cli->langue1, "fran"))
                        {
                            $clilang="Fr";

                        }
                        else
                        {

                            $clilang="Ang";

                        }
                

                 if($f->mail_40_env==0 || $f->mail_55_env==0)
                 {

                    if($f->mail_40_env==0 && $dateSys55 < $dateEnvoi  )
                    {
                       if($adr && count($adr)>0)
                       {
                          if($clilang=="Fr")
                            {
                            $sujet = 'Facture client';
                             /*$contenu = "Bonjour ,<br>
                            Pour le dossier ".$dossier->reference_medic."(dont l'assuré : ".$prenom_ass." ".$nom_ass.", réfernce ".$f->reference." et la date ".$dateEmail."), votre facture n'est pas encore réglée<br>
                             (Signé): Mail généré automatiquement";
                            $contenu=$contenu.'<br><br>Cordialement <br>'.$signatureFinances.'<br><br><hr style="float:left;"><br><br>';*/

                            $contenu = "Bonjour du service gestion de Najda Assistance,<br><br>
                           
Nous vous avons adressé en date du ". $dateEmail." dans le cadre du dossier ".$doss_ref_cus." (Notre ref:".$doss_ref.") notre facture numéro ".$f->reference." du montant ".$f->montant." ".$f->devise."<br><br>
Or à ce jour nous n’avons toujours pas reçu votre règlement pour cette facture envoyée voilà maintenant une quarantaine de jours. Si vous ne l’avez pas encore effectué, nous vous saurions gré de bien vouloir procéder dans les meilleurs délais et nous en informer. Si toutefois vous l’avez déjà effectué entre-temps et que nous ne l’avons pas encore reçu, veuillez SVP nous en adresser les détails et ne pas tenir compte de ce mail.<br><br>
Avec nos remerciements pour votre collaboration.<br><br>
 (Signé): Ceci est un email généré automatiquement par le système de gestion de Najda Assistance";
                            $contenu=$contenu.'<br><br>Cordialement <br>'.$signatureFinances.'<br><br><hr style="float:left;"><br><br>';

                            $email_client[]=$adr[0];
                           self::envoi_mail($swiftTransport,$adr,$sujet,$contenu,$from, $fromname);
                            Facture::where('id', $f->id)->update(['mail_40_env'=>1]);
                            //dd('ok envoi au gestionnaire client');
                            }
                            else
                            {
                              $sujet = 'Customer invoice';
                             /* $contenu = "Hello , <br>
                               For the File ".$dossier->reference_medic." (including the insured: ". $prenom_ass." ".$nom_ass.", refers ". $f->reference." and the date ". $dateEmail."), your bill has not been paid yet<br>
                              (Signed): Mail generated automatically ";
                               $contenu=$contenu.'<br><br>cordially <br>'.$signatureFinances.'<br><br><hr style="float:left;"><br><br>';*/

                               $contenu = "Hello from the accountability department of Najda Assistance,<br><br>
We sent you on the date of". $dateEmail."  as part of the file under your reference ".$doss_ref_cus." (Our ref:".$doss_ref.") our invoice number ".$f->reference." with the amount of ".$f->montant." ".$f->devise."<br><br>.
However, until today, we did not receive any payment for this invoice that was sent forty days ago. If u did not proceed for the payment yet, we would be grateful if you could do it as soon as possible and let us know. In case that you have already done the payment in the meantime and we did not receive it yet, please send us the details and do not take note of this email.<br><br>
Many thanks for your collaboration<br><br>
                              (Signed): This is an automatically generated email by the management system of Najda Assistance. ";
                               $contenu=$contenu.'<br><br>cordially <br>'.$signatureFinances.'<br><br><hr style="float:left;"><br><br>';


                                 $email_client[]=$adr[0];
                                 self::envoi_mail($swiftTransport,$adr,$sujet,$contenu,$from, $fromname);
                                  Facture::where('id', $f->id)->update(['mail_40_env'=>1]);
            
                                }

                                      $ccea=self::return_dest_cc($adr);
                                      $ccea = implode(";", $ccea);
                                      $toea=self::return_dest_to($adr);
                                   
                                      $emaiauto=new EmailAuto ([ 
                                     'dossierid'=>$dossier->id,
                                     'dossier' =>$dossier->reference_medic,
                                     'client' =>$cli->name,
                                     'destinataire' =>$toea,
                                     'emetteur'=>$from,
                                     'cc'=>$ccea,
                                     'sujet'=>$sujet, 
                                     'contenutxt' =>$contenu,
                                     'type'=>'facture_client'                    

                                     ]);

                                    $emaiauto->save();

                       }
                       else // sinon si mail gestion n'existe pas 
                       {



                       } // fin else sinon mail
                       
                    }
                    else
                    {
                      
                      if($f->mail_55_env==0 && $dateSys55 >=  $dateEnvoi)
                        {
                         // dd("55");
                           if($adr && count($adr)>0)
                             {
                                if($clilang=="Fr")
                                  {

                                    //dd('fr');
                                  $sujet = 'Facture client';
                                   /*$contenu = "Bonjour ,<br>
                                  Pour le dossier ".$dossier->reference_medic."(dont l'assuré : ".$prenom_ass." ".$nom_ass.", réfernce ".$f->reference." et la date ".$dateEmail."), votre facture n'est pas encore réglée<br>
                                   (Signé): Mail généré automatiquement";*/

                                   $contenu = "Bonjour du service gestion de Najda Assistance,<br><br>
Ceci est un deuxième rappel pour le règlement de notre facture numéro ".$f->reference."  que nous vous avons adressée en date ". $dateEmail." dans le cadre du dossier référence ".$doss_ref_cus." (Notre réf;".$doss_ref.") du montant ".$f->montant." ".$f->devise.".<br><br>
Or à ce jour nous n’avons toujours pas reçu votre règlement pour cette facture envoyée voilà maintenant 55 jours. Si vous ne l’avez pas encore effectué, nous vous saurions gré de bien vouloir procéder dans les meilleurs délais et nous en informer. Si toutefois vous l’avez déjà effectué entre-temps et que nous ne l’avons pas encore reçu, veuillez SVP nous en adresser les détails et ne pas tenir compte de ce mail.<br><br>
Avec nos remerciements pour votre collaboration.<br><br> (Signé): Ceci est un email généré automatiquement par le système de gestion de Najda Assistance (2ème rappel).<br><br>";


                                  $contenu=$contenu.'<br><br>Cordialement <br>'.$signatureFinances.'<br><br><hr style="float:left;"><br><br>';
                                  $email_client[]=$adr[0];
                                 self::envoi_mail($swiftTransport,$adr,$sujet,$contenu,$from, $fromname);
                                  Facture::where('id', $f->id)->update(['mail_55_env'=>1]);
                                  //dd('ok envoi au gestionnaire client');
                                  }
                                  else
                                  {
                                    //dd('En');
                                    $sujet = 'Customer invoice';
                                    /*$contenu = "Hello , <br>
                                     For the File ".$dossier->reference_medic." (including the insured: ". $prenom_ass." ".$nom_ass.", refers ". $f->reference." and the date ". $dateEmail."), your bill has not been paid yet<br>
                                    (Signed): Mail generated automatically ";*/

                            $contenu = "Hello from the accountability department of Najda Assistance,<br><br>
This is a second reminder for the payment of our invoice number ".$f->reference." that we sent you on the date of ".$dateEmail." as part of the file under your reference:".$doss_ref_cus." (Our ref: ".$doss_ref.")  with the amount of ".$f->montant." ".$f->devise.".<br><br>
However, until today, we did not receive any payment for this invoice that was sent 55 days ago. If u did not proceed for the payment yet, we would be grateful if you could do it as soon as possible and let us know. In case that you have already done the payment in the meantime and we did not receive it yet, please send us the details and do not take note of this email.<br><br>
Many thanks for your collaboration.<br><br>
                                    (Signed): This is an automatically generated email by the management system of Najda Assistance.(2nd reminder)
 ";
                                     $contenu=$contenu.'<br><br>cordially <br>'.$signatureFinances.'<br><br><hr style="float:left;"><br><br>';
                                       $email_client[]=$adr[0];
                                       self::envoi_mail($swiftTransport,$adr,$sujet,$contenu,$from, $fromname);
                                        Facture::where('id', $f->id)->update(['mail_55_env'=>1]);
                  
                                      }

                                            $ccea=self::return_dest_cc($adr);
                                            $ccea = implode(";", $ccea);
                                            $toea=self::return_dest_to($adr);
                                         
                                            $emaiauto=new EmailAuto ([ 
                                           'dossierid'=>$dossier->id,
                                           'dossier' =>$dossier->reference_medic,
                                           'client' =>$cli->name,
                                           'destinataire' =>$toea,
                                           'emetteur'=>$from,
                                           'cc'=>$ccea,
                                           'sujet'=>$sujet, 
                                           'contenutxt' =>$contenu,
                                           'type'=>'facture_client'                    

                                           ]);

                                          $emaiauto->save();

                             }
                             else // sinon si mail gestion n'existe pas 
                             {



                             }


                         
                                 
                        }// fin mail 55 jours 

                    } // fin else avant 55
                

                 }// if mail45=0 ou mail55=0

                }// fin if f dossier
                 

            }
              else
              {
                if($dateSys70>= $dateEnvoi) // alerte le financier
                {

                   $sujet = 'Facture Client';
                   // $adr='finances@najda-assistance.com';
                   $adr='kbskhaled@gmail.com';

                   if($dateSys70 >= $dateEnvoi && $dateEnvoi > $dateSys85 )
                    {

                     if($f->mail_70_env==0)
                     {
                     $contenu = "Alerte pour le financier<br>
                    la facture du client ".$cli->name." pour le dossier ".$dossier->reference_medic." n'est pas encore réglée  depuis une période qui dépasse 70 jours<br>
                    (Signé): Mail généré automatiquement";
                   
                  //$dateCreation = \DateTime::createFromFormat($p->date_prestation);
                                     
                     //$date_rapp2= \DateTime::createFromFormat($format, $date_rapp);
                // $dtc30=(new \DateTime())->modify('-30 days')->format($format);
                       $email_client[]=$adr;
                        self::envoi_mail($swiftTransport2,$adr,$sujet,$contenu,$from2, $fromname2);
                         Facture::where('id', $f->id)->update(['mail_70_env'=>1]);

                         $ccea=self::return_dest_cc($adr);
                              $ccea = implode(";", $ccea);
                              $toea=self::return_dest_to($adr);
                             

                              $emaiauto=new EmailAuto ([ 
                             'dossierid'=>$dossier->id,
                             'dossier' =>$dossier->reference_medic,
                             'client' =>$cli->name,
                             'destinataire' =>$toea,
                             'emetteur'=>$from,
                             'cc'=>$ccea,
                             'sujet'=>$sujet, 
                             'contenutxt' =>$contenu,
                             'type'=>'Alerte_financier_facture_client'                    

                             ]);

                            $emaiauto->save();

                             $date_rapp = (new \DateTime($dateEmail))->modify('+70 days');
    
                     Facture::where('id', $f->id)->update(['date_rapp_15'=>$date_rapp]);
                     }
                  
                    }
                     else
                     {
                      if($dateSys85 > $dateEnvoi )
                      {

                        if($f->date_rapp_15)
                        {

                            $date_rapp = new \DateTime($f->date_rapp_15);
                            //$date_rapp2= \DateTime::createFromFormat($format, $date_rapp);

                            $dtc=(new \DateTime())->format($format);
                            $dateSys = \DateTime::createFromFormat($format, $dtc);

                            if( $dateSys >  $date_rapp && intval($dateSys->diff($date_rapp)->d)>15)
                            {

                              $contenu = "Alerte pour le financier - Rappel periodique de 15 jours <br>
                               la facture du client ".$cli->name." pour le dossier ".$dossier->reference_medic." n'est pas encore réglée  depuis une période qui dépasse 70 jours<br>
                               (Signé): Mail généré automatiquement";


                               $email_client[]=$adr;
                        self::envoi_mail($swiftTransport2,$adr,$sujet,$contenu,$from2, $fromname2);
                         Facture::where('id', $f->id)->update(['mail_70_env'=>1]);

                         $ccea=self::return_dest_cc($adr);
                              $ccea = implode(";", $ccea);
                              $toea=self::return_dest_to($adr);
                             

                              $emaiauto=new EmailAuto ([ 
                             'dossierid'=>$dossier->id,
                             'dossier' =>$dossier->reference_medic,
                             'client' =>$cli->name,
                             'destinataire' =>$toea,
                             'emetteur'=>$from,
                             'cc'=>$ccea,
                             'sujet'=>$sujet, 
                             'contenutxt' =>$contenu,
                             'type'=>'Alerte_financier_facture_client'                    

                             ]);

                            $emaiauto->save();

                              $date_rapp->modify('+15 days');
                             Facture::where('id', $f->id)->update(['date_rapp_15'=>$date_rapp]);

                            }


                        }



                      }

                     }

                 // dd("envoi alerte au financier cas client");
                        


                }

              }                 

              

///////------------------------------------------------------------------------------------------------
            
                
            }// fin if reg

        } //fin if honoraire

      } // fin foreach ($factures as $f )

   } // fin if  $factures
      

    }//fin fonction

    public static function return_dest_to ($adresses)
    {

            $cc=array();
            $dests = null;
            $to=null;
      
      if(is_array($adresses))
             {
                $dests=$adresses;
             }
             else
             {
                $dests=array($adresses);
             }
             
            // dd($dests);
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
                 
                 array_push($cc,'nejib.karoui@medicmultiservices.com');
                // array_push($cc,'kbskhaled@gmail.com');
                  //array_push($cc,'nbsnajoua@gmail.com');
  
               }
                else
               {
               $to=$dests[0] ; // null;
          
                array_push($cc,'nejib.karoui@medicmultiservices.com');
                //array_push($cc,'kbskhaled@gmail.com');
                //array_push($cc,'nbsnajoua@gmail.com');
               }
    
             }

             return $to;

    }


    public static function return_dest_cc ($adresses)
    {

            $cc=array();
            $dests = null;
            $to=null;
      
      if(is_array($adresses))
             {
                $dests=$adresses;
             }
             else
             {
                $dests=array($adresses);
             }
             
            // dd($dests);
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
                  
                  array_push($cc,'nejib.karoui@medicmultiservices.com');
                 // array_push($cc,'kbskhaled@gmail.com');
                  //array_push($cc,'nbsnajoua@gmail.com');
  
               }
                else
               {
               $to=$dests[0] ; // null;
             
                 array_push($cc,'nejib.karoui@medicmultiservices.com');
                // array_push($cc,'kbskhaled@gmail.com');
                 //array_push($cc,'nbsnajoua@gmail.com');
               }
    
             }

             return $cc;

    }


     public static function envoi_mail_automatique_factures_version3()    
    {
        $contenu='';
        $id_prest_test=array(10000,11000);
        $id_fact_test=array(22,23);
        $email_prestataires=array();
        $email_client=array();

      $dtc = (new \DateTime())->format('2021-01-01 00:00:00');
      //$factures=Facture::where('id',22)->orWhere('id',23)->get();
      //$factures=Facture::where('honoraire',1)->whereNotNull('date_email')->whereIn('id',$id_fact_test)->where('created_at','>=', $dtc)->get();
      //dd($factures);
     // $prestations=Prestation::whereNotNull('date_prestation')->where('parvenu','<>',1)->get();
     //$prestations=Prestation::where('parvenu','<>',1)->whereNotNull('date_prestation')->whereNotNull('created_at')->where('created_at','>=', $dtc)->whereIn('prestataire_id',$id_prest_test)->get();
      //dd($prestations);
      $factures=Facture::where('honoraire',1)->whereNotNull('date_email')->where('created_at','>=', $dtc)->get();
      $prestations=Prestation::where('parvenu','<>',1)->whereNotNull('date_prestation')->whereNotNull('created_at')->where('created_at','>=', $dtc)->get();
      

      $format = "Y-m-d H:i:s";
      $dtc30=(new \DateTime())->modify('-30 days')->format($format);
      $dtc45=(new \DateTime())->modify('-45 days')->format($format);
      $dtc60=(new \DateTime())->modify('-60 days')->format($format);
      $dtc75=(new \DateTime())->modify('-75 days')->format($format);


      $dtc40=(new \DateTime())->modify('-40 days')->format($format);
      $dtc55=(new \DateTime())->modify('-55 days')->format($format);
      $dtc70=(new \DateTime())->modify('-70 days')->format($format);
      $dtc85=(new \DateTime())->modify('-85 days')->format($format);

      $dateSys30 = \DateTime::createFromFormat($format, $dtc30);
      $dateSys45 = \DateTime::createFromFormat($format, $dtc45);
      $dateSys60 = \DateTime::createFromFormat($format, $dtc60);
      $dateSys75 = \DateTime::createFromFormat($format, $dtc75);

      $dateSys40 = \DateTime::createFromFormat($format, $dtc40);
      $dateSys55 = \DateTime::createFromFormat($format, $dtc55);
      $dateSys70 = \DateTime::createFromFormat($format, $dtc70);
      $dateSys85 = \DateTime::createFromFormat($format, $dtc85);


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
            $fromname_TM="Transport medic";
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

   if($prestations && $prestations->count()>0 )
   {
      
      foreach ($prestations as $p ) {

              $dateCreation1=str_replace('/','-',$p->date_prestation);   
              //$dateCreation = \DateTime::createFromFormat($p->date_prestation);
              $dateCreation = new \DateTime($dateCreation1);
              $dss=Dossier::where('id',$p->dossier_id)->first();
               $doss_ref='';
              $nom_ass='';
              $prenom_ass='';
              if($dss)
              {
              $doss_ref=$dss->reference_medic;
              $nom_ass=$dss->subscriber_name;
              $prenom_ass=$dss->subscriber_lastname;
              }
              //oooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo
              //dd($doss_ref);
              //19TV01308 ; 20V00146 ; 20TM00343; 19MI01306 ; 19M02548 ; 20N00456; 19TN00001  19TPA00497 19XP00002
              $doss_ref="19XP00002";
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

               // dd($entete);
//=======================================================================================================

              if(($dateSys30>= $dateCreation ||  $dateSys45>= $dateCreation) &&  $dateSys60< $dateCreation)
              {
                
               //extraction d'adresse email
                if($p->prestataire_id)
                {
                 $adr=Adresse::where('parent', $p->prestataire_id)->where('nature','emailinterv')->pluck('champ')->toArray();
                 $typeprestation='';
                 if($p->type_prestations_id && $p->type_prestations_id != 0)
                 {
                   $typeprestation=TypePrestation::where('id',$p->type_prestations_id)->first()->name;
                 }

                 if($p->mail_30_env==0 || $p->mail_45_env==0)
                 {

                    if($p->mail_30_env==0 && $dateSys45 < $dateCreation  )
                    {
                        //dd("ok");
                     /*$contenu = "Bonjour,<br>
                     Pour le dossier : ".$doss_ref." (dont l'assuré est".$prenom_ass." ".$nom_ass." et la date de prestation est ".$dateCreation1."), votre facture n'est pas encore reçue depuis une période qui dépasse 30 jours<br>
                     (Signé): Mail généré automatiquement";*/

                     $contenu = "Bonjour du service ".$entete.",<br><br>
                     Vous avez réalisé pour Najda Assistance en date du ".$dateCreation1." le service suivant :<br><br>
                      Le service ".$typeprestation."<br><br>
                      Or à ce jour nous n’avons toujours pas reçu votre facture pour ce service rendu il y’a maintenant une trentaine de jours. Si vous ne l’avez pas encore envoyée, nous vous prions de bien vouloir le faire dans les meilleurs délais en précisant notre référence de dossier référence ".$doss_ref." (dont l'assuré est ".$prenom_ass." ".$nom_ass."). Si toutefois vous l’avez déjà envoyée et que nous ne l’avons pas encore reçue, veuillez ne pas tenir compte de ce mail.<br><br>
                        Avec nos remerciements pour votre collaboration.<br><br>
                        (Signé): Ceci est un email généré automatiquement par le système de gestion de Najda Assistance (Rappel 1)";  
                     
                    }
                    else
                    {
                    
                      if($p->mail_45_env==0 && $dateSys45 >= $dateCreation)
                        {
                          /*$contenu = "Bonjour,<br>
                         Pour le dossier : ".$doss_ref." (dont l'assuré est ".$prenom_ass." ".$nom_ass." et la date de prestation est ".$dateCreation1."), votre facture n'est pas encore reçue depuis une période qui dépasse 45 jours<br>
                        (Signé): Mail généré automatiquement";*/
                        $contenu = "Bonjour du service ".$entete.",<br><br>
                          Ceci est le deuxième rappel pour le service rendu à Najda Assistance en date du  ".$dateCreation1." et qui consiste en:<br><br>
                           Le service ".$typeprestation."  dans le cadre de notre dossier de référence ".$doss_ref." (dont l'assuré est ".$prenom_ass." ".$nom_ass.").<br><br>
                             Or à ce jour nous n’avons toujours pas reçu votre facture pour ce service rendu il y’a maintenant 45 jours. Si vous ne l’avez pas encore envoyée, nous vous prions de bien vouloir le faire dans les meilleurs délais en précisant notre référence. Passé 60 jours après le service rendu, nous ne pourrons plus garantir son règlement.<br><br>
                         Si toutefois vous l’avez déjà envoyée et que nous ne l’avons pas encore reçue, veuillez ne pas tenir compte de ce mail.
                          Avec nos remerciements pour votre collaboration<br><br>
                          (Signé): Ceci est un email généré automatiquement par le système de gestion de Najda Assistance (Rappel 2)";

                       }

                    }
                
                  $sujet = "Rappel Facture Prestataire";
                 
                  $contenu=$contenu.'<br><br>Cordialement <br><br> '.$signature.'<br><br><hr style="float:left;"><br><br>';

                  if($adr && count($adr)>0)
                   {
                    //dd('ok envoi au prestataire ');
                    //
                     //dd('ok envoi au prestataire ');
                     if($dateSys30>= $dateCreation && $dateSys45 < $dateCreation && $p->mail_30_env==0 )
                            {
                              $email_prestataires[]=$adr[0];
                              self::envoi_mail($swiftTransport,$adr,$sujet,$contenu,$from, $fromname);
                              Prestation::where('id', $p->id)->update(['mail_30_env'=>1]);
                              $ccea=self::return_dest_cc($adr);
                              $ccea = implode(";", $ccea);
                              $toea=self::return_dest_to($adr);
                              $presname='';
                              $prenom='';
                              $presname=Prestataire::where('id',$p->prestataire_id)->first();
                              if($presname)
                              {
                              $presname=Prestataire::where('id',$p->prestataire_id)->first()->name;
                              $prenom=Prestataire::where('id',$p->prestataire_id)->first()->prenom;
                                if($prenom)
                                {
                                  $presname=$prenom.' '.$presname;
                                }
                              }

                              $emaiauto=new EmailAuto ([ 
                             'dossierid'=>$p->dossier_id,
                             'dossier' =>$doss_ref,
                             'prestataire' =>$presname,
                             'destinataire' =>$toea,
                             'emetteur'=>$from,
                             'cc'=>$ccea,
                             'sujet'=>$sujet, 
                             'contenutxt' =>$contenu,
                             'type'=>'facture_prestataire'                    

                             ]);

                            $emaiauto->save();


                            }
                            else
                            {
                                 if($dateSys45 >= $dateCreation && $p->mail_45_env==0)
                                 {
                                   $email_prestataires[]=$adr[0];
                               self::envoi_mail($swiftTransport,$adr,$sujet,$contenu,$from, $fromname);
                                Prestation::where('id', $p->id)->update(['mail_45_env'=>1]);
                                    $ccea=self::return_dest_cc($adr);
                                      $ccea = implode(";", $ccea);
                                      $toea=self::return_dest_to($adr);
                                      $presname='';
                                      if($presname=Prestataire::where('id',$p->prestataire_id)->first())
                                      {
                                      $presname=Prestataire::where('id',$p->prestataire_id)->first()->name;
                                      }

                                      $emaiauto=new EmailAuto ([ 
                                     'dossierid'=>$p->dossier_id,
                                     'dossier' =>$doss_ref,
                                     'prestataire' =>$presname,
                                     'destinataire' =>$toea,
                                     'emetteur'=>$from,
                                     'cc'=>$ccea,
                                     'sujet'=>$sujet, 
                                     'contenutxt' =>$contenu,
                                     'type'=>'facture_prestataire'                    

                                     ]);

                                    $emaiauto->save();

                                 }

                            }
                           

                   }
                   else// pour les sms automatiques
                   {
                     $num=Adresse::where('parent', $p->prestataire_id)->where('nature','telinterv')->where('typetel','like','Mobile')->orderBy('created_at','DESC')->first()->champ;
                     if($p->mail_30_env==0 && $dateSys45 < $dateCreation  )
                      {
                        
                        $contenu="  Votre facture du service ".$typeprestation." effectué la date ".$dateCreation1."  dans le cadre de notre dossier référence ".$doss_ref." (dont l'assuré est ".$prenom_ass." ".$nom_ass.") toujours pas recue. Merci ns l’adresser ds les meilleurs délais.
                          ".$entete." ";

                        $contenu= str_replace ( '&' ,'' ,$contenu);
                        $contenu= str_replace ( '<' ,'' ,$contenu);
                        $contenu= str_replace ( '>' ,'' ,$contenu);
                        $xmlString = '<?xml version="1.0" encoding="UTF-8" ?>
                        <sms>
                            <gsm>'.$num.'</gsm>
                            <texte>'.$contenu.'</texte>
                        </sms>';
                        $date=date('dmYHis');
                        $filepath = storage_path() . '/SENDSMS/sms_'.$num.'_'.$date.'.xml';
                        
                         $emaiauto=new EmailAuto ([ 
                                     'dossierid'=>$p->dossier_id,
                                     'dossier' =>$doss_ref,
                                     'prestataire' =>$presname,
                                     'destinataire' => $num,
                                     'emetteur'=>$from,
                                     'sujet'=>'facture prestataire', 
                                     'contenutxt' =>$contenu,
                                     'type'=>'facture_prestataire'                    

                                     ]);

                                    $emaiauto->save();

                                    /* $par=Auth::id();
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
                                    $envoye->save();*/

                      }
                    else
                     {
                      if($dateSys45 >= $dateCreation && $p->mail_45_env==0)
                        {

                           $contenu="  Votre facture du service ".$typeprestation." effectué la date ".$dateCreation1."  dans le cadre de notre dossier référence ".$doss_ref." (dont l'assuré est ".$prenom_ass." ".$nom_ass.") ne pourra plus etre garantie si non recue ds les 15 jours. 
                             ".$entete." ";
                        $contenu= str_replace ( '&' ,'' ,$contenu);
                        $contenu= str_replace ( '<' ,'' ,$contenu);
                        $contenu= str_replace ( '>' ,'' ,$contenu);
                        $xmlString = '<?xml version="1.0" encoding="UTF-8" ?>
                        <sms>
                            <gsm>'.$num.'</gsm>
                            <texte>'.$contenu.'</texte>
                        </sms>';
                        $date=date('dmYHis');
                        $filepath = storage_path() . '/SENDSMS/sms_'.$num.'_'.$date.'.xml';

                        $emaiauto=new EmailAuto ([ 
                                     'dossierid'=>$p->dossier_id,
                                     'dossier' =>$doss_ref,
                                     'prestataire' =>$presname,
                                     'destinataire' => $num,
                                     'emetteur'=>$from,
                                     'sujet'=>'facture prestataire', 
                                     'contenutxt' =>$contenu,
                                     'type'=>'facture_prestataire'                    

                                     ]);

                                    $emaiauto->save();

                                   /* $par=Auth::id();
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
                                    $envoye->save();*/


                        }
                      }

                   }                    


                 }

                }
                 

            }
              else
              {
                if($dateSys60>= $dateCreation) // alerte le financier
                {

                    $sujet = 'Facture Prestataire - Alerte pour le financier';
                    $adr='finances@najda-assistance.com';
                    //$adr='kbskhaled@gmail.com';
                    //dd($adr);
                      $presname='';
                      $prenom='';
                      $presname=Prestataire::where('id',$p->prestataire_id)->first();
                      if($presname)
                      {
                      $presname=Prestataire::where('id',$p->prestataire_id)->first()->name;
                      $prenom=Prestataire::where('id',$p->prestataire_id)->first()->prenom;
                        if($prenom)
                        {
                          $presname=$prenom.' '.$presname;
                        }
                      }

                    //dd("envoi alerte au financier cas prestataire");
                      if($dateSys60 >= $dateCreation && $dateCreation > $dateSys75 )
                      {
                        if($p->mail_60_env==0)
                          {
                            $contenu = "Alerte pour le financier<br>
                    la facture du prestataire ". $presname." pour la prestation concernant le dossier ".$doss_ref." (dont l'assuré ".$prenom_ass." ".$nom_ass." et la date de prestation est ".$dateCreation1."), n'est pas encore reçue depuis une période qui dépasse 60 jours<br>
                    (Signé): Mail généré automatiquement";
                      
                  //$dateCreation = \DateTime::createFromFormat($p->date_prestation);
                                     
               // $date_rapp2= \DateTime::createFromFormat($format, $date_rapp);
     // $dtc30=(new \DateTime())->modify('-30 days')->format($format);

                  $email_prestataires[]=$adr;
                    self::envoi_mail($swiftTransport,$adr,$sujet,$contenu,$from, $fromname);
                    Prestation::where('id', $p->id)->update(['mail_60_env'=>1]);
                    //adresse financier
                    //$adr= fianancier
                              $ccea=self::return_dest_cc($adr);
                              $ccea = implode(";", $ccea);
                              $toea=self::return_dest_to($adr);
                             

                              $emaiauto=new EmailAuto ([ 
                             'dossierid'=>$p->dossier_id,
                             'dossier' =>$doss_ref,
                             'prestataire' =>$presname,
                             'destinataire' =>$toea,
                             'emetteur'=>$from,
                             'cc'=>$ccea,
                             'sujet'=>$sujet, 
                             'contenutxt' =>$contenu,
                             'type'=>'Alerte_financier_facture_prestataire'                    

                             ]);

                            $emaiauto->save();
                      $date_rapp = (new \DateTime($dateCreation1))->modify('+60 days');
                      Prestation::where('id', $p->id)->update(['date_rapp_15'=>$date_rapp]);
                       }
                     }
                     else
                     {
                      if($dateSys75 > $dateCreation )
                      {

                        if($p->date_rapp_15)
                        {

                            $date_rapp = new \DateTime($p->date_rapp_15);
                            //dd($date_rapp);
                            //$date_rapp2= \DateTime::createFromFormat($format, $date_rapp);

                            $dtc=(new \DateTime())->format($format);
                            $dateSys = \DateTime::createFromFormat($format, $dtc);
                            //dd($dateSys->diff($date_rapp)->d);

                    if( $dateSys > $date_rapp && intval($dateSys->diff($date_rapp)->format('%R%a'))> 15)
                            {

                               $contenu = "Alerte pour le financier - Rappel periodique de 15 jours <br>
                              la facture du prestataire ". $presname." pour la prestation concernant le dossier ".$doss_ref." (dont l'assuré ".$prenom_ass." ".$nom_ass." et la date de prestation est ".$dateCreation1."), n'est pas encore reçue depuis une période qui dépasse 60 jours<br>
                              (Signé): Mail généré automatiquement";

                               $email_prestataires[]=$adr;
                                self::envoi_mail($swiftTransport,$adr,$sujet,$contenu,$from, $fromname);
                                 Prestation::where('id', $p->id)->update(['mail_60_env'=>1]);
                               //adresse financier
                              //$adr= fianancier
                              $ccea=self::return_dest_cc($adr);
                              $ccea = implode(";", $ccea);
                              $toea=self::return_dest_to($adr);
                             

                              $emaiauto=new EmailAuto ([ 
                             'dossierid'=>$p->dossier_id,
                             'dossier' =>$doss_ref,
                             'prestataire' =>$presname,
                             'destinataire' =>$toea,
                             'emetteur'=>$from,
                             'cc'=>$ccea,
                             'sujet'=>$sujet, 
                             'contenutxt' =>$contenu,
                             'type'=>'Alerte_financier_facture_prestataire'                    

                             ]);

                            $emaiauto->save();

                            $date_rapp->modify('+15 days');
                              Prestation::where('id', $p->id)->update(['date_rapp_15'=>$date_rapp]);

                            }


                        }



                      }

                     }
                                     

                }

              }

//=======================================================================================================
         
        }// end foreach prestation
    }// end if prestation

  //dd('fin envoi mail au prestataire');
    //$testadr=array(4364,4371);
    if($factures && $factures->count()>0 )
        {
      //dd($factures);
      foreach ($factures as $f ) 
      {
       
        if($f->honoraire == 1) // cas client
        {
                
            if($f->regle==0)// facture non réglée
            {
               
                //$format = "Y/m/d"; 
                if($f->date_email)
                {
                  $dateEmail=str_replace('/','-',$f->date_email);
                }
                else
                {
                  if($f->date_poste)
                  {
                     $dateEmail=str_replace('/','-',$f->date_poste);
                  }

                }
                 $dateEnvoi= new \DateTime($dateEmail);
                 $dossier=Dossier::where('id',$f->iddossier)->first(); 
                 $cli=Client::where('id', $dossier->customer_id)->first();
                  $doss_ref='';
                  $nom_ass='';
                  $prenom_ass='';
              if($dossier)
              {
              $doss_ref=$dossier->reference_medic;
              $doss_ref_cus=$dossier->reference_customer;
              $nom_ass=$dossier->subscriber_name;
              $prenom_ass=$dossier->subscriber_lastname;
              }


               // dd( date_format(strtotime($f->date_email),"Y/m/d H:i:s"));          
             // $dateEnvoi = (\date::createFromFormat($format, $f->date_email) );
           //  dd($dateEnvoi);
              //nature telinterv
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
////////----------------------------------------------------------------------------------------------
// 30->40 ; 45->55 ; 60->70; 
if(($dateSys40>= $dateEnvoi ||  $dateSys55>=  $dateEnvoi) &&  $dateSys70<  $dateEnvoi)
              {
                //dd('kk');
               //extraction d'adresse email
                if($f->iddossier)
                {
                 $adr=Adresse::where('parent',$dossier->customer_id)->where('nature','gestion')->orderBy('id')->pluck('mail')->toArray();
                 $clilang=null;
                
                     $sujet = '';
                     $contenu ='';
                    if($cli->langue1=="francais" || stristr($cli->langue1, "fran"))
                        {
                            $clilang="Fr";

                        }
                        else
                        {

                            $clilang="Ang";

                        }
                

                 if($f->mail_40_env==0 || $f->mail_55_env==0)
                 {

                    if($f->mail_40_env==0 && $dateSys55 < $dateEnvoi  )
                    {
                       if($adr && count($adr)>0)
                       {
                          if($clilang=="Fr")
                            {
                            $sujet = 'Rappel facture client';
                             /*$contenu = "Bonjour ,<br>
                            Pour le dossier ".$dossier->reference_medic."(dont l'assuré : ".$prenom_ass." ".$nom_ass.", réfernce ".$f->reference." et la date ".$dateEmail."), votre facture n'est pas encore réglée<br>
                             (Signé): Mail généré automatiquement";
                            $contenu=$contenu.'<br><br>Cordialement <br>'.$signatureFinances.'<br><br><hr style="float:left;"><br><br>';*/

                            $contenu = "Bonjour du service gestion de ".$entete.",<br><br>
                           
Nous vous avons adressé en date du ". $dateEmail." dans le cadre du dossier ".$doss_ref_cus." (Notre ref:".$doss_ref." - Assuré : ".$prenom_ass." ".$nom_ass.") notre facture numéro ".$f->reference." du montant ".$f->montant." ".$f->devise."<br><br>
Or à ce jour nous n’avons toujours pas reçu votre règlement pour cette facture envoyée voilà maintenant une quarantaine de jours. Si vous ne l’avez pas encore effectué, nous vous saurions gré de bien vouloir procéder dans les meilleurs délais et nous en informer. Si toutefois vous l’avez déjà effectué entre-temps et que nous ne l’avons pas encore reçu, veuillez SVP nous en adresser les détails et ne pas tenir compte de ce mail.<br><br>
Avec nos remerciements pour votre collaboration.<br><br>
 (Signé): Ceci est un email généré automatiquement par le système de gestion de Najda Assistance";
                            $contenu=$contenu.'<br><br>Cordialement <br>'.$signature.'<br><br><hr style="float:left;"><br><br>';

                            $email_client[]=$adr[0];
                           self::envoi_mail($swiftTransport,$adr,$sujet,$contenu,$from, $fromname);
                            Facture::where('id', $f->id)->update(['mail_40_env'=>1]);
                            //dd('ok envoi au gestionnaire client');
                            }
                            else
                            {
                              $sujet = 'Customer invoice reminder';
                             /* $contenu = "Hello , <br>
                               For the File ".$dossier->reference_medic." (including the insured: ". $prenom_ass." ".$nom_ass.", refers ". $f->reference." and the date ". $dateEmail."), your bill has not been paid yet<br>
                              (Signed): Mail generated automatically ";
                               $contenu=$contenu.'<br><br>cordially <br>'.$signatureFinances.'<br><br><hr style="float:left;"><br><br>';*/

                            $contenu = "Hello from the accountability department of ".$entete.",<br><br>
We sent you on the date of". $dateEmail."  as part of the file under your reference ".$doss_ref_cus." (Our ref:".$doss_ref." - insured: ". $prenom_ass." ".$nom_ass.") our invoice number ".$f->reference." with the amount of ".$f->montant." ".$f->devise."<br><br>.
However, until today, we did not receive any payment for this invoice that was sent forty days ago. If u did not proceed for the payment yet, we would be grateful if you could do it as soon as possible and let us know. In case that you have already done the payment in the meantime and we did not receive it yet, please send us the details and do not take note of this email.<br><br>
Many thanks for your collaboration<br><br>
                              (Signed): This is an automatically generated email by the management system of Najda Assistance. ";
                               $contenu=$contenu.'<br><br>cordially <br>'.$signature.'<br><br><hr style="float:left;"><br><br>';


                                 $email_client[]=$adr[0];
                                 self::envoi_mail($swiftTransport,$adr,$sujet,$contenu,$from, $fromname);
                                  Facture::where('id', $f->id)->update(['mail_40_env'=>1]);
            
                                }

                                      $ccea=self::return_dest_cc($adr);
                                      $ccea = implode(";", $ccea);
                                      $toea=self::return_dest_to($adr);
                                   
                                      $emaiauto=new EmailAuto ([ 
                                     'dossierid'=>$dossier->id,
                                     'dossier' =>$dossier->reference_medic,
                                     'client' =>$cli->name,
                                     'destinataire' =>$toea,
                                     'emetteur'=>$from,
                                     'cc'=>$ccea,
                                     'sujet'=>$sujet, 
                                     'contenutxt' =>$contenu,
                                     'type'=>'facture_client'                    

                                     ]);

                                    $emaiauto->save();

                       }
                       else // sinon si mail gestion n'existe pas dernier mail envoyes
                       {

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
                               $adr=$dests;

                               if($clilang=="Fr")
                            {
                            $sujet = 'Rappel facture client';
                             /*$contenu = "Bonjour ,<br>
                            Pour le dossier ".$dossier->reference_medic."(dont l'assuré : ".$prenom_ass." ".$nom_ass.", réfernce ".$f->reference." et la date ".$dateEmail."), votre facture n'est pas encore réglée<br>
                             (Signé): Mail généré automatiquement";
                            $contenu=$contenu.'<br><br>Cordialement <br>'.$signatureFinances.'<br><br><hr style="float:left;"><br><br>';*/

                            $contenu = "Bonjour du service gestion de ".$entete.",<br><br>
                           
Nous vous avons adressé en date du ". $dateEmail." dans le cadre du dossier ".$doss_ref_cus." (Notre ref:".$doss_ref." - Assuré : ".$prenom_ass." ".$nom_ass.") notre facture numéro ".$f->reference." du montant ".$f->montant." ".$f->devise."<br><br>
Or à ce jour nous n’avons toujours pas reçu votre règlement pour cette facture envoyée voilà maintenant une quarantaine de jours. Si vous ne l’avez pas encore effectué, nous vous saurions gré de bien vouloir procéder dans les meilleurs délais et nous en informer. Si toutefois vous l’avez déjà effectué entre-temps et que nous ne l’avons pas encore reçu, veuillez SVP nous en adresser les détails et ne pas tenir compte de ce mail.<br><br>
Avec nos remerciements pour votre collaboration.<br><br>
 (Signé): Ceci est un email généré automatiquement par le système de gestion de Najda Assistance";
                            $contenu=$contenu.'<br><br>Cordialement <br>'.$signature.'<br><br><hr style="float:left;"><br><br>';

                            $email_client[]=$adr[0];
                           self::envoi_mail($swiftTransport,$adr,$sujet,$contenu,$from, $fromname);
                            Facture::where('id', $f->id)->update(['mail_40_env'=>1]);
                            //dd('ok envoi au gestionnaire client');
                            }
                            else
                            {
                              $sujet = 'Customer invoice reminder';
                             /* $contenu = "Hello , <br>
                               For the File ".$dossier->reference_medic." (including the insured: ". $prenom_ass." ".$nom_ass.", refers ". $f->reference." and the date ". $dateEmail."), your bill has not been paid yet<br>
                              (Signed): Mail generated automatically ";
                               $contenu=$contenu.'<br><br>cordially <br>'.$signatureFinances.'<br><br><hr style="float:left;"><br><br>';*/

                            $contenu = "Hello from the accountability department of ".$entete.",<br><br>
We sent you on the date of". $dateEmail."  as part of the file under your reference ".$doss_ref_cus." (Our ref:".$doss_ref." - insured: ". $prenom_ass." ".$nom_ass.") our invoice number ".$f->reference." with the amount of ".$f->montant." ".$f->devise."<br><br>.
However, until today, we did not receive any payment for this invoice that was sent forty days ago. If u did not proceed for the payment yet, we would be grateful if you could do it as soon as possible and let us know. In case that you have already done the payment in the meantime and we did not receive it yet, please send us the details and do not take note of this email.<br><br>
Many thanks for your collaboration<br><br>
                              (Signed): This is an automatically generated email by the management system of Najda Assistance. ";
                               $contenu=$contenu.'<br><br>cordially <br>'.$signature.'<br><br><hr style="float:left;"><br><br>';


                                 $email_client[]=$adr[0];
                                 self::envoi_mail($swiftTransport,$adr,$sujet,$contenu,$from, $fromname);
                                  Facture::where('id', $f->id)->update(['mail_40_env'=>1]);
            
                                }

                                      $ccea=self::return_dest_cc($adr);
                                      $ccea = implode(";", $ccea);
                                      $toea=self::return_dest_to($adr);
                                   
                                      $emaiauto=new EmailAuto ([ 
                                     'dossierid'=>$dossier->id,
                                     'dossier' =>$dossier->reference_medic,
                                     'client' =>$cli->name,
                                     'destinataire' =>$toea,
                                     'emetteur'=>$from,
                                     'cc'=>$ccea,
                                     'sujet'=>$sujet, 
                                     'contenutxt' =>$contenu,
                                     'type'=>'facture_client'                    

                                     ]);

                                    $emaiauto->save();



                                } // if($arraykbs && count($arraykbs)>0)


                       } // fin else sinon mail
                       
                    }
                    else
                    {
                      
                      if($f->mail_55_env==0 && $dateSys55 >=  $dateEnvoi)
                        {
                         // dd("55");
                           if($adr && count($adr)>0)
                             {
                                if($clilang=="Fr")
                                  {

                                    //dd('fr');
                                  $sujet = 'Facture client';
                                   /*$contenu = "Bonjour ,<br>
                                  Pour le dossier ".$dossier->reference_medic."(dont l'assuré : ".$prenom_ass." ".$nom_ass.", réfernce ".$f->reference." et la date ".$dateEmail."), votre facture n'est pas encore réglée<br>
                                   (Signé): Mail généré automatiquement";*/

                                   $contenu = "Bonjour du service gestion de ".$entete.",<br><br>
Ceci est un deuxième rappel pour le règlement de notre facture numéro ".$f->reference."  que nous vous avons adressée en date ". $dateEmail." dans le cadre du dossier référence ".$doss_ref_cus." (Notre réf;".$doss_ref." - Assuré : ".$prenom_ass." ".$nom_ass.") du montant ".$f->montant." ".$f->devise.".<br><br>
Or à ce jour nous n’avons toujours pas reçu votre règlement pour cette facture envoyée voilà maintenant 55 jours. Si vous ne l’avez pas encore effectué, nous vous saurions gré de bien vouloir procéder dans les meilleurs délais et nous en informer. Si toutefois vous l’avez déjà effectué entre-temps et que nous ne l’avons pas encore reçu, veuillez SVP nous en adresser les détails et ne pas tenir compte de ce mail.<br><br>
Avec nos remerciements pour votre collaboration.<br><br> (Signé): Ceci est un email généré automatiquement par le système de gestion de Najda Assistance (2ème rappel).<br><br>";


                                  $contenu=$contenu.'<br><br>Cordialement <br>'.$signature.'<br><br><hr style="float:left;"><br><br>';
                                  $email_client[]=$adr[0];
                                 self::envoi_mail($swiftTransport,$adr,$sujet,$contenu,$from, $fromname);
                                  Facture::where('id', $f->id)->update(['mail_55_env'=>1]);
                                  //dd('ok envoi au gestionnaire client');
                                  }
                                  else
                                  {
                                    //dd('En');
                                    $sujet = 'Customer invoice';
                                    /*$contenu = "Hello , <br>
                                     For the File ".$dossier->reference_medic." (including the insured: ". $prenom_ass." ".$nom_ass.", refers ". $f->reference." and the date ". $dateEmail."), your bill has not been paid yet<br>
                                    (Signed): Mail generated automatically ";*/

                            $contenu = "Hello from the accountability department of ".$entete.",<br><br>
This is a second reminder for the payment of our invoice number ".$f->reference." that we sent you on the date of ".$dateEmail." as part of the file under your reference:".$doss_ref_cus." (Our ref: ".$doss_ref." - insured: ". $prenom_ass." ".$nom_ass.")  with the amount of ".$f->montant." ".$f->devise.".<br><br>
However, until today, we did not receive any payment for this invoice that was sent 55 days ago. If you did not proceed for the payment yet, we would be grateful if you could do it as soon as possible and let us know. In case that you have already done the payment in the meantime and we did not receive it yet, please send us the details and do not take note of this email.<br><br>
Many thanks for your collaboration.<br><br>
                                    (Signed): This is an automatically generated email by the management system of Najda Assistance.(2nd reminder)
 ";
                                     $contenu=$contenu.'<br><br>cordially <br>'.$signature.'<br><br><hr style="float:left;"><br><br>';
                                       $email_client[]=$adr[0];
                                       self::envoi_mail($swiftTransport,$adr,$sujet,$contenu,$from, $fromname);
                                        Facture::where('id', $f->id)->update(['mail_55_env'=>1]);
                  
                                      }

                                            $ccea=self::return_dest_cc($adr);
                                            $ccea = implode(";", $ccea);
                                            $toea=self::return_dest_to($adr);
                                         
                                            $emaiauto=new EmailAuto ([ 
                                           'dossierid'=>$dossier->id,
                                           'dossier' =>$dossier->reference_medic,
                                           'client' =>$cli->name,
                                           'destinataire' =>$toea,
                                           'emetteur'=>$from,
                                           'cc'=>$ccea,
                                           'sujet'=>$sujet, 
                                           'contenutxt' =>$contenu,
                                           'type'=>'facture_client'                    

                                           ]);

                                          $emaiauto->save();

                             }
                             else // sinon si mail gestion n'existe pas denière adresse envoyé
                             {


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
                               $adr=$dests;

                               if($clilang=="Fr")
                            {
                            $sujet = 'Rappel facture client';
                             /*$contenu = "Bonjour ,<br>
                            Pour le dossier ".$dossier->reference_medic."(dont l'assuré : ".$prenom_ass." ".$nom_ass.", réfernce ".$f->reference." et la date ".$dateEmail."), votre facture n'est pas encore réglée<br>
                             (Signé): Mail généré automatiquement";
                            $contenu=$contenu.'<br><br>Cordialement <br>'.$signatureFinances.'<br><br><hr style="float:left;"><br><br>';*/

                            $contenu = "Bonjour du service gestion de ".$entete.",<br><br>
                           
Nous vous avons adressé en date du ". $dateEmail." dans le cadre du dossier ".$doss_ref_cus." (Notre ref:".$doss_ref." - Assuré : ".$prenom_ass." ".$nom_ass.") notre facture numéro ".$f->reference." du montant ".$f->montant." ".$f->devise."<br><br>
Or à ce jour nous n’avons toujours pas reçu votre règlement pour cette facture envoyée voilà maintenant une quarantaine de jours. Si vous ne l’avez pas encore effectué, nous vous saurions gré de bien vouloir procéder dans les meilleurs délais et nous en informer. Si toutefois vous l’avez déjà effectué entre-temps et que nous ne l’avons pas encore reçu, veuillez SVP nous en adresser les détails et ne pas tenir compte de ce mail.<br><br>
Avec nos remerciements pour votre collaboration.<br><br>
 (Signé): Ceci est un email généré automatiquement par le système de gestion de Najda Assistance";
                            $contenu=$contenu.'<br><br>Cordialement <br>'.$signature.'<br><br><hr style="float:left;"><br><br>';

                            $email_client[]=$adr[0];
                           self::envoi_mail($swiftTransport,$adr,$sujet,$contenu,$from, $fromname);
                            Facture::where('id', $f->id)->update(['mail_40_env'=>1]);
                            //dd('ok envoi au gestionnaire client');
                            }
                            else
                            {
                              $sujet = 'Customer invoice reminder';
                             /* $contenu = "Hello , <br>
                               For the File ".$dossier->reference_medic." (including the insured: ". $prenom_ass." ".$nom_ass.", refers ". $f->reference." and the date ". $dateEmail."), your bill has not been paid yet<br>
                              (Signed): Mail generated automatically ";
                               $contenu=$contenu.'<br><br>cordially <br>'.$signatureFinances.'<br><br><hr style="float:left;"><br><br>';*/

                            $contenu = "Hello from the accountability department of ".$entete.",<br><br>
We sent you on the date of". $dateEmail."  as part of the file under your reference ".$doss_ref_cus." (Our ref:".$doss_ref." - insured: ". $prenom_ass." ".$nom_ass.") our invoice number ".$f->reference." with the amount of ".$f->montant." ".$f->devise."<br><br>.
However, until today, we did not receive any payment for this invoice that was sent forty days ago. If u did not proceed for the payment yet, we would be grateful if you could do it as soon as possible and let us know. In case that you have already done the payment in the meantime and we did not receive it yet, please send us the details and do not take note of this email.<br><br>
Many thanks for your collaboration<br><br>
                              (Signed): This is an automatically generated email by the management system of Najda Assistance. ";
                               $contenu=$contenu.'<br><br>cordially <br>'.$signature.'<br><br><hr style="float:left;"><br><br>';


                                 $email_client[]=$adr[0];
                                 self::envoi_mail($swiftTransport,$adr,$sujet,$contenu,$from, $fromname);
                                  Facture::where('id', $f->id)->update(['mail_40_env'=>1]);
            
                                }

                                      $ccea=self::return_dest_cc($adr);
                                      $ccea = implode(";", $ccea);
                                      $toea=self::return_dest_to($adr);
                                   
                                      $emaiauto=new EmailAuto ([ 
                                     'dossierid'=>$dossier->id,
                                     'dossier' =>$dossier->reference_medic,
                                     'client' =>$cli->name,
                                     'destinataire' =>$toea,
                                     'emetteur'=>$from,
                                     'cc'=>$ccea,
                                     'sujet'=>$sujet, 
                                     'contenutxt' =>$contenu,
                                     'type'=>'facture_client'                    

                                     ]);

                                    $emaiauto->save();



                                } // if($arraykbs && count($arraykbs)>0)





                             } // fin si gestion n'existe pas


                         
                                 
                        }// fin mail 55 jours 

                    } // fin else avant 55
                

                 }// if mail45=0 ou mail55=0

                }// fin if f dossier
                 

            }
              else
              {
                if($dateSys70>= $dateEnvoi) // alerte le financier
                {

                   $sujet = 'Facture Client';
                   $adr='finances@najda-assistance.com';
                   //$adr='kbskhaled@gmail.com';

                   if($dateSys70 >= $dateEnvoi && $dateEnvoi > $dateSys85 )
                    {

                     if($f->mail_70_env==0)
                     {
                     $contenu = "Alerte pour le financier<br>
                    la facture du client ".$cli->name." pour le dossier ".$dossier->reference_medic." ( Assuré : ".$prenom_ass." ".$nom_ass.") n'est pas encore réglée  depuis une période qui dépasse 70 jours<br>
                    (Signé): Mail généré automatiquement";
                   
                  //$dateCreation = \DateTime::createFromFormat($p->date_prestation);
                                     
                     //$date_rapp2= \DateTime::createFromFormat($format, $date_rapp);
                // $dtc30=(new \DateTime())->modify('-30 days')->format($format);
                       $email_client[]=$adr;
                        self::envoi_mail($swiftTransport,$adr,$sujet,$contenu,$from, $fromname);
                         Facture::where('id', $f->id)->update(['mail_70_env'=>1]);

                         $ccea=self::return_dest_cc($adr);
                              $ccea = implode(";", $ccea);
                              $toea=self::return_dest_to($adr);
                             

                              $emaiauto=new EmailAuto ([ 
                             'dossierid'=>$dossier->id,
                             'dossier' =>$dossier->reference_medic,
                             'client' =>$cli->name,
                             'destinataire' =>$toea,
                             'emetteur'=>$from,
                             'cc'=>$ccea,
                             'sujet'=>$sujet, 
                             'contenutxt' =>$contenu,
                             'type'=>'Alerte_financier_facture_client'                    

                             ]);

                            $emaiauto->save();

                             $date_rapp = (new \DateTime($dateEmail))->modify('+70 days');
    
                     Facture::where('id', $f->id)->update(['date_rapp_15'=>$date_rapp]);
                     }
                  
                    }
                     else
                     {
                      if($dateSys85 > $dateEnvoi )
                      {

                        if($f->date_rapp_15)
                        {

                            $date_rapp = new \DateTime($f->date_rapp_15);
                            //$date_rapp2= \DateTime::createFromFormat($format, $date_rapp);

                            $dtc=(new \DateTime())->format($format);
                            $dateSys = \DateTime::createFromFormat($format, $dtc);

                    if( $dateSys > $date_rapp && intval($dateSys->diff($date_rapp)->format('%R%a')) > 15)
                            {

                              $contenu = "Alerte pour le financier - Rappel periodique de 15 jours <br>
                               la facture du client ".$cli->name." pour le dossier ".$dossier->reference_medic." ( Assuré : ".$prenom_ass." ".$nom_ass.") n'est pas encore réglée  depuis une période qui dépasse 70 jours<br>
                               (Signé): Mail généré automatiquement";


                               $email_client[]=$adr;
                        self::envoi_mail($swiftTransport,$adr,$sujet,$contenu,$from, $fromname);
                         Facture::where('id', $f->id)->update(['mail_70_env'=>1]);

                         $ccea=self::return_dest_cc($adr);
                              $ccea = implode(";", $ccea);
                              $toea=self::return_dest_to($adr);
                             

                              $emaiauto=new EmailAuto ([ 
                             'dossierid'=>$dossier->id,
                             'dossier' =>$dossier->reference_medic,
                             'client' =>$cli->name,
                             'destinataire' =>$toea,
                             'emetteur'=>$from,
                             'cc'=>$ccea,
                             'sujet'=>$sujet, 
                             'contenutxt' =>$contenu,
                             'type'=>'Alerte_financier_facture_client'                    

                             ]);

                            $emaiauto->save();

                              $date_rapp->modify('+15 days');
                             Facture::where('id', $f->id)->update(['date_rapp_15'=>$date_rapp]);

                            }


                        }



                      }

                     }

                 // dd("envoi alerte au financier cas client");
                        


                }

              }                 

              

///////------------------------------------------------------------------------------------------------
            
                
            }// fin if reg

        } //fin if honoraire

      } // fin foreach ($factures as $f )

   } // fin if  $factures
      

    }//fin fonction


} // fin controlleur

