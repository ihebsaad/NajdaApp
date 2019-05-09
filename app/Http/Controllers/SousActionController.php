<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SousAction;
use App\Action;
use App\Dossier;
use App\TypeAction;
use Auth;

class SousActionController extends Controller
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
         $sousactions = SousAction::orderBy('created_at', 'desc')->paginate(5);
        return view('sousactions.index', compact('sousactions'));
    }


    public function Traitementsousaction($iddoss,$idact,$idsousact)
    {

     $sousaction=SousAction::find($idsousact);
     $act=$sousaction->action;
          $dossier=$act->dossier;
     $dossiers=Dossier::all();
     $typesactions=TypeAction::get();
     $actions=Auth::user()->activeActions;
     $sousactions=$act->sousactions;
    // dd($dossier);

     return view('sous_actions.TraitementSousAction',['act'=>$act,'dossiers' => $dossiers,'typesactions'=>$typesactions,'actions'=>$actions, 'sousactions' => $sousactions,'sousaction'=>$sousaction], compact('dossier'));

    }

    public function Traitercommentsousaction(Request $request,$iddoss,$idact,$idsousact)
    {

        $input = $request->all();
        // dd($input);
       //$comment1= $request->
     
        $this->enregisterCommentaires($input,$idsousact);

        // $sousaction=SousAction::find($idsousact);
        return back();

    }
     public function TraitercommentsousactionAjax(Request $request,$iddoss,$idact,$idsousact)
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

      

      $sact=SousAction::find($idsousact);
       $order=$sact->ordre;
       
     $sousactSui=SousAction::where("action_id",$idact)->where('ordre',$order+1)->first();

     if($sousactSui)
     {

        $sact->update(['statut'=> "Achevée", 'realisee' => 1]);
        $sousactSui->update(['statut'=> "Active"]);

        return redirect('/dossier/action/Traitementsousaction/'.$iddoss.'/'.$idact.'/'.$sousactSui->id);

     }
     else
    {
        $sact->update(['statut'=> "Achevée", 'realisee' => 1]);
        return back();

    }

      

    }

    public function AnnulerEtAllerSuivante ($iddoss,$idact,$idsousact)

    {

       $sact=SousAction::find($idsousact);
       $order=$sact->ordre;
       
       $sousactSui=SousAction::where("action_id",$idact)->where('ordre',$order+1)->first();

     if($sousactSui)
     {

        $sact->update(['statut'=> "Annulée", 'realisee' => 0]);
        $sousactSui->update(['statut'=> "Active"]);

        return redirect('/dossier/action/Traitementsousaction/'.$iddoss.'/'.$idact.'/'.$sousactSui->id);

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

      

      $sact=SousAction::find($idsousact);
       $order=$sact->ordre;
    if($order>1) 
    {

     $sousactSui=SousAction::where("action_id",$idact)->where('ordre',$order-1)->first();

     if($sousactSui)
     {

        $sact->update(['statut'=> "Null", 'realisee' => 0]);
        $sousactSui->update(['statut'=> "Active"]);

        return redirect('/dossier/action/Traitementsousaction/'.$iddoss.'/'.$idact.'/'.$sousactSui->id);

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

    public function FinaliserAction ($iddoss,$idact,$idsousact)
    {


        $sact=SousAction::find($idsousact);
        $sact->update(['statut'=> "Achevée", 'realisee' => 1]);
        $act=Action::find($idact);
        $act->update(['statut_courant'=> "Achevée", 'realisee' => 1]);
        return redirect('dossiers/view/'.$iddoss);
    }


   public function Reportersousaction (Request $request,$iddoss,$idact,$idsousact)
   {

        $sact=SousAction::find($idsousact);
        $sact->update(['statut'=> "Suspendue", 'realisee' => 0,'date_report'=>$request->get('datereport')]);
        $act=Action::find($idact);
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
            SousAction::where('id',intval($idsousact))
            ->update(['comment1'=> $input["comment1"]]);

      }
       if (array_key_exists("comment2",$input))
      {
            $c2=true;
            SousAction::where('id',intval($idsousact))
            ->update(['comment2'=>  $input["comment2"]]);

      }
       if (array_key_exists("comment3",$input))
      {
            $c3=true;
            SousAction::where('id',intval($idsousact))
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
                SousAction::where('id',intval($idsousact))
                ->update(['comment1'=> $input["field_name"]["0"]]);

                 $c1=true;
                 $entree1=true;
                }

                if ( $c1 and ! $c2 and  ! $entree1)
                {
                SousAction::where('id',intval($idsousact))
                ->update(['comment2'=> $input["field_name"]["0"]]);

                 $c2=true;
                 $entree2=true;

                }

                 if (  $c1 and  $c2 and ! $c3 and  ! $entree2  )
                {
                SousAction::where('id',intval($idsousact))
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
                SousAction::where('id',intval($idsousact))
                ->update(['comment1'=> $input["field_name"]["1"]]);

                 $c1=true;
                 $entree1=true;
                }

                if (  $c1 and ! $c2 and ! $entree1)
                {
                SousAction::where('id',intval($idsousact))
                ->update(['comment2'=> $input["field_name"]["1"]]);

                 $c2=true;
                 $entree2=true;
                }

                 if (  $c1 and  $c2 and ! $c3 and ! $entree2)
                {
                SousAction::where('id',intval($idsousact))
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
                SousAction::where('id',intval($idsousact))
                ->update(['comment1'=> $input["field_name"]["2"]]);

                 $c1=true;
                 $entree1=true;
                }

                if (  $c1 and  ! $c2 and ! $entree1 )
                {
                SousAction::where('id',intval($idsousact))
                ->update(['comment2'=> $input["field_name"]["2"]]);

                 $c2=true;
                 $entree2=true;
                }

                 if ( $c1 and  $c2 and ! $c3 and ! $entree2)
                {
                SousAction::where('id',intval($idsousact))
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
        $sousactions = SousAction::all();

        return view('sousactions.create',['sousactions' => $sousactions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $sousaction = new SousAction([
             'ref' =>trim( $request->get('ref')),
             'type' => trim($request->get('type')),
             'affecte'=> $request->get('affecte'),

        ]);

        $sousaction->save();
        return redirect('/sousactions')->with('success', '  has been added');

    }

    public function saving(Request $request)
    {
        $sousaction = new SousAction([
       //     'emetteur' => $request->get('emetteur'),
        //    'sujet' => $request->get('sujet'),
        //    'contenu'=> $request->get('contenu'),

        ]);

        $sousaction->save();
        return redirect('/sousactions')->with('success', 'Entry has been added');

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $sousactions = SousAction::all();

        $sousaction = SousAction::find($id);
        return view('sousactions.view',['sousactions' => $sousactions], compact('sousaction'));

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
        $sousaction = SousAction::find($id);
        $sousactions = SousAction::all();

        return view('sousactions.edit',['sousactions' => $sousactions], compact('sousaction'));
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

        $sousaction = SousAction::find($id);

        if( ($request->get('ref'))!=null) { $sousaction->name = $request->get('ref');}
        if( ($request->get('type'))!=null) { $sousaction->email = $request->get('type');}
        if( ($request->get('affecte'))!=null) { $sousaction->user_type = $request->get('affecte');}

        $sousaction->save();

        return redirect('/sousactions')->with('success', '  has been updated');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sousaction = SousAction::find($id);
        $sousaction->delete();

        return redirect('/sousactions')->with('success', '  has been deleted Successfully');  

     }
}
