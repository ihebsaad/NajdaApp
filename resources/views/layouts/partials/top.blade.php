
<link href="{{ asset('public/js/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>
<header class="header">
  <script src="{{ asset('public/najda_app/najdaapp/webphone/webphone_api.js') }}"></script>
<link href="{{ asset('public/js/select2/css/select2.css') }}" rel="stylesheet" type="text/css"/>
<?php
    use App\Entree;
    use App\User;
use App\Dossier;
     $listedossiers = Dossier::where('current_status','<>','Cloture')->orderby('id','desc')
             ->get();
    $seance =  DB::table('seance')
        ->where('id','=', 1 )->first();
    $user = auth()->user();
    $user_type = $user->user_type;
    $iduser=$user->id;

     User::where('id', $iduser)->update(array('statut'=>'1'));

    ?>
     <?php

         $CurrentUser = auth()->user();
         $iduser=$CurrentUser->id;

if($iduser===32)
{
?>
                        <input id="extensiontel" name="extensiontel" type="hidden" value="2000">
                        <input id="motdepassetel" name="motdepassetel" type="hidden" value="3862oOPD3F">
<?php
}
else if($iduser===37)
{
?>
 <input id="extensiontel" name="extensiontel" type="hidden" value="2001">
                        <input id="motdepassetel" name="motdepassetel" type="hidden" value="z6Hm&FqQF2G@S3">
<?php
}
else 
{
?>
<input id="extensiontel" name="extensiontel" type="hidden" value="2002">
                        <input id="motdepassetel" name="motdepassetel" type="hidden" value="najda2020!-A">
<?php
}
?>
        <div class="collapse bg-grey" id="navbarHeader">
          <input id="natureappel" name="natureappel" type="hidden" value="" />
          <input id="natureappelrecu" name="natureappelrecu" type="hidden" value="" />   
            <input id="iduser" name="iduser" type="hidden" value="{{$iduser}}" />                       
             @include('layouts.partials._top_menu')
        </div>
    <div class="navbar ">
      <div class="row">
        <div class="col-sm-1 col-md-1 col-lg-1" style="margin-right:60px">
           <a href="{{ route('home')}}" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                <img style="margin-left:-60px;" src="{{  URL::asset('public/img/logo.png') }}" alt="logo" />
            </a>
        </div>
          <div style="min-width:100px!important;padding-top:15px;padding-left:0px!important" class="col-sm-1 col-md-1 col-lg-1">
              <p id="njour" style="font-size: 25px; margin-bottom: 0px!important;color: white"></p>
              <div  >


                  <?php
                  // Récupère l'heure du serveur

                  $localtime = localtime();

                  $seconde =  $localtime[0];
                  $minute =  $localtime[1];
                  $heure =  $localtime[2];

                  ?>
                  <script>
                      bcle=0;

                      function clock()
                      {
                          if (bcle == 0)
                          {
                              heure = <?php echo $heure ?>;
                              min = <?php echo $minute ?>;
                              sec = <?php echo $seconde ?>;
                          }
                          else
                          {
                              sec ++;
                              if (sec == 60)
                              {
                                  sec=0;
                                  min++;
                                  if (min == 60)
                                  {
                                      min=0;
                                      heure++;
                                  };
                              };
                          };
                          txt="";
                          if(heure < 10)
                          {
                              txt += "0";
                          }
                          txt += heure+ ":";
                          if(min < 10)
                          {
                              txt += "0"
                          }
                          txt += min ;//+ ":";
                         /* if(sec < 10)
                          {
                              txt += "0"
                          }
                          txt += sec ;*/
                          timer = setTimeout("clock()",1000);
                          bcle ++;
                          document.clock.date.value = txt ;
                      }
                  </script>

<body onload="clock();">
                  <style type="text/css">
                      form{
                          display:inline;
                      }
                      .style {border-width: 0;background-color:transparent;color: #f3fdfd;font-weight:bold;font-size:20px;margin-left:10px;}
                  </style>

                  <form name="clock" onSubmit="0"  >
                      <input type="text" name="date" size="5" readonly="true" class="style">
                  </form>
</body>
              </div>
          </div>
        <div class="col-sm-1 col-md-1 col-lg-1" style="padding-top: 5px;">
          <span  id="ndate" class="date" data-month="" data-year="" style="width:70px;height:60px;line-height: 1; padding-top: 15px;">
                    <span id="numj">13</span>
           </span>
        </div>

      @can('isAdmin')

          <div  class="col-sm-1 col-md-1 col-lg-1 " style="padding-top:10px;padding-left:0px!important">
          <a href="{{ route('parametres') }}" class="btn btn-default btn-lg btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Demander pause de ton superviseur" style="margin-bottom: 28px!important;">
              <i class="fas fa-user-tie"></i>
              <br>
          Admin
          </a> 
        </div>
          @endcan


          @cannot('isAdmin')
              <?php
              $statut=$user->statut;
              if( ($seance->superviseurmedic!=$iduser)  && ($seance->superviseurtech!=$iduser))
              {
              if($statut==2) { ?>
              <div  id="pause" class="col-sm-1 col-md-1 col-lg-1 " style="padding-top:10px;padding-left:0px!important">


                  <a href="#"    id="enpause" class="btn btn-danger btn-lg btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Demander pause de ton superviseur" style="margin-bottom: 28px!important;">
                      <span class="fas fa-mug-hot"></span>
                      <br>
                      En Pause
                  </a>

                  <a href="#"  style="display:none" id="dpause" class="btn btn-default btn-lg btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Demander pause de ton superviseur" style="margin-bottom: 28px!important;">
                      <span class="fa fa-fw fa-pause"></span>
                      <br>
                      Pause
                  </a>
              </div>

                  <?php }else{  ?>
                  <div  id="pause" class="col-sm-1 col-md-1 col-lg-1 " style="padding-top:10px;padding-left:0px!important">

                  <a href="#"  id="dpause" class="btn btn-default btn-lg btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Demander pause de ton superviseur" style="margin-bottom: 28px!important;">
                      <span class="fa fa-fw fa-pause"></span>
                      <br>
                      Pause
                  </a>

                  <a href="#"    style="display:none"  id="enpause" class="btn btn-danger btn-lg btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Demander pause de ton superviseur" style="margin-bottom: 28px!important;">
                      <span class="fas fa-mug-hot"></span>
                      <br>
                      En Pause
                  </a>

                  </div>

                  <?php }
                  } // sup
                  ?>
          @endcannot

          <?php
          if( ($seance->superviseurmedic==$iduser)  || ($seance->superviseurtech==$iduser) ||($user->user_type=='admin'))
          { ?>
          <div class="col-sm-1 col-md-1 col-lg-1" style="padding-top:10px;margin-left:15px">
              <a  href="{{ route('supervision') }}" class="btn btn-default btn-lg btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Demander pause de ton superviseur" style="margin-bottom: 28px!important;">
                  <span class="fas fa-fw fa-users-cog"></span>
                  <br>
                  Superv
              </a>
          </div>
        <?php } ?>

          <div class="col-sm-1 col-md-1 col-lg-2" style=" height: 40px!important;padding-top:27px;padding-left:0px ">
          <form class="search-container" action="{{route('RechercheMulti.test')}}" id="testRecheche" method="POST">
            <input type="text" id="search-bar"  placeholder="Recherche" autocomplete="off" name="qy">
            <a href="#" onclick='document.getElementById("testRecheche").submit()'><img class="search-icon" src="{{ URL::asset('public/img/search-icon.png') }}"></a>
                 <!--<div id="countryList">
                </div>-->

                <div id="kkk" class="dropdown" style="top:0px; left:-50px; "></div>

           {{ csrf_field() }}
          </form>
        </div>
        <div class="col-sm-1 col-md-1 col-lg-1" style="padding-top:10px;">
          <a  data-toggle="modal" data-target="#faireappel1" id="phonebtn" href="#" class="btn btn-primary btn-lg btn-responsive phone" role="button"  data-placement="bottom" data-original-title="Lancer / Recevoir des appels téléphoniques" style="margin-left:-5px;margin-bottom: 28px!important;padding-top: 15px;padding-bottom: 15px; ">
              <span class="fa fa-fw fa-phone fa-2x"></span>
          </a> 
        </div>

		 <?php

        $disp=$seance->dispatcheur ;

        $iduser=Auth::id();
        if($iduser==$disp){$icon='fa-map-signs';}else{$icon='fa-envelope';}

          $count=Entree::where('statut','<','2')
              ->where('dossier','=','')
			  ->where('destinataire','<>','finances@najda-assistances.com')
			  ->count();
            if($count==0){$color='btn-success';}
            else{$color='btn-danger';
            }
          ?>
          <div class="col-sm-1 col-md-1 col-lg-1" style="padding-top:10px;">
           <?php  if( $user_type=='financier' || $user_type=='bureau') {  ?>
              <a href="{{ route('entrees.finances') }}" class="btn btn-danger btn-lg btn-responsive boite" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Boîte Finances" style="margin-bottom: 28px!important;padding-top: 15px;padding-bottom: 15px;margin-left:10px">
                  <span class="fa fa-fw fa-dollar fa-2x"></span>
              </a>
               <?php    }  else{   ?>
          <a href="{{ route('entrees.dispatching') }}" class="btn <?php echo $color; ?> btn-lg btn-responsive boite" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Boîte d'emails" style="margin-bottom: 28px!important;padding-top: 15px;padding-bottom: 15px;">
              <span class="  fa-fw fas <?php echo $icon ; ?> fa-2x"></span><?php  if($count > 0 ){ ?><span id="countnotific" class="label label-warning" style="color:black"><?php echo $count;?></span><?php } else{ ?><span id="countnotific" class="label " style="color:black"><?php echo $count;?></span> <?php } ?>
          </a>

               <?php    }    ?>
          </div>



        <div class="col-sm-1 col-md-1 col-lg-1">
          <ul class="nav navbar-nav" style=" ">
                    {{-- User Account --}}
                    @include('layouts.partials._user_menu')

                </ul>

        </div>

          @cannot('isAdmin')

          <div class="col-sm-1 col-md-1 col-lg-1" class="overme">
              <?php
              $user = auth()->user();
              $name=$user->name;
              $lastname=$user->lastname;

              ?>

              <b style="font-size: 12px;color:white;font-weight:600">   <?php echo $name .' '. $lastname; ?></b>

              <?php $disp=$seance->dispatcheur ;
              $supmedic=$seance->superviseurmedic ;
              $suptech=$seance->superviseurtech ;
              $charge=$seance->chargetransport ;
              $disptel=$seance->dispatcheurtel ;
              $disptel2=$seance->dispatcheurtel2 ;
              $disptel3=$seance->dispatcheurtel3 ;
              ?>
               <div class=" overme" style="font-size:12px;color:white;text-align:left">
                  <?php
                  $iduser=Auth::id();
                  if ($iduser==$disp) { ?>
                  <span>Dispatcheur</span><br>
                  <?php }    if ($iduser==$supmedic) { ?>
                  <span>Sup Medical</span><br>
                  <?php }
                  if ($iduser==$suptech) { ?>
                      <span>Sup Technique</span><br>
                  <?php }    if ($iduser==$charge) { ?>
                  <span>Chargé Trans</span><br>
                  <?php }    if ($iduser==$disptel) { ?>
                  <span>Disp Tél 1</span><br>
                  <?php }    if ($iduser==$disptel2) { ?>
                  <span>Disp Tél 2</span><br>
                  <?php }    if ($iduser==$disptel3) { ?>
                  <span>Disp Tél 3</span><br>
                  <?php } ?>
               </div>

          </div>
        @endcannot
          <div class="col-sm-1 col-md-1 col-lg-1" class="navbar-toggler" data-toggle="collapse" data-target="#navbarHeader">
         <!-- <img class="menu-trigger" src="{{ URL::asset('resources/assets/img/menu-black.png') }}" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation"/>-->
          <div class="menu-icon menu-trigger" class="navbar-toggler" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation" alt="Menu de l'application"  style="zoom:60%;float: right!important; padding-top: 28px">
            <div class="line-1 no-animation"></div>
            <div class="line-2 no-animation"></div>
            <div class="line-3 no-animation"></div>
          </div>


        </div>


      </div><!--------------- row -->
    </div>

    </header>

<div class="modal  " id="modalconfirm" >
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="text-align:center"  id="modalalert0"><center>Demande de Pause  </center> </h5>

            </div>
            <div class="modal-body">
                <div class="card-body">

                    <div style="text-align:center" class="row" >
                        <div style="text-align:center" class="     show" role="alert">

                            <h3 style="font-weight:bold;"> <center> Vous voulez demander une pause ?</center></h3>
                            <div class="row"><label>Durée: </label>    <center><input type="number" step="1" value="15" class="form-control" style="width:60px;margin:10px 10px 10px 10px" name="duree" id="dureep" /> Minutes</center>
                            </div>
                        </div>
                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <a id="oui"   class="btn btn  "   style="background-color:#5D9CEC; width:100px;color:#ffffff"   >Oui</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width:100px">Non</button>
            </div>
        </div>
    </div>
</div>
 <!--Modal Tel-->

    <div class="modal fade" style="z-index:10000!important;left: 20px;" id="numatransfer"    role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
        <div class="modal-dialog" role="numatransfer">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal2">Saisir le numéro</h5>

                </div>
                <div class="modal-body">
                    <div class="card-body" sytle="height:300px">

                        <div class="form-group">
                            {{ csrf_field() }}

                            <form id="numatransfer" novalidate="novalidate">

                                <input id="numatrans" name="numatrans" type="text" value="" />
                                   
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
<?php

?>

                    <button type="button"  class="btn btn-primary"  onclick="transfer();">Transférer</button>
   
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>

                </div>
            </div>

        </div>

    </div>
<!--Modal Tel 2-->

    <div class="modal fade" id="appelinterfacerecep"    role="dialog" aria-labelledby="basicModal" aria-hidden="true" data-backdrop="static"  data-keyboard="false">
 <div class="modal-dialog modal-lg" role="telrecep"  sytle="width:200px;height:30px">
            <div class="modal-content">
                <div class="modal-header">
                  <h3 class="modal-title" style="text-align:center"  id=" "><center>Recevoir un appel </center> </h3>
</div>
                <div class="modal-body">
                    <div class="card-body" >


                        <div class="form-group">
                            {{ csrf_field() }}

                            <form id="appelinterfacerecep" novalidate="novalidate">
  <div id="call_duration">&nbsp;</div>
            <div style="font-size: 30px;">

<label style="color:green;font-size: 30px;"id="status_call"></label>


<label style="margin-left:180px;font-size: 30px;"id="minutes1"></label>
<label style="font-size:30px;" id="seconds1"></label>

</div>
<input id="nomencoursrecep" name="nomencours" type="text" readonly value="" style="font-size: 30px;border: none;">
 <div>
<input id="numencoursrecep" name="numencours" type="text" readonly value="" style="font-size: 30px;border: none;">
</div>
                            </form>

                        </div>
                    </div>

                </div>

                <div class="modal-footer">


<button id="repondre" type="button"  class="btn btn-primary"  onclick="accept();"><i class="fas fa-phone-volume"></i> Répondre</button>              
 <button id="racc2" type="button"  class="btn btn-primary"  onclick="Hangup();"><i class="fas fa-phone-slash"></i> Raccrocher </button>
 <div id="mettreenattente" style="display :none;"><button type="button"  class="btn btn-primary" onclick="hold(true);" ><i class="fas fa-pause"></i> Mettre en attente</button></div>
 <div id="reprendreappel" style="display :none;"><button type="button"  class="btn btn-primary"  onclick="hold(false);"><i class="fas fa-phone"></i> Reprendre</button></div>
 <div id="couperson" style="display :none;"><button type="button"  class="btn btn-primary" onclick="mute(true,0);" ><i class="fas fa-microphone-slash"></i> Couper le son</button></div>
 <div id="reactiveson" style="display :none;"><button type="button"  class="btn btn-primary"  onclick="mute(false,0);"><i class="fas fa-microphone"></i> Réactiver son</button></div>
 <button id="transferapp" type="button"  style="display :none;" class="btn btn-primary" data-toggle="modal" data-target="#numatransfer"><i class="fas fa-reply-all"></i> Transférer</button>
<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
              <!--<button type="button"  class="btn btn-primary"  onclick="transfer();">Transférer</button>    
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>!-->

                </div>
            </div>

        </div>

    </div>
<!--Modal Tel-->

    <div class="modal fade" id="faireappel1"    role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
        <div class="modal-dialog" role="tel">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal2">Saisir le numéro</h5>

                </div>
                <div class="modal-body">
                    <div class="card-body" sytle="height:300px">


                        <div class="form-group">
                            {{ csrf_field() }}

                            <form id="faireappel1" novalidate="novalidate">

                               
                                     <label for="numtel1">Numéro</label>
 <input id="numtel1" name="numtel1" type="text"  value="" ></input>
                            </form>

                        </div>
                    </div>

                </div>

                <div class="modal-footer">
 <button type="button"  class="btn btn-primary"  onclick="ButtonOnclick2();">Appeler</button>
 <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>

                </div>
            </div>

        </div>

    </div>

<!--Modal Tel-->

    <div class="modal fade" style="z-index:10000!important;left: 20px;" id="numatransfer1"    role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
        <div class="modal-dialog" role="numatransfer2">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal2">Saisir le numéro</h5>

                </div>
                <div class="modal-body">
                    <div class="card-body" sytle="height:300px">

                        <div class="form-group">
                            {{ csrf_field() }}

                            <form id="numatransfer2" novalidate="novalidate">

                                <input id="numatrans2" name="numatrans2" type="text" value="" />
                                   
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
<?php

?>

                    <button type="button"  class="btn btn-primary"  onclick="transfer2();">Transférer</button>
   
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>

                </div>
            </div>

        </div>

    </div>
<!--Modal Tel 2-->

    <div class="modal fade" id="appelinterfaceenvoi2"    role="dialog" aria-labelledby="exampleModal2" aria-hidden="true" data-backdrop="static"  data-keyboard="false" >
        <div class="modal-dialog" role="telenvoi"  sytle="width:20px;height:10px">
            <div class="modal-content">
                <div class="modal-header">
                  <h3 class="modal-title" style="text-align:center"  id=" "><center>Passer un appel </center> </h3>
</div>
                <div class="modal-body">
                    <div class="card-body" >


                        <div class="form-group">
                            {{ csrf_field() }}

                            <form id="appelinterfaceenvoi2" novalidate="novalidate">
  
            <div style="font-size: 30px;">

<label style="color:green;font-size: 30px;"id="status_callenv2"></label>


<label style="margin-left:150px;font-size: 30px;"id="minutes"></label>
<label style="font-size:30px;" id="seconds"></label>

</div>
<input id="nomencours2" name="nomencours" type="text" readonly value="" style="font-size: 30px;border: none;">
 <div>
<input id="numencours2" name="numencours" type="text" readonly value="" style="font-size: 30px;border: none;">
</div>
        

                            </form>

                        </div>
                    </div>

                </div>

                <div class="modal-footer">


                   
 <button id="racc3" type="button"  class="btn btn-primary"  onclick="Hangup2();"><i class="fas fa-phone-slash"></i> Raccrocher</button>
 <div id="mettreenattenteenv2" style="display:none;"><button type="button"  class="btn btn-primary" onclick="hold2(true);" ><i class="fas fa-pause"></i> Mettre en attente</button></div>
 <div id="reprendreappelenv2" style="display:none;"><button type="button"  class="btn btn-primary"  onclick="hold2(false);"><i class="fas fa-phone"></i> Reprendre</button></div>
 <div id="coupersonenv2" style="display :none;"><button type="button"  class="btn btn-primary" onclick="mute2(true,0);" ><i class="fas fa-microphone-slash"></i> Couper le son</button></div>
 <div id="reactivesonenv2" style="display:none;"><button type="button"  class="btn btn-primary"  onclick="mute2(false,0);"><i class="fas fa-microphone"></i> Réactiver son</button></div>
 <button id="transferappenv2" style="display:none;" type="button"  class="btn btn-primary" data-toggle="modal" data-target="#numatransfer1"><i class="fas fa-reply-all"></i> Transférer</button>
<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
              <!--<button type="button"  class="btn btn-primary"  onclick="transfer();">Transférer</button>    
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>!-->

                </div>
            </div>

        </div>

    </div>
    <div class="modal  " style="z-index:10000!important;left: 20px;"  id="crenduappel" data-backdrop="static"  data-keyboard="false" >
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="text-align:center"  id="modalalert0"><center>Compte Rendu </center> </h5>
                </div>
                <div class="modal-body">
                    <div class="card-body">
<input type="hidden"    id="idenvoyetel"   class="form-control" name="idenvoyetel"    />
                      

                       <!-- <div class="form-group">
                            <label for="sujet">Dossier :</label>
                            <select   id="iddossier"  style="width:100%;" class="form-control select2" name="dossierid"     >
                                <option></option>
                                <?php /* foreach($listedossiers as $ds)
                                {
                                echo '<option value="'.$ds->reference_medic.'"> '.$ds->reference_medic.' | '.$ds->subscriber_name.' - '.$ds->subscriber_lastname.' </option>';}  */ ?>
                            </select>
                        </div>
                        -->
                        <div class="form-group">
                            <label for="sujetcr">Sujet :</label>
                            <input type="text"    id="sujetcrtel"   class="form-control" name="sujetcrtel"    />

                        </div>

                        <div class="form-group">
                            <label for="descriptioncr">Description :</label>
                            <input style="overflow:scroll;" id="descriptioncrtel"   class="form-control" name="descriptioncrtel"    />

                        </div>

                        <div class="form-group">
                            <label for="contenucr">Contenu *:</label>
                            <textarea style="height:100px;" id="contenucrtel"   class="form-control" name="contenucrtel"    ></textarea>

                        </div>

                        


                    </div>

                </div>
                <div class="modal-footer">
                    <a id="ajoutcompterappel"   class="btn btn  "   style="background-color:#5D9CEC; width:100px;color:#ffffff"   >Ajouter</a>
                    <button id="fermecompterendu" type="button" class="btn btn-secondary" data-dismiss="modal" style="width:100px">Annuler</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" style="z-index:10000!important;left: 20px;"  id="crenduappellibre" role="dialog" aria-labelledby="exampleModal2" aria-hidden="true"  data-backdrop="static"  data-keyboard="false">

        <div class="modal-dialog" role="crlibre" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="text-align:center"  id=""><center>Dispatch et Compte Rendu </center> </h5>
                </div>
                <div class="modal-body">
                    <div class="card-body">
<input type="hidden"    id="idenvoyetellibre"   class="form-control" name="idenvoyetellibre" />
                      

                       <div class="form-group">
                            <label for="dossiercrlibre">Dossier :</label>
                            <select   id="dossiercrlibre"  style="width:100%;color:black!important;"  name="dossiercrlibre" class="form-control "   >
                                <option></option>
                                <?php 

                                foreach($listedossiers as $ds)
                                {
                                echo '<option value="'.$ds->reference_medic.'"> '.$ds->reference_medic.' | '.$ds->subscriber_name.' - '.$ds->subscriber_lastname.' </option>';}  
                                ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="sujetcrlibre">Sujet :</label>
                            <input type="text"    id="sujetcrlibre"   class="form-control" name="sujetcrlibre"    />

                        </div>

                        <div class="form-group">
                            <label for="descriptioncrlibre">Description :</label>
                            <input style="overflow:scroll;" id="descriptioncrlibre"   class="form-control" name="descriptioncrlibre"    />

                        </div>

                        <div class="form-group">
                            <label for="contenucr">Contenu *:</label>
                            <textarea style="height:100px;" id="contenucrlibre"   class="form-control" name="contenucrlibre"    ></textarea>

                        </div>

                        


                    </div>

                </div>
                <div class="modal-footer">
                    <a id="ajoutcompterappellibre"   class="btn btn  "   style="background-color:#5D9CEC; width:100px;color:#ffffff"   >Ajouter</a>
                    <button id="fermecompterendulibre" type="button" class="btn btn-secondary" data-dismiss="modal" style="width:100px">Annuler</button>
                </div>
            </div>
        </div>
    </div>
     <div class="modal  " style="z-index:10000!important;left: 20px;"  id="crenduappelrecu"  data-backdrop="static"  data-keyboard="false">
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="text-align:center"  id="modalalert0"><center>Dispatch et Compte Rendu </center> </h5>
                </div>
                <div class="modal-body">
                    <div class="card-body">
<input type="hidden"    id="idenvoyetelrecu"   class="form-control" name="idenvoyetelrecu"    />
                      

                       <div class="form-group">
                            <label for="dossiercrrecu">Dossier :</label>
                            <select   id="dossiercrrecu"  style="width:100%;"  name="dossiercrrecu"     >
                                <option></option>
                                <?php 

                                foreach($listedossiers as $ds)
                                {
                                echo '<option value="'.$ds->reference_medic.'"> '.$ds->reference_medic.' | '.$ds->subscriber_name.' - '.$ds->subscriber_lastname.' </option>';}  
                                ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="sujetcrrecu">Sujet :</label>
                            <input type="text"    id="sujetcrrecu"   class="form-control" name="sujetcrrecu"    />

                        </div>

                        <div class="form-group">
                            <label for="descriptioncrrecu">Description :</label>
                            <input style="overflow:scroll;" id="descriptioncrrecu"   class="form-control" name="descriptioncrrecu"    />

                        </div>

                        <div class="form-group">
                            <label for="contenucr">Contenu *:</label>
                            <textarea style="height:100px;" id="contenucrrecu"   class="form-control" name="contenucrrecu"    ></textarea>

                        </div>

                        


                    </div>

                </div>
                <div class="modal-footer">
                    <a id="ajoutcompterappelrecu"   class="btn btn  "   style="background-color:#5D9CEC; width:100px;color:#ffffff"   >Ajouter</a>
                    <button id="fermecompterendurecu" type="button" class="btn btn-secondary" data-dismiss="modal" style="width:100px">Annuler</button>
                </div>
            </div>
        </div>
    </div>

<style>
    @media  (max-width: 1280px)  /*** 150 % ***/  {
        #search-bar input{width:200px;}
        .search-icon{display:none;}
    }
    @media (max-width: 1024px)    {
        #search-bar input{width:170px;}
       .phone , .boite,  .date,  .search-icon{display:none;}
    }
    @media (max-width: 1100px) /*** 175 % ***/  {
        #search-bar input{width:170px;}
        .phone , .boite,   .date,   .search-icon{display:none;}
    }

   .user-menu .boite , .phone{
    margin-left: 30px;
    }

    .single-char {
        color:red;
        cursor:pointer;
    }

    .single-char2 {
        color:black;
        cursor:pointer;
    }


    .kbsdropdowns
    {

        display:block ;
        position:relative ;
        top:-65px;
        left: -50px;


    }
.modal-lg
    {

        max-width:60%;


    }
</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script>

$(document).ready(function() {
    
  });
    function colorerSeq(string,qy) {
        if(qy!='')
        {
            var caracSp = ['-', '_', '(',')',' '];
            // For all matching elements
            $(string).each(function() {

                var hrefString=$(this).html();
                var a1=hrefString.indexOf("\"");
                var b1=hrefString.lastIndexOf("\"");
                hrefString=hrefString.substring(a1+1,b1);
                // Get contents of string
                var myStr = $(this).text();
                // Split myStr into an array of characters
                myStr = myStr.split("");
                var dejaEn=false;
                // Build an html string of characters wrapped in  tags with classes
                var myContents = "";
                var noniden="";
                var ancien;
                var kol=false;
                for (var i = 0, len = myStr.length; i < len; i++) {
                    if(qy[0].toUpperCase()==myStr[i].toUpperCase())
                    {
                        if(!dejaEn)
                        {
                            ancien= myContents ;
                            kol=true;
                            noniden="";
                            for(var j=0, len2 = qy.length; j<len2; j++)
                            {
                                if(i<len)
                                {
                                    if(qy[j].toUpperCase()==myStr[i].toUpperCase() || caracSp.includes(myStr[i]) )
                                    {
                                        if(qy[j].toUpperCase()==myStr[i].toUpperCase())
                                        {
                                            // alert ("bonjour");
                                            myContents += '<span class="single-char char-' + i + '">' + myStr[i] + '</span>';
                                            noniden+= '<span class="single-char2 char-' + i + '">' + myStr[i] + '</span>';
                                            i++;
                                        }
                                        else
                                        {
                                            if(caracSp.includes(myStr[i]))
                                            {
                                                i++;
                                                if(qy[j].toUpperCase()==myStr[i].toUpperCase())
                                                {
                                                    myContents += '<span class="single-char2 char-' + (i-1) + '">' + myStr[i-1] + '</span>';
                                                    myContents += '<span class="single-char char-' + i + '">' + myStr[i] + '</span>';
                                                    noniden+= '<span class="single-char2 char-' + (i-1) + '">' + myStr[i-1] + '</span>';
                                                    noniden+= '<span class="single-char2 char-' + i + '">' + myStr[i] + '</span>';
                                                    i++;
                                                }
                                                else
                                                {
                                                    myContents += '<span class="single-char2 char-' + (i-1) + '">' + myStr[i-1] + '</span>';
                                                    noniden+= '<span class="single-char2 char-' + (i-1) + '">' + myStr[i-1] + '</span>';
                                                }
                                            }
                                        }
                                    }
                                    else
                                    {
                                        myContents=ancien;
                                        myContents += noniden;
                                        myContents += '<span class="single-char2 char-' + i + '">' + myStr[i] + '</span>';
                                        j=len2;
                                        kol=false;
                                        ancien= myContents;
                                    }
                                }
                                else
                                {
                                    myContents=ancien;
                                    j=len2;
                                    kol=false;
                                }
                            }
                            if(kol)
                            {
                                i--;
                                dejaEn=true;
                            }
                            else
                            {
                                 dejaEn=false;
                            }
                        }
                        else
                        {
                            myContents += '<span class="single-char2 char-' + i + '">' + myStr[i] + '</span>';
                        }
                    }
                    else
                    {
                        myContents += '<span class="single-char2 char-' + i + '">' + myStr[i] + '</span>';
                    }
                }
                myContents='<a href="'+hrefString+'">'+myContents+'</a>';
                // Replace original string with constructed html string
                $(this).html(myContents);
            });
        }
    }

    $(document).ready(function(){

        $("#search-bar").keyup(function(){
            var qy=$(this).val();
            //alert(qy);

            if(qy != ''){
                var _token=$('input[name="_token"]').val();
                $.ajax({

                    url:"{{ route('RechercheMulti.autocomplete')}}",
                    method:"POST",
                    data:{qy:qy, _token:_token},
                    success:function(data)
                    {
                        $("#kkk").fadeIn();
                        $("#kkk").html(data);
                        var myStringType=$('.resAutocompRech');
                        colorerSeq(myStringType,qy);
                    }
                });
            }
            else
            {
                $("#kkk").fadeOut();
            }
        });
    });


</script>

<script>
    $(document).on('click','.resAutocompTyoeAct',function(){

        $("#search-bar").val($(this).text());
        $("#kkk").fadeOut();

    });


    $('#search-bar').blur(function() {
        $("#kkk").fadeOut();
    });



</script>
<?php
  $param= App\Parametre::find(1);$env=$param->env;
$urlapp="http://$_SERVER[HTTP_HOST]/".$env;
?>


<?php
$seance =  DB::table('seance')
    ->where('id','=', 1 )->first();
$user = auth()->user();
$iduser=$user->id; ?>
<!--select css-->
<script src="{{ asset('public/js/select2/js/select2.js') }}"></script>


<script>

    $('#dpause').click(function() {

        $('#modalconfirm').modal({show: true});

    });
     // $('.js-example-basic-single').select2();
    /*
    $("#dossierid").select2();
  

    $('#phoneicon').click(function() {

        $('#crendu').modal({show: true});

    });

    // Ajout Compte Rendu
    $('#ajoutcompter').click(function() {

        var _token = $('input[name="_token"]').val();
        var dossier = document.getElementById('dossierid').value;
        var contenu = document.getElementById('contenucr').value;

        $.ajax({
            url: "{{ route('entrees.ajoutcompter') }}",
            method: "POST",
            data: { dossier:dossier,contenu:contenu,  _token: _token},

            success: function (data) {
                alert('Ajouté avec succès');
                $('#crendu').modal('hide');
                //     $('#crendu').modal({show: false});

            }
        });


    }); //end click
*/


    $('#oui').click(function() {
        $('#modalconfirm').modal('hide');

        var _token = $('input[name="_token"]').val();
        var duree = document.getElementById('dureep').value;

        $.ajax({
            url: "{{ route('home.demandepause') }}",
            method: "POST",
            data: { duree:duree,  _token: _token},

            success: function (data) {
                alert('Demande envoyée');

            }
        });


    }); //end click





</script>

<script>
$(document).ready(function() {
  /*$("#dossiercrlibre").select2();*/

var countrecep=1;
var extensiontel = $('#extensiontel').val();
 var motdepassetel = $('#motdepassetel').val();
//alert(extensiontel);
        webphone_api.parameters['username'] = extensiontel;      // SIP account username
        webphone_api.parameters['password'] = motdepassetel;      // SIP account password (see the "Parameters encryption" in the documentation)        
        webphone_api.parameters['callto'] = '';        // destination number to call
        webphone_api.parameters['autoaction'] = 0;     // 0=nothing (default), 1=call, 2=chat, 3=video call
        webphone_api.parameters['autostart'] = 0;     // start the webphone only when button is clicked
       //webphone_api.parameters['voicerecupload'] = 'ftp://mizutest:NajdaApp2020!@host.enterpriseesolutions.com/voice_CALLER_CALLED_DATETIME.wav'; 
webphone_api.parameters['voicerecupload'] = 'ftp://ftpmizuuser:Najda2020@192.168.1.249/voice_CALLER_CALLED_DATETIME.wav'; 
 webphone_api.start();
webphone_api.onCallStateChange(function (event, direction, peername, peerdisplayname)

{

                if (event === 'setup' && direction == 2)

                {
              
$('#appelinterfacerecep').modal({show: true});
            $(".modal-body #numencoursrecep").val(peerdisplayname );
var _token = $('input[name="_token"]').val();
$.ajax({

                    url:"{{ route('entrees.detectnom')}}",
                    method:"POST",
                    data:'_token='+_token+'&peerdisplayname='+peerdisplayname,
                    success:function(data)
                    {
                         $(".modal-body #nomencoursrecep").val(data );
                    }
                });  

                }  
 if (event === 'connected' && direction == 2)

                {
var minutesLabel1 = document.getElementById("minutes1");
var secondsLabel1 = document.getElementById("seconds1");
var totalSeconds1 = 0;
setInterval(setTime1, 1000);

function setTime1() {
  ++totalSeconds1;
  secondsLabel1.innerHTML = pad1(totalSeconds1 % 60);
  minutesLabel1.innerHTML = pad1(parseInt(totalSeconds1 / 60))+":";
}

function pad1(val1) {
  var valString1 = val1 + "";
  if (valString1.length < 2) {
    return "0" + valString1;
  } else {
    return valString1;
  }
}
document.getElementById('mettreenattente').style.display = 'inline-block';
 document.getElementById('couperson').style.display = 'inline-block'; 
document.getElementById('transferapp').style.display = 'inline-block';
document.getElementById('status_call').innerHTML="Appel en cours";
document.getElementById('repondre').style.display = 'none';
              } 
if (event === 'connected' && direction == 1)

                {
var minutesLabel = document.getElementById("minutes");
var secondsLabel = document.getElementById("seconds");
var totalSeconds = 0;
setInterval(setTime, 1000);

function setTime() {
  ++totalSeconds;
  secondsLabel.innerHTML = pad(totalSeconds % 60);
  minutesLabel.innerHTML = pad(parseInt(totalSeconds / 60))+":";
}

function pad(val) {
  var valString = val + "";
  if (valString.length < 2) {
    return "0" + valString;
  } else {
    return valString;
  }
}

document.getElementById('mettreenattenteenv2').style.display = 'inline-block';
 document.getElementById('coupersonenv2').style.display = 'inline-block'; 
document.getElementById('transferappenv2').style.display = 'inline-block';
document.getElementById('status_callenv2').innerHTML="Appel en cours";
var minutesLabel2 = document.getElementById("min2");
var secondsLabel2 = document.getElementById("sec2");
var totalSeconds2 = 0;
setInterval(setTime2, 1000);

function setTime2() {
  ++totalSeconds2;
  secondsLabel2.innerHTML = pad2(totalSeconds2 % 60);
  minutesLabel2.innerHTML = pad2(parseInt(totalSeconds2 / 60))+":";
}

function pad2(val2) {
  var valString2 = val2 + "";
  if (valString2.length < 2) {
    return "0" + valString2;
  } else {
    return valString2;
  }
}
document.getElementById('mettreenattenteenv').style.display = 'inline-block';
 document.getElementById('coupersonenv').style.display = 'inline-block'; 
document.getElementById('transferappenv').style.display = 'inline-block';
document.getElementById('status_callenv').innerHTML="Appel en cours";
 } 
if (event === 'disconnected' && direction == 2)
{
$('#appelinterfacerecep').modal('hide');
$('#appelinterfaceenvoi').modal('hide');
 //location.reload();

} 
if (event === 'disconnected' && direction == 1)
{
// location.reload();
$('#appelinterfaceenvoi2').modal('hide');}

 //$("#dossiercrlibre").select2();
});

webphone_api.onCdr(function (caller, called, connecttime, duration, direction, peerdisplayname, reason, line)
{

if (direction == 1)
{

 var durationInt = parseInt(duration,10);
var durationSec = Math.floor((durationInt+500)/1000);
var _token = $('input[name="_token"]').val();
var refdossier = $('#refdossier').val();
var natureappel = $('#natureappel').val();

$.ajax({

                    url:"{{ route('envoyes.envoyetel')}}",
                    method:"POST",

                    data:'_token='+_token+'&caller='+caller+'&called='+called+'&duration='+durationSec+'&refdossier='+refdossier+'&natureappel='+natureappel,
                    success:function(data)
                    {
                      if(natureappel==="dossier")
                      {

                        //alert(data);
                         document.getElementById('idenvoyetel').value=data;  
                         $("#appelinterfaceenvoi").modal('hide');
                         $('#crenduappel').modal({show:true});

                      }
                      if(natureappel==="libre")
                      {

                        //alert(data);
                         document.getElementById('idenvoyetellibre').value=data;  
                         $("#appelinterfaceenvoi2").modal('hide');
                         $('#crenduappellibre').modal({show:true});
                         //$("#dossiercrlibre").select2();

                      }



                    }
                });

}
if ( direction == 2)
{

var durationInt = parseInt(duration,10);
var durationSec = Math.floor((durationInt+500)/1000);
var _token = $('input[name="_token"]').val();
var natureappelrecu= $('#natureappelrecu').val();
//alert(natureappelrecu);


$.ajax({

                    url:"{{ route('entrees.entreetel')}}",
                    method:"POST",
                    data:'_token='+_token+'&caller='+caller+'&called='+called+'&duration='+durationSec+'&natureappelrecu='+natureappelrecu,
                    success:function(data)
                    {
                      if(natureappelrecu==="librerecu")
                      {

                        //alert(data);
                         document.getElementById('idenvoyetelrecu').value=data;  
                         $("#appelinterfacerecep").modal('hide');
                         $('#crenduappelrecu').modal({show:true});

                      }



                    }
                });


}
});
});
      function Hangup()
        {
            webphone_api.hangup();
            
        }
function accept()
        {
            document.getElementById('natureappelrecu').value='librerecu';  
            webphone_api.accept();
            
        }
    function transfer()
        {
numtrans=$('#numatrans').val();
//numtrans.toString();
//alert(numtrans);
            webphone_api.transfer(numtrans);
//alert("OK");
        }
  function hold(state)
        {
if(state===true)

         {   webphone_api.hold(state);
document.getElementById('mettreenattente').style.display = 'none';
document.getElementById('reprendreappel').style.display = 'inline-block';}
if(state===false)

         {   webphone_api.hold(state);
document.getElementById('reprendreappel').style.display = 'none';
document.getElementById('mettreenattente').style.display = 'inline-block';}

        }
function mute(state,direction)
        {
if(state===true)

         {   webphone_api.mute(state,direction);
document.getElementById('couperson').style.display = 'none';
document.getElementById('reactiveson').style.display = 'inline-block';}
if(state===false)

         {   webphone_api.mute(state,direction);
document.getElementById('reactiveson').style.display = 'none';
document.getElementById('couperson').style.display = 'inline-block';}

        }

 

       
        function ButtonOnclick2()
        {

document.getElementById('natureappel').value='libre';
peerdisplayname=document.getElementById('numtel1').value;
if(peerdisplayname!=="")
{
$('#appelinterfaceenvoi2').modal({show:true});
//alert(peerdisplayname);
     $(".modal-body #numencours2").val(peerdisplayname );
var _token = $('input[name="_token"]').val();
$.ajax({

                    url:"{{ route('entrees.detectnom')}}",
                    method:"POST",
                    data:'_token='+_token+'&peerdisplayname='+peerdisplayname,
                    success:function(data)
                    {
                         $(".modal-body #nomencours2").val(data );
                    }
                }); 


  $("#faireappel1").modal('hide');
                
 
 
                webphone_api.call(peerdisplayname);}

//testiscall();




}

           function Hangup2()
        {
            webphone_api.hangup();
            
        }
    function transfer2()
        {
numtrans=$('#numatrans2').val();
//numtrans.toString();
//alert(numtrans);
            webphone_api.transfer(numtrans);
//alert("OK");
        }
  function hold2(state)
        {

if(state===true)

         {  

 webphone_api.hold(state);
document.getElementById('mettreenattenteenv2').style.display = 'none';
document.getElementById('reprendreappelenv2').style.display = 'inline-block';}
if(state===false)

         {   webphone_api.hold(state);
document.getElementById('reprendreappelenv2').style.display = 'none';
document.getElementById('mettreenattenteenv2').style.display = 'inline-block';}

        }
function mute2(state,direction)
        {
if(state===true)

         {   webphone_api.mute(state,direction);
document.getElementById('coupersonenv2').style.display = 'none';
document.getElementById('reactivesonenv2').style.display = 'inline-block';}
if(state===false)

         {   webphone_api.mute(state,direction);
document.getElementById('reactivesonenv2').style.display = 'none';
document.getElementById('coupersonenv2').style.display = 'inline-block';}

        }
         $('#ajoutcompterappel').click(function() {
            
            var envoyetel = document.getElementById('idenvoyetel').value;
           var _token=$('input[name="_token"]').val();
            var contenu = document.getElementById('contenucrtel').value;
            var sujet = document.getElementById('sujetcrtel').value;
            var description = document.getElementById('descriptioncrtel').value;
            var iduser=document.getElementById('iduser').value;
            if(contenu != ''){
                $.ajax({
                    url: "{{ route('envoyes.ajoutcompterappel') }}",
                    method: "POST",
                    data: { envoyetel:envoyetel,contenu:contenu, sujet:sujet,description:description,iduser:iduser, _token: _token},
                    success: function (data) {
                     location.reload();
                    }
                });
            }else{
                alert('le contenu est obligatoire !');
            }
        }); //
          $('#ajoutcompterappellibre').click(function() {
           
            var envoyetel = document.getElementById('idenvoyetellibre').value;
            
           var _token=$('input[name="_token"]').val();
            var contenu = document.getElementById('contenucrlibre').value;
            var sujet = document.getElementById('sujetcrlibre').value;
            var description = document.getElementById('descriptioncrlibre').value;
            var dossier = $('#dossiercrlibre').val();
            var iduser=document.getElementById('iduser').value;
            if(contenu != ''){
                $.ajax({
                    url: "{{ route('envoyes.ajoutcompterappellibre') }}",
                    method: "POST",
                    data: { envoyetel:envoyetel,contenu:contenu, sujet:sujet,description:description,iduser:iduser,dossier:dossier, _token: _token},
                    success: function (data) {
                     location.reload();
                    }
                });
            }else{
                alert('le contenu est obligatoire !');
            }
        }); 

$('#ajoutcompterappelrecu').click(function() {
           
            var envoyetel = document.getElementById('idenvoyetelrecu').value;
            
           var _token=$('input[name="_token"]').val();
            var contenu = document.getElementById('contenucrrecu').value;
            var sujet = document.getElementById('sujetcrrecu').value;
            var description = document.getElementById('descriptioncrrecu').value;
            var dossier = $('#dossiercrrecu').val();
            var iduser=document.getElementById('iduser').value;
            if(contenu != ''){
                $.ajax({
                    url: "{{ route('entrees.ajoutcompterappelrecu') }}",
                    method: "POST",
                    data: { envoyetel:envoyetel,contenu:contenu, sujet:sujet,description:description,iduser:iduser,dossier:dossier, _token: _token},
                    success: function (data) {
                     location.reload();
                    }
                });
            }else{
                alert('le contenu est obligatoire !');
            }
        }); 
$('#fermecompterendu').click(function(){
 
                            window.location.reload();
});
$('#fermecompterendulibre').click(function(){
 
                            window.location.reload();
});
$('#fermecompterendurecu').click(function(){
 
                            window.location.reload();
});

</script>
