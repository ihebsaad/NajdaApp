<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Entree ;
use App\Dossier ;
use DB;
use App\Note;
use App\Note_his;
use App\User;
use App\EnvoyerNote;
use App\EnvoyerNote_his;
use auth; 
use Illuminate\Routing\UrlGenerator;
use URL;
use App\Historique;


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

    public function managenotesom  (Request $req)
    {

     // dd($req->all());

      if($req->get('id_notes'))
      {

       for($i=0; $i<count($req->get('id_notes')); $i++)
       {

         if($req->get('delete_notes')[$i]=="oui") // delete note
         {
            $note=Note::where('id',$req->get('id_notes')[$i])->first();
            $note->delete();

         }
         else// update notes
         {
            $note=Note::where('id',$req->get('id_notes')[$i])->first();
            $note->update([
              'titre'=>$req->get('titre_notes')[$i],
              'contenu'=>$req->get('contenu_notes')[$i],
              'date_rappel'=>$req->get('dates_rappel_notes')[$i],
               'user_id'=>$req->get('id_user_notes')[$i],
               'villemission' =>$req->get('villemission')[$i],

            ]);

             $note->save();

         }

       }
          return("La mise à jour est effectuée avec succès");

      }

       return("Erreur lors de la mise à jour de(s) note(s)");

   

    }

    public function getnotesom (Request $req)
    {
     
      $liste_id=trim($req->get('liste_id_notes'));
      $liste_id=explode(',',$liste_id);
      $output="";

        $output=' <form id="form_notes"><div class="modal-header">
                <h5 class="modal-title"  style="font-weight: bold"> Gestion de notes associées à l\'OM</h5>
                <div style="position: absolute; right: 15px; top: 15px;"><button type="button" class="btn btn-default btn-xs" readonly disabled id=""><i class="fas fa-tag"></i></button></div>
            </div>
            <div  class="modal-body"><table id="idtablepd" class="table table-striped table-bordered">
                                                <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Nom note</th>  
                                                    <th>Contenu note</th> 
                                                    <th>Ville mission</th> 
                                                    <th>Agent vers lequel la note est associée</th> 
                                                    <th>date rappel</th>
                                                    <th>Suppression de la note</th>                                                            
                                                </tr>
                                                </thead>
                                                <tbody>';
      $notes=Note::whereIn('id',$liste_id)->get();
      $alluser=User::get();
       $selected="";

      foreach($notes as $note)
      {

      $t_rap=date('Y-m-d\TH:i', strtotime($note->date_rappel));

      $user=User::where('id',$note->user_id)->first();
      
      $output.=' <tr>                                                 
                <td><input type="hidden" name="id_notes[]" value="'. $note->id.'"style="width:90%"></td>
                <td><input type="text" name="titre_notes[]" value="'. $note->titre.'" style="width:90%"></td>
                <td><textarea  name="contenu_notes[]"  style="width:90%">'. $note->contenu.'</textarea></td> 
                 <td><input type="text" name="villemission[]" value="'. $note->villemission.'" style="width:90%"></td> 
                  <td> 
                     <select name="id_user_notes[]" style="width:90%">';

                     foreach ( $alluser as $u) {

                      if($u->id==$note->user_id)
                      {
                        $selected="selected";
                      }
                      else
                      {
                        $selected="";
                      }

                  $output.='<option value="'.$u->id.'" '.$selected.' >'.$u->name.' '.$u->lastname.'</option>';

                     }
                    
                    $output.=' </select> </td>                
                   
                    <td><input type="datetime-local" name="dates_rappel_notes[]" value="'.$t_rap.'" style="width:90%"></td>     
                 <td><select name="delete_notes[]" style="width:90%"><option value="non" selected>non</option>  <option value="oui">oui</option> </select></td>                                                                    

            </tr>';

      }
        /* <td><input type="text" name="user_notes[]" value="'.$user->name.' '.$user->lastname.'" style="width:90%" readonly="readonly"><input type="hidden" name="id_user_notes[]" value="'.$note->user_id.'" style="width:90%" ></td>*/

        /*
         
        */
     // <input type="checkbox" name="delete_notes[]" value="true" style="width:90%">

    $output.='</tbody> </table>
            </div>          
            <div class="modal-footer">
                <button type="button" id="submit_form_notes"  class="btn btn-primary" >Enregistrer</button>
                <button type="button" id="" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div> </form>';

      return $output;

    }

     public function getNotesEnvoyeesAjax ()
    {

      $dtc = (new \DateTime())->format('Y-m-d H:i:s');
      $noteEnvoyee=EnvoyerNote::where('util_affecte',auth::user()->id)->where('date_rappel','<=', $dtc)->orderBy('date_rappel', 'asc')->orderBy('date_affectation', 'asc')->first();
       $output='';


   if( $noteEnvoyee){
       $note=Note::where('id',$noteEnvoyee->note_id)->first();
       $user=User::where('id',$note->emetteur_id)->first();
    
        if($noteEnvoyee->forceDelete())
        {

        $output='La note :  '.$note->titre.' | '.$note->contenu.', est vous a envoyée  par :'.$user->name.' '.$user->lastname;
        }
        else
        {
           $noteEnvoyee->forceDelete();
           $output='La note : '.$note->titre.' | '.$note->contenu.' est envoyée à vous par :'.$user->name.' '.$user->lastname;

        }

      //Note::where('id',$note->id)->update(['affichee'=>1]);
   
     } 

     return $output;
   

    }


    public function getNotesReporteesAjax ()
    {
         $dtc = (new \DateTime())->format('Y-m-d H:i:s');
         $noteReportee=Note::where('date_rappel','<=', $dtc)->where('statut','reportee')->where('user_id', auth::user()->id)->orderBy('date_rappel', 'asc')->first();

         // vérifier que ce note n'est pas dans la table envoyerNote 
         

     $output='';

   if($noteReportee){

       $en=EnvoyerNote::where('note_id',$noteReportee->id)->first();

      if(!$en)
      {
       $output='Une nouvelle note est activée : '.$noteReportee->titre.' | '.$noteReportee->contenu;
      }


      $noteReportee->update(['statut'=>'active']);
   
     } 

     return $output;
   

    }

     public function store(Request $request)
    {


      //titreNote
      //descripNote
      //daterappelNote
     
      
      $user='';
      $user_destin='';

    if (!$request->get('EnvoyerNoteId') )
    {
       $user='moi';
       $user_destin=auth::user()->id;
    }
    else
    {

         if( $request->get('EnvoyerNoteId')== auth::user()->id)
         {
           $user='moi';
           $user_destin=auth::user()->id;
         }
         else
         {
           $user='autre';
           $user_destin=trim($request->get('EnvoyerNoteId'));
         }
    }
    //return   $user;


        $format="Y-m-d\TH:i";
        $dtc = (new \DateTime())->format('Y-m-d\TH:i');       
        $dateSys  = \DateTime::createFromFormat($format, $dtc);
        $dateNote  = \DateTime::createFromFormat($format, $request->get('daterappelNote'));          

       if($dateNote>$dateSys)
       {       
        $statut='reportee';        
       }
       else
       {
        $statut='active';
       }



     $note = new Note([

        'titre'=>trim($request->get('titreNote')),
        'contenu'=>trim($request->get('descripNote')), 
        'statut'=>$statut,
        'date_rappel'=>trim($request->get('daterappelNote')),
        'user_id'=>$user_destin,
        'emetteur_id'=> auth::user()->id,
        'originUser_id'=>auth::user()->id,
        'nommission'=>trim($request->get('nommission')),
        'villemission'=>trim($request->get('villemission')),

      ]);

    $note->save();


      if($user=='autre')// envoie note
      {
         $noteenvoie= new EnvoyerNote([
         'util_affecteur'=>auth::user()->id, 
         'util_affecte'=>$user_destin,
         'note_id'=>$note->id,
         'date_affectation'=> $dtc,        
         'date_rappel'=>$request->get('daterappelNote'),
         'statut'=>$statut

         ]);
         if($noteenvoie->save())
         {
          $envoyehis=new EnvoyerNote_his($noteenvoie->toArray());
          $envoyehis->save();
         }



      }

      return $dtc;


    }


    public function getNotesAjaxModal ()
    {

        $burl = URL::to("/");


         $dtc = (new \DateTime())->format('Y-m-d H:i:s');
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
      $note->update(['date_rappel'=>$request->get('daterappelNote'),'statut'=>'reportee']);
      return back();


   }

 public function SupprimerNote($id)
  {

      
     $note = Note::find($id);
     $nh= new Note_his($note->toArray());

     if($nh->save())
     {
        $nh->update(['note_id'=>$note->id]);
        $note->delete();
     }
    
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

        return redirect('/notes')->with('success', '  Supprimé  ');    }

public function getAjaxUsersNote($idnote)
{
    $output='';

     $note=Note::where('id',$idnote)->first();

     $output.='<form  id="idFormUsersNote" method="get" action="">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModal2">Envoi d\'une note </h5>

            </div>
            <div class="modal-body">
                <div class="card-body">

                    <div class="form-group">'.
                                                
                            csrf_field() .'
                          
                            <input id="EnvoyerNoteid" name="EnvoyerNoteid" type="hidden" value="'.$idnote.'">

                            <input id="affecteurNote" name="affecteurNote" type="hidden" value="'. Auth::user()->id.'">
                           

                            <div class="form-group " >
                                <div class=" row  ">
                                    <div class="form-group mar-20">
                                        <label for="agent" class="control-label" style="padding-right: 20px">Agent </label>
                                        <select id="NoteAgent" name="NoteAgent" class="form-control select2" style="width: 230px">
                                            <option value="Select">Selectionner</option>';
                                              $agents = User::get(); 
                                              $agentname='';
                                                foreach ($agents as $agt){
                                                  if($agt->isOnline())
                                                  {
                                                 if (!empty ($agentname)) { 
                                                 if ($agentname["id"] == $agt["id"]) {
                                               $output.=' <option value="'. $agt["id"] .'" selected >'. $agt["name"].' '.$agt["lastname"].'</option>';
                                                }
                                                else
                                                {
                                                 $output.=' <option value="'.$agt["id"] .'" >'. $agt["name"].' '.$agt["lastname"] .'</option>';
                                                }
                                               
                                                
                                                
                                                }
                                                else
                                                  { $output.= '<option value="'.$agt["id"] .'" >'.$agt["name"].' '.$agt["lastname"].'</option>';}
                                              }
                                                
                                               }   
                                       $output.= ' </select>
                                    </div>
                                </div>
                            </div>
                      

                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" id="BoutonEnvoyerNote" class="BoutonEnvoyerNote btn btn-secondary">Envoyer la note</button>
            </div>
        </div>
          </form>';

          return $output;

}

      public function EnvoyerNote (Request $request)
      {
        
            /*"EnvoyerNoteid" => "envoyer8"
              "affecteurNote" => "9"
              "NoteAgent" => "Select"
            "EnvoyerNoteid" => "8"
              "affecteurNote" => "9"
              "NoteAgent" => "Select"

             */
       $output='';
        $format="Y-m-d\TH:i";
        $dtc = (new \DateTime())->format('Y-m-d\TH:i');       
        $dateSys  = \DateTime::createFromFormat($format, $dtc);
       // $dateNote  = \DateTime::createFromFormat($format, $request->get('daterappelNote')); 

      if($request->get('NoteAgent')!= 'Select' && $request->get('NoteAgent') )// envoie note
      {
         $noteenvoie= new EnvoyerNote([
         'util_affecteur'=>auth::user()->id, 
         'util_affecte'=>trim($request->get('NoteAgent')),
         'note_id'=>trim($request->get('EnvoyerNoteid')),
         'date_affectation'=> $dtc,        
         'date_rappel'=>$dtc,
         'statut'=>'active'

         ]);
         if($noteenvoie->save())
         {
          $envoyehis=new EnvoyerNote_his($noteenvoie->toArray());
          $envoyehis->save();
         }

         $c=Note::where('id',$request->get('EnvoyerNoteid'))->first();
         $c->update(['user_id'=>$request->get('NoteAgent')]);
         $c->update(['emetteur_id'=>auth::user()->id]);

            $output="la note est envoyée";

      }
      else
      {
        $output="Vous devez sélectionner un utilisateur";
      }

     return  $output;

    }

}
