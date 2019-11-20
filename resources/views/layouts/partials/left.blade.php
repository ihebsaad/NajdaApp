

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
<style>

    #notificationkbsleft {
    cursor: pointer;
    position: fixed;
    left: 0px;
    z-index: 9997;
    bottom: : 0px;
    margin-bottom: 22px;
    margin-left: 10px;
    max-width: 450px;   
     }
   </style>
   <script>
     NotifyLeft = function(text, callback, close_callback, style) {

  var time = '120000';
  var $container = $('#notificationkbsleft');
  var icon = '<i class="fa fa-info-circle "></i>';
 
  if (typeof style == 'undefined' ) style = 'warning'
  
  var html = $('<div class="alert alert-' + style + '  hide">' + icon +  " " + text + '</div>');
  
  $('<a>',{
    text: '×',
    class: 'button close',
    style: 'padding-left: 10px;',
    href: '#',
    click: function(e){
      e.preventDefault()
      close_callback && close_callback()
      remove_notice()
    }
  }).prependTo(html)

  $container.prepend(html)
  html.removeClass('hide').hide().fadeIn('slow')

  function remove_notice() {
    html.stop().fadeOut('slow').remove()
  }
  
  var timer =  setInterval(remove_notice, time);

  $(html).hover(function(){
    clearInterval(timer);
  }, function(){
    timer = setInterval(remove_notice, time);
  });
  
  html.on('click', function () {
    clearInterval(timer)
    callback && callback()
    remove_notice()
  });
  
  
}

   </script>

<?php
use App\Notification;

$seance =  DB::table('seance')
    ->where('id','=', 1 )->first();
$user = auth()->user();
$iduser=$user->id;

$superviseurmedic=$seance->superviseurmedic ;
$superviseurtech=$seance->superviseurtech ;

 if ( ($iduser==$superviseurmedic) || ($iduser== $superviseurtech) ) {

$dtc = (new \DateTime())->modify('-5 minutes')->format('Y-m-d\TH:i');
    $dtc2 = (new \DateTime())->modify('-10 minutes')->format('Y-m-d\TH:i');

    $countO=Notification::where('read_at', null)
         ->where('created_at','<=', $dtc)
        ->where('created_at','>', $dtc2)
        ->count();


    $dtc3 = (new \DateTime())->modify('-10 minutes')->format('Y-m-d\TH:i');

    $countR=Notification::where('read_at', null)
         ->where('created_at','<=', $dtc3)
        ->count();



     $style='';

     if($countR+$countO==0){$style='width:40%;padding:8px 8px 8px 8px;background:white' ;}
     else{
         if($countR >0){
             $style='width:40%;padding:8px 8px 8px 8px;background:#fc6e51';
         }else{
             $style='width:40%;padding:8px 8px 8px 8px;background:#FFCE54';
         }
     }

}

?>
<div id="notificationkbsleft"></div>
<div class="panel panel-default"  id="notificationspanel">
    <div class="panel-heading" id="headernotifs">
        <h4 class="panel-title">Notifications</h4>
        <span class="pull-right">
         <i class="fa fa-fw clickable fa-chevron-up"></i>
            </span>
    </div>
    <div  class="panel-body" style="display: block;">
<?php        if ( ($iduser==$superviseurmedic) || ($iduser== $superviseurtech) ) {  ?>
<style>
    #totnotifs{background-color: white;}
</style>
       <div id="totnotifs"  class="row pull-right" style="<?php echo $style;?>">
        <div class="col-md-1 "><a  title="Notification depuis plus de 10 Minutes" href="{{ route('entrees.index') }}" ><span  id="notifrouge" class="label label-danger  " style="color:black"><?php echo $countR ;?></span></a></div>
        <div class="col-md-1 "><a  title="Notification depuis plus de 5 Minutes" href="{{ route('entrees.index') }}" ><span  id="notiforange" class="label label-warning  " style="color:black"><?php echo $countO ;?></span></a></div>
    </div>
        <?php } ?>
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


            <!-- treeview of notifications -->
                <div id="jstree">
                    <ul>

                        <?php
		$urlapp="http://$_SERVER[HTTP_HOST]/najdaapp";?>

                        @php
                            use App\Dossier;{{
                              //session()->put('authuserid',Auth::id());
                              //$notifications = config('commondata.notifications');
                              $notificationns =  DB::table('notifications')
                              ->where('statut','=', 0 )
                                          ->where('notifiable_id','=', Auth::id() )
                                           ->get()->toArray();

                              // extraire les informations de l'entree à travers id trouvé dans la notification
                              $nnotifs = array();
                              foreach ($notificationns as $i) {
                                $notifc = json_decode($i->data, true);
                                $Datenotif=$i->created_at;
                               // $datenotif= date('d/m/y H:i', strtotime($Datenotif)) ;
                                $entreeid = $notifc['Entree']['id'];
                                $dossierid = $notifc['Entree']['dossierid'];
                                $notifentree = DB::table('entrees')->where('id','=', $entreeid)->get()->toArray();
                                $row = array();
                                $row['id'] = $entreeid;
                                //print_r($notifc) ;
                                $row['read_at']= $i->read_at;
                                foreach ($notifentree as $ni) {
                                  $row['sujet'] = $ni->sujet;
                                  $row['type'] = $ni->type;
                                  $row['dossier'] = $ni->dossier;
                                  $row['emetteur'] = $ni->emetteur;
                                  $row['reception'] = substr ($ni->reception,0,16);
                                //  $row['reception'] = $ni->reception ;
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

                                    $nassure= Dossier::where('reference_medic',$search)->first();
                                  // fin recuperation nom assuré
                                  echo "<li  class='jstree-open' id='prt_".$ntf[0]['dossier']."'><a href='".$urlapp."/dossiers/view/".$dossierid."'>".$ntf[0]['dossier']." | ".$nassure['subscriber_name']." ".$nassure['subscriber_lastname']." </a><ul>";
                                  }
                                foreach ($ntf as $n) {

                                  if (!isset ($n['type']) )
                                  {  $n['type'] = 'default'; }


                                    if ((empty($n['read_at']))||(is_null($n['read_at'])))
                                      { $newnotif=" class='newnotif'" ;}
                                    else
                                      {$newnotif="" ;}

                                  if (!isset ($n['sujet']) )
                                    {  $n['sujet'] = ' '; }

                                        if (!empty($ntf[0]['dossier']))
                                {
                                    switch ($n['type']) {
                                        case "email":
                                            echo '<li  id="'.$n['id'].'" rel="tremail" '.$newnotif.'><a class="idEntreePourMiss" id="'.$n['id'].'" href="'.action('EntreesController@show', $n['id']).'" ><span class="cutlongtext"><span class="fa fa-fw fa-envelope"></span> '.$n['reception'].' '.$n['emetteur'].' '.$n['sujet'].'</span></a></li>';
                                            break;
                                        case "fax":
                                            echo '<li id="'.$n['id'].'" rel="trfax" '.$newnotif.'><a class="idEntreePourMiss" id="'.$n['id'].'"  href="'.action('EntreesController@show', $n['id']).'" ><span class="cutlongtext"><span class="fa fa-fw fa-fax"></span>  '.$n['reception'].' '.$n['sujet'].'</span></a></li>';
                                            break;
                                        case "tel":
                                            echo '<li  id="'.$n['id'].'" rel="trtel" '.$newnotif.'><a class="idEntreePourMiss" id="'.$n['id'].'" href="'.action('EntreesController@show', $n['id']).'" ><span class="cutlongtext"><span class="fa fa-fw fa-phone"></span>  '.$n['reception'].' '.$n['sujet'].'</span></a></li>';
                                            break;
                                        case "sms":
                                            echo '<li  id="'.$n['id'].'" rel="trsms" '.$newnotif.'><a class="idEntreePourMiss" id="'.$n['id'].'" href="'.action('EntreesController@show', $n['id']).'" ><span class="cutlongtext"><span class="fas fa-sms"></span>  '.$n['reception'].' '.$n['sujet'].'</span></a></li>';
                                            break;
                                        case "whatsapp":
                                            echo '<li  id="'.$n['id'].'" rel="trwp" '.$newnotif.'><a class="idEntreePourMiss" id="'.$n['id'].'" href="'.action('EntreesController@show', $n['id']).'" ><span class="cutlongtext"><span class="fab fa-whatsapp"></span> '.$n['sujet'].'</span></a></li>';
                                            break;
                                        default:
                                            echo '<li  id="'.$n['id'].'" rel="tremail" '.$newnotif.'><a class="idEntreePourMiss" id="'.$n['id'].'" href="'.action('EntreesController@show', $n['id']).'" ><span class="cutlongtext"> '.$n['sujet'].'</span></a></li>';
                                    }

                                }else{

                               switch ($n['type']) {
                                        case "email":
                                            echo '<li  id="'.$n['id'].'" rel="tremail" '.$newnotif.'><a class="idEntreePourMiss" id="'.$n['id'].'" href="'.action('EntreesController@showdisp', $n['id']).'" ><span class="cutlongtext"><span class="fa fa-fw fa-envelope"></span> '.$datenotif.' '.$n['emetteur'].' '.$n['sujet'].'</span></a></li>';
                                            break;
                                        case "fax":
                                            echo '<li  id="'.$n['id'].'" rel="trfax" '.$newnotif.'><a class="idEntreePourMiss" id="'.$n['id'].'"  href="'.action('EntreesController@showdisp', $n['id']).'" ><span class="cutlongtext"><span class="fa fa-fw fa-fax"></span> '.$n['sujet'].'</span></a></li>';
                                            break;
                                        case "tel":
                                            echo '<li  id="'.$n['id'].'" rel="trtel" '.$newnotif.'><a class="idEntreePourMiss" id="'.$n['id'].'" href="'.action('EntreesController@showdisp', $n['id']).'" ><span class="cutlongtext"><span class="fa fa-fw fa-phone"></span> '.$n['sujet'].'</span></a></li>';
                                            break;
                                        case "sms":
                                            echo '<li  id="'.$n['id'].'" rel="trsms" '.$newnotif.'><a class="idEntreePourMiss" id="'.$n['id'].'" href="'.action('EntreesController@showdisp', $n['id']).'" ><span class="cutlongtext"><span class="fas fa-sms"></span> '.$n['sujet'].'</span></a></li>';
                                            break;
                                        case "whatsapp":
                                            echo '<li  id="'.$n['id'].'" rel="trwp" '.$newnotif.'><a class="idEntreePourMiss" id="'.$n['id'].'" href="'.action('EntreesController@showdisp', $n['id']).'" ><span class="cutlongtext"><span class="fab fa-whatsapp"></span> '.$n['sujet'].'</span></a></li>';
                                            break;
                                        default:
                                            echo '<li  id="'.$n['id'].'" rel="tremail" '.$newnotif.'><a class="idEntreePourMiss" id="'.$n['id'].'" href="'.action('EntreesController@showdisp', $n['id']).'" ><span class="cutlongtext"> '.$n['sujet'].'</span></a></li>';
                                    }

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
                            <a data-toggle="modal" data-target="#AjouterNote"  class="com_button" title="Ajouter une nouvelle Note"><img width="26" height="26" src="{{ asset('public/img/plus.png') }}"/></a>
                        </div>

                    </div>
                    <br>

                    <?php $notes=Auth::user()->notes->sortBy(function($t)
                                        {
                                          return $t->updated_at;
                                        })->reverse();?>

                    @if($notes)

                        <div id ="contenuNotes">

                            <?php $burl = URL::to("/");?>

                            @foreach($notes as $note)

                                <div class="row" style="padding: 0px; margin:0px" > <!-- liste des notes-->

                                    <div class="col-md-2">

                                        <div class="dropdown">
                                            <button class="dropbtn"><i class="glyphicon glyphicon-pencil"></i></button>
                                            <div class="dropdown-content">
                                                <a href="<?php echo $burl.'/SupprimerNote/'.$note->id ?>">Supprimer</a>
                                                <a href="#" class="ReporterNote2" id="{{$note->id}}">Reporter</a>
                                                <input id="noteh<?php echo $note->id ?>" type="hidden" class="form-control" value="{{$note->titre}}" name="note"/>
                                                <a class="idNoteEnvoyerA" id="envoyer{{$note->id}}" href="javascript:void(0)">Envoyer à</a>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-md-10">

                                        <div class="panel panel-default">
                                            <div class="panel-heading <?php /*if($Mission->id ==$currentMission){echo 'active';}
                                                else {if($Mission->dossier->id==$dosscourant){echo 'ColorerMissionsCourantes' ;}}*/ ?>">
                                               @if($note->user_id==$note->emetteur_id)
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" href="#collapse{{$note->id}}">   {{$note->titre}}</a>
                                                </h4>
                                                @else
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" href="#collapse{{$note->id}}">   {{$note->titre}}</a> <span> ( envoyée par {{ $note->emetteur->name}} {{ $note->emetteur->lastname}})</span>
                                                </h4>
                                                @endif
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
        if ($("#prt_{{ trim($dossier['reference_medic'])}}").length > 0) {
             // scroll vers lemplacement de la notification
            $('html, #notificationstab').animate({
                scrollTop: $("#prt_{{ trim($dossier['reference_medic'])}}").offset().top
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


<div class="modal fade" id="AjouterNote"  role="dialog" aria-labelledby="basicModal" aria-hidden="true">

             <div class="modal-dialog">
             <div class="modal-content">
             <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
             <h4 class="modal-title" id="myModalLabel">Créer une nouvelle note</h4>
             </div>

             <form id="idFormCreerNote" action="" method="POST">
             <div class="modal-body">
             <!-- début les inputs-->


               <div class="form-group">
                 {{ csrf_field() }}
               <div class="row">
                   <div class="col-md-4" style="padding-top:5px"> 
                    <label  style=" ;  text-align: left; width: 40px;">Titre:</label>
                  </div>
                   <div class="col-md-8">
                    <input id="titreNote" type="text" class="form-control" style="width:80%;  text-align: left !important;" name="titreNote" autocomplete="off"/>
                  </div>
              </div>
              </div>

              <div class="form-group">
                  <div class="row">
                      <div class="col-md-4" style="padding-top:5px">   
                        <label for="descrip" style="display: inline-block;  text-align: right; width: 40px;">Contenu</label>
                      </div>
                      <div class="col-md-8">
                        <textarea id="descripNote" type="text" class="form-control" style="width:80%;  text-align: left;" name="descripNote"></textarea>
                      </div>
                  </div>
              </div>

             <div class="form-group">

              <div class="row">
                <div class="col-md-4">
               <?php $da = (new \DateTime())->format('Y-m-d\TH:i'); ?>
               <label for="daterappel" style="display: inline-block;  text-align: left; width:200px;"> la date de rappel</label>
               </div>

              <div class="col-md-8">
             <input id="daterappelNote" type="datetime-local" value="<?php echo $da ?>" class="form-control" style="width:80%; flow:right; display: inline-block; text-align: right;" name="daterappelNote"/>
             </div>
             </div>
             </div>


             <div class="form-group">

              <div class="row">
                <div class="col-md-4">
               
               <label for="daterappel" style="display: inline-block;  text-align: left; width:200px;"> Envoyer à </label>
               </div>

              <div class="col-md-8">
          

             <select id="EnvoyerNoteId" name="EnvoyerNoteId" class="form-control select2" style="width: 80%;">
                                            <option value="">Selectionner</option>
                                            <?php $agents = App\User::get(); ?>
                                           
                                                @foreach ($agents as $agt)
                                                <?php if (!empty ($agentname)) { ?>
                                                @if ($agentname["id"] == $agt["id"])
                                                    <option value={{ $agt["id"] }} selected >{{ $agt["name"] }}</option>
                                                @else
                                                    <option value={{ $agt["id"] }} >{{ $agt["name"] }}</option>
                                                @endif
                                                
                                                <?php }
                                                else
                                                      {  echo '<option value='.$agt["id"] .' >'.$agt["name"].'</option>';}
                                                ?>
                                                @endforeach    
                </select>

             </div>
             </div>
             </div>


            <!-- fin les inputs-->
             </div>
             <div class="modal-footer">
             <a href="#" type="button" class="btn btn-default" data-dismiss="modal">Fermer</a>
             <button id="idEnregisterNote" type="button" class="btn btn-primary">Enregister</button>
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
<!-- debut traitement modal notes à envoyer-->
<div class="modal fade" id="myNoteModalEnvoyer" role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
    <div class="modal-dialog" id="EnoyerNotekbs" role="document">
       
    </div>
</div>

<script>

   $(document).on("click",".idNoteEnvoyerA",function() {

          var idwn=$(this).attr("id");
          idwn=idwn.substring(idwn.indexOf('r')+1);
        // alert(idwn.substring(idwn.indexOf('r')+1));  
         // alert('doudou');
      $.ajax({
           
            url : '{{ url('/') }}'+'/getAjaxUsersNote/'+idwn,
            type : 'GET',
            dataType : 'html', 
            success : function(data){

              if (data)
              {
                 $('#EnoyerNotekbs').html(data);
                 $('#myNoteModalEnvoyer').modal('show');
              }

            }


        })


    });
</script>

<script>

   
    $(document).on("click",".BoutonEnvoyerNote",function() {

     // alert("et oui");  
       var idwn=$(this).attr("id"); 
    //   alert(idwn);

     $("#"+idwn).attr("disabled", true);
     
     
    var donnees = $('#idFormUsersNote').serialize(); // On créer une variable content le formulaire sérialisé
    if(donnees)
    {

    // alert(donnees);

    }

    //var _token = $('input[name="_token"]').val();
    $.ajax({

           url:"{{ route('Envoyer.Note') }}",
           method:"get",
           data : donnees,
           success:function(data){
         
               // alert("Note créee");
              alert(data);
             if(data=="la note est envoyée")
             {

                        $("#myNoteModalEnvoyer").modal('hide');
                        location.reload();

             }

                },
            error: function(jqXHR, textStatus, errorThrown) {

              alert('erreur lors de création de la Note');


            }

   
    });


    $("#"+idwn).attr("disabled",false);


    });// fin $document clik boutonEnvoyerNote


</script>




<script src="{{ URL::asset('resources/assets/js/alertify.js') }}"></script>

<script>
   $(document).ready(function() {

     setInterval(function(){
        //alert("Hello");


        $.ajax({
            //url : '{{ url('/') }}'+'/getNotesAjaxModal',
            url : '{{ url('/') }}'+'/getNotesEnvoyeesAjax',
            type : 'GET',
            dataType : 'html', // On désire recevoir du HTML
            success : function(data){ // code_html contient le HTML renvoyé
                //alert (data);

                if(data)
                {
                 
                    var sound = document.getElementById("audiokbs");
                    sound.setAttribute('src', "{{URL::asset('public/media/point.mp3')}}");
                    sound.play();

           
                  NotifyLeft("<b>"+data+"</b>",
                    function () { 
                      //alert("clicked notification")
                    },
                    function () { 
                      //alert("clicked x")
                    },
                    'success'
                  );

                alertify.alert("Réception d\'une nouvelle Note","<h3><i> "+data+"</i></h3>").show();


                    /*ropdown).hide();
                    $("#hiddenreporter").hide();


                    $("#myNoteModalReporter1").modal('show');
                    idNote=jQuery(data).find('.rowkbs').attr("id");
                    dataNote=jQuery(data).find('#'+idNote).html();
                    hrefidAchever=jQuery(data).find('#idAchever').attr("href");*/


                }
            }
        });



    }, 12000);

   /*  fin de gestion des notes envoyées */


    /*  début de gestion des notes reportées */
    var idNote;
    var dataNote;
    var iddropdown;
    var hrefidAchever;

    setInterval(function(){
        //alert("Hello");


        $.ajax({
            //url : '{{ url('/') }}'+'/getNotesAjaxModal',
            url : '{{ url('/') }}'+'/getNotesReporteesAjax',
            type : 'GET',
            dataType : 'html', // On désire recevoir du HTML
            success : function(data){ // code_html contient le HTML renvoyé
                //alert (data);

                if(data)
                {
                 
                    var sound = document.getElementById("audiokbs");
                    sound.setAttribute('src', "{{URL::asset('public/media/point.mp3')}}");
                    sound.play();

           
                  NotifyLeft("<b>"+data+"</b>",
                    function () { 
                      //alert("clicked notification")
                    },
                    function () { 
                      //alert("clicked x")
                    },
                    'success'
                  );
                     alertify.alert("Nouvelle Note","<h3><i>"+data+"</i></h3>").show();

                    /*ropdown).hide();
                    $("#hiddenreporter").hide();


                    $("#myNoteModalReporter1").modal('show');
                    idNote=jQuery(data).find('.rowkbs').attr("id");
                    dataNote=jQuery(data).find('#'+idNote).html();
                    hrefidAchever=jQuery(data).find('#idAchever').attr("href");*/




                }
            }
        });



    }, 10000);

  });


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

        //return YYYY+'-'+MM+'-'+DD+'T'+HH+':'+II+':'+SS;

        return YYYY+'-'+MM+'-'+DD+'T'+HH+':'+II;


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
            data+='<div class="row"><div class="col-md-4"><label for="daterappel" style="display: inline-block;  text-align: left; width:200px;"> la nouvelle date de report</label></div><div class="col-md-8"> <input id="daterappel" type="datetime-local" value="'+k+'" class="form-control" style="width:80%; flow:right; display: inline-block; text-align: right;" name="daterappelNote"/></div></div>';


            data+=' </p></div> <div class="modal-footer"><button id="" type="submit" class="btn btn-default" >Reporter</button><a href="" class="btn btn-default" data-dismiss="modal">Annuler</a>  </div></form>';


            $('#kbskbs').empty().html(data);

            $('#myNoteModalReporter2').modal('show');

            //alert(JSON.stringify(retour))   ;
            // location.reload();


        })


    });
</script>

<script>

   
    $(document).on("click",".idEntreePourMiss",function() {

        var idw=$(this).attr("id");
       // alert(idw);
        $('#idEntreeMissionOnclik').val(idw);
        //alert( $('#idEntreeMissionOnclik').val());

    });



    function countNotifs() {

        <?php  if (($view_name != 'supervision') && ($view_name != 'affectation') && ($view_name != 'notifs') && ($view_name != 'missions') && ($view_name != 'transport') && ($view_name != 'transportsemaine') && ($view_name != 'dossiers-create') && ($view_name != 'entrees-dispatching') && ($view_name != 'entrees-showdisp') ) { ?>
        <?php // if($iduser == $seance->dispatcheur)
        //{  ?>
        // count notif dispatcheur
        console.log('count notif dispatcheur: ');
        $.ajax({
            type: "get",
            url: "<?php echo $urlapp; ?>/entrees/countnotifs",
            success: function (countdata1) {
                // console.log('count notif : ' + countdata1);
                //  alert( 'count Notifs disp'+countdata1);

                // var count=parseInt(data);
                //  if(count>0 )
                //  {
                //  document.getElementById('countnotif').innerHTML =   countdata1;
                document.getElementById('countnotific').innerHTML =   ''+countdata1;
                //     $('#countnotif').html('500') ;
                // document.getElementById('countnotif').innerHTML='500';
                // }
            }
        });

        <?php  // }

          if ( ($iduser==$seance->superviseurmedic) || ($iduser== $seance->superviseurtech) ) {
          // count notif superviseur
                            ?>
console.log('count notif Orange: ');
        document.getElementById('totnotifs').style.background = 'white';

        $.ajax({
            type: "get",
            url: "<?php echo $urlapp; ?>/entrees/countnotifsorange",
            success: function (countdata2) {
                console.log('count notif orange: ' + countdata2);

                // var count = parseInt(data);
                   if (parseInt(countdata2) > 0) {
                document.getElementById('notiforange').innerHTML = '' + countdata2;
                document.getElementById('totnotifs').style.background = '#FFCE54';

                 }
            }
        });


        $.ajax({
            type: "get",
            url: "<?php echo $urlapp; ?>/entrees/countnotifsrouge",
            success: function (countdata3) {
                console.log('count notif rouge: ' + countdata3);
                //   var count = parseInt(data);
                   if (parseInt(countdata3) > 0) {

                document.getElementById('notifrouge').innerHTML = '' + countdata3;
                document.getElementById('totnotifs').style.background = '#fc6e51';

                    }

            }
        });


        <?php
        } // superviseur
?>
        setTimeout(function(){
            countNotifs();
        }, 30000);  //30 secds


        <?php
        } // viewname
        ?>

    }    //function

    countNotifs();



</script>

 <script>
 $(document).ready(function() {
 $("#EnvoyerNoteId").select2();
});
</script>
<script type="text/javascript">

   $("#idEnregisterNote").click(function(e){ 

    $("#idEnregisterNote").attr("disabled", true);

     var en=true;

     if(!$('#idFormCreerNote #titreNote').val())
     {

      alert('vous devez remplir le champs titre de la note');
      en=false;

     }

     if(!$('#idFormCreerNote #descripNote').val())
     {

      alert('vous devez saisir le contenu de la note');
      en=false;

     }

  

 if(en==true)
   {
    var donnees = $('#idFormCreerNote').serialize(); // On créer une variable content le formulaire sérialisé
    var _token = $('input[name="_token"]').val();
    $.ajax({

           url:"{{route('Note.store')}}",
           method:"POST",
           data : donnees,
           success:function(data){

            //alert(data);
         
                alert("Note créee");
                //alert(data);
                 $('#idFormCreerNote #descripNote').val('');
                 //$('#idFormCreationMission #typeMissauto option:eq(1)').prop('selected', true);
                //$('#idFormCreationMission #typeMissauto').text('Sélectionner');
                $('#idFormCreerNote #titreNote').val('');
                //$('#typeMissauto option[value=selectkbs]').attr("selected", "selected");
                $("#idFormCreerNote #EnvoyerNoteId").select2("val", "Sélectionner");

                 //var curr_date=toDatetimeLocal();

                 $('#idFormCreerNote #daterappelNote').val(data);

     
                },
            error: function(jqXHR, textStatus, errorThrown) {

              alert('erreur lors de création de la Note');


            }

   
    });
  }

   $("#idEnregisterNote").attr("disabled",false);

});


</script>
 



