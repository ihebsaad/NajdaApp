 <!-- Content -->
<?php 
use App\Http\Controllers\TagsController;
 ?>
<!--select css-->
    <link href="{{ asset('public/js/select2/css/select2.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('public/js/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    
    
    <style>

   /* #notifications {
    cursor: pointer;
    position: fixed;
    right: 0px;
    z-index: 9999;
    top: 50px;
    margin-bottom: 22px;
    margin-right: 15px;
    max-width: 500px;   
     }*/
   </style>

<div class="panel panel-danger">
                    <div class="panel-heading">
                        <h4 class="panel-title">Missions et Informations</h4>
                        <span class="pull-right">
                           <i class="fa fa-fw clickable fa-chevron-up"></i>
                            
                        </span>
                    </div>

                    <!--<div id="notifications"></div>-->


                   <div class="panel-body scrollable-panel" style="display: block;">
                        

                        <div class="panel-body" style="display: block;">
                            <?php use \App\Http\Controllers\MissionController;
                                $typesMissions= MissionController::ListeTypeMissions();


                             /// if (isset( $dossier)){
                                 // $Missions=$dossier->activeMissions;

                               if (true){ 
                                $Missions=Auth::user()->activeMissions->sortBy(function($t)
                                        {
                                            return $t->updated_at;
                                        })->reverse();
                            ?>
                  @if ($Missions)
<!--  début tab -+----------------------------------------------------------------------------------------->
                        <ul id="actiontabs" class="nav nav-tabs" style="margin-bottom: 15px;">
                            <li class="active">
                                <a id="idMissionstab" href="#Missionstab" data-toggle="tab">Missions</a>
                            </li>
                            <li>
                                <a href="#newMissiontab" data-toggle="tab">Nouvelle Mission</a>
                            </li>
                            <?php if (\Request::is('entrees/show/*')) { ?>
                            <li>
                                <a href="#infostab" data-toggle="tab">Informations</a>
                            </li>
                            <?php } ?>
                        </ul>
                        <div id="MissionsTabContent" class="tab-content">

                          <!-- début  Missions tab-->
                          <div class="tab-pane fade active in " id="Missionstab">
                              <!--<div class="tab-pane fade active in  scrollable-panel" id="Missionstab">-->

                               
                                <!-- treeview of notifications -->
                                <div id="accordionkbs">

                                <style scoped>
                               
                              .panel-heading.active {
                                background-color: #00BFFF /*#86B404  #2EFEF7;*/
                              }
                              .panel-heading.ColorerMissionsCourantes{
                                background-color: #ffd051;
                                color: red;
                              }

                              .panel-heading.activeActDeleg {
                                background-color: #8A0808 /*#86B404  #2EFEF7;*/
                              }

                              </style>
                              <?php if (isset($act)){$currentMission=$act->id;}else{$currentMission=0;} ?>

                              <?php if (isset($dossier)){$dosscourant=$dossier->id ;}else{$dosscourant=0;} ?>
                                    
                                    <div class="accordion panel-group" id="accordion">

                                 @if (!$Missions->isEmpty())

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
                                      @foreach ($Missions as $Mission)
                                      @if ( $Mission->statut_courant =='active')

                                      <div class="row" style="padding-bottom: 3px;">
                                      <div class="col-md-10">
                                      <div class="panel panel-default">

                                        <div class="panel-heading <?php if($Mission->id ==$currentMission){echo 'active';}
                                        else {if($Mission->dossier->id==$dosscourant){echo 'ColorerMissionsCourantes' ;}}?>">

                                         
                                           <h4 class="panel-title">
                                              <a data-toggle="collapse" href="#collapse{{$Mission->id}}">{{$Mission->dossier->reference_medic}}   {{$Mission->titre}}</a>
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
                                          <a class="DescripMission" id="<?php echo $Mission->id ?>" href="#">Descrip. Mission</a>
                                          <a class="etatAction" id="<?php echo $Mission->id ?>" href="#">Voir état actions</a>
                                          <a class="mailGenerateur" id="<?php echo $Mission->id ?>" href="#">Mail générateur</a>
                                          <a class="deleguerMission" id="<?php echo $Mission->id ?>" href="#">Déléguer Mission</a>
                                          <a class="annulerMission" id="<?php echo $Mission->id ?>" href="#">Annuler Mission</a>

                                           <input id="workflowh<?php echo $Mission->id ?>" type="hidden" value="{{$Mission->titre}}">
                                            <input id="workflowht<?php echo $Mission->id ?>" type="hidden" value="{{$Mission->typeMission->nom_type_Mission}}">



                                          </div>
                                        </div>



                                     


                                            <!-- {{-- <a class="workflowkbs" id="<?php echo $Mission->id ?>" style="color:black !important; margin-top: 10px; margin-right: 10px;" data-toggle="modal" data-target="#myworow" title ="Voir Workflow" href="#"><span class="fa fa-2x fa-tasks" style="  margin-right: 20px;" aria-hidden="true"></span>
                                            </a>
                                            <input id="workflowh<?php echo $Mission->id ?>" type="hidden" value="{{$Mission->titre}}">
                                            <input id="workflowht<?php echo $Mission->id ?>" type="hidden" value="{{$Mission->typeMission->nom_type_Mission}}"> --}} --> 


                                             {{-- <a  style="color:black !important; margin-top: 10px; margin-right: 10px;" title ="Voir Workflow" href="{{url('Mission/workflow/'.$Mission->dossier->id.'/'.$Mission->id)}}"><span class="fa fa-2x fa-cogs" style=" margin-top: 10px; margin-right: 20px;" aria-hidden="true"></span>
                                            </a>  --}}
                                                                         
                                        <?php $actk=$Mission;?>
                                     
                                    </div>
                                    </div>
                                    @endif

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
                                  <form  id="idFormCreationMission" method="post" action="{{route('Missions.storeActionsEC') }}" style="padding-top:30px">
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

                                                <option value="{{$c->nom_type_Mission}}">{{$c->nom_type_Mission}} </option>

                                         @endforeach



                                         </select>





                                                 <div id="listtypeact" style=" left:-50px; z-index: 9999; width: 200 px;"> </div>


                                                 <script> $(document).ready(function(){

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
                                            <?php  $da = (new \DateTime())->format('Y-m-d\TH:i');// $da= date('Y-m-d\TH:m'); ?>

                                                <div class="row">
                                                    <div class="col-md-3" style="padding-top:5px">  <label for="datedeb" style="display: inline-block;  text-align: left; width: 55px;">Date:</label></div>
                                                    <div class="col-md-9"> <input id="datedeb" type="datetime-local" value="<?php echo $da ?>" class="form-control" style="width:95%;  text-align: left;" name="datedeb"/></div>
                                                </div>
                                         </div>
                                        <div class="form-group">
                                          <div class="row">
                                              <div class="col-md-3" style="padding-top:5px">     <label for="descrip" style="display: inline-block;  text-align: right; width: 55px;">Description:</label></div>
                                              <div class="col-md-9"><input id="descrip" type="text" class="form-control" style="width:95%;  text-align: left;" name="descrip"/></div>
                                          </div>
                                        </div>
                                        </br>
                                       <div class="row">
                                             <div class="col-md-3" style="padding-top:5px"> <label  style=" ;  text-align: left; width: 55px;">Commentaire:</label></div>
                                             <div class="col-md-9"><textarea id="commentaire" class="form-control" style="width:95%;  text-align: left !important;" name="commentaire"></textarea></div>
                                       </div>
                                      </div>

                                           


                                      <div class="form-group">

                                           <?php if(isset($dossier)) {  ?>
                                          
                                          <input id="dossier" type="hidden" class="form-control" value="{{$dossier->reference_medic}}" name="dossier"/>
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
                                        <button  id="idAjoutMiss" type="button"  class="btn btn-success">Ajouter la mission</button>
                                        <br><br><br>
                                       <!-- <button  id="idFinAjoutMiss" type="button"  class="btn btn-danger">Fin ajout de missions</button>-->

                                     <!-- <button id="add"  class="btn btn-primary">Ajax Add</button>-->
                                  </form>
                                   <?php } else {?>

                                    <div> vous devez sélectionner un dossier pour créer une mission  </div>

                                     <?php } ?>
                               </div>   



                                   </div>
                                                                   
                    </div>
                    <?php if (\Request::is('entrees/show/*')) { ?>
                     <!-- Informations tab------------------------>
                     <div class="tab-pane fade " id="infostab" style="overflow-x: hidden;">
                      <div class="row text-center">
                        <div class="col-md-6" >
                          <button id="btn-atag" class="btn btn-default default-hovered" style="background-color: #A9A9A9">Ajouter TAG</button>
                        </div>
                        <div class="col-md-6" >
                          <button id="btn-cmttag" class="btn btn-default">TAG & Commentaire</button>
                        </div>
                      </div>    
                      <div id="ajouttag" style="display:block;margin-top: 30px">
                        <input type="hidden" name="dossieridtag" id="dossieridtag" value="<?php echo $dosscourant; ?>">
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
                            </div>
                            <div id="champstags" class="form-group mar-20"></div>
                            <textarea id="contenutag" name="contenutag" rows="7" class="form-control resize_vertical" placeholder="Entrer le contenu de TAG" data-bv-field="message" style="width: 280px"></textarea></br>
                            
                             <div class="row text-center">
                              <div class="col-md-4" >
                                <button id="btn-addtag" type="submit"  class="btn btn-danger">Ajouter</button>
                              </div>
                              <div class="col-md-8" >
                                <span id="addedsuccess" style="color:green;display: none">✓ Le TAG est ajouté avec succés</span>
                                <span id="addedfail" style="color:red;display: none">✖ Erreur lors de l'ajout de TAG</span>
                              </div>
                              {{ csrf_field() }}
                            </div> 
                      </div>   
                      <div id="cmttag"  style="display:none;margin-top: 30px">
                          <div class="row">
                            <div class="col-md-6"><label for="commentuser" class="control-label" >Commentaire</label></div>
                            <div class="col-md-3 pull-right"><button id="editbtn" type="button" class="btn btn-info btn-xs" ><i class="fas fa-lock-open"></i> Modifier</button></div>
                          </div>
                          <textarea id="commentuser" name="commentuser" rows="7" class="form-control resize_vertical" placeholder="Entrez votre commentaire" readonly >{{ $entree['commentaire']  }}</textarea></br>
                          <!-- affichage des tags -->
                          <label for="accordiontags" class="control-label" >TAGs</label>
                          <div class="accordion panel-group" id="accordiontags">
                            @php
                              {{$tags=TagsController::entreetags($entree['id']);}}
                            @endphp
                                 @if (!$tags->isEmpty())
                                      @foreach ($tags as $tag)
                                      <div class="row" style="padding-bottom: 3px;">
                                      <div class="col-md-10">
                                      <div class="panel panel-default">

                                        <div class="panel-heading" >

                                         
                                           <h4 class="panel-title">
                                              <a data-toggle="collapse" href="#collapse{{$tag->id}}">{{$tag->titre}}   </a>
                                           </h4>
                                        </div>

                                       <div id="collapse{{$tag->id}}" class="panel-collapse collapse">
                                            <ul class="list-group">
                                              <?php if ((isset($tag->montant)) && (! empty($tag->montant))) { 
                                                    if ($tag->montant !== null){
                                                ?>
                                              <li class="list-group-item"><b style="color: #c1c1b7">Montant: </b>{{$tag->montant}} </li>
                                              <?php }} ?>
                                              <li class="list-group-item"><b style="color: #c1c1b7">Contenu: </b>{{$tag->contenu}} </li>
                                            </ul>

                                        </div>


                                        <!-- /.panel-heading -->


                                      </div>
                                    </div>
                                    <!--<div class="col-md-2" >
                                     btn supprimer tag
                                    </div>-->
                                    </div>

                                     @endforeach
                                  @endif
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

            <div class="modal fade" id="NouveauType" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
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
                           {{-- <tbody>
                              <?php  if (isset( $dossier)){
                                  $acts=$dossier->Missions; $u=1; ?>
                               @foreach ($acts as $ac)
                              <tr>
                                <th scope="row"><?php echo $u ?></th>
                                <td>{{$ac->titre}}</td>
                                <td>{{$ac->date_deb}}</td>
                                <td>{{$ac->statut_courant}}</td>
                                <td> 
                                  <div class="dropdown">
                                              <button class="dropbtn"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                                              <div class="dropdown-content">
                                                <a href="#">Rendre Active</a>
                                                <a href="#">Rendre Inactive</a>
                                                <a href="#">Rendre Achevée</a>
                                          
                                              </div>
                                            </div>

                                          </td>
                              </tr>
                                <?php  $u++ ; ?>
                              @endforeach
                            <?php } ?>
                            </tbody>--}}
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
          <p>

  <div id="contenumodalworkflow" style="background-color: #ABF8F8;padding:15px 15px 15px 15px" >

               
  </div>


          </p>
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
    }); 
$("#tagname").select2();
$('#tagname').change(function(e){
  if ($('#tagname option:selected').val() != null)
    {if($('#tagname option:selected').val().match(/^(Franchise|Plafond|GOPmed|PlafondRem|GOPtn)$/))
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
    if ($('textarea#commentuser').is('[readonly]') )
      { $('textarea#commentuser').attr('readonly',false);}
    else
      {$('textarea#commentuser').attr('readonly',true);}
});

$('#btn-addtag').click(function(e){
      var entree = $('input[name="entree"]').val();
      var dossier = $('input[name="dossieridtag"]').val();
      var tag = $('select[name=tagname]').val();
      var tagtxt = $('select[name=tagname] option:selected').text();
      var tagcontent = $('textarea#contenutag').val();
      var _token = $('input[name="_token"]').val();
      var urladdtag = $('input[name="urladdtag"]').val();
      var montant= null;
      var devise = null;
      var limontant='';
      if (document.getElementById("montanttag")!=null)
      {
        montant = $('input[name="montanttag"]').val();

        devise = $('select[name=devise] option:selected').text();
        limontant = '<li class="list-group-item"><b style="color: #c1c1b7">Montant: </b>'+montant+' '+devise+'</li>';
      }
            if (entree != '')
            {

                $.ajax({
                    url:urladdtag,
                    method:"POST",
                    data:{entree:entree,dossier:dossier,titre:tag,contenu:tagcontent,montant:montant,devise:devise, _token:_token},
                    success:function(data){
                        $("#addedsuccess").fadeIn(1500);
                        $("#addedsuccess").fadeOut(1500);

                        // ajouter la nouvelle tag dans la section cmttags
                        $('#accordiontags').append('<div class="row"><div class="col-md-10"><div class="panel panel-default"><div class="panel-heading" ><h4 class="panel-title"><a data-toggle="collapse" href="#collapse'+tag+'">'+tagtxt+'</a></h4></div><div id="collapse'+tag+'" class="panel-collapse collapse"><ul class="list-group">'+limontant+'<li class="list-group-item"><b style="color: #c1c1b7">Contenu: </b>'+tagcontent+'</li></ul></div></div></div></div>');

                        $('textarea#contenutag').val('');
                        //document.getElementById('tagname').selectedIndex = -1;
                        //$("#tagname").select2("val", "");
                        $('#tagname').val(null).trigger('change');
                        if (document.getElementById("montanttag")!=null)
                        {
                          $('#champstags').html("");
                        }
                    
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
        if (timeoutId) clearTimeout(timeoutId);
        var _token = $('input[name="_token"]').val();
        var entree = $('input[name="entree"]').val();
        timeoutId = setTimeout(function () {
            $.ajax({
                url: "{{ route('entrees.savecomment') }}",
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
    $urlapp=env('APP_URL');

    if (App::environment('local')) {
        // The environment is local
        $urlapp='http://localhost/najdaapp';
       // $urlapp=env('APP_URL');

    }
    ?>

    <!-- get modal workflow by ajax -->
<script>

$(document).ready(function() {

  $('.workflowkbs').on('click', function() {


   var idw=$(this).attr("id");
   //alert(idw);
   var nomact=$('#workflowh'+idw).attr("value");
   var typemiss=$('#workflowht'+idw).attr("value");
      $("#titleworkflowmodal").empty().append('<b>Mission: '+nomact+' (type de Mission: '+typemiss+')</b>');//ou la methode html

           $.ajax({

               url: "<?php echo $urlapp; ?>/Mission/getAjaxWorkflow/"+idw,
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


   var idw=$(this).attr("id");
  // alert(idw);
   var nomact=$('#workflowh'+idw).attr("value");
   //alert
   var typemiss=$('#workflowht'+idw).attr("value");
      $("#titleworkflowmodal").empty().append('<b>Mission: '+nomact+' (type de Mission: '+typemiss+')</b>');//ou la methode html

           $.ajax({

               url: "<?php echo $urlapp; ?>/Mission/getDescriptionMissionAjax/"+idw,
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
  


//-- script click etat action
 $('.etatAction').on('click', function() {


   var idw=$(this).attr("id");
  // alert(idw);
   var nomact=$('#workflowh'+idw).attr("value");
   //alert
   var typemiss=$('#workflowht'+idw).attr("value");
      $("#titleworkflowmodal").empty().append('<b>Mission: '+nomact+' (type de Mission: '+typemiss+')</b>');//ou la methode html

           $.ajax({

               url: "<?php echo $urlapp; ?>/Mission/getAjaxWorkflow/"+idw,
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


 // script click doc générateur

 $('.mailGenerateur').on('click', function() {


   var idw=$(this).attr("id");
   //alert(idw);
   var nomact=$('#workflowh'+idw).attr("value");
   var typemiss=$('#workflowht'+idw).attr("value");
      $("#titleworkflowmodal").empty().append('<b>Mission: '+nomact+' (type de Mission: '+typemiss+')</b>');//ou la methode html

           $.ajax({

               url: "<?php echo $urlapp; ?>/Mission/getMailGenerator/"+idw,
               type : 'GET',
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


   var idw=$(this).attr("id");
   //alert(idw);
   var nomact=$('#workflowh'+idw).attr("value");
   var typemiss=$('#workflowht'+idw).attr("value");
      $("#titleworkflowmodal").empty().append('<b>Mission: '+nomact+' (type de Mission: '+typemiss+')</b>');//ou la methode html

           $.ajax({

               url: "<?php echo $urlapp; ?>/Mission/getAjaxDeleguerMission/"+idw,
               type : 'GET',
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


   var idw=$(this).attr("id");
   //alert(idw);
  // var nomact=$('#workflowh'+idw).attr("value");
   //var typemiss=$('#workflowht'+idw).attr("value");
    //  $("#titleworkflowmodal").empty().append('<b>Mission: '+nomact+' (type de Mission: '+typemiss+')</b>');//ou la methode html

var r = confirm("Voulez-vous vraiment annuler cette mission ?");
if (r == true) {
 
         $.ajax({

               url: "<?php echo $urlapp; ?>/Mission/AnnulerMissionCouranteByAjax/"+idw,
               type : 'GET',
              // data : 'idw=' + idw,
               success: function(data){
               
               
               location.reload();
               alert(data);

               //alert(JSON.stringify(data));
             // $('#contenumodalworkflow').html(data);

             // $('#myworkflow').modal('show');

                  //alert(JSON.stringify(retour))   ;
                 // location.reload();
            }

             
           });

         } 
  

  });





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




<script>
 setInterval(function(){ 
     
    $.ajax({
       url : '{{ url('/') }}'+'/activerActionsReporteeOuRappelee',
       type : 'GET',
       dataType : 'html', // On désire recevoir du HTML
       success : function(data){ // code_html contient le HTML renvoyé
           //alert (data);

           if(data)
           {

             // alert ("des nouvelles notes sont activées");
              //$("#contenuNotes").prepend(data);
              //var sound = document.getElementById("audiokbs");
              //sound.setAttribute('src', "{{URL::asset('public/media/point.mp3')}}");
             // sound.play();

             // alertify.alert("Note","Une nouvelle note est activée").show();

            /* var r = confirm(data+'\n'+ 'Si vous voulez afficher maintenant les nouvelles actions actives dans l\'onglet des missions cliquez sur OK (Attention  la page sera rechargée et vos données peuvent être perdues !). \n Sinon vous pouvez annuler et consulter les actions ultèrieurement');
              if (r == true) {
                location.reload();
            } else {
              txt = "You pressed Cancel!";
             }*/

          
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
                            showCancelButton: false,
                            confirmButtonText: 'Ok !',
                            cancelButtonText: 'Non',
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

           
          
          

            
           }
       }
    });
   


}, 15000);


</script>

<!--activerAct_des_dates_speciales-->
<script>

 setInterval(function(){ 
     
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

           
          
          

            
           }
       }
    });
   


}, 20000);


</script>



</script>

<!-- gestion des rappels des missions (pour les rappels actions voir traitementaction blade)-->
  <script>

var idMission;
var datamission;
var iddropdownM;
var hrefidAcheverM;

    setInterval(function(){
 //alert("Hello"); 

 
     
    $.ajax({
       url : '{{ url('/') }}'+'/getMissionAjaxModal',
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

             $("#contenuMissionModal").empty().append(data);
             iddropdown=jQuery(data).find('.dropdown').attr("id");
             $("#"+iddropdown).hide();
             $("#hiddenreporterMiss").hide();
            
              
             $("#myMissionModalReporter1").modal('show');          
             idAction=jQuery(data).find('.rowkbs').attr("id");
             dataAction=jQuery(data).find('#'+idAction).html();
             hrefidAcheverA=jQuery(data).find('#idAchever').attr("href");

            
           }
       }
    });
   


}, 10000);

    $(document).ready(function() {
    
    $(document).on("click","#missionOngletaj",function() {
      //alert(datakbs);
      $("#contenuMissions").prepend(dataAction);
      $("#"+iddropdown).show();
    

        $('#actiontabs a[href="#Missionstab"]').trigger('click');


    });

  

     $(document).on("click","#reporterHideM",function() {
       
       $("#hiddenreporterMiss").toggle();  


    });

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




</script>

<!--  délégation des missions -->

<script type="text/javascript">
  

    //setInterval(function(){
 //alert("Hello"); 

  var userIdConnec = $('meta[name="userId"]').attr('content');
     
    $.ajax({
       url : '{{ url('/') }}'+'/getNotificationDeleguerMiss/'+userIdConnec,
       type : 'GET',
       dataType : 'html', // On désire recevoir du HTML
       success : function(data){ // code_html contient le HTML renvoyé
           //alert (data);

           if(data)
           {
          
           // var idUserAffecte=jQuery(data).find('.userAff').attr("id");
           // var refdoss=jQuery(data).find('.refdoss').attr("id");
           

           swal(data);

          // location.reload(); 
            /* if(idUserAffecte==userIdConnec)
             {

                 alert('un nouveau dossier dont la réf : '+refdoss +'est affecté à vous');
                 location.reload(); 


             }*/
            
              
       

            
           }
       }
    });
   


//}, 7000);




</script>



<!--  délégation des actions -->

<script type="text/javascript">
  

    //setInterval(function(){
 //alert("Hello"); 

  var userIdConnec = $('meta[name="userId"]').attr('content');
     
    $.ajax({
       url : '{{ url('/') }}'+'/getNotificationDeleguerAct/'+userIdConnec,
       type : 'GET',
       dataType : 'html', // On désire recevoir du HTML
       success : function(data){ // code_html contient le HTML renvoyé
           //alert (data);

           if(data)
           {
          
           // var idUserAffecte=jQuery(data).find('.userAff').attr("id");
           // var refdoss=jQuery(data).find('.refdoss').attr("id");
           

           swal(data);

          // location.reload(); 
            /* if(idUserAffecte==userIdConnec)
             {

                 alert('un nouveau dossier dont la réf : '+refdoss +'est affecté à vous');
                 location.reload(); 


             }*/
            
              
       

            
           }
       }
    });
   


//}, 7000);




</script>

<script>


  $("#hreftopwindow").val(top.location.href);
  
  
</script>


<!-- gestion de bouton mette à jour date spécifique 1 pour le modal descrip mission -->
 <script>

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
     




  </script>

  <!-- gestion de bouton mette à jour date spécifique 2 pour le modal descrip mission -->
 <script>

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
     




  </script>
  <script>

  $("#idAjoutMiss").click(function(e){ // On sélectionne le formulaire par son identifiant
   // e.preventDefault(); // Le navigateur ne peut pas envoyer le formulaire
     //alert('ok');

     $("#idAjoutMiss").prop('disabled', true);

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

  

    if(!$('#idFormCreationMission #dossier').val())
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
           method:"POST",
           data : donnees,
           success:function(data){

         
                alert(data);
                 $('#idFormCreationMission #typeMissauto').val('');
                 //$('#idFormCreationMission #typeMissauto option:eq(1)').prop('selected', true);
                //$('#idFormCreationMission #typeMissauto').text('Sélectionner');
                $('#idFormCreationMission #titre').val('');
                   

                },
            error: function(jqXHR, textStatus, errorThrown) {

              alert('erreur lors de création de la mission');


            }

   
    });
  }

   $("#idAjoutMiss").prop('disabled', false);

});

</script>

<!-- bouton fin creation mission-->

 <script>

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


</script>

<!-- click sur l'onglet missions --> 



<script>

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



</script>



<script>


 /* Notify = function(text, callback, close_callback, style) {

  var time = '20000';
  var $container = $('#notifications');
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

$( document ).ready(function() {
  Notify("Can't Touch This");
    Notify("Can't Touch This");
      Notify("Can't Touch This");
  Notify("Stop! Hammer time", null, null, 'danger');

  Notify("I told you homeboy (You can't touch this)",
  function () { 
    alert("clicked notification")
  },
  function () { 
    alert("clicked x")
  },
  'success'
);

});*/

</script>





<!-- gestion les reports et l'attente de réponse des actions (pour les rappels mission voir right blade)-->





  <!--<script>
$(document).ready(function() {
  $('.panel-collapse').on('show.bs.collapse', function () {
    $(this).siblings('.panel-heading').addClass('active');
  });

  $('.panel-collapse').on('hide.bs.collapse', function () {
    $(this).siblings('.panel-heading').removeClass('active');
  });
});
</script>-->


<!--<script>

$(document).ready(function () {
    $('.accordion a').click(function () {
        //removing the previous selected menu state
        $('.accordion').find('panel-heading.active').removeClass('active');
        //adding the state for this parent menu
        $(this).parents("panel-title").addClass('active');

    });
});

</script>-->




<!--<script>

  // Get the container element
var btnContainer = document.getElementById("accordion");

// Get all buttons with class="btn" inside the container
var btns = btnContainer.getElementsByClassName("panel-heading");

// Loop through the buttons and add the active class to the current/clicked button
for (var i = 0; i < btns.length; i++) {
  btns[i].addEventListener("click", function() {
    var current = document.getElementsByClassName("active");

    // If there's no active class
    if (current.length > 0) { 
      current[0].className = current[0].className.replace(" active", "");
    }

    // Add the active class to the current/clicked button
    this.className += " active";
  });
}
</script>-->
