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
          <?php
              use \App\Http\Controllers\UsersController;
              use \App\Http\Controllers\ClientsController;
              $user = auth()->user();
              $iduser=$user->id;
              $seance =  DB::table('seance')
                  ->where('id','=', 1 )->first();
              $disp=$seance->dispatcheur ;
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
              // changer condition :
              // dossier par type supervision medic /..
              //+ Actif et non affectés

              if($iduser==$suptech)
              {

              }

              if($iduser==$supmedic)
	          {

              }

             $dossiers=    Dossier::where('current_status','actif')
          ->where(function ($query) {
               $query->whereNull('affecte')
               ->orWhere('affecte', 0);

              })->get();

              $Cdossiers=    Dossier::where('current_status','actif')
                  ->where(function ($query) {
                      $query->whereNull('affecte')
                          ->orWhere('affecte', 0);

                  })->count();

           /*   $dossiers=    Dossier::where('current_status','like','actif')
                 ->Where('affecte','<',1)->get();

              $Cdossiers=   Dossier::where('current_status','like','actif')
                  ->Where('affecte','<',1)->count();
*/


/*
              $dossiersI=    Dossier::where('current_status','like','inactif')
                  ->Where('affecte','<',1)->get();


              $CdossiersI=    Dossier::where('current_status','like','inactif')
                  ->Where('affecte','<',1)->count();
*/
              $dossiersI=    Dossier::where('current_status','inactif')
                  ->where(function ($query) {
                      $query->whereNull('affecte')
                          ->orWhere('affecte', 0);

                  })->get();

              $CdossiersI=    Dossier::where('current_status','inactif')
                  ->where(function ($query) {
                      $query->whereNull('affecte')
                          ->orWhere('affecte', 0);

                  })->count();

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
									if($user->id!=1){
										
                                  if($user->isOnline()) {
									  $c++; echo  '<div class="userdiv" id="user-'.$iduser.'" style="margin-bottom:30px;'.$bg.'"  >';
									  echo '<h3>'.$user->name.'  '.$user->lastname.' <small> ('.$role.')</small> </h3>';

                                      $folders = Dossier::where('affecte','=',$user->id)->get();
  foreach($folders as $folder)
              { $type=$folder['type_dossier'];if($type=='Mixte'){$style="background-color:#F39C12;";}if($type=='Medical'){$style="background-color:#52BE80";} if($type=='Technique'){$style="background-color:#3498DB;";}
              $statut=$folder['statut']; $idd=$folder['id'];$ref=$folder['reference_medic'];$abn=$folder['subscriber_lastname'].' '.$folder['subscriber_name'];$idclient=$folder['customer_id'];$client= $folder['reference_customer'] /*  ClientsController::ClientChampById('name',$idclient)*/ ;?>
              <div  id="dossier-<?php echo $idd;?>" class="dossier"  style="margin-top:5px;<?php echo $style; if($statut!=2){ echo';border:2px solid black';}?>" >
                    <label style="font-size: 18px;"><?php echo $ref ;?></label>
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

                <ul class="nav  nav-tabs" style="margin-top:10px;margin-bottom:10px">

                    <li class="nav-item active">
                        <a class="nav-link   active " href="#panelactifs" data-toggle="tab"  onclick="hideTab2();showTab1()"  >
                            <i class="fa-lg fas fa-folder-open"></i>  Dossiers Actifs (<?php echo  $Cdossiers ;?>)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#panelainactifs" data-toggle="tab" onclick="hideTab1();showTab2()"  >
                            <i class="fa-lg fas fa-folder"></i>  Dossiers Inactifs (<?php echo  $CdossiersI ;?>)
                        </a>
                    </li>
                </ul>

                <div id="panelactifs"   class="tab-pane fade  active in ">


                <div class="row">
                    <div class="col-sm-4"> <input style="width:200px;margin-top:10px;" class="search" type="text" id="myInput" onkeyup="Searchf()" placeholder="N° de Dossier.." title="Taper"></div>
                    <div class="col-sm-4"><input  style="width:200px;margin-top:10px;" class="search" type="text" id="myInput2" onkeyup="Searchf2()" placeholder="Assuré.." title="Taper"></div>
                    <div class="col-sm-4"> <input style="width:200px;margin-top:10px;" class="search" type="text" id="myInput3" onkeyup="Searchf3()" placeholder="Réf Client.." title="Taper"></div>
                </div>
                   <div class="panel-body scrollable-panel" style="display: block;min-height: 800px">
                       <div class="row" style="margin-bottom:15px;">
                           <div class="col-md-3" style="cursor:pointer;color:#F39C12" onclick="showMixtes()"><i class="fa fa-lg fa-folder"></i>  <b  onclick="showMixtes()">Dossier Mixte</b></div>
                           <div class="col-md-3" style="cursor:pointer;color:#52BE80" onclick="showMedic()" ><i class="fa fa-lg fa-folder"></i>  <b  onclick="showMedic()">Dossier Medical</b></div>
                           <div class="col-md-4" style="cursor:pointer;color:#3498DB" onclick="showTech()" ><i class="fa fa-lg fa-folder"></i>  <b  onclick="showTech()">Dossier Technique</b></div>
                           <div class="col-md-2" style="cursor:pointer;color:#000000;font-weight: 600" onclick="showTous()" >  <b  onclick="showTech()">TOUS</b></div>
                       </div>

 					<div id="drag-elements">

			<?php  $type='';$style='';
			if($Cdossiers >0)
			 {
             foreach($dossiers as $dossier)
			{ $type=$dossier['type_dossier'];if($type=='Mixte'){$style="background-color:#F39C12;";}if($type=='Medical'){$style="background-color:#52BE80";} if($type=='Technique'){$style="background-color:#3498DB;";}
			$idd=$dossier['id'];
            $immatricul=$dossier['vehicule_immatriculation']; $statut=$dossier['statut'];
			$ref=$dossier['reference_medic'];$abn=$dossier['subscriber_lastname'].' '.$dossier['subscriber_name'];$idclient=$dossier['customer_id'];$client=   ClientsController::ClientChampById('name',$idclient) ;?>
			      <div  id="dossier-<?php echo $idd;?>" class="dossier dossier-<?php echo $type;?>"  style="margin-top:5px;<?php echo $style; if($statut!=2){ echo';border:2px solid black;';} ?>" >
                <!--<i style="float:right;color:black;margin-left:5px;margin-right:5px;" class="fa fa-folder" ></i>--> <small style="font-size:11px"><?php custom_echo($abn,18);?></small>
	 	         <div class="infos">  <label style="font-size: 15px;"><?php echo $ref;?></label>
               <br><small style="font-size:10px"><?php echo custom_echo($client,18);?></small><br>
                  <?php if($immatricul!='') { echo '<small style="font-size:10px">'. $immatricul .'</small>';} ?>
			
                     </div>
	        </div>

	<?php	}
                }

		
		?>
					</div>


                  </div>

                </div><!--- Panel Inactifs --->


                <div id="panelainactifs"  class="tab-pane fade " >

                    <div class="row">
                        <div class="col-sm-4"> <input style="width:200px;margin-top:10px;" class="search" type="text" id="myInput" onkeyup="ISearchf()" placeholder="N° de Dossier.." title="Taper"></div>
                        <div class="col-sm-4"><input  style="width:200px;margin-top:10px;" class="search" type="text" id="myInput2" onkeyup="ISearchf2()" placeholder="Assuré.." title="Taper"></div>
                        <div class="col-sm-4"> <input style="width:200px;margin-top:10px;" class="search" type="text" id="myInput3" onkeyup="ISearchf3()" placeholder="Réf Client.." title="Taper"></div>
                    </div>
                    <div class="panel-body scrollable-panel" style="display: block;min-height: 800px">
                        <div class="row" style="margin-bottom:15px;">
                            <div class="col-md-3" style="cursor:pointer;color:#F39C12" onclick="IshowMixtes()"><i class="fa fa-lg fa-folder"></i>  <b  onclick="showMixtes()">Dossier Mixte</b></div>
                            <div class="col-md-3" style="cursor:pointer;color:#52BE80" onclick="IshowMedic()" ><i class="fa fa-lg fa-folder"></i>  <b  onclick="showMedic()">Dossier Medical</b></div>
                            <div class="col-md-4" style="cursor:pointer;color:#3498DB" onclick="IshowTech()" ><i class="fa fa-lg fa-folder"></i>  <b  onclick="showTech()">Dossier Technique</b></div>
                            <div class="col-md-2" style="cursor:pointer;color:#000000;font-weight: 600" onclick="IshowTous()" >  <b  onclick="IshowTech()">TOUS</b></div>
                        </div>

                        <div id="drag-elements2">

                            <?php  $type='';$style='';
                            if($CdossiersI >0)
                            {
                            foreach($dossiersI as $dossierI)
                            { $type=$dossierI['type_dossier'];if($type=='Mixte'){$style="background-color:#F39C12;";}if($type=='Medical'){$style="background-color:#52BE80";} if($type=='Technique'){$style="background-color:#3498DB;";}
                            $idd=$dossierI['id'];
                            $immatricul=$dossierI['vehicule_immatriculation'];
                            $ref=$dossierI['reference_medic'];$abn=$dossierI['subscriber_lastname'].' '.$dossierI['subscriber_name'];$idclient=$dossierI['customer_id'];$client=   ClientsController::ClientChampById('name',$idclient) ;?>
                            <div  id="dossier-<?php echo $idd;?>" class="dossier dossier-<?php echo $type;?>"  style="margin-top:5px;<?php echo $style;?>" > <small style="font-size:11px"><?php custom_echo($abn,18);?></small>
                                <!--<i style="float:right;color:black;margin-left:5px;margin-right:5px;" class="fa fa-folder" ></i>-->
                                <div class="infos"> <label style="font-size: 15px;"><?php echo $ref;?></label>
                                    <br><small style="font-size:10px"><?php echo custom_echo($client,18);?></small><br>
                                    <?php if($immatricul!='') { echo '<small style="font-size:10px">'. $immatricul .'</small>';} ?>

                                </div>
                            </div>

                            <?php	}
                            }


                            ?>
                        </div>


                    </div>

                </div><!--- Panel Actifs --->


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



    function hideTab1() {
        $('#panelactifs').css('display','none');
    }
    function hideTab2() {
        $('#panelainactifs').css('display','none');
    }

    function showTab1() {
        $('#panelactifs').css('display','block');
    }
    function showTab2() {
        $('#panelainactifs').css('display','block');
    }


    function   showMixtes  (){

        var elements = document.getElementsByClassName('dossier');
        for (var i = 0; i < elements.length; i++){
           if (elements[i].parentElement.id =='drag-elements')
           {
               elements[i].style.display = 'none';
           }
        }

        var elements = document.getElementsByClassName('dossier-Mixte');
        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = 'block';
        }
    }

    function   showMedic  (){

        var elements = document.getElementsByClassName('dossier');
        for (var i = 0; i < elements.length; i++){
            if (elements[i].parentElement.id =='drag-elements')
            {
                elements[i].style.display = 'none';
            }
        }

        var elements = document.getElementsByClassName('dossier-Medical');
        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = 'block';
        }
    }

    function   showTech  (){

        var elements = document.getElementsByClassName('dossier');
        for (var i = 0; i < elements.length; i++){
            if (elements[i].parentElement.id =='drag-elements')
            {
                elements[i].style.display = 'none';
            }
        }

        var elements = document.getElementsByClassName('dossier-Technique');
        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = 'block';
        }
    }

    function   showTous  (){

        var elements = document.getElementsByClassName('dossier');
        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = 'block';
        }


    }

    function selectFolder(elm)
    {
        var idelm=elm.id;
        var ref=idelm.slice(7);

        var dossier=document.getElementById('affdoss').value=ref;
    }


    function Searchf() {
        var input, filter, ul, li, label, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        ul = document.getElementById("drag-elements");
        li = ul.getElementsByClassName("dossier");
        for (i = 0; i < li.length; i++) {
            label = li[i].getElementsByTagName("label")[0];
            txtValue = label.textContent || label.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }


        }
    }
    function Searchf2() {
        var input, filter, ul, li, label, i, txtValue;
        input = document.getElementById("myInput2");
        filter = input.value.toUpperCase();
        ul = document.getElementById("drag-elements");
        li = ul.getElementsByClassName("dossier");
        for (i = 0; i < li.length; i++) {
            label = li[i].getElementsByTagName("small")[0];
            txtValue = label.textContent || label.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }


        }
    }

    function Searchf3() {
        var input, filter, ul, li, label, i, txtValue;
        input = document.getElementById("myInput3");
        filter = input.value.toUpperCase();
        ul = document.getElementById("drag-elements");
        li = ul.getElementsByClassName("dossier");
        for (i = 0; i < li.length; i++) {
            label = li[i].getElementsByTagName("small")[1];
            txtValue = label.textContent || label.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }


        }
    }


    /************     Inactifs   **************/


    function   IshowMixtes  (){

        var elements = document.getElementsByClassName('dossier');
        for (var i = 0; i < elements.length; i++){
            if (elements[i].parentElement.id =='drag-elements2')
            {
                elements[i].style.display = 'none';
            }
        }

        var elements = document.getElementsByClassName('dossier-Mixte');
        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = 'block';
        }
    }

    function   IshowMedic  (){

        var elements = document.getElementsByClassName('dossier');
        for (var i = 0; i < elements.length; i++){
            if (elements[i].parentElement.id =='drag-elements2')
            {
                elements[i].style.display = 'none';
            }
        }

        var elements = document.getElementsByClassName('dossier-Medical');
        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = 'block';
        }
    }

    function   IshowTech  (){

        var elements = document.getElementsByClassName('dossier');
        for (var i = 0; i < elements.length; i++){
            if (elements[i].parentElement.id =='drag-elements2')
            {
                elements[i].style.display = 'none';
            }
        }

        var elements = document.getElementsByClassName('dossier-Technique');
        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = 'block';
        }
    }

    function   IshowTous  (){

        var elements = document.getElementsByClassName('dossier');
        for (var i = 0; i < elements.length; i++){
            elements[i].style.display = 'block';
        }


    }


    function ISearchf() {
        var input, filter, ul, li, label, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        ul = document.getElementById("drag-elements2");
        li = ul.getElementsByClassName("dossier");
        for (i = 0; i < li.length; i++) {
            label = li[i].getElementsByTagName("label")[0];
            txtValue = label.textContent || label.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }


        }
    }
    function ISearchf2() {
        var input, filter, ul, li, label, i, txtValue;
        input = document.getElementById("myInput2");
        filter = input.value.toUpperCase();
        ul = document.getElementById("drag-elements2");
        li = ul.getElementsByClassName("dossier");
        for (i = 0; i < li.length; i++) {
            label = li[i].getElementsByTagName("small")[0];
            txtValue = label.textContent || label.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }


        }
    }

    function ISearchf3() {
        var input, filter, ul, li, label, i, txtValue;
        input = document.getElementById("myInput3");
        filter = input.value.toUpperCase();
        ul = document.getElementById("drag-elements2");
        li = ul.getElementsByClassName("dossier");
        for (i = 0; i < li.length; i++) {
            label = li[i].getElementsByTagName("small")[1];
            txtValue = label.textContent || label.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }


        }
    }
</script>
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
         document.getElementById('drag-elements'),
         document.getElementById('drag-elements2')


     ], {
         revertOnSpill: true
     }).on('drop', function (el, target, source) {
          console.log('target: '+target.id);
          console.log(' element: '+el.id);
          if(target.id=='drag-elements' || target.id=='drag-elements2' ){

              var dossdiv=el.id;var statut='actif';

              var dossier=dossdiv.slice( 8);
              if(target.id=='drag-elements2'){statut='inactif';}

              console.log('DOSSIER : '+dossier);
              var _token = $('input[name="_token"]').val();

              // attendre 5 secondes pour confirmer l'attribution
              setTimeout(function(){
                  $.ajax({
                      url: "{{ route('dossiers.attribution') }}",
                      method: "POST",
                      data: {  dossierid:dossier ,statut:statut,agent:0, _token: _token},
                      success: function ( ) {
                          $('#dossier-'+dossier).animate({
                              opacity: '0.1',
                          });
                          $('#dossier-'+dossier).animate({
                              opacity: '1',
                          });

                      }
                  });

              }, 5000);

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

    .userdiv   {border:2px dotted grey; padding:5px 5px 5px;opactity:0.1;height:1300px;}
    .userdiv .dossier label{font-size:18px;}
    .userdiv .dossier .infos small{display:none;}
    #drag-elements .dossier .infos{display:block;}
    #drag-elements2 .dossier .infos{display:block;}

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
 

#drag-elements , #drag-elements2 {
  display: block;
 /* background-color: #dfdfdf;*/
 border:1px dashed #728D96;
 border-radius: 5px;
  min-height: 700px;
  margin: 0 auto;
    padding: 20px;

}

#drag-elements > div ,#drag-elements2 > div {
  text-align: center;
  float: left;
  padding: 1em;
  margin: 0 1em 1em 0;
  box-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);
  /*border-radius: 100px;*/
  border: 2px solid #ececec;
   transition: all .5s ease;
}

#drag-elements > div:active ,#drag-elements2 > div:active {
  -webkit-animation: wiggle 0.3s 0s infinite ease-in;
  animation: wiggle 0.3s 0s infinite ease-in;
  opacity: .6;
  border: 2px solid #000;
}

#drag-elements > div:hover ,#drag-elements2 > div:hover  {
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
