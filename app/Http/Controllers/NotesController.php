<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Entree ;
use App\Dossier ;
use DB;
use App\Note;
use Auth; 
use Illuminate\Routing\UrlGenerator;
use URL;


class NotesController extends Controller
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


    public function getNotesAjax ()
    {
         $dtc = (new \DateTime())->modify('-1 Hour')->format('Y-m-d H:i:s');
         $notes=Note::where('date_rappel','<=', $dtc)->where('user_id', Auth::user()->id)->where('affichee','!=', 1)->get();

     $output='';

   if(count($notes)!=0){
     foreach ($notes as $note) {
       //$output.='<div>'. $note->id.'</div>';
     $output.='<div class="row" style="padding: 0px; margin:0px" >'; 

    $output.=' <div class="col-md-10">
        
        <div class="panel panel-default">
        <div class="panel-heading" style=" background-color: #00BFFF">
 
           <h4 class="panel-title">';
              $output.=' <a data-toggle="collapse" href="#collapse'.$note->id.'"> '. $note->titre .'</a>';
           $output.='</h4>
        </div>';

      $output.='<div id="collapse'.$note->id.'" class="panel-collapse collapse in">';
           $output.=' <ul class="list-group" style="padding:0px; margin:0px">';
           
             $output.='<li class="list-group-item"><a  href="#">'.$note->contenu.'</a></li>';
          
         $output.=' </ul>

      </div>                                        
    </div>

  </div>

    <div class="col-md-2">
   
    </div>


  </div>';


      Note::where('id',$note->id)->update(['affichee'=>1]);
     }
 } 

     echo $output;
   

    }


    public function getNotesAjaxModal ()
    {

        $burl = URL::to("/");


         $dtc = (new \DateTime())->modify('-1 Hour')->format('Y-m-d H:i:s');
         $note=Note::where('date_rappel','<=', $dtc)->where('user_id', Auth::user()->id)->where('affichee','!=', 1)
         ->orderBy('date_rappel', 'asc')->first();

     $output='';



   if($note){
     
       //$output.='<div>'. $note->id.'</div>';

    $output='<div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 id="titleNoteModal" class="modal-title">'.$note->titre.'</h4>
          </div>
        
           <div class="modal-body">
           <p>';


     $output.='<div id="noteajax" class="row rowkbs" style="padding: 0px; margin:0px" >'; 

      $output.='<div class="col-md-2">

     <div class="dropdown" id="dropdown'.$note->id.'">
      <button class="dropbtn"><i class="glyphicon glyphicon-pencil"></i></button>
      <div class="dropdown-content">
      <a href="#">Achever</a>
      <a href="#" class="ReporterNote2" id="'.$note->id.'">Reporter</a>
      <input id="noteh'.$note->id.'" type="hidden" class="form-control" value="'.$note->titre.'" name="note"/>                                              
      </div>
    </div>
   
    </div>';

    $output.=' <div class="col-md-10">
        
        <div class="panel panel-default">
        <div class="panel-heading" style=" background-color: #00BFFF">
 
           <h4 class="panel-title">';
              $output.=' <a data-toggle="collapse" href="#collapse'.$note->id.'"> '. $note->titre .'</a>';
           $output.='</h4>
        </div>';

      $output.='<div id="collapse'.$note->id.'" class="panel-collapse collapse in">';
           $output.=' <ul class="list-group" style="padding:0px; margin:0px">';
           
             $output.='<li class="list-group-item"><a  href="#">'.$note->contenu.'</a></li>';
          
         $output.=' </ul>

      </div>                                        
    </div>

  </div>; 


  </div>';

  $output.='</p>
        </div>
        <div class="modal-footer">
          <button id="reporterHide" type="button" class="btn btn-default" >Reporter</button>
          <button id="noteOnglet" type="submit" class="btn btn-default" data-dismiss="modal">Ajouter à l\'onglet Notes</button>
        <a id="idAchever" href="'.$burl.'/SupprimerNoteAjax/'.$note->id.'" class="btn btn-default" data-dismiss="modal">Achever</a>
        </div>
        <div id="hiddenreporter">
        <br>
        <form action="'.$burl.'/ReporterNote/'.$note->id.'" method="GET">
          <center><input id="daterappelh" type="datetime-local" value="'.$dtc.'" class="form-control" style="width:50%; flow:right; display: inline-block; text-align: right;" name="daterappelNote"/>
          </center>
           <br>
          <center><button type="submit" class="btn btn-default" style="width:30%;"> OK </button><center>
          </form>
          <br>
        </div> ';


      Note::where('id',$note->id)->update(['affichee'=>1]);
     
 } 

     echo $output;
   

    }


   public function ReporterNote ($id,Request $request)
   {
      $note = Note::find($id);
     // dd($note);
      $note->update(['date_rappel'=>$request->get('daterappelNote'),'affichee'=>0]);
      return back();


   }

 public function SupprimerNote($id)
  {

      
     $note = Note::find($id);
     $note->delete();
     return back();


  }

  public function SupprimerNoteAjax ($id)
  {

      
     $note = Note::find($id);
     $note->delete();
     echo 'La note est supprimée avec succèss' ;


  }


    public function index()
    {
         $notes = Note::orderBy('created_at', 'desc')->paginate(5);
        return view('notes.index',['dossiers' => $dossiers], compact('notes'));
    }

 
 
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dossiers = Dossier::all();

        return view('notes.create',['dossiers' => $dossiers]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //dd($request->all());
        $note = new Note([
             'titre' =>trim( $request->get('titre')),
             'contenu' => trim($request->get('descrip')),
             'date_rappel'=> trim($request->get('daterappel')),
             'affichee'=>0,
             'user_id'=>auth::user()->id

        ]);

        $note->save();

        return back();
        /*return redirect('/notes')->with('success', ' ajouté avec succès');*/

    }

    public function saving(Request $request)
    {
        $note = new Note([
       //     'emetteur' => $request->get('emetteur'),
        //    'sujet' => $request->get('sujet'),
        //    'contenu'=> $request->get('contenu'),

        ]);

        $note->save();
        return redirect('/notes')->with('success', 'ajouté avec succès');

    }

    public function updating(Request $request)
    {

        $id= $request->get('dossier');
        $champ= strval($request->get('champ'));
       $val= $request->get('val');
      //  $dossier = Dossier::find($id);
       // $dossier->$champ =   $val;
        Note::where('id', $id)->update(array($champ => $val));

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
        $dossiers = Dossier::all();

       $note = Note::find($id);
        return view('notes.view',['dossiers' => $dossiers], compact('note'));

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
        $note = Note::find($id);
        $dossiers = Dossier::all();

        return view('notes.edit',['dossiers' => $dossiers], compact('note'));
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

        $note = Notes::find($id);

       // if( ($request->get('ref'))!=null) { $note->name = $request->get('ref');}
       // if( ($request->get('type'))!=null) { $note->email = $request->get('type');}
       // if( ($request->get('affecte'))!=null) { $note->user_type = $request->get('affecte');}

        $note->save();

        return redirect('/notes')->with('success', 'mise à jour avec succès');    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $note = Note::find($id);
        $note->delete();

        return redirect('/notes')->with('success', '  Supprimé avec succès');    }
}
