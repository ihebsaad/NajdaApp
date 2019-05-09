 <!-- Content -->

 <style>
/* Style The Dropdown Button */
.dropbtn {
  /*background-color: #4CAF50;*/
  background-color: white;
  color: black;
  padding: 10px;
  font-size: 16px;
  border: none;
  cursor: pointer;
}

/* The container <div> - needed to position the dropdown content */
.dropdown {
  position: relative;
  display: inline-block;
}

/* Dropdown Content (Hidden by Default) */
.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f9f9f9;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
   right: 0;
}

/* Links inside the dropdown */
.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

/* Change color of dropdown links on hover */
.dropdown-content a:hover {background-color: #f1f1f1}

/* Show the dropdown menu on hover */
.dropdown:hover .dropdown-content {
  display: block;
}

/* Change the background color of the dropdown button when the dropdown content is shown */
.dropdown:hover .dropbtn {
  background-color: #3e8e41;
}
</style>

<div class="panel panel-danger">
                    <div class="panel-heading">
                        <h4 class="panel-title">Actions</h4>
                        <span class="pull-right">
                           <i class="fa fa-fw clickable fa-chevron-up"></i>
                            
                        </span>
                    </div>


                   <div class="panel-body scrollable-panel" style="display: block;">
                        

                        <div class="panel-body" style="display: block;">
                            <?php use \App\Http\Controllers\ActionController;
                                $typesactions= ActionController::ListeTypeActions();


                             /// if (isset( $dossier)){
                                 // $actions=$dossier->activeActions;

                               if (true){ 
                                $actions=Auth::user()->activeActions;
                            ?>
                  @if ($actions)
<!--  début tab -+----------------------------------------------------------------------------------------->
                        <ul id="actiontabs" class="nav nav-tabs" style="margin-bottom: 15px;">
                            <li class="active">
                                <a href="#actionstab" data-toggle="tab">Actions</a>
                            </li>
                            <li>
                                <a href="#newactiontab" data-toggle="tab">Nouvelle action</a>
                            </li>
                        </ul>
                        <div id="ActionsTabContent" class="tab-content">

                          <!-- début  actions tab-->
                          <div class="tab-pane fade active in " id="actionstab">
                              <!--<div class="tab-pane fade active in  scrollable-panel" id="actionstab">-->

                               
                                <!-- treeview of notifications -->
                                <div id="accordionkbs">

                                <style scoped>
                               
                              .panel-heading.active {
                                background-color: #00BFFF /*#86B404  #2EFEF7;*/
                              }
                              .panel-heading.ColorerActionsCourantes{
                                background-color: #ffd051;
                              }

                              </style>
                              <?php if (isset($act)){$currentaction=$act->id;}else{$currentaction=0;} ?>

                              <?php if (isset($dossier)){$dosscourant=$dossier->id ;}else{$dosscourant=0;} ?>
                                    
                                    <div class="accordion panel-group" id="accordion">
                                 @if (!$actions->isEmpty())
                                  <div class="row">

                                <div class="col-md-4" >
                                  
                                </div>
                               
                                    <div class="col-md-8">
                                  <!--<button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#toutesactions">États de toutes les actions</button> -->
                                  </div>

                                  </div>
                                  <br>
                                   <div class="row">
                                    <div class="col-md-8" >
                                    <!--<h4 >les actions actives : </h4>-->
                                   </div>
                                   </div>
                                    <br>                             
                                  @endif
                                      @foreach ($actions as $action)
                                      <div class="row" style="padding-bottom: 3px;">
                                      <div class="col-md-10">
                                      <div class="panel panel-default">
                                        <div class="panel-heading <?php if($action->id ==$currentaction){echo 'active';}
                                        else {if($action->dossier->id==$dosscourant){echo 'ColorerActionsCourantes' ;}}?>">
                                         
                                           <h4 class="panel-title">
                                              <a data-toggle="collapse" href="#collapse{{$action->id}}">{{$action->dossier->reference_medic}}   {{$action->titre}}</a>
                                           </h4>
                                        </div>

                                       <div id="collapse{{$action->id}}" class="panel-collapse collapse">
                                            <ul class="list-group">
                                              @foreach($action->activeSousAction as  $sas)
                                              <li class="list-group-item"><a  href="{{url('dossier/action/Traitementsousaction/'.$action->dossier->id.'/'.$action->id.'/'.$sas->id)}}">{{$sas->titre}} </a></li>
                                              @endforeach
                                            </ul>

                                        </div>


                                        <!-- /.panel-heading -->


                                      </div>
                                    </div>
                                    <div class="col-md-2" >
                                     


                                            <a class="workflowkbs" id="<?php echo $action->id ?>" style="color:black !important; margin-top: 10px; margin-right: 10px;" data-toggle="modal" data-target="#myworow" title ="Voir Workflow" href="#"><span class="fa fa-2x fa-tasks" style=" margin-top: 10px; margin-right: 20px;" aria-hidden="true"></span>
                                            </a>
                                            <input id="workflowh<?php echo $action->id ?>" type="hidden" value="{{$action->titre}}">

                                             {{-- <a  style="color:black !important; margin-top: 10px; margin-right: 10px;" title ="Voir Workflow" href="{{url('action/workflow/'.$action->dossier->id.'/'.$action->id)}}"><span class="fa fa-2x fa-cogs" style=" margin-top: 10px; margin-right: 20px;" aria-hidden="true"></span>
                                            </a>--}}
                                                                         
                                        <?php $actk=$action ;?>
                                     
                                    </div>
                                    </div>

                                     @endforeach


                                    </div>


                                              </div>

                                        </div>


                                    <!-- fin  actions tab---------------------------------------------------------->

                                    <!-- début creation nouvelle actions tab------------------------>

                                    <div class="tab-pane fade  scrollable-panel" id="newactiontab">



                                   <div class="row text-center">
                                                           <h4>Créer une nouvelle action</h4>

                                                            
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
                                  <form  method="post" action="{{ route('actions.store') }}" style="padding-top:30px">
                                      <div class="form-group">
                                           {{ csrf_field() }}
                                         <div class="row">
                                             <div class="col-md-3"> <label  style="display: inline-block;  text-align: left; width: 40px;">titre:</label></div>
                                             <div class="col-md-9"><input id="titre" type="text" class="form-control" style="width:95%;  text-align: right !important;" name="titre"/></div>
                                       </div>
                                      </div>
                                      <div class="form-group">
                                          <div class="row">
                                              <div class="col-md-3">     <label for="descrip" style="display: inline-block;  text-align: right; width: 40px;">Description:</label></div>
                                              <div class="col-md-9"><input id="descrip" type="text" class="form-control" style="width:95%;  text-align: right;" name="descrip"/></div>
                                          </div>
                                      </div>
                                      <div class="form-group">
                                        <?php $da= date('Y-m-d\TH:m'); ?>

                                            <div class="row">
                                                <div class="col-md-3">  <label for="datedeb" style="display: inline-block;  text-align: right; width: 40px;">Date début:</label></div>
                                                <div class="col-md-9"> <input id="datedeb" type="datetime-local" value="<?php echo $da ?>" class="form-control" style="width:95%;  text-align: right;" name="datedeb"/></div>
                                            </div>
                                      </div>

                                           <div class="row">
                                              <div class="col-md-3">
                                                  <label for="typeact" style="display: inline-block;  text-align: right; width: 40px;">Type action:</label>
                                              </div>
                                              <div class="col-md-9">  <select id="typeact" type="text" class="form-control" style="width:95%;  text-align: right;" name="typeact"/>
                                                         @foreach($typesactions as $tyaction)
                                                              <option value="{{ $tyaction->id }}">{{ $tyaction->nom_type_action }}</option>
                                                          @endforeach
                                      </select>
                                              </div>
                                           </div>

                                      <div class="form-group">

                                           <?php if(isset($dossier)) {  ?>
                                          
                                          <input id="dossier" type="hidden" class="form-control" value="{{$dossier->reference_medic}}" name="dossier"/>
                                          <?php } else {  ?>
                                               <div class="row">

                                               <div class="col-md-3">     <label for="typeact" style="display: inline-block;  text-align: right; width: 40px;">Réf dossier</label></div>
                                                  <div class="col-md-9"> <input id="dossier" type="text" class="form-control" value="" name="dossier"/></div>
                                               </div>

                                           <?php } ?>

                                      </div>
                                       <button  type="submit"  class="btn btn-primary">Ajouter</button>
                                     <!-- <button id="add"  class="btn btn-primary">Ajax Add</button>-->
                                  </form>
                               </div>   



                                   </div>


                                 <div class="row text-center">
                                    <h4>Action libre</h4>
                                     <a href="#" class="btn btn-lg btn-success" data-toggle="modal" data-target="#NouveauType">créer nouveau type d'action </a>
                                 </div> 
                                                                   
                    </div>
                 </div>
                               
                   <!-- fin creation nouvelle actions tab------------------------>        

                    @endif

                    <?php } ?>

 <!--  Fin tab -+----------------------------------------------------------------------------------------->     
                       </div>

                 </div>
            </div>

<!-- modal pour creer un nouveau type d'action--> 

            <div class="modal fade" id="NouveauType" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
             <div class="modal-dialog">
             <div class="modal-content">
             <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
             <h4 class="modal-title" id="myModalLabel">Créer un nouveau type d'action</h4>
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



 <!-- Modal pour toutes les etats actions ------------->


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

              <div id="toutesactions" class="modal fade" role="dialog">
                <div class="modal-dialog">

                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">États de toutes les actions de dossier courant</h4>
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
                                <th class="col-md-5 col-xs-5">Nom de l'action</th>
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
                                  $acts=$dossier->actions; $u=1; ?>
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
  <div class="modal fade" id="myworkflow" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
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
  </div>
  
</div>
<!--fin modal workflow -->
<!-- pour l'action libre-->
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

               url : '{{URL('/action/updateworkflow/')}}',
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
       // $urlapp='http://localhost/NejdaApp';
        $urlapp=env('APP_URL');

    }
    ?>
<script>

$(document).ready(function() {

  $('.workflowkbs').on('click', function() {


   var idw=$(this).attr("id");
   //alert(idw);
   var nomact=$('#workflowh'+idw).attr("value");
      $("#titleworkflowmodal").empty().append(nomact);//ou la methode html

           $.ajax({

               url: "<?php echo $urlapp; ?>/action/getAjaxWorkflow/"+idw,
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

  })


});
</script>




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
