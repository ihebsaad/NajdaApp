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
  
               }
                else
               {
               $to=$adresses ; // null;
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
  
             }
                    

    }

 public static function envoi_mail_automatique_factures()    
    {
        $contenu='';
        $id_prest_test=array(10000,11000);
        $id_fact_test=array(22,23);
        $email_prestataires=array();
        $email_client=array();
         
      $factures=Facture::where('honoraire',1)->whereIn('id',$id_fact_test)->whereNotNull('date_email')->get();
     // dd($factures);
     // $prestations=Prestation::whereNotNull('date_prestation')->where('parvenu','<>',1)->get();
     $prestations=Prestation::where('parvenu','<>',1)->whereNotNull('date_prestation')->whereIn('prestataire_id',$id_prest_test)->get();
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
      $fromname="Najda Assistance";
      $from='24ops@najda-assistance.com';

      // instancier swift for fiances

      $swiftTransport =  new \Swift_SmtpTransport( 'ssl0.ovh.net', '587', '');
      $swiftTransport->setUsername('finances@najda-assistance.com');
      $swiftTransport->setPassword($parametres->pass_Finances);
      $fromname="Najda Assistance";
      $from='24ops@najda-assistance.com';

      $x=1/0;
   
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
                     $contenu = "Bonjour de Najda,<br>
                     Pour le dossier : ".$doss_ref.", votre facture n'est pas encore reçue depuis une période qui dépasse 30 jours<br>
                     (Signé): Mail généré automatiquement";

                    }
                    else
                    {
                    
                      if($p->mail_45_env==0 && $dateSys45 >= $dateCreation)
                        {
                          $contenu = "Bonjour de Najda,<br>
                         Pour le dossier : ".$doss_ref.", votre facture n'est pas encore reçue depuis une période qui dépasse 45 jours<br>
                        (Signé): Mail généré automatiquement";
                       }

                    }
                

                   $sujet = 'Facture Prestataire';
                 
                  $contenu=$contenu.'<br><br>Cordialement <br> Najda <br><br><hr style="float:left;"><br><br>';

                  if($adr && count($adr)>0)
                   {
                    //dd('ok envoi au prestataire ');
                    //
                     //dd('ok envoi au prestataire ');
                     if($dateSys30>= $dateCreation && $dateSys45 < $dateCreation && $p->mail_30_env==0 )
                            {
                              $email_prestataires[]=$adr[0];
                              /*self::envoi_mail($swiftTransport,$adr,$sujet,$contenu,$from, $fromname);
                              Prestation::where('id', $p->id)->update(['mail_30_env'=>1]);*/
                            }
                            else
                            {
                                 if($dateSys45 >= $dateCreation && $p->mail_45_env==0)
                                 {
                                   $email_prestataires[]=$adr[0];
                               /*self::envoi_mail($swiftTransport,$adr,$sujet,$contenu,$from, $fromname);
                                Prestation::where('id', $p->id)->update(['mail_45_env'=>1]);*/
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
                    //$adr='finances@medicmultiservices.com';
                    $adr='kbskhaled@gmail.com';

                    //dd("envoi alerte au financier cas prestataire");
                    $contenu = "Alerte pour le financier<br>
                    la facture du prestataire pour la prestation concernant le dossier ".$doss_ref." n'est pas encore reçue depuis une période qui dépasse 60 jours<br>
                    (Signé): Mail généré automatiquement";
                    
                    $email_prestataires[]=$adr[0];
                    /*self::envoi_mail($swiftTransport2,$adr,$sujet,$contenu,$from, $fromname);
                    Prestation::where('id', $p->id)->update(['mail_60_env'=>1]);*/
                    //adresse financier
                    //$adr= fianancier

                }

              }


        }
    }
   // dd('fin envoi mail au prestataire');
    $testadr=array(4364,4371);
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
                
                $adr=Adresse::where('parent',$dossier->customer_id)->where('nature','gestion')->whereIn('id', $testadr)->pluck('mail')->toArray();
                //dd($adr);
                $clilang=null;
                 if($adr && count($adr)>0)
                   {

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
                     $contenu = "Bonjour de Najda,<br>
                    Pour le dossier ".$dossier->reference_medic.", votre facture n'est pas encore réglée<br>
                     (Signé): Mail généré automatiquement";
                    $contenu=$contenu.'<br><br>Cordialement <br> Najda <br><br><hr style="float:left;"><br><br>';
                    $email_client[]=$adr[0];
                  /* self::envoi_mail($swiftTransport,$adr,$sujet,$contenu,$from, $fromname);
                    Facture::where('id', $f->id)->update(['mail_30_env'=>1]);*/
                    //dd('ok envoi au gestionnaire client');
                    }
                    else
                    {
                         $sujet = 'Customer invoice';
                     $contenu = "Hello from Najda, <br>
                     For the File ".$dossier->reference_medic.", your bill has not been paid yet<br>
                      (Signed): Mail generated automatically ";
                    $contenu=$contenu.'<br><br>cordially <br> Najda <br><br><hr style="float:left;"><br><br>';
                    $email_client[]=$adr[0];
                   /*self::envoi_mail($swiftTransport,$adr,$sujet,$contenu,$from, $fromname);
                    Facture::where('id', $f->id)->update(['mail_30_env'=>1]);*/
                   //dd('ok send to the manager of customer');



                    }
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
                    $adr='finances@medicmultiservices.com';

                     $contenu = "Alerte pour le financier<br>
                    la facture du client ".$cli->name." pour le dossier ".$dossier->reference_medic." n'est pas encore réglée  depuis une période qui dépasse 45 jours<br>
                    (Signé): Mail généré automatiquement";

                 // dd("envoi alerte au financier cas client");
                        $email_client[]=$adr[0];
                       // self::envoi_mail($swiftTransport2,$adr,$sujet,$contenu,$from, $fromname);
                           // Facture::where('id', $f->id)->update(['mail_45_env'=>1]);
                      


                }

              }// fin else

                
            }// fin if reg

        } //fin if honoraire

      } // fin foreach ($factures as $f )

   } // fin if  $factures
      



    }//fin fonction

 


}

