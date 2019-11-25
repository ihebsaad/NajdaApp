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
              use App\Entree;
              use \App\Http\Controllers\UsersController;
   use \App\Http\Controllers\ClientsController;
              use \App\Attachement ;
$urlapp="http://$_SERVER[HTTP_HOST]/najdaapp";
   

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


$urlapp="http://$_SERVER[HTTP_HOST]/najdaapp";
              if( ($user_type=='superviseur')  || ( ($user_type=='admin')) ) {
?>
              <?php           $today=date('Y-m-d');
              $entrees = Entree::orderBy('id', 'desc')->where('statut','<','2')
                  ->where('created_at','like',$today.'%')
                  ->paginate(12);

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

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('missions') }}"  >
                                        <i class="fas fa-lg  fa-user-cog"></i>  Missions
                                    </a>
                                </li>

                                <li class="nav-item active">
                                    <a class="nav-link active" href="{{ route('affectation') }}"  >
                                        <i class="fa fa-lg  fa-inbox"></i>  Flux de réception
                                    </a>
                                </li>
                            </ul>
							<br>
                            <H2>Flux de réception d'Aujourd'hui </H2>
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
									
									echo '<tr class="usertr" onclick="show(this);"  id="user-'.$entree['id'].'" ><td>';
									  if ($type=='email'){echo '<img width="15" src="'. $urlapp .'/public/img/email.png" />';}   if ($type=='fax'){echo '<img width="15" src="'. $urlapp .'/public/img/faxx.png" />';}  if ($type=='sms'){echo '<img width="15" src="'. $urlapp .'/public/img/smss.png" />';}   if ($type=='phone'){echo '<img width="15" src="'. $urlapp .'/public/img/tel.png" />';} 
									echo ' '.$type.'</td><td>'.$heure.'</td><td>'.$emetteur.'</td><td>';
									if($dossier==''){ ?><a href="{{action('EntreesController@showdisp', $entree['id'])}}"> <?php }else{ ?> <a href="{{action('EntreesController@show', $entree['id'])}}"> <?php }  
									echo $sujet.'</a></td><td>'.$attachs.'</td><td>'.$folder.'</td><td>'.$agent.'</td><td>'.$consulte.'</td></tr>';

                                }

?>
								</tbody>

                            </table>
                            <?php echo $entrees->render(); ?>

                        </div>
    <?php } ?>
	
			</div>
			</div><!--panel 1-->
			
			<div class="panel panel-danger col-md-5" style="padding:0 ; ">
                    <div class="panel-heading">
                        <h4 class="panel-title"> </h4>

                    </div>


                   <div class="panel-body scrollable-panel" style="display: block;">

                    <?php   foreach($entrees as $entree)
                       {
 ?> <div class="agent" id="agent-<?php echo $entree->id;?>"  style="display:none" >
                            <div class="form-group pull-left">
                               <?php if ($type=='email'){echo '<img width="15" src="'. $urlapp .'/public/img/email.png" />';} ?><?php if ($type=='fax'){echo '<img width="15" src="'. $urlapp .'/public/img/faxx.png" />';} ?><?php if ($type=='sms'){echo '<img width="15" src="'. $urlapp .'/public/img/smss.png" />';} ?> <?php if ($type=='phone'){echo '<img width="15" src="'. $urlapp .'/public/img/tel.png" />';} ?> <?php // echo $entree['type']; ?>

                            </div>

                            <div class="form-group pull-right">
                                <label for="date">Date:</label>
                                <label> <?php echo  date('d/m/Y H:i', strtotime($entree->reception)) ; ?></label>
                            </div><br>

                        <div class="form-group">
                        <label for="emetteur">Emetteur:</label>
                       <input id="emetteur" type="text" class="form-control" name="emetteur"  value="<?php echo $entree->emetteur ?>" />
                   </div>
                <div class="form-group">
                    <label for="sujet">Sujet :</label>
                    <input style="overflow:scroll;" id="sujet" type="text" class="form-control" name="sujet"  value="<?php echo  ($entree->sujet);?>"  />

                </div>
                <div class="form-group">
                    <label for="contenu">Contenu:</label>
                    <div class="form-control" style="overflow:scroll;min-height:400px">

                        <?php $contenu= $entree['contenu'];
                        echo ($contenu);  ?>
                    </div>

                    @if ($entree['nb_attach']  > 0)
                        <?php
                        // get attachements info from DB
                        $attachs = Attachement::get()->where('parent', '=', $entree['id'] );

                        ?>
                        @if (!empty($attachs) )
                            <?php $i=1; ?>
                            @foreach ($attachs as $att)
                                <div class="tab-pane fade in <?php  if ( ($entree['type']=='fax')&&($i==1)) {echo 'active';}?>" id="pj<?php echo $i; ?>">
                                    Pièce jointe N°: <?php echo $i; ?>
                                    <h4><b style="font-size: 13px;">{{ $att->nom }}</b> (<a style="font-size: 13px;" href="{{ URL::asset('storage'.$att->path) }}" download>Télécharger</a>)</h4>


                                    @switch($att->type)
                                    @case('docx')
                                    @case('doc')
                                    @case('dot')
                                    @case('dotx')
                                    @case('docm')
                                    @case('odt')
                                    @case('pot')
                                    @case('potm')
                                    @case('pps')
                                    @case('ppsm')
                                    @case('ppt')
                                    @case('pptm')
                                    @case('pptx')
                                    @case('ppsx')
                                    @case('odp')
                                    @case('xls')
                                    @case('xlsx')
                                    @case('xlsm')
                                    @case('xlsb')
                                    @case('ods')
                                    <iframe src="https://view.officeapps.live.com/op/view.aspx?src={{ URL::asset('storage'.$att->path) }}" frameborder="0" style="width:100%;min-height:640px;"></iframe>
                                    @break

                                    @case('pdf')
                                    <?php

                                    $fact=$att->facturation;
                                    if ($fact!='')
                                    {
                                        echo '<span class="pdfnotice"> Ce document contient le(s) mots important(s) suivant(s) : <b>'.$fact.'</b></span>';
                                    }

                                    ?>

                                    <iframe src="{{ URL::asset('storage'.$att->path) }}" frameborder="0" style="width:100%;min-height:640px;"></iframe>
                                    @break

                                    @case('jpg')
                                    @case('jpeg')
                                    @case('gif')
                                    @case('png')
                                    @case('bmp')
                                    <img src="{{ URL::asset('storage'.$att->path) }}" class="mx-auto d-block" style="max-width: 100%!important;">
                                    @break

                                    @default
                                    <span>Type de fichier non reconnu ... </span>
                                    @endswitch

                                </div>
                                <?php $i++; ?>
                            @endforeach

                        @endif

                    @endif
                </div>


                        </div>


                    <?php        }
                       ?>


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

    function show(elm) {
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