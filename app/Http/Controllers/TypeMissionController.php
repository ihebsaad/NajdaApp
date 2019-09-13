<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TypeMission;
use App\Mission;
use DB;

class TypeMissionController extends Controller
{
    //
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
         $typesMissions = TypeMission::orderBy('created_at', 'desc')->paginate(5);
        return view('typesMissions.index', compact('typesMissions'));
    }


    public function getTypeMissionAjax(Request $request)
    {

        if( $request->get('qy'))
        {
          $qery=$request->get('qy');

          $data=DB::Table('type_mission')->where('nom_type_Mission','like','%'.$qery.'%')->get();

          $output='<ul class="dropdown-menu" style="display:block ; position:relative; width: 300 px; left:-50px;">';

          foreach ($data as $row ) {
              
              $output.='<li  class="resAutocompTyoeAct" ><a href="#">'.$row->nom_type_Mission.'</a></li>';
          }


           $output.='</ul>';

           echo $output ;


        }



    }

 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $typesMissions = TypeMission::all();

        return view('typesMissions.create',['typesMissions' => $typesMissions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $typeMission = new TypeMission([
             'ref' =>trim( $request->get('ref')),
             'type' => trim($request->get('type')),
             'affecte'=> $request->get('affecte'),

        ]);

        $typeMission->save();
        return redirect('/typesMissions')->with('success', '  has been added');

    }

    public function saving(Request $request)
    {
        $typeMission = new TypeMission([
       //     'emetteur' => $request->get('emetteur'),
        //    'sujet' => $request->get('sujet'),
        //    'contenu'=> $request->get('contenu'),

        ]);

        $typeMission->save();
        return redirect('/typesMissions')->with('success', 'Entry has been added');

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $typesMissions = TypeMission::all();

        $typeMission = TypeMission::find($id);
        return view('typesMissions.view',['typesMissions' => $typesMissions], compact('typeMission'));

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
        $typeMission = TypeMission::find($id);
        $typesMissions = TypeMission::all();

        return view('typesMissions.edit',['typesMissions' => $typesMissions], compact('typeMission'));
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

        $typeMission = TypeMission::find($id);

        if( ($request->get('ref'))!=null) { $typeMission->name = $request->get('ref');}
        if( ($request->get('type'))!=null) { $typeMission->email = $request->get('type');}
        if( ($request->get('affecte'))!=null) { $typeMission->user_type = $request->get('affecte');}

        $typeMission->save();

        return redirect('/typesMissions')->with('success', '  has been updated');
    }

    public function updatedesc(Request $request)
    {
        $typemission=$request->get('typemission');
        $contenu=$request->get('description');

        TypeMission::where('id', $typemission)->update(array('des_miss' => $contenu));

    }

    public function updatecharge(Request $request)
    {
        $typemission=$request->get('typemission');

        $action=$request->get('action');
        $charge=$request->get('charge');

        TypeMission::where('id', $typemission)->update(array('duree'.$action => $charge));

    }

    public function updatedescact(Request $request)
    {
        $typemission=$request->get('typemission');

        $action=$request->get('action');
        $description=$request->get('description');

        TypeMission::where('id', $typemission)->update(array('desc_action'.$action => $description));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $typeMission = TypeMission::find($id);
        $typeMission->delete();

        return redirect('/typesMissions')->with('success', '  Supprimé');

     }

    public function custom_echo($x, $length)
    {
        if(strlen($x)<=$length)
        {
            return $x;
        }
        else
        {
            $y=substr($x,0,$length) . '..';
            return $y;
        }
    }

    public   function loading(Request $request)
    {
        $id = $request->get('typemission');
        $TM =TypeMission::find($id);
        //$TM =TypeMission::where('id',$id)->get();

        $description=$TM['des_miss'];
        $output='';
        $output.='<div class="row" style="margin-bottom:15px;" ><div class="col-md-1" style="width:170px" >Description:</div><div class="col-md-6" > <textarea style="width:650px;height: 85px;" onchange="updateDesc(this)" id="desc-'.$id.'" >'.$description.'  </textarea> </div> </div><br>';

        $output.='<table class="mytable"><thead><tr><th style="width:100px">Action </th><th style="width:650px">Titre</th><th style="width:100px">Charge</th></tr></thead>';


        $output.='</td>';
         $nb_acts=$TM->nb_acts;
        for ($i=1; $i<=$nb_acts;$i++) {
            $descr='desc_action'.$i;
            $duree='duree'.$i;
            $action='action'.$i;
            $output .= '<tr><td style="width:50px">'.$i.'</td><td style="width:650px;cursor:pointer" onclick="ShowModal(this)" title="'.$TM[$descr].'" class="tdtitre" id="act-'.$i.'"> '.$this->custom_echo($TM[$action],60).'</td><td style="width:100px"><input onchange="updateCharge(this)" style="width:80px" type="number" value="'.$TM[$duree].'" id="action-'.$i.'" /></td></tr>  ';

          }

        $output .= '</table>';
        return $output;


       }

}
