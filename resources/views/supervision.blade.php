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


              function custom_echo($x, $length)
              {
                  if(strlen($x)<=$length)
                  {
                      echo $x;
                  }
                  else
                  {
                      $y=substr($x,0,$length) . '..';
                      echo $y;
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
              $disptel2=$seance->dispatcheurtel2 ;
              $disptel3=$seance->dispatcheurtel3 ;
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
                               <div class="row" style="margin-top:20px">
                                <span   class=" " style="margin-left:10px;margin-right:30px;marign-top:25px;padding:5px 5px 5px 5px;text-align:center;background-color:#fd9883;color:white;width:150px;">Grande charge</span>
                                <span    class=" " style="margin-left:10px;margin-right:30px;marign-top:25px;padding:5px 5px 5px 5px;text-align:center;background-color:#FFCE54;color:black;width:150px;">En pause</span>
                               </div>
                                <thead style="text-align:center;font-size:13px;"><th>Agent</th><th>Type</th><th>Rôle(s)</th><th>Dossiers Affectés</th><th>Missions</th><th>Actions </th><th>Actions Actives</th><th>Notifications</th></thead>
                            <?php $c=0;
                            foreach($users as $user)
                                { if($c % 2 ==0){$bg='background-color:#dddcda!important';}else{$bg='';}


                                    $role=' ';
                                    if($user->id==$veilleur){$role.='(Veilleur de nuit)';}
                                    if($user->id==$disp){$role.='(Dispatcheur)';}
                                    if($user->id==$disptel){$role.='(Dispatcheur Téléphonique)';}
                                    if($user->id==$disptel2){$role.='(Dispatcheur Téléphonique 2)';}
                                    if($user->id==$disptel3){$role.='(Dispatcheur Téléphonique 3)';}
                                    if($user->id==$supmedic){$role.='(Superviseur Médical)';}
                                    if($user->id==$suptech){$role.='(Superviseur Technique)';}
                                    if($user->id==$charge){$role.='(Chargé de transport)';}


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
									if($user->id!=1){
										
                                  if(/*$user->isOnline()  && */ $user->statut== 1 &&  $user->user_type!= 'financier' && $user->user_type!= 'bureau' && $user->user_type!= 'admin') {$style=''; if($dureeactives >20){$style='background-color:#fd9883;color:white;';}    if($user->statut==2){$style='background-color:#FFCE54;color:black;';}
                                      $c++; echo  '<tr class="usertr" onclick="showuser(this);"  id="user-'.$user->id.'" style="font-size:12px;cursor:pointer;'.$style.'" ><td>   '.$user->name.' '.$user->lastname .'</td><td>'.$user->user_type.' </td><td>'. $role.'</td><td>'.$dossiers.'</td><td>'.$missions.'</td><td>'.$actions.' <br>charge : '.$dureeactions.'</td><td>'.$actives.' <br>charge : '.$dureeactives.'</td><td>'.$notifications.'</td>  </tr>' ;}
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
                        <h4 class="panel-title"> Liste des dossiers</h4>
                       <!-- <span class="pull-right">
                           <i class="fa fa-fw clickable fa-chevron-up"></i>
                            
                        </span>-->
                    </div>


                   <div class="panel-body scrollable-panel" style="display: block;">
                       <div class="row" style="margin-bottom:15px;">
                           <div class="col-md-4" style=";color:#F39C12"><i class="fa fa-lg fa-folder"></i>  <b>Dossier Mixte</b></div>
                           <div class="col-md-4" style=";color:#52BE80"><i class="fa fa-lg fa-folder"></i>  <b>Dossier Medical</b></div>
                           <div class="col-md-4" style=";color:#3498DB"><i class="fa fa-lg fa-folder"></i>  <b>Dossier Technique</b></div>
                       </div>

                    <?php   foreach($users as $user)
                       {
                       if($user->id!=1){

                       if(/*$user->isOnline() &&*/ $user->statut== 1 && $user->user_type!= 'bureau' && $user->user_type!= 'financier' && $user->user_type!= 'admin' ) {
                           $c++;?>
                        <div  class="agent" id="agent-<?php echo $user->id;?>"  style="display:none">
                       <?php echo  '<h4 style=";text-align:center;background-color:grey;color:white;padding-top:10px;padding-bottom:10px;border:2px solid black">Agent : '.$user->name.' '.$user->lastname .'</h4>';

                           $folders = Dossier::where('affecte','=',$user->id)->get();
                          echo '<ul  class="fa-ul"  >';
                           foreach($folders as $folder)
                           {$idd=$folder['id'];
                            $missions=UsersController::countmissionsDossier($idd);
                            $actions=UsersController::countactionsDossier($idd);

                              $notifications=UsersController::countnotifsDossier($idd);

                            $complexite=$folder['complexite'];
                            $statutdos=$folder['statut'];
                       $type=$folder['type_dossier'];if($type=='Mixte'){$style="background-color:#F39C12;";}if($type=='Medical'){$style="background-color:#52BE80";} if($type=='Technique'){$style="background-color:#3498DB;";}
                       $ref=$folder['reference_medic'];$abn=$folder['subscriber_lastname'].' '.$folder['subscriber_name'];$idclient=$folder['customer_id'];$client=   ClientsController::ClientChampById('name',$idclient) ;?>

                        <li  id="dossier-<?php echo $idd;?>"    style=";padding:10px 10px 10px 10px;color:white;margin-top:20px;<?php echo $style; if($statutdos==5){echo ';border:2px solid black;';}?>" >
                            <a title="voir la fiche" href="{{action('DossiersController@fiche',$idd)}}"> <span style="color:grey;margin-top:25px" class="fa-li"><i style="color:grey" class="fa fa-folder-open"></i></span></a>
                            <div class="row pull-right " style="width:70%">
                                Affectation: <select style="font-weight:bold;border:1px solid grey;<?php echo $style;?>" id="statut<?php echo $idd;?>" onchange="changingst(this, '<?php echo $idd;?>')"  ><option <?php if($statutdos==5){ echo 'selected="selected"';} ?> value="5">Manuelle</option><option <?php if($statutdos==2){ echo 'selected="selected"';} ?> value="2">Automatique</option></select> <?php // if($statutdos==5){echo 'Manuelle';}else{echo 'Automatique';}  ;?>
                            </div>
                            <div class="row">

                            <div class="col-md-4">
                                <label style="font-size: 18px;"><a style="color:white;text-shadow: 1px 1px black" title="voir les détails du dossier" href="{{action('DossiersController@view',$idd)}}"><?php echo $ref;?></a></label>
                           <div class="infos">  <small style="font-size:11px"><?php custom_echo($abn,25);?></small>
                               <br><small style="font-size:10px"><?php echo custom_echo($client,25);?></small>
                           </div>
                           </div>

                               <div class="col-md-4" style=" ">
                                   Complexité: <?php echo $complexite;?><br>
                                   Notifications: <?php echo $notifications;?>

                               </div>
                               <div class="col-md-4"  style=" ">
                                   Missions: <?php echo $missions;?><br>
                                   Actions: <?php echo $actions;?>
                               </div>


                           </div><!---row-->

                       </li>
                    <?php       } ?>
                    </ul>
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

    function changingst(elm,dossier) {
        var champ=elm.id;

        var val =document.getElementById(champ).value;
        //  var type = $('#type').val();
         //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('dossiers.updating') }}",
            method: "POST",
            data: {dossier: dossier , champ:'statut' ,val:val, _token: _token},
            success: function (data) {
                $('#'+champ).animate({
                    opacity: '0.3',
                });
                $('#'+champ).animate({
                    opacity: '1',
                });
             //   location.reload();
                if(val==5){
                    $('#dossier-'+dossier).css("border","2px solid black");
                }else{
                    $('#dossier-'+dossier).css("border","none");

                }

             //   document.getElementById("myDIV").style.

            }
        });
        // } else {

        // }
    }

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