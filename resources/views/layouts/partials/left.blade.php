
            
<link rel="stylesheet" href="{{ URL::asset('resources/assets/css/alertify.css') }}">
<link rel="stylesheet" href="{{ URL::asset('resources/assets/css/alertify-bootstrap.css') }}">
<style>
.dropbtn {
  background-color: #4CAF50;
  color: white;
  padding: 12px;
  font-size: 12px;
  border: none;
  cursor: pointer;
}

.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f9f9f9;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

.dropdown-content a:hover {background-color: #f1f1f1}

.dropdown:hover .dropdown-content {
  display: block;
}

.dropdown:hover .dropbtn {
  background-color: #3e8e41;
}
</style>

            <div class="panel panel-default"  id="notificationspanel">
                                <div class="panel-heading" id="headernotifs">
                                    <h4 class="panel-title">Notifications</h4>
                                    <span class="pull-right">
                                       <i class="fa fa-fw clickable fa-chevron-up"></i>
                                        
                                         
                                    </span>
                                </div>
                                <div  class="panel-body" style="display: block;">
                                    <ul id="tabskbs" class="nav nav-tabs" style="margin-bottom: 15px;">
                                        <li class="active">
                                            <a href="#notificationstab" data-toggle="tab">Notifs</a>
                                        </li>
                                        <li>
                                            <a id="idnotestab" href="#notestab" data-toggle="tab">Notes</a>
                                        </li>
                                     
                                    </ul>
                                    <div id="NotificationsTabContent" class="tab-content">
                                        <div class="tab-pane fade active in  scrollable-panel" id="notificationstab">
                                            <div class="row" style="width: 99%">
                                               <div class="col-xs-9 col-md-9 align-left"> 
                                                    <div class="select">
                                                      <select>
                                                        <option>Trier par</option>
                                                        <option>Temps</option>
                                                        <option>Dossier</option>
                                                      </select>
                                                      <div class="select__arrow"></div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-1 col-md-1 pull-right"> 
                                                    <a href="#" class="btn btn-default btn-sm btn-responsive" role="button" id='tte'> </a>
                                                </div>
                                                <div class="col-xs-1 col-md-1 pull-right"> 
                                                    <a href="#" class="btn btn-success btn-sm btn-responsive" role="button"> </a>
                                                </div>
                                                <div class="col-xs-1 col-md-1 pull-right"> 
                                                    <a href="#" class="btn btn-danger btn-sm btn-responsive" role="button"> </a>
                                                </div>
                                            </div>
                                            @php
                                            {{ //print_r(config('commondata.dossiers')); 
                                            }}
                                            @endphp
                                            <!-- treeview of notifications -->
                                            <div id="jstree">
                                              <ul>
                                                <!-- in this example the tree is populated from inline HTML -->
                                                <!--<ul>
                                                  <li >Root node 1
                                                    <ul>
                                                      <li id="child_node_1" type="demo">Child node 1</li>
                                                      <li id="D123" type="foldernotifs">Child node 2</li>
                                                    </ul>
                                                  </li>
                                                  <li>Root node 2</li>
                                                </ul>

                                                <button id="btntree">demo button</button>-->
                                                 @php
                                                    {{
                                                      //session()->put('authuserid',Auth::id());
                                                      //$notifications = config('commondata.notifications');
                                                      $notificationns = DB::table('notifications')->where('notifiable_id','=', Auth::id() )->get()->toArray();
            
                                                      // extraire les informations de l'entree à travers id trouvé dans la notification
                                                      $nnotifs = array();
                                                      foreach ($notificationns as $i) {
                                                        $notifc = json_decode($i->data, true);
                                                        $entreeid = $notifc['Entree']['id'];
                                                        $notifentree = DB::table('entrees')->where('id','=', $entreeid)->get()->toArray();
                                                        $row = array();
                                                        $row['id'] = $entreeid;
                                                        //print_r($notifc) ;
                                                        $row['read_at']= $i->read_at;
                                                        foreach ($notifentree as $ni) {
                                                          $row['sujet'] = $ni->sujet;
                                                          $row['type'] = $ni->type;
                                                          $row['dossier'] = $ni->dossier;
                                                        }
                                                        $nnotifs[] = $row;
                                                      }

                                                      // group notifications by ref dossier
                                                      $result = array();
                                                      foreach ($nnotifs as $element) {
                                                          if (isset($element['dossier']))
                                                          { $result[$element['dossier']][] = $element; }
                                                          else
                                                          {
                                                            $result[null][] = $element;
                                                          }
                                                      } 
                                                      $notifications = $result;




                                                      foreach ($notifications as $ntf) {
                                                        if (!empty($ntf[0]['dossier']))
                                                        {
                                                          // recuperation nom assuré du dossier
                                                            $search = $ntf[0]['dossier'];
                                                            $assuree = $dossiers->filter(function($item) use ($search) {
                                                                return stripos($item['reference_medic'],$search) !== false;

                                                            });

                                                            $nassure =$assuree->first();
                                                          // fin recuperation nom assuré
                                                          echo "<li  class='jstree-open' id='prt_".$ntf[0]['dossier']."'>".$ntf[0]['dossier']." | ".$nassure['subscriber_name']." ".$nassure['subscriber_lastname']."<ul>";}
                                                        foreach ($ntf as $n) {
                                                          
                                                          if (!isset ($n['type']) )
                                                          {  $n['type'] = 'default'; }

                                                           
                                                            if ((empty($n['read_at']))||(is_null($n['read_at'])))
                                                              { $newnotif=" class='newnotif'" ;}
                                                            else
                                                              {$newnotif="" ;}

                                                          if (!isset ($n['sujet']) )
                                                            {  $n['sujet'] = ' '; }

                                                            switch ($n['type']) {
                                                                case "email":
                                                                    echo '<li id="'.$n['id'].'" rel="tremail" '.$newnotif.'><a href="'.action('EntreesController@show', $n['id']).'" ><span class="cutlongtext"><span class="fa fa-fw fa-envelope"></span> '.$n['sujet'].'</span></a></li>'; 
                                                                    break;
                                                                case "fax":
                                                                    echo '<li id="'.$n['id'].'" rel="trfax" '.$newnotif.'><a href="'.action('EntreesController@show', $n['id']).'" ><span class="cutlongtext"><span class="fa fa-fw fa-fax"></span> '.$n['sujet'].'</span></a></li>'; 
                                                                    break;
                                                                case "tel":
                                                                    echo '<li id="'.$n['id'].'" rel="trtel" '.$newnotif.'><a href="'.action('EntreesController@show', $n['id']).'" ><span class="cutlongtext"><span class="fa fa-fw fa-phone"></span> '.$n['sujet'].'</span></a></li>'; 
                                                                    break;
                                                                case "sms":
                                                                    echo '<li id="'.$n['id'].'" rel="trsms" '.$newnotif.'><a href="'.action('EntreesController@show', $n['id']).'" ><span class="cutlongtext"><span class="fas fa-sms"></span> '.$n['sujet'].'</span></a></li>'; 
                                                                    break;
                                                                case "whatsapp":
                                                                    echo '<li id="'.$n['id'].'" rel="trwp" '.$newnotif.'><a href="'.action('EntreesController@show', $n['id']).'" ><span class="cutlongtext"><span class="fab fa-whatsapp"></span> '.$n['sujet'].'</span></a></li>'; 
                                                                    break;
                                                                default:
                                                                    echo '<li id="'.$n['id'].'" rel="tremail" '.$newnotif.'><a href="'.action('EntreesController@show', $n['id']).'" ><span class="cutlongtext"> '.$n['sujet'].'</span></a></li>'; 
                                                            }


                                                        }
                                                        if (!empty($ntf[0]['dossier'])) {echo '</ul>'; }
                                                      }
                                                      if (!empty($ntf[0]['dossier'])) {echo '</li>';}
                                                    }}
                                                  @endphp
                                              </ul>
                                              </div>
                                                 
                                        </div>
                                        <div class="tab-pane fade  scrollable-panel" id="notestab">


                                          @if (auth()->check()) 
                                    

                                          <div class="row">

                                            <div class="col-md-10">
                                                  <!--<span style="font-size: 20px;">Ajouter une Note</span>-->
                                            </div>
                                            <div class="col-md-2">
                                            <a data-toggle="modal" data-target="#AjouterNote" href="javascript:void(0);" class="com_button" title="Ajouter une nouvelle Note"><img width="26" height="26" src="{{ asset('public/img/plus.png') }}"/></a>
                                            </div>

                                          </div>
                                          <br>

                                           <?php $notes=Auth::user()->notes;?>

                                          

                                          
                                           @if($notes)

                                           <div id ="contenuNotes">

                                           <?php $burl = URL::to("/");?>

                                           @foreach($notes as $note)

                                           <div class="row" style="padding: 0px; margin:0px" > <!-- liste des notes-->

                                            <div class="col-md-2">

                                             <div class="dropdown">
                                              <button class="dropbtn"><i class="glyphicon glyphicon-pencil"></i></button>
                                              <div class="dropdown-content">
                                              <a href="<?php echo $burl.'/SupprimerNote/'.$note->id ?>">Achever</a>
                                              <a href="#" class="ReporterNote2" id="{{$note->id}}">Reporter</a>
                                              <input id="noteh<?php echo $note->id ?>" type="hidden" class="form-control" value="{{$note->titre}}" name="note"/>                                              
                                              </div>
                                            </div>

                                            </div>

                                            <div class="col-md-10">
                                                
                                                <div class="panel panel-default">
                                                <div class="panel-heading <?php /*if($Mission->id ==$currentMission){echo 'active';}
                                                else {if($Mission->dossier->id==$dosscourant){echo 'ColorerMissionsCourantes' ;}}*/ ?>">
                                         
                                                   <h4 class="panel-title">
                                                      <a data-toggle="collapse" href="#collapse{{$note->id}}">   {{$note->titre}}</a>
                                                   </h4>
                                                </div>

                                              <div id="collapse{{$note->id}}" class="panel-collapse collapse in">
                                                  <ul class="list-group" style="padding:0px; margin:0px">
                                                   
                                                    <li class="list-group-item"><a  href="#">{{$note->contenu}} </a></li>
                                                  
                                                  </ul>

                                              </div>                                        
                                            </div>

                                          </div>

                                            <div class="col-md-2">
                                           
                                            </div>


                                          </div>

                                            @endforeach 
                                            </div>                                                
                                           @endif
                                          @endif
                                      </div>

                                       <div class="tab-pane fade  scrollable-panel" id="rappelstab">


                                          @if (auth()->check()) 


                                          <div class="row">

                                            <div class="col-md-9">
                                                  <span>Liste des rappels d'actions</span>
                                            </div>
                                            <div class="col-md-3">
                                            
                                            </div>


                                          </div>

                                          <div class="row">
                                          </div>
                                                 



                                          @endif
                                      </div>




                                    </div>
                                    <!--<audio id="audio" src="http://www.soundjay.com/button/beep-07.wav" autoplay="false" ></audio>-->
                                   <audio id="audiokbs" src="" autoplay="false" ></audio>

                                </div>

<!-- début modal-->




<!-- fin les modals -->


</div><!--fin tab -->

<?php
if (isset($dossier))
{
?>
<script type="text/javascript">
         $( document ).ready(function() {
            // verifier sil existe des notifications pour le dossier courant pour les marquer comme actifs
            if ($("#prt_{{ $dossier['reference_medic']}}").length > 0) { 
              // $("li#prt_{{{-- $dossier['reference_medic'] --}}}").addClass('dossiercourant');
               // scroll vers lemplacement de la notification
              $('html, #notificationstab').animate({
               scrollTop: $("#prt_{{ $dossier['reference_medic']}}").offset().top
              }, 1000);
              <?php
                if (isset($entree)) {
              ?>
              // highlight notification courante
              if ($("li#{{ $entree['id']}}").length > 0) { 
               $("li#{{ $entree['id']}}").addClass('dossiercourant');
              }
              <?php
               } 
              ?>
            }
          });
</script>
<?php } ?>


<div class="modal fade" id="AjouterNote" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
             <div class="modal-dialog">
             <div class="modal-content">
             <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
             <h4 class="modal-title" id="myModalLabel">Créer une nouvelle note</h4>
             </div>

             <form action="{{route('Note.store')}}" method="POST">
             <div class="modal-body">
             <!-- début les inputs-->


               <div class="form-group">
                 {{ csrf_field() }}
               <div class="row">
                   <div class="col-md-4" style="padding-top:5px"> 
                    <label  style=" ;  text-align: left; width: 40px;">Titre:</label>
                  </div>
                   <div class="col-md-8">
                    <input id="titre" type="text" class="form-control" style="width:80%;  text-align: left !important;" name="titre"/>
                  </div>
              </div>
              </div>

              <div class="form-group">
                  <div class="row">
                      <div class="col-md-4" style="padding-top:5px">   
                        <label for="descrip" style="display: inline-block;  text-align: right; width: 40px;">Contenu</label>
                      </div>
                      <div class="col-md-8">
                        <textarea id="descrip" type="text" class="form-control" style="width:80%;  text-align: left;" name="descrip"></textarea>
                      </div>
                  </div>
              </div>

             <div class="form-group">

              <div class="row">
                <div class="col-md-4">
               <?php $da= date('Y-m-d\TH:m'); ?>
               <label for="daterappel" style="display: inline-block;  text-align: left; width:200px;"> la date de rappel</label>
               </div>

              <div class="col-md-8">
             <input id="daterappel" type="datetime-local" value="<?php echo $da ?>" class="form-control" style="width:80%; flow:right; display: inline-block; text-align: right;" name="daterappel"/>
             </div>
             </div>
             </div>


            <!-- fin les inputs-->
             </div>
             <div class="modal-footer">
             <a href="#" type="button" class="btn btn-default" data-dismiss="modal">Fermer</a>
             <button type="submit" class="btn btn-primary">Enregister</button>
             </div>

           </form>
                 </div>
             </div>

             
            </div>



    <div class="modal fade" id="myNoteModalReporter2" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content" id="kbskbs">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 id="titleNoteModal" class="modal-title"></h4>
        </div>
        <form action="" method="POST">
        <div class="modal-body">
          <p>

          <div id="contenumodalNote" >

                       
          </div>


          </p>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-default" data-dismiss="modal">Reporter</button>
          <a href="" class="btn btn-default" data-dismiss="modal">Fermer</a>
        </div>
      </form>
      </div>
      
    </div>
  </div>

  <div class="modal fade" id="myNoteModalReporter1" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content" id="contenuNotesModal">
       
      </div>
      
    </div>
  </div>


<script src="{{ URL::asset('resources/assets/js/alertify.js') }}"></script>

<script>


 /*function playSound() {
          
          
      }*/

var idNote;
var dataNote;
var iddropdown;
var hrefidAchever;

setInterval(function(){
 //alert("Hello"); 

 
     
    $.ajax({
       url : '{{ url('/') }}'+'/getNotesAjaxModal',
       type : 'GET',
       dataType : 'html', // On désire recevoir du HTML
       success : function(data){ // code_html contient le HTML renvoyé
           //alert (data);

           if(data)
           {


             // alert ("des nouvelles notes sont activées");
              //$("#contenuNotes").prepend(data);
              var sound = document.getElementById("audiokbs");
              sound.setAttribute('src', "{{URL::asset('public/media/point.mp3')}}");
              sound.play();

             // alertify.alert("Note","Une nouvelle note est activée").show();

             $("#contenuNotesModal").empty().append(data);
             iddropdown=jQuery(data).find('.dropdown').attr("id");
             $("#"+iddropdown).hide();
             $("#hiddenreporter").hide();
            
              
             $("#myNoteModalReporter1").modal('show');          
             idNote=jQuery(data).find('.rowkbs').attr("id");
             dataNote=jQuery(data).find('#'+idNote).html();
             hrefidAchever=jQuery(data).find('#idAchever').attr("href");

            


           }
       }
    });
   


}, 10000);


$(document).ready(function() {
    
    $(document).on("click","#noteOnglet",function() {
      //alert(datakbs);
      $("#contenuNotes").prepend(dataNote);
      $("#"+iddropdown).show();
    

        $('#tabskbs a[href="#notestab"]').trigger('click');


    });

  

     $(document).on("click","#reporterHide",function() {
       
       $("#hiddenreporter").toggle();  


    });

     $(document).on("click","#idAchever",function() {
       
       $.ajax({
       url : hrefidAchever,
       type : 'GET',
       dataType : 'html', // On désire recevoir du HTML
       success : function(data){ 

        //alert(data);
        //alertify.alert("Information", data).show();

       },

       error : function(resultat, statut, erreur){
        
        alert("Erreur lors de suppression de la note :"+erreur);

       }

      });
    });



   
});

 



</script>

 <script>

  function formatDate(date) {
      var hours = date.getHours();
      var minutes = date.getMinutes();
      var ampm = hours >= 12 ? 'pm' : 'am';
      hours = hours % 12;
      hours = hours ? hours : 12; // the hour '0' should be '12'
      minutes = minutes < 10 ? '0'+minutes : minutes;
      var strTime = hours + ':' + minutes + ' ' + ampm;
      return date.getMonth()+1 + "/" + date.getDate() + "/" + date.getFullYear() + "  " + strTime;
    };

    function toDatetimeLocal ()
    {

      var date = new Date();
      var ten= function(i)
      {
        return( i<10 ? '0':'')+i;
      },


      YYYY=date.getFullYear(),
      MM=ten(date.getMonth()+1),
      DD=ten(date.getDate()),
      HH=ten(date.getHours()),                                               
      II=ten(date.getMinutes()),
      SS=ten(date.getSeconds());

      return YYYY+'-'+MM+'-'+DD+'T'+HH+':'+II+':'+SS;


    };



    $(document).ready(function() {

      $(document).on('click','.ReporterNote2', function() {


       var idn=$(this).attr("id");
       //alert(idn);
        nomact=$('#noteh'+idn).attr("value");
          //$("#titleNoteReporter2").empty().append(idw);//ou la methode html

         <?php /*$da= date('Y-m-d\TH:m'); */?>
         <?php /*echo $da */?>
                  // var d = new Date("Y-m-d\TH:m");
                   //alert(nomact);
                 // var d = new Date();
                   //var d =new Date().toISOString();
                   var k=toDatetimeLocal();

                   var data='<div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button> <h4 id="titleNoteModal" class="modal-title">'+nomact+'</h4></div><form action="'+'{{ url("/") }}'+'/ReporterNote/'+idn+'" method="GET"><div class="modal-body"><p>';




                 // var e = formatDate(d);
                 // alert(k);

                   //alert(JSON.stringify(data));
               data+='<div class="row"><div class="col-md-4"><label for="daterappel" style="display: inline-block;  text-align: left; width:200px;"> la nouvelle date de rappel</label></div><div class="col-md-8"> <input id="daterappel" type="datetime-local" value="'+k+'" class="form-control" style="width:80%; flow:right; display: inline-block; text-align: right;" name="daterappelNote"/></div></div>';


               data+=' </p></div> <div class="modal-footer"><button id="" type="submit" class="btn btn-default" >Reporter</button><a href="" class="btn btn-default" data-dismiss="modal">Annuler</a>  </div></form>';


                 $('#kbskbs').empty().html(data);

                  $('#myNoteModalReporter2').modal('show');

                      //alert(JSON.stringify(retour))   ;
                     // location.reload();
              

      })


    });
    </script>


 



