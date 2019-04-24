 <!-- Content -->
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


                                          if (isset( $dossier)){
                                              $actions=$dossier->actions;
                                        ?>
                              @if ($actions)

                                    <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                                        <li class="active">
                                            <a href="#actionstab" data-toggle="tab">Actions</a>
                                        </li>
                                        <li>
                                            <a href="#newactiontab" data-toggle="tab">Nouvelle action</a>
                                        </li>
                                    </ul>
                                    <div id="ActionsTabContent" class="tab-content">
                                        <div class="tab-pane fade active in  scrollable-panel" id="actionstab">

                                           
                                            <!-- treeview of notifications -->
                                            <div id="accordionkbs">

<style scoped>
 
.panel-heading.active {
  background-color: #ffd051;
}
</style>
<?php if (isset( $act)){$currentaction=$act->id ;}else{$currentaction=0;} ?>
      <div class="accordion panel-group" id="accordion">
        @foreach ($actions as $action)
        <div class="panel panel-default">
          <div class="panel-heading <?php if($action->id ==$currentaction){echo 'active';}?>">
            <h4 class="panel-title">
              <a  href="{{url('action/workflow/'.$dossier->id.'/'.$action->id)}}">
               {{$action->titre}}
              </a>
            </h4>
          </div><!-- /.panel-heading -->
         <!--<div id="collapse{{$action->id}}" class="panel-collapse collapse">
            <div class="panel-body">
              <p>{{$action->descrip}}</p>
            </div>
          </div> -->
        </div>
       @endforeach


      </div>


                                              </div>

                                        </div>
                                        <div class="tab-pane fade  scrollable-panel" id="newactiontab">



             <div class="row text-center">
                                     <h3>Créer une nouvelle action</h3>


                                      
        <div class="card-header">
            Ajouter
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
            <form method="post" action="{{ route('actions.store') }}">
                <div class="form-group">
                     {{ csrf_field() }}
                   <label  style="display: inline-block;  text-align: left; width: 40px;">titre:</label>
                   <input id="titre" type="text" class="form-control" style="width:95%;  text-align: right !important;" name="titre"/>
                </div>

                <div class="form-group">
                    <label for="descrip" style="display: inline-block;  text-align: right; width: 40px;">Description:</label>
                    <input id="descrip" type="text" class="form-control" style="width:95%;  text-align: right;" name="descrip"/>
                </div>
                <div class="form-group">
                 <label for="datedeb" style="display: inline-block;  text-align: right; width: 40px;">Date début:</label>
                    <input id="datedeb" type="datetime-local" value="2018-02-25T19:24:23" class="form-control" style="width:95%;  text-align: right;" name="datedeb"/>
                </div>
                <div class="form-group">
                 <!--<label for="datefin" style="display: inline-block;  text-align: right; width: 40px;">Date fin:</label>
                    <input id="datefin" type="text" class="form-control" style="width:95%;  text-align: right;" name="datefin"/>
                </div>-->
                <div class="form-group">
                 <label for="typeact" style="display: inline-block;  text-align: right; width: 40px;">Type action:</label>
                    <select id="typeact" type="text" class="form-control" style="width:95%;  text-align: right;" name="typeact"/> 

                                   @foreach($typesactions as $tyaction)
                                        <option value="{{ $tyaction->id }}">{{ $tyaction->nom_type_action }}</option>
                                    @endforeach
                </select>
                </div>
                 <div class="form-group">
                    
                    <input id="dossier" type="hidden" class="form-control" value="{{$dossier->id}}" name="dossier"/>
                </div>
                <button  type="submit"  class="btn btn-primary">Ajouter</button>
               <!-- <button id="add"  class="btn btn-primary">Ajax Add</button>-->
            </form>
         </div>   



                                   </div>


                                    <div class="row text-center">
                                     <h3>Action libre</h3>
                                   <a href="#" class="btn btn-lg btn-success" data-toggle="modal" data-target="#basicModal">créer nouveau type d'action </a>
                                   </div> 
                                            allo
                                    </div>
                                    </div>


 @endif

                                        <?php        }
                                            ?>
                                </div>

                     

                                   

                                </div>
            </div>

<!-- modal pour creer un nouveau type d'action--> 

            <div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
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
             <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
             <button type="button" class="btn btn-primary">Enregister</button>
             </div>
                 </div>
             </div>
            </div>

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



  <script>
$(document).ready(function() {
  $('.panel-collapse').on('show.bs.collapse', function () {
    $(this).siblings('.panel-heading').addClass('active');
  });

  $('.panel-collapse').on('hide.bs.collapse', function () {
    $(this).siblings('.panel-heading').removeClass('active');
  });
});
</script>


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




<script>

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
</script>