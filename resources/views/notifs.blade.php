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


      use \App\Dossier;


              function convertToHoursMins($time, $format = '%02d:%02d') {
                  if ($time < 1) {
                      return;
                  }
                  $hours = floor($time / 60);
                  $minutes = ($time % 60);
                  return sprintf($format, $hours, $minutes);
              }

			  function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'année',
        'm' => 'mois',
        'w' => 'semaine',
        'd' => 'jour',
        'h' => 'heure',
        'i' => 'minute',
     //   's' => 'seconde',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ' : 'maintenant';
}


$urlapp=env('APP_URL');

if (App::environment('local')) {
    // The environment is local
    $urlapp='http://localhost/najdaapp';
}
              if( ($user_type=='superviseur')  || ( ($user_type=='admin')) ) {
?>
                        <div class="padding:5px 5px 5px 5px">
                           <!-- <h4>Supervision</h4><br>-->
                            <ul id="tabs" class="nav  nav-tabs"  >
                                <li class=" nav-item ">
                                    <a class="nav-link    " href="{{ route('supervision') }}"  >
                                        <i class="fas fa-lg  fa-users-cog"></i>  Supervision
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('affectation') }}"  >
                                        <i class="fas fa-lg  fa-user-tag"></i>  Affectations
                                    </a>
                                </li>
                                <li class="nav-item active">
                                    <a class="nav-link active" href="{{ route('affectation') }}"  >
                                        <i class="fa fa-lg  fa-inbox"></i>  Flux de réception
                                    </a>
                                </li>
                            </ul>
							<br>
                            <table id="tabusers" style="text-align: center ;background-color:#F8F7F6;padding:5px 5px 5px 5px">
                                <thead style="text-align:center;font-size:14px;"><th>Type</th><th>Réception</th><th>Emetteur</th><th>Sujet</th><th>Nb attchs</th><th>Dossier</th><th>Affecté à</th><th>Consulté</th></thead>
								<tbody class="thetable" style="font-size:14px;line-height:30px">
                            <?php $c=0;
                            foreach($entrees as $entree)
                                {
									$type=$entree['type'];
									$time=$entree['created_at'];$heure= "<small>Il y'a ".time_elapsed_string($time, false).'</small>';
								//	$emetteur= $entree['emetteur'] ;
									$emetteur=custom_echo($entree['emetteur'],'18');
								//	$sujet= $entree['sujet'] ;
									$sujet=custom_echo($entree['sujet'],'20');  
									$dossier=$entree['dossier']; if($dossier==''){$folder='<small style="color:red">Non Dispatché!</small>';}else{$folder=$entree['dossier'];}
									$attachs=$entree['nb_attach'];
									$affecte=$entree['affecte']; if($affecte>0){$agent= UsersController::ChampById('name',$affecte).' '.UsersController::ChampById('lastname',$affecte); }else{ $agent='<span style="color:red">Non Affecté!</span>'; }
									$viewed=$entree['viewed']; if($viewed==1){$consulte='<span style="color:green">OUI';}else{$consulte='<span style="color:red">NON</span>';}
									
									echo '<tr><td>';
									  if ($type=='email'){echo '<img width="15" src="'. $urlapp .'/public/img/email.png" />';}   if ($type=='fax'){echo '<img width="15" src="'. $urlapp .'/public/img/faxx.png" />';}  if ($type=='sms'){echo '<img width="15" src="'. $urlapp .'/public/img/smss.png" />';}   if ($type=='phone'){echo '<img width="15" src="'. $urlapp .'/public/img/tel.png" />';} 
									echo ' '.$type.'</td><td>'.$heure.'</td><td>'.$emetteur.'</td><td>';
									if($dossier==''){ ?><a href="{{action('EntreesController@showdisp', $entree['id'])}}"> <?php }else{ ?> <a href="{{action('EntreesController@show', $entree['id'])}}"> <?php }  
									echo $sujet.'</a></td><td>'.$attachs.'</td><td>'.$folder.'</td><td>'.$agent.'</td><td>'.$consulte.'</td></tr>';
                                }
								 
?>
								</tbody>
                                    
                            </table>
                        </div>
    <?php } ?>
	
			</div>
			</div><!--panel 1-->
			
			<div class="panel panel-danger col-md-5" style="padding:0 ; ">
                    <div class="panel-heading">
                        <h4 class="panel-title"> </h4>

                    </div>


                   <div class="panel-body scrollable-panel" style="display: block;">
 

                   </div>
            <!-- /.content -->
        </div>

  </div><!-- /main -->

     @endsection
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<script>

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
 

	@media (min-width: 1024px) {

	.thetable{line-height:30px;}

	}
	
	@media  (width > 1280px)    {
		
	 .thetable{line-height:30px;}
		
		}
	
 
    </style>