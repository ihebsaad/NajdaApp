 <!-- Content -->
<?php 
use App\Http\Controllers\TagsController;
use App\Tag;
use App\Attachement ;
 ?>
<!--select css-->
    
    
    
    <style>

    #notificationkbs {
    cursor: pointer;
    position: fixed;
    right: 0px;
    z-index: 9999;
    bottom: : 0px;
    margin-bottom: 22px;
    margin-right: 15px;
    max-width: 500px;   
     }
   </style>
   <script>
     Notify = function(text, callback, close_callback, style) {

  var time = '120000';
  var $container = $('#notificationkbs');
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

<div class="panel panel-danger">
                    <div class="panel-heading">
                        <h4 class="panel-title">Missions et Informations</h4>
                        <span class="pull-right">
                           <i class="fa fa-fw clickable fa-chevron-up"></i>
                            
                        </span>
                    </div>

                    <div id="notificationkbs"></div>


                   <div class="panel-body scrollable-panel" style="display: block;">
                        

                        <div class="panel-body" style="display: block;">
                            <?php use \App\Http\Controllers\MissionController;
                                $typesMissions= MissionController::ListeTypeMissions();


                             /// if (isset( $dossier)){
                                 // $Missions=$dossier->activeMissions;

                               if (true){ 
                                /*$MissionsWithoutCurrentId=App\Mission::groupBy('dossier_id',auth::user())*/
                                $Missions=Auth::user()->activeMissions->groupBy(function ($me) {
                                     return $me->dossier_id;
                                   });

                                $MissionsDC=array();

                                if(isset($dossier))
                                {
                                //$MissionsDC=App\Mission::where('dossier_id',$dossier->id)->where('user_id','!=', Auth::user()->id)->orderBy('updated_at','desc')->get();
                               // dd($MissionsDC);
                                }

                                /*->sortBy(function($t)
                                        {
                                            return $t->updated_at;
                                        })->reverse();
                               
                                $Missions=$Missionsk->groupBy(function ($me) {
                                     return $me->dossier_id;
                                   })->all();*/
                               /* dd($Missionsh);
                                    $missionC=null;
                                    $missionO=null;
                                    $missionR=null;

                                foreach ($Missions as $m) {
                                  if($m->dossier_id==$dossier->id)
                                  {
                                     $missionC[]=$m;
                                  }
                                  else
                                  {
                                    $missionO[]=$m;

                                  }

                                }*/

                                /*function cmp($a, $b) {
                                            if ($a[2] == $b[2]) {
                                                    return 0;
                                            }
                                            return ($a[2] < $b[2]) ? -1 : 1;
                                    }

                                  usort($arr,"cmp");*/

                               // dd(array_values($missionO));

                            ?>
                  @if ($Missions || $MissionsDC )
<!--  début tab -+----------------------------------------------------------------------------------------->
                        <ul id="actiontabs" class="nav nav-tabs" style="margin-bottom: 15px;">
                            <li class="<?php if ( ! \Request::is('entrees/show/*')) {echo ' class="active" ';} ?> ">
                                <a id="idMissionstab" href="#Missionstab" data-toggle="tab">Missions</a>
                            </li>
                            <li>
                                <a href="#newMissiontab" data-toggle="tab">Nouvelle Mission</a>
                            </li>
                            <?php if (\Request::is('entrees/show/*')) { ?>
                            <li  <?php if (\Request::is('entrees/show/*')) {echo ' class="active" ';} ?> >
                                <a href="#infostab" data-toggle="tab">Informations</a>
                            </li>
                            <?php } ?>
                        </ul>
                        <div id="MissionsTabContent" class="tab-content">

                          <!-- début  Missions tab-->
                          <div class="tab-pane fade <?php if (! \Request::is('entrees/show/*')) {echo ' active in';}?> " id="Missionstab">
                              <!--<div class="tab-pane fade active in  scrollable-panel" id="Missionstab">-->

                               
                                <!-- treeview of notifications -->
                                <div id="accordionkbs">

                                <style scoped>
                               
                              .panel-heading.active {
                                background-color: #00BFFF /*#86B404  #2EFEF7;*/
                              }
                              .panel-heading.ColorerMissionsCourantes{
                                background-color: #ffd051 !important;  /*#5d9cec;*/
                                color: red;
                              }

                              .panel-heading.activeActDeleg {
                                background-color: #8A0808 /*#86B404  #2EFEF7;*/
                              }

                              </style>
                              <?php if (isset($act)){$currentMission=$act->id;}else{$currentMission=0;} ?>

                              <?php
                                     if (isset($dossier)){$dosscourant=$dossier->id ;
                              ;}else{$dosscourant=0;} ?>
                                    
                                    <div class="accordion panel-group" id="accordion">

                                 @if ($Missions || $MissionsDC )

                                  <div class="row">

                                <div class="col-md-4" >
                                  
                                </div>
                               
                                    <div class="col-md-8">
                                  <!--<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#toutesMissions">États de toutes les Missions</button> -->
                                  </div>

                                  </div>
                                  <br>
                                   <div class="row">
                                    <div class="col-md-8" >
                                    <!--<h4 >les Missions actives : </h4>-->
                                   </div>
                                   </div>
                                    <br>                             
                                  @endif

                                      <div id ="contenuMissions">
                                          @foreach( $MissionsDC as $Missidc)
                                     
                                      @if ( $Missidc->statut_courant =='active' || $Missidc->statut_courant =='deleguee')

                                      <div class="row" style="padding-bottom: 3px;">
                                      <div class="col-md-10">
                                      <div class="panel panel-default">

                                        <div class="panel-heading <?php if($Missidc->id ==$currentMission){echo 'active';}
                                        else {if($Missidc->dossier_id==$dosscourant){ echo 'ColorerMissionsCourantes' ;}}?>">

                                         
                                           <h4 class="panel-title">
                                              <a href="{{action('DossiersController@view',$Missidc->dossier->id)}}"> {{$Missidc->dossier->reference_medic}}&nbsp;-&nbsp;{{$Missidc->dossier-> subscriber_name}} {{$Missidc->dossier->subscriber_lastname}}</a>
                                             <br>
                                                {{$Missidc->typeMission->nom_type_Mission}}
                                               <a data-toggle="collapse" href="#collapse{{$Missidc->id}}">&nbsp; --</a>@if ($Missidc->assistant_id != NULL && $Missidc->emetteur_id != NULL && $Missidc->emetteur_id != $Missidc->assistant_id && $Missidc->emetteur_id!= $Missidc->user_id && $Missidc->statut_courant =='deleguee')
                                         <span style="color:#151515"> &nbsp;(déléguée par {{$Missidc-> emetteur->name}}&nbsp {{$Missidc->emetteur->lastname}} ) </span> @endif

                                         @if ($Missidc->miss_mere_id!=NULL )
                                         <span style="color:#151515"> &nbsp;(*Sous-Mission*) </span> @endif
                                           </h4>
                                        </div>

                                       <div id="collapse{{$Missidc->id}}" class="panel-collapse collapse in">
                                            <ul class="list-group">
                                              @foreach($Missidc->activeActionEC as  $sas)
                                              <li class="list-group-item"><a  href="{{url('dossier/Mission/TraitementAction/'.$sas->Mission->dossier->id.'/'.$sas->mission_id.'/'.$sas->id)}}">

                                                {{$sas->titre}} </a></li>
                                              @endforeach
                                            </ul>

                                        </div>


                                        <!-- /.panel-heading -->


                                      </div>
                                    </div>
                                    <div class="col-md-2" >

                                        <div class="dropdown" style="float:right;">
                                          <button class="dropbtn"><span class="fa fa-2x fa-tasks" aria-hidden="true"></span></button>
                                          <div class="dropdown-content" style="right:0;">
                                          <a class="DescripMission" id="<?php echo $Missidc->id ?>" href="javascript:void(0)">Descrip. Mission</a>
                                          <a class="etatAction" id="<?php echo $Missidc->id ?>" href="javascript:void(0)">Voir état actions</a>
                                          <a class="mailGenerateur" id="<?php echo $Missidc->id ?>" href="javascript:void(0)">Mail générateur</a>
                                          <a class="deleguerMission" id="<?php echo $Missidc->id ?>" href="javascript:void(0)">Déléguer Mission</a>
                                          <a class="annulerMission" id="<?php echo $Missidc->id ?>" href="javascript:void(0)">Annuler Mission</a>

                                           <input id="workflowh<?php echo $Missidc->id ?>" type="hidden" value="{{$Missidc->titre}}">
                                            <input id="workflowht<?php echo $Missidc->id ?>" type="hidden" value="{{$Missidc->typeMission->nom_type_Mission}}">


                                          </div>
                                        </div>

                                    </div>
                                    </div>
                                    @endif

                                    
                                     @endforeach

                                   <!--Missions affectée à l'utilisateur dont les dossiers sont affectés --> 

                                       @foreach($Missions as $t=>$k)
                                      @foreach ($k as $Mission)
                                      @if ( $Mission->statut_courant =='active' || $Mission->statut_courant =='deleguee')

                                      <div class="row" style="padding-bottom: 3px;">
                                      <div class="col-md-10">
                                      <div class="panel panel-default">

                                        <div class="panel-heading <?php if($Mission->id ==$currentMission){echo 'active';}
                                        else {if($Mission->dossier_id==$dosscourant){ echo 'ColorerMissionsCourantes' ;}}?>">

                                         
                                           <h4 class="panel-title">
                                              <a href="{{action('DossiersController@view',$Mission->dossier->id)}}"> {{$Mission->dossier->reference_medic}}&nbsp;-&nbsp;{{$Mission->dossier-> subscriber_name}} {{$Mission->dossier->subscriber_lastname}}</a>
                                             <br>
                                                {{$Mission->typeMission->nom_type_Mission}}
                                               <a data-toggle="collapse" href="#collapse{{$Mission->id}}">&nbsp; --</a>@if ($Mission->assistant_id != NULL && $Mission->emetteur_id != NULL && $Mission->emetteur_id != $Mission->assistant_id && $Mission->emetteur_id!= $Mission->user_id && $Mission->statut_courant =='deleguee')
                                         <span style="color:#151515"> &nbsp;(déléguée par {{$Mission-> emetteur->name}}&nbsp {{$Mission->emetteur->lastname}} ) </span> @endif

                                         @if ($Mission->miss_mere_id!=NULL )
                                         <span style="color:#151515"> &nbsp;(*Sous-Mission*) </span> @endif
                                           </h4>
                                        </div>

                                       <div id="collapse{{$Mission->id}}" class="panel-collapse collapse in">
                                            <ul class="list-group">
                                              @foreach($Mission->activeActionEC as  $sas)
                                              <li class="list-group-item"><a  href="{{url('dossier/Mission/TraitementAction/'.$sas->Mission->dossier->id.'/'.$sas->mission_id.'/'.$sas->id)}}">



                                                {{$sas->titre}} </a></li>
                                              @endforeach
                                            </ul>

                                        </div>


                                        <!-- /.panel-heading -->


                                      </div>
                                    </div>
                                    <div class="col-md-2" >

                                        <div class="dropdown" style="float:right;">
                                          <button class="dropbtn"><span class="fa fa-2x fa-tasks" aria-hidden="true"></span></button>
                                          <div class="dropdown-content" style="right:0;">
                                          <a class="DescripMission" id="<?php echo $Mission->id ?>" href="javascript:void(0)">Descrip. Mission</a>
                                          <a class="etatAction" id="<?php echo $Mission->id ?>" href="javascript:void(0)">Voir état actions</a>
                                          <a class="mailGenerateur" id="<?php echo $Mission->id ?>" href="javascript:void(0)">Mail générateur</a>
                                          <a class="deleguerMission" id="<?php echo $Mission->id ?>" href="javascript:void(0)">Déléguer Mission</a>
                                          <a class="annulerMission" id="<?php echo $Mission->id ?>" href="javascript:void(0)">Annuler Mission</a>

                                           <input id="workflowh<?php echo $Mission->id ?>" type="hidden" value="{{$Mission->titre}}">
                                            <input id="workflowht<?php echo $Mission->id ?>" type="hidden" value="{{$Mission->typeMission->nom_type_Mission}}">



                                          </div>
                                        </div>



                                     


                                            <!-- {{-- <a class="workflowkbs" id="<?php // echo $Mission->id ?>" style="color:black !important; margin-top: 10px; margin-right: 10px;" data-toggle="modal" data-target="#myworow" title ="Voir Workflow" href="#"><span class="fa fa-2x fa-tasks" style="  margin-right: 20px;" aria-hidden="true"></span>
                                            </a>
                                            <input id="workflowh<?php //echo $Mission->id ?>" type="hidden" value="{{$Mission->titre}}">
                                            <input id="workflowht<?php //echo $Mission->id ?>" type="hidden" value="{{$Mission->typeMission->nom_type_Mission}}"> --}} --> 


                                             {{-- <a  style="color:black !important; margin-top: 10px; margin-right: 10px;" title ="Voir Workflow" href="{{url('Mission/workflow/'.$Mission->dossier->id.'/'.$Mission->id)}}"><span class="fa fa-2x fa-cogs" style=" margin-top: 10px; margin-right: 20px;" aria-hidden="true"></span>
                                            </a>  --}}
                                                                         
                                        <?php $actk=$Mission;?>
                                     
                                    </div>
                                    </div>
                                    @endif

                                     @endforeach
                                     @endforeach

                                     <br><br>
                                    <!-- actions déléguées -->

                                      <div class="panel panel-default">

                                        <div class="panel-heading activeActDeleg">

                                         
                                           <h4 class="panel-title">
                                              <a data-toggle="collapse" href="">Actions déléguées</a>
                                           </h4>
                                        </div>

                                       <div id="" class="panel-collapse collapse in">
                                            <ul class="list-group">
                                             {{-- @foreach($Mission->activeActionEC as  $sas) --}}

                                             <?php  $actionsDeleg = App\ActionEC::whereNotNull('statut')->where('statut','=','deleguee')->where('assistant_id','=', Auth::user()->id)->get(); ?>
                                           @if(isset($actionsDeleg) && !Empty($actionsDeleg) && count($actionsDeleg) > 0)

                                            @foreach($actionsDeleg as  $sas)

                                             <li class="list-group-item"><a  href="{{url('dossier/Mission/TraitementAction/'.$sas->Mission->dossier->id.'/'.$sas->mission_id.'/'.$sas->id)}}">

                                                Action: {{$sas->titre}}</a> <br> (Dossier: {{$sas->Mission->dossier->reference_medic}} / Mission:  {{$sas->Mission->titre}} / Type de mission : {{$sas->Mission->typeMission->nom_type_Mission}} / Affectée par: {{$sas->agent->name}} {{$sas->agent->lastname}} ) </li>
                                                @endforeach
                                         
                                           @else

                                            <li class="list-group-item">Il n'y a pas des actions déléguées</li>
                                          
                                           @endif
                                            </ul>

                                        </div>


                                        <!-- /.panel-heading -->


                                      </div>


                                      </div><!--  kkkk-->


                                              </div>

                                        </div>

                                        </div>


                                    <!-- fin  Missions tab---------------------------------------------------------->



                 
                                                               <!-- début creation nouvelle Missions tab------------------------>

                                    <div class="tab-pane fade  scrollable-panel" id="newMissiontab">



                                   <div class="row text-center">
                                                           <h4>Création d'une nouvelle Mission</h4>

                                                            
                              <div class="card-header">
                                
                              </div>
                              <div class="card-body">
                                  @if ($errors->any())
                                      <div class="alert alert-danger">
                                          <ul>
                                              @foreach ($errors->all() as $error)
                                                  <li>{{ $error }}</li>
                                              @endforeach
                                          </ul>
                                      </div><br />
                                  @endif

                                  <!-- input pour sauvgarder l id de l entree lors de click (iheb)(et voir l'autre input hidden dans le formulaire suivant qui va contenir id entree lors de markage (haithem) et creation de mission-->

                                 <input id="idEntreeMissionOnclik" type="hidden" class="form-control" value="" name="idEntreeMissionOnclik"/>

                                  <?php if(isset($dossier)) {  ?>
                                    <?php if($dossier) {  ?>
                                      <?php if($dossier->current_status != 'Cloture') {  ?>
                                  <form  id="idFormCreationMission" method="post" style="padding-top:30px">
                                   <input id="idEntreeMissionOnMarker" type="hidden" class="form-control" value="" 
                                   name="idEntreeMissionOnMarker"/>


                                      <div class="form-group">
                                           {{ csrf_field() }}

                                        <div class="row">
                                             <div class="col-md-3" style="padding-top:5px"> <label  style=" ;  text-align: left; width: 55px;">Extrait:</label></div>
                                             <div class="col-md-9"><input id="titre" type="text" class="form-control" style="width:95%;  text-align: left !important;" name="titre"/></div>
                                       </div>
                                       <br>
                                        <!-- input pour l'autocomplete type Mission -->
                                          <div class="form-group">

                                               <div class="row">
                                                <div class="col-md-3" style="padding-top:5px">  <label for="typeactauto" style="display: inline-block;  text-align: left; width: 50px;">Type:</label></div>
                                                <div class="col-md-9" style=" margin-left: -9px ; "> 
                                                 <!-- <input id="typeactauto" type="text" value="" class="form-control" style="width:95%;  text-align: left;" name="typeactauto" autocomplete="off" />-->


                                          <select id="typeMissauto" name="typeMissauto" class="form-control select2" style="width:95%; border: 1px solid #ccc; height: 32px">
                                            <option value="">Sélectionner</option>
                                         @foreach( $typesMissions as $c) 

                                          <option value="{{$c->id}}">{{$c->nom_type_Mission}} </option>

                                         @endforeach



                                         </select>





                                                 <div id="listtypeact" style=" left:-50px; z-index: 9999; width: 200 px;"> </div>


                                                 <script> $(document).ready(function(){

                                                   $(document).on("change","#typeMissauto",function() {

                                                if ($(this).val()=="Transports terrestres effectué par entité-sœur MMS" || $(this).val()=="Transport terrestre effectué par prestataire externe")
                                                {
                                                  //alert($(this).val());
                                                  $("#idDateSpecifique").empty().append("<span style='color:red'> la date ci dessous indique la date de départ d'avion <span>");
                                                }
                                                else
                                                {

                                                  $("#idDateSpecifique").empty();

                                                };                                                  ;

                                                });

                                                     $("#typeMissauto").select2();

                                                     $("#typeactauto").keyup(function(){

                                                      var qy=$(this).val();

                                                      if(qy != ''){

                                                        var _token=$('input[name="_token"]').val();

                                                        $.ajax({

                                                          url:"{{ route('typeMission.autocomplete')}}",
                                                          method:"POST",
                                                          data:{qy:qy, _token:_token},
                                                          success:function(data)
                                                          {


                                                           // alert(data);

                                                           //$("#listtypeact").fadeIn();
                                                            //$("#listtypeact").html(data);

                                                          }


                                                        });


                                                      }


                                                     });


                                                    
                                                 });
                                                 



                                                  </script>

                                                  <script>
                                                     $(document).on('click','.resAutocompTyoeAct',function(){

                                                      //alert("bonjour");

                                                    $("#typeactauto").val($(this).text());
                                                    $("#listtypeact").fadeOut();


                                                     });



                                                  </script>

                                                </div>
                                            </div>

                                          </div>
                                          <div class="form-group">
                                           <div class="row"> 
                                            <div class="col-md-3">
                                            </div>
                                            <div class="col-md-9">
                                           <div id="idDateSpecifique"> </div>
                                            </div>
                                          </div>

                                        </div>
                                         <div class="form-group">
                                            <?php  $da = (new \DateTime())->format('Y-m-d\TH:i');// $da= date('Y-m-d\TH:m'); ?>

                                                <div class="row">
                                                    <div class="col-md-3" style="padding-top:5px">  <label for="datedeb" style="display: inline-block;  text-align: left; width: 55px;">Date:</label></div>
                                                    <div class="col-md-9"> <input id="datedeb" type="datetime-local" value="<?php echo $da ?>" class="form-control" style="width:95%;  text-align: left;" name="datedeb"/></div>
                                                </div>
                                         </div>
                                        <!--<div class="form-group">
                                          <div class="row">
                                              <div class="col-md-3" style="padding-top:5px">     <label for="descrip" style="display: inline-block;  text-align: right; width: 55px;">Description:</label></div>
                                              <div class="col-md-9"><input id="descrip" type="text" class="form-control" style="width:95%;  text-align: left;" name="descrip"/></div>
                                          </div>
                                        </div>-->
                                        </br>
                                       <div class="row">
                                             <div class="col-md-3" style="padding-top:5px"> <label  style=" ;  text-align: left; width: 55px;">Commentaire:</label></div>
                                             <div class="col-md-9"><textarea id="commentaire" class="form-control" style="width:95%;  text-align: left !important;" name="commentaire"></textarea></div>
                                       </div>
                                      </div>

                                           


                                      <div class="form-group">

                                           <?php if(isset($dossier)) {  ?>
                                          
                                          <input id="" type="hidden" class="form-control" value="" name="dossier"/>
                                          <input id="dossierID" type="hidden" class="form-control" value="{{$dossier->id}}" name="dossierID"/>
                                          <input id="hreftopwindow" type="hidden" class="form-control" value="" name="hreftopwindow"/>
                                            

                                    

                                          <?php } else {  ?>
                                               <div class="row">

                                               <div class="col-md-3" style="padding-top:5px">     <label for="typeact" style="display: inline-block;  text-align: right; width: 40px;">Réf dossier</label></div>
                                                  <div class="col-md-9"> <input id="dossier" type="text" class="form-control" value="" name="dossier"/></div>
                                               </div>

                                           <?php } ?>

                                      </div>
                                       <!--<button  type="submit"  class="btn btn-success">Ajouter</button>-->
                                        <br><br>
                                        <button  id="idAjoutMiss" type="button" onClick="this.disabled=true; creerNouvelleMission();" class="btn btn-success">Ajouter la mission</button>
                                        <br><br><br>
                                       <!-- <button  id="idFinAjoutMiss" type="button"  class="btn btn-danger">Fin ajout de missions</button>-->

                                     <!-- <button id="add"  class="btn btn-primary">Ajax Add</button>-->
                                  </form>
                                     <?php } else { ?>

                                       <div> <h4 style="color:red;">Vous ne pouvez pas créer des missions dans ce dossier car il est clos.</h4> </div>

                                       <?php } ?><!-- fin if($dossier->current_status)!=cloture-->
                                     <?php } ?> <!-- fin if($dossier)!=null-->
                                   <?php } else {?>

                                    <div> <h4 style="color:red;">Vous devez sélectionner un dossier pour créer une mission </h4> </div>

                                     <?php } ?>
                               </div>   



                                   </div>
                                                                   
                    </div>
                    <?php if (\Request::is('entrees/show/*')) { ?>
                     <!-- Informations tab------------------------>
                     <div class="tab-pane fade <?php if (\Request::is('entrees/show/*')) {echo ' active in';} ?> " id="infostab" style="overflow-x: hidden;">
                      <div class="row text-center">
                        <div class="col-md-6" >
                          <button id="btn-atag" class="btn btn-default "  style="background-color: #A9A9A9">Ajouter TAG</button>
                        </div>
                        <div class="col-md-6" >
                          <button id="btn-cmttag" class="btn btn-default default-hovered">TAG & Commentaire</button>
                        </div>
                      </div>    
                      <?php 
                      
                      $tags = array();
                      // recuperer tag des attachements de l'entree
                      $colattachs = Attachement::where("parent","=",$entree['id'])->get();
                        if (!empty($colattachs))
                        {
                            foreach ($colattachs as $lattach) {
                                $coltagsattach = Tag::get()->where('entree', '=', $lattach['id'] )->where('type', '=', 'piecejointe');
                                $tags = array_merge($tags,$coltagsattach->toArray());
                            }
                        }

                      $tagsentree = Tag::where(['entree' => $entree['id'], 'dernier' => 1 ])->orderBy('created_at','desc')->get();
                      $tags = array_merge($tags,$tagsentree->toArray());
 $columns = array_column($tags, 'created_at');
array_multisort($columns, SORT_DESC, $tags);
        
                      
                       ?>  
                      <div id="ajouttag" style="display:none;margin-top: 30px">
                        <input type="hidden" name="dossieridtag" id="dossieridtag" value="<?php echo $dosscourant; ?>">
                        <?php if (count($tags) > 0) { ?>
                           <div id="norrtags" class="form-group mar-20">
                                <input id="cajout" type="radio" name="ajout_remplace" value="ajouttag" checked >
                                <label for="cajout">Nouveau</label>

                                <input id="cremplace" type="radio" name="ajout_remplace" value="remplacetag" style="margin-left: 30px">
                                <label for="cremplace">Remplacer</label>
                           </div>
                        <?php } ?>
                           <div class="form-group mar-20">
                                <label for="tagname" class="control-label" style="padding-right: 20px">TAG</label>
                                <select id="tagname" name="tagname" class="form-control select2" style="width: 230px">
                                    <option value="Select">Selectionner</option>
                                        <option value="Franchise">Franchise (frais médicaux)</option>
                                        <option value="Plafond">Plafond (frais médicaux)</option>
                                        <option value="GOPmed">GOP (frais médicaux)</option>
                                        <option value="PlafondRem">Plafond (remorquage)</option>
                                        <option value="GOPtn">GOP (toutes natures)</option>
                                        <option value="RM">RM (rapport médical)</option>
                                        <option value="RMtraduit">RM (rapport médical) traduit</option>
                                        <option value="CT">CT (contact technique)</option>
                                        <option value="DOCasigner">Doc à signer (LE, DAFM, DFM)</option>
                                        <option value="RE">RE (rapport d’expertise)</option>
                                        <option value="RDD">RDD</option>
                                        <option value="DDR">DDR (Décharge de responsabilité)</option>
                                        <option value="Procuration">Procuration</option>
                                        <option value="NAF">Mail/Fax d’ouverture (NAF)</option>
                                        <option value="EAF">Entité à facturer</option>
                                        <option value="PCFP">Passeport/ CIN + fiche de police</option>
                                        <option value="CG">Carte grise</option>
                                        <option value="Dyptique">Dyptique</option>
                                        <option value="PVpolice">PV de police</option>
                                        <option value="PVehicule">Photo de véhicule</option>
                                        <option value="Billet">Billets d’avion/Train</option>
                                        <option value="MEDIF">MEDIF rempli</option>
                                        <option value="PF">Patient form</option>
                                        <option value="CF">Consent form</option>
                                </select>
                                <select id="tagslist" name="tagslist" class="form-control select2" style="width: 230px;display:none">
                                    
                                     @foreach( $tags as $tag)
                                        <option value={{$tag['id']}}>{{$tag['titre']}} | {{$tag['contenu']}} | 
                                        <?php if ((isset($tag['montant'])) && (! empty($tag['montant']))) { 
                                                  if ($tag['montant'] !== null){
                                              ?> {{$tag['montant']}} {{$tag['devise']}}
                                        <?php }} ?>
                                        </option>
                                     @endforeach
                                </select>
                            </div>
                            <div id="champstags" class="form-group mar-20"></div>
                            <input id="contenutag" name="contenutag" class="form-control resize_vertical" placeholder="Entrer la description de TAG" data-bv-field="message" style="width: 280px"></input></br>
                            
                             <div class="row text-center">
                              <div class="col-md-4" >
                                <button id="btn-addtag" type="submit" onclick="document.getElementById('btn-addtag').disabled=true"  class="btn btn-danger">Ajouter</button>
                              </div>
                              <div class="col-md-8" >
                                <span id="addedsuccess" style="color:green;display: none">✓ Le TAG est ajouté avec succés</span>
                                <span id="addedfail" style="color:red;display: none">✖ Erreur lors de l'ajout de TAG</span>
                              </div>
                              {{ csrf_field() }}
                            </div> 
                      </div>   
                      <div id="cmttag"  style="display:block;margin-top: 30px">
                          <div class="row">
                            <div class="col-md-6"><label for="commentuser" class="control-label" >Description</label></div>
                            <div class="col-md-3 pull-right"><button id="editbtn" type="button" class="btn btn-info btn-xs" ><i class="fas fa-lock-open"></i> Modifier</button></div>
                          </div>
                          <textarea id="commentuser" name="commentuser" rows="7" class="form-control resize_vertical" placeholder="Entrez votre Description" <?php if($entree['commentaire']!=''){echo 'readonly'; }?> >{{ $entree['commentaire']  }}</textarea></br>
                          <!-- affichage des tags -->
                          <label for="accordiontags" class="control-label" >TAGs</label>
                          <div class="accordion panel-group" id="accordiontags">
  
    
                 
<table   bordercolor="#FD9883" class="table table-striped" id="tabletags" style="width:20%;margin-top:15px;">
                            <thead  style=" background-color: #FD9883;">
                            <tr id="headtable" style=" background-color: #FD9883;">
                                <th style="">Titre</th>
                                <th style="">contenu</th>
                                <th style="">Montant</th>
                               
                             </tr>

                            </thead>
                            <tbody>

 @foreach( $tags as $tag)
                                  <tr>
                                <td style="">{{$tag['titre']}}  </td>

                                <td style="">{{$tag['contenu']}} </td>
 <?php if ((isset($tag['montant'])) && (! empty($tag['montant']))) { 
                                                    if ($tag['montant'] !== null){
                                                ?>
                                <td style="">{{$tag['montant']}}{{$tag['devise']}}</td>
 <?php }}else { ?>
                            
 <td style=""></td>
   <?php }?>

                             </tr>
 @endforeach
                            </tbody>
                    </table>

                                    </div>
                      </div>                                       
                    </div>
                    <?php } ?>    
                   <!-- fin creation nouvelle Missions tab------------------------>        

                    @endif

                    <?php } ?>

 <!--  Fin tab -+----------------------------------------------------------------------------------------->     
                       </div>

                 </div>
            </div>

<!-- modal pour creer un nouveau type de Mission--> 

            <div class="modal fade" id="NouveauType"    role="dialog" aria-labelledby="basicModal" aria-hidden="true">
             <div class="modal-dialog">
             <div class="modal-content">
             <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
             <h4 class="modal-title" id="myModalLabel">Créer un nouveau type de Mission</h4>
             </div>
             <div class="modal-body">
               <h3>Mettre les étapes en ordre</h3>
                <div class="field_wrapper">
                                       <div>
                                    <input type="text" name="field_name[]" value=""/>
                                   <a href="javascript:void(0);" class="add_button" title="Add field"><img width="26" height="26" src="{{ asset('public/img/plus.png') }}"/></a>
                                      </div>
                                    </div>
             </div>
             <div class="modal-footer">
             <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
             <button type="button" class="btn btn-primary">Enregister</button>
             </div>
                 </div>
             </div>
            </div>



 <!-- Modal pour toutes les etats Missions ------------->


           <style>

              .results tr[visible='false'],
              .no-result{
                    display:none;
                  }

                  .results tr[visible='true']{
                    display:table-row;
                  }

                  .counter{
                    padding:8px; 
                    color:#ccc;
                  }
          </style>

              <div id="toutesMissions" class="modal fade" role="dialog">
                <div class="modal-dialog">

                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">États de toutes les Missions de dossier courant</h4>
                    </div>
                    <div class="modal-body">
                      <p>
                             <div class="form-group pull-right">
                              <input type="text" class="search form-control" placeholder="Recherhe">
                          </div>
                          <span class="counter pull-right"></span>
                          <table class="table table-hover table-bordered results">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th class="col-md-5 col-xs-5">Nom de l'Mission</th>
                                <th class="col-md-5 col-xs-5">Date création</th>
                                <th class="col-md-4 col-xs-4">État</th>
                                <th class="col-md-3 col-xs-3">Modifier état</th>
                              </tr>
                              <tr class="warning no-result">
                                <td colspan="4"><i class="fa fa-warning"></i> Pas de résultats</td>
                              </tr>
                            </thead>
                          
                          </table>
                      </p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    </div>
                  </div>

                </div>
              </div>

             <!--  re -->
              <script>

                  $(document).ready(function() {
                    $(".search").keyup(function () {
                      var searchTerm = $(".search").val();
                      var listItem = $('.results tbody').children('tr');
                      var searchSplit = searchTerm.replace(/ /g, "'):containsi('")
                      
                    $.extend($.expr[':'], {'containsi': function(elem, i, match, array){
                          return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
                      }
                    });
                      
                    $(".results tbody tr").not(":containsi('" + searchSplit + "')").each(function(e){
                      $(this).attr('visible','false');
                    });

                    $(".results tbody tr:containsi('" + searchSplit + "')").each(function(e){
                      $(this).attr('visible','true');
                    });

                    var jobCount = $('.results tbody tr[visible="true"]').length;
                      $('.counter').text(jobCount + ' résultat(s)');

                    if(jobCount == '0') {$('.no-result').show();}
                      else {$('.no-result').hide();}
                        });
                  });

              </script>


<!-- --------- fin modal ------------------------------------------------------------->

<!------------- Modal workflow ---------------------------------------------------------------- -->
<style>


</style>

  <div class="modal fade" id="myworkflow" role="dialog" >
    <div class="modal-dialog modal-lg" >
    
      <!-- Modal content-->
      <div class="modal-content" >
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 id="titleworkflowmodal" class="modal-title"></h4>
        </div>
        <div class="modal-body">
         

  <div id="contenumodalworkflow" style="background-color: #ABF8F8;padding:5px 5px 5px 5px" >

               
  </div>


       
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
        </div>
      </div>
      
    </div>
  </div> <!-- fin modal workflow-->


<!-- model pour délégation des missions-->

<div class="modal fade" id="modalDelegMissAjax" role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
    <div class="modal-dialog" id="contenuModalDeleguerMission" role="document">
       
    </div>
</div>



  
</div>
<!--fin modal -->

<!-- pour l'Mission libre-->

<script src="{{ asset('public/js/select2/js/select2.js') }}"></script>
 <script type="text/javascript">
  
 function creerNouvelleMission(){

 // $("#idAjoutMiss").click(function(e){ // On sélectionne le formulaire par son identifiant
   // e.preventDefault(); // Le navigateur ne peut pas envoyer le formulaire
     //alert('ok');

     //$("#idAjoutMiss").attr("disabled", true);
     //document.getElementById('idAjoutMiss').disabled=true;
      // $(document).on('')
     var en=true;

     if(!$('#idFormCreationMission #titre').val())
     {

      alert('vous devez remplir le champs extrait');
      en=false;

     }

     if(!$('#idFormCreationMission #typeMissauto').val())
     {

      alert('vous devez sélectionner le type de mission');
      en=false;

     }

  

    if(!$('#idFormCreationMission #dossierID').val())
     {

      alert('vous devez sélectionner un dossier pour créer une mission ou créer une mission à partir d\'un email');
      en=false;

     }
     
 

   if(en==true)
   {
    var donnees = $('#idFormCreationMission').serialize(); // On créer une variable content le formulaire sérialisé
    var _token = $('input[name="_token"]').val();
    $.ajax({

           url:"{{ route('Mission.StoreMissionByAjax') }}",
           method:"post",
           data:donnees,
           success:function(data){
         
                alert("Mission créée");
                //alert(data);
                 $('#idFormCreationMission #typeMissauto').val('');
                 //$('#idFormCreationMission #typeMissauto option:eq(1)').prop('selected', true);
                //$('#idFormCreationMission #typeMissauto').text('Sélectionner');
                $('#idFormCreationMission #titre').val('');
                //$('#typeMissauto option[value=selectkbs]').attr("selected", "selected");
                $("#idFormCreationMission #typeMissauto").select2("val", "Sélectionner");

                 $('#idFormCreationMission #datedeb').val(data);

                  $('#idFormCreationMission #commentaire').val('');

                },
            error: function(jqXHR, textStatus, errorThrown) {

              alert('erreur lors de création de la mission');


            }

   
    });
  }

  //$("#idAjoutMiss").attr("disabled",false);
   document.getElementById('idAjoutMiss').disabled=false;

//});
}


     $(document).ready(function(){
    var maxField = 10; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var fieldHTML = '<div><input type="text" name="field_name[]" value=""/><a href="javascript:void(0);" class="remove_button"> <img width="26" height="26" src="{{ asset('public/img/moin.png') }}"/></a></div>'; //New input field html 
    var x = 1; //Initial field counter is 1
    
    //Once add button is clicked
    $(addButton).click(function(){
        //Check maximum number of input fields
        if(x < maxField){ 
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); //Add field html
        }
    });
    
    //Once remove button is clicked
    $(wrapper).on('click', '.remove_button', function(e){
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });

    $('#btn-atag').click(function(){
      $("#cmttag").hide();
      $("#ajouttag").show();
      $('#btn-atag').toggleClass("default-hovered");
      $('#btn-cmttag').removeClass("default-hovered");
    });  
 $('#btn-cmttag').click(function(){
      $("#ajouttag").hide();
      $("#cmttag").show();


      $('#btn-cmttag').toggleClass("default-hovered");
      $('#btn-atag').removeClass("default-hovered");
var entree = $('ul#mailpiece').find('li.active').data('identreeattach');
var type = $('ul#mailpiece').find('li.active').data('type');
  // alert(entree);
//alert(type);

                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('tags.entreetags') }}",
                    method:"POST",

                    data:{entree:entree,type:type, _token:_token},
                    success:function(data){


                  

                    var tags = JSON.parse(data);
                    // vider le contenu du table 
                    $("#tabletags tbody").empty();
                    var items = [];
                    $.each(tags, function(i, field){
                      items.push([ i,field ]);
                    });
                    // affichage template dans iframe
                    $.each(items, function(index, val) {

                    //titre du document
                    if (val[0]==0)
                    {
                        $("#tags").text(val[1]['titre']);
                    }


                if (val[1]['montant']==null)   
{montant="";} 
else   
{montant=val[1]['montant']+val[1]['devise'];}  
                    $("#tabletags tbody").append("<tr><td>"+val[1]['titre']+"</td><td>"+val[1]['contenu']+"</td><td>"+montant+"</td></tr>");
                    
                    });

                   
                },
                error: function(jqXHR, textStatus, errorThrown) {


                    Swal.fire({
                        type: 'error',
                        title: 'Oups...',
                        text: 'Erreur lors de recuperation de tags',

                    });
                    console.log('jqXHR:');
                    console.log(jqXHR);
                    console.log('textStatus:');
                    console.log(textStatus);
                    console.log('errorThrown:');
                    console.log(errorThrown);
                }
            });
var entree = $('ul#mailpiece').find('li.active').data('identreeattach');
var type = $('ul#mailpiece').find('li.active').data('type');
   //alert(entree);
//alert(type);
var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('tags.entreetags1') }}",
                    method:"POST",

                    data:{entree:entree,type:type, _token:_token},
                    success:function(data){


                  

                    var comment = JSON.parse(data);
document.getElementById('commentuser').value=comment;}
}); 
                }); 
$("#tagname").select2();
$("#tagslist").select2();
$('input[type=radio][name=ajout_remplace]').change(function() {
    if (this.value == 'ajouttag') {
        $("#tagname").next(".select2-container").show();
        if($('#tagname option:selected').val().match(/^(Franchise|Plafond|GOPmed|PlafondRem|GOPtn)$/))
        { //posséde champs montant
          if ($('#champstags').html() === "")
          {
            
            $('#champstags').html("<div class='row'><div class='col-md-7' style='padding-left: 0px;'><input type='text' id='montanttag'  name='montanttag' class='form-control' style='width: 150px' placeholder='Entrer le montant' required></div><div class='col-md-3'><select name='devise'><option value='TND'>TND</option><option value='USD'>USD</option><option value='EUR'>EUR</option></select></div></div>");
            // verifier valeur montant si entier
            $('#montanttag').keyup(function() {
                var val = $(this).val(), error ="";
                $('#lblIntegerError').remove();
                $('#btn-addtag').removeAttr('disabled');
                if (isNaN(val)) {
                  error = "le montant doit être numérique";
                  $('#btn-addtag').prop("disabled",true);
                }
                else if (parseInt(val,10) != val || val<= 0) {
                  error = "le montant doit être supérieure à zero";
                  $('#btn-addtag').prop("disabled",true);
                }
                else {return true;  }
                $('#montanttag').after('<label style="color:red"  id="lblIntegerError"><br/>'+error+'</label>');
                return false;
            });
          }
        }
      else
        {
          if ($('#champstags').html() !== "")
          {
            $('#champstags').html("");
          }
        }
        $("#tagslist").next(".select2-container").hide();
    }
    else if (this.value == 'remplacetag') {
        $("#tagname").next(".select2-container").hide();
        var tname = $('select[name=tagslist] option:selected').text();

        if ((tname.indexOf("GOP") != -1) || (tname.indexOf("Plafond") != -1) || (tname.indexOf("Franchise") != -1))
                { //posséde champs montant
                  if ($('#champstags').html() === "")
                  {
                    
                    $('#champstags').html("<div class='row'><div class='col-md-7' style='padding-left: 0px;'><input type='text' id='montanttag'  name='montanttag' class='form-control' style='width: 150px' placeholder='Entrer le montant' required></div><div class='col-md-3'><select name='devise'><option value='TND'>TND</option><option value='USD'>USD</option><option value='EUR'>EUR</option></select></div></div>");
                }}else
                {
                  if ($('#champstags').html() !== "")
                  {
                    $('#champstags').html("");
                  }
                }
        $("#tagslist").next(".select2-container").show();
    }
});
$('#tagslist').change(function(e){
var tname = $('select[name=tagslist] option:selected').text();

if ((tname.indexOf("GOP") != -1) || (tname.indexOf("Plafond") != -1) || (tname.indexOf("Franchise") != -1))
        { //posséde champs montant
          if ($('#champstags').html() === "")
          {
            
            $('#champstags').html("<div class='row'><div class='col-md-7' style='padding-left: 0px;'><input type='text' id='montanttag'  name='montanttag' class='form-control' style='width: 150px' placeholder='Entrer le montant' required></div><div class='col-md-3'><select name='devise'><option value='TND'>TND</option><option value='USD'>USD</option><option value='EUR'>EUR</option></select></div></div>");
        }}else
        {
          if ($('#champstags').html() !== "")
          {
            $('#champstags').html("");
          }
        }
});
$('#tagname').change(function(e){

  if ($('#tagname option:selected').val() != null)

    {$('#btn-addtag').prop("disabled",false);
if($('#tagname option:selected').val().match(/^(Franchise|Plafond|GOPmed|PlafondRem|GOPtn)$/))
        { //posséde champs montant
          if ($('#champstags').html() === "")
          {
            
            $('#champstags').html("<div class='row'><div class='col-md-7' style='padding-left: 0px;'><input type='text' id='montanttag'  name='montanttag' class='form-control' style='width: 150px' placeholder='Entrer le montant' required></div><div class='col-md-3'><select name='devise'><option value='TND'>TND</option><option value='USD'>USD</option><option value='EUR'>EUR</option></select></div></div>");
            // verifier valeur montant si entier
            $('#montanttag').keyup(function() {
                var val = $(this).val(), error ="";
                $('#lblIntegerError').remove();
                $('#btn-addtag').removeAttr('disabled');
                if (isNaN(val)) {
                  error = "le montant doit être numérique";
                  $('#btn-addtag').prop("disabled",true);
                }
                else if (parseInt(val,10) != val || val<= 0) {
                  error = "le montant doit être supérieure à zero";
                  $('#btn-addtag').prop("disabled",true);
                }
                else {return true;  }
                $('#montanttag').after('<label style="color:red"  id="lblIntegerError"><br/>'+error+'</label>');
                return false;
            });
          }
        }
      else
        {
          if ($('#champstags').html() !== "")
          {
            $('#champstags').html("");
          }
        }}
});


$('#editbtn').click(function(){
var type = $('ul#mailpiece').find('li.active').data('type');

   // alert(type);
if(type=="email"){
url="{{ route('entrees.savecomment') }}";}
if(type=="piecejointe"){
url="{{ route('attachements.savecomment') }}";}
    if ($('textarea#commentuser').is('[readonly]') )
      { $('textarea#commentuser').attr('readonly',false);}
    else
      {$('textarea#commentuser').attr('readonly',true);}
});

$('#btn-addtag').click(function(e){

var entree = $('ul#mailpiece').find('li.active').data('identreeattach');
    //alert(entree);
var type = $('ul#mailpiece').find('li.active').data('type');
   // alert(type);

      var dossier = $('input[name="dossieridtag"]').val();
      var tag = $('select[name=tagname]').val();
      var tagtxt = $('select[name=tagname] option:selected').text();
      var tagcontent = $('input#contenutag').val();
      var _token = $('input[name="_token"]').val();
      var urladdtag = $('input[name="urladdtag"]').val();
      var montant= null;
      var devise = null;
      var limontant='';
      var tparent = null;
      var tremplace = false;
      <?php if (\Request::is('entrees/show/*')) { 
            if (count($tags) > 0) { ?>
            var remplaceouajout = $('input[type=radio][name=ajout_remplace]:checked').val();    
            //alert(remplaceouajout);  
            // CAS REMPLACE: recuperer parent et titre du TAG
            if (remplaceouajout=="remplacetag")
            {
              var tagparent = $('select[name=tagslist]').val();
              tremplace = true;
              tparent = tagparent;
              //alert(tagparent);

              var prttitre = $('select[name=tagslist] option:selected').text();
              var titretag = prttitre.substring(0, prttitre.indexOf(" | "));
              tag =String(titretag);
              
              //alert(titretag);
            }

      <?php }} ?>
if (document.getElementById("montanttag")!=null)
      {
        montant = $('input[name="montanttag"]').val();

        devise = $('select[name=devise] option:selected').text();
      
      }
      
            if (entree != '')
            {


                $.ajax({
                    url:urladdtag,
                    method:"POST",
                      data:{entree:entree,type:type,dossier:dossier,titre:tag,contenu:tagcontent,montant:montant,devise:devise,parent:tparent, _token:_token},
                    success:function(data){
                      if (data.indexOf("par: ") >= 0)
                      {
                        Swal.fire({
                          type: 'error',
                          title: 'le plafond du dossier est dépassé '+data+' TND',
                          text: '',

                        });
                        return false;
                      }
                        $("#addedsuccess").fadeIn(1500);
                        $("#addedsuccess").fadeOut(1500);

                        // recharger la page
                        location.reload();
                        // ajouter la nouvelle tag dans la section cmttags
                        

                        /*$('input#contenutag').val('');
                        $('#tagname').val(null).trigger('change');
                        if (document.getElementById("montanttag")!=null)
                        {
                          $('#champstags').html("");
                        }*/
                    
                    }
                    ,
                    fail: function(xhr, textStatus, errorThrown){
                       $("#addedfail").fadeIn(1500);
                       $("#addedfail").fadeOut(1500);
                    }
                });
            }
            else{
            alert('ERROR url tag');
            }
            //alert('info: '+infotag+' | content: '+tagcontent);
});

    // auto enregistrement de commentaire
    var timeoutId;  


    $('#commentuser').keypress(function () {
var type = $('ul#mailpiece').find('li.active').data('type');

    //alert(type);
if(type=="email"){
url="{{ route('entrees.savecomment') }}";}
if(type=="piecejointe"){
url="{{ route('attachements.savecomment') }}";}
        if (timeoutId) clearTimeout(timeoutId);

        var _token = $('input[name="_token"]').val();
        var entree = $('ul#mailpiece').find('li.active').data('identreeattach');
 

        timeoutId = setTimeout(function () {
            $.ajax({
                url: url,
                method:"POST",
                data: { entree: entree, commentaire: $('textarea#commentuser').val(), _token:_token },
                success:function(data){
                    $('#commentuser').animate({
                    opacity: '0.3',
                    });
                    $('#commentuser').animate({
                        opacity: '1',
                    });
                }
            });
        }, 550);
    });
});
</script>


<!-- mettre à jour le workflow par ajax-->
<script>
$("#workflowform input:checkbox").change(function() {
    var ischecked= $(this).is(':checked');
    if(!ischecked)
      alert('uncheckd ' + $(this).val());
    else
    {
      //alert('checkd ');

     // $("#workflowform").submit(function(e){ // On sélectionne le formulaire par son identifiant
        // e.preventDefault(); // Le navigateur ne peut pas envoyer le formulaire

         var donnees = $("#workflowform").serialize(); // On créer une variable content le formulaire sérialisé
       // alert (donnees);
           $.ajax({

               url : '{{URL('/Mission/updateworkflow/')}}',
               type : 'POST',
               data : donnees,
               success: function(retour){
               
                  //alert(JSON.stringify(retour))   ;
                  location.reload();
            }

              //...

           });

            //});

    }
});
</script>
    <?php
  $param= App\Parametre::find(1);$env=$param->env;
$urlapp="http://$_SERVER[HTTP_HOST]/".$env;
    ?>

    <!-- get modal workflow by ajax -->
<script>

$(document).ready(function() {
  $("#tagslist").next(".select2-container").hide();
  $('.workflowkbs').on('click', function() {


   var idw=$(this).attr("id");
   //alert(idw);
   var nomact=$('#workflowh'+idw).attr("value");
   var typemiss=$('#workflowht'+idw).attr("value");
      $("#titleworkflowmodal").empty().append('<b>Mission: '+nomact+' (type de Mission: '+typemiss+')</b>');//ou la methode html

           $.ajax({

              // url: "<?php //echo $urlapp; ?>/Mission/getAjaxWorkflow/"+idw,
               type : 'GET',
              // data : 'idw=' + idw,
               success: function(data){
               
              // alert(data);

               //alert(JSON.stringify(data));
              $('#contenumodalworkflow').html(data);

              $('#myworkflow').modal('show');

                  //alert(JSON.stringify(retour))   ;
                 // location.reload();
            }

             
           });

  });


  //   -----------------


//--script pour la description de mission et les dates speciales

$('.DescripMission').on('click', function() {


   var idwde=$(this).attr("id");
  // alert(idw);
   var nomact=$('#workflowh'+idwde).attr("value");
   //alert
   var typemiss=$('#workflowht'+idwde).attr("value");
      $("#titleworkflowmodal").empty().append('<b>Mission: '+nomact+' (type de Mission: '+typemiss+')</b>');//ou la methode html

           $.ajax({

               url: "{{ url('/') }}/Mission/getDescriptionMissionAjax/"+idwde,
               type : 'get',
              // data : 'idw=' + idw,
               success: function(data){
               
              // alert(data);

               //alert(JSON.stringify(data));
              $('#contenumodalworkflow').html(data);

              $('#myworkflow').modal('show');

                  //alert(JSON.stringify(retour))   ;
                 // location.reload();
            }

             
           });

  });
  


//-- script click etat action
 //$('.etatAction').on('click', function() {

   $(document).on('click','.etatAction', function() {


   var idwe=$(this).attr("id");
  // alert(idw);
   //var nomact=$('#workflowh'+idw).attr("value");
  
   //var typemiss=$('#workflowht'+idw).attr("value");
      //$("#titleworkflowmodal").empty().append('<b>Mission: '+nomact+' (type de Mission: '+typemiss+')</b>');//ou la methode html
      // var _token = $('input[name="_token"]').val();

           $.ajax({

               url: '{{ url('/') }}'+'/Mission/getAjaxWorkflow/'+idwe,
               type : 'get',
               //data:{idwe:idwe, _token:_token},
              // data : 'idw=' + idw,
               success: function(data){
               
              // alert(data);

               //alert(JSON.stringify(data));
              $('#contenumodalworkflow').html(data);

              $('#myworkflow').modal('show');

                  //alert(JSON.stringify(retour))   ;
                 // location.reload();
            }

             
           });

  });


 // script click doc générateur

 //$('.mailGenerateur').on('click', function() {

 $(document).on('click','.mailGenerateur', function() {
   var idwg=$(this).attr("id");
   //alert(idw);
   var nomact=$('#workflowh'+idwg).attr("value");
   var typemiss=$('#workflowht'+idwg).attr("value");
      $("#titleworkflowmodal").empty().append('<b>Mission: '+nomact+' (type de Mission: '+typemiss+')</b>');//ou la methode html

           $.ajax({

               url: "{{ url('/') }}/Mission/getMailGenerator/"+idwg,
               type : 'get',
              // data : 'idw=' + idw,
               success: function(data){
               
              //alert(data);

               //alert(JSON.stringify(data));
              $('#contenumodalworkflow').html(data);

              $('#myworkflow').modal('show');

                  //alert(JSON.stringify(retour))   ;
                 // location.reload();
            }

             
           });

  });

 //--- déléguer mission


$('.deleguerMission').on('click', function() {


   var idwd=$(this).attr("id");
   //alert(idw);
   var nomact=$('#workflowh'+idwd).attr("value");
   var typemiss=$('#workflowht'+idwd).attr("value");
      $("#titleworkflowmodal").empty().append('<b>Mission: '+nomact+' (type de Mission: '+typemiss+')</b>');//ou la methode html

           $.ajax({

               url: "{{ url('/') }}/Mission/getAjaxDeleguerMission/"+idwd,
               type : 'get',
              // data : 'idw=' + idw,
               success: function(data){
               
              //alert(data);

               //alert(JSON.stringify(data));
              $('#contenuModalDeleguerMission').html(data);


              $('#modalDelegMissAjax').modal('show');

                  //alert(JSON.stringify(retour))   ;
                 // location.reload();
            }

             
           });

  });

//-- annuler mission 

$('.annulerMission').on('click', function() {


   var idws=$(this).attr("id");
  
   if(idws.indexOf('a')!= -1)
   {
      //alert(idw);
      idws=idws.substr(1);
      //alert(idw);
   }
  // var nomact=$('#workflowh'+idw).attr("value");
   //var typemiss=$('#workflowht'+idw).attr("value");
    //  $("#titleworkflowmodal").empty().append('<b>Mission: '+nomact+' (type de Mission: '+typemiss+')</b>');//ou la methode html

var r = confirm("Voulez-vous vraiment annuler cette mission ?");
if (r == true) {
 
         $.ajax({

               url: "{{ url('/') }}/Mission/AnnulerMissionCouranteByAjax/"+idws,
               type : 'get',
              // data : 'idw=' + idw,
               success: function(data){
              
                           
               alert(data);


                   var urllocalee=top.location.href;

                         var poss=urllocalee.indexOf("traitementsBoutonsActions");
                         var res22=urllocalee.indexOf("deleguerMission");
                         var res33=urllocalee.indexOf("deleguerAction");
                         var res44=urllocalee.indexOf("TraitementAction");
                         
                         //alert(poss);
                         var countt=0;

                         if(poss!= -1)
                         {
                            for (var i = poss; i <100; i++) {
                              
                                if(urllocalee[i]=='/')
                                {
                                  countt++;
                                }

                              }
                           



                         }

                    if( poss!=-1 && countt!=4 && res22!= -1 && res33!=-1 &&  res44 !=-1 )
                    {
                     location.reload();          

                    }

                    if(poss==-1)
                    {

                      location.reload();   


                    }
                      if(countt==4 ||  res22!= -1 ||  res33!=-1  || res44 !=-1 )
                    {  

                   

                      var dosskk= $('#dossierID').val() ;
                      
                      var NouveaURL="{{ url('/') }}"+"/dossiers/view/"+dosskk;

                                                         
                      document.location.href=NouveaURL;


                    }




            }// fin success

             
           });

         } 
  

  });// fin annuler mission





});
</script>


<!-- script pour le modal etat actopn d une mission recherche-->

<script>

$(document).on("keyup","#InputetatActionMission",function() {
 
    var value = $(this).val().toLowerCase();
    $("#tabetatActionMission tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });




</script>
<style>
.swal2-confirm{
  margin-bottom: 30px;
    margin-top: 20px;
    <?php
if ($view_name=='dossiers-view') {
// echo 'visibility:hidden;' ;
}
?>

}

</style>

<script>
<?php if ($view_name=='dossiers-view') { ?>
// document.getElementsByClassName('swal2-confirm').disabled = true;
//$('.swal2-confirm').prop('disabled', true);
<?php }
?>
// setInterval(function(){ 
 $(document).ready(function(){

   function verifier_Rappels_actions_reportees ()

   {     
    $.ajax({
       url : '{{ url('/') }}'+'/activerActionsReporteeOuRappelee',
       type : 'GET',
       dataType : 'html', // On désire recevoir du HTML
       success : function(data){ // code_html contient le HTML renvoyé
           //alert (data);

           if(data)
           {

                    
           var urllocale=top.location.href;

           var pos=urllocale.indexOf("traitementsBoutonsActions");

           var count=0;

           if(pos!= -1)
           {
              for (var i = pos; i <100; i++) {
                
                  if(urllocale[i]=='/')
                  {
                    count++;
                  }

                }
             



           }

          // alert(count);

           //alert(data);

          // sweetAlert('Activation d\'action', data , 'success');
         // swal(data);

          const swalWithBootstrapButtonskbs = Swal.mixin({
                            customClass: {
                                confirmButton: 'btn btn-success',
                                cancelButton: 'btn btn-danger'
                            },
                            buttonsStyling: false,
                        }) ;

                        swalWithBootstrapButtonskbs.fire({
                            title: 'Activation d\'action',
                            html: '<b>'+data+'</b>',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Traiter maintenant ',
                            cancelButtonText: 'Consulter ultèrieurement',
                         //   reverseButtons: true
                        }).then((result) => {
                            if (result.value) {

  
      
                      var actionk= $('#idactActive').val() ;
                      var  missk= $('#idactMissActive').val() ;
                      var  dossk= $('#idactDossActive').val() ;
                        
                       // alert(actionk+"/"+ missk+"/"+dossk);

                                      if(count==4)                                
                                         {

                                           var NouveaURL;
                                         //alert('{{ url('/') }}');
                                         if(actionk != null && missk !=null && dossk != null)
                                         {
                                        NouveaURL="{{ url('/') }}"+"/dossier/Mission/TraitementAction/"+dossk+"/"+missk+"/"+actionk;
                                         }
                                         else
                                         {
                                         NouveaURL="{{ url('/') }}";
                                         }
                                         //window.location.replace(NouveaURL) ;
                                         document.location.href=NouveaURL;

                                          //alert(window.location.href);
                                        
                                         }
                                         else
                                         {

                                          window.location.reload(true);

                                         }


                            }


                            }); 

           
            Notify("<b>"+data+"</b>",
                    function () { 
                      //alert("clicked notification")
                    },
                    function () { 
                      //alert("clicked x")
                    },
                    'success'
                  );
                            

            
           }// fin if data

           setTimeout(function(){ 

            verifier_Rappels_actions_reportees();

            }, 15000);


       }// fin success
    });// fin $ajax
   
}

verifier_Rappels_actions_reportees();

//}, 15000);  // fin setInterval




//activerAct_des_dates_speciales-->


 //setInterval(function(){ 
   function  activerAct_des_dates_speciales()
     { 

    $.ajax({
       url : '{{ url('/') }}'+'/activerAct_des_dates_speciales',
       type : 'GET',
       dataType : 'html', // On désire recevoir du HTML
       success : function(data){ // code_html contient le HTML renvoyé
           //alert (data);

           if(data)
           {

          
           var urllocale=top.location.href;

           var pos=urllocale.indexOf("traitementsBoutonsActions");

           var count=0;

           if(pos!= -1)
           {
              for (var i = pos; i <100; i++) {
                
                  if(urllocale[i]=='/')
                  {
                    count++;
                  }

                }
             

           }

    

          const swalWithBootstrapButtonskbs = Swal.mixin({
                            customClass: {
                                confirmButton: 'btn btn-success',
                                cancelButton: 'btn btn-danger'
                            },
                            buttonsStyling: false,
                        }) ;

                        swalWithBootstrapButtonskbs.fire({
                            title: 'Activation d\'action',
                            html: '<b>'+data+'</b>',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Traiter maintenant !<br>',
                            cancelButtonText: 'Consulter ultèrieurement',
                         //   reverseButtons: true
                        }).then((result) => {
                            if (result.value) {

  
      
                      var actionk= $('#idactActive').val() ;
                      var  missk= $('#idactMissActive').val() ;
                      var  dossk= $('#idactDossActive').val() ;
                        
                       // alert(actionk+"/"+ missk+"/"+dossk);

                                      if(count==4)                                
                                         {
                                           var NouveaURL;
                                         //alert('{{ url('/') }}');
                                         if(actionk != null && missk !=null && dossk != null)
                                         {
                                        NouveaURL="{{ url('/') }}"+"/dossier/Mission/TraitementAction/"+dossk+"/"+missk+"/"+actionk;
                                         }
                                         else
                                         {
                                         NouveaURL="{{ url('/') }}";
                                         }
                                         //window.location.replace(NouveaURL) ;
                                         document.location.href=NouveaURL;

                                          //alert(window.location.href);
                                        
                                         }
                                         else
                                         {

                                          window.location.reload(true);

                                         }


                            }


                            }); 

           
                    Notify("<b>"+data+"</b>",
                    function () { 
                     // alert("clicked notification")
                    },
                    function () { 
                      //alert("clicked x")
                    },
                    'warning'
                  );
          

            
           }
         setTimeout(function(){          

                activerAct_des_dates_speciales();

           }, 20000);



       } // fin success
    }); // fin ajax
   
}
//}, 20000); // fin set interval

activerAct_des_dates_speciales();


// gestion des rappels des missions (pour les rappels actions voir traitementaction blade)


var idMission;
var datamission;
var iddropdownM;
var hrefidAcheverM;
var idPourRepMiss;
var daterappelmissh;
//var titleActionRModal;

    //setInterval(function(){
 //alert("Hello"); 

 function getMissionAjaxModal() {
     
    $.ajax({
       url : '{{ url('/') }}'+'/getMissionAjaxModal',
       type : 'GET',
       dataType : 'html', // On désire recevoir du HTML
       success : function(data){ // code_html contient le HTML renvoyé
           //alert (data);

           if(data)
           {
               //jQuery(data).find('#titleActionRModal').html()+

                Notify("<b> Activation d\'une nouvelle Mission </b>",
                    function () { 
                     // alert("clicked notification")
                    },
                    function () { 
                      //alert("clicked x")
                    },
                    'danger'
                  );
                  
              

             $("#myMissionModalReporter1").modal('show');          
             idAction=jQuery(data).find('.rowkbs').attr("id");
             dataAction=jQuery(data).find('#'+idAction).html();
             hrefidAcheverA=jQuery(data).find('#idAchever').attr("href");
             idPourRepMiss=jQuery(data).find('#'+idPourRepMiss).html();
             daterappelmissh=jQuery(data).find('#'+daterappelmissh).html();            

             // alert ("des nouvelles notes sont activées");
              //$("#contenuNotes").prepend(data);
              var sound = document.getElementById("audiokbs");
              sound.setAttribute('src', "{{URL::asset('public/media/point.mp3')}}");
              sound.play();

             // alertify.alert("Note","Une nouvelle note est activée").show();

             $("#contenuMissionModal").empty().append(data);
             iddropdown=jQuery(data).find('.dropdown').attr("id");
             $("#"+iddropdown).hide();
             $("#hiddenreporterMiss").hide();
            
              
            
            
           }

            setTimeout(function(){          

                getMissionAjaxModal();

           }, 10000);

       }
    });// $ajax
   
}
getMissionAjaxModal();

//}, 10000); // fin setInterval

  
    
    $(document).on("click","#missionOngletaj",function() {
      //alert(datakbs);
      $("#contenuMissions").prepend(dataAction);
      $("#"+iddropdown).show();
    
        
        $('#actiontabs a[href="#Missionstab"]').trigger('click');


    });

  

     $(document).on("click","#reporterHideM",function() {
       
       $("#hiddenreporterMiss").toggle();  


    });


     $(document).on("click","#idOkRepMiss",function() {

          idPourRepMiss=$("#idPourRepMiss").val();
          //alert(idPourRepMiss);       
          daterappelmissh = $('#daterappelmissh').val();
          var _token = $('input[name="_token"]').val();

                           
                $.ajax({
                   url : "{{ route('Mission.ReporterMission') }}",
                   type : 'GET',
                   //dataType : 'html', // On désire recevoir du HTML
                   data:{idPourRepMiss:idPourRepMiss,daterappelmissh:daterappelmissh, _token:_token},
                   success : function(data){ // code_html contient le HTML renvoyé
                       //alert (data);
                       if(data)
                       {
                           alert(data); 

                           if(String(data).indexOf("Mission reportée")!=-1) 
                           {              
                             $("#myMissionModalReporter1").modal('hide');
                           }
                         
                       }
                   }
                });
               




    }); // fin on click



      });

  

  </script>

<div class="modal fade" id="myMissionModalReporter1" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content" id="contenuMissionModal">
       
      </div>
      
    </div>
</div>




<!-- gestion des affectations des nouveaux dossiers par le dispatcheur-->



<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">
  

$(document).ready(function() {


  var userIdConnec = $('meta[name="userId"]').attr('content');
     
    $.ajax({
       url : '{{ url('/') }}'+'/getNotificationAffectationDoss/'+userIdConnec,
       type : 'GET',
       dataType : 'html', // On désire recevoir du HTML
       success : function(data){ // code_html contient le HTML renvoyé
           //alert (data);

           if(data)
           {
          
           // var idUserAffecte=jQuery(data).find('.userAff').attr("id");
           // var refdoss=jQuery(data).find('.refdoss').attr("id");
           

           swal(data);
           Notify("<b>"+data+"</b>",
                    function () { 
                     // alert("clicked notification")
                    },
                    function () { 
                      //alert("clicked x")
                    },
                    'danger'
                  );

          // location.reload(); 
            /* if(idUserAffecte==userIdConnec)
             {

                 alert('un nouveau dossier dont la réf : '+refdoss +'est affecté à vous');
                 location.reload(); 


             }*/
            
              
       

            
           }
       }
    });
   


//}, 5000);






//  délégation des missions -->


  

    //setInterval(function(){
 //alert("Hello"); 
   function getNotificationDeleguerMiss () {
  var userIdConnec = $('meta[name="userId"]').attr('content');
     
    $.ajax({
       url : '{{ url('/') }}'+'/getNotificationDeleguerMiss/'+userIdConnec,
       type : 'GET',
       dataType : 'html', // On désire recevoir du HTML
       success : function(data){ // code_html contient le HTML renvoyé
           //alert (data);

           if(data)
           {
          
                 

          // swal(data);

           const swalWithBootstrapButtonskbs = Swal.mixin({
                            customClass: {
                                confirmButton: 'btn btn-success',
                                cancelButton: 'btn btn-danger'
                            },
                            buttonsStyling: false,
                        }) ;

                        swalWithBootstrapButtonskbs.fire({
                            title: 'Délégation Mission',
                            html: '<b>'+data+'</b>',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Afficher maintenant !<br>',
                            cancelButtonText: 'Consulter ultèrieurement',
                         //   reverseButtons: true
                        }).then((result) => {
                            if (result.value) {

                                  // window.location.reload(true);

                                               var urllocaleee2=top.location.href;

                         var posss2=urllocaleee2.indexOf("traitementsBoutonsActions");
                         var res2222=urllocaleee2.indexOf("deleguerMission");
                         var res3332=urllocaleee2.indexOf("deleguerAction");
                         var res4442=urllocaleee2.indexOf("TraitementAction");
                         
                         //alert(poss);
                         var counttt2=0;

                         if(posss2!= -1)
                         {
                            for (var i = posss2; i <100; i++) {
                              
                                if(urllocaleee2[i]=='/')
                                {
                                  counttt2++;
                                }

                              }
                         
                         }

                    if( posss2!=-1 && counttt2!=4 && res2222!= -1 && res3332!=-1 &&  res4442 !=-1 )
                    {
                     location.reload();          

                    }

                    if(posss2==-1)
                    {

                      location.reload();   


                    }
                      if(counttt2==4 ||  res2222!= -1 ||  res3332!=-1  || res4442 !=-1 )
                    {  

                   

                      var dosskkk2= $('#dossierID').val() ;
                      
                      var NouveaURL2="{{ url('/') }}"+"/dossiers/view/"+dosskkk2;

                                                         
                      document.location.href=NouveaURL2;


                    }

                            }// fin resultvalue


                            }); 

       
              
           Notify("<b>"+data+"</b>",
                    function () { 
                     // alert("clicked notification")
                    },
                    function () { 
                      //alert("clicked x")
                    },
                    'danger'
                  );

            
           }

            setTimeout(function(){          

                getNotificationDeleguerMiss();

           }, 7000);


       }
    });
   
} 

//}, 7000);

getNotificationDeleguerMiss();






//  délégation des actions -->


  

    //setInterval(function(){
 //alert("Hello"); 
function  getNotificationDeleguerAct () {

  var userIdConnec = $('meta[name="userId"]').attr('content');
     
    $.ajax({
       url : '{{ url('/') }}'+'/getNotificationDeleguerAct/'+userIdConnec,
       type : 'GET',
       dataType : 'html', // On désire recevoir du HTML
       success : function(data){ // code_html contient le HTML renvoyé
           //alert (data);

           if(data)
           {
          
          
             const swalWithBootstrapButtonskbs = Swal.mixin({
                            customClass: {
                                confirmButton: 'btn btn-success',
                                cancelButton: 'btn btn-danger'
                            },
                            buttonsStyling: false,
                        }) ;

                        swalWithBootstrapButtonskbs.fire({
                            title: 'Délégation Action',
                            html: '<b>'+data+'</b>',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Afficher maintenant !<br>',
                            cancelButtonText: 'Consulter ultèrieurement',
                         //   reverseButtons: true
                        }).then((result) => {
                            if (result.value) {

                                   //window.location.reload(true);

                                    var urllocaleee=top.location.href;

                         var posss=urllocaleee.indexOf("traitementsBoutonsActions");
                         var res222=urllocaleee.indexOf("deleguerMission");
                         var res333=urllocaleee.indexOf("deleguerAction");
                         var res444=urllocaleee.indexOf("TraitementAction");
                         
                         //alert(poss);
                         var counttt=0;

                         if(posss!= -1)
                         {
                            for (var i = posss; i <100; i++) {
                              
                                if(urllocaleee[i]=='/')
                                {
                                  counttt++;
                                }

                              }
                         
                         }

                    if( posss!=-1 && counttt!=4 && res222!= -1 && res333!=-1 &&  res444 !=-1 )
                    {
                     location.reload();          

                    }

                    if(posss==-1)
                    {

                      location.reload();   


                    }
                      if(counttt==4 ||  res222!= -1 ||  res333!=-1  || res444 !=-1 )
                    {  

                   

                      var dosskkk= $('#dossierID').val() ;
                      
                      var NouveaURL1="{{ url('/') }}"+"/dossiers/view/"+dosskkk;

                                                         
                      document.location.href=NouveaURL1;


                    }

                            }// fin if result value


                            }); 

       
                          
               Notify("<b>"+data+"</b>",
                    function () { 
                     // alert("clicked notification")
                    },
                    function () { 
                      //alert("clicked x")
                    },
                    'info'
                  );
       

            
           }

             setTimeout(function(){          

               getNotificationDeleguerAct();

           }, 8000);
       }
    });
}

getNotificationDeleguerAct ();
   


//}, 7000);









  $("#hreftopwindow").val(top.location.href);
  
  



// gestion de bouton mette à jour date spécifique 1 pour le modal descrip mission -->
 

  $(document).on("click","#MajDateSpecM",function() {

//$('#MajDateSpecMiss').click(function(){
        var idmissionDateSpec = $('#idmissionDateSpecM').val();
        var NomTypeDateSpec = $('#NomTypeDateSpecM').val();
        var dateSpec = $('#dateSpecM').val();
      
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('Action.dateSpecifique') }}",
                method:"POST",
                data:{idmissionDateSpec:idmissionDateSpec,NomTypeDateSpec:NomTypeDateSpec,dateSpec:dateSpec,_token:_token},
                success:function(data){

                    //  idspandateAssNonAss
                   // window.location =data;

                   if(data=='date spécifique invalide')
                   {
                       alert(data);
                   }
                   if(data=='date affectée')
                   {
                       alert(data);
                 
                         $('#idspandateAssNonAssM').css('color','green');
                                              
                         $('#idspandateAssNonAssM').text('Oui,date affectée');
                          //$('#idspandateAssNonAss').css('text-decoration','blink'); 

                   }

                   

                }
            });
       
    });
     




  

  //gestion de bouton mette à jour date spécifique 2 pour le modal descrip mission -->
 

  $(document).on("click","#MajDateSpecM2",function() {

//$('#MajDateSpecMiss').click(function(){
        var idmissionDateSpec = $('#idmissionDateSpecM2').val();
        var NomTypeDateSpec = $('#NomTypeDateSpecM2').val();
        var dateSpec = $('#dateSpecM2').val();
      
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('Action.dateSpecifique') }}",
                method:"POST",
                data:{idmissionDateSpec:idmissionDateSpec,NomTypeDateSpec:NomTypeDateSpec,dateSpec:dateSpec,_token:_token},
                success:function(data){

                    //  idspandateAssNonAss
                   // window.location =data;

                   if(data=='date spécifique invalide')
                   {
                       alert(data);
                   }
                   if(data=='date affectée')
                   {
                       alert(data);
                 
                         $('#idspandateAssNonAssM2').css('color','green');
                                              
                         $('#idspandateAssNonAssM2').text('Oui,date affectée');
                          //$('#idspandateAssNonAss').css('text-decoration','blink'); 

                   }

                   

                }
            });
       
    });
     



 
  



// bouton fin creation mission-->



  $("#idFinAjoutMiss").click(function(e){ // On sélectionne le formulaire par son identifiant
 
    

    var r = confirm("l'appui sur ce bouton termine la phase de création des missions et provoque la perte de tous vos sélections des mots clés dans l\'email ! Voulez vous vraiment finaliser la phase de création de missions ?");
      
      if (r == true) {
       
      
       var urllocalee=top.location.href;

           var poss=urllocalee.indexOf("traitementsBoutonsActions");
           var res22=urllocalee.indexOf("deleguerMission");
           var res33=urllocalee.indexOf("deleguerAction");
           var res44=urllocalee.indexOf("AnnulerMissionCourante");
           
           //alert(poss);
           var countt=0;

           if(poss!= -1)
           {
              for (var i = poss; i <100; i++) {
                
                  if(urllocalee[i]=='/')
                  {
                    countt++;
                  }

                }
             



           }

      if( poss!=-1 && countt!=4 && res22!= -1 && res33!=-1 &&  res44 !=-1 )
      {
       location.reload();          

      }

      if(poss==-1)
      {

        location.reload();   


      }
        if(countt==4 ||  res22!= -1 ||  res33!=-1  || res44 !=-1 )
      {  

     

        var dosskk= $('#dossierID').val() ;
        
        var NouveaURL="{{ url('/') }}"+"/dossiers/view/"+dosskk;

                                           
        document.location.href=NouveaURL;


      }
     // return redirect('dossiers/view/'.$request->dossierID);

        
     //location.reload();

      }

});




// click sur l'onglet missions --> 





  $("#idMissionstab").click(function(e){ // On sélectionne le formulaire par son identifiant
 
    

         
      
       var urllocalee=top.location.href;

           var poss=urllocalee.indexOf("traitementsBoutonsActions");
           var res22=urllocalee.indexOf("deleguerMission");
           var res33=urllocalee.indexOf("deleguerAction");
           var res44=urllocalee.indexOf("AnnulerMissionCourante");
           
           //alert(poss);
           var countt=0;

           if(poss!= -1)
           {
              for (var i = poss; i <100; i++) {
                
                  if(urllocalee[i]=='/')
                  {
                    countt++;
                  }

                }
             



           }

      if( poss!=-1 && countt!=4 && res22!= -1 && res33!=-1 &&  res44 !=-1 )
      {
       location.reload();          

      }

      if(poss==-1)
      {

        location.reload();   


      }
        if(countt==4 ||  res22!= -1 ||  res33!=-1  || res44 !=-1 )
      {  

     

        var dosskk= $('#dossierID').val() ;
        
        var NouveaURL="{{ url('/') }}"+"/dossiers/view/"+dosskk;

                                           
        document.location.href=NouveaURL;


      }
     // return redirect('dossiers/view/'.$request->dossierID);

        
     //location.reload();

  

});

  });



</script>
<script> 
  $(document).ready(function(){
  
          var pres_om= '';
          var idmissionDateSpeck = '';

      $(document).on("change","input[type=radio][name=pres_om]",function() {
             idmissionDateSpeck = $('#idmissionDateSpecM').val();
            if (this.value == 'interne') {
              
               pres_om= 'interne';
               //alert("interne "+idmissionDateSpeck);
              }
              else if (this.value == 'externe') {
              // var idmissionDateSpeck = $('#idmissionDateSpecM').val();
               pres_om= 'externe';
                 // alert("externe "+idmissionDateSpeck);
              }

                var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('mission.traiterPrestOmIntExt') }}",
                method:"POST",
                data:{idmissionDateSpeck:idmissionDateSpeck,pres_om:pres_om,_token:_token},
                success:function(data){

                alert(data);
                 

                }
            });
             });

          

    });

</script>




