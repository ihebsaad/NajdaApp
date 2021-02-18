@extends('layouts.mainlayout')
<?php 
use App\User ; 
use App\Prestataire ;
use App\Template_doc ; 
use App\Document ; 
use App\Client;
use App\ClientGroupe;
use App\Adresse;
use App\Mission;
use App\Facture;
use App\Tag;
use App\Rubrique;
use App\Parametre;
?>
<?php use \App\Http\Controllers\PrestationsController;
     use  \App\Http\Controllers\PrestatairesController;
     use  \App\Http\Controllers\ClientsController;
use  \App\Http\Controllers\DossiersController ;
use  \App\Http\Controllers\EnvoyesController ;
use  \App\Http\Controllers\EntreesController ;
use \App\Http\Controllers\UsersController;
use \App\Http\Controllers\TagsController;
function formatBytes($size){
    $base = log($size) / log(1024);
    $suffix = array("", " KO", " MO", " GO", " TO");
    $f_base = floor($base);
    return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
}
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
?>

<link rel="stylesheet" href="{{ asset('public/css/timelinestyle.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('public/css/timeline.css') }}" type="text/css">
<!--select css-->
<link href="{{ asset('public/js/select2/css/select2.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('public/js/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>
<!--
<link href="//demo.chandra-admin.com/assets/vendors/Buttons/css/buttons.css" rel="stylesheet">
<link href="//demo.chandra-admin.com/assets/vendors/hover/hover.css" rel="stylesheet">
<link href="//demo.chandra-admin.com/assets/css/custom_css/advbuttons.css" rel="stylesheet">
-->
                <!-- Include the webphone_api.js to your webpage -->

<script src="{{ asset('public/webphone/najdaapp/webphone/webphone_api.js') }}"></script>


@section('content')
    @if(session()->has('AffectDossier'))
        <div class="alert alert-success">
            <center> <h4>{{ session()->get('AffectDossier') }}</h4></center>
        </div>
    @endif

<div class="row">
    <div class="col-md-3">
<?php  // $doss=  DossiersController::DossiersActifs(); echo json_encode($doss) ; ?>
        <h4 style="font-weight:bold;"><a  href="{{action('DossiersController@fiche',$dossier->id)}}" ><?php echo   $dossier->reference_medic .' - '.  DossiersController::FullnameAbnDossierById($dossier->id);?> </a></h4>
       <h4 ><a style="font-weight:bold;color:#bcdf94!important" href="{{action('DossiersController@details',$dossier->id)}}" >Détails du dossier </a></h4>
    </div>
    <div class="col-md-2">
        <b>Statut:</b>
        <?php $statut=$dossier->current_status;
        if ($statut =='actif' || $statut =='inactif' ){
            echo '<b style="font-size:20px">Ouvert</b> <a style="font-size:13px" title="changer le statut" href="#" data-toggle="modal" data-target="#FermerDoss"> (Clôturer)</a>';
        }
        if($statut=='Cloture'){
            echo '<b style="font-size:20px">Clôturé</b> <a style="font-size:13px" title="changer le statut" href="#" data-toggle="modal" data-target="#OuvrirDoss"> (Ouvrir)</a>';
        }
        ?>

        <input type="hidden" id="dossier" value="<?php echo $dossier->id; ?>">
        <input type="hidden" id="typedossier" value="<?php echo $dossier->type_affectation; ?>">
<?php
$garanties=DB::table('garanties_assure')->where('id_assure',$dossier->ID_assure)->count();
?>
 <input type="hidden" id="countgarantie" value="<?php echo $garanties; ?>">
    </div>
     <div class="col-md-2">

         <?php
         $idagent=$dossier->user_id;
         $CurrentUser = auth()->user();
         $iduser=$CurrentUser->id;
         // les agents ne voient pas l'aaffectation - à vérifier
         if (Gate::check('isAdmin') || Gate::check('isSupervisor') || ( $idagent==$iduser) ) { ?>

         <?php if ((isset($dossier->affecte)) && (($dossier->affecte>0))) { ?>
        <b>Affecté à:</b>
        <?php
             if($dossier->affecte >0) {$agentname = User::where('id',$dossier->affecte)->first();}else{$agentname=null;}
        if ((Gate::check('isAdmin') || Gate::check('isSupervisor') || ( $idagent==$iduser)  )  )
            { echo '<a href="#" data-toggle="modal" data-target="#attrmodal">';
              }
             if( ($dossier->affecte >0)){ echo $agentname['name'].' '.$agentname['lastname'];}
             if(Gate::check('isAdmin') || Gate::check('isSupervisor') || ( $idagent==$iduser) )
            { echo '</a>';}
        ?>
        <?php }
        else
        {
            if($statut=='Cloture'){ } else {
            if ((Gate::check('isAdmin') || Gate::check('isSupervisor')))
            {echo '<a style="color:#FD9883" href="#" data-toggle="modal" data-target="#attrmodal">merci cliquer pour affecter</a>';}
            else
            {echo '<b style="color:#FD9883">non affecté</b>';}
            }
        } ?>

      <?php  } else{
             if($dossier->affecte >0) {$agentname = User::where('id',$dossier->affecte)->first();}else{$agentname=null;}
             echo 'Affecté à : ';
               echo $agentname['name'].' '.$agentname['lastname'];
             }?>
    </div>
    <div class="col-md-5" style="text-align: right;padding-right: 35px">
        <div class="page-toolbar">

          <?php  if( $statut!='Cloture'){ ?>
        <div class="btn-group">
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-envelope"></i> Email <i class="fa fa-angle-down"></i>
                </button>
                <ul class="dropdown-menu pull-right">
                    <li>
                        <a href="{{route('emails.envoimail',['id'=>$dossier->id,'type'=> 'client','prest'=> 0])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                            Au client </a>
                    </li>
                    <li>
                        <a href="{{route('emails.envoimail',['id'=>$dossier->id,'type'=> 'prestataire','prest'=> 0])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                            À l'intervenant </a>
                    </li>
                    <li>
                        <a href="{{route('emails.envoimail',['id'=>$dossier->id,'type'=> 'assure','prest'=> 0])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                            À l'assuré </a>
                    </li>

                </ul>
            </div>

            <div class="btn-group">
                <!--<button type="button" class="btn btn-default" id="sms">
                    <a style="color:black" href="{{action('EmailController@sms',$dossier->id)}}"> <i class="fas fa-sms"></i> SMS</a>
                </button>-->

                <button type="button"  class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-sms"></i> SMS <i class="fa fa-angle-down"></i>

                </button>


                <ul class="dropdown-menu pull-right">
                    <li>
                        <a href="{{route('emails.sms',['id'=>$dossier->id,'type'=> 'client','prest'=> 0])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                            Au client </a>
                    </li>
                    <li>
                        <a href="{{route('emails.sms',['id'=>$dossier->id,'type'=> 'prestataire','prest'=> 0])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                            À l'intervenant </a>
                    </li>
                    <li>
                        <a href="{{route('emails.sms',['id'=>$dossier->id,'type'=> 'assure','prest'=> 0])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                            À l'assuré  </a>
                    </li>
                    <li>
                        <a href="{{route('emails.sms',['id'=>$dossier->id,'type'=> 'libre','prest'=> 0])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                            Libre </a>
                    </li>

                </ul>
            </div>

            <div class="btn-group">
                <button type="button" id="newfax" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-fax"></i> Fax <i class="fa fa-angle-down"></i>

                </button>


                <ul class="dropdown-menu pull-right">
                    <li>
                        <a href="{{route('emails.envoifax',['id'=>$dossier->id,'type'=> 'client','prest'=> 0])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                            Au client </a>
                    </li>
                    <li>
                        <a href="{{route('emails.envoifax',['id'=>$dossier->id,'type'=> 'prestataire','prest'=> 0])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                            À l'intervenant </a>
                    </li>
                    <li>
                        <a href="{{route('emails.envoifax',['id'=>$dossier->id,'type'=> 'libre','prest'=> 0])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                            Libre </a>
                    </li>

                </ul>
            </div>

            <div class="btn-group">
                <button   type="button"   class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <b><i class="fa fa-phone"></i>  Tél</b><i class="fa fa-angle-down"></i>
                </button>

                <ul class="dropdown-menu pull-right">
                    <li>
                        <a  data-toggle="modal" data-target="#faireappel" onclick="ShowNumsCc();" style="font-size:17px;height:30px;margin-bottom:5px;">
                            Au Client  </a>
                    </li>
                    <li>
                        <a data-toggle="modal" data-target="#faireappel" onclick="ShowNumsInt();" style="font-size:17px;height:30px;margin-bottom:5px;">
                            À l'intervenant </a>
                    </li>
                    <li>
                        <a data-toggle="modal" data-target="#faireappel" onclick="ShowNumsAss();" style="font-size:17px;height:30px;margin-bottom:5px;">
                            À l'assuré   </a>
                    </li>

                </ul>
            </div>

            <div class="btn-group">
                <button id="phoneicon"  type="button" class="btn btn-default"  >
                    <i class="fa fa-comment-dots"></i>
                    C R

                </button>
            </div>

        </div>
            <?php  } ?>

        </div>
    </div>

</div>

 <section class="content form_layouts">


        <div class="container-fluid">
<br>
            <div class="row" style="margin-top:10px">
                <div class="col-lg-12">
                    <ul class="nav  nav-tabs" style="font-size:12px!important;">
 
                        <li class="nav-item ">
                            <a class="nav-link  " href="#tab2" data-toggle="tab">
                               <i class="fas a-lg fa-exchange-alt"></i>  Communications
                            </a>
                        </li>
                        <li class="nav-item active show">
                            <a class="nav-link" href="#tab3" data-toggle="tab">
                                <i class="fas fa-lg  fa-ambulance"></i>  Prestations
                            </a>
                        </li>
                        <li class="nav-item  ">
                            <a class="nav-link" href="#tab4" data-toggle="tab" style="font-size:10px">
                                <i class="fas  fa-lg fa-users"></i>  Prestataires et Intervenants
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tab5" data-toggle="tab">
                                <i class="fas  fa-lg fa-file-archive"></i>  Attachements
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tabtags" data-toggle="tab">
                                <i class="fas fa-tags"></i>  TAG
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tab6" data-toggle="tab">
                                <i class="fas fa-lg fa-file-word"></i>  Docs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tab7" data-toggle="tab">
                                <i class="fas fa-file-import"></i>  OM
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tab8" data-toggle="tab" onclick=";showinfos81();hideinfos82()">
                                <i class="fas fa-tasks"></i> Missions Plateau
                            </a>
                        </li>


                        <li class="nav-item ">
                            <a class="nav-link  " href="#tab9" data-toggle="tab">
                                <i class="fas a-lg fa-file-invoice"></i>  Factures
                            </a>
                        </li>
					<?php  if(Gate::check('isAdmin') || Gate::check('isFinancier') ) {  ?>
						  <li class="nav-item ">
							 <a class="nav-link  " target="_blank" href="{{action('DossiersController@fermeture',$dossier->id)}}" >
                             <i class="fas a-lg fa-file-invoice"></i>  Contrats 
							</a>
                        </li>
					<?php } ?>
                    </ul>

                </div>
            </div>
            <div class="tab-content mar-top">
            
            <div id="tab2" class="tab-pane fade">
                <?php $idagent=$dossier->user_id; $creator=UsersController::ChampById('name',$idagent).' '.UsersController::ChampById('lastname',$idagent);
               if($dossier->created==null){ $createdat=  date('d/m/Y H:i', strtotime($dossier->created_at ));}else{
                $createdat=  date('d/m/Y H:i', strtotime($dossier->created ));
                }
                ;?>
                Dossier créé par <B><?php echo $creator ;?></B> - Date :<?php echo $createdat ?>

                <section id="timeline">

                            @if($communins)
                            @foreach($communins as $communin)

                            <article>
                                <div class="inner  <?php if ($communin['boite']==1){echo 'sent ';}?>">
                                <span class="date">

                                 <?php if($communin['type']=="email") {?>

                                    <img  src="{{ asset('public/img/mail.png') }}"  width="60" height="60">

                                    <?php }?><?php if($communin['type']=="sms") {?>
                                    <img  src="{{ asset('public/img/sms.png') }}"  width="60" height="60">
                                    <?php }?>
                                    <?php if($communin['type']=="whatsapp") {?>
                                    <img  src="{{ asset('public/img/whatsapp.png') }}"  width="60" height="60">

                                    <?php }?>
                                    <?php if($communin['type']=="tel") {?>
                                    <img  src="{{ asset('public/img/phone.png') }}"  width="60" height="60">

                                    <?php }?>

                                    <?php if($communin['type']=="fax") {?>
                                    <img  src="{{ asset('public/img/fax.png') }}"  width="60" height="60">
                                    <?php }?>

                                    <?php if($communin['type']=="rendu") {?>
                                    <img  src="{{ asset('public/img/rendu.png') }}"  width="60" height="60">
                                    <?php }?>
                                </span>
                                    <h2 style="font-size: 16px"><?php echo $communin['sujet']; ?></h2>
                                    <p class="overme">

                                        <?php if ($communin['boite']==0 || $communin['boite']==null)
                                        { 

                                        	if($communin['type']=="tel") {


$emetteur= explode(' ', $communin['emetteur']);
$adressecomm=Adresse::where("champ",$emetteur)->first();
                                         echo '<span class="commsujet" style="font-size:12px"><B>Emetteur: </B>'. $communin['emetteur']."(".$adressecomm['prenom']." ".$adressecomm['nom'].")".'</span>';

                                       echo '<span class="cd-date">'.
                                       $communin['reception'] .'<i class="fa fa-fw fa-clock-o"></i><br>
                                        </span>';
                                        }
                                      else
                                      {
 echo '<span class="commsujet" style="font-size:12px"><B>Emetteur: </B>'. $communin['emetteur'].'</span>';

                                       echo '<span class="cd-date">'.
                                       $communin['reception'] .'<i class="fa fa-fw fa-clock-o"></i><br>
                                        </span>';

                                      }



                                    }
                                        ?>
                                             <?php if ($communin['boite']==1 && $communin['par']>0 )
                                            {
                                           echo '<b>Emetteur : </b>'. UsersController::ChampById('name',$communin['par']) .' '.  UsersController::ChampById('lastname',$communin['par']);
                                            } ?>

                                            <?php if ($communin['commentaire']!=null)
                                        {  echo '<br><span style="font-size:12px"><B>Descrip: </B>'. $communin['commentaire'].'</span><br>';
                                        }
                                        ?>
                                        <?php if ($communin['boite']==1)
                                        {  echo '<br><span style="font-size:12px"><B>Descrip: </B>'. $communin['description'].'</span><br>';
                                        }
                                        ?>

                                        <span class="cd-date">

                                            <?php echo /* date('d/m/Y H:i', (*/$communin['reception']/*))*/ ; ?> <i class="fa fa-fw fa-clock-o"></i><br>


                                        </span>
                                    <?php if($communin['type']=="tel" && $communin['par']!==null) {    ?>
<br>
                                         <span class="cd-media">
                                            <audio style="width:200px;"controls>
  <source src="<?php  echo  $communin["path"] ; ?>" type="audio/wav">
 Your browser does not support the audio element.
</audio>
</span>
<br>
 <?php }?>
                                        <?php if($communin['type']=="email") {
                                            if($communin['nb_attach']>0) { ?> <span style="font-size:12px"><i class="fa fa-fw fa-paperclip"></i>(<?php echo $communin['nb_attach'];?>) Attachements</span><br>
                                        <?php } } ?>

                                        <?php
                                        if ($communin['boite']==1)
                                        {
                                        ?>
                                        <a class="btn btn-md btn-default" style="margin-top: 10px;" href="{{action('EnvoyesController@view', $communin['id'])}}"> Voir les détails</a><br>

                                        <?php
                                        }
                                        else { ?>
                                        <a class="btn btn-md btn-default" style="margin-top: 10px;" href="{{action('EntreesController@show', $communin['id'])}}"> Voir les détails</a><br>

                                        <?php } ?>

                                    </p>
                                </div>
                            </article>

                            @endforeach
                            @endif

                        </section>

                <?php // echo '<br>test entrees <br>'.json_encode($entrees1) .' <br> Envoyés <br>'.json_encode($envoyes1);?>
            </div>
            <div id="tab3" class="tab-pane fade  active in">
                <ul class="nav  nav-tabs">

                    <li class="nav-item active">
                        <a class="nav-link active show" href="#tab32" data-toggle="tab"  onclick=";showinfos2();hideinfos();hideinfos3();">
                            <i class="fas fa-lg  fa-user-md"></i>  Recherche des Prestataires
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tab33" data-toggle="tab"  onclick="showinfos3();hideinfos();hideinfos2();">
                            <i class="fas  fa-lg fa-users"></i>  Optimiseur
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link   " href="#tab34" data-toggle="tab"  onclick="showinfos();hideinfos2();hideinfos3();">
                            <i class="fas fa-lg  fa-ambulance"></i>  Prestations
                        </a>
                    </li>

                </ul>
                <div id="tab32" class="tab-pane fade active in  ">
                  <br>
<?php
                    if (isset($_GET['typeprest'])){$typeprest=$_GET['typeprest']; } else{$typeprest='';}
                    if (isset($_GET['specialite'])){$specialite=$_GET['specialite']; } else{$specialite='';}
                    if (isset($_GET['gouvernorat'])){$gouvernorat=$_GET['gouvernorat']; } else{$gouvernorat='';}
                    if (isset($_GET['typeprest'])){$typeprest=$_GET['typeprest']; } else{$typeprest='';}
                    if (isset($_GET['ville'])){$ville=$_GET['ville']; } else{$ville='';}
?>
<form  accept-charset="utf-8" action="{{route('searchprest')}}">
                    <div class="form-group " >
                        <label>Type de prestations</label>
                        <div class=" row  ">
                            <select class="itemName form-control col-lg-12  " required style="width:400px" name="typeprest"    id="typeprest2">
                                <option></option>
                                @foreach($typesprestations as $aKey)
                                    <option   <?php if($typeprest==$aKey->id){echo 'selected="selected"';}?>  value="<?php echo $aKey->id;?>"> <?php echo $aKey->name;?></option>
                                @endforeach

                            </select>

                        </div>
                    </div>

                    <div class="form-group ">
                        <div class="row">
                            <label>Spécialité</label>
                        </div>
                        <div class="row">
                            <select class="form-control  col-lg-12 " style="width:400px" name="specialite"     id="specialite2">
                                <option value="0"></option>
                                @foreach($specialites as $sp)
                                         <?php     $specialite_tprestation = DB::table('specialites_typeprestations')
                                         ->where([
                                            ['specialite', '=', $sp->id],
                                            ])->first();
                                         if (isset($specialite_tprestation->type_prestation))
                                            {$stprest = $specialite_tprestation->type_prestation;
                                            ?>
                                            <option  class="tprest2  tprest2-<?php echo $stprest;?>" value="<?php echo $sp->id;?>"> <?php echo $sp->nom;?></option>
                                        <?php } else { ?>
                                                <option  class="tprest2" value="<?php echo $sp->id;?>"> <?php echo $sp->nom;?></option>
                                        <?php
                                        } ?>
                                        @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label>Gouvernorat de couverture</label>
                        <div class="row">
                            <select class="form-control  col-lg-12 " style="width:400px" name="gouvernorat"   required   id="gouvcouv2">
                                <option></option>
                                @foreach($gouvernorats as $aKeyG)
                                    <option  <?php if($gouvernorat==$aKeyG->id){echo 'selected="selected"';}?>  value="<?php echo $aKeyG->id;?>"> <?php echo $aKeyG->name;?></option>
                                @endforeach

                            </select>
                        </div>
                    </div>

                    <div class="form-group ">
                        <div class="row">
                            <label>Ville</label>
                        </div>
                        <div class="row" style=";margin-bottom:10px;"><style>.algolia-places{width:80%;}</style>
                        </div>
                        <input class="form-control" value="<?php echo $ville; ?>" style="padding-left:5px" type="text" placeholder="toutes"  name="ville" id="villepr2" />
                        <input class="form-control" style="padding-left:5px;" type="hidden" name="postal" id="villecode2" />

                    </div>
                    <script>
                        (function() {
                            var placesAutocomplete3 = places({
                                appId: 'plCFMZRCP0KR',
                                apiKey: 'aafa6174d8fa956cd4789056c04735e1',
                                container: document.querySelector('#villepr2'),
                            });
                            placesAutocomplete3.on('change', function resultSelected(e) {
                                document.querySelector('#villecode2').value = e.suggestion.postcode || '';
                            });
                        })();
                    </script>

    <input type="hidden" name="dossier" value="<?php echo $dossier->id; ?>"/>

    <input type="submit" value="rechercher" class="btn btn-success" style="width:150px"/>

    <?php if (isset($datasearch)) { ?>
    <div class="row" style="margin-top:15px">  <label>Liste des Prestataires trouvés:</label>
    </div>
    <table class="table table-striped" id="mytable1" style="width:100%">
    <thead>
    <tr id="headtable">
    <th style="width:30%">Prestataire</th>
    <th style="width:20%;font-size:14px;">Type de prestations</th>
    <th style="width:15%">Gouvernorats</th>
    <th style="width:10%">Ville</th>
    <th style="width:15%">Spécialités</th>
    <th style="width:10%">Actions</th>
    </tr>
    </thead>
        <tbody>
        <?php  foreach($datasearch as $do)
        {  $id= $do['prestataire'];
        if($id >0)
        {
        $prestataire = Prestataire::find($id);
        if($prestataire != null)
        {
        $villeid=intval($do['ville_id']);
        if (isset($villes[$villeid]['name']) ){$ville=$villes[$villeid]['name'];}
        else{$ville=$do['ville'];}
        $gouvs=  PrestatairesController::PrestataireGouvs($id);
        $typesp=  PrestatairesController::PrestataireTypesP($id);
        $specs=  PrestatairesController::PrestataireSpecs($id);
        $tels=array();
        $tels =   Adresse::where('nature', 'telinterv')
        ->where('parent',$id)
        ->get();
        ?>

        <tr style="border-top:1px solid black">
            <td style="font-size:14px;width:30%"><a href="{{action('PrestatairesController@view', $id)}}" ><?php echo '<i>'.$prestataire['civilite'] .'</i> <b>'. $prestataire['name'] .'</b> '.$prestataire['prenom']; ?></a>  </td>
            <td style="font-size:12px;width:20%"><?php     foreach($typesp as $tp){echo PrestatairesController::TypeprestationByid($tp->type_prestation_id).',  ';}?></td>
            <td style="font-size:12px;width:15%"><?php foreach($gouvs as $gv){echo PrestatairesController::GouvByid($gv->citie_id).',  ';}?></td>
            <td style="font-size:12px;width:10%"><?php echo $ville; ?></td>
            <td style="font-size:12px;width:15%"><?php   foreach($specs as $sp){echo  PrestatairesController::SpecialiteByid($sp->specialite).',  ';}?></td>
            <?php $nomc =addslashes($prestataire['name'].' '.$prestataire['prenom']); ?>
            <td style="font-size:13px;width:10%">  <button onclick="init('<?php echo $id;?>','<?php  echo  $nomc ;?>')" style="margin-bottom:10px;margin-top:10px" type="button" data-toggle="modal"  data-target="#openmodalprest" class="btn  btn-primary"><i class="far fa-save"></i> + Prestation</button>

            </td>

        </tr>

        <?php
        foreach ($tels as $tel) {
        echo ' <tr>
            <td colspan="2" style="padding-right:8px;"><i class="fa fa-phone"></i> ' . $tel->champ . '</td>
            <td colspan="2" style="padding-right:8px;">' . $tel->remarque . '</td>';?>
        <?php if($tel->typetel=='Mobile') {
        echo '<td colspan="2"><a onclick="setTel(this);" class="'. $tel->champ.'" style="margin-left:5px;cursor:pointer" data-toggle="modal"  data-target="#sendsms" ><i class="fas fa-sms"></i> Envoyer un SMS </a></td>';
        } else
        { echo  '<td colspan="2"></td>';}
        echo '</tr> ';
        }
        ?>
        <?php }
        }
        }
        ?>

        </tbody>
    </table>

  <?php }  ?>
</form>
                     </div><!--32-->


                 <div id="tab33" class="tab-pane fade ">
                    <br> <!-- <button style="float:right;margin-top:10px;margin-bottom: 15px;margin-right: 20px" id="addpres" class="btn btn-md btn-success"   data-toggle="modal" data-target="#create"><b><i class="fas fa-plus"></i> Ajouter une Prestation</b></button>-->

                     <h3>Ajouter une nouvelle prestation</h3><br>
                     <?php
                     $users=UsersController::ListeUsers();
                     $CurrentUser = auth()->user();
                     $iduser=$CurrentUser->id;
                     ?>
                     <div class="form-group">

                         <form id="addpresform" novalidate="novalidate">
                             {{ csrf_field() }}


                             <div class="form-group " >
                                 <label>Type de prestations *</label>
                                 <div class=" row  ">
                                     <select class="itemName form-control col-lg-12  " style="width:400px" name="itemName"    id="typeprest">
                                         <option></option>
                                         @foreach($typesprestations as $aKey)
                                             <option     value="<?php echo $aKey->id;?>"> <?php echo $aKey->name;?></option>
                                         @endforeach

                                     </select>

                                 </div>
                             </div>

                             <div class="form-group ">
                                 <div class="row">
                                     <label>Spécialité </label>
                                 </div>
                                 <div class="row">
                                     <select class="form-control  col-lg-12 " style="width:400px" name="specialite"    id="specialite">
                                         <option value="0"></option>
                                         @foreach($specialites as $sp)
                                         <?php     $specialite_tprestation = DB::table('specialites_typeprestations')
                                         ->where([
                                            ['specialite', '=', $sp->id],
                                            ])->first();
                                         if (isset($specialite_tprestation->type_prestation))
                                            {$stprest = $specialite_tprestation->type_prestation;
                                            ?>
                                            <option  class="tprest  tprest-<?php echo $stprest;?>" value="<?php echo $sp->id;?>"> <?php echo $sp->nom;?></option>
                                        <?php } else { ?>
                                                <option  class="tprest" value="<?php echo $sp->id;?>"> <?php echo $sp->nom;?></option>
                                        <?php
                                        } ?>
                                        @endforeach
                                     </select>
                                 </div>
                             </div>

                             <div class="form-group ">
                                 <label>Gouvernorat de couverture *</label>
                                 <div class="row">
                                     <select class="form-control  col-lg-12 " style="width:400px" name="gouv"    id="gouvcouv">
                                         <option></option>
                                         @foreach($gouvernorats as $aKeyG)
                                             <option   value="<?php echo $aKeyG->id;?>"> <?php echo $aKeyG->name;?></option>
                                         @endforeach

                                     </select>
                                 </div>
                             </div>

                             <div class="form-group ">
                                 <div class="row">
                                     <label>Ville</label>
                                 </div>
                                 <div class="row" style=";margin-bottom:10px;"><style>.algolia-places{width:80%;}</style></div>
                                 <input class="form-control" style="padding-left:5px" type="text"  id="villepr"  placeholder="toutes"/>
                                 <input class="form-control" style="padding-left:5px;" type="hidden"  id="villecode" />

                             </div>
                             <script>
                                 (function() {
                                     var placesAutocomplete2 = places({
                                         appId: 'plCFMZRCP0KR',
                                         apiKey: 'aafa6174d8fa956cd4789056c04735e1',
                                         container: document.querySelector('#villepr'),
                                     });
                                     placesAutocomplete2.on('change', function resultSelected(e) {
                                         document.querySelector('#villecode').value = e.suggestion.postcode || '';
                                     });
                                 })();
                             </script>


                             <div class="form-group row" >
                                 <label class=" control-label">Date de prestation <span class="required" aria-required="true"> * </span></label>
                                 <div class="row">
                                     <input style="width:200px;" value='<?php echo date('d/m/Y'); ?>' class="form-control datepicker-default " name="pres_date" id="pres_date" data-required="1" required="" aria-required="true">
                                 </div>
                             </div>
							<input type="hidden" value="1"  id="start" />
                             <div class="row">
                                 <span class="btn btn-success" id="rechercher" >Rechercher <i class="fa fa-loop"></i></span>
                             </div>
                             <!--
                                                         <div style="align:center;text-align:center">
                                                             <span style="align:center" id="check" class="btn btn-danger">Chercher des prestataires</span>
                                                         </div>
                             -->
                             <div id="data" ><style>#data b{text-align:center;}</style>

                             </div>
                             <!--                         <a href="#" class="hvr-shrink button button-3d button-success button-rounded" style="display:none;margin-top:40px;margin-bottom:30px" id="choisir"><i class="fa fa-check"></i>  Sélectionner</a>-->
                             <button style="display:none;margin-top:50px;margin-bottom:50px" id="showNext" type="button" class="hvr-wobble-horizontal btn btn-lg btn-labeled btn-info">
                                 Suivant
                                 <span class="btn-label" style="left: 13px;">
                                                    <i class="fa fa-chevron-right"></i>
                                                </span>
                             </button>

                             <div id="termine" style="display:none;height:120px;align:center;">
                                 <center><br>   Fin de la liste.<br></center>

                                 <button style="margin-bottom: 40px" id="essai2" type="button" class="btn btn-labeled btn-default btn-lg hvr-wobble-to-top-right right1">
                                                <span class="btn-label">
                                                    <i class="fa fa-refresh"></i>
                                                </span>
                                     Réessayez
                                 </button>
                             </div>
                             <input type="hidden" id="selected" value="0">
                             <input type="hidden" id="par" value="<?php echo $iduser;?>">
                             <div class="row">
                                 <div class="col-md-4">

                                     <button style="display:none;margin-botom:10px;margin-top:20px;font-size:14px" type="button" id="add2" class="btn btn-lg btn-primary"><i class="far fa-save"></i> Enregister la prestation</button>
                                 </div>
                                 <div class="col-md-4"  id="add2prest" style="display:none" >
                                     <label>Prestataire sélectionné :</label><br>
                                     <select style="width:300px;margin-top:10px;margin-bottom:10px;margin-left:10px" disabled id="selectedprest"  class="form-control col-lg-9 " value=" ">
                                         <option></option>
                                         @foreach($prestataires as $prest)
                                             <option    value="<?php echo $prest->id;?>"> <?php echo $prest->name;?> <?php echo $prest->prenom;?></option>
                                         @endforeach
                                     </select>
                                 </div>
                                 <div class="col-md-4"></div>
                             </div>

                             <div class="row">
                                 <div class="  form-group"  id="prestation"   >
                                   <div class="col-md-4">
                                       <button style="display:none;margin-bottom:10px;font-size:14px" type="button" id="valide" class="btn btn-lg btn-success"><i class="fa fa-check"></i> Valider la prestation</button>
                                       <input type="hidden" value="0" id="firstsaved" />
                                   </div>
                                     <div class="col-md-4"  style="display:none;padding-left:15px;"  id="validation" >
                                         <label>ou bien Prestation non effectuée ? Raison:</label>

                                         <select class="form-control" id="statutprest" >
                                             <option></option>
                                             <option    value="nonjoignable">Non Joignable </option>
                                             <option    value="nondisponible">Non Disponible </option>
                                             <option    value="autre">Autre </option>
                                         </select>

                                     </div>
                                     <div class="col-md-4" >
                                         <textarea type="text" style="display:none;height:60px" class="form-control" Placeholder="Détails"  id="detailsprest" ></textarea>
                                      </div>
                                     <input type="hidden"  id="idprestation" value="0" />


                                 </div>
                             </div>

                             <div class="row">
                             </div>


                             <input id="dossier" name="dossier" type="hidden" value="{{ $dossier->id}}">

                         </form>
                     </div>
                </div>
                <div id="tab34" class="tab-pane fade ">
                    <br><label style="font-weight:bold;color:#FD9883 ">Prestation non effectuée</label><br>
                    <!--  <span style="background-color:#fcdcd5;color:black;font-weight:bold">Prestation non effectuée </span>  <br>-->
                    <table class="table table-striped" id="mytable2" style="width:100%;margin-top:15px;">
                        <thead>
                        <tr id="headtable">
                            <th style="width:10%">ID</th>
                            <th style="width:10%">Date</th>
                            <th style="width:15%">Prestataire</th>
                            <th style="width:20%">Prestation</th>
                            <th style="width:15%">Spécialité</th>
                           <!-- <th style="width:20%">Détails</th>-->
                            @can('isAdmin')<th style="width:10%">Actions</th>@endcan
                        </tr>

                        </thead>
                        <tbody>

                        @foreach($prestations as $prestation)
                            <?php $dossid= $prestation['dossier_id'];?>
                            <?php $effectue= $prestation['effectue'];
                            if($effectue ==0){$style='background-color:#fcdcd5;';}else{$style='';}
                            ?>

                            <tr  >
                                <td style="width:10%; <?php echo $style;?> ">
                                    <a href="{{action('PrestationsController@view', $prestation['id'])}}" >
                                        <?php  echo $prestation['id']  ; ?>
                                    </a></td>

                                <td style="width:10%">
                                    <?php echo $prestation['date_prestation'] ; ?>
                                </td>
                                <td style="width:20%">
                                    <?php $prest= $prestation['prestataire_id']; ?>
                                    <a  href="{{action('PrestatairesController@view', $prest)}}" ><?php echo PrestationsController::PrestataireById($prest);  ?>
                                    </a>
                                </td>
                                <td style="width:15%;">
                                    <?php $typeprest= $prestation['type_prestations_id'];
                                    echo PrestationsController::TypePrestationById($typeprest);  ?>
                                </td>
                                <td style="width:15%;">
                                    <?php $specialite= $prestation['specialite'];
                                    echo PrestationsController::SpecialiteById($specialite);  ?>
                                </td>
                              <!--  <td style="width:20%;">
                                <?php $details= $prestation['details'];
                                  custom_echo($details ,20);
                                ?>
                                </td>-->
                                @can('isAdmin')   <td style="width:10%;"><a onclick="return confirm('Êtes-vous sûrs ?')"  href="{{action('PrestationsController@destroy', $prestation->id) }}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer" >
                                        <span class="fa fa-fw fa-trash-alt"></span>
                                    </a>
                                </td>
                                    @endcan
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                </div>


           <div id="tab4" href="#tab4" class="tab-pane fade">
               <div class="row">
                   <div class="col-md-4"><h4 style="margin-bottom:30px">Intervenants et Prestataires</h4></div>
                   <div class="col-md-4"><a   style="margin-top:15px" class="pull-right btn btn-md btn-default"   href="{{route('prestataires.create',['id'=>$dossier->id])}}" ><b><i class="fas fa-plus"></i> Ajouter un Nouvel Intervenant</b></a></div>
                   <div class="col-md-4"><button   style="margin-top:15px" id="" class="pull-right btn btn-md btn-success"   data-toggle="modal" data-target="#insererprest"><b><i class="fas fa-plus"></i>  Insérer un Intervenant </b></button></div>
               </div>
            <br><B> Intervenants qui ont effectué de(s) prestation(s) </B>
               <table class="table table-striped" id="mytable3" style="width:100%;margin-top:15px;">
                   <thead>
                   <tr class="headtable">

                       <th style="width:20%">Intervenant</th>
                       <th style="width:15%;font-size:14px;">Type de prestations</th>
                       <th style="width:15%">Gouv et Ville</th>
                        <th style="width:15%">Spécialités</th>
                       <th style="width:15%">Actions</th>
                   </tr>

                   </thead>
                   <tbody>

<?php
$listepr=array();
foreach($prestations as $pr )
    {          $effectue= $pr['effectue'];
              if($effectue ==1)
                  {
               $prest= $pr['prestataire_id'];
               /*
$villeid=intval($prest['ville_id']);
if (isset($villes[$villeid]['name']) ){$ville=$villes[$villeid]['name'];}
else{$ville=$prest['ville'];}
*/
$interv = PrestationsController::PrestById($prest);
            $gouvs=  PrestatairesController::PrestataireGouvs($prest);
            $typesp=  PrestatairesController::PrestataireTypesP($prest);
            $specs=  PrestatairesController::PrestataireSpecs($prest);
            $ville=PrestatairesController::ChampById('ville',$prest);
        if(!in_array($prest,$listepr)){
            ?> <tr>
<td style="font-size:14px;width:20%"><a href="{{action('PrestatairesController@view', $prest)}}" ><?php echo '<i>'.$interv['civilite'] .'</i> <b>'. $interv['name'] .'</b> '.$interv['prenom']; ?></a></td>
<td style="font-size:12px;width:15%"><?php     foreach($typesp as $tp){echo PrestatairesController::TypeprestationByid($tp->type_prestation_id).',  ';}?></td>
<td style="font-size:12px;width:15%"><?php foreach($gouvs as $gv){echo PrestatairesController::GouvByid($gv->citie_id).',  ';}?><br><?php echo $ville; ?></td>
 <td style="font-size:12px;width:15%"><?php   foreach($specs as $sp){echo  PrestatairesController::SpecialiteByid($sp->specialite).',  ';}?></td>

<td style="font-size:12px;width:15%"> <button onclick="init('<?php echo $prest;?>','<?php  echo addslashes( PrestatairesController::ChampById('name',$prest).' '. PrestatairesController::ChampById('prenom',$prest))  ;?>')" style=";margin-botom:10px;margin-top:10px" type="button" data-toggle="modal"  data-target="#openmodalprest" class="btn  btn-primary"><i class="far fa-save"></i> Prestation</button></td></tr>

<?php }
array_push($listepr,$pr['prestataire_id']);
}
}
    ?>
                   </tbody>

               </table><br><br><br>


               <B> Intervenants Ajoutés Manuellement </B>
               <table class="table table-striped" id="mytable4" style="width:100%;margin-top:15px;">
                   <thead>
                   <tr class="headtable">

                       <th style="width:20%">Intervenant</th>
                       <th style="width:15%;font-size:14px;">Type de prestations</th>
                       <th style="width:15%">Gouv et Ville</th>
                        <th style="width:15%">Spécialités</th>
                       <th style="width:15%">Actions</th>
                   </tr>
                   </thead>
                   <tbody>

                   <?php foreach($intervenants as $interv )
                   {  $prest= $interv['prestataire_id'];
                   $interven = PrestationsController::PrestById($prest);
                   $gouvs=  PrestatairesController::PrestataireGouvs($prest);
                   $typesp=  PrestatairesController::PrestataireTypesP($prest);
                   $specs=  PrestatairesController::PrestataireSpecs($prest);
                   $ville= PrestatairesController::ChampById('ville',$prest);
                   ?> <tr>
                       <td style="font-size:14px;width:20%"><a href="{{action('PrestatairesController@view', $prest)}}" ><?php echo '<i>'.$interven['civilite'] .'</i> <b>'. $interven['name'] .'</b> '.$interven['prenom']; ?></a></td>
                       <td style="font-size:12px;width:15%"><?php     foreach($typesp as $tp){echo PrestatairesController::TypeprestationByid($tp->type_prestation_id).',  ';}?></td>
                       <td style="font-size:12px;width:15%"><?php foreach($gouvs as $gv){echo PrestatairesController::GouvByid($gv->citie_id).',  ';}?><br><?php echo $ville; ?></td>
                        <td style="font-size:12px;width:15%"><?php   foreach($specs as $sp){echo  PrestatairesController::SpecialiteByid($sp->specialite).',  ';}?></td>

                       <td style="font-size:12px;width:15%"> <button onclick="init('<?php echo $prest;?>','<?php  echo  addslashes(PrestatairesController::ChampById('name',$prest).' '. PrestatairesController::ChampById('prenom',$prest))  ;?>')" style=";margin-botom:10px;margin-top:10px" type="button" data-toggle="modal"  data-target="#openmodalprest" class="btn  btn-primary"><i class="far fa-save"></i> Prestation</button></td>


                   </tr>
                   <?php } ?>


                   </tbody>
               </table><br><br><br>

           </div>

                <div id="tab5" class="tab-pane fade">
                    <button type="button" style="float:left" class="btn btn-info btn-lg"  id="actualiserAtt"><i class="fas fa-sync"></i> Actualiser la page</button>
                  <button type="button" style="float:right" class="btn btn-info btn-lg" data-toggle="modal" data-target="#ajouterfichier"><i class="fas fa-plus-square"></i> Ajouter un fichier</button>
                  <br><br><br><br>
                    <table class="table table-striped" id="mytable" style=" ;margin-top:15px;">
                        <thead>
                        <tr id="headtable">
                            <th style="width:15%">Date</th>
                            <th style="width:20%">Titre</th>
                            <th style="width:20%">Description</th>
                            <th style="width:10%">Boite</th>
                        </tr>
                        <tr >
                            <th style="width:15%">Date</th>
                            <th style="width:20%">Titre</th>
                            <th style="width:20%">Description</th>
                            <th style="width:10%">Boite</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($attachements as $attach)
<?php                       $type= $attach->type;    $parent=$attach->parent;
                            $descriptionEmail='';
                            $userID=$attach->user;
            if($userID>0){
                $PrenomAgent =UsersController::ChampById('name',$userID);
                $NomAgent = UsersController::ChampById('lastname',$userID);
                $NomcA=$PrenomAgent.' '.$NomAgent;}
                else{ $NomcA='Système';}
                $filesize=$attach->filesize;
                        if($filesize >0)
                         { $tailleA=formatBytes($filesize);}else{$tailleA='';}
                            $descriptionAttach=$attach->description;
                            if($attach->entree_id>0){
                            $descriptionEmail= EntreesController::ChampById('commentaire',$parent);
                            }
                            if($attach->envoye_id>0){
                            $descriptionEmail= EnvoyesController::ChampById('description',$parent);
                            }
?>
                            <tr    >

                                <td style="width:15%;"><small><?php /*if ($attach->boite==3) {
                                        $datem='';
                                        if($attach->entree_id>0){
                                        $datem= EntreesController::ChampById('created_at',$parent);
                                        }
                                        if($attach->envoye_id>0){
                                        $datem= EnvoyesController::ChampById('created_at',$parent);
                                        }
                                        echo date('d/m/Y H:i', strtotime( $datem)) ;
                                        } else{*/ echo date('d/m/Y H:i', strtotime( $attach->created_at)) ;/* }*/ ?></small></td>
                                <td class="overme" style="cursor:pointer;width:20%;"   onclick="modalattach('<?php echo addslashes($attach->nom); ?>','<?php  echo URL::asset('storage'.addslashes($attach->path)) ; ?>','<?php echo $type; ?>');"    ><small  >
                                        <?php
                                        $type= $attach->type;
                                        switch ($type) {
                                        case 'pdf':
                                        echo '<i class="far fa-2X fa-file-pdf"></i>';
                                        break;
                                        case 'txt':
                                        echo '<i class="far fa-2X fa-file-alt"></i>';
                                        break;
                                        case 'png':
                                        echo '<i class="far fa-2X fa-file-image"></i>';
                                        break;
                                        case 'jpg':
                                        echo '<i class="far fa-2X fa-file-image"></i>';
                                        break;
                                        case 'doc':
                                        echo '<i class="far fa-2X  fa-file-word"></i>';
                                        break;
                                        case 'docx':
                                        echo '<i class="far fa-2X fa-file-word"></i>';
                                        break;
                                        case 'xls':
                                        echo '<i class="far fa-2X fa-file-excel"></i>';
                                        break;
                                        case 'xls':
                                        echo '<i class="far fa-2X fa-file-excel"></i>';
                                        break;
                                        default:
                                        echo '<i class="far fa-2X  fa-file"></i>';
                                        }
                                        ?>
                                        <?php  echo $attach->nom;  ?></small>

                                </td>
                                <td  style="cursor:pointer" onclick="modalattach2('<?php echo $attach->id ; ?>','<?php echo addslashes($descriptionAttach) ;?>','<?php echo addslashes($attach->nom) ; ?>','<?php echo $tailleA ;?>','<?php echo addslashes($NomcA);?>')" class="overme" style="width:20%;"><small><?php  echo  $descriptionAttach ; if($descriptionAttach==''){echo   $descriptionEmail  ;}   ?></small></td>

                                <td style="width:10%"><small><?php if ($attach->boite==1) {echo ' Envoi<i class="fas a-lg fa-level-up-alt" />';} if ($attach->boite==0) {echo 'Réception<i class="fas a-lg fa-level-down-alt"/>';}  if ($attach->boite==3) {echo 'Généré <br><i style="margin-top:4px;" class="fas fa-lg fa-file-invoice"/>';}    if ($attach->boite==4) {echo 'Externe <br><i style="margin-top:4px;" class="fas fa-upload"></i>';}  if ($attach->boite==7) {echo ' Envoi Fax<i class="fas a-lg fa-level-up-alt" />';}     ?></small></td>

                            </tr>

                        @endforeach

                        </tbody>
                    </table>

                </div>
            <!-- Tab TAGs -->    
            <div id="tabtags" class="tab-pane fade">
                <table class="table table-striped" id="tabletags" style="width:100%;margin-top:15px;">
                    <thead>
                    <tr id="headtable" style="font-size:13px;">
                        <th style="">Type</th>
                        <th style="">Description</th>
                        <th style="">Montant</th>
                        <th style="">Reste</th>
                        <th style="">Historique</th>
                        <th style="">Source</th>
                     </tr>

                    </thead>
                    <tbody style="font-size:13px;">
                    @foreach($ftags as $dtag)
                        <tr>
                            <td style=";"><?php echo $dtag->titre; ?> </td>
                            <td style=";"><?php echo $dtag->contenu; ?> </td>
                            <td style=";"><?php echo $dtag->montant; ?> <?php echo $dtag->devise; ?></td>
                            <td style=";"><?php echo $dtag->mrestant; ?> <?php echo $dtag->devise; ?></td>
                            <td style=";">
                            <?php
                                if ($dtag->parent !== null)
                                {
                                    echo '<button type="button" class="btn btn-primary panelciel" style="color:black;background-color: rgb(214,239,247) !important;" id="btnhistotag" onclick="historiquetag('.$dtag->parent.',\''.$dtag->contenu.'\');"><i class="far fa-eye"></i> Voir</button>';
                                   
                                }
                                else
                                {
                                    echo "Aucun";
                                }
                            ?></td>
                            <td style=";">
                                <?php if ($dtag->type == "email") { ?>
                                    <div class="btn-group" style="margin-right: 10px">
                                            <button type="button" class="btn btn-primary panelciel" style="background-color: rgb(214,247,218) !important;" id="btnsrctag">
                                                <a style="color:black" href='{{action('EntreesController@show', $dtag->entree)}}' ><i class="fas fa-external-link-alt"></i> Accéder</a>
                                            </button>
                                        </div>
                             <?php   } ?>
                             <!-- add block when the tag is for attachement  -->
                             <?php if ($dtag->type == "piecejointe") { 
                                $entreeattach = DB::table('attachements')->where('id',$dtag->entree)->first();
                                
                                ?>
                                    <div class="btn-group" style="margin-right: 10px">
                                        <button type="button" class="btn btn-primary panelciel" style="background-color: rgb(214,247,218) !important;" id="btnsrctag">
                                            <a style="color:black" href='{{action('EntreesController@show', $entreeattach->entree_id)}}' ><i class="fas fa-external-link-alt"></i> Accéder</a>
                                        </button>
                                    </div>
                             <?php   } ?>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div> 
            <div id="tab6" class="tab-pane fade">
                <div style="">
                    <button style="float:right;margin-top:10px;margin-bottom: 15px;margin-right: 20px" id="adddoc" class="btn btn-md btn-success"   data-toggle="modal" data-target="#generatedoc"><b><i class="fas fa-plus"></i> Générer un document</b></button>


                </div>
                <table class="table table-striped" id="mytable2" style="width:100%;margin-top:15px;">
                    <thead>
                    <tr id="headtable" style="font-size:13px;">
                        <th style="">Document</th>
                        <!--<th style="">Description</th>-->
                        <th style="">Historique</th>
                        <th style="">Actions</th>
                     </tr>

                    </thead>
                    <tbody style="font-size:13px;">
                    @foreach($documents as $doc)
                        <tr>
                            <td style=";"><?php echo $doc->titre; 
                                            if (stristr($doc->emplacement,'annulation') !== FALSE) 
                                            {
                                        ?> <code><i class="far fa-window-close"></i> Annulé</code></td><?php } ?>
                            <td style=";">
                            <?php
                                if ($doc->parent !== null)
                                {
                                    echo '<button type="button" class="btn btn-primary panelciel" style="color:black;background-color: rgb(214,239,247) !important;" id="btnhisto" onclick="historiquedoc('.$doc->parent.');"><i class="far fa-eye"></i> Voir</button>';
                                   
                                }
                                else
                                {
                                    echo "Aucun";
                                }
                            ?>
                            </td>
                            <?php 
                            $pathdoc = storage_path().$doc->emplacement;
                            $templatedoc = $doc->template;
                            ?>
                            <td>
                                    <div class="page-toolbar">

                                    <div class="btn-group">
                                        <?php
                                            if (stristr($doc->emplacement,'annulation')=== FALSE) 
                                            {
$remplace='remplace';
$modif='modif';
                                        ?>
<?php
 if ((! strstr($doc->titre, 'Demande_refoulement')) && (! strstr($doc->titre, 'Fax_Ima'))&& (! strstr($doc->titre, 'Procu_abonne_pr_Najda_rapat_vhl'))&& (! strstr($doc->titre, 'RM_'))) 
                                {
?> 
                                        <div class="btn-group" style="margin-right: 10px">
<input type="hidden" value=""  id="modif" />
                                            <button type="button" class="btn btn-primary panelciel" style="background-color: rgb(247,227,214) !important;" id="btnannremp">
<?php
$paramapp=Parametre::select('euro_achat','dollar_achat')->first();

$garanties=DB::table('garanties_assure')->where('id_assure',$dossier->ID_assure)->get()->toArray();
if($doc->idtaggop===null)
{
$Montanttag = $doc->montantgop;}

if($dossier->type_affectation!=='Najda TPA' || empty($garanties) )
{
$ltag=Tag::where("id",$doc->idtaggop)->first();
$count=0;
}
else
{$ltag=Rubrique::where("id",$doc->idtaggop)->first();
$count=1;}

 if ( $ltag['devise'] === "TND") 
                                    {$Montanttag = $doc->montantgop;}
                                    if ( $ltag['devise'] === "EUR")
                                       { $Montanttag = intval($doc->montantgop) * floatval($paramapp['euro_achat']);}
                                    if ( $ltag['devise'] === "USD")
                                       { $Montanttag = intval($doc->montantgop) * floatval($paramapp['dollar_achat']);}
?>

                                                <a style="color:black" href="#" id="annremp" onclick="remplacedoc(<?php echo '0'; ?>,<?php echo $doc->id; ?>,<?php echo $templatedoc; ?>,<?php if (! empty($doc->montantgop)) {echo $Montanttag;} else {echo '0';} ?>,<?php echo $doc->idtaggop; ?>);"> <i class="far fa-plus-square"></i> Annuler et remplacer</a>
                                            </button>
                                        </div>
<?php
                                            }
                                        ?>
 <div class="btn-group" style="margin-right: 10px">
                                            <button type="button" class="btn btn-primary panelciel" style="background-color: rgb(247,227,214) !important;" id="btnmodif">
                                                <a style="color:black" href="#" id="modif" onclick="remplacedoc(<?php echo '1'; ?>,<?php echo $doc->id; ?>,<?php echo $templatedoc; ?>,<?php if (! empty($doc->montantgop)) {echo $Montanttag;} else {echo '0';} ?>,<?php echo $doc->idtaggop; ?>);"> <i class="far fa-plus-square"></i> Modifier</a>
                                            </button>
                                        </div>

                                        <div class="btn-group" style="margin-right: 10px">
<?php
 if ((! strstr($doc->titre, 'Demande_refoulement'))  && (! strstr($doc->titre, 'Procu_abonne_pr_Najda_rapat_vhl')))
                                {
?>                                            
                                            <button type="button" class="btn btn-primary panelciel" style="background-color: rgb(247,214,214) !important;" id="btnann">
                                                <a style="color:black"  onclick="annuledoc('<?php echo $doc->titre; ?>',<?php echo $doc->id; ?>,<?php echo $templatedoc; ?>);" href="#" > <i class="far fa-window-close"></i> Annuler</a>
                                            </button>
                                <?php
                                            }
                                        ?>
                                        </div>
                                        <?php
                                            }
                                        ?>
                                        <div class="btn-group" style="margin-right: 10px">
<?php
                                            
       $attach = DB::table('attachements')->where('nom',$doc->name)->first();  
if($attach!==null)   
{$existe='1';}  
else                          
{$existe='0';}  
$null=null;
?>
                                            <button type="button" class="btn btn-primary panelciel" style="background-color: rgb(214,247,218) !important;" id="btntele">
                                              <a style="color:black" onclick='modalodoc("<?php echo $doc->titre; ?>","{{ URL::asset('storage'.'/app/'.$doc->emplacement) }}","<?php echo 'doc' ?>","<?php echo $null ?>","<?php echo $doc->comment; ?>","<?php echo $doc->idtaggop; ?>","<?php echo $doc->name; ?>","<?php echo $doc->emplacement; ?>",<?php echo $existe ?>,);' ><i class="fas fa-external-link-alt"></i> Aperçu</a>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>

            </div>
<!-- Modal optimiseur prestataire externe-->
<div class="modal fade" id="optprestataire"  role="dialog" aria-labelledby="exampleModal2" aria-hidden="true" style="z-index:10000!important;left: 20px;">
    <div class="modal-dialog" role="document" style="width:900px;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="optprestatairetitle">Optimiseur de choix des prestataires</h3>
            </div>
            <div class="modal-body">
                <div class="card-body" style="min-height: 650px">

                            <div class="form-group ">
                                <div class="row">
                                    <label>Spécialité</label>
                                </div>
                                <div class="row">
                                    <select class="form-control  col-lg-12 " style="width:400px" name="specialite"     id="specialitem">
                                        <option value="0"></option>
                                        @foreach($specialites as $sp)
                                         <?php     $specialite_tprestation = DB::table('specialites_typeprestations')
                                         ->where([
                                            ['specialite', '=', $sp->id],
                                            ])->first();
                                         if (isset($specialite_tprestation->type_prestation))
                                            {$stprest = $specialite_tprestation->type_prestation;
                                            ?>
                                            <option  class="tprestm  tprestm-<?php echo $stprest;?>" value="<?php echo $sp->id;?>"> <?php echo $sp->nom;?></option>
                                        <?php } else { ?>
                                                <option  class="tprestm" value="<?php echo $sp->id;?>"> <?php echo $sp->nom;?></option>
                                        <?php
                                        } ?>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group ">
                                 <label>Gouvernorat de couverture *</label>
                                 <div class="row">
                                     <select class="form-control  col-lg-12 " style="width:400px" name="gouvm"    id="gouvcouvm">
                                         <option></option>
                                         @foreach($gouvernorats as $aKeyG)
                                             <option   value="<?php echo $aKeyG->id;?>"> <?php echo $aKeyG->name;?></option>
                                         @endforeach

                                     </select>
                                 </div>
                             </div>

                             <div class="form-group ">
                                 <div class="row">
                                     <label>Ville</label>
                                 </div>
                                 <div class="row" style=";margin-bottom:10px;"><style>.algolia-places{width:80%;}</style></div>
                                 <input class="form-control" style="padding-left:5px" type="text"  id="villeprm" />
                                 <input class="form-control" style="padding-left:5px;" type="hidden"  id="villecodem" />

                             </div>
                             <script>
                                 (function() {
                                     var placesAutocomplete2 = places({
                                         appId: 'plCFMZRCP0KR',
                                         apiKey: 'aafa6174d8fa956cd4789056c04735e1',
                                         container: document.querySelector('#villeprm'),
                                     });
                                     placesAutocomplete2.on('change', function resultSelected(e) {
                                         document.querySelector('#villecodem').value = e.suggestion.postcode || '';
                                     });
                                 })();
                             </script>


                             <div class="form-group row" >
                                 <label class=" control-label">Date de prestation <span class="required" aria-required="true"> * </span></label>
                                 <div class="row">
                                     <input style="width:200px;" value='<?php echo date('d/m/Y'); ?>' class="form-control datepicker-default" name="pres_datem" id="pres_datem"  >
                                 </div>
                             </div>

                             <div class="row">
<input type="hidden" value="1"  id="start-m" />
                                 <span class="btn btn-success" id="rechercherm" >Rechercher <i class="fa fa-loop"></i></span>
                             </div>

                            <div id="data-m" ><style>#data-m b{text-align:center;}</style>

                             </div>
                             <!--                         <a href="#" class="hvr-shrink button button-3d button-success button-rounded" style="display:none;margin-top:40px;margin-bottom:30px" id="choisir"><i class="fa fa-check"></i>  Sélectionner</a>-->
                             <button style="display:none;margin-top:50px;margin-bottom:50px" id="showNext-m" type="button" class="hvr-wobble-horizontal btn btn-lg btn-labeled btn-info">
                                 Suivant
                                 <span class="btn-label" style="left: 13px;">
                                                    <i class="fa fa-chevron-right"></i>
                                                </span>
                             </button>

                             <div id="termine-m" style="display:none;height:120px;align:center;">
                                 <center><br>   Fin de la liste.<br></center>

                                 <button style="margin-bottom: 40px" id="essai2-m" type="button" class="btn btn-labeled btn-default btn-lg hvr-wobble-to-top-right right1">
                                                <span class="btn-label">
                                                    <i class="fa fa-refresh"></i>
                                                </span>
                                     Réessayez
                                 </button>
                             </div>
                             <input type="hidden" id="selected-m" value="0">
                             <input type="hidden" id="par-m" value="<?php echo $iduser;?>">
                             <div class="row">
                                 <div class="col-md-4">
                                     <button style="display:none;margin-botom:10px;margin-top:20px" type="button" id="add2-m" class="btn btn-lg btn-primary"><i class="far fa-save"></i> Enregister la prestation</button>
                                 </div>
                                 <div class="col-md-4"  id="add2prest-m" style="display:none" >
                                     <label>Prestataire sélectionné :</label><br>
                                     <select style="width:350px;margin-top:10px;margin-bottom:10px;" disabled id="selectedprest-m" name="selectedprest-m"  class="form-control col-lg-9 " value=" ">
                                         <option></option>
                                         @foreach($prestataires as $prest)
                                             <option    value="<?php echo $prest->id;?>"> <?php echo $prest->name;?></option>
                                         @endforeach
                                     </select>
                                 </div>
                                 <div class="col-md-4"></div>
                             </div>

                             <div class="row">
                                 <div class="  form-group"  id="prestation-m"  style="display:none">
                                   <div class="col-md-4">
                                       <button style="display:none;margin-botom:10px" type="button" id="valide-m" class="btn btn-lg btn-success"><i class="fa fa-check"></i> Valider la prestation</button>
<input type="hidden" value="0" id="firstsaved-m" />
                                   </div>
                                     <div class="col-md-4"  style="display:none;padding-left:15px;"  id="validation-m">
                                         <label>ou bien Prestation non effectuée ? Raison:</label>

                                         <select class="form-control" id="statutprest-m" >
                                             <option></option>
                                             <option    value="nonjoignable">Non Joignable </option>
                                             <option    value="nondisponible">Non Disponible </option>
                                             <option    value="autre">Autre </option>
                                         </select>

                                     </div>
                                     <div class="col-md-4" >
                                         <textarea type="text" style="display:none;height:60px" class="form-control" Placeholder="Détails"  id="detailsprest-m" ></textarea>
                                      </div>
                                     <input type="hidden"  id="idprestation-m" value="0" />


                                 </div>
                             </div>

                             <div class="row">
                             </div>


                             <input id="dossier-m" name="dossier" type="hidden" value="{{ $dossier->id}}">

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ouvrir Document-->
<div class="modal fade" id="opendoc"  style="z-index: 1600;"role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width:900px;height: 450px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="doctitle" style="font-weight: bold"></h5>
                <div style="position: absolute; right: 15px; top: 15px;"><button type="button" class="btn btn-default btn-xs" readonly disabled id="tagudoc"><i class="fas fa-tag"></i> Tag</button></div>
            </div>
            <div class="modal-body">
                <div class="card-body">

                    <iframe id="dociframe" src="" frameborder="0" style="width:100%;min-height:480px;"></iframe>

                </div>

            </div>
            <textarea name="apercucomment" id="apercucomment" placeholder="Commentaire..." style="margin-left: 2%;margin-right: 2.5%;margin-bottom: 1%;width: 95%; background: #efefef;" readonly></textarea>
            <div class="modal-footer">
                <button type="button" id="attachdoc" onclick="document.getElementById('attachdoc').disabled=true;" class="btn btn-primary" >Enregistrer</button>
                <button type="button" id="fermedoc" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
            <div id="tab7" class="tab-pane fade">
                <div style="">
                    <button style="float:right;margin-top:10px;margin-bottom: 15px;margin-right: 20px" id="addom" class="btn btn-md btn-success"   data-toggle="modal" data-target="#generateom"><b><i class="fas fa-plus"></i> Créer un ordre de mission</b></button>


                </div>
                <table class="table table-striped" id="mytable2" style="width:100%;margin-top:15px;">
                    <thead>
                    <tr id="headtable" style="font-size:13px;">
                        <th style="">OM</th>
                        <!--<th style="">Description</th>-->
                        <th style="">Historique</th>
                        <th style="">Validation</th>
                        <th style="">Date de mission</th>
                        <th style="">Date de création</th>
                        <th style="">Actions</th>
                     </tr>

                    </thead>
                    <tbody style="font-size:13px;">
                       <?php
$omtaxis = \App\OMTaxi::where(['dossier' => $dossier->id,'dernier' => 1])->select('id','affectea','titre','parent','emplacement','created_at','CL_heuredateRDV','statut')->orderBy('created_at','desc')->get();
        $omambs = \App\OMAmbulance::where(['dossier' => $dossier->id,'dernier' => 1])->select('id','affectea','titre','parent','emplacement','created_at','CL_heuredateRDV','statut')->orderBy('created_at','desc')->get();
        $omrem = \App\OMRemorquage::where(['dossier' => $dossier->id,'dernier' => 1])->select('id','affectea','titre','parent','emplacement','created_at','CL_heuredateRDV','statut')->orderBy('created_at','desc')->get();
$omstot = array_merge($omtaxis->toArray(),$omambs->toArray(),$omrem->toArray() );
  function cmp($a, $b)
    {
        return strcmp($b["created_at"],$a["created_at"]);
    }
    usort($omstot, "cmp");
?>
                        <?php if (!empty ($omstot)) { ?>
                        @foreach($omstot as $om)
                        <tr>
                            <td style="width:10%" ><?php echo $om['titre']; ?></td>
                            <td style="width:10%">
                            <?php
if (stristr( $om['titre'],'taxi')!== FALSE)
{ $titre=1;}
if (stristr( $om['titre'],'ambulance')!== FALSE)
{ $titre=2;}
if (stristr( $om['titre'],'remorquage')!== FALSE)
{ $titre=3;}
                                if ($om['parent'] !== null)
                                {
                                    echo '<button type="button" class="btn btn-primary panelciel" style="color:black;background-color: rgb(214,239,247) !important; padding: 6px 6px!important;" id="btnhisto" onclick="historiqueomtx('.$om['parent'].','.$titre.');"><i class="far fa-eye"></i> Voir</button>';
                                   
                                }
                                else
                                {
                                    echo "Aucun";
                                }
                            ?>
                            </td>
                            <td style="width:10%">
                            <?php
if($om['affectea']!='externe') {
                                if (Gate::check('isSupervisor')) 
                                {$id=Auth::user()->id;
if (stristr( $om['titre'],'taxi')!== FALSE)
{ $types=1;}
if (stristr( $om['titre'],'ambulance')!== FALSE)
{ $types=2;}
if (stristr( $om['titre'],'remorquage')!== FALSE)
{$types=3;}
if ($om['statut'] !="Validé" && $om['statut']!="Annulé" ) {
                                    echo '<button type="button" class="btn btn-primary panelciel" style="color:black;background-color: rgb(214,239,247) !important; padding: 6px 6px!important;" id="btnvalid" onclick="valideom('.$om['id'].','.$id.','.$types.');" ><i class="fas fa-check"></i> Valider</button>';
                                   
                               
                           }
else {
if ($om['statut'] =="Validé"){
echo "<span style='color:blue'>".$om['statut']."</span>";}
if ($om['statut'] =="Annulé"){
echo "<span style='color:black'>".$om['statut']."</span>";}
}}
                                else
                                { if ($om['statut'] =="Validé" || $om['statut']=="Annulé" ) {
                                    if ($om['statut'] =="Validé"){
echo "<span style='color:blue'>".$om['statut']."</span>";}
if ($om['statut'] =="Annulé"){
echo "<span style='color:black'>".$om['statut']."</span>";}}
else {
echo "<span style='color:red'> Non Validé </span>" ;
}
                                }}
else {
echo "";
}
                            ?>
                            </td>
<?php
   if (stristr( $om['titre'],'annulation')!== FALSE) 
                                            {
if (stristr( $om['titre'],'taxi')!== FALSE)
{$omtaxii = DB::table('om_taxi')->where('id',$om['parent'])->first();}
if (stristr( $om['titre'],'ambulance')!== FALSE)
{ $omtaxii = DB::table('om_ambulance')->where('id',$om['parent'])->first();}
if (stristr( $om['titre'],'remorquage')!== FALSE)
{ $omtaxii = DB::table('om_remorquage')->where('id',$om['parent'])->first();}
      $heuredaterdv=$omtaxii->CL_heuredateRDV;  }
else{ $heuredaterdv=$om['CL_heuredateRDV'];
                   }  
$heuredaterdv1 = strtotime(substr($heuredaterdv,0,10));
      $heuredaterdv2 = date('d-m-Y',$heuredaterdv1);     ?>

<td style="width:10%"><?php echo $heuredaterdv2; ?></td>

<td style="width:10%"><?php  $heurecrea = strtotime(substr($om['created_at'],0,10));
 $heurecrea1 = date('d-m-Y',$heurecrea); 
echo $heurecrea1; ?></td>
                            <?php 
                            $emppos=strpos($om['emplacement'], '/OrdreMissions/');
                            $empsub=substr($om['emplacement'], $emppos);
                            $pathomtx = storage_path().$empsub;
                            //$templatedoc = $doc->template;
                            ?>
                            <td>
                                    <div class="page-toolbar">

                                    <div class="btn-group">
                                        <?php
                                            if (stristr($empsub,'annulation')=== FALSE) 
                                            {
if (stristr( $om['titre'],'taxi')!== FALSE)
{$omd='omtx';}
if (stristr( $om['titre'],'ambulance')!== FALSE)
{ $omd='omamb';}
if (stristr( $om['titre'],'remorquage')!== FALSE)
{$omd='omre';}
                                        ?>
                                        <div class="btn-group" style="margin-right: 10px">
                                            <button type="button" class="btn btn-primary panelciel" style="background-color: rgb(247,227,214) !important; padding: 6px 6px!important;" id="btnannrempomtx">

                                                <a style="color:black" href="#" id="annrempomtx" onclick="remplaceom(<?php echo $om['id']; ?>,'<?php echo $om['affectea']; ?>','<?php echo $omd; ?>');"> <i class="far fa-plus-square"></i> Remplacer</a>

                                            </button>
                                        </div>

                                        <div class="btn-group" style="margin-right: 10px">
                                            <button type="button" class="btn btn-primary panelciel" style="background-color: rgb(247,214,214) !important; padding: 6px 6px!important;" id="btnannomtx">
                                                <a style="color:black"  onclick="annuleom('<?php echo $om['titre']; ?>',<?php echo $om['id']; ?>);" href="#" > <i class="far fa-window-close"></i> Annuler</a>
                                            </button>
                                        </div>
                                        <?php
                                            }
                                        ?>
                                        <?php
                                            if (isset($om['affectea'])) 
                                            { if ($om['affectea'] === "interne") {
                                        ?>
                                        <div class="btn-group" style="margin-right: 10px">
                                            <button type="button" class="btn btn-primary panelciel" style="background-color: rgb(221,221,221) !important; padding: 6px 6px!important;" id="btncomp">

                                                <a style="color:black" onclick='completeom("<?php echo $om['id']; ?>","<?php echo $om['affectea']; ?>","<?php echo $omd; ?>");' ><i class="fas fa-pen"></i> Compléter</a>
                                            </button>
                                        </div>
                                        <?php
                                            }}
                                        ?>

                                        <div class="btn-group" style="margin-right: 10px">
                                            <button type="button" class="btn btn-primary panelciel" style="background-color: rgb(214,247,218) !important; padding: 6px 6px!important;" id="btntele">
<?php
   $titre=$om['titre'].'.pdf';                                       
       $attachom = DB::table('attachements')->where('nom',$titre)->where('dossier',$dossier->id)->first();  
if($attachom!==null)   
{$existe='1';}  
else                          
{$existe='0';}  
$null=null;
?>

                                                <a style="color:black" onclick='modalodoc("<?php echo $om['titre']; ?>","{{ URL::asset('storage'.$empsub) }}","<?php echo 'om' ?>","<?php echo $om['parent']; ?>","<?php echo $null; ?>","<?php echo $null; ?>","<?php echo $null; ?>","<?php echo $null; ?>","<?php echo $existe; ?>");' ><i class="fas fa-external-link-alt"></i> Aperçu</a>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    <?php //endif
                            } ?>
                    <?php if (! ($ommi->isEmpty())) { ?>  
                        @foreach($ommi as $ommie)
                            <tr>
                                <td style=";"><?php echo $ommie->titre; ?></td>
                                <td style=";">
                                    <?php
 $titre=4;
                                    if ($ommie->parent !== null)
                                    {
                                       echo '<button type="button" class="btn btn-primary panelciel" style="color:black;background-color: rgb(214,239,247) !important; padding: 6px 6px!important;" id="btnhisto" onclick="historiqueomtx('.$ommie->parent.','.$titre.');"><i class="far fa-eye"></i> Voir</button>';
                                    }
                                    else
                                    {
                                        echo "Aucun";
                                    }
                                    ?>
                                </td>
<td style=";">
                                    <?php
echo "";
                                    ?>
                                </td>
<?php
   if (stristr( $ommie->titre,'annulation')!== FALSE) 
                                            {
$ommiei = DB::table('om_medicinternationnal')->where('id',$ommie->parent)->first();
      $heuredaterdv=$ommiei->CL_date_heure_prise;  }
else{ $heuredaterdv=$ommie->CL_date_heure_prise;
                   }  
$heuredaterdv1 = strtotime(substr($heuredaterdv,0,10));
      $heuredaterdv2 = date('d-m-Y',$heuredaterdv1);     ?>

<td style=";"><?php echo $heuredaterdv2; ?></td>

<td style=";"><?php  $heurecrea = strtotime(substr($ommie->created_at,0,10));
 $heurecrea1 = date('d-m-Y',$heurecrea); 
echo $heurecrea1; ?></td>
                                <?php
                                $emppos=strpos($ommie->emplacement, '/OrdreMissions/');
                                $empsub=substr($ommie->emplacement, $emppos);
                                $pathomtx = storage_path().$empsub;
                                //$templatedoc = $doc->template;
                                ?>
                                <td>
                                    <div class="page-toolbar">

                                        <div class="btn-group">
                                            <?php
                                            if (stristr($empsub,'annulation')=== FALSE)
                                            {
                                            ?>
                                            <div class="btn-group" style="margin-right: 10px">
                                                <button type="button" class="btn btn-primary panelciel" style="background-color: rgb(247,227,214) !important; padding: 6px 6px!important;" id="btnannrempomtomre">
                                                    <a style="color:black" href="#" id="annrempomtx" onclick="remplaceom(<?php echo $ommie->id; ?>,'<?php echo $ommie->affectea; ?>','ommie');"> <i class="far fa-plus-square"></i> Remplacer</a>
                                                </button>
                                            </div>

                                            <div class="btn-group" style="margin-right: 10px">
                                                <button type="button" class="btn btn-primary panelciel" style="background-color: rgb(247,214,214) !important; padding: 6px 6px!important;" id="btnannomre">
                                                    <a style="color:black"  onclick="annuleom('<?php echo $ommie->titre; ?>',<?php echo $ommie->id; ?>);" href="#" > <i class="far fa-window-close"></i> Annuler</a>
                                                </button>
                                            </div>
                                            <?php
                                            }
                                            ?>
                                            <div class="btn-group" style="margin-right: 10px">
                                                <button type="button" class="btn btn-primary panelciel" style="background-color: rgb(214,247,218) !important; padding: 6px 6px!important;" id="btntele">
<?php
   $titre=$ommie['titre'].'.pdf';                                       
       $attachom = DB::table('attachements')->where('nom',$titre)->where('dossier',$dossier->id)->first();  
if($attachom!==null)   
{$existe='1';}  
else                          
{$existe='0';}  
$null=null;
?>
                                                    <a style="color:black" onclick='modalodoc("<?php echo $ommie->titre; ?>","{{ URL::asset('storage'.$empsub) }}","<?php echo 'om' ?>","<?php echo $ommie['parent']; ?>","<?php echo $null; ?>","<?php echo $null; ?>","<?php echo $null; ?>","<?php echo $null; ?>","<?php echo $existe; ?>");' ><i class="fas fa-external-link-alt"></i> Aperçu</a>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    <?php //endif
                            } ?>
                    </tbody>
                </table>

            </div>

            <div id="tab8" class="tab-pane fade">
                <ul class="nav  nav-tabs">

                    <li class="nav-item active">
                        <a class="nav-link active show" href="#tab81" data-toggle="tab"  onclick=";showinfos81();hideinfos82()">
                            <i class="fas fa-lg  fa-user-md"></i>  Missions actives + reportées + déléguées
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tab82" data-toggle="tab"  onclick="showinfos82();hideinfos81();">
                            <i class="fas  fa-lg fa-users"></i>  Missions achevées 
                        </a>
                    </li>
               
                </ul>

                <div id="tab81" class="tab-pane fade active in " style="overflow-y: scroll;">
                    <br>
                   Missions actives + reportées + déléguées
                   <br>

                    <br><br><br>
                    <table class="table table-striped" id="mytableMA" style="margin-top:15px;">
                        <thead>
                        <tr id="headtable">
                            <th style="width:15%">Extrait (titre)</th>
                            <th style="width:25%">Type</th>
                            <th style="width:15%">Date début // Date fin</th>
                            <th style="width:15%">Commentaire</th>
                            <th style="width:10%">statut</th>
                             <th style="width:20%">Créateur</th>
                            <th style="width:10%">Actions /Source</th>
                        </tr>
                        <tr>
                           <th style="width:15%">Extrait (titre)</th>
                            <th style="width:25%">Type</th>
                            <th style="width:15%">Date début // Date fin</th>
                            <th style="width:15%">Commentaire</th>
                            <th style="width:10%">statut</th>
                             <th style="width:20%">Créateur</th>
                            <th style="width:10%">Actions /Source</th>
                        </tr>
                        </thead>
                        <tbody>
                             <?php $missionsACVD=App\Mission::where('dossier_id',$dossier->id)->orderBy('date_deb','desc')->get();?>
                     @if ($missionsACVD)                                       
                       @foreach ($missionsACVD as $macvd)     
                            <tr><td style="width:15%;"><small>{{$macvd->titre}} </small></td>
                                @if($macvd->nom_type_miss)
                                <td style="width:25%;"><small>{{$macvd->nom_type_miss}} </small></td>
                                @else
                                <td style="width:25%;"><small>{{$macvd->typeMission->nom_type_Mission}} </small></td>
                                @endif
                           
                            <td style="width:15%;"><small>{{$macvd->date_deb}}//{{$macvd->date_fin}} </small></td>
                            <td style="width:15%;"><small>{{$macvd->commentaire}} </small></td>
                            @if($macvd->statut_courant=='reportee')
                            <td style="width:10%;"><small>reportée</small></td>
                            @endif
                             @if($macvd->statut_courant=='deleguee')
                            <td style="width:10%;"><small>déléguée
<?php if($macvd->assistant_id) {
    $userrr=App\User::where('id',$macvd->assistant_id)->first();
    echo 'à '.$userrr->name.' '.$userrr->lastname ;
}?>                       </small></td>
                            @endif
                            @if($macvd->statut_courant=='active')
                            <td style="width:10%;"><small>active</small></td>
                            @endif
                             @if($macvd->statut_courant=='endormie')
                            <td style="width:10%;"><small>endormie</small></td>
                            @endif
                            @if($macvd->statut_courant=='delendormie')
                            <td style="width:10%;"><small>contient une action déléguée</small></td>
                            @endif
                             <td style="width:15%;"><small>   <?php if ((isset($mhivd->user_origin))  ) { ?>
                                     {{$macvd->user_origin->name}} {{$macvd->user_origin->lastname}} <?php } ?></small></td>
                            <td style="width:10%;"><button type="button" id="macvd{{$macvd->id}}" class="btn btn-primary panelciel macvd" style="color:black;background-color: rgb(214,239,247) !important;"  onclick=""> Actions</button><br>
                                <button type="button" id="macvdo{{$macvd->id}}" class="btn btn-primary panelciel mailGenermacvd" style="color:black;background-color: rgb(214,239,247) !important;"  onclick=""> Source</button></td></tr>
                       @endforeach
                     @endif    
                        </tbody>

                 </table>
                </div> 
                <div id="tab82" class="tab-pane fade " style="overflow-y: scroll;">
                    <br>
                     Missions achevées
                     <br>
                      <br><br><br>
                    <table class="table table-striped" id="mytableMACC" style=" margin-top:15px;">
                        <thead>
                        <tr id="headtable">
                            <th style="width:15%">Extrait (titre)</th>
                            <th style="width:25%">Type</th>
                            <th style="width:15%">Date début //Date fin</th>
                            <th style="width:15%">Commentaire</th>
                            <th style="width:10%">statut</th>
                             <th style="width:20%">Créateur</th>
                            <th style="width:10%">Actions /Source</th>
                        </tr>
                        <tr>
                           <th style="width:15%">Extrait (titre)</th>
                            <th style="width:25%">Type</th>
                            <th style="width:15%">Date début //Date fin</th>
                            <th style="width:15%">Commentaire</th>
                            <th style="width:10%">statut</th>
                             <th style="width:20%">Créateur</th>
                            <th style="width:10%">Actions /Source</th>
                        </tr>
                        </thead>
                        <tbody>
                    <?php $missionsHIVD=App\MissionHis::where('dossier_id',$dossier->id)->orderBy('date_deb','desc')->get();?>
                     @if ($missionsHIVD)                                       
                       @foreach ($missionsHIVD as $mhivd)     
                            <tr><td style="width:15%;"><small>{{$mhivd->titre}} </small></td>
                                 @if($mhivd->nom_type_miss)
                                <td style="width:25%;"><small>{{$mhivd->nom_type_miss}} </small></td>
                                @else
                                <td style="width:25%;"><small>{{$mhivd->typeMission->nom_type_Mission}} </small></td>
                                @endif
                            <td style="width:15%;"><small>{{$mhivd->date_deb}}//{{$mhivd->date_fin}} </small></td>
                            <td style="width:15%;"><small>{{$mhivd->commentaire}} </small></td>
                             @if($mhivd->statut_courant=='achevee')
                            <td style="width:10%;"><small>achevée</small></td>
                            @endif
                             @if($mhivd->statut_courant=='annulee')
                            <td style="width:10%;"><small>annulée </small></td>
                            @endif
                           <td style="width:15%;">         <?php if ((isset($dossier->user_origin))  ) { ?>
                               <small>{{$mhivd->user_origin->name}} {{$mhivd->user_origin->lastname}}</small><?php } ?></td>
                            <td style="width:10%;"><button type="button" id="mhivd{{$mhivd->id_origin_miss}}" class="btn btn-primary panelciel mhivd" style="color:black;background-color: rgb(214,239,247) !important;"  onclick="">Actions</button><br>
                                <button type="button" id="mhivdo{{$mhivd->id_origin_miss}}" class="btn btn-primary panelciel mailGenermhivd" style="color:black;background-color: rgb(214,239,247) !important;"  onclick="">Source</button></td></tr>
                       @endforeach
                     @endif    
                        </tbody>

                 </table>
                </div> 

             </div> <!--fin tab missions-->


<?php           $user = auth()->user();
                $type =    $user->user_type;
                if($type=='admin' || $type=='bureau' ||$type=='financier' ){
?>
                <div id="tab9" class="tab-pane fade">

                	<div class="col-sm-2  ">
					    <button id="addgr" class="btn btn-md btn-success"   data-toggle="modal" data-target="#createfacture"><b><i class="fas fa-plus"></i> Ajouter une facture </b></button>
						</div><br>

                    <table class="table table-striped" id="mytable2" style="width:100%;margin-top:15px;">
                        <thead>
                        <tr id="headtable">
                            <th style="width:10%">ID</th>
                            <th style="width:10%">Date</th>
                            <th style="width:15%">N° Facture</th>
                            <th style="width:15%">Assistance</th>
                            <th style="width:15%">Prestataire</th>
                            <th style="width:10%">Supp</th>
                          </tr>

                        </thead>
                        <tbody>
                        <?php $factures= Facture::where('iddossier',$dossier->id)->get() ;?>
                        @foreach($factures as $facture)

                            <tr  >
                                <td style="width:10%;">
                                    <a href="{{action('FacturesController@view', $facture->id)}}" ><?php echo sprintf("%05d",$facture->id);?></a>
                                    </td>
                                <td style="width:10%">
                                    <?php echo date('d/m/Y H:i', strtotime($facture->created_at)) ; ?>
                                 </td>
                                <td style="width:15%">
                                    <?php echo $facture->reference ; ?>
                                    </td>
                                <td style="width:20%">
                                    <?php $client =   $dossier->customer_id ; echo   ClientsController::ClientChampById('name',$client);?>
                                </td>
                                <td style="width:20%">
                                    <?php $prest=  $facture->prestataire; ?>
                                    <a  href="{{action('PrestatairesController@view', $prest)}}" ><?php echo PrestationsController::PrestataireById($prest);  ?>
                                    </a>
                                </td>
								 <td style="width:10%">
								     <a  href="{{action('FacturesController@destroy', $facture->id )}}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer" >
                                <span class="fa fa-fw fa-trash-alt"></span>
								</a>
								</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
<?php
                }
                ?>

                </div>


        </div>
    </section>


<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>







<!-- Modal Email
<div class="modal fade" id="createemail"    role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModal2">Ajouter une adresse Email </h5>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <div class="form-group">
                        <form id="addemailform" novalidate="novalidate">
                            {{-- csrf_field() --}}
                            <input id="parent" name="parent" type="hidden" value="{{-- $dossier->id --}}">
                            <div class="form-group " >
                                <label for="emaildoss">Email</label>
                                <div class=" row  ">
                                    <input class="form-control" type="email" required id="emaildoss"/>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="DescrEmail">nom</label>
                                <div class="row">
                                    <input type="text" class="form-control"  id="DescrEmail" />
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="DescrEmail">qualité</label>
                                <div class="row">
                                    <input type="text" class="form-control"  id="qualite" />
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="DescrEmail">Tel</label>
                                <div class="row">
                                    <input type="text" class="form-control"  id="telmail" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" id="emailadd" class="btn btn-primary">Ajouter</button>
            </div>
        </div>
    </div>
</div>
-->
<!-- Modal Document-->
<div class="modal fade" id="generatedoc" role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModal2">Choisir la template du document </h5>

            </div>
            <div class="modal-body">
                <div class="card-body">


                    <div class="form-group">
                        {{ csrf_field() }}

                        <form id="gendocform" novalidate="novalidate">

                            <input id="dossier" name="dossier" type="hidden" value="{{ $dossier->id}}">
                            <div class="form-group " >
                                <label for="emaildoss">Template</label>
                                <div class=" row  ">
                                    <select class="form-control select2" style="width: 350px" required id="templatedoc" name="templatedoc" >
                                        <option value="Select">Selectionner</option>
                                    <?php
                                       /* $usedtemplates = Document::where('dossier',$dossier->id)->distinct()->get(['template']);
                                        $usedtid=array();
                                        foreach ($usedtemplates as $tempu) {
                                            $usedtid[]=$tempu['template'];
                                        }*/
                                        $templatesd = Template_doc::orderBy('nom','asc')->get();
                                        $docwithcl = array();
                                    ?>
                                        @foreach ($templatesd as $tempdoc)
                                         
                                      <option value={{ $tempdoc["id"] }} >{{ $tempdoc["nom"] }}</option>
                                                                                
                                        @endforeach
                                        
                                   </select>
                                </div>
                            </div>

                        </form>
                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" id="gendoc" class="btn btn-primary">Choisir</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Select GOP -->
<div class="modal fade" id="selectgopdoc" role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModal2">Choisir le GOP à utiliser</h5>

            </div>
            <div class="modal-body">
                <div class="card-body">


                    <div class="form-group">
                        {{ csrf_field() }}

                        <form id="gopselectform" novalidate="novalidate">
                            <div class="form-group " >
                                <label for="gopdoc">Template</label>
                                <div class=" row  ">
                                    <select class="form-control select2" style="width: 420px" required id="gopdoc" name="gopdoc" >
                                   </select>
                                </div>
                            </div>

                        </form>
                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" id="btngop" class="btn btn-primary">Choisir</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal OM-->
<div class="modal fade" id="generateom" role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModal2">Créer un ordre de mission </h5>

            </div>
            <div class="modal-body">
                <div class="card-body">


                    <div class="form-group">
                        {{ csrf_field() }}

                        <form id="genomform" novalidate="novalidate">

                            <input id="dossom" name="dossom" type="hidden" value="{{ $dossier->id}}">
                            <div class="form-group " >
              
                                  <div class=" row  ">
                                    <div class="col-md-3"><label for="templateom">Ordre de mission</label></div>
                                      <select class="form-control select2" style="width: 230px" required id="templateom" name="templateom" >
                                          <option value="Select">Selectionner</option>
                                          <option value="Taxi">Taxi</option>
                                          <option value="Ambulance">Ambulance</option>
                                          <option value="Remorquage">Remorquage</option>
                                          <?php
if(strstr($dossier['reference_medic'],"MI")){
?>
                                          <option value="Medic Internationnal">Medic Internationnal</option>
          <?php
}
?>  
                                     
                                          
                                     </select>
                                  </div>
                                  <div class=" row  " style="margin-top: 15px">
                                    <!--<div class="col-md-3"><label for="emispar">Émis par</label></div>
                                      <select class="form-control" style="width: 230px" required id="emispar" name="emispar" >
                                          <option value="Select">Selectionner</option>
                                          <option value="najda">Najda Assistance </option>
                                          <option value="medicm">Medic Multiservices </option>
                                          <option value="medict">Medic transport </option>
                                          <option value="vat">VAT transport </option>
                                          <option value="medici">Medic International </option>
                                     </select>-->
                                     <input type="hidden" name="affectea" id="affectea" value="">
<input type="hidden" name="affecteasecondaire" id="affecteasecondaire" value="">
<input type="hidden" name="dossierexistant" id="dossierexistant" value="">
                                  </div>
                                  <!--<div class=" row  " style="margin-top: 15px">
                                    <div class="col-md-3"><label for="affectea">Affecté à</label></div>
                                      <select class="form-control" style="width: 230px" required id="affectea" name="affectea" >
                                          <option value="Select">Selectionner</option>
                                          <option value="interne">Société soeur</option>
                                          <option value="externe">Prestataire externe</option>
                                     </select>
                                  </div>-->
                              </div>

                        </form>
                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" id="genom" class="btn btn-primary">Valider</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal template html doc-->
<div class="modal fade" id="templatehtmldoc" role="dialog" aria-labelledby="exampleModal2" aria-hidden="true"  data-backdrop="static" data-keyboard="false" >
    <div class="modal-dialog" role="document" style="width:900px;height: 450px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModal2">Veuillez éditer les champs du document</h5>

            </div>
            <div class="modal-body">
                <div class="card-body" style="padding-bottom: 0px!important">


                    <div class="form-group">
                        

                        <form id="gendocfromhtml" novalidate="novalidate" method="post" action="{{ route('documents.adddocument') }}">
                            {{ csrf_field() }}
                            <input type="hidden" id="idMissionDoc" name="idMissionDoc"  value="">
                            <input id="dossdoc" name="dossdoc" type="hidden" value="{{ $dossier->id}}">
                            <input type="hidden" name="templatedocument" id="templatedocument" >
                            <input type="hidden" name="iddocparent" id="iddocparent" >
                            <input type="hidden" name="idtaggop" id="idtaggop" >
                            <iframe src="#" id="templatefilled" name="templatefilled" style="width:100%;height:100%">content</iframe>

                        </form>
                    </div>


                </div>

            </div>
                <textarea name="doccomment" id="doccomment" placeholder="Commentaire..." style="margin-left: 2%;margin-right: 2.5%;margin-bottom: 1%;width: 95%; background: #efefef;"></textarea>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" id="gendochtml" class="btn btn-primary">Générer</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal template html om-->
<div class="modal fade" id="templatehtmlom" role="dialog" aria-labelledby="exampleModal2" aria-hidden="true"  data-backdrop="static" data-keyboard="false" >
    <div class="modal-dialog" role="document" style="width:900px;height: 450px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModal2">Veuillez éditer les champs de l'ordre de mission</h5>


            </div>
            <div class="modal-body">
                <div class="card-body">


                    <div class="form-group">
                        

                        <form id="genomfromhtml" novalidate="novalidate" method="post" action="">
                            {{ csrf_field() }}
                            <input type="hidden" id="idMissionOM" name="idMissionOM"  value="">
                            <input id="dossdoc" name="dossdoc" type="hidden" value="{{ $dossier->id}}">
                            <input type="hidden" name="templateordrem" id="templateordrem" value="">
                            <input type="hidden" name="idomparent" id="idomparent" >
                            <iframe src="#" id="omfilled" name="omfilled" style="width:100%;height:100%">content</iframe>

                        </form>
                    </div>


                </div>

            </div>
            <div class="modal-footer" >
                <div class="row"  >
                    <div id="claffect1" class="col-md-2" style="float: left!important;">
                        Assigner à: 

                    </div>
                    <div id="claffect2" class="col-md-2" style="float: left!important;padding-left:50px;">
                        <select id="affectationprest" name="affectationprest" class="form-control" style="width: 140px">
                                                    <option value="Select">Selectionner</option>
                                                    <option value="mmentite">Meme entite</option>
                                                    <option value="interne">Entite-soeur</option>
                                                    <option value="externe">Prestataire externe</option>
                        </select>
                    </div>
                    
 <div id="typeaffect1" class="col-md-2" style="float: left!important;display: none;padding-left:50px;">
                        <select id="type_affectation_exis" name="type_affectation_exis" class="form-control" style="width: 140px">
                                  
                                                        
                                                    
                                                    
                        </select>
                    </div>
<div id="typeaffect" class="col-md-2" style="float: left!important;display: none;padding-left:50px;">
                        <select id="type_affectation" name="type_affectation" class="form-control" style="width: 140px">
                                                    <option value="Select">Selectionner</option>
                                                    <option value="Transport VAT">Transport VAT</option>
                                                    <option value="Transport MEDIC">Transport MEDIC</option>
                                                    <option value="Transport Najda">Transport Najda</option>
                                                    <option value="X-Press">X-Press</option>
                        </select>
                    </div>
                    <div id="externaffect" class="col-md-3" style="float: left!important;display: none;">
                        <input type="hidden" name="idprestselected" id="idprestselected">
                        <input name="prestselected" id="prestselected" disabled>
                    </div>
                    <div class="col-md-3" style="float: right!important;">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="button" id="genomhtml" onclick="document.getElementById('genomhtml').disabled=true" class="btn btn-primary">Générer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal historique TAG-->
<div class="modal fade" id="modalhistotag"    role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
    <div class="modal-dialog" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModal2">Historique du TAG</h4>

            </div>
            <div class="modal-body">
                <div class="card-body">
                    <h5 style="font-size: 20px; font-weight: 900; color: slategrey;" id="taghistoname"></h5>
                    <table class="table table-striped" id="tabletagshisto" style="width:100%;margin-top:15px;font-size: 12px!important;">
                            <thead>
                            <tr id="headtable">
                                <th style="">Type</th>
                                <th style="">Description</th>
                                <th style="">Date</th>
                                <th style="">Montant</th>
                                <th style="">Reste</th>
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
<!-- Modal historique doc-->
<div class="modal fade" id="modalhistodoc"    role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
    <div class="modal-dialog" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModal2">Historique du document</h4>

            </div>
            <div class="modal-body">
                <div class="card-body">
                    <h5 style="font-size: 20px; font-weight: 900; color: slategrey;" id="dochistoname"></h5>
                    <table class="table table-striped" id="tabledocshisto" style="width:100%;margin-top:15px;">
                            <thead>
                            <tr id="headtable">
                                <th style="">Date de génération</th>
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
<!-- Modal historique OM TAXI-->
<div class="modal fade" id="modalhistoom"    role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">

    <div class="modal-dialog" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModal2">Historique de l'ordre de mission</h4>

            </div>
            <div class="modal-body">
                <div class="card-body">
                    <h5 style="font-size: 20px; font-weight: 900; color: slategrey;" id="dochistoname"></h5>
                    <table class="table table-striped" id="tableomshisto" style="width:100%;margin-top:15px;">
                            <thead>
                            <tr id="headtable">
                                <th style="">Date de génération</th>
                                <th style="">Statut</th>
                                <th style="">Validé par</th>
                                <th style="">Actions</th>
                             </tr>

                            </thead>
                            <tbody>
                            </tbody>
                    </table>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

 <div class="modal fade" id="myworkflowMAA" role="dialog" >
    <div class="modal-dialog modal-lg" >
    
      <!-- Modal content-->
      <div class="modal-content" >
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 id="titleworkflowmodalMAA" class="modal-title"></h4>
        </div>
        <div class="modal-body">       

  <div id="contenumodalworkflowMAA" style="background-color: #ABF8F8;padding:5px 5px 5px 5px" >
        
  </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
        </div>
      </div>
      
    </div>
  </div> <!-- fin modal workflow-->

<?php if ((Gate::check('isAdmin') || Gate::check('isSupervisor'))) { ?>
<!-- Modal attribution dossier-->
<div class="modal fade" id="attrmodal" role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <form  method="post" action="{{ route('affectation.dossier') }}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModal2">Affectation de dossier</h5>

            </div>
            <div class="modal-body">
                <div class="card-body">

                    <div class="form-group">
                        
                        
                            {{ csrf_field() }}
                            <input id="dossierid" name="dossierid" type="hidden" value="{{ $dossier->id}}">
                            <input id="affecteurdoss" name="affecteurdoss" type="hidden" value="{{ Auth::user()->id}}">
                            <input id="statdoss" name="statdoss" type="hidden" value="existant">

                            <div class="form-group " >
                                <div class=" row  ">
                                    <div class="form-group mar-20">
                                        <label for="agent" class="control-label" style="padding-right: 20px">Agent</label>
                                        <select id="agent" name="agent" class="form-control select2" style="width: 230px">
                                            <option value="Select">Selectionner</option>
                                            <?php $agents = User::get(); ?>
                                           
                                                @foreach ($agents as $agt)
 <?php if ( $agt['user_type']!= 'financier' &&  $agt['user_type']!= 'bureau'  ){ ?>
                                                <?php if ( ($dossier->affecte >0) && $agentname["id"] == $agt["id"]){ ?>
                                                    <option value={{ $agt["id"] }} selected >{{ $agt["name"].' '.$agt["lastname"] }}</option> <?php
                                                }else{
                                                  if ( $agt->isOnline() ) { ?>

                                                    <option value={{ $agt["id"] }} >{{ $agt["name"] .' '.$agt["lastname"] }}</option>

                                                <?php }
                                                }}
                                                ?>
                                                @endforeach    
                                        </select>
                                    </div>
                                </div>
                            </div>
                      

                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="submit" id="attribdoss" class="btn btn-primary">Affecter</button>
            </div>
        </div>
          </form>
    </div>
</div>

<!-- Modal Email -->
<div class="modal fade" id="adding7"    role="dialog" aria-labelledby="exampleModal7" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModal7">Ajouter une adresse Email </h5>

            </div>
            <div class="modal-body">
                <div class="card-body">

                    <div class="form-group">

                        <form   id="fggf" name="">
                            {{ csrf_field() }}

                            <div class="form-group " >
                                <label for="adresse">Nom</label>
                                <div class=" row  ">
                                    <input class="form-control" type="text" required id="nome"/>

                                </div>
                            </div>
                            <div class="form-group " >
                                <label for="adresse">Prénom</label>
                                <div class=" row  ">
                                    <input class="form-control" type="text" required id="prenome"/>

                                </div>
                            </div>

                            <div class="form-group " >
                                <label for="adresse">Fonction</label>
                                <div class=" row  ">
                                    <input class="form-control" type="text" required id="fonctione"/>

                                </div>
                            </div>

                            <div class="form-group ">
                                <label for="code">Adresse Email</label>
                                <div class="row">
                                    <input type="email"   class="form-control"  id="emaildoss" />

                                </div>
                            </div>

                            <div class="form-group ">
                                <label for="code">Remarque</label>
                                <div class="row">
                                    <textarea   class="form-control"  id="remarquee" ></textarea>

                                </div>
                            </div>

                            <input id="natureem" name="nature" type="hidden" value="emaildoss">


                        </form>
                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <span type="button" id="btnaddemail" class="btn btn-primary">Ajouter</span>
            </div>
        </div>
    </div>
</div>


<!-- Modal Tel -->
<div class="modal fade" id="adding6"    role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" >Ajouter une numéro Tel </h5>

            </div>
            <div class="modal-body">
                <div class="card-body">

                    <div class="form-group">

                        <form   id="fghgf" name="">
                            {{ csrf_field() }}

                            <div class="form-group " >
                                <label for="adresse">Nom</label>
                                <div class=" row  ">
                                    <input class="form-control" type="text" required id="nomt"/>

                                </div>
                            </div>
                            <div class="form-group " >
                                <label for="adresse">Prénom</label>
                                <div class=" row  ">
                                    <input class="form-control" type="text" required id="prenomt"/>

                                </div>
                            </div>

                            <div class="form-group " >
                                <label for="adresse">Fonction</label>
                                <div class=" row  ">
                                    <input class="form-control" type="text" required id="fonctiont"/>

                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="code">Tel</label>
                                <div class="row">
                                    <input type="text"   class="form-control"  id="teldoss" />

                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="code">Remarque</label>
                                <div class="row">
                                    <textarea   class="form-control"  id="remarquet" ></textarea>

                                </div>
                            </div>
                            <input id="naturetel" name="nature" type="hidden" value="teldoss">

                        </form>
                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <span type="button" id="btnaddtel" class="btn btn-primary">Ajouter</span>
            </div>
        </div>
    </div>
</div>




<?php } ?>



<!-- Modal -->
    <div class="modal fade" id="createfacture"    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter une facture</h5>

                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <form method="post" >
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="type">Date d'arrivée :</label>
                                <input   id="date_arrive"   value='<?php echo date('d/m/Y'); ?>' class="form-control datepicker-default "  />

                            </div>


							<div class="form-group">
                                <label for="type">N° de Facture :</label>
                                <input class="form-control"  id="reference"  type="text" class="form-control input"   />

                            </div>

                            <div class="form-group">
                                <label for="type"> Dossier : </label>
                                     <?php         $dossiers = \App\Dossier::orderBy('created_at', 'desc')->get(); ?>
                                    <select id ="iddossier"  class="form-control " style="width: 100%;color:black!important;">
                                        <option></option>
                                        <?php foreach($dossiers as $ds)
                                        {
											if($ds->id== $dossier->id){$selected='selected="selected"';}else{$selected='';}
                                            echo '<option   '.$selected.' style="color:black!important" title="'.$ds->id.'" value="'.$ds->id.'"> '.$ds->reference_medic.' | '.$ds->subscriber_name .' '.$ds->subscriber_lastname .' </option>';}     ?>
                                    </select>
                             </div>
                        </form>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="button" id="addfacture" class="btn btn-primary">Ajouter</button>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="insererprest"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Insérer un Prestataire</h5>

                </div>
                <div class="modal-body" style="min-height:250px">
                    <div class="card-body">

                        <form method="post" >
                            {{ csrf_field() }}

                            <div class="form-group" style="margin-top:30px;ùargin-left:30px">
                                <label for="type">Prestataire :</label>
                                 <select id="selectable" style="margin-top:10px;margin-bottom:10px;width:350px"      class="form-control  "  >
                                    <option></option>
                                    <?php
                                        foreach($prestataires as $prest)
                                      {
                                     // $prestat= $pr['prestataire_id'];
                                       //$interv = PrestationsController::PrestById($prestat);
                                   //  if ($pr['prestataire_id'] != $inter['id'])
                                    //{ ?>

                                    <option    value="<?php echo $prest->id;?>"> <?php echo $prest->name .' '.$prest->prenom;?></option>
                              <?php // }
                                   // }
                                    }
                                    ?>
                                 </select>
                            </div>

                        </form>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="button" id="addpr2" class="btn btn-primary">Ajouter</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="createinterv"    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter un nouveau Prestataire</h5>

                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <form method="post" >
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="type">Nom :</label>
                                <input class="form-control" type="text" id="nom" />

                            </div>

                            <div class="form-group">
                                <label for="type">Prénom :</label>
                                <input class="form-control" type="text" id="prenom" />
                            </div>

                        </form>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="button" id="addpr1" class="btn btn-primary">Ajouter</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal SMS -->
    <div class="modal fade" id="sendsms" role="dialog" aria-labelledby="sendingsms" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal7">Envoyer un SMS </h5>

                </div>
                <form   >
               <!-- <form method="post" action="{{--action('EmailController@sendsms')--}}" >-->

<!-- change it to ajax-->
                    <div class="modal-body">
                        <div class="card-body">


                            <div class="form-group">
                                {{ csrf_field() }}

                                <div class="form-group">
                                    <input type="hidden" id ="ledossier"  name ="dossier"  class="form-control " value="<?php echo $dossier->id; ?>">


                                </div>


                            </div>


                            <div class="form-group">
                                {{ csrf_field() }}
                                <label for="description">Description:</label>
                                <input id="ladescription" type="text" class="form-control" name="description"     />
                            </div>

                            <div class="form-group">

                                <label for="destinataire">Destinataire:</label>
                                <input id="ledestinataire" type="number" class="form-control" name="destinataire"      />
                            </div>

                            <div class="form-group">
                                <label for="contenu">Message:</label>
                                <textarea  id="lemessage" type="text" class="form-control" name="message"></textarea>
                            </div>
                        {{--  {!! NoCaptcha::renderJs() !!}     --}}
                        <!--  <script src="https://www.google.com/recaptcha/api.js" async defer></script>-->




                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal" style="margin-left:30px">Fermer</button>
                        <span   id="envoisms" class="btn btn-md  btn-primary btn_margin_top"><i class="fa fa-paper-plane" aria-hidden="true"></i> Envoyer</span>
                    </div>
                </form>

            </div>
        </div>
    </div>




    <!-- Modal Ouvrir Attachement-->
    <div class="modal fade" id="openattach"  role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
        <div class="modal-dialog" role="document" style="width:900px;height: 450px">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attTitle"></h5>
                </div>
                <div class="modal-body">
                    <div class="card-body">


                        <iframe id="attachiframe" src="" frameborder="0" style="width:100%;min-height:640px;"></iframe>
                         <center>   <img style="max-width:800px" id="imgattach"/></center>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal Ouvrir Attachement Desc-->
    <div class="modal fade" id="openattachDesc"  role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
        <div class="modal-dialog" role="document" style=" ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attTitle2"></h5>
                </div>
                <div class="modal-body">
                    <div class="card-body"><br>

                        <div class="row">
                            <div class="col-md-6 pull-left" >
                                <b>Créé par : </b><span id="fileCreator"></span>
                            </div>
                            <div class="col-md-6 pull-right" >
                                <b>Taille : </b><span id="fileSize"></span>
                            </div>
                        </div><br>
                        <input type="hidden" id="selectedAttach"  />
                        <label ><b>Description :</b></label>
                        <center><textarea id="descAttach" onchange="updateDesc()" class="form-control" ></textarea> </center><br><br>
                        <button onclick=" deleteattach()" type="button" class="btn btn-danger pull-right"><i class="fa fa-trash" ></i> Supprimer l'attachement</button>
                        <br><br>
                     </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>




    <?php     $listedossiers = DB::table('dossiers')->get();?>

    <div class="modal  " id="crendu" >
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="text-align:center"  id="modalalert0"><center>Compte Rendu </center> </h5>
                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <input   id="iddossier"  type="hidden"  value="<?php echo $dossier->id ;?>" name="dossierid"     />
                        <input   id="refdossier"  type="hidden"  value="<?php echo $dossier->reference_medic ;?>" name="refdossier"     />

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
                            <label for="emetteur">Interlocuteur :</label>
                            <input type="text"    id="emetteur"   class="form-control" name="emetteur"    />

                        </div>

                        <div class="form-group">
                            <label for="sujet">Média :</label>
                            <select  id="mediacr"   class="form-control" name="mediacr"    >
                                <option value="Tel">Tel</option>
                                <option value="Email">Email</option>
                                <option value="Fax">Fax</option>
                                <option value="Poste">Poste</option>
                            </select>

                        </div>

                        <div class="form-group">
                            <label for="sujet">Contenu *:</label>
                            <textarea style="height:100px;" id="contenucr"   class="form-control" name="contenucr"    ></textarea>

                        </div>

                        <div class="form-group">
                            <label for="sujet">Description :</label>
                            <input style="overflow:scroll;" id="descriptioncr"   class="form-control" name="descriptioncr"    />

                        </div>


                    </div>

                </div>
                <div class="modal-footer">
                    <a id="ajoutcompter"   class="btn btn  "   style="background-color:#5D9CEC; width:100px;color:#ffffff"   >Ajouter</a>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width:100px">Annuler</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal  " id="openmodalprest" >
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" style="text-align:center"  id=" "><center>Ajouter une Prestation </center> </h5>
                </div>
                <div class="modal-body">
                    <div class="card-body">


                        <div class="form-group ">
                            <label for="selectedprest2">Prestataire :</label>
                           <input type="hidden" id="selectedprest2" />
                           <input type="text" readonly class="form-control" id="inputprest" />

                        </div>

                        <div class="form-group">
                            <label for="pres_date2">Date de Prestation :</label>
                            <input style="width:200px;" value="<?php echo date('d/m/Y');?>" class="form-control datepicker-default  " name="pres_date2" id="pres_date2" data-required="1" required="" aria-required="true">
                        </div>


                        <div class="form-group " >
                            <label>Type de prestations</label>
                            <div class=" row  ">
                                <select class="itemName form-control col-lg-12  " style="width:400px"     id="ajout_typeprest">
                                    <option></option>
                                    @foreach($typesprestations as $aKey)
                                        <option     value="<?php echo $aKey->id;?>"> <?php echo $aKey->name;?></option>
                                    @endforeach

                                </select>

                            </div>
                        </div>

                        <div class="form-group ">
                            <div class="row">
                                <label>Spécialité </label>
                            </div>
                            <div class="row">
                                <select class="form-control  col-lg-12 " style="width:400px"     id="ajout_specialite">
                                    <option value="0"></option>
                                    @foreach($specialites as $sp)
                                        <?php     $specialite_tprestation = DB::table('specialites_typeprestations')
                                        ->where([
                                        ['specialite', '=', $sp->id],
                                        ])->first();
                                        if (isset($specialite_tprestation->type_prestation))
                                        {$stprest = $specialite_tprestation->type_prestation;
                                        ?>
                                        <option  class="add-tprest  tprest-<?php echo $stprest;?>" value="<?php echo $sp->id;?>"> <?php echo $sp->nom;?></option>
                                        <?php } else { ?>
                                        <option  class="add-tprest" value="<?php echo $sp->id;?>"> <?php echo $sp->nom;?></option>
                                        <?php
                                        } ?>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group ">
                            <div class="row">
                                <label>Gouvernorat </label>
                            </div>

                            <div class="row">
                                <select class="form-control  col-lg-12 " style="width:400px" name="gouvernorat"   required   id="pres_gouv">
                                    <option></option>
                                    @foreach($gouvernorats as $aKeyG)
                                        <option  <?php if($gouvernorat==$aKeyG->id){echo 'selected="selected"';}?>  value="<?php echo $aKeyG->id;?>"> <?php echo $aKeyG->name;?></option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="form-group ">
                            <div class="row">
                                <label>Ville</label>
                            </div>
                            <div class="row" style=";margin-bottom:10px;"><style>.algolia-places{width:80%;}</style>
                            </div>
                            <input class="form-control"  style="padding-left:5px" type="text" placeholder="toutes"  name="ville" id="villepr3" />
                            <input class="form-control" style="padding-left:5px;" type="hidden" name="postal" id="villecode3" />

                        </div>

                        <script>
                            (function() {
                                var placesAutocomplete4 = places({
                                    appId: 'plCFMZRCP0KR',
                                    apiKey: 'aafa6174d8fa956cd4789056c04735e1',
                                    container: document.querySelector('#villepr3'),
                                });
                                placesAutocomplete4.on('change', function resultSelected(e) {
                                    document.querySelector('#villecode3').value = e.suggestion.postcode || '';
                                });
                            })();
                        </script>

                        <div class="form-group">
                            <label for="sujet">Autorisé Par :</label>
                            <select  required id="autorise" class="form-control"  style="width:350px" >
                                <option value="">Veuillez sélectionnez l'autorisation</option>
                                <option value="procedure">Engagé au préalable</option>
                                <option value="nejib">Dr Nejib</option>
                                <option value="salah">Dr Salah Harzallah</option>
                                <option value="mahmoud">Dr Mahmoud HELALI</option>
                                <option value="maher">Mr Maher BEN OTHMANE</option>
                            </select>

                        </div>

                        <div class="form-group">
                            <label for="details">Détails   :</label>
                            <textarea  id="details" class="form-control"  ></textarea>

                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button id="selectionnerprest"  onclick="document.getElementById('selectionnerprest').disabled=true"      class="btn btn  "   style="background-color:#5D9CEC; width:100px;color:#ffffff"   >Ajouter</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width:100px">Annuler</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal  " id="OuvrirDoss" >
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" style="text-align:center"  id=" "><center>Ouvrir le Dossier </center> </h3>
                </div>
                <div class="modal-body">
                    <div class="card-body" style="text-align:center;height:100px"><br>
                        <center><B> Etes vous sûrs de vouloir Ré-Ouvrir ce Dossier ?</B><br><br></center>
                        <a id="ouvrirdossier"   class="btn btn  "   style="background-color:#5D9CEC; width:100px;color:#ffffff"   >OUI</a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width:100px">Annuler</button><br>


                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width:100px">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal  " id="FermerDoss" >
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" style="text-align:center"  id=" "><center>Clôturer le Dossier </center> </h3>
                </div>
                <div class="modal-body">
                    <div class="card-body" style="text-align:center;height:200px"><br>
<?php  if($type=='admin' || $type=='bureau' ||$type=='financier' ){ ?>			
			<section><center><a target="_blank" href="{{action('DossiersController@fermeture',$dossier->id)}}" >Contrats (type de dossier) </a><br>
				<br></center></section><br>
				
<?php    
			}
app('App\Http\Controllers\MissionController')->verifier_fin_missions($dossier['id']);
$count= Mission::where('dossier_id',$dossier['id'])
                        ->where('statut_courant','!=','annulee')
                        ->where('statut_courant','!=','achevee')
                        ->count();
if($count==0) {
?>
                        <center><B> Etes vous sûrs de vouloir clôturer ce Dossier ?</B><br> <br> </center>
                       <section><center><label  style="width:250px;text-align:center;;" class="check "> Fermer Sans suite
                            <input type="checkbox" id="sanssuite" class="form-control"></input>
                            <span class="checkmark"   ></span>  
                            </label>
							 </center></section>
				
							<br>
                        <a id="fermerdossier"   class="btn btn  "   style="background-color:#5D9CEC; width:100px;color:#ffffff"   >OUI</a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width:100px">Annuler</button><br>

<?php }else{ ?>
                        <br> <br>  <center><B> Vous devez terminer toutes les missions pour pouvoir clôturer ce Dossier </B><br> <br> </center>


                    <?php          } ?>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width:100px">Fermer</button>
                </div>
            </div>
        </div>
    </div>


    <?php
 $param= App\Parametre::find(1);$env=$param->env;
$urlapp="http://$_SERVER[HTTP_HOST]/".$env;
?>

    <!--Modal Tel-->

    <div class="modal fade" id="faireappel"    role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
        <div class="modal-dialog" role="tel">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal2">Choisir le numéro</h5>

                </div>
                <div class="modal-body">
                    <div class="card-body" sytle="height:300px">
<?php use App\Intervenant;
                        ;?>

                        <div class="form-group">
                            {{ csrf_field() }}

                            <form id="faireappel" novalidate="novalidate">

                                <input id="dossier" name="dossier" type="hidden" value="{{ $dossier->id}}" />
                                     <label for="emaildoss">Numéro</label>

                                <select  class="form-control" id="numtel" name="numtel"   >
                                 <option value=""></option>
                                    
                                     <?php foreach($phonesDossier   as $phone)
                                        {
$title=$phone->nom.' '.$phone->prenom.' ( '.$phone->remarque .' ) ';
                                    echo '<option class="telsassures" title="'.$title.'"  value="'.$phone->champ.'">'.$phone->champ.'  ('.$phone->nom.' '.$phone->prenom.'  | '.$phone->remarque.')</option>';
                                    }
                                    ?>
                                          <?php foreach($phonesCl   as $phone)
                                        {
$title=$phone->nom.' '.$phone->prenom.' ( '.$phone->remarque .' ) ';
                                        echo '<option class="telsclients" title="'.$title.'"  value="'.$phone->champ.'">'.$phone->champ.'  ( '.$phone->nom.' '.$phone->prenom.' | '.$phone->remarque.') </option>';
                                        }
                                        ?>
                                         <?php foreach($phonesInt   as $phone)
                                        {
$title=$phone->nom.' '.$phone->prenom.' ( '.$phone->remarque .' ) ';
                                        echo '<option class="telsintervs" title="'.$title.'"  value="'.$phone->champ.'">'.$phone->champ.'  ('.$phone->nom.' '.$phone->prenom.' | '.$phone->remarque.')</option>';
                                        }
                                        ?>

                                 </select>
                            </form>

                        </div>
                    </div>

                </div>

                <div class="modal-footer">
<?php
$idagent=$dossier->user_id;
         $CurrentUser = auth()->user();
         $iduser=$CurrentUser->id;

if($iduser===32)
{
?>
                        <input id="extensiontel" name="extensiontel" type="hidden" value="2000">
                        <input id="motdepassetel" name="motdepassetel" type="hidden" value="3862oOPD3F">
<?php
}
else
{
?>
 <input id="extensiontel" name="extensiontel" type="hidden" value="2001">
                        <input id="motdepassetel" name="motdepassetel" type="hidden" value="z6Hm&FqQF2G@S3">
<?php
}
?>

                    <button type="button"  class="btn btn-primary"  onclick="ButtonOnclick();">Appeler</button>
   
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>

                </div>
            </div>

        </div>

    </div>
   <!--Modal Tel-->

    <div class="modal fade" style="z-index:10000!important;left: 20px;" id="numatransfer1"    role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
        <div class="modal-dialog" role="numatransfer1">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal2">Saisir le numéro</h5>

                </div>
                <div class="modal-body">
                    <div class="card-body" sytle="height:300px">

                        <div class="form-group">
                            {{ csrf_field() }}

                            <form id="numatransfer1" novalidate="novalidate">

                                <input id="numatrans1" name="numatrans1" type="text" value="" />
                                   
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
<?php

?>

                    <button type="button"  class="btn btn-primary"  onclick="transfer1();">Transférer</button>
   
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>

                </div>
            </div>

        </div>

    </div>
<!--Modal Tel 2-->

    <div class="modal fade" id="appelinterfaceenvoi"    role="dialog" aria-labelledby="exampleModal2" aria-hidden="true" data-backdrop="static"  data-keyboard="false" >
        <div class="modal-dialog" role="telenvoi"  sytle="width:20px;height:10px">
            <div class="modal-content">
                <div class="modal-header">
                  <h3 class="modal-title" style="text-align:center"  id=" "><center>Passer un appel </center> </h3>
</div>
                <div class="modal-body">
                    <div class="card-body" >


                        <div class="form-group">
                            {{ csrf_field() }}

                            <form id="appelinterfaceenvoi" novalidate="novalidate">
  <div id="call_duration">&nbsp;</div>
                <div style="font-size: 30px;">

<label style="color:green;font-size: 30px;"id="status_callenv"></label>


<label style="margin-left:150px;font-size: 30px;"id="min2"></label>
<label style="font-size:30px;" id="sec2"></label>

</div>
<input id="nomencours" name="nomencours" type="text" readonly value="" style="font-size: 30px;border: none;">
 <div>
<input id="numencours" name="numencours" type="text" readonly value="" style="font-size: 30px;border: none;">
</div>
<div id='compterendudossierencours' style="display:none"><label style="color:green;font-size: 30px;">Compte rendu</label>
    <div class="form-group">
                            <label for="sujetcrteldossierencours">Sujet :</label>
                            <input type="text"    id="sujetcrteldossierencours"   class="form-control" name="sujetcrteldossierencours"    />

                        </div>

                        <div class="form-group">
                            <label for="descriptioncrteldossierencours">Description :</label>
                            <input style="overflow:scroll;" id="descriptioncrteldossierencours"   class="form-control" name="descriptioncrteldossierencours"    />

                        </div>

                        <div class="form-group">
                            <label for="contenucrteldossierencours">Contenu *:</label>
                            <textarea style="height:100px;" id="contenucrteldossierencours"   class="form-control" name="contenucrteldossierencours"    ></textarea>

                        </div>      
 </div> 
                            </form>

                        </div>
                    </div>

                </div>

                <div class="modal-footer">


                   
 <button id="racc" type="button"  class="btn btn-primary"  onclick="Hangup1();"><i class="fas fa-phone-slash"></i> Raccrocher</button>
 <div id="mettreenattenteenv" style="display:none;"><button type="button"  class="btn btn-primary" onclick="hold1(true);" ><i class="fas fa-pause"></i> Mettre en attente</button></div>
 <div id="reprendreappelenv" style="display:none;"><button type="button"  class="btn btn-primary"  onclick="hold1(false);"><i class="fas fa-phone"></i> Reprendre</button></div>
 <div id="coupersonenv" style="display :none;"><button type="button"  class="btn btn-primary" onclick="mute1(true,0);" ><i class="fas fa-microphone-slash"></i> Couper le son</button></div>
 <div id="reactivesonenv" style="display:none;"><button type="button"  class="btn btn-primary"  onclick="mute1(false,0);"><i class="fas fa-microphone"></i> Réactiver son</button></div>
 <button id="transferappenv" style="display:none;" type="button"  class="btn btn-primary" data-toggle="modal" data-target="#numatransfer1"><i class="fas fa-reply-all"></i> Transférer</button>
<button type="button" class="btn btn-secondary reloadclass" data-dismiss="modal">Fermer</button>
              <!--<button type="button"  class="btn btn-primary"  onclick="transfer();">Transférer</button>    
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>!-->

                </div>
            </div>

        </div>

    </div>

    <!-- model pour délégation des missions-->

<div class="modal fade" id="ajouterfichier" role="dialog" aria-labelledby="exampleModal2" aria-hidden="true" >
    <div class="modal-dialog" role="document">
       <form  id="formFileExterne" method="post" enctype="multipart/form-data" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id=""><center>Ajouter un fichier dans ce dossier </center></h4>

            </div>
            <div class="modal-body">
                <div class="card-body">

                    <div class="form-group">
                        
                          {{ csrf_field() }}


                        <input id="ExterneFiledossid" name="ExterneFiledossid" type="hidden" value="{{$dossier->id}}">
                        <input id="ExterneFiledossRef" name="ExterneFiledossRef" type="hidden" value="{{$dossier->reference_medic}}">
                            <div class="form-group " >
                                <div class=" row  ">
                                    <div class="form-group mar-20">

                                        <div class="form-group " >
                                        <label for="fileExterneDoss" class=" control-label" style="font-weight:bold">Fichier *</label>
                                     <input class="from-control" type="file" name="fileExterneDoss" id="fileExterneDoss" />
                                        </div>

                                        <div class="form-group " >
                                        <label for="titrefileExterne" class="control-label " style="font-weight:bold">Nouveau nom (optionnel)</label><br>
                                     <input style="width:100% " type="text" name="titrefileExterne" class="from-control" id="titrefileExterne" />
                                        </div>

                                     <div class="form-group">
                                        <label for="descripfileExterne" class=" control-label" style="font-weight:bold">Description (optionnel)</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                     <textarea class="form-control" style=" height:100px;"  name="descripfileExterne" id="descripfileExterne" ></textarea>
                                     </div>
                          <br>
                                            
                    <div style="align:center" id="successUloadExterneFile">
                    </div>                                                
                                    </div>
                                </div>
                            </div>                    
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="submit" id="UploadExterneFile" class="btn btn-primary">Télécharger</button>
            </div>
        </div>
          </form>
    </div>
</div>

<input type="hidden" name="cnctuserid" id="cnctuserid" value="<?php echo Auth::user()->id; ?>">




@endsection

 <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script> 
  <!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script>  -->
    <!-- <script src="http://malsup.github.com/jquery.form.js"></script> -->

<script src="https://cdn.jsdelivr.net/npm/places.js@1.16.4"></script>

<script src="{{ asset('public/js/select2/js/select2.js') }}"></script>

<script>
$('.reloadclass').click(function(){
 
                            window.location.reload();
});
//script pour activer l onglet OM si lurl contient le mot CreerOM 
 $(document).ready(function() {
 
 
             $('#addfacture').click(function(){
                var date_arrive = $('#date_arrive').val();
                var reference = $('#reference').val();
                var dossier = $('#iddossier').val();
                if ((date_arrive != '' ) || (reference != '' )   )
                {
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('factures.saving') }}",
                        method:"POST",
                        data:{reference:reference,date_arrive:date_arrive,dossier:dossier, _token:_token},
                        success:function(data){
                            //   alert('Added successfully');
                            window.location =data;
                        }
                    });
                }else{
                    // alert('ERROR');
                }
            });
			
			
                     var urllocale=top.location.href;
                     var posit=urllocale.indexOf("/dossiers/view/CreerOM/");
                      // alert(pos);
                     if(posit != -1)
                     {
                        var urlloc2=urllocale;
                       var n = urllocale.lastIndexOf("/");
                       var res = urllocale.substr(n+1);
                       document.location.href=urlloc2+"#tab7";
                       //location.reload();
                   //  alert(res);
                    // $('#ViewDosstabs a[href="#tab7"]').trigger();
                       $('#idMissionOM').val(res);
                       // alert( $('#idEntreeMissionOnMarker').val());
                       //alert($('#idMissionOM').val());
                       if(res.indexOf('#')!=-1)
                       {
                           var re = res.substr(0,(res.indexOf('#')));
                            $('#idMissionOM').val(re);
                             //alert($('#idMissionOM').val()+"kkk");
                       }
                     }
      });
</script>
<script>
//script pour activer l onglet OM si lurl contient le mot CreerDoc 
 $(document).ready(function() {
                     var urllocale=top.location.href;
                     var posit=urllocale.indexOf("/dossiers/view/CreerDoc/");
                      // alert(pos);
                     if(posit != -1)
                     {
                        var urlloc2=urllocale;
                       var n = urllocale.lastIndexOf("/");
                       var res = urllocale.substr(n+1);
                       document.location.href=urlloc2+"#tab6";
                       //location.reload();
                   //  alert(res);
                    // $('#ViewDosstabs a[href="#tab7"]').trigger();
                       $('#idMissionDoc').val(res);
                       // alert( $('#idEntreeMissionOnMarker').val());
                       //alert($('#idMissionOM').val());
                       if(res.indexOf('#')!=-1)
                       {
                           var re = res.substr(0,(res.indexOf('#')));
                            $('#idMissionDoc').val(re);
                             //alert($('#idMissionOM').val()+"kkk");
                       }
                     }
      });
</script>
<script src="{{ asset('public/js/nombre_en_lettre.js') }}"></script>

<script>
    function ShowNumsCc() {
        $('.telsassures').css('display','none');
        $('.telsintervs').css('display','none');
        $('.telsclients').css('display','block');
    }
    function ShowNumsInt() {
        $('.telsassures').css('display','none');
        $('.telsintervs').css('display','block');
        $('.telsclients').css('display','none');
    }
    function ShowNumsAss() {
        $('.telsassures').css('display','block');
        $('.telsintervs').css('display','none');
        $('.telsclients').css('display','none');
    }
    function hideinfos() {
        $('#tab31').css('display','none');
    }
    function hideinfos2() {
        $('#tab32').css('display','none');
    }
    function hideinfos3() {
        $('#tab33').css('display','none');
    }
    function showinfos() {
        $('#tab31').css('display','block');
    }
    function showinfos2() {
        $('#tab32').css('display','block');
    }
    function showinfos3() {
        $('#tab33').css('display','block');
    }
    function hideinfos81()
    {
     $('#tab81').css('display','none');
    }
    function hideinfos82()
    {
     $('#tab82').css('display','none');
    }
    function showinfos81() {
        $('#tab81').css('display','block');
    }
    function showinfos82() {
        $('#tab82').css('display','block');
    }
    function modalattach(titre,emplacement,type)
    {
        document.getElementById('attachiframe').style.display='none';
        document.getElementById('imgattach').style.display='none';
          $("#attTitle").text(titre);
        if  (type ==  'pdf')
        {
            document.getElementById('attachiframe').src =emplacement;
            document.getElementById('attachiframe').style.display='block';
            // document.getElementById('attachiframe').src =emplacement;
        }
        if ( (type ==  'doc') || ( type ==  'docx'  ) || ( type ==  'xls'  ) || ( type ==  'xlsx'  )  )
        {document.getElementById('attachiframe').src ="https://view.officeapps.live.com/op/view.aspx?src="+emplacement;
            document.getElementById('attachiframe').style.display='block';
        }
        if ( (type ==  'png') || (type ==  'jpg') ||(type ==  'jpeg' ) )
        {
            document.getElementById('imgattach').style.display='block';
            document.getElementById('imgattach').src =emplacement;
           // document.getElementById('attachiframe').src =emplacement;
        }
        // cas DOC fichier DOC
        $("#openattach").modal('show');
    }
    function modalattach2(id,description,titre,taille,par)
    {          $("#attTitle2").text(titre);
        document.getElementById('selectedAttach').value=id;
        document.getElementById('descAttach').value=description;
        document.getElementById('fileCreator').innerHTML=par;
        document.getElementById('fileSize').innerHTML=taille;
        $("#openattachDesc").modal('show');
    }
function remplaceom(id,affectea,verif)
{
    document.getElementById('claffect1').style.display = 'block';
    document.getElementById('claffect2').style.display = 'block';
    if (verif === "ommie")
     { document.getElementById('claffect1').style.display = 'none';
            document.getElementById('claffect2').style.display = 'none';}
        if (affectea !== undefined && affectea !== null && affectea !== '')
        {
            //$("#affectationprest").val(affectea).change();
            document.getElementById('claffect1').style.display = 'none';
            document.getElementById('claffect2').style.display = 'none';
            document.getElementById('typeprest').style.display = 'none';
            $('#affectea').val(affectea);
            
        }
    //ajout id user conncte
    var cnctuserid = $("#cnctuserid").val();
 var dossier = $('#dossom').val();
    if(verif==='omtx')
    var url = '<?php echo url('/'); ?>/public/preview_templates/odm_taxi.php?remplace=1&parent='+id+'&iduser='+cnctuserid+'&DB_HOST='+'<?php echo env("DB_HOST"); ?>'+'&DB_DATABASE='+'<?php echo env("DB_DATABASE"); ?>'+'&DB_USERNAME='+'<?php echo env("DB_USERNAME"); ?>'+'&DB_PASSWORD='+'<?php echo env("DB_PASSWORD"); ?>';
     if(verif==='omamb')
    var url = '<?php echo url('/'); ?>/public/preview_templates/odm_ambulance.php?remplace=1&parent='+id+'&iduser='+cnctuserid+'&DB_HOST='+'<?php echo env("DB_HOST"); ?>'+'&DB_DATABASE='+'<?php echo env("DB_DATABASE"); ?>'+'&DB_USERNAME='+'<?php echo env("DB_USERNAME"); ?>'+'&DB_PASSWORD='+'<?php echo env("DB_PASSWORD"); ?>';
    if(verif==='omre')
        var url = '<?php echo url('/'); ?>/public/preview_templates/odm_remorquage.php?remplace=1&parent='+id+'&iduser='+cnctuserid+'&DB_HOST='+'<?php echo env("DB_HOST"); ?>'+'&DB_DATABASE='+'<?php echo env("DB_DATABASE"); ?>'+'&DB_USERNAME='+'<?php echo env("DB_USERNAME"); ?>'+'&DB_PASSWORD='+'<?php echo env("DB_PASSWORD"); ?>';
    if(verif==='ommie')
        var url = '<?php echo url('/'); ?>/public/preview_templates/odm_medic_international.php?remplace=1&parent='+id+'&iduser='+cnctuserid+'&dossier='+dossier+'&DB_HOST='+'<?php echo env("DB_HOST"); ?>'+'&DB_DATABASE='+'<?php echo env("DB_DATABASE"); ?>'+'&DB_USERNAME='+'<?php echo env("DB_USERNAME"); ?>'+'&DB_PASSWORD='+'<?php echo env("DB_PASSWORD"); ?>';
         document.getElementById("omfilled").src = url;
         $("#idomparent").val(id);
        $('#templateordrem').val("remplace");
        
        $("#templatehtmlom").modal('show');
 }
function completeom(id,affectea,verifc)
{
    document.getElementById('claffect1').style.display = 'block';
    document.getElementById('claffect2').style.display = 'block';
    //ajout id user conncte
    var cnctuserid = $("#cnctuserid").val();
    if(verifc==='omtx')
    {var url = '<?php echo url('/'); ?>/public/preview_templates/odm_taxi.php?complete=1&parent='+id+'&iduser='+cnctuserid+'&DB_HOST='+'<?php echo env("DB_HOST"); ?>'+'&DB_DATABASE='+'<?php echo env("DB_DATABASE"); ?>'+'&DB_USERNAME='+'<?php echo env("DB_USERNAME"); ?>'+'&DB_PASSWORD='+'<?php echo env("DB_PASSWORD"); ?>';}
    if(verifc==='omamb')
    {var url = '<?php echo url('/'); ?>/public/preview_templates/odm_ambulance.php?complete=1&parent='+id+'&iduser='+cnctuserid+'&DB_HOST='+'<?php echo env("DB_HOST"); ?>'+'&DB_DATABASE='+'<?php echo env("DB_DATABASE"); ?>'+'&DB_USERNAME='+'<?php echo env("DB_USERNAME"); ?>'+'&DB_PASSWORD='+'<?php echo env("DB_PASSWORD"); ?>';}
    if(verifc==='omre')
    {var url = '<?php echo url('/'); ?>/public/preview_templates/odm_remorquage.php?complete=1&parent='+id+'&iduser='+cnctuserid+'&DB_HOST='+'<?php echo env("DB_HOST"); ?>'+'&DB_DATABASE='+'<?php echo env("DB_DATABASE"); ?>'+'&DB_USERNAME='+'<?php echo env("DB_USERNAME"); ?>'+'&DB_PASSWORD='+'<?php echo env("DB_PASSWORD"); ?>';}
         document.getElementById("omfilled").src = url;
         $("#idomparent").val(id);
        $('#templateordrem').val("complete");
        if (affectea !== undefined && affectea !== null && affectea !== '')
        {
            //$("#affectationprest").val(affectea).change();
            document.getElementById('claffect1').style.display = 'none';
            document.getElementById('claffect2').style.display = 'none';
            document.getElementById('typeprest').style.display = 'none';
            $('#affectea').val(affectea);
        }
        
        $("#templatehtmlom").modal('show');
 }
function modalodoc(titre,emplacement,type=null,parent=null,commentaire=null,idutag=null,name=null,emp=null,existe=null)
{

//alert(existe);
//alert(type);
//alert(parent);
if(existe=='0')
{

 document.getElementById('attachdoc').style.display = 'inline';
 

}
else
{

 document.getElementById('attachdoc').style.display = 'none';

}

     $("#doctitle").text(titre);
     if (commentaire != null)
     {$("#apercucomment").text(commentaire);
        document.getElementById('apercucomment').style.display = 'block';
      } else
      {
        document.getElementById('apercucomment').style.display = 'none';
      }
 
var dossier = $('#dossier').val();
//alert(idutag);
      //<i class="fas fa-tag"></i> 
      if (idutag)
     {
var typedossier = $('#typedossier').val();

        var _token = $('input[name="_token"]').val();
var count = $('#countgarantie').val();


if(typedossier==='Najda TPA' && count !== '0')
{

 $.ajax({
                url:"{{ route('garanties.inforubrique') }}",
                method:"POST",
                //'&_token='+_token
                data:'_token='+_token+'&rubrique='+idutag,
                dataType: 'json',
                success:function(data){
                    if ($.trim(data)){ 


if(data['commentaire']!==null)
{comment=data['commentaire'];}
else{
comment="";
}
                        $("#tagudoc").html("<i class='fas fa-tag'></i> "+data['nom']+" : "+data['created_at']);
                    }
                }
            });


}
else

{

        $.ajax({
                url:"{{ route('tags.infotag') }}",
                method:"POST",
                //'&_token='+_token
                data:'_token='+_token+'&tag='+idutag,
                dataType: 'json',
                success:function(data){
                    if ($.trim(data)){ 

                        $("#tagudoc").html("<i class='fas fa-tag'></i> "+data['titre']+" : "+data['contenu']+" | "+data['created_at']);
                    }
                }
            });}


        document.getElementById('tagudoc').style.display = 'block';
      } else
      {
        document.getElementById('tagudoc').style.display = 'none';
      }
    // cas OM fichier PDF
    /*if (emplacement.indexOf("/OrdreMissions/") !== -1 )
    {*/
        document.getElementById('dociframe').src =emplacement;
    /*}
    else
    // cas DOC fichier DOC
    {
        document.getElementById('dociframe').src ="https://view.officeapps.live.com/op/view.aspx?src="+emplacement;
    }*/
    $("#opendoc").modal('show');

 $('#attachdoc').click(function(){
 var _token = $('input[name="_token"]').val();
if(type==='doc')
{
 $.ajax({
                        url:"{{ route('documents.attachdocs') }}",
                        method:"POST",
                        data:{emplacement:emp,titre:titre,dossier:dossier,name:name, _token:_token},
                        success:function(data){
                            
                            location.reload();

                        }
                    });}
if(type==='om')
{
 $.ajax({
                        url:"{{ route('ordremissions.attachoms') }}",
                        method:"POST",
                        data:{emplacement:emplacement,titre:titre,dossier:dossier,parent:parent, _token:_token},
                        success:function(data){
                            
                            location.reload();

                        }
                    });}

  
 });
}
function remplacedoc(modif,iddoc,template,montantgopprec,idgopprec)
{
if(modif===0)
{document.getElementById('modif').value=0;}
if(modif===1)
{document.getElementById('modif').value=1;}
        var dossier = $('#dossier').val();
        var tempdoc = template;
        if ((dossier != '') )
        {
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('documents.htmlfilled') }}",
                method:"POST",
                data:{dossier:dossier,modif:modif,template:tempdoc,parent:iddoc, _token:_token},
                success:function(data){
                        // set iddocparent value
                        $('#iddocparent').val(iddoc);
                        filltemplate(data,tempdoc,montantgopprec,idgopprec);
                 }
            });
        }else{
         }
}
function annuledoc(titre,iddoc,template)
{
        var dossier = $('#dossier').val();
        var tempdoc = template;
        //ajout id user conncte
        var cnctuserid = $("#cnctuserid").val();
        $("#gendochtml").prop("disabled",false);
        
         var r = confirm("Êtes-vous sûr de vouloir Annuler le document: "+titre+" ? ");
        if (r == true) {
          if ((dossier != '') )
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('documents.canceldoc') }}",
                    method:"POST",
                    data:{dossier:dossier,template:tempdoc,parent:iddoc,iduser:cnctuserid, _token:_token},
                success:function(data){
                    console.log(data);
                    location.reload();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                     Swal.fire({
                        type: 'error',
                        title: 'Oups...',
                        text: 'Erreur lors de lannulation du document',
                    });
                    console.log('jqXHR:');
                    console.log(jqXHR);
                    console.log('textStatus:');
                    console.log(textStatus);
                    console.log('errorThrown:');
                    console.log(errorThrown);
                }
                });
            }
        }
}
function annuleom(titre,iddoc)
{
        //ajout id user hs change
        var cnctuserid = $("#cnctuserid").val();
        var dossier = $('#dossier').val();
        $("#genomhtml").prop("disabled",false);
        
         var r = confirm("Êtes-vous sûr de vouloir Annuler l'ordre de mission: "+titre+" ? ");
        if (r == true) {
          if ((dossier != '') )
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('ordremissions.cancelom') }}",
                    method:"POST",
                    data:{dossier:dossier,title:titre,parent:iddoc,iduser:cnctuserid, _token:_token},
                success:function(data){
                    //alert(data);
                    location.reload();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                     Swal.fire({
                        type: 'error',
                        title: 'Oups...',
                        text: 'Erreur lors de l`annulation du l`ordre de mission',
                    });
                    console.log('jqXHR:');
                    console.log(jqXHR);
                    console.log('textStatus:');
                    console.log(textStatus);
                    console.log('errorThrown:');
                    console.log(errorThrown);
                }
                });
            }
        }
}
// affichage de lhistorique du document
    
    function historiquedoc(doc){

        //$("#gendocfromhtml").submit();
        var _token = $('input[name="_token"]').val();
        $.ajax({
                url:"{{ route('documents.historique') }}",
                method:"POST",
                //'&_token='+_token
                data:'_token='+_token+'&doc='+doc,
                success:function(data){
                    var histdoc = JSON.parse(data);
                    // vider le contenu du table historique
                    $("#tabledocshisto tbody").empty();
                    var items = [];
                    $.each(histdoc, function(i, field){
                      items.push([ i,field ]);
                    });
                    // affichage template dans iframe
                    $.each(items, function(index, val) {
                    //titre du document
                    if (val[0]==0)
                    {
                        $("#dochistoname").text(val[1]['titre']);
                    }
                    urlf="{{ URL::asset('storage'.'/app/') }}";
aurlf="<a style='color:black' href='#' onclick='modalodoc(\""+val[1]['titre']+"\",\""+urlf+"/"+val[1]['emplacement']+"\",null,null,\""+val[1]['comment']+"\",\""+val[1]['idtaggop']+"\");'><i class='fas fa-external-link-alt'></i>Aperçu</a>";
                  // aurlf="<a style='color:black' href='"+urlf+"/"+val[1]['emplacement']+"' ><i class='fa fa-download'></i> Télécharger</a>";
                    $("#tabledocshisto tbody").append("<tr><td>"+val[1]['created_at']+"</td><td>"+aurlf+"</td></tr>");
                    });
                    $("#modalhistodoc").modal('show');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                     Swal.fire({
                        type: 'error',
                        title: 'Oups...',
                        text: 'Erreur lors de recuperation de l historique du document',
                    });
                    console.log('jqXHR:');
                    console.log(jqXHR);
                    console.log('textStatus:');
                    console.log(textStatus);
                    console.log('errorThrown:');
                    console.log(errorThrown);
                }
            });
    }
// affichage de lhistorique du TAG
    
    function historiquetag(tag,titretag){
        //$("#gendocfromhtml").submit();
        var _token = $('input[name="_token"]').val();
        $.ajax({
                url:"{{ route('tags.historique') }}",
                method:"POST",
                //'&_token='+_token
                data:'_token='+_token+'&tag='+tag,
                success:function(data){
                    var histtag = JSON.parse(data);
                    // vider le contenu du table historique
                    $("#tabletagshisto tbody").empty();
                    var items = [];
                    $.each(histtag, function(i, field){
                      items.push([ i,field ]);
                    });
                    // affichage template dans iframe
                    $.each(items, function(index, val) {
                    //titre du tag
                    if (val[0]==0)
                    {
                        $("#taghistoname").text(val[1]['titre']+" | "+titretag);
                    }
if(val[1]['mrestant']!==null)
{mrestant=val[1]['mrestant'];}
else
{mrestant="";}
if(val[1]['montant']!==null)
{montant=val[1]['montant'];}
else
{montant="";}
if(val[1]['devise']!==null)
{devise=val[1]['devise'];}
else
{devise="";}

                    $("#tabletagshisto tbody").append("<tr><td>"+val[1]['titre']+"</td><td>"+val[1]['contenu']+"</td><td>"+val[1]['created_at']+"</td><td>"+montant+" "+devise+"</td><td>"+mrestant+" "+devise+"</td></tr>");
                    });
                    $("#modalhistotag").modal('show');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                     Swal.fire({
                        type: 'error',
                        title: 'Oups...',
                        text: 'Erreur lors de recuperation de l historique du document',
                    });
                    console.log('jqXHR:');
                    console.log(jqXHR);
                    console.log('textStatus:');
                    console.log(textStatus);
                    console.log('errorThrown:');
                    console.log(errorThrown);
                }
            });
    }
// affichage de lhistorique du om taxi
    
    function historiqueomtx(om,titre){
        //$("#gendocfromhtml").submit();
        var _token = $('input[name="_token"]').val();
        $.ajax({
                url:"{{ route('ordremissions.historique') }}",
                method:"POST",
                //'&_token='+_token
                data:'_token='+_token+'&om='+om+'&titre='+titre,
                success:function(data){
                    var histom = JSON.parse(data);
                    // vider le contenu du table historique
                    $("#tableomshisto tbody").empty();
                    var items = [];
                    $.each(histom, function(i, field){
                      items.push([ i,field ]);
                    });
                    // affichage template dans iframe
                    $.each(items, function(index, val) {
                    //titre du document
                    if (val[0]==0)
                    {
                        $("#omhistoname").text(val[1]['titre']);
                    }
                    urlf="{{ URL::asset('storage') }}";
                    posom=val[1]['emplacement'].indexOf("/OrdreMissions/");
                    empom=val[1]['emplacement'].slice(posom+1);
aurlf="<a style='color:black' href='#' onclick='modalodoc(\""+val[1]['titre']+"\",\""+urlf+"/"+empom+"\");'><i class='fas fa-external-link-alt'></i>Aperçu</a>";
                   // aurlf="<a style='color:black' href='"+urlf+"/"+empom+"' ><i class='fa fa-download'></i> Télécharger</a>";
if(titre!==4 ) {if(val[1]['affectea'] !="externe" ) { if(val[1]['statut']!=="Validé" ){statut="Non Validé";} else {statut= val[1]['statut'];}} else{statut="";}} else {statut="";}
          if(titre!==4 ) {if(val[1]['affectea'] !="externe" ) { if(val[1]['supervisordate']) {supervisordate=val[1]['supervisordate'];} else {supervisordate= "";}} else{supervisordate="";}} else {supervisordate="";}          
                    $("#tableomshisto tbody").append("<tr><td>"+val[1]['created_at']+"</td><td>"+statut+"</td><td>"+supervisordate+"</td><td>"+aurlf+"</td></tr>");
                    
                    });
                    $("#modalhistoom").modal('show');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire({
                        type: 'error',
                        title: 'Oups...',
                        text: 'Erreur lors de recuperation de l historique du om taxi',
                    });
                    console.log('jqXHR:');
                    console.log(jqXHR);
                    console.log('textStatus:');
                    console.log(textStatus);
                    console.log('errorThrown:');
                    console.log(errorThrown);
                }
            });
    }
// affichage de lhistorique du om taxi
    /*
    function historiqueomtx(om){
        //$("#gendocfromhtml").submit();
        var _token = $('input[name="_token"]').val();
        $.ajax({
                url:"{{ route('ordremissions.historique') }}",
                method:"POST",
                //'&_token='+_token
                data:'_token='+_token+'&om='+om,
                success:function(data) {
                    //alert(JSON.stringify(data));
                    var histom = JSON.parse(data);
                    // vider le contenu du table historique
                    $("#tableomshisto tbody").empty();
                    var items = [];
                    $.each(histom, function (i, field) {
                        items.push([i, field]);
                    });
                    // affichage template dans iframe
                    $.each(items, function (index, val) {
                        //titre du document
                        if (val[0] == 0) {
                            $("#omhistoname").text(val[1]['titre']);
                        }
                        //alert(val[0]+" | "+val[1]['emplacement']+" | "+val[1]['updated_at']);
                        urlf = "{{ URL::asset('storage') }}";
                        posom = val[1]['emplacement'].indexOf("/OrdreMissions/");
                        empom = val[1]['emplacement'].slice(posom + 1);
                        aurlf = "<a style='color:black' href='" + urlf + "/" + empom + "' ><i class='fa fa-download'></i> Télécharger</a>";
                        $("#tableomshisto tbody").append("<tr><td>" + val[1]['updated_at'] + "</td><td>" + aurlf + "</td></tr>");
                    });
                });
*/
   var items = [];
   var html_string="";
function filltemplate(data,tempdoc,mgopprec,idgopprec)
{
   // window.location =data; hde gendocform and display template filled
   if ($('#generatedoc').hasClass('in'))
   {$("#generatedoc").modal('hide');}
   //change html template content
   var templateexist = true;
   var needgop =false;
   var parsed = data;
   items.length = 0;
   $.each(parsed, function(i, field){
      items.push([ i,field ]);
    });
   // affichage template dans iframe
  $.each(items, function(index, val) {
        //recuperer la template html du document
        if(val[0] ==='templatehtml')
            {
                if ((val[1].includes(undefined)) || (!val[1]))
                {
                    templateexist = false;
                    Swal.fire({
                        type: 'error',
                        title: 'Oups...',
                        text: "la template html du document n'est pas bien défini"
                    });
                }
                else
                {
                    html_string= "{{asset('public/') }}"+"/"+val[1];
                    //alert(html_string);
                }
            }
        //verifier la templte rtf du document
        if(val[0] ==='templatertf')
            {
                if ((val[1].includes(undefined)) || (!val[1]))
                {
                    $("#gendochtml").prop("disabled",true);
                     Swal.fire({
                        type: 'error',
                        title: 'Oups...',
                        text: "la template RTF du document n'est pas bien défini"
                    });
                }
                else
                {
                    $("#templatedocument").val(tempdoc);
                }
            }
        //verifier les tags du document
        if((val[0] ==='lestags') && (val[1].indexOf("VERIFglist") !== -1 ))
            {
                console.log('les tags: '+val[1] );
                var tagstr = val[1].replace('allow_VERIFglist(','');
                tagstr = tagstr.substring(0, tagstr.indexOf(")"));
                //var arr_tags = JSON.parse("[" + tagstr + "]");
                //var arr_tags = tagstr.split(",");
                arr_tags = tagstr.split(",");
                console.log('nouv tags: '+tagstr );
                    // vider select gop options
                    $('#gopdoc').find('option').remove();
                $.each(arr_tags, function(i, field){
                    var strt = [ field ] + "" ;
                   // alert(strt);
                    var champgop = strt.split("_");
                    // ajout des options pour select gop
                    // verifier s'il ya gop precedent
                    if (idgopprec == undefined)
                    {$('#gopdoc').append(new Option(champgop[2]+" | "+"montant max: "+champgop[1]+" | "+champgop[3], champgop[0]));

}
                    else
                    {
                        if (idgopprec === parseInt(champgop[0]))
                         {
                            if (mgopprec == undefined)
                                {var mgop = champgop[1];}
                            else
                                {var mgop = parseInt(mgopprec) + parseInt(champgop[1]);}
                            $('#gopdoc').append('<option value="'+champgop[0]+'" selected="selected">'+champgop[2]+' | '+'montant max: '+mgop+' | '+champgop[3]+'</option>');
                        }
                        else {$('#gopdoc').append(new Option(champgop[2]+" | "+"montant max: "+champgop[1]+' | '+champgop[3], champgop[0]));}
                    }
                  console.log('les champs tags: '+strt );
                });
                needgop =true;
                $("#selectgopdoc").modal('show');
                arr_tags = null;
                $('#templatedoc').attr('value', '');
            }
        /*if(val[0] ==='montantgop')
            {
                console.log('montantgop: '+val[1] );
            }*/
    });
// on n'affiche pas liste de gop ici
    if ((templateexist) && (document.getElementById('templatedoc').options[document.getElementById('templatedoc').selectedIndex].text.indexOf("PEC") === -1 || document.getElementById('templatedoc').options[document.getElementById('templatedoc').selectedIndex].text.indexOf("PEC_location_VAT_a_Prest") !== -1 || document.getElementById('templatedoc').options[document.getElementById('templatedoc').selectedIndex].text.indexOf("PEC_Hotel") !== -1 ) && !(needgop) )
    {
        // remplissage de la template dans iframe
        var numparam = 0;
        $.each(items, function(index, val) {
            // les champs du document
            if ((val[0] !=='templatertf') && (val[0] !=='templatehtml')  && (val[0] !=='lestags') /* && (val[0] !=='montantgop') && (val[0].indexOf("CL_") == -1)*/ )
            {
                if (numparam == 0)
                {
                    html_string=html_string+'?';
                }
                else
                {
                    html_string=html_string+'&';
                }
                html_string=html_string+val[0]+'='+val[1];
                numparam ++;
            }
        });
        //ajout id user hs change
        var cnctuserid = $("#cnctuserid").val();
        html_string = html_string+"&iduser="+cnctuserid;
        //chargement du contenu et affichage du preview du document
        document.getElementById('templatefilled').src = html_string;
        $("#templatehtmldoc").modal('show');
    }
}
    $(document).ready(function() {
    $("#typeprest").select2();
    $("#typeprest2").select2();
   /* $("#specialite").select2();
    $("#specialite2").select2();*/
    $("#gouvcouv").select2();
    $("#gouvcouv2").select2();
    $("#pres_gouv").select2();
    $("#agent").select2();
    $("#gopdoc").select2();
    $("#templatedoc").select2();
    $("#selectable").select2 (
        //{ dropdownParent: "#insererprest" }
        );
    // btngop gop selectionné
    $('#btngop').click(function(){
        $("#selectgopdoc").modal('hide');
        var gopselected = $('#gopdoc').val();
        var goptxt = document.getElementById("gopdoc").options[document.getElementById("gopdoc").selectedIndex].text;
        var montantgop = goptxt.substr(goptxt.indexOf('montant max: ')+13);
        montantgop = montantgop.substr(0 , montantgop.lastIndexOf(" |"));
        $('#idtaggop').val(gopselected);
        //alert(goptxt);
            if ((gopselected !== 'undefined' && gopselected !== null))
            {
                // remplissage de la template dans iframe
                var numparam = 0;
                $.each(items, function(index, val) {
                    // les champs du document
                    if ((val[0] !=='templatertf') && (val[0] !=='templatehtml')  && (val[0] !=='lestags') /* && (val[0] !=='montantgop') && (val[0].indexOf("CL_") == -1)*/ )
                    {
                        if (numparam == 0)
                        {
                            html_string=html_string+'?';
                        }
                        else
                        {
                            html_string=html_string+'&';
                        }
                        html_string=html_string+val[0]+'='+val[1];
                        numparam ++;
                    }
                });
                // ajout idgop a lurl
                html_string=html_string+'&idtaggop='+gopselected;
                // ajout montant a lurl
                html_string=html_string+'&montantgop='+montantgop;
                //ajout id user hs change
                var cnctuserid = $("#cnctuserid").val();
                html_string = html_string+"&iduser="+cnctuserid;
                //chargement du contenu et affichage du preview du document
                document.getElementById('templatefilled').src = html_string;
                $("#templatehtmldoc").modal('show');
            }
        });
        // fermerdossier
        //$('#fermerdossier').click(function(){
            $(document).on('click','#fermerdossier',function(){
            var dossier = $('#dossier').val();
            var statut ="Cloture";
            var sanssuite=0;
            if ($('#sanssuite').is(':checked'))
            {sanssuite=1;}
            else{
                sanssuite=0;
            }
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('dossiers.changestatut') }}",
                    method:"POST",
                    data:{dossier:dossier,statut:statut,sanssuite:sanssuite,  _token:_token},
                    success:function(data){
                       // window.location =data;
                        alert("opération envoyée !");
                        window.location.reload();
                    }
                });
        });
        // ouvrirdossier
        //$('#ouvrirdossier').click(function(){
          $(document).on('click','#ouvrirdossier',function(){
            var dossier = $('#dossier').val();
            var statut ="actif";
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('dossiers.changestatut') }}",
                method:"POST",
                data:{dossier:dossier,statut:statut,  _token:_token},
                success:function(data){
                    // window.location =data;
                    alert("dossier Ouvert !");
                    window.location.reload();
                }
            });
        });
         $('#envoisms').click(function(){
            var description = $('#ladescription').val();
            var destinataire = $('#ledestinataire').val();
             var message = $('#lemessagel').val();
             var dossier = $('#ledossier').val();
            if ((message != '') &&(destinataire!='')&&(description!=''))
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('emails.sendsms') }}",
                    method:"POST",
                    data:{description:description,destinataire:destinataire,message:message,dossier:dossier, _token:_token},
                    success:function(data){
                         Swal.fire({
                            type: 'success',
                            title: 'Envoyé...',
                            text: "SMS Envoyé"
                        });
                       // window.location =data;
                        $("#sendsms").modal('hide');
                    }
                });
            }else{
            }
        });
    $('#emailadd').click(function(){
        var parent = $('#parent').val();
        var champ = $('#emaildoss').val();
        var nom = $('#DescrEmail').val();
        var tel = $('#telmail').val();
        var qualite = $('#qualite').val();
        if ((champ != '') )
        {
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('dossiers.addemail') }}",
                method:"POST",
                data:{parent:parent,champ:champ,nom:nom,tel:tel,qualite:qualite, _token:_token},
                success:function(data){
                     window.location =data;
                }
            });
        }else{
        }
    });
// fonction du remplissage de la template web du document
    $('#gendoc').click(function(){
        var dossier = $('#dossier').val();
        var tempdoc = $("#templatedoc").val();
        $("#gendochtml").prop("disabled",false);
        // renitialise la val de parentdoc
        $('#iddocparent').attr('value', '');
        $('#idtaggop').attr('value', '');
        if ((dossier != '') )
        {
             var _token = $('input[name="_token"]').val();
            // alert(tempdoc);
            $.ajax({
                url:"{{ route('documents.htmlfilled') }}",
                method:"POST",
                data:{dossier:dossier,template:tempdoc, _token:_token},
                success:function(data){
                    console.log(data);
                     if (typeof data !== "string")
                    {
                        //alert('no gop needed');
                        filltemplate(data,tempdoc);
                    }
                    else if (data.startsWith("notallow"))
                    {
                         msg = data.replace("notallow_", "");
                         Swal.fire({
                            type: 'error',
                            title: 'Oups...',
                            text: msg
                        });
                    }
                    else
                    {
                         Swal.fire({
                            type: 'error',
                            title: 'Oups...',
                            text: "OPERATION NON AUTORISE: Le dossier n'a pas un GOP Spécifié!"
                        });
                    }
                }
            });
        }else{
         }
    });
//document.getElementById('targetFrame').contentWindow.keyUpHandler();
function keyUpHandler(){
           // document.getElementById('dociframe').contentWindow.document.getElementById('CL_montant_toutes_lettres').firstChild.nodeValue  =   NumberToLetter(obj.value)
           alert('changed');
        }//fin de keypressHandler
// fonction du remplissage de la template web du OM
//https://www.jqueryscript.net/loading/Ajax-Progress-Bar-Plugin-with-jQuery-Bootstrap-progressTimer.html
    $('#genom').click(function(){
        var dossier = $('#dossier').val();
        var tempom = $("#templateom").val();
        var affectea = $("#affectea").val();
        var op ="";
        if (tempom==="Select")
        {
             Swal.fire({
                type: 'error',
                title: 'oups...',
                text: "Veuillez selectionner un ordre de mission"
            });
            return false;
        }
 if (tempom==="Remorquage")
        {
             $("#type_affectation option[value='Transport VAT']").hide();
             $("#type_affectation option[value='Transport MEDIC']").hide();
             $("#type_affectation option[value='Transport Najda']").hide();
 $("#type_affectation option[value='X-Press']").show();
        }
 if (tempom==="Taxi")
        {
             $("#type_affectation option[value='Transport VAT']").show();
             $("#type_affectation option[value='Transport MEDIC']").show();
             $("#type_affectation option[value='Transport Najda']").show();
 $("#type_affectation option[value='X-Press']").hide();
        }
if (tempom==="Ambulance")
        {
             $("#type_affectation option[value='Transport VAT']").show();
             $("#type_affectation option[value='Transport MEDIC']").show();
             $("#type_affectation option[value='Transport Najda']").show();
$("#type_affectation option[value='X-Press']").hide();
        }
        /*var emispar = $("#emispar").val();
        if (emispar==="Select")
        {
             Swal.fire({
                type: 'error',
                title: 'oups...',
                text: "Veuillez selectionner l'entitée qui émis l'ordre de mission"
            });
            return false;
        }
        /*var affectea = $("#affectea").val();
        if (affectea==="Select")
        {
            alert("Veuillez selectionner à qui sera affecté l'ordre de mission");
            return false;
        }*/
        //$("#gendochtml").prop("disabled",false);
        // renitialise la val de parentdoc
        //$('#iddocparent').attr('value', '');
        if ((dossier != '') )
        {
            var _token = $('input[name="_token"]').val();
            /*$.ajax({
                url:"{{-- route('documents.htmlfilled') --}}",
                method:"POST",
                data:{dossier:dossier,template:tempom, _token:_token},
                success:function(data){
                        afficheom(data,tempom);
                }
            });*/
            document.getElementById('claffect1').style.display = 'block';
            document.getElementById('claffect2').style.display = 'block';
            if (tempom === "Medic Internationnal")
            { document.getElementById('claffect1').style.display = 'none';
            document.getElementById('claffect2').style.display = 'none';}
            $("#affectationprest").val("Select").change();
            afficheom(tempom,dossier,affectea);
        }else{
        }
$("#affectationprest").val("Select").change();
$("#templateom").val("Select").change();
    });
    function afficheom(tempom,dossier,affectea)
    {
        $("#affectationprest").val("Select").change();
        $("#generateom").modal('hide');
        var cnctuserid = $("#cnctuserid").val();
        if (affectea === undefined && affectea === null)
        {
            affectea = "";
        }
        if (tempom === "Taxi")
        {var url = '<?php echo url('/'); ?>/public/preview_templates/odm_taxi.php?dossier='+dossier+'&affectea='+affectea+'&iduser='+cnctuserid+'&DB_HOST='+'<?php echo env("DB_HOST"); ?>'+'&DB_DATABASE='+'<?php echo env("DB_DATABASE"); ?>'+'&DB_USERNAME='+'<?php echo env("DB_USERNAME"); ?>'+'&DB_PASSWORD='+'<?php echo env("DB_PASSWORD"); ?>';}
        if (tempom === "Ambulance")
        {var url = '<?php echo url('/'); ?>/public/preview_templates/odm_ambulance.php?dossier='+dossier+'&affectea='+affectea+'&iduser='+cnctuserid+'&DB_HOST='+'<?php echo env("DB_HOST"); ?>'+'&DB_DATABASE='+'<?php echo env("DB_DATABASE"); ?>'+'&DB_USERNAME='+'<?php echo env("DB_USERNAME"); ?>'+'&DB_PASSWORD='+'<?php echo env("DB_PASSWORD"); ?>';}
        if (tempom === "Remorquage")
        {var url = '<?php echo url('/'); ?>/public/preview_templates/odm_remorquage.php?dossier='+dossier+'&affectea='+affectea+'&iduser='+cnctuserid+'&DB_HOST='+'<?php echo env("DB_HOST"); ?>'+'&DB_DATABASE='+'<?php echo env("DB_DATABASE"); ?>'+'&DB_USERNAME='+'<?php echo env("DB_USERNAME"); ?>'+'&DB_PASSWORD='+'<?php echo env("DB_PASSWORD"); ?>';}
        if (tempom === "Medic Internationnal")
            {var url = '<?php echo url('/'); ?>/public/preview_templates/odm_medic_international.php?dossier='+dossier+'&affectea='+affectea+'&iduser='+cnctuserid+'&DB_HOST='+'<?php echo env("DB_HOST"); ?>'+'&DB_DATABASE='+'<?php echo env("DB_DATABASE"); ?>'+'&DB_USERNAME='+'<?php echo env("DB_USERNAME"); ?>'+'&DB_PASSWORD='+'<?php echo env("DB_PASSWORD"); ?>';}
         document.getElementById("omfilled").src = url;
        $("#templatehtmlom").modal('show');
        $('#templateordrem').val("");
         if (affectea !== "")
         {
            $('#affectationprest').val(affectea);
         }
    }
    $('#gendochtml').click(function(){
         //$("#gendocfromhtml").submit();
        var _token = $('input[name="_token"]').val();
        var dossier = $('#dossdoc').val();
        var tempdoc = $("#templatedocument").val();
        var comdoc = $("#doccomment").val();
var modif = $("#modif").val()
        var idparent = '';
        var idgop = '';
        var idMissionDoc=$("#idMissionDoc").val();
        // verifier si cest le cas de annule et remplace pour sauvegarder lid du parent
        if ($('#iddocparent').val())
        {
            idparent = $('#iddocparent').val();
            console.log('parent: '+idparent);
        }
        if ($('#idtaggop').val())
        {
            idgop = $('#idtaggop').val();
            console.log('gopid: '+idgop);
        }
         if($('#idMissionDoc').val()==null)
        {
           alert(" attention l id mission null. Veuillez Contacter l admin");
        }
        $.ajax({
                url:"{{ route('documents.adddocument') }}",
                method:"post",
                //'&_token='+_token
                data:$("#templatefilled").contents().find('form').serialize()+'&_token='+_token+'&dossdoc='+dossier+'&templatedocument='+tempdoc+'&parent='+idparent+'&modif='+modif+'&comdoc='+comdoc+'&idtaggop='+idgop+'&idMissionDoc='+idMissionDoc,
                success:function(data){
//alert(data);
if(data==="false")
{


 Swal.fire({
                type: 'error',
                title: 'oups...',
                text: "Veuillez sélectionner le prestataire"
            });
$("#gendochtml").prop("disabled",false);

}
           else{        // alert(data);
                     console.log(data);
                   var doc = JSON.parse(data);
emplacement=doc['emplacement'];
titre=doc['titre'];
dossier=doc['dossier'];
name=doc['name'];
urlf="{{ URL::asset('storage'.'/app/') }}";
$("#templatehtmldoc").modal('hide');
modalodoc(doc['titre'],urlf+"/"+doc['emplacement'],'doc',null,doc['comment'],doc['idtaggop'],doc['name'],doc['emplacement'],'0');

$('#fermedoc').click(function(){
 
                            window.location.reload();
});}
                },
                error: function(jqXHR, textStatus, errorThrown) {
                      Swal.fire({
                        type: 'error',
                        title: 'oups...',
                        text: "Erreur lors de la generation du document:"+jqXHR.responseText
                    });
                    console.log('jqXHR:');
                    console.log(jqXHR);
                    console.log('textStatus:');
                    console.log(textStatus);
                    console.log('errorThrown:');
                    console.log(errorThrown);
                }
            });
    });
    $('#genomhtml').click(function(){
         //$("#gendocfromhtml").submit();
        var _token = $('input[name="_token"]').val();
        var dossier = $('#dossom').val();
        var tempdoc = $("#templateordrem").val();
        var affectea = $("#affectea").val();
var affecteasecondaire = $("#affecteasecondaire").val();
var dossierexistant = $("#dossierexistant").val();
//alert(affectea);
//alert(affecteasecondaire);
//alert(dossierexistant);
        var srctemp = document.getElementById('omfilled').src;
if (srctemp.indexOf("/odm_medic") === -1 )
      { var heuredateRDV =document.omfilled.CL_heuredateRDV.value;
       if (heuredateRDV==="")
        {document.getElementById('genomhtml').disabled = false;
             Swal.fire({
                type: 'error',
                title: 'oups...',
                text: "Veuillez saisir la date de RDV"
            });
            return false;
              
        }
var heureRDV =document.omfilled.CL_heure_RDV.value;
       if (heureRDV==="")
        {document.getElementById('genomhtml').disabled = false;
             Swal.fire({
                type: 'error',
                title: 'oups...',
                text: "Veuillez saisir l'heure de RDV"
            });
            return false;
              
        }
var lieuprestpc =document.omfilled.lieuprest.value;
       if (lieuprestpc==="")
        {document.getElementById('genomhtml').disabled = false;
             Swal.fire({
                type: 'error',
                title: 'oups...',
                text: "Veuillez saisir le Lieu prise en charge"
            });
            return false;
              
        }
var lieudechargedec =document.omfilled.lieudecharge.value;
       if (lieudechargedec==="")
        {document.getElementById('genomhtml').disabled = false;
             Swal.fire({
                type: 'error',
                title: 'oups...',
                text: "Veuillez saisir le Lieu décharge"
            });
            return false;
              
        }
}
        if (srctemp.indexOf("/odm_medic") === -1 )
            {
if((affectea==="mmentite" && tempdoc==="remplace")|| (affectea==="interne" && tempdoc==="complete") )
     { var dateheuredep =document.omfilled.dateheuredep.value;
       if (dateheuredep==="")
        
        {document.getElementById('genomhtml').disabled = false;
             Swal.fire({
                type: 'error',
                title: 'oups...',
                text: "Veuillez saisir la date de départ base"
            });
            return false;
              
        }
      var dateheuredispprev =document.omfilled.dateheuredispprev.value;
       if (dateheuredispprev==="")
        
        {document.getElementById('genomhtml').disabled = false;
             Swal.fire({
                type: 'error',
                title: 'oups...',
                text: "Veuillez saisir la date de dispo prévisible"
            });
            return false;
              
        }
var dhretbaseprev =document.omfilled.dhretbaseprev.value;
       if (dhretbaseprev==="")
        
        {document.getElementById('genomhtml').disabled = false;
             Swal.fire({
                type: 'error',
                title: 'oups...',
                text: "Veuillez saisir la date de retour base prévisible"
            });
            return false;
              
        }}}
  
        var srctemp = document.getElementById('omfilled').src;
        if (affectea == "interne")
        {
          var type_affectation = $("#type_affectation").val();
 if (  ((tempdoc !== 'remplace') &&  (tempdoc !== 'complete') ) && ((affecteasecondaire==="") ||  (dossierexistant==="Select") ||  (dossierexistant==="")) && !(affecteasecondaire==="nouveau")  )
        {
document.getElementById('genomhtml').disabled = false;
             Swal.fire({
                type: 'error',
                title: 'oups...',
                text: "Veuillez selectionner une entité soeur ou un dossier existant"
            });
            return false;
     
        }
 if (  ((tempdoc !== 'remplace') &&  (tempdoc !== 'complete') ) && (affecteasecondaire==="nouveau") && (type_affectation==="Select") )
        {
document.getElementById('genomhtml').disabled = false;
             Swal.fire({
                type: 'error',
                title: 'oups...',
                text: "Veuillez selectionner une entité soeur "
            });
            return false;
     
        }
          var nomprestextern = "";
          var idprestextern= "";
        }
        else {
            var type_affectation = "";
            var nomprestextern = $("#prestselected").val();
            var idprestextern = $("#idprestselected").val();
if (srctemp.indexOf("/odm_medic") === -1 )
{
if (  ((tempdoc !== 'remplace') &&  (tempdoc !== 'complete') ) && (nomprestextern==="") && (affectea !== "mmentite"))
        {
document.getElementById('genomhtml').disabled = false;
$("#affectationprest").val("Select").change();
             Swal.fire({
                type: 'error',
                title: 'oups...',
                text: "Veuillez selectionner le prestataire externe "
            });
            return false;
     
        }
        }}
        var idparent = '';
         var idMissionOM=$("#idMissionOM").val();
        /*if ($('#templateordrem').val())
        {
        alert ($('#templateordrem').val());}
        if ($('#idomparent').val())
        {
        alert ($('#idomparent').val());}*/
        // verifier si cest le cas de annule et remplace pour sauvegarder lid du parent
        if ($('#idomparent').val())
        {
            idparent = $('#idomparent').val();
            console.log('parent: '+idparent);
        }
         if($('#idMissionOM').val()==null)
        {
           alert(" attention l id mission null. Veuillez Contacter l admin");
        }
        //alert(type_affectation+" | "+nomprestextern);
        if (srctemp.indexOf("/odm_taxi") !== -1 )
        {var routeom = "{{ route('ordremissions.export_pdf_odmtaxi') }}"; }
        if (srctemp.indexOf("/odm_ambulance") !== -1 )
        {var routeom = "{{ route('ordremissions.export_pdf_odmambulance') }}"; }
        if (srctemp.indexOf("/odm_remorquage") !== -1 )
        {var routeom = "{{ route('ordremissions.export_pdf_odmremorquage') }}"; }
        if (srctemp.indexOf("/odm_medic") !== -1 )
        {var routeom = "{{ route('ordremissions.export_pdf_odmmedicinternationnal') }}"; }
        $.ajax({
                url:routeom,
                method:"POST",
                //'&_token='+_token
                data:$("#omfilled").contents().find('form').serialize()+'&_token='+_token+'&dossdoc='+dossier+'&affectea='+affectea+'&affecteasecondaire='+affecteasecondaire+'&dossierexistant='+dossierexistant+'&type_affectation='+type_affectation+'&prestextern='+nomprestextern+'&idprestextern='+idprestextern+'&templatedocument='+tempdoc+'&parent='+idparent+'&idMissionOM='+idMissionOM,
              dataType: 'json',  
 success:function(data){
                     console.log(data);
                    $('#idomparent').val("");
                    $('#templateordrem').val("");



                    if (!$.trim(data))
                    {location.reload();}
                    else
                        {//alert(data['titre']);
 if(data['resultatNote']!='' && data['resultatNote']!=undefined ){alert(data['resultatNote']);}
urlf="{{ URL::asset('storage'.'/OrdreMissions/') }}";
//alert(urlf+"/"+dossier+"/"+data['titre']);
$("#templatehtmlom").modal('hide');
                        modalodoc(data['titre'],urlf+"/"+dossier+"/"+data['titre']+'.pdf','om',data['parent'],null,null,null,null,'0');


$('#fermedoc').click(function(){
 
                            window.location.reload();
});
}
                },
                error: function(jqXHR, textStatus, errorThrown) {
                      Swal.fire({
                        type: 'error',
                        title: 'oups...',
                        text: "Erreur lors de la génération "
                    });
                    console.log('jqXHR:');
                    console.log(jqXHR);
                    console.log('textStatus:');
                    console.log(textStatus);
                    console.log('errorThrown:');
                    console.log(errorThrown);
                }
            });
    });
function toggle(className, displayState){
            var elements = document.getElementsByClassName(className);
            for (var i = 0; i < elements.length; i++){
                elements[i].style.display = displayState;
            }
        }
 $("#type_affectation_exis").change(function() { 
if ($("#type_affectation_exis").val()==="nouveau")
{

document.getElementById('typeaffect').style.display = 'block';
$("#affecteasecondaire").val("nouveau");
$("#dossierexistant").val("");
}
else
{

document.getElementById('typeaffect').style.display = 'none';
$("#affecteasecondaire").val("ancien");
$("#dossierexistant").val($("#type_affectation_exis").children("option:selected").val());}

});
    $("#affectationprest").change(function() {
            if ($("#affectationprest").val()==="interne")
            {var dossier = $('#dossier').val();
             var srctemp = document.getElementById('omfilled').src;
 if (srctemp.indexOf("/odm_remorquage") !== -1 )
        {var xp =1;


  }
else
        {var xp =0; 
   }
//alert(xp);
             var _token = $('input[name="_token"]').val();
                  $.ajax({
                    url:"{{ route('ordremissions.verifdossiers') }}",
                    method:"POST",
                    dataType: 'json',
                    data:{dossier:dossier,xp:xp,_token:_token},
                    success:function(data){
                   if (data!="")
                       { //alert(data);
nov="nouveau";
Nov="Nouveau";
var select = document.getElementById("type_affectation_exis");
var length = select.options.length;
for (i = length-1; i >= 0; i--) {
  select.options[i] = null;
}
if(document.getElementById('type_affectation_exis').options.length===0)
{
$('#type_affectation_exis').append('<option value="'+'Select'+'" ">'+'Selectionner'+'</option>');
for (i = 0; i < data.length; i++)
{
 $('#type_affectation_exis').append('<option value="'+data[i]['id']+'" ">'+data[i]['reference_medic']+'</option>');}

$('#type_affectation_exis').append('<option value="'+nov+'" ">'+Nov+'</option>');}
            
document.getElementById('typeaffect1').style.display = 'block';
                $("#affectea").val("interne");
}
else
{document.getElementById('typeaffect').style.display = 'block';
document.getElementById('typeaffect1').style.display = 'none'
                $("#affectea").val("interne");
$("#affecteasecondaire").val("nouveau");
$("#dossierexistant").val("");}
                    }
                });   
                
            }else
            {
                document.getElementById('typeaffect1').style.display = 'none'
                document.getElementById('typeaffect').style.display = 'none';
                if ($("#affectationprest").val()==="externe")
                {
                    document.getElementById('externaffect').style.display = 'none';
                    // afficher les specialite par type de prestation selectionné
                    var srctemp = document.getElementById('omfilled').src;
                    var typeprest = 2;
                    toggle('tprestm', 'none');
                    if (srctemp.indexOf("/odm_taxi") !== -1 )
                    {typeprest =2; }
                    if (srctemp.indexOf("/odm_ambulance") !== -1 )
                    {typeprest =4; }
                    if (srctemp.indexOf("/odm_remorquage") !== -1 )
                    {typeprest =1; }
                    toggle('tprestm-'+typeprest, 'block');
                    
                    $("#optprestataire").modal('show');
                    $("#affectea").val("externe");
$("#affecteasecondaire").val("");
$("#dossierexistant").val("");
                }
                // condition affecte a mm entite <hs change>
                else if ($("#affectationprest").val()==="mmentite")
                {
                   document.getElementById('externaffect').style.display = 'none';
                   $("#affectea").val("mmentite");
$("#affecteasecondaire").val("");
$("#dossierexistant").val("");
                }
                else
                {
                    $("#affectea").val("");
$("#affecteasecondaire").val("");
$("#dossierexistant").val("");
                }
            }
        });
        $('#btnaddemail').click(function(){
            var parent = $('#dossier').val();
            var nom = $('#nome').val();
            var prenom = $('#prenome').val();
            var fonction = $('#fonctione').val();
             var email = $('#emaildoss').val();
             var observ = $('#remarquee').val();
            var nature = $('#natureem').val();
            if ((email != '') )
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('dossiers.addressadd') }}",
                    method:"POST",
                    data:{parent:parent,nom:nom,prenom:prenom,fonction:fonction,email:email,observ: observ, nature:nature, _token:_token},
                    success:function(data){
                        window.location =data;
                    }
                });
            }else{
            }
        });
        $('#btnaddtel').click(function(){
            var parent = $('#dossier').val();
            var nom = $('#nomt').val();
            var prenom = $('#prenomt').val();
            var fonction = $('#fonctiont').val();
            var tel = $('#teldoss').val();
             var observ = $('#remarquet').val();
            var nature = $('#naturetel').val();
            if ((tel != '') )
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('dossiers.addressadd2') }}",
                    method:"POST",
                    data:{parent:parent,nom:nom,prenom:prenom,fonction:fonction,tel:tel,observ: observ, nature:nature, _token:_token},
                    success:function(data){
                        window.location =data;
                    }
                });
            }else{
            }
        });
});
</script>



<script src="https://cdn.jsdelivr.net/npm/places.js@1.16.4"></script>

<script>
    function deleteattach() {
        var attach= document.getElementById('selectedAttach').value;
         var _token = $('input[name="_token"]').val();
       if ( confirm("Etes vous sûrs ?")){
           $.ajax({
               url: "{{ route('deleteattach') }}",
               method: "POST",
               data: {  attach:attach , _token: _token},
               success: function ( ) {
                   location.reload()
               }
           });
       }
    }
    function updateDesc() {
        var attach= document.getElementById('selectedAttach').value;
        var descrip = document.getElementById('descAttach').value;
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('updateattach') }}",
            method: "POST",
            data: {  attach:attach ,descrip:descrip, _token: _token},
            success: function ( ) {
                $('#descAttach').animate({
                    opacity: '0.3',
                });
                $('#descAttach').animate({
                    opacity: '1',
                });
            }
        });
    }
    $(function () {
      //  $("#iddossier").select2();
        $('#phoneicon').click(function() {
            $('#crendu').modal({show: true});
        });
        // Ajout Compte Rendu
        $('#ajoutcompter').click(function() {
            var _token = $('input[name="_token"]').val();
            var dossier = document.getElementById('iddossier').value;
            var refdossier = document.getElementById('refdossier').value;
            var contenu = document.getElementById('contenucr').value;
            var emetteur = document.getElementById('emetteur').value;
            var media = document.getElementById('mediacr').value;
            var description = document.getElementById('descriptioncr').value;
            if(contenu != ''){
                $.ajax({
                    url: "{{ route('entrees.ajoutcompter') }}",
                    method: "POST",
                    data: { emetteur:emetteur, dossier:dossier,refdossier:refdossier,contenu:contenu, media:media,description:description, _token: _token},
                    success: function (data) {
                        alert('Ajouté avec succès');
                        $('#crendu').modal('hide');
                        //     $('#crendu').modal({show: false});
                    }
                });
            }else{
                alert('le contenu est obligatoire !');
            }
        }); //end click
        $('#valide').click(function(){
            var prestation=  document.getElementById('idprestation').value;
            var firstsaved= parseInt(  document.getElementById('firstsaved').value);
            var _token = $('input[name="_token"]').val();
// creation prestation  si ce n'est pas la premiere
			
			
				  var prestataire = $('#selectedprest').val();
			  var nomprestataire = $('#selectedprest option:selected').text();
                 var dossier_id = $('#dossier').val();
//alert(nomprestataire);
//alert(prestataire);
                var typeprest = $('#typeprest').val();
                var gouvernorat = $('#gouvcouv').val();
                var specialite = $('#specialite').val();
                var date = $('#pres_date').val();
				if(firstsaved==0){
                //   gouvcouv
                if ((parseInt(prestataire) >0)&&(parseInt(dossier_id) >0)&&(parseInt(typeprest) >0))
                {
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('prestations.saving') }}",
                        method:"POST",
                        data:{date:date,prestataire:prestataire,dossier_id:dossier_id,specialite:specialite,gouvernorat:gouvernorat,typeprest:typeprest, _token:_token},
                        success:function(data){
                            var prestation=parseInt(data);
                            // window.location =data;
						//	document.getElementById('idprestation').value=prestation;
							
							
							
			   $.ajax({
                url:"{{ route('prestations.valide') }}",
                method:"POST",
                data:{prestation:prestation, _token:_token},
                success:function(data){
                 //   var prestation=parseInt(data);
                    /// window.location =data;
                    window.location = '<?php echo $urlapp; ?>/prestations/view/'+prestation;
                },
                error: function(jqXHR, textStatus, errorThrown) {
                }
            });
 
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                        }
                    });
                }else{
                }
				
				
				
				}else{
					
									
			   $.ajax({
                url:"{{ route('prestations.valide') }}",
                method:"POST",
                data:{prestation:prestation, _token:_token},
                success:function(data){
                 //   var prestation=parseInt(data);
                    /// window.location =data;
                    window.location = '<?php echo $urlapp; ?>/prestations/view/'+prestation;
                },
                error: function(jqXHR, textStatus, errorThrown) {
                }
            });
			
				}
			/*
			// validation
			prestation= document.getElementById('idprestation').value;
			alert('prestation '+prestation);
            $.ajax({
                url:"{{ route('prestations.valide') }}",
                method:"POST",
                data:{prestation:prestation, _token:_token},
                success:function(data){
                 //   var prestation=parseInt(data);
                    /// window.location =data;
                    window.location = '<?php echo $urlapp; ?>/prestations/view/'+prestation;
                },
                error: function(jqXHR, textStatus, errorThrown) {
                }
            });
			
			*/
        });
 $('#valide-m').click(function(){
          var prestation=  document.getElementById('idprestation-m').value;
var firstsavedm= parseInt(  document.getElementById('firstsaved-m').value);
            var _token = $('input[name="_token"]').val();
// creation prestation  si ce n'est pas la premiere
			
			
				  var prestataire = $('#selectedprest-m').val();
			  var nomprestataire = $('#selectedprest-m option:selected').text();
                 var dossier_id = $('#dossier').val();
//alert(nomprestataire);
//alert(prestataire);
            var typeprestom = document.getElementById('templateom').value;
            var gouvernorat = $('#gouvcouvm').val();
            var specialite = $('#specialitem').val();
 if ((typeprestom==="Taxi")&&(typeprestom !=="")) {typeprest=2; type=2; }
            // AMBULANCE
            if ((typeprestom==="Ambulance")&&(typeprestom !=="")) {typeprest=4; type=4;}
            // REMORQUAGE
            if ((typeprestom==="Remorquage")&&(typeprestom !=="")) {typeprest=1; type=1;}
            // cas remplace
            var srcomtemp = document.getElementById("omfilled").src;
            var posomtaxitemp = srcomtemp.indexOf("odm_taxi");
            var posomambulancetemp = srcomtemp.indexOf("odm_ambulance");
            var posomremorquagetemp = srcomtemp.indexOf("odm_remorquage");
            if(((typeprestom === "") || (typeprestom === "Select"))&&(posomtaxitemp != -1)) {typeprest=2; type=2;}
            if(((typeprestom === "") || (typeprestom === "Select"))&&(posomambulancetemp != -1)) {typeprest=4; type=4; }
            if(((typeprestom === "") || (typeprestom === "Select"))&&(posomremorquagetemp != -1)) {typeprest=1; type=1;}
                var date = $('#pres_datem').val();
				if(firstsavedm==0){
                //   gouvcouv
                if ((parseInt(prestataire) >0)&&(parseInt(dossier_id) >0)&&(parseInt(typeprest) >0))
                {
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('prestations.saving') }}",
                        method:"POST",
                        data:{date:date,prestataire:prestataire,dossier_id:dossier_id,specialite:specialite,gouvernorat:gouvernorat,typeprest:typeprest, _token:_token},
                        success:function(data){
                            var prestation=parseInt(data);
                            // window.location =data;
						//	document.getElementById('idprestation').value=prestation;
							
							
							
			   $.ajax({
                url:"{{ route('prestations.valide') }}",
                method:"POST",
                data:{prestation:prestation, _token:_token},
                success:function(data){
                 //   var prestation=parseInt(data);
                    /// window.location =data;
                    document.getElementById('typeaffect').style.display='none';
                 //document.getElementById('prestselected').value = document.getElementById('selectedprest-m').value;
                 var prestvalid=document.getElementById('selectedprest-m').value;
                 console.log(prestvalid);
                 var prestvaltext = $("#selectedprest-m option[value='"+prestvalid+"']").text();
          console.log("text: "+prestvaltext);
                 //document.getElementById('prestselected').val = prestvaltext;
                 $("#prestselected").val(prestvaltext);
                 $("#idprestselected").val(prestation);
                 document.getElementById('externaffect').style.display='block';
                 
                 $("#affectationprest").prop('disabled', true);
                 
                 $('#optprestataire').hide();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                }
            });
 
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                        }
                    });
                }else{
                }
				
				
				
				}else{
					
									
			   $.ajax({
                url:"{{ route('prestations.valide') }}",
                method:"POST",
                data:{prestation:prestation, _token:_token},
                success:function(data){
                 //   var prestation=parseInt(data);
                    /// window.location =data;
                 document.getElementById('typeaffect').style.display='none';
                 //document.getElementById('prestselected').value = document.getElementById('selectedprest-m').value;
                 var prestvalid=document.getElementById('selectedprest-m').value;
                 console.log(prestvalid);
                 var prestvaltext = $("#selectedprest-m option[value='"+prestvalid+"']").text();
          console.log("text: "+prestvaltext);
                 //document.getElementById('prestselected').val = prestvaltext;
                 $("#prestselected").val(prestvaltext);
                 $("#idprestselected").val(prestation);
                 document.getElementById('externaffect').style.display='block';
                 
                 $("#affectationprest").prop('disabled', true);
                 
                 $('#optprestataire').hide();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                }
            });
			
				}
			/*
			// validation
			prestation= document.getElementById('idprestation').value;
			alert('prestation '+prestation);
            $.ajax({
                url:"{{ route('prestations.valide') }}",
                method:"POST",
                data:{prestation:prestation, _token:_token},
                success:function(data){
                 //   var prestation=parseInt(data);
                    /// window.location =data;
                    window.location = '<?php echo $urlapp; ?>/prestations/view/'+prestation;
                },
                error: function(jqXHR, textStatus, errorThrown) {
                }
            });
			
			*/
        });
 
       
        $('#add2').click(function(){
            selected=   document.getElementById('selected').value;
            document.getElementById('selectedprest').value = document.getElementById('prestataire_id_'+selected).value ;
            var prestataire = $('#selectedprest').val();
            var dossier_id = $('#dossier').val();
            var typeprest = $('#typeprest').val();
            var gouvernorat = $('#gouvcouv').val();
            var specialite = $('#specialite').val();
            var date = $('#pres_date').val();
            //   gouvcouv
            if ((parseInt(prestataire) >0)&&(parseInt(dossier_id) >0)&&(parseInt(typeprest) >0))
               {
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('prestations.saving') }}",
                method:"POST",
                data:{date:date,prestataire:prestataire,dossier_id:dossier_id,specialite:specialite,gouvernorat:gouvernorat,typeprest:typeprest, _token:_token},
                success:function(data){
           var prestation=parseInt(data);
               /// window.location =data;
                    document.getElementById('prestation').style.display='block';
                   document.getElementById('valide').style.display='block';
                   document.getElementById('validation').style.display='block';
                    document.getElementById('idprestation').value =prestation;
                    document.getElementById('firstsaved').value =1;
                },
                error: function(jqXHR, textStatus, errorThrown) {
                }
            });
              }else{
             }
        });
        // ajout prestation
        $('#selectionnerprest').click(function(){
            var prestataire = $('#selectedprest2').val();
            var dossier_id = $('#dossier').val();
            var typeprest = $('#ajout_typeprest').val();
            var gouvernorat = $('#pres_gouv').val();
            var specialite = $('#ajout_specialite').val();
            var date = $('#pres_date2').val();
            var autorise = $('#autorise').val();
            var details = $('#details').val();
            var ville = $('#villepr3').val();
var manuel ="manuel";

            //   gouvcouv
            if ((parseInt(prestataire) >0)&&(parseInt(dossier_id) >0) )
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('prestations.saving') }}",
                    method:"POST",
                    data:{manuel:manuel, autorise:autorise,details:details,date:date,prestataire:prestataire,dossier_id:dossier_id,specialite:specialite,gouvernorat:gouvernorat ,typeprest:typeprest,ville:ville, _token:_token},
                    success:function(data){
if(data==='faux')
{

 Swal.fire({
                            type: 'Error',
                            title: 'Champs invalide...',
                            text:"il faut sélectionner l'autorisation"
                        });
document.getElementById('selectionnerprest').disabled=false;
}
                        //var prestation=parseInt(data);
                        /// window.location =data;
                        /*Swal.fire({
                            type: 'success',
                            title: 'Enregistrée...',
                            text: "Prestation Enregistrée"
                        });*/
                     else{   alert('prestation ajoutée');
                        //window.location =location.href ;
                         location.reload() ;
                        // window.location =data;
                        $("#openmodalprest").modal('hide');}
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                    }
                });
            }else{
            alert('il manque des informations');
            }
        });
        // filtrer specialités selon type de prestation
        $('#ajout_typeprest').change(function() {
            var typeprestation = $('#ajout_typeprest').val();
            $("#ajout_specialite").val('');
            var liste ;
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('prestataires.listesprest') }}",
                method:"POST",
                data:{typeprestation:typeprestation,  _token:_token},
                success:function(data){
                    liste=data;
                    console.log('data : '+data);
                    //   alert('Added successfully');
                    $('#ajout_specialite option').each(function() {
                        $(this).css("display", "none");
                    });
                    $('#ajout_specialite option').each(function() {
                        // console.log(  $(this).val());
                        for (i=0;i< liste.length ;i++){
                            if(liste[i]== $(this).val() )
                            {//alert('1');
                                $(this).css("display", "block");
                                break;
                            }
                        }
                    });
                }
            });
        });
$('#add2-m').click(function(){
           selected=   document.getElementById('selected-m').value;
            document.getElementById('selectedprest-m').value = document.getElementById('prestataire_id_'+selected+'-m').value ;
             var prestataire = $('#selectedprest-m').val();
            var dossier_id = $('#dossier-m').val();
            var typeprestom = document.getElementById('templateom').value;
            var gouvernorat = $('#gouvcouvm').val();
            var specialite = $('#specialitem').val();
 if ((typeprestom==="Taxi")&&(typeprestom !=="")) {typeprest=2; type=2; }
            // AMBULANCE
            if ((typeprestom==="Ambulance")&&(typeprestom !=="")) {typeprest=4; type=4;}
            // REMORQUAGE
            if ((typeprestom==="Remorquage")&&(typeprestom !=="")) {typeprest=1; type=1;}
            // cas remplace
            var srcomtemp = document.getElementById("omfilled").src;
            var posomtaxitemp = srcomtemp.indexOf("odm_taxi");
            var posomambulancetemp = srcomtemp.indexOf("odm_ambulance");
            var posomremorquagetemp = srcomtemp.indexOf("odm_remorquage");
            if(((typeprestom === "") || (typeprestom === "Select"))&&(posomtaxitemp != -1)) {typeprest=2; type=2;}
            if(((typeprestom === "") || (typeprestom === "Select"))&&(posomambulancetemp != -1)) {typeprest=4; type=4; }
            if(((typeprestom === "") || (typeprestom === "Select"))&&(posomremorquagetemp != -1)) {typeprest=1; type=1;}
            var date = $('#pres_datem').val();
           
            //   gouvcouv
            if ((parseInt(prestataire) >0)&&(parseInt(dossier_id) >0)&&(parseInt(typeprest) >0))
               {
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('prestations.saving') }}",
                method:"POST",
                data:{date:date,prestataire:prestataire,dossier_id:dossier_id,specialite:specialite,gouvernorat:gouvernorat,typeprest:typeprest, _token:_token},
                success:function(data){
           var prestation=parseInt(data);
               /// window.location =data;
                    document.getElementById('prestation-m').style.display='block';
                   document.getElementById('valide-m').style.display='block';
                   document.getElementById('validation-m').style.display='block';
                    document.getElementById('idprestation-m').value =prestation;
document.getElementById('firstsaved-m').value =1;
                },
                error: function(jqXHR, textStatus, errorThrown) {
                }
            });
              }else{
             }
        });
      
        $('.radio1').click(function() {
            var   div=document.getElementById('montantfr');
            if(div.style.display==='none')
            {div.style.display='block';  }
            else
            {div.style.display='none';     }
            var   div2=document.getElementById('plafondfr');
            if(div2.style.display==='none')
            {div2.style.display='block';     }
            else
            {div2.style.display='none';     }
        });
        $('#btn01').click(function() {
            var   div=document.getElementById('ben2');
            if(div.style.display==='none')
            {div.style.display='block';  }
            else
            {div.style.display='none';     }
        });
        $('#btn02').click(function() {
            var   div=document.getElementById('ben3');
            if(div.style.display==='none')
            {div.style.display='block';  }
            else
            {div.style.display='none';     }
        });
        $('#btn03').click(function() {
            var   div=document.getElementById('adresse2');
            if(div.style.display==='none')
            {div.style.display='block';  }
            else
            {div.style.display='none';     }
        });
        $('#btn04').click(function() {
            var   div=document.getElementById('adresse3');
            if(div.style.display==='none')
            {div.style.display='block';  }
            else
            {div.style.display='none';     }
        });
        function toggle(className, displayState){
            var elements = document.getElementsByClassName(className);
            for (var i = 0; i < elements.length; i++){
                elements[i].style.display = displayState;
            }
        }
        $("#typeprest").change(function() {
            document.getElementById('termine').style.display = 'none';
            document.getElementById('showNext').style.display='none';
            document.getElementById('prestation').style.display='none';
            document.getElementById('add2').style.display='none';
            document.getElementById('valide').style.display='none';
            document.getElementById('validation').style.display='none';
            document.getElementById('add2prest').style.display='none';
            document.getElementById('selectedprest').value=0;
                // afficher les specialite par type de prestation selectionné
            toggle('tprest', 'none');
           var typeprest=  document.getElementById('typeprest').value;
           // document.getElementById('tprest-'+typeprest).style.display='block';
            toggle('tprest-'+typeprest, 'block');
        });
        $("#typeprest2").change(function() {
            // afficher les specialite par type de prestation selectionné
            toggle('tprest2', 'none');
            var typeprest=  document.getElementById('typeprest2').value;
             //document.getElementById('tprest2-'+typeprest).style.display='block';
            toggle('tprest2-'+typeprest, 'block');
        });
        $("#rechercher").click(function(){
            // document.getElementById('termine').style.display = 'none';
            document.getElementById('showNext').style.display='none';
            document.getElementById('showNext').firstChild.data ='Commencer';
$('#showNext').prop('disabled', false);
            document.getElementById('add2').style.display='none';
            document.getElementById('add2prest').style.display='none';
            document.getElementById('selectedprest').value=0;
            toggle('tprest', 'none');
            var typeprest=  document.getElementById('typeprest').value;
        //   document.getElementById('tprest-'+typeprest).style.display='block';
            toggle('tprest', 'block');
            //  prest = $(this).val();
            document.getElementById('selectedprest').value=0;
            var  type =document.getElementById('typeprest').value;
            var  gouv =document.getElementById('gouvcouv').value;
            var  specialite =document.getElementById('specialite').value;
            var  ville =document.getElementById('villepr').value;
            var  postal =document.getElementById('villecode').value;
            if((type !="")&&(gouv !=""))
            {
                var _token = $('input[name="_token"]').val();
                document.getElementById('termine').style.display = 'none';
                document.getElementById('add2').style.display = 'none';
                document.getElementById('add2prest').style.display='none';
               console.log('Gouv: '+gouv+' Type P: '+type+' Specialite: '+specialite+' Ville: '+ville+' Postal: '+postal) ;
                $.ajax({
                    url:"{{ route('dossiers.listepres') }}",
                    method:"post",
                    data:{gouv:gouv,type:type,specialite:specialite,ville:ville,postal:postal, _token:_token},
                    success:function(data){
                        $('#data').html(data);
                        //window.location =data;
                        console.log(data);
                        ////       data.map((item, i) => console.log('Index:', i, 'Id:', item.id));
                        var  total =parseInt(document.getElementById('total').value);
                        if(total>0)
                        {
                            document.getElementById('showNext').style.display='block';
                        }
						if(  document.getElementById('showNext').firstChild.data =='Suivant'){
							document.getElementById('item1').style.display = 'block';
						}
                    }
                }); // ajax
            }else{
                 Swal.fire({
                    type: 'error',
                    title: 'oups...',
                    text: "SVP, Sélectionner le gouvernorat et la spécialité"
                });
            }
        }); // change
 
    /*    $("#choisir").click(function() {
            //selected= document.getElementById('selected').value;
            selected=    $("#selected").val();
            document.getElementById('selectedprest').value = document.getElementById('prestataire_id_'+selected).value ;
        });
*/
       
  $("#rechercherm").click(function(){
           document.getElementById('showNext-m').style.display='none';
            document.getElementById('add2-m').style.display='none';
             document.getElementById('showNext-m').firstChild.data ='Commencer';
$('#showNext-m').prop('disabled', false);
            document.getElementById('add2prest-m').style.display='none';
            document.getElementById('selectedprest-m').value=0;
            var typeprestom=  document.getElementById('templateom').value;
            var specialite=  document.getElementById('specialitem').value;
            
            /*
                Taxi: - type:2 - specialite:2
                Remorquage: - type:1 - specialite:3
                Ambulance: -type:4 - specialite:4
            */
            // TAXI
            if ((typeprestom==="Taxi")&&(typeprestom !=="")) {typeprest=2; type=2; }
            // AMBULANCE
            if ((typeprestom==="Ambulance")&&(typeprestom !=="")) {typeprest=4; type=4; }
            // REMORQUAGE
            if ((typeprestom==="Remorquage")&&(typeprestom !=="")) {typeprest=1; type=1;}
            // cas remplace
            var srcomtemp = document.getElementById("omfilled").src;
            var posomtaxitemp = srcomtemp.indexOf("odm_taxi");
            var posomambulancetemp = srcomtemp.indexOf("odm_ambulance");
            var posomremorquagetemp = srcomtemp.indexOf("odm_remorquage");
            if(((typeprestom === "") || (typeprestom === "Select"))&&(posomtaxitemp != -1)) {typeprest=2; type=2;}
            if(((typeprestom === "") || (typeprestom === "Select"))&&(posomambulancetemp != -1)) {typeprest=4; type=4; }
            if(((typeprestom === "") || (typeprestom === "Select"))&&(posomremorquagetemp != -1)) {typeprest=1; type=1;}
            //document.getElementById('tprest2-'+typeprest).style.display='block';
            //  prest = $(this).val();
            document.getElementById('selectedprest-m').value=0;
            //var  type =document.getElementById('typeprest2').value;
            var  gouv =document.getElementById('gouvcouvm').value;
            //var  specialite =document.getElementById('specialite').value;
            var  ville =document.getElementById('villeprm').value;
            var  postal =document.getElementById('villecodem').value; 
            if((type !="")&&(gouv !=""))
            {
                var _token = $('input[name="_token"]').val();
                document.getElementById('termine-m').style.display = 'none';
                document.getElementById('add2-m').style.display = 'none';
                document.getElementById('add2prest-m').style.display='none';
               console.log('in ajax') ;
                $.ajax({
                    url:"{{ route('dossiers.listepresm') }}",
                    method:"post",
                    data:{gouv:gouv,type:type,specialite:specialite,ville:ville,postal:postal, _token:_token},
                    success:function(data){
                        $('#data-m').html(data);
                        console.log("success list prest");
                        //window.location =data;
                        console.log(data);
                        ////       data.map((item, i) => console.log('Index:', i, 'Id:', item.id));
                        var  total =parseInt(document.getElementById('total-m').value);
                        if(total>0)
                        {
                            document.getElementById('showNext-m').style.display='block';
                        }
						if(  document.getElementById('showNext-m').firstChild.data =='Suivant'){
							document.getElementById('item1-m').style.display = 'block';
						}
                    }
                }); // ajax
            }else{
                 Swal.fire({
                    type: 'error',
                    title: 'oups...',
                    text: "SVP, Sélectionner le gouvernorat et la spécialité"
                });
            }
        });
        $("#essai2").click(function() {
   /*         document.getElementById('start').value = 1;
            document.getElementById('termine').style.display = 'none';
            document.getElementById('add2').style.display = 'block';
            document.getElementById('valide').style.display = 'block';
            document.getElementById('validation').style.display = 'block';
            document.getElementById('add2prest').style.display='block';
            document.getElementById('showNext').style.display = 'block';
            //document.getElementById('showNext').firstChild.data   = 'Commencer';
            document.getElementById('item1').style.display = 'block';
            document.getElementById('selected').value = 1;
            document.getElementById('selectedprest').value = 0;
   document.getElementById('selectedprest').value = document.getElementById('prestataire_id_1_').value ;
   $('#showNext').prop('disabled', true);
	  $('#add2').prop('disabled', false);
*/
     document.getElementById('selected').value = 1;
     document.getElementById('selectedprest').value = 0;
  $( "#rechercher" ).trigger( "click" );
 
 //$( "#showNext" ).trigger( "click" );
 document.getElementById('selected').value=1; var selected=1; next=selected+1;
   document.getElementById('selectedprest').value = document.getElementById('prestataire_id_1').value ;
   
document.getElementById('item1').style.display = 'block';
document.getElementById('add2').style.display = 'block';
  document.getElementById('selected').value = 1;
  
  
  $('#showNext').prop('disabled', true);
	  $('#add2').prop('disabled', false);
  document.getElementById('add2').style.display = 'block';
                        document.getElementById('valide').style.display = 'block';
                        document.getElementById('validation').style.display = 'block';
                        document.getElementById('add2prest').style.display='block';
                        document.getElementById('termine').style.display = 'none';
                        document.getElementById('item1').style.display = 'block';
 
 document.getElementById('showNext').firstChild.data ='Suivant';
 //  $('#showNext').prop('disabled', false);
     });
$("#essai2-m").click(function() {
   /*         document.getElementById('start').value = 1;
            document.getElementById('termine').style.display = 'none';
            document.getElementById('add2').style.display = 'block';
            document.getElementById('valide').style.display = 'block';
            document.getElementById('validation').style.display = 'block';
            document.getElementById('add2prest').style.display='block';
            document.getElementById('showNext').style.display = 'block';
            //document.getElementById('showNext').firstChild.data   = 'Commencer';
            document.getElementById('item1').style.display = 'block';
            document.getElementById('selected').value = 1;
            document.getElementById('selectedprest').value = 0;
   document.getElementById('selectedprest').value = document.getElementById('prestataire_id_1').value ;
   $('#showNext').prop('disabled', true);
	  $('#add2').prop('disabled', false);
*/
     document.getElementById('selected-m').value = 1;
     document.getElementById('selectedprest-m').value = 0;
  $( "#rechercherm" ).trigger( "click" );
 
 //$( "#showNext" ).trigger( "click" );
 document.getElementById('selected-m').value=1; var selected=1; next=selected+1;
   document.getElementById('selectedprest-m').value = document.getElementById('prestataire_id_1-m').value ;
   
document.getElementById('item1-m').style.display = 'block';
document.getElementById('add2-m').style.display = 'block';
  document.getElementById('selected-m').value = 1;
  
  
  $('#showNext-m').prop('disabled', true);
	  $('#add2-m').prop('disabled', false);
  document.getElementById('add2-m').style.display = 'block';
                        document.getElementById('valide-m').style.display = 'block';
                        document.getElementById('validation-m').style.display = 'block';
                        document.getElementById('add2prest-m').style.display='block';
                        document.getElementById('termine-m').style.display = 'none';
                        document.getElementById('item1-m').style.display = 'block';
 
 document.getElementById('showNext-m').firstChild.data ='Suivant';
 //  $('#showNext').prop('disabled', false);
     });
      
        $("#statutprest").change(function() {
 if(document.getElementById('statutprest').value=='autre'){
    document.getElementById('detailsprest').style.display='block';
}else{
    document.getElementById('detailsprest').style.display='none';
}
 $('#showNext').prop('disabled', false);
 });
   $("#statutprest-m").change(function() {
 if(document.getElementById('statutprest-m').value=='autre'){
    document.getElementById('detailsprest-m').style.display='block';
}else{
    document.getElementById('detailsprest-m').style.display='none';
}
 $('#showNext-m').prop('disabled', false);
 });
   $("#showNext-m").click(function() {
	var start=  document.getElementById('start-m').value ;
	  var  prest =document.getElementById('selectedprest-m').value;
    ///// Enregistrement prestation
 if(    start==1  &&       document.getElementById('showNext-m').firstChild.data =='Commencer' )
{
	 document.getElementById('selected-m').value=1; var selected=1; next=selected+1;
   document.getElementById('selectedprest-m').value = document.getElementById('prestataire_id_1-m').value ;
$('#showNext-m').prop('disabled', true);
 $('#add2-m').prop('disabled', false);
 document.getElementById('add2-m').style.display = 'block';
                        document.getElementById('valide-m').style.display = 'block';
                        document.getElementById('validation-m').style.display = 'block';
                        document.getElementById('add2prest-m').style.display='block';
                        document.getElementById('termine-m').style.display = 'none';
                        document.getElementById('item1-m').style.display = 'block';
                     //   document.getElementById('item'+String(selected)).style.display = 'none';
                     //   document.getElementById('item'+String(next)).style.display = 'block';
                 //      $("#selected").val(next);
 document.getElementById('showNext-m').firstChild.data ='Suivant';
  }
  else{
	  document.getElementById('start-m').value =0;
	  
	   var  prestation =document.getElementById('idprestation-m').value;
            //    var  prestataire =document.getElementById('selectedprest').value;
                var  statut =document.getElementById('statutprest-m').value;
                var  details =document.getElementById('detailsprest-m').value;
				
	            ///////    $("#selected").val(selected+1);
/*
                selected=  parseInt(document.getElementById('selected').value);
				alert(selected) ;
                if(selected >1 ) {
                document.getElementById('selectedprest').value = document.getElementById('prestataire_id_'+selected).value ;
                var prestataire = $('#selectedprest').val();
                var dossier_id = $('#dossier').val();
                var typeprest = $('#typeprest').val();
                var gouvernorat = $('#gouvcouv').val();
                var specialite = $('#specialite').val();
                var date = $('#pres_date').val();
                //   gouvcouv
                if ((parseInt(prestataire) >0)&&(parseInt(dossier_id) >0)&&(parseInt(typeprest) >0))
                {
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('prestations.saving') }}",
                        method:"POST",
                        data:{date:date,prestataire:prestataire,dossier_id:dossier_id,specialite:specialite,gouvernorat:gouvernorat,typeprest:typeprest, _token:_token},
                        success:function(data){
                            var prestation=parseInt(data);
                            /// window.location =data;
                        //    document.getElementById('prestation').style.display='block';
                        //    document.getElementById('valide').style.display='block';
                        //    document.getElementById('idprestation').value =prestation;
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                        }
                    });
                }else{
                }
    }
 */
                document.getElementById('showNext-m').firstChild.data  ='Suivant';
                var shownext=false;var infos=false;
				if( document.getElementById('firstsaved-m').value==0)
				{
				  var prestataire = $('#selectedprest-m').val();
			  var nomprestataire = $('#selectedprest-m option:selected').text();
 			//  alert(nomprestataire);
                var dossier_id = $('#dossier').val();
              var typeprestom = document.getElementById('templateom').value;
            var gouvernorat = $('#gouvcouvm').val();
            var specialite = $('#specialitem').val();
 if ((typeprestom==="Taxi")&&(typeprestom !=="")) {typeprest=2; type=2; }
            // AMBULANCE
            if ((typeprestom==="Ambulance")&&(typeprestom !=="")) {typeprest=4; type=4;}
            // REMORQUAGE
            if ((typeprestom==="Remorquage")&&(typeprestom !=="")) {typeprest=1; type=1;}
            // cas remplace
            var srcomtemp = document.getElementById("omfilled").src;
            var posomtaxitemp = srcomtemp.indexOf("odm_taxi");
            var posomambulancetemp = srcomtemp.indexOf("odm_ambulance");
            var posomremorquagetemp = srcomtemp.indexOf("odm_remorquage");
            if(((typeprestom === "") || (typeprestom === "Select"))&&(posomtaxitemp != -1)) {typeprest=2; type=2;}
            if(((typeprestom === "") || (typeprestom === "Select"))&&(posomambulancetemp != -1)) {typeprest=4; type=4; }
            if(((typeprestom === "") || (typeprestom === "Select"))&&(posomremorquagetemp != -1)) {typeprest=1; type=1;}
              var date = $('#pres_datem').val();
                //   gouvcouv
                if ((parseInt(prestataire) >0)&&(parseInt(dossier_id) >0)&&(parseInt(typeprest) >0))
                {
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('prestations.saving') }}",
                        method:"POST",
                        data:{date:date,prestataire:prestataire,dossier_id:dossier_id,specialite:specialite,gouvernorat:gouvernorat,typeprest:typeprest, _token:_token},
                        success:function(data){
                            var prestation=parseInt(data);
                            // window.location =data;
							document.getElementById('idprestation-m').value=prestation;
                        //    document.getElementById('prestation').style.display='block';
                        //    document.getElementById('valide').style.display='block';
                        //    document.getElementById('idprestation').value =prestation;
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                        }
                    });
                }else{
                }
				
				 }
				 document.getElementById('firstsaved-m').value=0;
				
// 				
 var _token = $('input[name="_token"]').val(); 
                var  prestation =document.getElementById('idprestation-m').value; 
                var  prestataire =document.getElementById('selectedprest-m').value;
            //    var  statut =document.getElementById('statutprest').value;
            //    var  details =document.getElementById('detailsprest').value;
  var nomprestataire = $('#selectedprest-m option:selected').text();
 		//	  alert(nomprestataire);
 		//	  alert(prestation);
			  
                $.ajax({
                    url:"{{ route('prestations.updatestatut') }}",
                    method:"POST",
                    data:{prestation:prestation,prestataire:prest,statut:statut,details:details, _token:_token},
                    success:function(data){
                // reinitialiser le champs de statut
                    ///    if(document.getElementById('selectedprest').value ==0) {
                    ///        document.getElementById('statutprest').value ='';
                    ///        document.getElementById('detailsprest').value ='';}
                    }
                });				
				
				
				
				
            // reinitialiser le champs de statut
            /*if(document.getElementById('selectedprest').value ==0) {
                document.getElementById('statutprest').value ='';
            document.getElementById('detailsprest').value ='';}*/
			
			
			
 	/*****
            // si une prestation a èté ajoutée
            if(document.getElementById('idprestation').value >0) {
                if ((document.getElementById('statutprest').value == 'autre') && (document.getElementById('detailsprest').value != '')) {
                    shownext=true;infos=true;
					$('#showNext').prop('disabled', false);
                }
                if ((document.getElementById('statutprest').value == 'nonjoignable') || (document.getElementById('statutprest').value == 'nondisponible')) {
                    shownext=true;infos=true;
					$('#showNext').prop('disabled', false);
                }
            }
            else{shownext=true;}
             if(shownext==true)
              {
            if(infos==true){
                // enregistrement des infos de prestation  + envoi des emails
                var _token = $('input[name="_token"]').val();
                var  prestation =document.getElementById('idprestation').value;
                var  prestataire =document.getElementById('selectedprest').value;
                var  statut =document.getElementById('statutprest').value;
                var  details =document.getElementById('detailsprest').value;
                $.ajax({
                    url:"{{ route('prestations.updatestatut') }}",
                    method:"POST",
                    data:{prestation:prestation,prestataire:prestataire,statut:statut,details:details, _token:_token},
                    success:function(data){
                // reinitialiser le champs de statut
                        if(document.getElementById('selectedprest').value ==0) {
                            document.getElementById('statutprest').value ='';
                            document.getElementById('detailsprest').value ='';}
                    }
                });
                document.getElementById('statutprest').selectedIndex =0;
                      if(document.getElementById('idprestation').value >0) {
                       ////   document.getElementById('prestation').style.display='none';
                      }
            }
			
			****/
                document.getElementById('selectedprest-m').value = 0;
                document.getElementById('detailsprest-m').value='';
                var selected =parseInt(document.getElementById('selected-m').value);
                var total = parseInt(document.getElementById('total-m').value);
                document.getElementById('statutprest-m').value='';
                var next = parseInt(selected) + 1;
				
				 if ((selected != 0) && (next <=  total  )) {
                document.getElementById('selected-m').value = next;
            document.getElementById('selectedprest-m').value = document.getElementById('prestataire_id_'+next+'-m').value ;
				 }
			
			
			// button reset => set prstaitaire 1
			
			
                if ((selected == 0)) {
                    document.getElementById('termine-m').style.display = 'none';
                    document.getElementById('item1-m').style.display = 'block';
                    document.getElementById('add2-m').style.display = 'block';
                    document.getElementById('valide-m').style.display = 'block';
                    document.getElementById('validation-m').style.display = 'block';
                    document.getElementById('add2prest-m').style.display='block';
                    //document.getElementById('selected').value=1;
                    // $("#selected").val('1');
                }
                if ((selected) == (total  )) {
                    document.getElementById('termine-m').style.display = 'block';
                    document.getElementById('item' + selected+'-m').style.display = 'none';
                    document.getElementById('showNext-m').style.display = 'none';
                    document.getElementById('add2-m').style.display = 'none';
                    document.getElementById('valide-m').style.display = 'none';
                    document.getElementById('validation-m').style.display = 'none';
                    document.getElementById('add2prest-m').style.display='none';
                } else {
                    if ((selected != 0) && (selected <= total + 1)) {
                        document.getElementById('add2-m').style.display = 'block';
                        document.getElementById('valide-m').style.display = 'block';
                        document.getElementById('validation-m').style.display = 'block';
                        document.getElementById('add2prest-m').style.display='block';
                        document.getElementById('termine-m').style.display = 'none';
                        document.getElementById('item' + selected+'-m').style.display = 'none';
                        document.getElementById('item' + next+'-m').style.display = 'block';
                        $("#selected-m").val(next);
                    }
                }
                  if(next>parseInt(total)+1) {
                    // document.getElementById('item' + selected).style.display = 'none';
                }
            /*    if( document.getElementById('idprestation').value>0 ){
                      document.getElementById('idprestation').value=0
                      document.getElementById('selectedprest').value = 0;
                      document.getElementById('detailsprest').value='';
                   ////   document.getElementById('prestation').style.display='none';
                      document.getElementById('statutprest').selectedIndex =0;
                  }*/
    /*        }
            else{
                if(document.getElementById('selectedprest').selectedIndex  >0) {
                  Swal.fire({
                     type: 'error',
                     title: 'Attendez...',
                     text: 'SVP Expliquez la raison de ne pas choisir ce prestataire',
                 })
            }
            }
*/
			
				
				
				
		  $('#add2-m').prop('disabled', true);
		  
	  }		
	 document.getElementById('start-m').value =0;
	
	  $('#showNext-m').prop('disabled', true);
 			 
        });
  $("#showNext").click(function() {
	var start=  document.getElementById('start').value ;
	  var  prest =document.getElementById('selectedprest').value;
    ///// Enregistrement prestation
 if(    start==1  &&       document.getElementById('showNext').firstChild.data =='Commencer' )
{
	 document.getElementById('selected').value=1; var selected=1; next=selected+1;
   document.getElementById('selectedprest').value = document.getElementById('prestataire_id_1').value ;
$('#showNext').prop('disabled', true);
 $('#add2').prop('disabled', false);
 document.getElementById('add2').style.display = 'block';
                        document.getElementById('valide').style.display = 'block';
                        document.getElementById('validation').style.display = 'block';
                        document.getElementById('add2prest').style.display='block';
                        document.getElementById('termine').style.display = 'none';
                        document.getElementById('item1').style.display = 'block';
                     //   document.getElementById('item'+String(selected)).style.display = 'none';
                     //   document.getElementById('item'+String(next)).style.display = 'block';
                 //      $("#selected").val(next);
 document.getElementById('showNext').firstChild.data ='Suivant';
  }
  else{
	  document.getElementById('start').value =0;
	  
	   var  prestation =document.getElementById('idprestation').value;
            //    var  prestataire =document.getElementById('selectedprest').value;
                var  statut =document.getElementById('statutprest').value;
                var  details =document.getElementById('detailsprest').value;
				
	            ///////    $("#selected").val(selected+1);
/*
                selected=  parseInt(document.getElementById('selected').value);
				alert(selected) ;
                if(selected >1 ) {
                document.getElementById('selectedprest').value = document.getElementById('prestataire_id_'+selected).value ;
                var prestataire = $('#selectedprest').val();
                var dossier_id = $('#dossier').val();
                var typeprest = $('#typeprest').val();
                var gouvernorat = $('#gouvcouv').val();
                var specialite = $('#specialite').val();
                var date = $('#pres_date').val();
                //   gouvcouv
                if ((parseInt(prestataire) >0)&&(parseInt(dossier_id) >0)&&(parseInt(typeprest) >0))
                {
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('prestations.saving') }}",
                        method:"POST",
                        data:{date:date,prestataire:prestataire,dossier_id:dossier_id,specialite:specialite,gouvernorat:gouvernorat,typeprest:typeprest, _token:_token},
                        success:function(data){
                            var prestation=parseInt(data);
                            /// window.location =data;
                        //    document.getElementById('prestation').style.display='block';
                        //    document.getElementById('valide').style.display='block';
                        //    document.getElementById('idprestation').value =prestation;
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                        }
                    });
                }else{
                }
    }
 */
                document.getElementById('showNext').firstChild.data  ='Suivant';
                var shownext=false;var infos=false;
				if( document.getElementById('firstsaved').value==0)
				{
				  var prestataire = $('#selectedprest').val();
			  var nomprestataire = $('#selectedprest option:selected').text();
 			//  alert(nomprestataire);
                var dossier_id = $('#dossier').val();
                var typeprest = $('#typeprest').val();
                var gouvernorat = $('#gouvcouv').val();
                var specialite = $('#specialite').val();
                var date = $('#pres_date').val();
                //   gouvcouv
                if ((parseInt(prestataire) >0)&&(parseInt(dossier_id) >0)&&(parseInt(typeprest) >0))
                {
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('prestations.saving') }}",
                        method:"POST",
                        data:{date:date,prestataire:prestataire,dossier_id:dossier_id,specialite:specialite,gouvernorat:gouvernorat,typeprest:typeprest, _token:_token},
                        success:function(data){
                            var prestation=parseInt(data);
                            // window.location =data;
							document.getElementById('idprestation').value=prestation;
                        //    document.getElementById('prestation').style.display='block';
                        //    document.getElementById('valide').style.display='block';
                        //    document.getElementById('idprestation').value =prestation;
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                        }
                    });
                }else{
                }
				
				 }
				 document.getElementById('firstsaved').value=0;
				
// 				
 var _token = $('input[name="_token"]').val(); 
                var  prestation =document.getElementById('idprestation').value; 
                var  prestataire =document.getElementById('selectedprest').value;
            //    var  statut =document.getElementById('statutprest').value;
            //    var  details =document.getElementById('detailsprest').value;
  var nomprestataire = $('#selectedprest option:selected').text();
 		//	  alert(nomprestataire);
 		//	  alert(prestation);
			  
                $.ajax({
                    url:"{{ route('prestations.updatestatut') }}",
                    method:"POST",
                    data:{prestation:prestation,prestataire:prest,statut:statut,details:details, _token:_token},
                    success:function(data){
                // reinitialiser le champs de statut
                    ///    if(document.getElementById('selectedprest').value ==0) {
                    ///        document.getElementById('statutprest').value ='';
                    ///        document.getElementById('detailsprest').value ='';}
                    }
                });				
				
				
				
				
            // reinitialiser le champs de statut
            /*if(document.getElementById('selectedprest').value ==0) {
                document.getElementById('statutprest').value ='';
            document.getElementById('detailsprest').value ='';}*/
			
			
			
 	/*****
            // si une prestation a èté ajoutée
            if(document.getElementById('idprestation').value >0) {
                if ((document.getElementById('statutprest').value == 'autre') && (document.getElementById('detailsprest').value != '')) {
                    shownext=true;infos=true;
					$('#showNext').prop('disabled', false);
                }
                if ((document.getElementById('statutprest').value == 'nonjoignable') || (document.getElementById('statutprest').value == 'nondisponible')) {
                    shownext=true;infos=true;
					$('#showNext').prop('disabled', false);
                }
            }
            else{shownext=true;}
             if(shownext==true)
              {
            if(infos==true){
                // enregistrement des infos de prestation  + envoi des emails
                var _token = $('input[name="_token"]').val();
                var  prestation =document.getElementById('idprestation').value;
                var  prestataire =document.getElementById('selectedprest').value;
                var  statut =document.getElementById('statutprest').value;
                var  details =document.getElementById('detailsprest').value;
                $.ajax({
                    url:"{{ route('prestations.updatestatut') }}",
                    method:"POST",
                    data:{prestation:prestation,prestataire:prestataire,statut:statut,details:details, _token:_token},
                    success:function(data){
                // reinitialiser le champs de statut
                        if(document.getElementById('selectedprest').value ==0) {
                            document.getElementById('statutprest').value ='';
                            document.getElementById('detailsprest').value ='';}
                    }
                });
                document.getElementById('statutprest').selectedIndex =0;
                      if(document.getElementById('idprestation').value >0) {
                       ////   document.getElementById('prestation').style.display='none';
                      }
            }
			
			****/
                document.getElementById('selectedprest').value = 0;
                document.getElementById('detailsprest').value='';
                var selected =parseInt(document.getElementById('selected').value);
                var total = parseInt(document.getElementById('total').value);
                document.getElementById('statutprest').value='';
                var next = parseInt(selected) + 1;
				
				 if ((selected != 0) && (next <=  total  )) {
                document.getElementById('selected').value = next;
            document.getElementById('selectedprest').value = document.getElementById('prestataire_id_'+next).value ;
				 }
			
			
			// button reset => set prstaitaire 1
			
			
                if ((selected == 0)) {
                    document.getElementById('termine').style.display = 'none';
                    document.getElementById('item1').style.display = 'block';
                    document.getElementById('add2').style.display = 'block';
                    document.getElementById('valide').style.display = 'block';
                    document.getElementById('validation').style.display = 'block';
                    document.getElementById('add2prest').style.display='block';
                    //document.getElementById('selected').value=1;
                    // $("#selected").val('1');
                }
                if ((selected) == (total  )) {
                    document.getElementById('termine').style.display = 'block';
                    document.getElementById('item'+(selected)).style.display = 'none';
                    document.getElementById('showNext').style.display = 'none';
                    document.getElementById('add2').style.display = 'none';
                    document.getElementById('valide').style.display = 'none';
                    document.getElementById('validation').style.display = 'none';
                    document.getElementById('add2prest').style.display='none';
                } else {
                    if ((selected != 0) && (selected <= total + 1)) {
                        document.getElementById('add2').style.display = 'block';
                        document.getElementById('valide').style.display = 'block';
                        document.getElementById('validation').style.display = 'block';
                        document.getElementById('add2prest').style.display='block';
                        document.getElementById('termine').style.display = 'none';
                        document.getElementById('item'+String(selected)).style.display = 'none';
                        document.getElementById('item'+String(next)).style.display = 'block';
                        $("#selected").val(next);
                    }
                }
                  if(next>parseInt(total)+1) {
                    // document.getElementById('item' + selected).style.display = 'none';
                }
            /*    if( document.getElementById('idprestation').value>0 ){
                      document.getElementById('idprestation').value=0
                      document.getElementById('selectedprest').value = 0;
                      document.getElementById('detailsprest').value='';
                   ////   document.getElementById('prestation').style.display='none';
                      document.getElementById('statutprest').selectedIndex =0;
                  }*/
    /*        }
            else{
                if(document.getElementById('selectedprest').selectedIndex  >0) {
                  Swal.fire({
                     type: 'error',
                     title: 'Attendez...',
                     text: 'SVP Expliquez la raison de ne pas choisir ce prestataire',
                 })
            }
            }
*/
			
				
				
				
		  $('#add2').prop('disabled', true);
		  
	  }		
	 document.getElementById('start').value =0;
	
	  $('#showNext').prop('disabled', true);
 			 
        });
        $('#addpr1').click(function(){
            var nom = $('#nom').val();
            var prenom = $('#prenom').val();
            var dossier = $('#dossier').val();
            if ((nom != '')&&(prenom != '') )
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('prestataires.saving') }}",
                    method:"POST",
                    data:{nom:nom,prenom:prenom,dossier:dossier, _token:_token},
                    success:function(data){
                        window.location =data;
                    }
                });
            }else{
            }
        });
        $('#addpr2').click(function(){
            var prestataire = $('#selectable').val();
             var dossier = $('#dossier').val();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('intervenants.saving') }}",
                    method:"POST",
                    data:{prestataire:prestataire,dossier:dossier, _token:_token},
                    success:function(data){
                        location.reload();
/// here
                    }
                });
        });
        var url = document.location.toString();
        if (url.match('#')) {
            $('.nav-item a[href="#' + url.split('#')[1] + '"]').tab('show');
        }
// Change hash for page-reload
        $('.nav-item a').on('shown.bs.tab', function (e) {
            window.location.hash = e.target.hash;
        })
    }); // $ function
    function setTel(elm)
    {
        var num=elm.className;
        document.getElementById('ledestinataire').value=parseInt(num);
    }
    function init(id,nom)
    {
        document.getElementById('selectedprest2').value =id;
        document.getElementById('inputprest').value =nom;
        filtre(id);
    }
// filtrer type prestation dans ajout prestation
    function filtre(prestataire) {
        $("#ajout_typeprest").val('');
        var liste ;
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url:"{{ route('prestataires.listetypes') }}",
            method:"POST",
            data:{prestataire:prestataire,  _token:_token},
            success:function(data){
                liste=data;
                console.log('data : '+data);
                //   alert('Added successfully');
                $('#ajout_typeprest option').each(function() {
                    $(this).css("display", "none");
                });
                $('#ajout_typeprest option').each(function() {
                    // console.log(  $(this).val());
                    for (i=0;i< liste.length ;i++){
                        if(liste[i]== $(this).val() )
                        {//alert('1');
                            $(this).css("display", "block");
                            break;
                        }
                    }
                });
            }
        });
    }
</script>
<script>
   $(document).on('click','#actualiserAtt',function(e){
    
     location.reload();
   });
 $(document).on("submit","#formFileExterne",function(e) {
 // $("#formFileExterne").submit(function(e) {
    e.preventDefault();
    var en=true;
     //alert('ok');
    if(!$('#fileExterneDoss').val())
     {
      alert('Vous devez sélectionner un fichier à envoyer');
      en=false;
     }
    if(en==true)
   {
    //var donnees = $('#formFileExterne').serialize();
    var dataString = new FormData(jQuery(document).find('#formFileExterne')[0]);
    
    $.ajax({
                    type:"post",
                    url:"{{ route('Upload.ExterneFile')}}",
                    data:dataString,                  
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() 
                    {
                        $("#successUloadExterneFile").html('<div align="left"><img src="{{ URL::asset('public/img/progress.gif')}}" width="100%" height="8%" align="absmiddle" title="Upload...."/></div>');
                         setTimeout(function() {
                            
                                                  },2000);                      
                       
                    },
                    success:function(response) 
                    {
                        //alert(response);
                       $("#successUloadExterneFile").empty().html('<span style="color:green">le fichier est envoyé avec succès</span>');
                    },
                      error: function(jqXHR, textStatus, errorThrown) {
                        //alert('erreur lors de création de la mission');
                    $("#successUloadExterneFile").empty().html('<span style="color:red">Erreur lors de l\'envoi du fichier au serveur</span>');
            }
                });
}
 });
 /*function launchPhone() {
                           
                         num=document.getElementById('numtel').options[document.getElementById('numtel').selectedIndex].value;
                                window.open('http://192.168.1.249/najdatest/public/najdaapp/najdaapp/webphone/samples/mobile.php?wp_callto='+num,'phone',
                               'menubar=no,location=no,resizable=no,scrollbars=no,status=no,addressbar=no,width=295,height=575,');
                                $("#faireappel").modal('hide');
num=document.getElementById('numtel').options[document.getElementById('numtel').selectedIndex].value;
webphone_api.onAppStateChange (function (state)

{

          if (state === 'loaded')

          {

                           

                                webphone_api.setparameter('serveraddress', '192.168.1.248');

                                webphone_api.setparameter('username', '2000');

                                webphone_api.setparameter('password', '3862oOPD3F');


                                

                                webphone_api.start();

 

                               
                                webphone_api.call(num);

 

                               


                           }

});

                               }*/




    


       
        function ButtonOnclick()
        {
document.getElementById('natureappel').value='dossier';

                     $('#appelinterfaceenvoi').modal({show:true});


     num=document.getElementById('numtel').options[document.getElementById('numtel').selectedIndex].value;
nom=document.getElementById('numtel').options[document.getElementById('numtel').selectedIndex].title;

     $(".modal-body #numencours").val( num );

//alert(nom);
$(".modal-body #nomencours").val(nom );
  $("#faireappel").modal('hide');
                
 
  /**Configuration parameters*/
 /*var extensiontel = $('#extensiontel').val();
 var motdepassetel = $('#motdepassetel').val();
//alert(extensiontel);
        webphone_api.parameters['username'] = extensiontel;      // SIP account username
        webphone_api.parameters['password'] = motdepassetel;      // SIP account password (see the "Parameters encryption" in the documentation)        
        webphone_api.parameters['callto'] = '';        // destination number to call
        webphone_api.parameters['autoaction'] = 0;     // 0=nothing (default), 1=call, 2=chat, 3=video call
        webphone_api.parameters['autostart'] = 0;     // start the webphone only when button is clicked
  webphone_api.parameters['voicerecupload'] = 'ftp://mizutest:NajdaApp2020!@host.enterpriseesolutions.com/voice_CALLER_CALLED.wav'; 
 webphone_api.start();*/
            num=document.getElementById('numtel').options[document.getElementById('numtel').selectedIndex].value;
//alert(webphone_api.getstatus());
//document.getElementById("status_call").innerHTML= webphone_api.parameters.getstatus();
                webphone_api.call(num);

//testiscall();


}

           function Hangup1()
        {
            webphone_api.hangup();
            
        }
    function transfer1()
        {
numtrans=$('#numatrans1').val();
//numtrans.toString();
//alert(numtrans);
            webphone_api.transfer(numtrans);
        }
  function hold1(state)
        {
//alert('state');
if(state===true)

         {  

 webphone_api.hold(state);
document.getElementById('mettreenattenteenv').style.display = 'none';
document.getElementById('reprendreappelenv').style.display = 'inline-block';}
if(state===false)

         {   webphone_api.hold(state);
document.getElementById('reprendreappelenv').style.display = 'none';
document.getElementById('mettreenattenteenv').style.display = 'inline-block';}

        }
function mute1(state,direction)
        {
if(state===true)

         {   webphone_api.mute(state,direction);
document.getElementById('coupersonenv').style.display = 'none';
document.getElementById('reactivesonenv').style.display = 'inline-block';}
if(state===false)

         {   webphone_api.mute(state,direction);
document.getElementById('reactivesonenv').style.display = 'none';
document.getElementById('coupersonenv').style.display = 'inline-block';}

        }
   
/*webphone_api.onCallStateChange(function (event, direction, peername, peerdisplayname, line, callid)
        {
console.log("sirine"+event+direction+peername);
           
            
            // end of a call, even if it wasn't successfull
         
});
/*function testiscall()
{
event='test';
while (  event!=='setup')
 {

          
 document.getElementById('status_call').innerHTML ="appel en attente";
              
               
            }
            
            // end of a call, even if it wasn't successfull
         


document.getElementById('status_call').innerHTML ="appel en cours";

    }*/
 
</script>
<!-- <script src="http://malsup.github.com/jquery.form.js"></script>
<script>
$(document).ready(function(){
    $('#formFilekbs').ajaxForm({
      beforeSend:function(){
        $('#success').empty();
      },
      uploadProgress:function(event, position, total, percentComplete)
      {
        $('.progress-bar').text(percentComplete + '%');
        $('.progress-bar').css('width', percentComplete + '%');
      },
      success:function(data)
      {
        if(data.errors)
        {
          $('.progress-bar').text('0%');
          $('.progress-bar').css('width', '0%');
          $('#success').html('<span class="text-danger"><b>'+data.errors+'</b></span>');
        }
        if(data.success)
        {
          $('.progress-bar').text('Uploaded');
          $('.progress-bar').css('width', '100%');
          $('#success').html('<span class="text-success"><b>'+data.success+'</b></span><br /><br />');
          //$('#success').append(data.image);
        }
      }
    });
});
</script> -->
<style>.headtable{background-color: grey!important;color:white;}
    table{margin-bottom:40px;}
</style>



<style>
    /* The check */
    .check {
        display: block;
        position: relative;
        padding-left: 25px;
        margin-bottom: 12px;
        padding-right: 15px;
        cursor: pointer;
        font-size: 18px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }
    /* Hide the browser's default checkbox */
    .check input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }
    /* Create a custom checkbox */
    .checkmark {
        position: absolute;
        top: 3px;
        left: 0;
        height: 18px;
        width: 18px;
        background-color: #fff ;
        border-color:#5D9CEC;
        border-style:solid;
        border-width:2px;
    }
    /* When the checkbox is checked, add a blue background */
    .check input:checked ~ .checkmark {
        background-color: #fff  ;
    }
    /* Create the checkmark/indicator (hidden when not checked) */
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }
    /* Show the checkmark when checked */
    .check input:checked ~ .checkmark:after {
        display: block;
    }
    /* Style the checkmark/indicator */
    .check .checkmark:after {
        left: 5px;
        top: 1px;
        width: 5px;
        height: 10px;
        border: solid ;
        border-color:#5D9CEC;
        border-width: 0 3px 3px 0;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
    }
    section#timeline {
        width: 100%;
        margin: 20px auto;
        position: relative;
    }
    section#timeline:before {
        content: '';
        display: block;
        position: absolute;
        left: 50%;
        top: 0;
        margin: 0 0 0 -1px;
        width: 2px;
        height: 100%;
        background: rgba(255,255,255,0.2);
    }
    section#timeline article {
        width: 100%;
        margin: 0 0 20px 0;
        position: relative;
    }
    section#timeline article:after {
        content: '';
        display: block;
        clear: both;
    }
    section#timeline article div.inner {
        width: 40%;
        float: left;
        margin: 5px 0 0 0;
        border-radius: 6px;
    }
    section#timeline article div.inner span.date {
        display: block;
        width: 60px;
        height: 50px;
        padding: 5px 0;
        position: absolute;
        top: 0;
        left: 50%;
        margin: 0 0 0 -32px;
        border-radius: 100%;
        font-size: 12px;
        font-weight: 900;
        text-transform: uppercase;
        background: white;
        /* background: #25303B;
         color: rgba(255,255,255,0.5);
         border: 2px solid rgba(255,255,255,0.2);
         box-shadow: 0 0 0 7px #25303B;*/
    }
    section#timeline article div.inner span.date span {
        display: block;
        text-align: center;
    }
    section#timeline article div.inner span.date span.day {
        font-size: 10px;
    }
    section#timeline article div.inner span.date span.month {
        font-size: 18px;
    }
    section#timeline article div.inner span.date span.year {
        font-size: 10px;
    }
    section#timeline article div.inner h2 {
        padding: 15px;
        margin: 0;
        color: #fff;
        font-size: 20px;
        text-transform: uppercase;
        letter-spacing: -1px;
        border-radius: 6px 6px 0 0;
        position: relative;
    }
    section#timeline article div.inner h2:after {
        content: '';
        position: absolute;
        top: 20px;
        right: -5px;
        width: 10px;
        height: 10px;
        -webkit-transform: rotate(-45deg);
    }
    section#timeline article div.inner p {
        padding: 15px;
        margin: 0;
        font-size: 14px;
        background: #fff;
        color: #656565;
        border-radius: 0 0 6px 6px;
    }
    section#timeline article:nth-child(2n+2) div.inner {
        /* float: right;*/
    }
    section#timeline article:nth-child(2n+2) div.inner h2:after {
        /*left: -5px;*/
    }
    section#timeline  article div.inner.sent {
        float: right;
    }
    section#timeline article div.inner.sent h2:after {
        left: -5px;
    }
    section#timeline article  div.inner h2 {
        background: #e74c3c;
    }
    section#timeline article  div.inner h2:after {
        background: #e74c3c;
    }
    section#timeline article:nth-child(1) div.inner h2 {
        background: #e74c3c;
    }
    section#timeline article:nth-child(1) div.inner h2:after {
        background: #e74c3c;
    }
    section#timeline article:nth-child(2) div.inner h2 {
        background: #2ecc71;
    }
    section#timeline article:nth-child(2) div.inner h2:after {
        background: #2ecc71;
    }
    section#timeline article:nth-child(3) div.inner h2 {
        background: #e67e22;
    }
    section#timeline article:nth-child(3) div.inner h2:after {
        background: #e67e22;
    }
    section#timeline article:nth-child(4) div.inner h2 {
        background: #1abc9c;
    }
    section#timeline article:nth-child(4) div.inner h2:after {
        background: #1abc9c;
    }
    section#timeline article:nth-child(5) div.inner h2 {
        background: #9b59b6;
    }
    section#timeline article:nth-child(5) div.inner h2:after {
        background: #9b59b6;
    }
    section#timeline article:nth-child(6) div.inner h2 {
        background: #F8C471;
    }
    section#timeline article:nth-child(6) div.inner h2:after {
        background: #F8C471;
    }
    section#timeline article:nth-child(7) div.inner h2 {
        background: #85C1E9;
    }
    section#timeline article:nth-child(7) div.inner h2:after {
        background: #85C1E9;
    }
    section#timeline article:nth-child(8) div.inner h2 {
        background: #909497;
    }
    section#timeline article:nth-child(8) div.inner h2:after {
        background: #909497;
    }
    section#timeline article:nth-child(9) div.inner h2 {
        background: #F1948A  ;
    }
    section#timeline article:nth-child(9) div.inner h2:after {
        background: #F1948A  ;
    }
    section#timeline article:nth-child(10) div.inner h2 {
        background: #7DCEA0;
    }
    section#timeline article:nth-child(10) div.inner h2:after {
        background: #7DCEA0;
    }
    section#timeline article:nth-child(11) div.inner h2 {
        background: #B7950B;
    }
    section#timeline article:nth-child(11) div.inner h2:after {
        background: #B7950B;
    }
    section#timeline article:nth-child(11) div.inner h2 {
        background: #F5B7B1;
    }
    section#timeline article:nth-child(11) div.inner h2:after {
        background: #F5B7B1;
    }
    .overme {
        overflow:hidden;
        white-space:nowrap;
        text-overflow: ellipsis;
        max-width:250px!important;
    }
.swal2-popup.swal2-modal.swal2-show {
    z-index: 1000000!important;
}
.swal2-container.swal2-center.swal2-fade.swal2-shown {
    z-index: 1000000!important;
}
    .dataTables_filter{
        float:right;
    }
</style>





@section('footer_scripts')

    <script type="text/javascript" src="{{ asset('resources/assets/datatables/js/jquery.dataTables.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('resources/assets/datatables/js/dataTables.bootstrap.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('resources/assets/datatables/js/dataTables.rowReorder.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('resources/assets/datatables/js/dataTables.scroller.js') }}" ></script>

    <script type="text/javascript" src="{{ asset('resources/assets/datatables/js/dataTables.buttons.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('resources/assets/datatables/js/dataTables.responsive.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('resources/assets/datatables/js/buttons.colVis.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('resources/assets/datatables/js/buttons.html5.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('resources/assets/datatables/js/buttons.print.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('resources/assets/datatables/js/buttons.bootstrap.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('resources/assets/datatables/js/buttons.print.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('resources/assets/datatables/js/pdfmake.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('resources/assets/datatables/js/vfs_fonts.js') }}" ></script>

    <style>.searchfield{width:100px;}</style>


    <script type="text/javascript">
        $(document).ready(function() {
            $('#mytable thead tr:eq(1) th').each( function () {
                var title = $('#mytable thead tr:eq(0) th').eq( $(this).index() ).text();
                //  $(this).html( '<input class="searchfield" type="text" placeholder="'+title+'" />' );
                $(this).html( '<input class="searchfield" type="text"   />' );
            } );

 
            var table = $('#mytable').DataTable({
                "aaSorting": [],
                orderCellsTop: true,
                dom : '<"top"flp<"clear">>rt<"bottom"ip<"clear">>',
                responsive:true,
                buttons: [
                    'csv', 'excel', 'pdf', 'print'
                ],
                "columnDefs": [ {
                    "targets": 'no-sort',
                    "orderable": false,
                } ]
                ,
                "language":
                    {
                        "decimal":        "",
                        "emptyTable":     "Pas de données",
                        "info":           "affichage de  _START_ à _END_ de _TOTAL_ entrées",
                        "infoEmpty":      "affichage 0 à 0 de 0 entrées",
                        "infoFiltered":   "(Filtrer de _MAX_ total d`entrées)",
                        "infoPostFix":    "",
                        "thousands":      ",",
                        "lengthMenu":     "affichage de _MENU_ entrées",
                        "loadingRecords": "chargement...",
                        "processing":     "chargement ...",
                        "search":         "Recherche:",
                        "zeroRecords":    "Pas de résultats",
                        "paginate": {
                            "first":      "Premier",
                            "last":       "Dernier",
                            "next":       "Suivant",
                            "previous":   "Précédent"
                        },
                        "aria": {
                            "sortAscending":  ": activer pour un tri ascendant",
                            "sortDescending": ": activer pour un tri descendant"
                        }
                    }
            });
// Apply the search
            function delay(callback, ms) {
                var timer = 0;
                return function() {
                    var context = this, args = arguments;
                    clearTimeout(timer);
                    timer = setTimeout(function () {
                        callback.apply(context, args);
                    }, ms || 0);
                };
            }
            table.columns().every(function (index) {
                $('#mytable thead tr:eq(1) th:eq(' + index + ') input').on('keyup change', function () {
                    table.column($(this).parent().index() + ':visible')
                        .search(this.value)
                        .draw();
                });
                $('#mytable thead tr:eq(1) th:eq(' + index + ') input').keyup(delay(function (e) {
                    console.log('Time elapsed!', this.value);
                    $(this).blur();
                }, 2000));
            });
        });
    </script>

    <script type="text/javascript">

         
 
        $(document).ready(function() {
            $('#mytableMA thead tr:eq(1) th').each( function () {
                var title = $('#mytableMA thead tr:eq(0) th').eq( $(this).index() ).text();
                //  $(this).html( '<input class="searchfield" type="text" placeholder="'+title+'" />' );
                $(this).html( '<input class="searchfield" type="text"   />' );
            } );
            var table = $('#mytableMA').DataTable({
                "aaSorting": [],
                orderCellsTop: true,
                dom : '<"top"flp<"clear">>rt<"bottom"ip<"clear">>',
                responsive:true,
                buttons: [
                    'csv', 'excel', 'pdf', 'print'
                ],
                "columnDefs": [ {
                    "targets": 'no-sort',
                    "orderable": false,
                } ]
                ,
                "language":
                    {
                        "decimal":        "",
                        "emptyTable":     "Pas de données",
                        "info":           "affichage de  _START_ à _END_ de _TOTAL_ entrées",
                        "infoEmpty":      "affichage 0 à 0 de 0 entrées",
                        "infoFiltered":   "(Filtrer de _MAX_ total d`entrées)",
                        "infoPostFix":    "",
                        "thousands":      ",",
                        "lengthMenu":     "affichage de _MENU_ entrées",
                        "loadingRecords": "chargement...",
                        "processing":     "chargement ...",
                        "search":         "Recherche:",
                        "zeroRecords":    "Pas de résultats",
                        "paginate": {
                            "first":      "Premier",
                            "last":       "Dernier",
                            "next":       "Suivant",
                            "previous":   "Précédent"
                        },
                        "aria": {
                            "sortAscending":  ": activer pour un tri ascendant",
                            "sortDescending": ": activer pour un tri descendant"
                        }
                    }
            });
              function delay(callback, ms) {
                var timer = 0;
                return function() {
                    var context = this, args = arguments;
                    clearTimeout(timer);
                    timer = setTimeout(function () {
                        callback.apply(context, args);
                    }, ms || 0);
                };
            }
            table.columns().every(function (index) {
                $('#mytableMA thead tr:eq(1) th:eq(' + index + ') input').on('keyup change', function () {
                    table.column($(this).parent().index() + ':visible')
                        .search(this.value)
                        .draw();
                });
                $('#mytableMA thead tr:eq(1) th:eq(' + index + ') input').keyup(delay(function (e) {
                    console.log('Time elapsed!', this.value);
                    $(this).blur();
                }, 2000));
            });
        });
    </script>

     <script type="text/javascript">
        $(document).ready(function() {
            $('#mytableMACC thead tr:eq(1) th').each( function () {
                var title = $('#mytableMACC thead tr:eq(0) th').eq( $(this).index() ).text();
                //  $(this).html( '<input class="searchfield" type="text" placeholder="'+title+'" />' );
                $(this).html( '<input class="searchfield" type="text"   />' );
            } );
            var table = $('#mytableMACC').DataTable({
                "aaSorting": [],
                orderCellsTop: true,
                dom : '<"top"flp<"clear">>rt<"bottom"ip<"clear">>',
                responsive:true,
                buttons: [
                    'csv', 'excel', 'pdf', 'print'
                ],
                "columnDefs": [ {
                    "targets": 'no-sort',
                    "orderable": false,
                } ]
                ,
                "language":
                    {
                        "decimal":        "",
                        "emptyTable":     "Pas de données",
                        "info":           "affichage de  _START_ à _END_ de _TOTAL_ entrées",
                        "infoEmpty":      "affichage 0 à 0 de 0 entrées",
                        "infoFiltered":   "(Filtrer de _MAX_ total d`entrées)",
                        "infoPostFix":    "",
                        "thousands":      ",",
                        "lengthMenu":     "affichage de _MENU_ entrées",
                        "loadingRecords": "chargement...",
                        "processing":     "chargement ...",
                        "search":         "Recherche:",
                        "zeroRecords":    "Pas de résultats",
                        "paginate": {
                            "first":      "Premier",
                            "last":       "Dernier",
                            "next":       "Suivant",
                            "previous":   "Précédent"
                        },
                        "aria": {
                            "sortAscending":  ": activer pour un tri ascendant",
                            "sortDescending": ": activer pour un tri descendant"
                        }
                    }
            });
              function delay(callback, ms) {
                var timer = 0;
                return function() {
                    var context = this, args = arguments;
                    clearTimeout(timer);
                    timer = setTimeout(function () {
                        callback.apply(context, args);
                    }, ms || 0);
                };
            }
            table.columns().every(function (index) {
                $('#mytableMACC thead tr:eq(1) th:eq(' + index + ') input').on('keyup change', function () {
                    table.column($(this).parent().index() + ':visible')
                        .search(this.value)
                        .draw();
                });
                $('#mytableMACC thead tr:eq(1) th:eq(' + index + ') input').keyup(delay(function (e) {
                    console.log('Time elapsed!', this.value);
                    $(this).blur();
                }, 2000));
            });
        });
    </script>

<script type="text/javascript">


//You should also handle events from the webphone and change your GUI accordingly (onXXX callbacks)
   
 $(document).on('click','.macvd', function() {
   var macvd=$(this).attr("id");
    macvd=macvd.substr(5);
  //alert(idw);
   //var nomact=$('#workflowh'+macvd).attr("value");
 
  // var typemiss=$('#workflowht'+macvd).attr("value");
     // $("#titleworkflowmodal").empty().append('<b>Mission: '+nomact+' (type de Mission: '+typemiss+')</b>');//ou la methode html
           $.ajax({
               url: "<?php echo $urlapp; ?>/Mission/getAjaxWorkflow/"+macvd,
               type : 'GET',
              // data : 'idw=' + idw,
               success: function(data){
               
              // alert(data);
               //alert(JSON.stringify(data));
              $('#contenumodalworkflowMAA').empty().html(data);
              $('#myworkflowMAA').modal('show');
                  //alert(JSON.stringify(retour))   ;
                 // location.reload();
            }
             
           });
  });
  </script>
  
  <script type="text/javascript">
   
 $(document).on('click','.mhivd', function() {
   var mhivd=$(this).attr("id");
    mhivd=mhivd.substr(5);
 // alert(mhivd);
   //var nomact=$('#workflowh'+macvd).attr("value");
 
  // var typemiss=$('#workflowht'+macvd).attr("value");
     // $("#titleworkflowmodal").empty().append('<b>Mission: '+nomact+' (type de Mission: '+typemiss+')</b>');//ou la methode html
           $.ajax({
               url: "<?php echo $urlapp; ?>/Mission/getAjaxWorkflowMach/"+mhivd,
               type : 'GET',
              // data : 'idw=' + idw,
               success: function(data){
               
              // alert(data);
               //alert(JSON.stringify(data));
              $('#contenumodalworkflowMAA').empty().html(data);
              $('#myworkflowMAA').modal('show');
                  //alert(JSON.stringify(retour))   ;
                 // location.reload();
            }
             
           });
  });
  </script>
<script>
   $(document).on('click','.mailGenermacvd', function() {
   var mailGenermacvd=$(this).attr("id");
    mailGenermacvd=mailGenermacvd.substr(6);
   //alert( mailGenermacvd);
   /*var nomact=$('#workflowh'+idw).attr("value");
   var typemiss=$('#workflowht'+idw).attr("value");
      $("#titleworkflowmodal").empty().append('<b>Mission: '+nomact+' (type de Mission: '+typemiss+')</b>');*///ou la methode html
           $.ajax({
               url: "<?php echo $urlapp; ?>/Mission/getMailGenerator/"+mailGenermacvd,
               type : 'GET',
              // data : 'idw=' + idw,
               success: function(data){
               
              //alert(data);
               //alert(JSON.stringify(data));
               $('#contenumodalworkflowMAA').empty().html(data);
              $('#myworkflowMAA').modal('show');
                  //alert(JSON.stringify(retour))   ;
                 // location.reload();
            }
             
           });
  });
  </script>

  <script>
   $(document).on('click','.mailGenermhivd', function() {
   var mailGenermhivd=$(this).attr("id");
    mailGenermhivd=mailGenermhivd.substr(6);
  // alert(mailGenermhivd);
   /*var nomact=$('#workflowh'+idw).attr("value");
   var typemiss=$('#workflowht'+idw).attr("value");
      $("#titleworkflowmodal").empty().append('<b>Mission: '+nomact+' (type de Mission: '+typemiss+')</b>');*///ou la methode html
           $.ajax({
               url: "<?php echo $urlapp; ?>/Mission/getMailGeneratorMAch/"+mailGenermhivd,
               type : 'GET',
              // data : 'idw=' + idw,
               success: function(data){
               
              //alert(data);
               //alert(JSON.stringify(data));
             $('#contenumodalworkflowMAA').empty().html(data);
              $('#myworkflowMAA').modal('show');
                  //alert(JSON.stringify(retour))   ;
                 // location.reload();
            }
             
           });
       $( "#pres_date2" ).datepicker({
           altField: "#datepicker",
           closeText: 'Fermer',
           prevText: 'Précédent',
           nextText: 'Suivant',
           currentText: 'Aujourd\'hui',
           monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
           monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
           dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
           dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
           dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
           weekHeader: 'Sem.',
           buttonImage: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABEAAAATCAYAAAB2pebxAAABGUlEQVQ4jc2UP06EQBjFfyCN3ZR2yxHwBGBCYUIhN1hqGrWj03KsiM3Y7p7AI8CeQI/ATbBgiE+gMlvsS8jM+97jy5s/mQCFszFQAQN1c2AJZzMgA3rqpgcYx5FQDAb4Ah6AFmdfNxp0QAp0OJvMUii2BDDUzS3w7s2KOcGd5+UsRDhbAo+AWfyU4GwnPAYG4XucTYOPt1PkG2SsYTbq2iT2X3ZFkVeeTChyA9wDN5uNi/x62TzaMD5t1DTdy7rsbPfnJNan0i24ejOcHUPOgLM0CSTuyY+pzAH2wFG46jugupw9mZczSORl/BZ4Fq56ArTzPYn5vUA6h/XNVX03DZe0J59Maxsk7iCeBPgWrroB4sA/LiX/R/8DOHhi5y8Apx4AAAAASUVORK5CYII=",
           firstDay: 1,
           dateFormat: "dd/mm/yy"
       });
  });
function valideom(idom,idsuperviseur,types){
document.getElementById('btnvalid').disabled=true
        //$("#gendocfromhtml").submit();
        var _token = $('input[name="_token"]').val();
        $.ajax({
                url:"{{ route('ordremissions.valide') }}",
                method:"POST",
                //'&_token='+_token
                data:'_token='+_token+'&idom='+idom+'&idsuperviseur='+idsuperviseur+'&types='+types,
                success:function(data){
                                     location.reload();  }
 });
                                     }
  </script>


@stop


