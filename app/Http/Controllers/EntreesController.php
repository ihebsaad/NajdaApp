<?php

namespace App\Http\Controllers;

use App\Notifications\Notif_Suivi_Doss;
use App\Parametre;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Entree ;
use App\User ;
use App\Dossier ;
use App\Attachement ;
 use DB;
use Illuminate\Support\Facades\Auth;
use App\Notification;
use PDF;
use Illuminate\Support\Facades\Notification as Notification2;
use App\Notif ;

use PHPUnit\Framework\Exception;


class EntreesController extends Controller
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

    // Statut entrées :    3 = archivé= | 1 = affecté à un dossier |
    public function index()
    {
        //
        $entrees = Entree::orderBy('id', 'desc')->where('statut','<','2')->paginate(10000000);
        $dossiers = Dossier::all();

        return view('entrees.index',['dossiers' => $dossiers], compact('entrees'));

    }

    public function dispatching()
    {
        //
        $entrees = Entree::orderBy('id', 'desc')
            ->where('statut','<','2')
            ->where('dossier','=','')
            ->paginate(10000000);

        $dossiers = Dossier::orderBy('id', 'desc')->where('statut','<','2');

        return view('entrees.dispatching',['dossiers' => $dossiers], compact('entrees'));

    }


    public function boite()
    {
       // Log::info('Accès à la boite des entrées - utilisateur: Mounir Tounsi');

        $entrees = Entree::orderBy('created_at', 'desc')->where('statut','<','2')->paginate(10);
        $dossiers = Dossier::all();

        return view('entrees.boite',['dossiers' => $dossiers], compact('entrees'));

    }


    public function archive()
    {
        // Log::info('Accès à la boite des entrées - utilisateur: Mounir Tounsi');

        $entrees = Entree::orderBy('created_at', 'desc')->where('statut','=','3')->paginate(10);
        $dossiers = Dossier::all();

        return view('entrees.archive',['dossiers' => $dossiers], compact('entrees'));

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        $dossiers = Dossier::all();

        return view('entrees.create',['dossiers' => $dossiers]);
    }



    public function store(Request $request)
    {
        $entree = new Entree([
            'destinataire' => trim($request->get('destinataire')),
            'sujet' => trim($request->get('sujet')),
            'contenu'=>trim( $request->get('contenu')),

        ]);

        $entree->save();
        return redirect('/entrees')->with('success', 'Entry has been added');

    }

    public function saving(Request $request)
    {
        $entree = new Entree([
            'emetteur' => trim($request->get('emetteur')),
            'sujet' => trim($request->get('sujet')),
            'contenu'=> trim($request->get('contenu')),

        ]);

        $entree->save();
        return redirect('/entrees')->with('success', 'Entry has been added');

    }

    public function savecomment(Request $request)
    {
        if ($request->get('entree') != null)
        {  
            $identree = $request->get('entree');
            $comm  = $request->get('commentaire');
            //$entree = Entree::where(['id' => $identree])->first();
            Entree::where('id', $identree)->update(['commentaire' => $comm]);
            /*$entree->commentaire = $request->get('commentaire');
            $entree->save();*/
            return redirect('/entrees')->with('success', 'Entry has been saved');
        }

    }

    public function view($id)
    {
        $dossiers = Dossier::all();

        $entree = Entree::find($id);
        return view('entrees.view',['dossiers' => $dossiers], compact('entree'));

    }


    public function show($id)
    {
        $dossiers = Dossier::all();
        $entree = Entree::find($id);
        if ($entree->viewed==0 )
        {
            $entree->viewed=1;
            $date=date('d/m/Y H:i:s');
            Notif::where('entree',$id)->update(array( 'read_at'=> $date )) ;

        }
        $refdoss = trim($entree->dossier);
        $entree->save();
        $dossier = Dossier::where('reference_medic','=',$refdoss)->first();

        //$dossier=compact($dossier);
        return view('entrees.show',['dossiers' => $dossiers, 'dossier' => $dossier], compact('entree'));

    }


    public function showdisp($id)
    {
        $dossiers = Dossier::all();
        $entree = Entree::find($id);
        if ($entree->viewed==0 )
        {
           // $this->export_pdf($id);
            $entree->viewed=1;
            $date=date('d/m/Y H:i:s');
            Notif::where('entree',$id)->update(array( 'read_at'=> $date )) ;
        }
        $refdoss = trim($entree->dossier);
        $entree->save();
        $dossier = Dossier::where('reference_medic','=',$refdoss)->first();

        //$dossier=compact($dossier);
        return view('entrees.showdisp',['dossiers' => $dossiers, 'dossier' => $dossier], compact('entree'));

    }

    public function edit($id)
    {
        //
        $entrees = Entree::find($id);
        $dossiers = Dossier::all();

        return view('entrees.edit',['dossiers' => $dossiers], compact('entree'));
    }



    public function update(  $id)
    {


        $entree = Entree::find($id);
       // $entree->titre = $request->get('titre');
        //$entree->share_price = $request->get('share_price');
       // $entree->share_qty = $request->get('share_qty');
        $entree->save();

        return redirect('/entrees')->with('success', '  has been updated');
    }


    public static function archiver( $id)
    {

        $entree = Entree::find($id);
         $entree->statut = 3;  // 3 = archivé
          $entree->save();

        return redirect('/entrees/dispatching')->with('success', '  Archivé');
    }


    public static function spam( $id)
    {

        $entree = Entree::find($id);
        $entree->statut = 5;  // 5 = Spam
        $entree->save();

        return redirect('/entrees/dispatching')->with('success', '  Marqué comme SPAM');
    }


    public static function traiter( $id)
    {
      /*  $idnotif=0;
        $notifid = Notification::whereRaw('JSON_CONTAINS(data, \'{"Entree":{"id": '.$id.'}}\')')->get(['id']);
       if($notifid!=null) {$idnotif = array_values($notifid['0']->getAttributes());}
       if($idnotif>0) {
           $idnotification = $idnotif['0'];

           $notif = Notification::find($idnotification);

           $notif->statut = 1;
           $notif->save();
       }*/
      $notif=Notif::where('entree',$id)->first();
        $notif->affiche=1; // Marquer comme traitée
        $notif->save();

        $entree = Entree::find($id);
        $entree->notif=1; //traitée
        $dossid=$entree->dossierid;
        $entree->save();

        if( ( $entree->type=='email')||( $entree->type=='sms'))
        { app('App\Http\Controllers\EntreesController')->export_pdf($id);}

      //  return redirect('/home')->with('success', '  Traité');
        if($dossid >0) {return redirect('/dossiers/view/'.$dossid.'#tab2');}
        else{
            return back();
         }

    }


    public function destroy($id)
    {


        $entree = Entree::find($id);
        $entree->delete();

        // supprimer notif
        $notif=Notif::where('entree',$id)->first();
        $notif->delete();




        /*  $notifid = Notification::whereRaw('JSON_CONTAINS(data, \'{"Entree":{"id": '.$id.'}}\')')->get(['id']);
          $idnotif = array_values($notifid['0']->getAttributes());
          $idnotification=$idnotif['0'];

          $notif = Notification::find($idnotification);

          $notif->delete();*/



        return redirect('/entrees')->with('success', '  Supprimé');
    }

    public static function countarchives()
    {
        $par=Auth::id();

        $count = DB::table('entrees')
            ->where('statut','=',3)
          //  ->where('par','=',$par)
            ->count();

        return $count;

    }


    public function export_pdf($id)
    {
        // Fetch all customers from database

        $entree = Entree::find($id);
          compact('entree');
        // Send data to the view using loadView function of PDF facade
        $pdf = PDF::loadView('entrees.pdf', ['entree' => $entree])->setPaper('a4', '');

        $path= storage_path()."/Emails/";

        if (!file_exists($path.$id)) {
            mkdir($path.$id, 0777, true);
        }
        $filename=$entree->sujet;
        $name=  preg_replace('/[^A-Za-z0-9 _ .-]/', '', $filename);
        $name='REC - '.$name;

        // If you want to store the generated pdf to the server then you can use the store function
        $pdf->save($path.$id.'/'.$name.'.pdf');

        $path2='/Emails/'.$id.'/'.$name.'.pdf';

        $attachement = new Attachement([

            'type'=>'pdf','path' => $path2, 'nom' => $name,'boite'=>3,'entree_id'=>$id,'parent'=>$id,
        ]);
        $attachement->save();

    }



    public function pdf($id)
    {
        $entree = Entree::find($id);
        return view('entrees.pdf', ['entree' => $entree], compact('entree'));

    }

    public static function ChampById($champ,$id)
    {
        $entree = Entree::find($id);
        if (isset($entree[$champ])) {
            return $entree[$champ] ;
        }else{return '';}

    }

    public static function GetParametre($clientid)
    {


        $langue = app('App\Http\Controllers\ClientsController')->ClientChampById('langue1',$clientid);

        $message = Parametre::find(1);

        if ($langue=='anglais') {
            return $message['accuse2'];
        }else{
            return $message['accuse1'];
        }

     }

    public   function dispatchf(Request $request)
    {
        $identree   =$request->get('entree');
        $dossier  =trim($request->get('dossier'));
        $iddossier  =$request->get('iddossier');

        $entree = Entree::find($identree);

        //$iddossier = app('App\Http\Controllers\DossiersController')->IdDossierByRef($dossier);
        $userid = app('App\Http\Controllers\DossiersController')->ChampById('affecte', $iddossier);

        $first=false;
        // vérifier si c'est la premier dispatché la première fois
        if($entree->dossier==null)
        {
            $first=true;

            $entree->dossier=$dossier;
            if($iddossier>0){$entree->dossierid=$iddossier;}
            $entree->save();

       // supression notif from dispatcheur
            $seance =  DB::table('seance')
                ->where('id','=', 1 )->first();

            $disp=$seance->dispatcheur;
            $userD = User::find($disp);

            $count=Notification::where('notifiable_id', $disp)
                      ->where('dossier',NULL)
                ->where('entree',  $identree)
                ->count();
            // Supprimer la notification de dispatcheur
            if($count >0){$userD->notifications()->whereRaw('JSON_CONTAINS(data, \'{"Entree":{"id": '.$identree.'}}\')')->delete();}

            if($userid  >0) {

               // $user->notify(new Notif_Suivi_Doss($entree));
                 Notification2::send(User::where('id',$userid)->first(), new Notif_Suivi_Doss($entree));

            }

        }else{
           if($first==false)
            {
                $entree = Entree::find($identree);
                $doss=  $entree->dossier;

                $iddossier0 = app('App\Http\Controllers\DossiersController')->IdDossierByRef($doss);
                $userid0 = app('App\Http\Controllers\DossiersController')->ChampById('affecte', $iddossier0);

                $entree->dossier=$dossier;
             if($iddossier>0){ $entree->dossierid=$iddossier;}

            $entree->save();


                $user = User::find($userid0);

                $user->notifications()->whereRaw('JSON_CONTAINS(data, \'{"Entree":{"id": '.$identree.'}}\')')->delete();



                $iddossier2 = app('App\Http\Controllers\DossiersController')->IdDossierByRef($doss);
                $userid2 = app('App\Http\Controllers\DossiersController')->ChampById('affecte', $iddossier2);


                if($userid  >0) {
                $user = User::find($userid);
             //   $user->notify(new Notif_Suivi_Doss($entree));
                Notification2::send(User::where('id',$userid)->first(), new Notif_Suivi_Doss($entree));

            }

            }


       }

        // Activer le dossier
        Dossier::where('id',$iddossier)->update(array('current_status'=>'actif'));

       return url('/entrees/show/'.$identree);
      // if($first) {return url('/entrees/dispatching/');}
      // else{return url('/entrees/');}

    }


    public   function dispatchf2(Request $request)
    {
        $identree = $request->get('entree');
        $dossier = trim($request->get('dossier'));
        $iddossier = $request->get('iddossier');
        $entree = Entree::find($identree);

        // agent lequel le dossier est affecté
        $userid = app('App\Http\Controllers\DossiersController')->ChampById('affecte', $iddossier);

        $nomassure = app('App\Http\Controllers\DossiersController')->FullnameAbnDossierById( $iddossier);

        $entree->dossier=$dossier;
        if($iddossier>0){$entree->dossierid=$iddossier;}
        $entree->save();
        // dossier affecté à un agent
        if($userid >0)
        {   // afficher la notification pour l 'agent de dossier et rendre nouvelle (affiche 0, read_at null) dispatché (statut 1)
            Notif::where('entree',$identree)->update(array('user'=>$userid,'affiche'=>0,'statut'=>1,'read_at'=> null,'dossierid'=>$iddossier,'refdossier'=>$dossier,'nomassure'=>$nomassure)) ;
        }
        else{
            // dossier non affecté à un agent
            $seance =  DB::table('seance')
                ->where('id','=', 1 )->first();
            $disp=$seance->dispatcheur;

            // afficher la notification pour le dispatcheur et rendre nouvelle (affiche 0, read_at null) dispatché (statut 1)
            Notif::where('entree',$identree)->update(array('user'=>$disp,'affiche'=>0,'statut'=>1,'read_at'=> null,'dossierid'=>$iddossier,'refdossier'=>$dossier,'nomassure'=>$nomassure)) ;

        }

        // Activer le dossier
        Dossier::where('id',$iddossier)->update(array('current_status'=>'actif'));

        return url('/entrees/show/'.$identree);



    }




        public static function countnotifs()
    {

     $count=Entree::where('dossier','')
     ->count();

        return $count;

    }


    public static function countnotifsorange()
    {

        $dtc = (new \DateTime())->modify('-5 minutes')->format('Y-m-d\TH:i');
        $dtc2 = (new \DateTime())->modify('-10 minutes')->format('Y-m-d\TH:i');

      /*  $count=Notification::where('read_at', null)
            //      ->where('dossier','')
            ->where('created_at','<=', $dtc)
            ->where('created_at','>', $dtc2)
            ->count();*/

           $count=Notif::where('read_at', null)
              //      ->where('dossier','')
              ->where('created_at','<=', $dtc)
              ->where('created_at','>', $dtc2)
              ->count();


        return $count;

    }



    public static function countnotifsrouge()
    {

        $dtc = (new \DateTime())->modify('-10 minutes')->format('Y-m-d\TH:i');

  /*          $count=Notification::where('read_at', null)
           //      ->where('dossier','')
            ->where('created_at','<=', $dtc)
            ->count();
*/
        $count=Notif::where('read_at', null)
            //      ->where('dossier','')
            ->where('created_at','<=', $dtc)
            ->count();

        return $count;

    }


    public function AjoutCompteRendu(Request $request)
    {
        $par=Auth::id();
        $user = User::find($par);

        $iddossier=$request->get('dossier') ;
        $contenu=$request->get('contenu') ;
        $refdoss=$request->get('refdossier') ;
        $emetteur=$request->get('emetteur') ;

       // $refdoss = app('App\Http\Controllers\DossiersController')->RefDossierById($iddossier);

        $nomuser=$user->name.' '.$user->lastname;

        $userid = app('App\Http\Controllers\DossiersController')->ChampById('affecte', $iddossier);
        $nomassure = app('App\Http\Controllers\DossiersController')->FullnameAbnDossierById(  $iddossier);

        $user2 = User::find($userid);
        $nomuser2='';
        if($userid  >0) {
            $nomuser2=$user2->name.' '.$user2->lastname;

        }

        $date=date('Y-m-d H:i:s.u');


        $entree = new Entree([
            'destinataire' => $nomuser2,
            'emetteur' => $emetteur ,
            'mailid'=> 'CR-'.date('d-m-Y-H-i-s'),
            'sujet' =>  'Compte Rendu écrit par '.$nomuser,
            'contenu'=> $contenu ,
            'reception'=> $date,
            'type'=> 'tel',
            'viewed'=>0,
            'dossier'=>$refdoss,
            'dossierid'=>$iddossier,

        ]);

        $entree->save();$id=$entree->id;

        if($userid  >0) {
          //  $user2->notify(new Notif_Suivi_Doss($entree));
           //// Notification2::send(User::where('id',$userid)->first(), new Notif_Suivi_Doss($entree));

            if($id>0) {
                $notif = new Notif([
                    'emetteur' =>$emetteur,
                    'sujet' =>'Compte Rendu écrit par '.$nomuser,
                    'reception' => $date,
                    'type' => 'tel',
                    'refdossier' => $refdoss,
                    'affiche' => 0, // traitée ou non
                    'dossierid' => $iddossier,
                    'nomassure' => $nomassure,
                    'statut' => 1,  //dispatchée ou non
                    'entree' => $id,
                    'user' => $userid

                ]);
                $notif->save();
            }
        }else{


            if($id>0) {

                $seance =  DB::table('seance')
                    ->where('id','=', 1 )->first();
                $disp=$seance->dispatcheur ;
                $notif = new Notif([
                    'emetteur' =>$emetteur,
                    'sujet' =>'Compte Rendu écrit par '.$nomuser,
                    'reception' => $date,
                    'type' => 'tel',
                    'refdossier' => $refdoss,
                    'affiche' => 0, // traitée ou non
                    'dossierid' => $iddossier,
                    'nomassure' => $nomassure,
                    'statut' => 1,  //dispatchée ou non
                    'entree' => $id,
                    'user' => $disp

                ]);
                $notif->save();
            }

        }

        // Activer le dossier
        Dossier::where('id',$iddossier)->update(array('current_status'=>'actif'));


        Log::info('Création Compte Rendu - Par :'.$nomuser.' - Dossier : '.$refdoss);

    }




}
