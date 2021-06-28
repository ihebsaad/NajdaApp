<!--select css-->
<link href="{{ asset('public/js/select2/css/select2.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('public/js/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<?php   $testphoneaff=0; if (Session::get('telephonie')=='false') { ?>
<?php $testphoneaff=1; Session::put('telephonie', 'true'); ?>
<script> testphone=1; </script>

  <script src="{{ asset('public/webphone/najdaapp/webphone/webphone_api.js') }}"></script>

<?php } ?>
<script>var incall2=0; var natureappelconf='';var conference=0;var incall = 0 ; var acceptvar=0;var tabcall =[]; var i=0;</script>
<?php
use App\Dossier;
     $listedossiersappel= Dossier::where('current_status','<>','Cloture')->orderby('id','desc')
             ->get();
    $CurrentUser = auth()->user();
         $iduser=$CurrentUser->id;
$extensionusers=$CurrentUser->extension;
$motdepasseextensionusers=$CurrentUser->motdepasseextension;


?>
 <input id="extensiontel" name="extensiontel" type="text" value="<?php echo $extensionusers;  ?>">
                        <input id="motdepassetel" name="motdepassetel" type="text" value="<?php echo $motdepasseextensionusers;  ?>">

<header class="header">
   <input id="iduser" name="iduser" type="hidden" value="{{$iduser}}" />   
  <input id="natureappel" name="natureappel" type="hidden" value="" />
   <input id="natureappelrecu" name="natureappelrecu" type="hidden" value="" />   
    <?php
    use App\Entree;
    $seance =  DB::table('seance')
        ->where('id','=', 1 )->first();
    $user = auth()->user();
    $iduser=$user->id;

    $listedossiers = DB::table('dossiers')->get();
    ?>
    
    
        <div class="collapse bg-grey" id="navbarHeader">
             @include('layouts.partials._top_menu')

        </div>

    <div class="navbar "  style="background-color:#4FC1E9 !important">
      <div class="row">
        <div class="col-sm-1 col-md-1 col-lg-1" style="margin-right:60px">
           <a href="{{ route('supervision')}}" class="logo" style="background-color: transparent!important;border:none!important;">
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


		  
          <div  class="col-sm-1 col-md-1 col-lg-1 " style="padding-top:10px;padding-left:0px!important;margin-left:15px">
              <a href="{{ route('home') }}" class="btn btn-default btn-lg btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Demander pause de ton superviseur" style="margin-bottom: 28px!important;">
                  <i class="far fa-user"></i>
                  <br>
                  Agent
              </a>
          </div>


		  


        <div class="col-sm-3 col-md-2 col-lg-2" style=" height: 40px!important;padding-top:27px;padding-left:0px ">
          <form class="search-container" action="{{route('RechercheMulti.test')}}" id="testRecheche" method="POST">
            <input type="text" id="search-bar"  placeholder="Recherche" autocomplete="off" name="qy">
            <a href="#" onclick='document.getElementById("testRecheche").submit()'><img class="search-icon" src="{{ URL::asset('public/img/search-icon.png') }}"></a>
                 <!--<div id="countryList">
                </div>-->

                <div id="kkk" class="dropdown" style="top:0px; left:-50px; "></div>

           {{ csrf_field() }}
          </form>
        </div>
<?php    if ($testphoneaff==1) { ?>
        <div class="col-sm-1 col-md-1 col-lg-1" style="padding-top:10px;">
          <a   data-toggle="modal" data-target="#faireappel1" id="phonebtn"  class="btn btn-primary btn-lg btn-responsive phone" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Lancer / Recevoir des appels téléphoniques" style="margin-bottom: 28px!important;padding-top: 15px;padding-bottom: 15px;margin-left:-5px ">
              <span class="fas fa-fw fas fa-phone fa-2x"></span>
          </a> 
        </div>
<div class="col-sm-1 col-md-1 col-lg-1" style="padding-top:10px;padding-left:5px;padding-right:150px;">
 <button  id="reacttel" href="#" class="btn btn-primary btn-lg btn-responsive phone">
            Réactiver
          </button> 
        </div>
<?php   } else {  ?>
 <div class="col-sm-1 col-md-1 col-lg-1" style="padding-top:10px;">
 <a  id="phonebtn10" href="#" class="btn  btn-lg phone" role="button"   style="color:white;background-color:red; margin-left:-5px;margin-bottom: 28px!important;padding-top: 15px;padding-bottom: 15px; ">
              <span class="fa fa-fw fa-phone-slash fa-2x"></span>
          </a> 
 </div>
<div class="col-sm-1 col-md-1 col-lg-1" style="padding-top:10px;padding-left:5px;padding-right:150px;">
 <button  id="reacttel" href="#" class="btn btn-primary btn-lg btn-responsive phone">
            Réactiver
          </button> 
        </div>
	<?php   } ?>	
        <div class="col-sm-1 col-md-1 col-lg-1" style="padding-top:10px;">

			  <a href="{{ route('entrees.dispatching') }}" class="btn btn-danger btn-lg btn-responsive boite" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Boîte d'emails" style="margin-bottom: 28px!important;padding-top: 15px;padding-bottom: 15px;">
              <span class="  fa-fw    fas    fa-envelope fa-2x"></span>
          </a>


        </div>

        <div class="col-sm-1 col-md-1 col-lg-1">
          <ul class="nav navbar-nav" style="">
                    {{-- User Account --}}
                    @include('layouts.partials._user_menu')

                </ul>

        </div>


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

<?php
  $param= App\Parametre::find(1);$env=$param->env;
$urlapp="http://$_SERVER[HTTP_HOST]/".$env;
?>

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




<div class="modal  " id="crendu" >
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="text-align:center"  id="modalalert0"><center>Compte Rendu </center> </h5>
            </div>
            <div class="modal-body">
                <div class="card-body">


                    <div class="form-group">
                        <label for="sujet">Dossier :</label>
                        <select   id="dossierid"  style="width:100%;" class="form-control select2" name="dossierid"     >
                            <option></option>
                            <?php foreach($listedossiers as $ds)

                            {
                                echo '<option value="'.$ds->reference_medic.'"> '.$ds->reference_medic.' | '.$ds->subscriber_name.' - '.$ds->subscriber_lastname.' </option>';}     ?>
                        </select>


                    </div>

                    <div class="form-group">
                        <label for="sujet">Contenu :</label>
                        <textarea style="overflow:scroll;" id="contenucr"   class="form-control" name="contenucr"    ></textarea>

                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <a id="ajoutcompter"   class="btn btn  "   style="background-color:#5D9CEC; width:100px;color:#ffffff"   >Ajouter</a>
                <button type="button" class="btn btn-secondary reloadclass" data-dismiss="modal" style="width:100px">Annuler</button>
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

                        <button type="button"  class="btn btn-primary"  onclick="transfer();">Appeler avant le transfert</button>
<button type="button"  class="btn btn-primary"  onclick="transfer7();">Raccrocher avant le transfert
</button>
  <button type="button"  class="btn btn-primary"  onclick="transfer8();"> Transférer
</button>
   
   
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>

                </div>
            </div>

        </div>

    </div>
<!--Modal Tel 2-->

    <div style="overflow-y:scroll;"class="modal fade" id="appelinterfacerecep"    role="dialog" aria-labelledby="basicModal" aria-hidden="true" data-backdrop="static"  data-keyboard="false">
 <div class="modal-dialog modal-lg" role="telrecep"  sytle="width:200px;height:30px">
            <div class="modal-content">
                <div class="modal-header">
                  <h3 class="modal-title" style="text-align:center"  id=" "><center>Recevoir un appel </center> </h3>
</div>
                <div class="modal-body">
                    <div class="card-body" >


                        <div class="form-group">
                            {{ csrf_field() }}
<div id="divtableappels1" style="display:none;">
                            <form id="appelinterfacerecep" novalidate="novalidate">
<table class="table table-striped" id="tableappels1" style="width:100%;margin-top:15px;">
                            <thead>
                            <tr id="headtable">
                                <th style="">Numéro</th>
                                <th style="">Actions</th>
                             </tr>

                            </thead>
                            <tbody>
                            </tbody>
                    </table>
</div>
  
            <div style="font-size: 30px;">

<label style="color:green;font-size: 30px;"id="status_call"></label>


<label style="margin-left:180px;font-size: 30px;"id="minutes1"></label>
<label style="font-size:30px;" id="seconds1"></label>

</div>
<input id="nomencoursrecep" name="nomencours" type="text" readonly value="" style="font-size: 30px;border: none;">
 <div>
<input id="numencoursrecep" name="numencours" type="text" readonly value="" style="font-size: 30px;border: none;">
</div>
 <div id='compterendurecuencours' style="display:none"><label style="color:green;font-size: 30px;">Dispatch et Compte rendu</label>
 <div class="form-group">
                            <label for="dossiercrrecuencours">Dossier :</label>
                            <select   id="dossiercrrecuencours"  style="width:100%;"  name="dossiercrrecuencours"     >
                                <option></option>
                                <?php 

                                foreach($listedossiers as $ds)
                                {
                                echo '<option value="'.$ds->reference_medic.'"> '.$ds->reference_medic.' | '.$ds->subscriber_name.' - '.$ds->subscriber_lastname.' </option>';}  
                                ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="sujetcrrecuencours">Sujet :</label>
                            <input type="text"    id="sujetcrrecuencours"   class="form-control" name="sujetcrrecuencours"    />

                        </div>

                        <div class="form-group">
                            <label for="descriptioncrrecuencours">Description :</label>
                            <input style="overflow:scroll;" id="descriptioncrrecuencours"   class="form-control" name="descriptioncrrecuencours"    />

                        </div>

                        <div class="form-group">
                            <label for="contenucrrecuencours">Contenu *:</label>
                            <textarea style="height:100px;" id="contenucrrecuencours"   class="form-control" name="contenucrrecuencours"    ></textarea>

                        </div>
<button id="repondre" type="button"  class="btn btn-primary"  onclick="accept();"><i class="fas fa-phone-volume"></i> Répondre</button>              
 <button id="racc2" type="button"  class="btn btn-primary"  onclick="Hangup();"><i class="fas fa-phone-slash"></i> Raccrocher </button>
 <div id="mettreenattente" style="display :none;"><button type="button"  class="btn btn-primary" onclick="hold(true);" ><i class="fas fa-pause"></i> En attente</button></div>
 <div id="reprendreappel" style="display :none;"><button type="button"  class="btn btn-primary"  onclick="hold(false);"><i class="fas fa-phone"></i> Reprendre</button></div>
 <div id="couperson" style="display :none;"><button type="button"  class="btn btn-primary" onclick="mute(true,0);" ><i class="fas fa-microphone-slash"></i> Couper</button></div>
 <div id="reactiveson" style="display :none;"><button type="button"  class="btn btn-primary"  onclick="mute(false,0);"><i class="fas fa-microphone"></i> Réactiver</button></div>
 <button id="transferapp" type="button"  style="display :none;" class="btn btn-primary" data-toggle="modal" data-target="#numatransfer"><i class="fas fa-reply-all"></i> Transférer</button>
 <button id="conferenceapp" style="display:none;" type="button"  class="btn btn-primary" data-toggle="modal" data-target="#numaconference"><i class="fas fa-user-friends"></i> Conférence</button>
<button id="calvier3" style="display:none;" type="button"  class="btn btn-primary" data-toggle="modal" data-target="#clavier3"><i class="fas fa-keyboard"></i> Clavier</button>
<button style="display :none;" id="pass2" type="button"  class="btn btn-primary"  onclick="pass21();"><i class="fas fa-phone-volume"></i>deuxiéme appel</button> 
  </div>
 <div style="font-size: 30px;">

<label style="color:green;font-size: 30px;"id="status_call12"></label>


<label style="margin-left:180px;font-size: 30px;"id="minutes12"></label>
<label style="font-size:30px;" id="seconds12"></label>

</div>
<input id="nomencoursrecep12" name="nomencours12" type="text" readonly value="" style="font-size: 30px;border: none;">
 <div>
<input id="numencoursrecep12" name="numencours12" type="text" readonly value="" style="font-size: 30px;border: none;">
</div>
 <div id='compterendurecuencours12' style="display:none"><label style="color:green;font-size: 30px;">Dispatch et Compte rendu</label>
 <div class="form-group">
                            <label for="dossiercrrecuencours12">Dossier :</label>
                            <select   id="dossiercrrecuencours12"  style="width:100%;"  name="dossiercrrecuencours12"     >
                                <option></option>
                                <?php 

                                foreach($listedossiers as $ds)
                                {
                                echo '<option value="'.$ds->reference_medic.'"> '.$ds->reference_medic.' | '.$ds->subscriber_name.' - '.$ds->subscriber_lastname.' </option>';}  
                                ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="sujetcrrecuencours12">Sujet :</label>
                            <input type="text"    id="sujetcrrecuencours12"   class="form-control" name="sujetcrrecuencours12"    />

                        </div>

                        <div class="form-group">
                            <label for="descriptioncrrecuencours12">Description :</label>
                            <input style="overflow:scroll;" id="descriptioncrrecuencours12"   class="form-control" name="descriptioncrrecuencours12"    />

                        </div>

                        <div class="form-group">
                            <label for="contenucrrecuencours12">Contenu *:</label>
                            <textarea style="height:100px;" id="contenucrrecuencours12"   class="form-control" name="contenucrrecuencours12"    ></textarea>

                        </div>
<input type="hidden" value="" id="cr1">
<input type="hidden" value="" id="cr2">
  </div>
            
 

                            </form>

                        </div>
                    </div>

                </div>

                <div class="modal-footer">
<button id="racc12" type="button" style="display :none;"  class="btn btn-primary"  onclick="Hangup21();"><i class="fas fa-phone-slash"></i> Raccrocher </button>
 <div id="mettreenattente12" style="display :none;"><button type="button"  class="btn btn-primary" onclick="hold(true);" ><i class="fas fa-pause"></i> En attente</button></div>
 <div id="reprendreappel12" style="display :none;"><button type="button"  class="btn btn-primary"  onclick="hold(false);"><i class="fas fa-phone"></i> Reprendre</button></div>
 <div id="couperson12" style="display :none;"><button type="button"  class="btn btn-primary" onclick="mute(true,0);" ><i class="fas fa-microphone-slash"></i> Couper</button></div>
 <div id="reactiveson12" style="display :none;"><button type="button"  class="btn btn-primary"  onclick="mute(false,0);"><i class="fas fa-microphone"></i> Réactiver</button></div>
 <button id="transferapp12" type="button"  style="display :none;" class="btn btn-primary" data-toggle="modal" data-target="#numatransfer"><i class="fas fa-reply-all"></i> Transférer</button>
 <button id="conferenceapp12" style="display:none;" type="button"  class="btn btn-primary" data-toggle="modal" data-target="#numaconference"><i class="fas fa-user-friends"></i> Conférence</button>
<button id="calvier12" style="display:none;" type="button"  class="btn btn-primary" data-toggle="modal" data-target="#clavier3"><i class="fas fa-keyboard"></i> Clavier</button>
<button id="pass1" style="display :none;"  type="button"  class="btn btn-primary"  onclick="pass();"><i class="fas fa-phone-volume"></i>premier appel</button> 
<!--<button type="button" class="btn btn-secondary reloadclass" data-dismiss="modal">Fermer</button>!-->



              <!--<button type="button"  class="btn btn-primary"  onclick="transfer();">Transférer</button>    
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>!-->

                </div>
            </div>

        </div>

    </div>
<!--Modal Tel clavier-->

    <div class="modal fade" style="z-index:10000!important;left: 20px;" id="clavier3"    role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
        <div class="modal-dialog" role="clavier">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal2">Taper le numéro</h5>

                </div>
                <div class="modal-body">
                    <div class="card-body" sytle="height:300px">

                        <div class="form-group">
                            {{ csrf_field() }}

                            <form id="clavier3" novalidate="novalidate">

                               
             <div class="btn-group-vertical ml-4 mt-4" role="group" aria-label="Basic example">
    <div class="btn-group">
        <input class="text-center form-control-lg mb-2" id="code3">
    </div>
<br> <br>
    <div class="row">
        <button type="button" class="btn btn-outline-secondary py-3" onclick="document.getElementById('code3').value=document.getElementById('code3').value + '1';">1</button>
        <button type="button" class="btn btn-outline-secondary py-3" onclick="document.getElementById('code3').value=document.getElementById('code3').value + '2';">2</button>
        <button type="button" class="btn btn-outline-secondary py-3" onclick="document.getElementById('code3').value=document.getElementById('code3').value + '3';">3</button>
    </div>
    <div class="row">
        <button type="button" class="btn btn-outline-secondary py-3" onclick="document.getElementById('code3').value=document.getElementById('code3').value + '4';">4</button>
        <button type="button" class="btn btn-outline-secondary py-3" onclick="document.getElementById('code3').value=document.getElementById('code3').value + '5';">5</button>
        <button type="button" class="btn btn-outline-secondary py-3" onclick="document.getElementById('code3').value=document.getElementById('code3').value + '6';">6</button>
    </div>
    <div class="row">
        <button type="button" class="btn btn-outline-secondary py-3" onclick="document.getElementById('code3').value=document.getElementById('code3').value + '7';">7</button>
        <button type="button" class="btn btn-outline-secondary py-3" onclick="document.getElementById('code3').value=document.getElementById('code3').value + '8';">8</button>
        <button type="button" class="btn btn-outline-secondary py-3" onclick="document.getElementById('code3').value=document.getElementById('code3').value + '9';">9</button>
    </div>
    <div class="row">
        <button type="button" class="btn btn-outline-secondary py-3" onclick="document.getElementById('code3').value=document.getElementById('code3').value + '*';">*</button>
        <button type="button" class="btn btn-outline-secondary py-3" onclick="document.getElementById('code3').value=document.getElementById('code3').value + '0';">0</button>
    <button type="button" class="btn btn-outline-secondary py-3" onclick="document.getElementById('code3').value=document.getElementById('code3').value + '#';">#</button>
    </div>
</div>  
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
<?php

?>

                    <button type="button"  class="btn btn-primary" onclick="dtmfmessage3();">Envoyer
</button>
      
   
   
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>

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

    <div class="modal fade" style="z-index:10000!important;left: 20px;" id="numatransfer2"    role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
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

                       <button type="button"  class="btn btn-primary"  onclick="transfer2();">Appeler avant le transfert</button>
<button type="button"  class="btn btn-primary"  onclick="transfer4();">Raccrocher avant le transfert
</button>
  <button type="button"  class="btn btn-primary"  onclick="transfer3();"> Transférer
</button>
   
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>

                </div>
            </div>

        </div>

    </div>
<!--Modal Tel conference-->

    <div class="modal fade" style="z-index:10000!important;left: 20px;" id="numaconference2"    role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
        <div class="modal-dialog" role="numaconference2">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal2">Saisir le numéro</h5>

                </div>
                <div class="modal-body">
                    <div class="card-body" sytle="height:300px">

                        <div class="form-group">
                            {{ csrf_field() }}

                            <form id="numaconference2" novalidate="novalidate">

                                <input id="numaconf2" name="numaconf2" type="text" value="" />
                                   
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
<?php

?>

                    <button type="button"  class="btn btn-primary"  onclick="Conference2();">Conférence
</button>
           <button type="button"  class="btn btn-primary"  onclick="Conference3();">Confirmer la Conférence
</button>
  <button type="button"  class="btn btn-primary"  onclick="Conference4();">Annuler la Conférence
</button>
   
   
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>

                </div>
            </div>

        </div>

    </div>
<!--Modal Tel conference-->

    <div class="modal fade" style="z-index:10000!important;left: 20px;" id="numaconference"    role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
        <div class="modal-dialog" role="numaconference">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal2">Saisir le numéro</h5>

                </div>
                <div class="modal-body">
                    <div class="card-body" sytle="height:300px">

                        <div class="form-group">
                            {{ csrf_field() }}

                            <form id="numaconference" novalidate="novalidate">

                                <input id="numaconf" name="numaconf" type="text" value="" />
                                   
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
<?php

?>

                    <button type="button"  class="btn btn-primary"  onclick="Conference8();">Conférence
</button>
           <button type="button"  class="btn btn-primary"  onclick="Conference9();">Confirmer la Conférence
</button>
  <button type="button"  class="btn btn-primary"  onclick="Conference10();">Annuler la Conférence
</button>
   
   
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>

                </div>
            </div>

        </div>

    </div>
<!--Modal Tel 2-->

    <div class="modal fade" id="appelinterfaceenvoi2"    role="dialog" aria-labelledby="exampleModal2" aria-hidden="true" data-backdrop="static" data-keyboard="false" >
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
<div id='compterenduencours' style="display:none"><label style="color:green;font-size: 30px;">Dispatch et Compte rendu</label>
        <div class="form-group">
                            <label for="dossiercrlibreencours">Dossier :</label>
                            <select   id="dossiercrlibreencours"  style="width:100%;"  name="dossiercrlibreencours"     >
                                <option></option>
                                <?php 

                                foreach($listedossiersappel as $ds)
                                {
                                echo '<option value="'.$ds->reference_medic.'"> '.$ds->reference_medic.' | '.$ds->subscriber_name.' - '.$ds->subscriber_lastname.' </option>';}  
                                ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="sujetcrlibreencours">Sujet :</label>
                            <input type="text"    id="sujetcrlibreencours"   class="form-control" name="sujetcrlibreencours"    />

                        </div>

                        <div class="form-group">
                            <label for="descriptioncrlibreencours">Description :</label>
                            <input style="overflow:scroll;" id="descriptioncrlibreencours"   class="form-control" name="descriptioncrlibreencours"    />

                        </div>

                        <div class="form-group">
                            <label for="contenucrlibreencours">Contenu *:</label>
                            <textarea style="height:100px;" id="contenucrlibreencours"   class="form-control" name="contenucrlibreencours"    ></textarea>

                        </div>
</div>
         

                            </form>

                        </div>
                    </div>

                </div>

                <div class="modal-footer">


                   
 <button type="button"  class="btn btn-primary"  onclick="Hangup2();"><i class="fas fa-phone-slash"></i> Raccrocher</button>
 <div id="mettreenattenteenv2" style="display:none;"><button type="button"  class="btn btn-primary" onclick="hold2(true);" ><i class="fas fa-pause"></i>En attente</button></div>
 <div id="reprendreappelenv2" style="display:none;"><button type="button"  class="btn btn-primary"  onclick="hold2(false);"><i class="fas fa-phone"></i> Reprendre</button></div>
 <div id="coupersonenv2" style="display :none;"><button type="button"  class="btn btn-primary" onclick="mute2(true,0);" ><i class="fas fa-microphone-slash"></i> Couper </button></div>
 <div id="reactivesonenv2" style="display:none;"><button type="button"  class="btn btn-primary"  onclick="mute2(false,0);"><i class="fas fa-microphone"></i> Réactiver </button></div>
 <button id="transferappenv2" style="display:none;" type="button"  class="btn btn-primary" data-toggle="modal" data-target="#numatransfer2"><i class="fas fa-reply-all"></i> Transférer</button>
<button id="conferenceappenv2" style="display:none;" type="button"  class="btn btn-primary" data-toggle="modal" data-target="#numaconference2"><i class="fas fa-user-friends"></i> Conférence</button>
<button id="calvier" style="display:none;" type="button"  class="btn btn-primary" data-toggle="modal" data-target="#clavier"><i class="fas fa-keyboard"></i> Clavier</button>
<!--<button type="button" class="btn btn-secondary reloadclass" data-dismiss="modal">Fermer</button>!-->
              <!--<button type="button"  class="btn btn-primary"  onclick="transfer();">Transférer</button>    

            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>!-->

                </div>
            </div>

        </div>

    </div>
 <div class="modal  " style="z-index:10000!important;left: 20px;"  id="crenduappellibre"  data-backdrop="static"  data-keyboard="false">
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="text-align:center"  id="modalalert0"><center>Dispatch et Compte Rendu </center> </h5>
                </div>
                <div class="modal-body">
                    <div class="card-body">
<input type="hidden"    id="idenvoyetellibre"   class="form-control" name="idenvoyetellibre"    />
                      

                       <div class="form-group">
                            <label for="dossiercrlibre">Dossier :</label>
                            <select   id="dossiercrlibre"  style="width:100%;"  name="dossiercrlibre"     >
                                <option></option>
                                <?php 

                                foreach($listedossiersappel as $ds)
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
                    <button type="button" class="btn btn-secondary reloadclass" data-dismiss="modal" style="width:100px">Annuler</button>
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

                                foreach($listedossiersappel as $ds)
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
                    <button type="button" class="btn btn-secondary reloadclass" data-dismiss="modal" style="width:100px">Annuler</button>
                </div>
            </div>
        </div>
    </div>
<!-- Modal appels recus-->
<div class="modal fade" id="modalappels"    role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
    <div class="modal-dialog" role="appels" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModal2">Appels reçus</h4>

            </div>
            <div class="modal-body">
                <div class="card-body">
                  
                    <table class="table table-striped" id="tableappels" style="width:100%;margin-top:15px;">
                            <thead>
                            <tr id="headtable">
                                <th style="">Numéro</th>
                                <th style="">Actions</th>
                             </tr>

                            </thead>
                            <tbody>
                            </tbody>
                    </table>

                </div>

            </div>
            <div class="modal-footer">
                <button id="fermerhis"type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<!--Modal Tel clavier-->

    <div class="modal fade" style="z-index:10000!important;left: 20px;" id="clavier"    role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
        <div class="modal-dialog" role="clavier">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal2">Taper le numéro</h5>

                </div>
                <div class="modal-body">
                    <div class="card-body" sytle="height:300px">

                        <div class="form-group">
                            {{ csrf_field() }}

                            <form id="clavier" novalidate="novalidate">

                               
             <div class="btn-group-vertical ml-4 mt-4" role="group" aria-label="Basic example">
    <div class="btn-group">
        <input class="text-center form-control-lg mb-2" id="code">
    </div>
<br> <br>
    <div class="row">
        <button type="button" class="btn btn-outline-secondary py-3" onclick="document.getElementById('code').value=document.getElementById('code').value + '1';">1</button>
        <button type="button" class="btn btn-outline-secondary py-3" onclick="document.getElementById('code').value=document.getElementById('code').value + '2';">2</button>
        <button type="button" class="btn btn-outline-secondary py-3" onclick="document.getElementById('code').value=document.getElementById('code').value + '3';">3</button>
    </div>
    <div class="row">
        <button type="button" class="btn btn-outline-secondary py-3" onclick="document.getElementById('code').value=document.getElementById('code').value + '4';">4</button>
        <button type="button" class="btn btn-outline-secondary py-3" onclick="document.getElementById('code').value=document.getElementById('code').value + '5';">5</button>
        <button type="button" class="btn btn-outline-secondary py-3" onclick="document.getElementById('code').value=document.getElementById('code').value + '6';">6</button>
    </div>
    <div class="row">
        <button type="button" class="btn btn-outline-secondary py-3" onclick="document.getElementById('code').value=document.getElementById('code').value + '7';">7</button>
        <button type="button" class="btn btn-outline-secondary py-3" onclick="document.getElementById('code').value=document.getElementById('code').value + '8';">8</button>
        <button type="button" class="btn btn-outline-secondary py-3" onclick="document.getElementById('code').value=document.getElementById('code').value + '9';">9</button>
    </div>
    <div class="row">
        <button type="button" class="btn btn-outline-secondary py-3" onclick="document.getElementById('code').value=document.getElementById('code').value + '*';">*</button>
        <button type="button" class="btn btn-outline-secondary py-3" onclick="document.getElementById('code').value=document.getElementById('code').value + '0';">0</button>
    <button type="button" class="btn btn-outline-secondary py-3" onclick="document.getElementById('code').value=document.getElementById('code').value + '#';">#</button>
    </div>
</div>  
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
<?php

?>

                    <button type="button"  class="btn btn-primary" onclick="dtmfmessage();">Envoyer
</button>
      
   
   
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>

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

<script>
$('#reacttel').click(function() {

var val ='false';
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('telephonie.updating') }}",
            method: "POST",
            data: {val:val, _token: _token},
            success: function (data) {
location.reload();
//alert(data);
            }
        });
    return undefined;
});
if(testphone==1)
{
$(window).bind('beforeunload', function(){

var val ='false';
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('telephonie.updating') }}",
            method: "POST",
            data: {val:val, _token: _token},
            success: function (data) {
//alert(data);
            }
        });
    return undefined;
});}
    </script>

<script>
$('.reloadclass').click(function(){
 
                            window.location.reload();
});


    function colorerSeq(string,qy) {



        if(qy!='')
        {

            var caracSp = ['-', '_', '(',')',' '];
            //alert(string);
            // For all matching elements
            $(string).each(function() {


                var hrefString=$(this).html();
                //  alert(hrefString);
                var a1=hrefString.indexOf("\"");
                var b1=hrefString.lastIndexOf("\"");
                hrefString=hrefString.substring(a1+1,b1);

                // Get contents of string
                var myStr = $(this).text();
                //alert(myStr);
                // Split myStr into an array of characters
                myStr = myStr.split("");
                var dejaEn=false;
                // Build an html string of characters wrapped in  tags with classes
                var myContents = "";
                var noniden="";
                var ancien;
                var kol=false;
                for (var i = 0, len = myStr.length; i < len; i++) {
                    //alert(myStr[i]);

                    if(qy[0].toUpperCase()==myStr[i].toUpperCase())
                    {

                        if(!dejaEn)
                        {

                            ancien= myContents ;
                            kol=true;
                            // an_i=i;
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
                                            //b=true;
                                        }

                                        else
                                        {
                                            //alert ("special");

                                            if(caracSp.includes(myStr[i]))
                                            {
                                                //alert ("special");

                                                i++;

                                                if(qy[j].toUpperCase()==myStr[i].toUpperCase())
                                                {
                                                    myContents += '<span class="single-char2 char-' + (i-1) + '">' + myStr[i-1] + '</span>';
                                                    myContents += '<span class="single-char char-' + i + '">' + myStr[i] + '</span>';

                                                    noniden+= '<span class="single-char2 char-' + (i-1) + '">' + myStr[i-1] + '</span>';
                                                    noniden+= '<span class="single-char2 char-' + i + '">' + myStr[i] + '</span>';

                                                    i++;
                                                    //b=true;
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
                                        //i=an_i;
                                        //i++;
                                        kol=false;
                                        ancien= myContents;

                                    }

                                }
                                else
                                {
                                    myContents=ancien;
                                    j=len2;
                                    // i=an_i;
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

                                // i=an_i;
                                // i--;
                                dejaEn=false;

                                // myContents=ancien;
                                // myContents+= noniden;

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

                //alert($(this).html(myContents).text());
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


                        //alert(data);

                        $("#kkk").fadeIn();
                        $("#kkk").html(data);


                        var myStringType=$('.resAutocompRech');
                        // alert( myStringType.html());
                        colorerSeq(myStringType,qy);
                        // alert(myStringType);

                        /* $(document).ready(function() {
                         var myStringType = $('.resAutocompTyoeAct').text();
                         arrayMe(myStringType);
                         });*/



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

        // alert("bonjour");

        $("#search-bar").val($(this).text());
        $("#kkk").fadeOut();


    });


    $('#search-bar').blur(function() {
        $("#kkk").fadeOut();
    });



</script>

<script>


    $('#dpause').click(function() {

        $('#modalconfirm').modal({show: true});

    });


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

<?php    if ($testphoneaff==1) { ?>
<script>
$(document).ready(function() {
$("#dossiercrlibreencours").select2();
 $("#dossiercrrecuencours").select2();
var extensiontel = $('#extensiontel').val();
 var motdepassetel = $('#motdepassetel').val();
//alert(extensiontel);
        webphone_api.parameters['username'] = extensiontel;      // SIP account username
        webphone_api.parameters['password'] = motdepassetel;      // SIP account password (see the "Parameters encryption" in the documentation)        
        webphone_api.parameters['callto'] = '';        // destination number to call
        webphone_api.parameters['autoaction'] = 0;     // 0=nothing (default), 1=call, 2=chat, 3=video call
        webphone_api.parameters['autostart'] = 1;     // start the webphone only when button is clicked
  //webphone_api.parameters['voicerecupload'] = 'ftp://mizutest:NajdaApp2020!@host.enterpriseesolutions.com/voice_CALLER_CALLED_DATETIME.wav'; 
 webphone_api.parameters['transfertype'] = 1;
webphone_api.parameters['voicerecupload'] = 'ftp://ftpmizuuser:Najda2020@192.168.1.249/voice_CALLER_CALLED_DATETIME.wav'; 
webphone_api.parameters['conferencetype'] = 4; 
 //webphone_api.start();
webphone_api.onCallStateChange(function (event, direction, peername, peerdisplayname)

{
peername1=peername;
peername1.replace('+', '');
peername1.replace(' ', '');
if( tabcall.includes(peername1)===true)
{
var index = tabcall.indexOf(peername1);
if (index >= 0) {
  tabcall[index+1]=tabcall[index+1]+1;
}
if (tabcall[index+1]==2){
//alert("ko");
//alert(tabcall);
;
$('table#tableappels tr#'+peername1).remove();
$('table#tableappels1 tr#'+peername1).remove();
var index = tabcall.indexOf(peername1);
if (index >= 0) {
tabcall.splice( index+1, 1 );
  tabcall.splice( index, 1 );
}
//alert(tabcall);

}

}
                if (event === 'setup' && direction == 2 && incall != 1)

                {
tabcall.push(peername1);
tabcall.push(0);
aurlf="<button  style='color:green' href='#' onclick='accept4(\""+peername+"\");'><i class='fas fa-phone-volume'></i>Accepter</button>";
aurlf1="<button  style='color:red' href='#' onclick='disappel(\""+peername+"\");'><i class='fas fa-phone-slash'></i>Rejeter</button>";
aurlf2="<button  style='color:green' href='#' onclick='accept5(\""+peername+"\");'><i class='fas fa-phone-volume'></i>Accepter</button>";
aurlf3="<button  style='color:red' href='#' onclick='disappel(\""+peername+"\");'><i class='fas fa-phone-slash'></i>Rejeter</button>";

peername1=peername;
peername1.replace('+', '');
peername1.replace(' ', '');
document.getElementById('divtableappels1').style.display = 'block';
 $("#tableappels1 tbody").append("<tr id='"+peername1+"'><td>"+peerdisplayname+"</td><td>"+aurlf2+'  '+aurlf3+"</td></tr>");


if(incall!=1)
{

$('#modalappels').modal({show: true});
 $("#tableappels tbody").append("<tr id='"+peername1+"'><td>"+peerdisplayname+"</td><td>"+aurlf+'  '+aurlf1+"</td></tr>");

if(acceptvar===peername)
{
alert(acceptvar);
var _token = $('input[name="_token"]').val();
$.ajax({

                    url:"{{ route('entrees.numaccept')}}",
                    method:"POST",
                    data:'_token='+_token+'&peername='+peername,
                    success:function(data)
                    {
                      
//alert('test');
$('table#tableappels tr#'+data).remove();
$('table#tableappels1 tr#'+data).remove();
                    }
                });
}}
              
  

                }
  
 if (event === 'connected' && direction == 2  && conference!=1 )

                {
if (incall2==1){
document.getElementById("cr2").value=peername;
}else {
document.getElementById("cr1").value=peername;
}

if(incall2!=1)
{
//alert('testconnect');
$('#modalappels').modal('hide');
$('#appelinterfacerecep').modal({show: true});
            $(".modal-body #numencoursrecep").val(peerdisplayname );
var _token = $('input[name="_token"]').val();
$.ajax({

                    url:"{{ route('entrees.detectnom')}}",
                    method:"POST",
                    data:'_token='+_token+'&peerdisplayname='+peername,
                    success:function(data)
                    {
//if(incall != 1){
                         $(".modal-body #nomencoursrecep").val(data );}
                   // }
                });
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
document.getElementById('compterendurecuencours').style.display = 'block';
document.getElementById('mettreenattente').style.display = 'inline-block';
 document.getElementById('couperson').style.display = 'inline-block'; 
document.getElementById('transferapp').style.display = 'inline-block';
document.getElementById('conferenceapp').style.display = 'inline-block';
document.getElementById('calvier3').style.display = 'inline-block';
document.getElementById('status_call').innerHTML="Appel en cours";
document.getElementById('repondre').style.display = 'none';
document.getElementById('pass2').style.display = 'inline-block';
}
else
{
document.getElementById('divtableappels1').style.display = 'none';
    $(".modal-body #numencoursrecep12").val(peerdisplayname );
var _token = $('input[name="_token"]').val();
$.ajax({

                    url:"{{ route('entrees.detectnom')}}",
                    method:"POST",
                    data:'_token='+_token+'&peerdisplayname='+peername,
                    success:function(data)
                    {
//if(incall != 1){
                         $(".modal-body #nomencoursrecep12").val(data );}
                   // }
                });
var minutesLabel12 = document.getElementById("minutes12");
var secondsLabel12 = document.getElementById("seconds12");
var totalSeconds12 = 0;
setInterval(setTime12, 1000);

function setTime12() {
  ++totalSeconds12;
  secondsLabel12.innerHTML = pad12(totalSeconds12 % 60);
  minutesLabel12.innerHTML = pad12(parseInt(totalSeconds12 / 60))+":";
}

function pad12(val12) {
  var valString12 = val12 + "";
  if (valString12.length < 2) {
    return "0" + valString12;
  } else {
    return valString12;
  }
}

document.getElementById('compterendurecuencours12').style.display = 'block';
document.getElementById('racc12').style.display = 'inline-block';
document.getElementById('mettreenattente12').style.display = 'inline-block';
 document.getElementById('couperson12').style.display = 'inline-block'; 
document.getElementById('transferapp12').style.display = 'inline-block';
document.getElementById('conferenceapp12').style.display = 'inline-block';
document.getElementById('calvier12').style.display = 'inline-block';
document.getElementById('status_call12').innerHTML="Appel en cours";

document.getElementById('pass1').style.display = 'inline-block';


}
             }  
if (event === 'connected' && direction == 1 && conference!=1)

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
document.getElementById('compterenduencours').style.display = 'block';
document.getElementById('mettreenattenteenv2').style.display = 'inline-block';
 document.getElementById('coupersonenv2').style.display = 'inline-block'; 
document.getElementById('transferappenv2').style.display = 'inline-block';
document.getElementById('conferenceappenv2').style.display = 'inline-block';
document.getElementById('status_callenv2').innerHTML="Appel en cours";
document.getElementById('calvier').style.display = 'inline-block'; } 
if (event === 'disconnected' && direction == 2 && webphone_api.isincall()!=true && conference!=1)
{
//webphone_api.setline(peername);
           //webphone_api.hangup(true);
peername1=peername;
peername1.replace('+', '');
peername1.replace(' ', '');
$('table#tableappels tr#'+peername1).remove();


incall = 0;
var Countappelsrecus = $('#tableappels tr').length;
//alert(Countappelsrecus);
if(Countappelsrecus===1)
{
$('#modalappels').modal('hide');
}
//alert(webphone_api.isincall ());
$('#appelinterfacerecep').modal('hide');


 //location.reload();


} 
if (event === 'disconnected' && direction == 1 && conference!=1 )
{
// location.reload();
$('#appelinterfaceenvoi2').modal('hide');} 

});
webphone_api.onCdr(function (caller, called, connecttime, duration, direction, peerdisplayname, reason, line)
{
if(duration==0  && conference==1)
{

if(direction==1)
{
 var caller=document.getElementById('numtel1').value;}

webphone_api.setline(caller);
webphone_api.hold(false);
$('#numaconference2').modal('hide');

$('#numaconference').modal('hide');
$('#numatransfer2').modal('hide');
$('#numatransfer').modal('hide');
}
if ( direction == 1)
{
if( (duration!=0 && conference===1)|| (conference!=1))
{
var natureappel = $('#natureappel').val();
 var durationInt = parseInt(duration,10);
var durationSec = Math.floor((durationInt+500)/1000);
var _token = $('input[name="_token"]').val();
 var contenu = document.getElementById('contenucrlibreencours').value;
            var sujet = document.getElementById('sujetcrlibreencours').value;
            var description = document.getElementById('descriptioncrlibreencours').value;
            var dossier = $('#dossiercrlibreencours').val();
            var iduser=document.getElementById('iduser').value;
        if(natureappelconf=='libre')     
{

 var contenu = document.getElementById('contenucrrecuencours').value;
            var sujet = document.getElementById('sujetcrrecuencours').value;
            var description = document.getElementById('descriptioncrrecuencours').value;
            var dossier = $('#dossiercrrecuencours').val();
            var iduser=document.getElementById('iduser').value;
natureappel='libre';}    


$.ajax({

                    url:"{{ route('envoyes.envoyetel')}}",
                    method:"POST",

                  data:'_token='+_token+'&caller='+caller+'&called='+called+'&duration='+durationSec+'&natureappel='+natureappel+'&contenu='+contenu+'&sujet='+sujet+'&description='+description+'&dossier='+dossier+'&iduser='+iduser,
                    success:function(data)
                    {
               if(natureappel==="libre" && conference!=1 )
                      {

                        //alert(data);
                                   document.getElementById('idenvoyetellibre').value=data['id'];  
document.getElementById('contenucrlibre').value=data['contenu'];
document.getElementById('sujetcrlibre').value=data['sujet'];   
document.getElementById('descriptioncrlibre').value=data['description']; 
$('#dossiercrlibre').val(data['dossier']); 
                         $("#appelinterfaceenvoi2").modal('hide');
 $("#appelinterfacerecep").modal('hide');
                         $('#crenduappellibre').modal({show:true});

                      }


                    }
                });
}
}
if ( direction == 2)
{
if( (duration!=0 && conference===1)|| (conference!=1))
{
var durationInt = parseInt(duration,10);
var durationSec = Math.floor((durationInt+500)/1000);
var _token = $('input[name="_token"]').val();

var natureappelrecu = $('#natureappelrecu').val();
 var contenu1 = document.getElementById('contenucrrecuencours12').value;
            var sujet1 = document.getElementById('sujetcrrecuencours12').value;
             var description1 = document.getElementById('descriptioncrrecuencours12').value;
           var dossier1 = $('#dossiercrrecuencours12').val();


var cr1=document.getElementById("cr1").value;
var cr2=document.getElementById("cr2").value;

  var contenu = document.getElementById('contenucrrecuencours').value;
            var sujet = document.getElementById('sujetcrrecuencours').value;
            var description = document.getElementById('descriptioncrrecuencours').value;
             var dossier = $('#dossiercrrecuencours').val();


            var iduser=document.getElementById('iduser').value;

$.ajax({

                    url:"{{ route('entrees.entreetel')}}",
                    method:"POST",
              data:'_token='+_token+'&caller='+caller+'&called='+called+'&duration='+durationSec+'&natureappelrecu='+natureappelrecu+'&contenu='+contenu+'&sujet='+sujet+'&description='+description+'&dossier='+dossier+'&iduser='+iduser+'&contenu1='+contenu1+'&sujet1='+sujet1+'&description1='+description1+'&dossier1='+dossier1+'&incall2='+incall2+'&cr1='+cr1+'&cr2='+cr2,
                  
                    success:function(data)
                    {
if((data['emetteur'].split(' -'))[0]==document.getElementById('cr1').value)
{incall2=-1;


document.getElementById('descriptioncrrecuencours').value='';
document.getElementById('sujetcrrecuencours').value='';
  document.getElementById('dossiercrrecuencours').value='';
  //document.getElementById('dossiercrrecuencours').value='';
 document.getElementById('contenucrrecuencours').value=''; 
$(".modal-body #nomencoursrecep").val('');   
$(".modal-body #numencoursrecep").val(''); 
document.getElementById("minutes1").innerHTML='';
 document.getElementById("seconds1").innerHTML='';
}
else
{incall2=0;

document.getElementById('sujetcrrecuencours12').value='';
document.getElementById('descriptioncrrecuencours12').value='';
  document.getElementById('dossiercrrecuencours12').value='';
 document.getElementById('contenucrrecuencours12').value=''; 
$(".modal-body #nomencoursrecep12").val('');   
$(".modal-body #numencoursrecep12").val(''); 
document.getElementById("minutes12").innerHTML='';
 document.getElementById("seconds12").innerHTML='';}
                    if(natureappelrecu==="librerecu" && incall != 1 && conference!=1)
                      {

                        //alert(data);
                         if(document.getElementById('idenvoyetelrecu').value==='')

                        { 

document.getElementById('idenvoyetelrecu').value=data['id'];
document.getElementById('contenucrrecu').value=data['contenu'];
document.getElementById('sujetcrrecu').value=data['sujet'];   
document.getElementById('descriptioncrrecu').value=data['commentaire']; 
$('#dossiercrrecu').val(data['dossier']);  }  
                         $("#appelinterfacerecep").modal('hide');
                         $('#crenduappelrecu').modal({show:true});

                      }


                    }
                });


}}
});
});
    function Hangup()
        {
    
if(incall2===1)
{
 webphone_api.hangup();
}
else
{
if(webphone_api.isincall())
{webphone_api.setline(-2);
            webphone_api.hangup();
              conference = 0; 
incall = 0;  }
else
{conference = 0;
incall = 0;
$('#appelinterfacerecep').modal('hide');
location.reload();}}
       
        }
  function Hangup21()
        {
    
if(incall2===1)
{
webphone_api.setline(2);
 webphone_api.hangup();
}
else
{
if(webphone_api.isincall())
{webphone_api.setline(2);
            webphone_api.hangup();
              conference = 0; 
incall = 0;  }
else
{conference = 0;
incall = 0;
$('#appelinterfacerecep').modal('hide');
location.reload();}}
       
        }
function pass()
{

webphone_api.setline(1);
webphone_api.hold(false);

webphone_api.setline(2);
webphone_api.hold(true);



}
function pass21()
{

webphone_api.setline(2);
webphone_api.hold(false);

webphone_api.setline(1);
webphone_api.hold(true);



}
function disappel(peername)
        {
          webphone_api.setline(peername);
          webphone_api.hangup(true);
incall = 0;
            
        }
function accept()
        {
            document.getElementById('natureappelrecu').value='librerecu';  
            webphone_api.accept();
incall = 1;
            
        }
function accept4(peername)
        {


            document.getElementById('natureappelrecu').value='librerecu';  
webphone_api.setline(peername);
            webphone_api.accept(true);
incall = 1;
acceptvar = peername ;
            
        }
function accept5(peername)
        {






            document.getElementById('natureappelrecu').value='librerecu';  
webphone_api.setline(peername);
if (webphone_api.getline()==1){
webphone_api.setline(2);
    webphone_api.hold(true);
}else {
webphone_api.setline(1);
    webphone_api.hold(true);
}


webphone_api.setline(peername);
            webphone_api.accept(true);
incall = 1;
incall2 = incall2+1;

acceptvar = peername ;
            
        }
    function transfer()
        {
natureappelconf='libre';
conference=1;

numtrans=$('#numatrans').val();
//numtrans.toString();
//alert(numtrans);
webphone_api.setline(1);
            webphone_api.hold(true);
webphone_api.setline(2);
            webphone_api.call(numtrans);
//alert("OK");
        }
function transfer7()
{
webphone_api.setline(1);
            webphone_api.hold(false);
           webphone_api.setline(2);
   webphone_api.hangup();
}
function transfer8()
{
conference =0;


webphone_api.setline(1);
            webphone_api.transfer(numtrans);
$('#numatransfer').modal('hide');}
function Conference8()
        {
natureappelconf='libre';
conference=1;
numtrans=$('#numaconf').val();
numtrans.toString();
//alert(numtrans);
webphone_api.setline(1);
            webphone_api.hold(true);
webphone_api.setline(2);
            webphone_api.call(numtrans);

//alert("OK");
        }
 function Conference9()
        {

//numtrans=$('#numaconf2').val();

webphone_api.setline(1);
            webphone_api.hold(false);
            webphone_api.conference(numtrans);
$('#numaconference').modal('hide');

//alert("OK");
        }
 function Conference10()
        {

//numtrans=$('#numaconf2').val();

webphone_api.setline(1);
            webphone_api.hold(false);
           webphone_api.setline(2);
if(webphone_api.isincall!=true)
{
            webphone_api.hangup();}
$('#numaconference').modal('hide');

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
if(incall != 1){
                         $(".modal-body #nomencours2").val(data );}
                    }
                }); 


  $("#faireappel1").modal('hide');
                
 
 
                webphone_api.call(peerdisplayname);}

//testiscall();




}

           function Hangup2()
        {
         if(webphone_api.isincall())
{webphone_api.setline(-2);
            webphone_api.hangup();
              conference = 0;   }
else
{conference = 0;
$('#appelinterfaceenvoi2').modal('hide');
location.reload();}
            
        }
    function transfer2()
        {
conference=1;

numtrans=$('#numatrans2').val();
//numtrans.toString();
//alert(numtrans);
webphone_api.setline(1);
            webphone_api.hold(true);
webphone_api.setline(2);
            webphone_api.call(numtrans);
//alert("OK");
        }
function dtmfmessage()
{

msg=document.getElementById('code').value.toString();
//alert(msg);
webphone_api.dtmf(-2,msg);
//alert('succes');
}
function dtmfmessage3()
{

msg=document.getElementById('code3').value.toString();
//alert(msg);
webphone_api.dtmf(-2,msg);
//alert('succes');
}
function transfer4()
{
webphone_api.setline(1);
            webphone_api.hold(false);
           webphone_api.setline(2);
   webphone_api.hangup();
}
function transfer3()
{
conference =0;


webphone_api.setline(1);
            webphone_api.transfer(numtrans);
$('#numatransfer2').modal('hide');}
function Conference2()
        {
conference=1;
numtrans=$('#numaconf2').val();
numtrans.toString();
//alert(numtrans);
webphone_api.setline(1);
            webphone_api.hold(true);
webphone_api.setline(2);
            webphone_api.call(numtrans);

//alert("OK");
        }
 function Conference3()
        {

//numtrans=$('#numaconf2').val();

webphone_api.setline(1);
            webphone_api.hold(false);
            webphone_api.conference(numtrans);
$('#numaconference2').modal('hide');

//alert("OK");
        }
 function Conference4()
        {

//numtrans=$('#numaconf2').val();

webphone_api.setline(1);
            webphone_api.hold(false);
           webphone_api.setline(2);
if(webphone_api.isincall!=true)
{
            webphone_api.hangup();}
$('#numaconference2').modal('hide');

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

</script>
<?php    } ?>
