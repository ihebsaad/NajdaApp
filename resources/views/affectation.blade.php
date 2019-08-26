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
 
            <div class="panel panel-primary column col-md-6"  style="margin-left:30px;margin-right:50px;padding:0;min-height: 800px" >
              <div class="panel-heading">
                 <h4 id="kbspaneltitle" class="panel-title">Agents connectés  </h4>
              
              </div>
        				
		  <div class="panel-body scrollable-panel" style="display: block;min-height:1000px;padding:15px 15px 15px 15px">
              <ul id="tabs" class="nav  nav-tabs"  >
                  <li class=" nav-item ">
                      <a class="nav-link active   " href="{{ route('supervision') }}"  >
                          <i class="fas fa-lg  fa-users-cog"></i>  Supervision
                      </a>
                  </li>
                  <li class="nav-item active">
                      <a class="nav-link" href="{{ route('affectation') }}"  >
                          <i class="fas fa-lg  fa-user-tag"></i>  Affectations
                      </a>
                  </li>

              </ul>
          <?php
              use \App\Http\Controllers\UsersController;
              use \App\Http\Controllers\ClientsController;
    $user = auth()->user();
              $seance =  DB::table('seance')
                  ->where('id','=', 1 )->first();
              $disp=$seance->dispatcheur ;
              $sup=$seance->superviseur ;
              $supmedic=$seance->superviseurmedic ;
              $suptech=$seance->superviseurtech ;
              $charge=$seance->chargetransport ;
              $disptel=$seance->dispatcheurtel ;
              $veilleur=$seance->veilleur ;


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

	use \App\Dossier;
	/*
 $dossiers = Dossier:://where('affecte','=','0')
              whereNull('affecte')
              ->orWhere('affecte',0)
      //->where('affecte','<', 1)  ajouter conditions non affectes
              ->get();
*/
              $dossiers=  Dossier::
                  whereNull('affecte')
                  ->orWhere('affecte',0)->get();
 ?>

  
  				                             <?php $c=0;
                            foreach($users as $user)
                                { if($c % 2 ==0){$bg=' border:2px dotted black ;';}else{$bg='';}
								$iduser=$user->id;
                                     $role='Agent';
                                    if($user->id==$charge){$role='Chargé de transport';}
                                    if($user->id==$disp){$role='Dispatcheur';}
                                    if($user->id==$disptel){$role='Dispatcheur Téléphonique';}
                                    if($user->id==$supmedic){$role='Superviseur Médical';}
                                    if($user->id==$suptech){$role='Superviseur Technique';}
                                    if($user->id==$veilleur){$role='Veilleur de nuit';}
									/*
									$missions=UsersController::countmissions($user->id);
									$actions=UsersController::countactions($user->id);
									$actives=UsersController::countactionsactives($user->id);
                                    $dossiers=UsersController::countaffectes($user->id);
                                    $notifications=UsersController::countnotifs($user->id);
                                    // if($user->type=='admin'){$role='(Administrateur)';}*/
									if($user->user_type!='admin'){
										
                                  if($user->isOnline()) {
									  $c++; echo  '<div class="userdiv" id="user-'.$iduser.'" style="margin-bottom:30px;'.$bg.'"  >';
									  echo '<h3>'.$user->name.'  '.$user->lastname.' <small> ('.$role.')</small> </h3>';

                                      $folders = Dossier::where('affecte','=',$user->id)->get();
  foreach($folders as $folder)
              { $type=$folder['type_dossier'];if($type=='Mixte'){$style="background-color:#F39C12;";}if($type=='Medical'){$style="background-color:#52BE80";} if($type=='Technique'){$style="background-color:#3498DB;";}
              $idd=$folder['id'];$ref=$folder['reference_medic'];$abn=$folder['subscriber_lastname'].' '.$folder['subscriber_name'];$idclient=$folder['customer_id'];$client=   ClientsController::ClientChampById('name',$idclient) ;?>
              <div  id="dossier-<?php echo $idd;?>" class="dossier"  style="margin-top:5px;<?php echo $style;?>" >
                    <label style="font-size: 18px;"><?php echo $ref;?></label>
                  <div class="infos">  <small style="font-size:11px"><?php custom_echo($abn,18);?></small>
                      <br><small style="font-size:10px"><?php echo custom_echo($client,18);?></small>

                      <i style="float:left;color:;margin-top:10px" class="delete fa fa-trash" onclick="Delete('<?php echo $idd;?>')"></i></div>
              </div>
              <?php	}

                                      echo '</div>' ;}
									}
                                }
                                    ?>
  
  
			</div>
			</div><!--panel 1-->
			
			<div class="panel panel-danger col-md-5" style="padding:0 ;min-height: 800px ">
                    <div class="panel-heading">
                        <h4 class="panel-title">Dossiers Non Affectés</h4>
         
                    </div>

                   <div class="panel-body scrollable-panel" style="display: block;min-height: 800px">
                       <div class="row" style="margin-bottom:15px;">
                           <div class="col-md-4" style=";color:#F39C12"><i class="fa fa-lg fa-folder"></i>  <b>Dossier Mixte</b></div>
                           <div class="col-md-4" style=";color:#52BE80"><i class="fa fa-lg fa-folder"></i>  <b>Dossier Medical</b></div>
                           <div class="col-md-4" style=";color:#3498DB"><i class="fa fa-lg fa-folder"></i>  <b>Dossier Technique</b></div>
                       </div>

 					<div id="drag-elements">
					
			<?php  $type='';$style='';
                        foreach($dossiers as $dossier)
			{ $type=$dossier['type_dossier'];if($type=='Mixte'){$style="background-color:#F39C12;";}if($type=='Medical'){$style="background-color:#52BE80";} if($type=='Technique'){$style="background-color:#3498DB;";}
			$idd=$dossier['id'];$ref=$dossier['reference_medic'];$abn=$dossier['subscriber_lastname'].' '.$dossier['subscriber_name'];$idclient=$dossier['customer_id'];$client=   ClientsController::ClientChampById('name',$idclient) ;?>
			     <div  id="dossier-<?php echo $idd;?>" class="dossier"  style="margin-top:5px;<?php echo $style;?>" >
                <!--<i style="float:right;color:black;margin-left:5px;margin-right:5px;" class="fa fa-folder" ></i>--> <label style="font-size: 15px;"><?php echo $ref;?></label>
	 	         <div class="infos">  <small style="font-size:11px"><?php custom_echo($abn,18);?></small>
               <br><small style="font-size:10px"><?php echo custom_echo($client,18);?></small>
			
                     <i style="float:left;color:;margin-top:10px" class="delete fa fa-trash" onclick="Delete('<?php echo $idd;?>')"></i></div>
	 </div>
	<?php	}
		
		?>
					</div>


                  </div>
 
            </div><!--panel 2-->
			
			
            <!-- /.content -->
        </div>

  </div><!-- /main -->
	<style>
 
        </style>
     @endsection
	 


<script src='https://bevacqua.github.io/dragula/dist/dragula.js'></script>

<!--

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
-->


 
<script>
 function Delete(ID)
 {
 	 document.getElementById('dossier-'+ID).style.display = "none";}
 
 
/*
 function $(id) {
     return document.getElementById(id);
 }
*/
 window.onload = function() {

     dragula([
         <?php
    foreach($users as $user)
          {
               if($user->isOnline()) {
                   echo "document.getElementById('user-".$user->id."'),";
               }
          }

         ?>
         document.getElementById('drag-elements')


     ], {
         revertOnSpill: true
     }).on('drop', function (el, target, source) {
          console.log('target: '+target.id);
          console.log(' element: '+el.id);
          if(target.id=='drag-elements'){

              var dossdiv=el.id;

              var dossier=dossdiv.slice( 8);

              console.log('DOSSIER : '+dossier);
              var _token = $('input[name="_token"]').val();

              $.ajax({
                  url: "{{ route('dossiers.attribution') }}",
                  method: "POST",
                  data: {  dossierid:dossier ,agent:null, _token: _token},
                  success: function ( ) {
                      $('#dossier-'+dossier).animate({
                          opacity: '0.3',
                      });
                      $('#dossier-'+dossier).animate({
                          opacity: '1',
                      });

                  }
              });
          }else {
              var userdiv=target.id;
              var dossdiv=el.id;
              var user=userdiv.slice( 5);
              var dossier=dossdiv.slice( 8);
              console.log('USER : '+user);
              console.log('DOSSIER : '+dossier);
              var _token = $('input[name="_token"]').val();

              $.ajax({
                  url: "{{ route('dossiers.attribution') }}",
                  method: "POST",
                  data: {  dossierid:dossier ,agent:user, _token: _token},
                  success: function ( ) {
                      $('#dossier-'+dossier).animate({
                          opacity: '0.3',
                      });
                      $('#dossier-'+dossier).animate({
                          opacity: '1',
                      });

                  }
              });
          }


     }).on('drag', function (el, source) {

         console.log('  element dragged  : ' + el.id);
         console.log(' source Drag :' + source.id);

         var force = el.id;
         var type = source.id;

// deleting element with ajax .........
         if (source.id != 'drag-elements') {


             console.log('deleting element from ' + source.id);


         }


     })
         .on('cancel', function (el, target, source) {
             console.log('element cancalled   .... !');
             var force = el.id;
             var type = target.id;
             if (target.id != 'drag-elements') {
// adding the element with ajax .....


             }

         });

 }
</script>





 
<style>
    .userdiv h3{margin-top:2px!important;}
    .userdiv .delete {display:none;}

    .userdiv   {border:2px dotted grey; padding:5px 5px 5px;opactity:0.1;height:400px;}
    .userdiv .dossier label{font-size:18px;}
    .userdiv .dossier .infos{display:none;}
    #drag-elements .dossier .infos{display:block;}

    .help{color:skyblue;}
.tooltip-inner{
font-size:20px;
width:300px;
}



.dossier{cursor:pointer;
     width: 150px;
    height: 105px;
    margin-top: 50px;
    position: relative;
   /* background-color: #708090;*/
  /*  background-color:#52BE80  #af8e33 !important;*/color:white;font-weight:600;
    border-radius: 0 6px 6px 6px;
    box-shadow: 4px 4px 7px rgba(0, 0, 0, 0.59);
  /*  overflow:hidden;*/
    white-space:nowrap;
    text-overflow: ellipsis;
    padding:5px 5px 5px 5px!important;
}

.dossier:before {
    content: '';
    width: 50%;
    height: 12px;
    border-radius: 0 20px 0 0;
    background-color: #708090;
    opacity:0.5;
    position: absolute;
    top: -12px;
    left: 0px;
}
 

#drag-elements {
  display: block;
 /* background-color: #dfdfdf;*/
 border:1px dashed #728D96;
 border-radius: 5px;
  min-height: 700px;
  margin: 0 auto;
    padding: 20px;

}

#drag-elements > div {
  text-align: center;
  float: left;
  padding: 1em;
  margin: 0 1em 1em 0;
  box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);
  /*border-radius: 100px;*/
  border: 2px solid #ececec;
   transition: all .5s ease;
}

#drag-elements > div:active {
  -webkit-animation: wiggle 0.3s 0s infinite ease-in;
  animation: wiggle 0.3s 0s infinite ease-in;
  opacity: .6;
  border: 2px solid #000;
}

#drag-elements > div:hover {
  border: 2px solid gray;
  background-color: #e5e5e5;
}

.userdiv    {
 /* border: 2px dashed #D9D9D9;*/
  border-radius: 5px;
  min-height: 50px;
  margin: 0 auto;
  margin-top: 10px;
  display: block;
  text-align: center;
}

.userdiv > div    {
  transition: all .5s;
  text-align: center;
  float: left;
  padding: 1em;
  margin: 0 1em 1em 0;
  box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);
  border-radius: 5px;
  border: 2px solid skyblue;
  background: #FFFFFF;
    height: 50px;
    width: 120px;
  transition: all .5s ease;
}

 .userdiv > div:active     {

  -webkit-animation: wiggle 0.3s 0s infinite ease-in;
  animation: wiggle 0.3s 0s infinite ease-in;
  opacity: .6;
  border: 2px solid #000;
}

@-webkit-keyframes wiggle {
  0% {
    -webkit-transform: rotate(0deg);
  }
  25% {
    -webkit-transform: rotate(2deg);
  }
  75% {
    -webkit-transform: rotate(-2deg);
  }
  100% {
    -webkit-transform: rotate(0deg);
  }
}

@keyframes wiggle {
  0% {
    transform: rotate(-2deg);
  }
  25% {
    transform: rotate(2deg);
  }
  75% {
    transform: rotate(-2deg);
  }
  100% {
    transform: rotate(0deg);
  }
}

.gu-mirror {
  position: fixed !important;
  margin: 0 !important;
  z-index: 9999 !important;
  padding: 1em;
}
.gu-hide {
  display: none !important;
}
.gu-unselectable {
  -webkit-user-select: none !important;
  -moz-user-select: none !important;
  -ms-user-select: none !important;
  user-select: none !important;
}
.gu-transit {
  opacity: 0.5;
  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=50)";
  filter: alpha(opacity=50);
}
.gu-mirror {
  opacity: 0.5;
  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=50)";
  filter: alpha(opacity=50);
}




    @media (min-width: 1280px) {

        .userdiv .dossier label{font-size:13px!important;}
        .userdiv .dossier {width:100px;height:40px;}
    }



    /**  small **/
    @media (min-width: 768px) and (max-width: 980px) {

        .userdiv .dossier label{font-size:13px!important;}
        .userdiv .dossier {width:100px;height:40px;}
    }

    /***/
    @media (min-width: 480px) and (max-width: 767px) {

        .userdiv .dossier label{font-size:13px!important;}
        .userdiv .dossier {width:100px;height:40px;}

    }


</style>
