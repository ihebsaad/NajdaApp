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
                                    <h4 id="kbspaneltitle" class="panel-title">  </h4>
                                  <!--  <span class="pull-right">
                                         <i id="open" class="fa fa-lg fa-window-maximize"></i>
                                         <i style="margin-left:20px" id="close" class="fa fa-lg   fa-window-restore"></i>
                                    </span>-->
              </div>
        				
		  <div class="panel-body" style="display: block;min-height:450px;padding:15px 15px 15px 15px">
		 <?php
use \App\Http\Controllers\UsersController;
    $user = auth()->user();
    $name=$user->name;
    $iduser=$user->id;
    $user_type=$user->user_type;

    $seance =  DB::table('seance')
        ->where('id','=', 1 )->first();
    $disp=$seance->dispatcheur ;
    $sup=$seance->superviseur ;
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
    $dollar=$parametres->dollar ;
    $euro=$parametres->euro ;

  
    

if( ($user_type=='superviseur')  || ( ($user_type=='admin')) ) {
?>
                        <div class="padding:5px 5px 5px 5px"><br>
                           <!-- <h4>Supervision</h4><br>-->
                            <table id="tabusers" style="text-align: center ;background-color:#F8F7F6;padding:5px 5px 5px 5px">
							<thead style="text-align:center"><th>Agent</th><th>Type</th><th>Rôle Principal</th><th>Dossiers Affectés</th><th>Missions</th><th>Actions </th><th>Actions Actives</th><th>Notifications</th>
                            <?php
                            foreach($users as $user)
                                {
                                    $role='Agent';
                                    if($user->id==$disp){$role='Dispatcheur';}
                                    if($user->id==$disptel){$role='Dispatcheur Téléphonique';}
                                    if($user->id==$supmedic){$role='Superviseur Médical';}
                                    if($user->id==$suptech){$role='Superviseur Technique';}
                                    if($user->id==$veilleur){$role='Veilleur de nuit';}
                                    if($user->id==$charge){$role='Chargé de transport';}
									
									$missions=UsersController::countmissions($user->id);
									$actions=UsersController::countactions($user->id);
									$actives=UsersController::countactionsactives($user->id);
                                    $dossiers=UsersController::countaffectes($user->id);
                                    $notifications=UsersController::countnotifs($user->id);
                                    // if($user->type=='admin'){$role='(Administrateur)';}
									if($user->user_type!='admin'){
										
                                  if($user->isOnline()) { echo  '<tr><td>   '.$user->name.' '.$user->lastname .'</td><td>'.$user->user_type.' </td><td>'. $role.'</td><td>'.$dossiers.'</td><td>'.$missions.'</td><td>'.$actions.'</td><td>'.$actives.'</td><td>'.$notifications.'</td>  </tr>' ;}
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
                        <h4 class="panel-title"> <br></h4>
                       <!-- <span class="pull-right">
                           <i class="fa fa-fw clickable fa-chevron-up"></i>
                            
                        </span>-->
                    </div>


                   <div class="panel-body scrollable-panel" style="display: block;">
                 
                  </div>
 
            </div>
            <!-- /.content -->
        </div>

  </div><!-- /main -->
	<style>
        #tabusers td, #tabusers th{text-align: center;padding-left:5px;padding-right: 5px;}
        #tabusers th{height:60px;background-color: #4FC1E9;color:white;border-left:1px solid white;}
        #tabusers td{border-left:1px solid white;border-bottom:1px solid white;}
        #tabusers tr{margin-bottom:15px;min-height:40px;}
        </style>
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

