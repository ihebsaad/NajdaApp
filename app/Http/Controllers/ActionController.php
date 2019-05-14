<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Action;
use App\Mission;
use App\Dossier;
use App\TypeMission;
use Auth;

class ActionController extends Controller
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
         $Actions = Action::orderBy('created_at', 'desc')->paginate(5);
        return view('Actions.index', compact('Actions'));
    }


    public function TraitementAction($iddoss,$idact,$idsousact)
    {

     $Action=Action::find($idsousact);


     $act=$Action->Mission;
     
          $dossier=$act->dossier;
     $dossiers=Dossier::all();
     $typesMissions=TypeMission::get();
     $Missions=Auth::user()->activeMissions;
     $Actions=$act->Actions;
    // dd($dossier);

     return view('Actions.TraitementAction',['act'=>$act,'dossiers' => $dossiers,'typesMissions'=>$typesMissions,'Missions'=>$Missions, 'Actions' => $Actions,'Action'=>$Action], compact('dossier'));

    }

    public function TraitercommentAction(Request $request,$iddoss,$idact,$idsousact)
    {

        $input = $request->all();
        // dd($input);
       //$comment1= $request->
     
        $this->enregisterCommentaires($input,$idsousact);

        // $sousaction=SousAction::find($idsousact);
        return back();

    }
     public function TraitercommentActionAjax(Request $request,$iddoss,$idact,$idsousact)
    {

        $input = $request->all();
        // dd($input);
       //$comment1= $request->
     
        $this->enregisterCommentaires($input,$idsousact);

        // $sousaction=SousAction::find($idsousact);
        //return back();

    }

    public function EnregistrerEtAllerSuivante( $iddoss,$idact,$idsousact )

    {
       //$input = $request->all();

      // $this->enregisterCommentaires($input,$idsousact);

      

      $sact=Action::find($idsousact);
       $order=$sact->ordre;
       
     $sousactSui=Action::where("Mission_id",$idact)->where('ordre',$order+1)->first();

     if($sousactSui)
     {

        $sact->update(['statut'=> "Achevée", 'realisee' => 1]);
        $sousactSui->update(['statut'=> "Active"]);

        return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idact.'/'.$sousactSui->id);

     }
     else
    {
        $sact->update(['statut'=> "Achevée", 'realisee' => 1]);
        return back();

    }

      

    }

    public function AnnulerEtAllerSuivante ($iddoss,$idact,$idsousact)

    {

       $sact=Action::find($idsousact);
       $order=$sact->ordre;
       
       $sousactSui=Action::where("Mission_id",$idact)->where('ordre',$order+1)->first();

     if($sousactSui)
     {

        $sact->update(['statut'=> "Annulée", 'realisee' => 0]);
        $sousactSui->update(['statut'=> "Active"]);

        return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idact.'/'.$sousactSui->id);

     }
     else
    {
        $sact->update(['statut'=> "Suspendue", 'realisee' => 0]);
        return back();

    }


    }

     public function EnregistrerEtAllerPrecedente( $iddoss,$idact,$idsousact )

    {
       //$input = $request->all();

      // $this->enregisterCommentaires($input,$idsousact);

      

      $sact=Action::find($idsousact);
       $order=$sact->ordre;
    if($order>1) 
    {

     $sousactSui=Action::where("Mission_id",$idact)->where('ordre',$order-1)->first();

     if($sousactSui)
     {

        $sact->update(['statut'=> "Null", 'realisee' => 0]);
        $sousactSui->update(['statut'=> "Active"]);

        return redirect('/dossier/Mission/TraitementAction/'.$iddoss.'/'.$idact.'/'.$sousactSui->id);

     }
     else
    {
        $sact->update(['statut'=> "Achevée", 'realisee' => 1]);
        return back();

    }
       }
       else
       {

        return back();
       }

      

    }

    public function FinaliserMission ($iddoss,$idact,$idsousact)
    {


        $sact=Action::find($idsousact);
        $sact->update(['statut'=> "Achevée", 'realisee' => 1]);
        $act=Mission::find($idact);
        $act->update(['statut_courant'=> "Achevée", 'realisee' => 1]);
        return redirect('dossiers/view/'.$iddoss);
    }


   public function ReporterAction (Request $request,$iddoss,$idact,$idsousact)
   {

        $sact=Action::find($idsousact);
        $sact->update(['statut'=> "Suspendue", 'realisee' => 0,'date_report'=>$request->get('datereport')]);
        $act=Mission::find($idact);
        $act->update(['statut_courant'=> "Suspendue", 'realisee' => 0]);
        return redirect('dossiers/view/'.$iddoss);


   }


    private function enregisterCommentaires ($input,$idsousact)
    {

       $c1=false;
      $c2=false;
      $c3=false;

      if (array_key_exists("comment1",$input))
      {
           $c1=true;
            Action::where('id',intval($idsousact))
            ->update(['comment1'=> $input["comment1"]]);

      }
       if (array_key_exists("comment2",$input))
      {
            $c2=true;
            Action::where('id',intval($idsousact))
            ->update(['comment2'=>  $input["comment2"]]);

      }
       if (array_key_exists("comment3",$input))
      {
            $c3=true;
            Action::where('id',intval($idsousact))
            ->update(['comment3'=>  $input["comment3"]]);

      }

     $entree1=false;
     $entree2=false;

    if (array_key_exists("field_name",$input))
      {
          if (array_key_exists("0",$input["field_name"]))
            {
                if(!$c1)
                {
                Action::where('id',intval($idsousact))
                ->update(['comment1'=> $input["field_name"]["0"]]);

                 $c1=true;
                 $entree1=true;
                }

                if ( $c1 and ! $c2 and  ! $entree1)
                {
                Action::where('id',intval($idsousact))
                ->update(['comment2'=> $input["field_name"]["0"]]);

                 $c2=true;
                 $entree2=true;

                }

                 if (  $c1 and  $c2 and ! $c3 and  ! $entree2  )
                {
                Action::where('id',intval($idsousact))
                ->update(['comment3'=> $input["field_name"]["0"]]);

                 $c3=true;
                }
             
          
            }

             $entree2=false;
            $entree1=false;

            if (array_key_exists("1",$input["field_name"]))
            {
          


              if(! $c1)
                {
                Action::where('id',intval($idsousact))
                ->update(['comment1'=> $input["field_name"]["1"]]);

                 $c1=true;
                 $entree1=true;
                }

                if (  $c1 and ! $c2 and ! $entree1)
                {
                Action::where('id',intval($idsousact))
                ->update(['comment2'=> $input["field_name"]["1"]]);

                 $c2=true;
                 $entree2=true;
                }

                 if (  $c1 and  $c2 and ! $c3 and ! $entree2)
                {
                Action::where('id',intval($idsousact))
                ->update(['comment3'=> $input["field_name"]["1"]]);

                 $c3=true;
                }
          

            }

             $entree2=false;
            $entree1=false;
            
            if (array_key_exists("2",$input["field_name"]))
            {
          
               if(!$c1 )
                {
                Action::where('id',intval($idsousact))
                ->update(['comment1'=> $input["field_name"]["2"]]);

                 $c1=true;
                 $entree1=true;
                }

                if (  $c1 and  ! $c2 and ! $entree1 )
                {
                Action::where('id',intval($idsousact))
                ->update(['comment2'=> $input["field_name"]["2"]]);

                 $c2=true;
                 $entree2=true;
                }

                 if ( $c1 and  $c2 and ! $c3 and ! $entree2)
                {
                Action::where('id',intval($idsousact))
                ->update(['comment3'=> $input["field_name"]["2"]]);

                 $c3=true;
                }
            
          

            }



      }


    }

 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Actions = Action::all();

        return view('Actions.create',['Actions' => $Actions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $Action = new Action([
             'ref' =>trim( $request->get('ref')),
             'type' => trim($request->get('type')),
             'affecte'=> $request->get('affecte'),

        ]);

        $Action->save();
        return redirect('/Actions')->with('success', '  has been added');

    }

    public function saving(Request $request)
    {
        $Action = new Action([
       //     'emetteur' => $request->get('emetteur'),
        //    'sujet' => $request->get('sujet'),
        //    'contenu'=> $request->get('contenu'),

        ]);

        $Action->save();
        return redirect('/Actions')->with('success', 'Entry has been added');

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $Actions = Action::all();

        $Action = Action::find($id);
        return view('Actions.view',['Actions' => $Actions], compact('Action'));

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
        $Action = Action::find($id);
        $Actions = Action::all();

        return view('Actions.edit',['Actions' => $Actions], compact('Action'));
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

        $Action = Action::find($id);

        if( ($request->get('ref'))!=null) { $Action->name = $request->get('ref');}
        if( ($request->get('type'))!=null) { $Action->email = $request->get('type');}
        if( ($request->get('affecte'))!=null) { $Action->user_type = $request->get('affecte');}

        $Action->save();

        return redirect('/Actions')->with('success', '  has been updated');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Action = Action::find($id);
        $Action->delete();

        return redirect('/Actions')->with('success', '  has been deleted Successfully');  

     }
}
