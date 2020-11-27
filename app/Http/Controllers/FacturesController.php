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
use App\Facture;
use App\Adresse;
use App\Client;
use App\EmailAuto;

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
                //nejib.karoui@gmail.com
                  array_push($cc,'kbskhaledfb@gmail.com');
  
               }
                else
               {
               $to=$adresses[0] ; // null;
                //nejib.karoui@gmail.com
                  array_push($cc,'kbskhaledfb@gmail.com');
 
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

              $dateCreation=str_replace('/','-',$p->date_prestation);   
              //$dateCreation = \DateTime::createFromFormat($p->date_prestation);
              $dateCreation = new \DateTime($dateCreation);
              $doss_ref=Dossier::where('id',$p->dossier_id)->first()->reference_medic ;
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
                     Pour le dossier : ".$doss_ref.", votre facture n'est pas encore reçue depuis une période qui dépasse 30 jours<br>
                     (Signé): Mail généré automatiquement";

                    }
                    else
                    {
                    
                      if($p->mail_45_env==0 && $dateSys45 >= $dateCreation)
                        {
                          $contenu = "Bonjour,<br>
                         Pour le dossier : ".$doss_ref.", votre facture n'est pas encore reçue depuis une période qui dépasse 45 jours<br>
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
                      $presname=Prestataire::where('id',$p->prestataire_id)->first()
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
                    la facture du prestataire ". $presname." pour la prestation concernant le dossier ".$doss_ref." n'est pas encore reçue depuis une période qui dépasse 60 jours<br>
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
                    Pour le dossier ".$dossier->reference_medic.", votre facture n'est pas encore réglée<br>
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
                     For the File ".$dossier->reference_medic.", your bill has not been paid yet<br>
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
                  //nejib.karoui@gmail.com
                  array_push($cc,'nejib.karoui@gmail.com');
                  array_push($cc,'kbskhaled@gmail.com');
  
               }
                else
               {
               $to=$dests[0] ; // null;
               //nejib.karoui@gmail.com
                array_push($cc,'nejib.karoui@gmail.com');
                array_push($cc,'kbskhaled@gmail.com');
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
                  //nejib.karoui@gmail.com
                  array_push($cc,'nejib.karoui@gmail.com');
                  array_push($cc,'kbskhaled@gmail.com');
  
               }
                else
               {
               $to=$dests[0] ; // null;
               //nejib.karoui@gmail.com
                 array_push($cc,'nejib.karoui@gmail.com');
                 array_push($cc,'kbskhaled@gmail.com');
               }
    
             }

             return $cc;

    }

} // fin controlleur

