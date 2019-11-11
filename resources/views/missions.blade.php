@extends('layouts.supervislayout')

@section('content')

         <div id="mainc" class=" row" style="padding:30px 30px 30px 30px  ">
         @if ($errors->any())
             <div class="alert alert-danger">
                 <ul>
                     @foreach ($errors->all() as $error)
                         <li>{{ $error }}</li>
                     @endforeach
                 </ul>
             </div><br />
         @endif

    @if (!empty( Session::get('success') ))
        <div class="alert alert-success">

        {{ Session::get('success') }}
        </div>

    @endif
 
            <div class="panel panel-primary column col-md-6"  style="margin-left:30px;margin-right:50px;padding:0" >
              <div class="panel-heading">
                <h4 id="kbspaneltitle" class="panel-title"> Agents connectés </h4>

              </div>
        				
		  <div class="panel-body" style="display: block;min-height:700px;padding:15px 15px 15px 15px">
		 <?php
          use \App\Http\Controllers\UsersController;
          use \App\Http\Controllers\ClientsController;
            Use App\ActionEC ;


              function custom_echo($x, $length)
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

    $user = auth()->user();
    $name=$user->name;
    $iduser=$user->id;
    $user_type=$user->user_type;

    $seance =  DB::table('seance')
        ->where('id','=', 1 )->first();

    $disp=$seance->dispatcheur ;
     $supmedic=$seance->superviseurmedic ;
    $suptech=$seance->superviseurtech ;
    $charge=$seance->chargetransport ;
    $disptel=$seance->dispatcheurtel ;
    $veilleur=$seance->veilleur ;

    $debut=$seance->debut ;
    $fin=$seance->fin ;

    $parametres =  DB::table('parametres')
        ->where('id','=', 1 )->first();
    $signature=$parametres->signature ;
    $accuse1=$parametres->accuse1 ;
    $accuse2=$parametres->accuse2 ;



      use \App\Dossier;


              function convertToHoursMins($time, $format = '%02d:%02d') {
                  if ($time < 1) {
                      return;
                  }
                  $hours = floor($time / 60);
                  $minutes = ($time % 60);
                  return sprintf($format, $hours, $minutes);
              }


              if( ($user_type=='superviseur')  || ( ($user_type=='admin')) ) {
?>
                        <div class="padding:5px 5px 5px 5px"><br>
                           <!-- <h4>Supervision</h4><br>-->
                            <ul id="tabs" class="nav  nav-tabs"  >
                                <li class=" nav-item active">
                                    <a class="nav-link active   " href="{{ route('supervision') }}"  >
                                        <i class="fas fa-lg  fa-users-cog"></i>  Supervision
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('affectation') }}"  >
                                        <i class="fas fa-lg  fa-user-tag"></i>  Affectations
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('missions') }}"  >
                                        <i class="fas fa-lg  fa-user-cog"></i>  Missions
                                    </a>
                                </li>

                                <li class="nav-item ">
                                    <a class="nav-link " href="{{ route('notifs') }}"  >
                                        <i class="fa fa-lg  fa-inbox"></i>  Flux de réception
                                    </a>
                                </li>
                            </ul>
                            <table id="tabusers" style="text-align: center ;background-color:#F8F7F6;padding:5px 5px 5px 5px">
                                <thead style="text-align:center;font-size:13px;"><th>Agent</th><th>Type</th><th>Rôle Principal</th><th>Dossiers Affectés</th><th>Missions</th><th>Actions </th><th>Actions Actives</th><th>Notifications</th></thead>
                            <?php $c=0;
                            foreach($users as $user)
                                { if($c % 2 ==0){$bg='background-color:#dddcda!important';}else{$bg='';}
                                    $role='Agent';
                                    if($user->id==$charge){$role='Chargé de transport';}
                                    if($user->id==$disp){$role='Dispatcheur';}
                                    if($user->id==$disptel){$role='Dispatcheur Téléphonique';}
                                    if($user->id==$supmedic){$role='Superviseur Médical';}
                                    if($user->id==$suptech){$role='Superviseur Technique';}
                                    if($user->id==$veilleur){$role='Veilleur de nuit';}


									$missions=UsersController::countmissions($user->id);
									$actions=UsersController::countactions($user->id);
									$actives=UsersController::countactionsactives($user->id);
									$dureeactions=UsersController::countactionsduree($user->id);
                                    if ($dureeactions <60){$dureeactions=$dureeactions.' minutes';}else{
                                        $dureeactions=convertToHoursMins($dureeactions, '%2d heures, %2d minutes');

                                    }

									$dureeactives=UsersController::countactionsactivesduree($user->id);
									if ($dureeactives <60){$dureeactives=$dureeactives.' minutes';}else{
                                      $dureeactives=convertToHoursMins($dureeactives, '%2d heures, %2d minutes');

                                    }

                                    $dossiers=UsersController::countaffectes($user->id);
                                    $notifications=UsersController::countnotifs($user->id);
                                    // if($user->type=='admin'){$role='(Administrateur)';}
									if($user->user_type!='admin'){
										
                                  if($user->isOnline()) {$c++; echo  '<tr class="usertr" onclick="showuser(this);"  id="user-'.$user->id.'" style="font-size:12px;cursor:pointer;'.$bg.'" ><td>   '.$user->name.' '.$user->lastname .'</td><td>'.$user->user_type.' </td><td>'. $role.'</td><td>'.$dossiers.'</td><td>'.$missions.'</td><td>'.$actions.' <br>charge : '.$dureeactions.'</td><td>'.$actives.' <br>charge : '.$dureeactives.'</td><td>'.$notifications.'</td>  </tr>' ;}
									}
                                }
                                    ?><br>

                            </table>
                        </div>
    <?php } ?>
	
			</div>
			</div><!--panel 1-->
			
			<div class="panel panel-danger col-md-5" style="padding:0 ; ">
                    <div class="panel-heading">
                        <h4 class="panel-title"> Liste des actions</h4>
                       <!-- <span class="pull-right">
                           <i class="fa fa-fw clickable fa-chevron-up"></i>
                            
                        </span>-->
                    </div>


                   <div class="panel-body scrollable-panel" style="display: block;">
                    <div class="row">
                        <span id="actoutes"  onclick="showatoutes()" class="pull-left" style="padding:10px 10px 10px 10px;text-align:center;background-color:grey;color:white;width:250px;cursor:pointer">Toutes les Actions</span>
                       <span id="acactives" onclick="showactives()" class="pull-right" style="padding:10px 10px 10px 10px;text-align:center;background-color:#4fc1e9;color:white;width:250px;cursor:pointer">Actions Actives</span>
                    </div>
                        <?php   foreach($users as $user)
                       {
                       if($user->user_type!='admin'){

                       if($user->isOnline()) {
                           $c++;?>
                        <div  class="agent" id="agent-<?php echo $user->id;?>"  style="display:none">
                       <?php
					   echo  '<h4 style=";text-align:center;background-color:black;color:white;padding-top:10px;padding-bottom:10px;border:2px solid black">Agent : '.$user->name.' '.$user->lastname .'</h4>';

					   
					 $missions=  $user->activeMissions;$c=0;
					foreach($missions as $m)
					{$c++;
 						echo  '<h4 style=";text-align:center;background-color:#D0ECE7;color:black;padding-top:10px;padding-bottom:10px;border:2px solid grey">Mission '.$c.' : ('.$m->typeMission->nom_type_Mission .')  '.$m->titre.'</h4>';

					 echo '<ul>';
                        $statut='';

                      // $actions=$m->activeActionEC;
                       $actions=$m->ActionECs;
                      foreach($actions as $act)
                      {
                          if (
                              ($act->user_id == '')&&($act->assistant_id == '') ||
                              (($act->user_id == $user->id ) && ( $act->assistant_id == $user->id || ($act->assistant_id =='' )   )  )
                           )
                              {

                          $datedebut=$act->date_deb; $debut=date("d/m/Y H:i", strtotime($datedebut));
                          $datefin=$act->date_fin; $fin=date("d/m/Y H:i", strtotime($datefin));
                          $statut=$act->statut; if ($statut=='active'){$color='#4fc1e9';$statut='Active';} if ($statut=='inactive'){$color='grey';$statut='Inactive';}if ($statut=='faite' || $statut=='rfaite'){$color='#45B39D';$statut='Réalisée';}if ($statut=='reportee' || $statut=='rappelee'){$color='#F0B27A';$statut='Mise en attente';}if ($statut=='annulee' ){$color='##FD9883';$statut='Annulée';}

                          echo  '<li style="display:block;margin-left:-35px;margin-bottom:15px;border:3px solid '.$color.';padding:10px 10px 10px 10px"  class=" liactions action-'.$statut.'">';
                        //  echo '<a  > <span style="color:grey;margin-top:25px" class="fa-li"><i style="color:grey" class="fa fa-cogs"></i></span></a>';

                          echo '<p style="font-size:14px"><label style="font-weight:600">Action:</label>  '.custom_echo($act->titre,50).'</p>';
                          echo '<p style="font-size:13px"><label style="font-weight:600">Description:</label>  '.custom_echo($act->descrip,150).'</p>';
                          echo '<div class="row" style="font-size:14px;padding-left:0px;"><div class="col-md-4 col-lg-4">'; if($datedebut!=''){ echo '<label style="font-weight:600">Début:</label>  '.$debut;} echo '</div> <div class="col-md-4">'; if($datefin!=''){echo '<label style="font-weight:600">Fin:</label>  '.$fin;} echo '</div><div class="col-md-3 col-lg-2"><b style="color:'.$color.'">'.$statut.'</b></div><div class="col-md-2 col-lg-2">Charge: '.$act->duree.' mins</div>';

                          echo '</div>';
						  echo  '</li>';

                              } // end user_id


                      }
					echo'</ul>';
					}

                            echo '<center><h4 style="text-align:center;background-color:#8A0808;color:white;border:1px solid grey;padding-top:10px;padding-bottom:10px">Actions déleguées</h4></center>';
                            $statut='';
                            echo'<ul>';
                            $allactionecs=ActionEC::get();
                            foreach($allactionecs as $actd)
                                {

                                    if (  ($actd->statut =='deleguee')  && ($user->id == $actd->assistant_id)    )
                                        {  $miss=$actd->Mission;

                                            $datedebut=$actd->date_deb; $debut=date("d/m/Y H:i", strtotime($datedebut));
                                            $datefin=$actd->date_fin; $fin=date("d/m/Y H:i", strtotime($datefin));
                                             $comment1=  $actd->comment1;
                                            echo  '<li style="margin-left:-35px;margin-bottom:15px;border:3px solid #8A0808;padding:10px 10px 10px 10px">';
                                            //  echo '<a  > <span style="color:grey;margin-top:25px" class="fa-li"><i style="color:grey" class="fa fa-cogs"></i></span></a>';

                                            echo '<p style="font-size:15px"><label style="font-weight:600">Mission:</label>  ('. $miss->typeMission->nom_type_Mission .') '.custom_echo($miss->titre,50).'</p>';
                                            echo '<p style="font-size:14px"><label style="font-weight:600">Action:</label>  '.custom_echo($actd->titre,50).'</p>';
                                            echo '<p style="font-size:13px"><label style="font-weight:600">Description:</label>  '.custom_echo($actd->descrip,150).'</p>';
                                            echo '<div class="row" style="font-size:14px;padding-left:0px;"><div class="col-md-4 col-lg-4">'; if($datedebut!=''){ echo '<label style="font-weight:600">Début:</label>  '.$debut;} echo '</div> <div class="col-md-4">'; if($datefin!=''){echo '<label style="font-weight:600">Fin:</label>  '.$fin;} echo '</div><div class="col-md-3 col-lg-2">Afectée par: '.$actd->agent->name.' '.$actd->agent->lastname .'</div><div class="col-md-3 col-lg-2">Charge : '.$actd->duree.' mins</div>';

                                            echo '</div>';
                                            echo  '</li>';

                                        }
                                }

                            echo'</ul>';

                            ?>
					 
                        </div>

                   <?php }

                       }
                       }

                       ?>


            </div>
            <!-- /.content -->
        </div>

  </div><!-- /main -->

     @endsection
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<script>

    function hideinfos() {
        $('#tab1').css('display','none');
    }
    function hideinfos2() {
        $('#tab2').css('display','none');
    }
    function hideinfos3() {
        $('#tab3').css('display','none');
    }
    function hideinfos4() {
        $('#tab4').css('display','none');
    }
    function showinfos() {
        $('#tab1').css('display','block');
    }
    function showinfos2() {
        $('#tab2').css('display','block');
    }
    function showinfos3() {
        $('#tab3').css('display','block');
    }
    function showinfos4() {
        $('#tab4').css('display','block');
    }


    function   showatoutes (){

        var elements = document.getElementsByClassName('liactions');
        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = 'block';
        }
    }

function   showactives (){

    var elements = document.getElementsByClassName('liactions');
    for (var i = 0; i < elements.length; i++){
        elements[i].style.display = 'none';
    }

             var elements = document.getElementsByClassName('action-Active');
            for (var i = 0; i < elements.length; i++){
                elements[i].style.display = 'block';
            }
    }

        // function slect all elements from class tag
    function toggle(className, displayState){
        var elements = document.getElementsByClassName(className);

        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = displayState;
        }
    }

    function toggle2(className, displayState){
        var elements = document.getElementsByClassName(className);

        for (var i = 0; i < elements.length; i++){
            elements[i].style.border = displayState;
        }
    }

    function showuser(elm) {
        var userid = elm.id;
        var user = userid.slice( 5);
        //document.getElement('agent').style.display='none';
        toggle('agent', 'none');
        toggle2('usertr', 'none');

        document.getElementById('user-'+user).style.border='2px solid black';
        document.getElementById('agent-'+user).style.display='block';
    }
        function changing(elm) {
        var champ=elm.id;

        var val =document.getElementById(champ).value;
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('home.parametring') }}",
            method: "POST",
            data: {  champ:champ ,val:val, _token: _token},
            success: function ( ) {
                $('#'+champ).animate({
                    opacity: '0.3',
                });
                $('#'+champ).animate({
                    opacity: '1',
                });

            }
        });

    }


    function changingseance(elm) {
        var champ=elm.id;

        var val =document.getElementById(champ).value;
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('home.parametring2') }}",
            method: "POST",
            data: {  champ:champ ,val:val, _token: _token},
            success: function ( ) {
                $('#'+champ).animate({
                    opacity: '0.3',
                });
                $('#'+champ).animate({
                    opacity: '1',
                });

            }
        });

    }

</script>

<style>
    #tabusers td, #tabusers th{text-align: center;padding-left:5px;padding-right: 5px;}
    #tabusers th{height:60px;background-color: #4FC1E9;color:white;border-left:1px solid white;}
    #tabusers td{border-left:1px solid white;border-bottom:1px solid white;}
    #tabusers tr{margin-bottom:15px;min-height:40px;}


    </style>