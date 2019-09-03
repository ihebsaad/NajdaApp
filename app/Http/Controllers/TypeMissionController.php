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

        return redirect('/typesMissions')->with('success', '  has been updated');    }

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
}
