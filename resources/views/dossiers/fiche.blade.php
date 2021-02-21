@extends('layouts.mainlayout')
<?php
use App\Http\Controllers\EntreesController;use App\User ;
use App\Template_doc ; 
use App\Document ;
use App\Entree ;
use \App\Http\Controllers\UsersController;
use App\Http\Controllers\DossiersController;


 use App\Dossier ;
 use App\Attachement ;
 use \App\Http\Controllers\PrestationsController;
 use  \App\Http\Controllers\PrestatairesController;
 use  \App\Http\Controllers\DocsController;
?>
<link href="{{ asset('public/css/summernote.css') }}" rel="stylesheet" media="screen" />

<link rel="stylesheet" href="{{ asset('public/css/timelinestyle.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('public/css/timeline.css') }}" type="text/css">
<!--select css-->
<link href="{{ asset('public/js/select2/css/select2.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('public/js/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>
<script src="{{ asset('public/webphone/najdaapp/webphone/webphone_api.js') }}"></script>
@section('content')
    <?php
    $users=UsersController::ListeUsers();

    $CurrentUser = auth()->user();

    $iduser=$CurrentUser->id;

    ?>
    <?php $idagent=$dossier->user_id; $creator=UsersController::ChampById('name',$idagent).' '.UsersController::ChampById('lastname',$idagent);
    if($dossier->created==null){ $createdat=  date('d/m/Y H:i', strtotime($dossier->created_at ));}else{
        $createdat=  date('d/m/Y H:i', strtotime($dossier->created ));}

    $statut=$dossier->current_status;
    ;?>
<div class="row">

    <div class="col-md-3">

        <a  href="{{action('DossiersController@view',$dossier->id)}}" ><?php echo   $dossier->reference_medic .' - '. DossiersController::FullnameAbnDossierById($dossier->id);?></a></h4>
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

    </div>

     <div class="col-md-2">
        <?php
         // les agents ne voient pas l'aaffectation - à vérifier
         if (Gate::check('isAdmin') || Gate::check('isSupervisor') || ( $idagent==$iduser) ) { ?>
        <?php if ((isset($dossier->affecte)) && (($dossier->affecte>0))) { ?>

        <b>Affecté à:</b>
        <?php 
       if($dossier->affecte >0) {$agentname = User::where('id',$dossier->affecte)->first();}else{$agentname=null;}
        if ((Gate::check('isAdmin') || Gate::check('isSupervisor') || ( $idagent==$iduser)  ) &&  ($agentname!=null) )
            { echo '<a href="#" data-toggle="modal" data-target="#attrmodal"><input type="hidden" id="affecte" value="'.$dossier->affecte.'" >';}
            if( ($dossier->affecte >0)){ echo $agentname['name'].' '.$agentname['lastname'];}
        if(Gate::check('isAdmin') || Gate::check('isSupervisor')|| ( $idagent==$iduser) )
            { echo '</a>';}

        ?>
        <?php }
        else
        {
            if($statut!='Cloture') {

                if ((Gate::check('isAdmin') || Gate::check('isSupervisor') || ( $idagent==$iduser) ))
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

    <?php
    if($statut!='Cloture') {
?>
    <div class="col-md-5" style="text-align: right;padding-right: 35px">
        <div class="page-toolbar">

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
    </div>
    </div>

    <?php } ?>

</div>
    <section class="content form_layouts">

<br>
        <?php if( ($dossier->affecte>0) && ($dossier->accuse!=1) ) {?> <button  class="btn btn-md btn-info pull-left"   data-toggle="modal" data-target="#createAccuse"><b><i class="fas fa-envelope"></i> Accusé N Aff</b></button><?php } ?>
         <button  class="btn btn-md btn-info pull-right"   data-toggle="modal" data-target="#observations"><b><i class="fas fa-clipboard"></i> Observations </b></button>
        <?php  if($dossier->entree >0 ) {
        $entree  = Entree::find($dossier->entree);
        if (isset($entree)){ ?>
        <a href="{{action('DossiersController@update',$dossier->id)}}" style="margin-right:30px;margin-left:30px;" class="btn btn-md btn-info pull-right"    ><b><i class="fas fa-mail-bulk"></i> Email Géner</b></a>
        <?php   }} ?>
 <br>
                 <div class="form-group" style="margin-top:25px;">
                        {{ csrf_field() }}

                        <form id="updatedossform">
                            <input type="hidden" name="iddossupdate" id="iddossupdate" value="{{ $dossier->id }}">

                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Type de dossier</label>
                                        <select  onchange="changing(this);location.reload();"  id="type_dossier" name="type_dossier" class="form-control js-example-placeholder-single">
                                            <option <?php if ($dossier->type_dossier =='Medical'){echo 'selected="selected"';} ?> value="Medical">Medical</option>
                                            <option <?php if ($dossier->type_dossier =='Technique'){echo 'selected="selected"';} ?> value="Technique">Technique</option>
                                            <option <?php if ($dossier->type_dossier =='Transport'){echo 'selected="selected"';} ?> value="Transport">Transport</option>
                                            <option <?php if ($dossier->type_dossier =='Mixte'){echo 'selected="selected"';} ?> value="Mixte">Mixte</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Affecté à </label>
                                        <select id="type_affectation" name="type_affectation" class="form-control js-example-placeholder-single" >
                                            <option <?php if ($dossier->type_affectation =='Najda'){echo 'selected="selected"';} ?> value="Najda">Najda</option>
                                            <option <?php if ($dossier->type_affectation =='VAT'){echo 'selected="selected"';} ?> value="VAT">VAT</option>
                                            <option <?php if ($dossier->type_affectation =='MEDIC'){echo 'selected="selected"';} ?> value="MEDIC">MEDIC</option>
                                            <option <?php if ($dossier->type_affectation =='Transport MEDIC'){echo 'selected="selected"';} ?> value="Transport MEDIC">Transport MEDIC</option>
                                            <option <?php if ($dossier->type_affectation =='Transport VAT'){echo 'selected="selected"';} ?> value="Transport VAT">Transport VAT</option>
                                            <option <?php if ($dossier->type_affectation =='Medic International'){echo 'selected="selected"';} ?> value="Medic International">Medic International</option>
                                            <option <?php if ($dossier->type_affectation =='Najda TPA'){echo 'selected="selected"';} ?> value="Najda TPA">Najda TPA</option>
                                            <option <?php if ($dossier->type_affectation =='Transport Najda'){echo 'selected="selected"';} ?> value="Transport Najda">Transport Najda</option>
                                            <option <?php if ($dossier->type_affectation =='X-Press'){echo 'selected="selected"';} ?> value="X-Press">X-Press</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="complexite"> Complexité</label>
                                        <select onchange="changing(this)" class="form-control" name="complexite" id="complexite"  >
                                            <option <?php if ($dossier['complexite'] ==1){echo 'selected="selected"';}?> value="1">1</option>
                                            <option <?php if ($dossier['complexite'] ==2){echo 'selected="selected"';}?>value="2">2</option>
                                            <option <?php if ($dossier['complexite'] ==3){echo 'selected="selected"';}?>value="3">3</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row form">
                                <div class="col-md-12">
                                    <div class="tab-content">

                                        <div class="col-md-12">
                                            <div class="panel panel-success">

                                            <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a class="accordion-toggle" data-toggle="collapse">
                                                    Info du Client</a>
                                            </h4>
                                        </div>
                                        <div class="panel-collapse collapse in">
                                            <div class="panel-body">
                                                <div class="col-md-12">

                                                    <div class="row">

                                                    <div class="col-md-8">
                                                        <div class="form-group">
                                                            <label>Client </label>
                                                            <select onchange="changing(this);location.reload();" id="customer_id" name="customer_id" class="form-control js-example-placeholder-single"   value="{{ $dossier->customer_id }}" >
                                                                <option value="0">Sélectionner..... </option>

                                                                @foreach($clients as $cl  )
                                                                    <option
                                                                            @if($dossier->customer_id==$cl->id)selected="selected"@endif

                                                                    value="{{$cl->id}}">{{$cl->name}}</option>

                                                                @endforeach


                                                            </select>
                                                        </div>
                                                    </div>


                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Référence Client </label>
                                                            <input onchange="changing(this)" type="text" id="reference_customer" name="reference_customer" class="form-control"  value="{{ $dossier->reference_customer }}" >

                                                        </div>
                                                    </div>

                                                    </div>

                                                    <div class="row">
                                                            <div class="col-md-9">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Entité de facturation  </label>

                                                                    <div class="input-group-control">
                                                                        <select onchange="changing(this);" type="text" id="adresse_facturation" name="adresse_facturation" class="form-control <?php if ($dossier->adresse_facturation=='') {echo ' bg-danger';}?>"  >
                                                                            <option></option>
                                                                            <option  <?php if  (trim ($dossier->adresse_facturation)==trim($entite)){echo 'selected="selected"';} ?> value="<?php echo $entite;?>"><?php echo $entite .' - <small>'.$adresse.'</small>';?></option>
                                                                            <?php foreach ($liste as $l)
                                                                            {?>
                                                                            <option  <?php  if ($dossier->adresse_facturation==$l->nom ){echo 'selected="selected"';} ?> value="<?php echo $l->nom;?>" ><?php echo $l->nom ;?>  - <small>  <?php echo $l->champ;?> </small></option>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                        </div>

                                                        <div class="form-group row ">
                                                            <h4>Documents à signer</h4>
                                                            <div class="row">
                                                            <div class="col-md-9">
                                                                <div class="form-group">

                                                                    <label for="documents" class="" style="width:80px">   &nbsp;&nbsp;
                                                                        <div class="radio radio3" id="documents-oui"><span><input onclick="changing(this);" type="radio" name="documents" id="documents" value="1" <?php if ($dossier->documents ==1){echo 'checked';} ?>></span></div> Oui  </label>

                                                                    <label for="nondocuments" class="" style="width:80px">

                                                                    <div class="radio radio3" id="documents-non"><span class="checked"><input onclick="disabling('documents');" type="radio" name="documents" id="documentsnon" value="0"  <?php if ($dossier->documents ==0){echo 'checked';} ?> ></span></div> Non   </label>

                                                                </div>
                                                            </div>
                                                            </div>
                                                            <div class="row" <?php if( $dossier->documents==0){echo 'style="display:none;" ';} ?> id="documentsdiv">
                                                                <select class="form-control  col-lg-12 itemName " style="width:400px" name="docs"  multiple  id="docs">

                                                                    <option></option>
                                                                    <?php if ( count($relations1) > 0 ) {?>

                                                                    @foreach($relations1 as $rel  )
                                                                        @foreach($cldocs as $doc)
                                                                            <option  @if($rel->doc==$doc->doc)selected="selected"@endif    onclick="createdocdossier('spec<?php echo $doc->doc; ?>')"  value="<?php echo $doc->doc;?>"> <?php echo DocsController::ChampById('nom',$doc->doc);?></option>
                                                                        @endforeach
                                                                    @endforeach

                                                                    <?php
                                                                    } else { ?>
                                                                    @foreach($cldocs as $doc)
                                                                        <option    onclick="createdocdossier('spec<?php echo $doc->doc; ?>')"  value="<?php echo $doc->doc;?>"> <?php echo DocsController::ChampById('nom',$doc->doc);?></option>
                                                                    @endforeach

                                                                    <?php }  ?>

                                                                </select>

                                                            </div>
                                                        </div>


                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">

                                                                    <label for="franchise" class=""> Franchise &nbsp;&nbsp;
                                                                        <div class="radio radio1" id="uniform-franchise"><span><input onclick="changing(this);" type="radio" name="franchise" id="franchise" value="1" <?php if ($dossier->franchise ==1){echo 'checked';} ?>></span></div> Oui
                                                                    </label>

                                                                    <label for="nonfranchise" class="">

                                                                        <div class="radio radio1" id="uniform-nonfranchise"><span class="checked"><input onclick="disabling('franchise');hidingd();" type="radio" name="franchise" id="nonfranchise" value="0"  <?php if ($dossier->franchise ==0){echo 'checked';} ?> ></span></div> Non
                                                                    </label>

                                                                </div>
                                                            </div>

                                                            <div class="col-md-4"  id="montantfr"  <?php if(  $dossier->franchise ==0){ ?> style="display:none" <?php  } ?> >
                                                                <div class="form-group">
                                                                    <label class="control-label">Montant Franchise
                                                                    </label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)"  type="number" id="montant_franchise" name="montant_franchise" class="form-control" style="width: 100px;" placeholder="Montant"   value="{{ $dossier->montant_franchise }}" >
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2"  id="devisefr"  <?php if(  $dossier->franchise ==0){ ?> style="display:none" <?php  } ?> >
                                                                <div class="form-group">
                                                                    <label class="control-label">Devise
                                                                    </label>

                                                                    <div class="input-group-control">
                                                                        <select onchange="changing(this)"   id="devise_franchise" name="devise_franchise" class="form-control"    style="width:100px"  >
                                                                            <option <?php if(  $dossier->devise_franchise =='TND'){ echo'selected="selected"';}?> value="TND">TND</option>
                                                                            <option <?php if(  $dossier->devise_franchise =='EUR'){ echo'selected="selected"';}?>  value="EUR">EUR</option>
                                                                            <option <?php if(  $dossier->devise_franchise =='USD'){ echo'selected="selected"';}?>  value="USD">USD</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>

                                                    <div class="row">

                                                        <div class="col-md-4">
                                                            <div class="form-group">

                                                                <label for="is_plafond" class=""> Plafond &nbsp;&nbsp;
                                                                    <div class="radio radio1" id="uniform-franchise"><span><input onclick="changing(this);" type="radio" name="is_plafond" id="is_plafond" value="1" <?php if ($dossier->is_plafond ==1){echo 'checked';} ?>></span></div> Oui
                                                                </label>

                                                                <label for="nonplafond" class="">

                                                                    <div class="radio radio1" id="uniform-nonplafond"><span class="checked"><input onclick="disabling('is_plafond');hidingd2();" type="radio" name="is_plafond" id="nonplafond" value="0"  <?php if ($dossier->is_plafond ==0){echo 'checked';} ?> ></span></div> Non
                                                                </label>

                                                            </div>
                                                        </div>

                                                    <div class="col-md-4" id="plafondmt" <?php if(  $dossier->is_plafond ==0){ ?> style="display:none" <?php  } ?> >
                                                        <div class="form-group">
                                                            <label class="control-label">Montant Plafond
                                                            </label>

                                                            <div class="input-group-control">
                                                                <input onchange="changing(this)"  type="number" id="plafond" name="plafond" class="form-control" style="width: 100px;" placeholder="Plafond"   value="{{ $dossier->plafond }}" >
                                                            </div>
                                                        </div>
                                                    </div>

                                                        <div class="col-md-4"  id="plafonddv"  <?php if(  $dossier->plafond ==0){ ?> style="display:none" <?php  } ?> >
                                                            <div class="form-group">
                                                                <label class="control-label">Devise
                                                                </label>

                                                                <div class="input-group-control">
                                                                    <select onchange="changing(this)"   id="devise_plafond" name="devise_plafond" class="form-control"  style="width:100px"    >
                                                                        <option <?php if(  $dossier->devise_plafond =='TND'){ echo'selected="selected"';}?> value="TND">TND</option>
                                                                        <option <?php if(  $dossier->devise_plafond =='EUR'){ echo'selected="selected"';}?> value="EUR">EUR</option>
                                                                        <option <?php if(  $dossier->devise_plafond =='USD'){ echo'selected="selected"';}?> value="USD">USD</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                </div>


                                            </div>
                                                </div>
                                            </div>
                                          </div>
                                        </div>
                                            <div class="col-md-12">
                                                <div class="panel panel-success">

                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <a class="accordion-toggle" data-toggle="collapse">
                                                                Info Assuré</a>
                                                        </h4>
                                                    </div>
                                                    <div class="panel-collapse collapse in">
                                                        <div class="panel-body">
                                                            <div class="col-md-12">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Nom de l'assuré </label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="subscriber_name" name="subscriber_name" class="form-control" value="{{ $dossier->subscriber_name }}"  >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Prénom de l'assuré *</label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="subscriber_lastname" name="subscriber_lastname" class="form-control"  value="{{ $dossier->subscriber_lastname }}" >
                                                                            </div>
                                                                        </div>
                                                                    </div>



                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="ben" class="control-label">bénéficiaire différent</label><br>

                                                                                 <label for="annule" class="">
                                                                                    <div class="radio" id="uniform-actif">
                                                                                        <span class="checked">
                                                                               <input    type="checkbox"  id="benefdiff"   value="1"  <?php if ($dossier->benefdiff ==1){echo 'checked';} ?>  onclick="changing2(this);showBen();" >
                                                                                        </span>Oui</div>
                                                                                </label>
                                                                         </div>
                                                                    </div>

                                                                </div>

                                                                <div class="row" id="bens" <?php if ($dossier->benefdiff ==0) { ?> style="display:none" <?php }?> >

                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Nom du Bénéficaire </label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="beneficiaire" name="beneficiaire" class="form-control"   value="{{ $dossier->beneficiaire }}" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Prénom du Bénéficaire</label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="prenom_benef" name="prenom_benef" class="form-control"   value="{{ $dossier->prenom_benef }}" >
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Parenté </label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="parente" name="parente" class="form-control"  value="{{ $dossier->parente }}"  >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-1" style="padding-top:30px">
                                                                        <span title="Afficher le bénéficiaire 2 " style="width:20px" class=" btn-md" id="btn01"><i class="fa fa-plus"></i> <i class="fa fa-minus"></i></span>
                                                                    </div>

                                                                    </div>
                                                                <div class="row" id="ben2" <?php if ($dossier->beneficiaire2 =='') { ?> style="display:none" <?php }?>  >

                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Nom du Bénéficaire 2</label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="beneficiaire2" name="beneficiaire" class="form-control"   value="{{ $dossier->beneficiaire2 }}" >
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Prénom du Bénéficaire 2</label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="prenom_benef2" name="prenom_benef" class="form-control"   value="{{ $dossier->prenom_benef2 }}" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Parenté </label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="parente2" name="parente" class="form-control"  value="{{ $dossier->parente2 }}"  >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-1" style="padding-top:30px">
                                                                        <span title="Afficher le bénéficiaire 3" style="width:20px" class=" btn-md" id="btn02"><i class="fa fa-plus"></i> <i class="fa fa-minus"></i></span>
                                                                    </div>
                                                                </div>


                                                                <div class="row" id="ben3"  <?php if ($dossier->beneficiaire3 =='') { ?> style="display:none" <?php }?>  >
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Nom du Bénéficaire 3 </label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="beneficiaire3" name="beneficiaire" class="form-control"   value="{{ $dossier->beneficiaire3 }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Prénom du Bénéficaire 3</label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="prenom_benef3" name="prenom_benef" class="form-control"   value="{{ $dossier->prenom_benef3 }}" >
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Parenté </label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="parente3" name="parente" class="form-control"  value="{{ $dossier->parente3 }}"  >
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                </div>

                                                                <?php if( trim($dossier->type_affectation)=='Najda TPA') {?>
                                                                <div class="row">

                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="tpa" class="control-label"> ID Assuré  </label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="number" id="ID_assure"    class="form-control" value="{{ $dossier->ID_assure }}" >
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-8">
                                                                        <div class="form-group">
                                                                            <label for="tpa" class="control-label"> Spécialité Médicale </label>

                                                                            <?php  $specsTPA = DB::table('specialites_typeprestations')
                                                                                ->where('type_prestation',15)  // medcecin traitant
                                                                                ->get();
                                                                            ?>
                                                                    <div class="input-group-control">
                                                                                <select id="specialite_TPA"  onchange="changing(this)"   class="form-control" value="{{ $dossier->specialite_TPA }}" >
                                                                                    <option value=""></option>
                                                                                    <?php  foreach($specsTPA as $spec)
                                                                                    {
                                                                                        $nomSpec=\App\Http\Controllers\SpecialitesController::NomSpecialiteById($spec->specialite);
                                                                                        if($dossier->specialite_TPA==$nomSpec){$selected='selected="selected"';}else{$selected='';}
                                                                                        echo '<option  '.$selected.'   value="'.$nomSpec.'" >'. $nomSpec  .'</option>';
                                                                                    }
                                                                                    ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="row">
                                                                <h3>Tables de garanties</h3>
                                                                <?php  
                                                                $garanties=\App\Garantie::get();
                                                                $garanties_assure= \DB::table('garanties_assure')->where('id_assure',$dossier->ID_assure)->pluck('garantie');
                                                                  $garanties_assure=$garanties_assure->toArray();
                                                                ?>
                                     <div class="row">
                                    <select class="form-control  col-lg-12   " style="width:400px" name="garanties"  multiple  id="garanties">


                                        <option></option>
                              
                                        <?php $c=0; foreach($garanties as $gr){ $c++;
                                    if (in_array($gr->id, $garanties_assure)) {$selected="selected='selected'";}else{$selected="";}
                                        ?>
                                      <option  id="gr-<?php echo $c; ?>"   {{$selected}}  value="<?php echo $gr->id;?>"    value="<?php echo $gr->id;?>">  <?php echo $gr->nom;?></option>
                                         <?php }?>

 
                                    </select>

                                </div>  
                                     <div class="row">
                                     <h3> Rubriques</h3>
                                     <?php 
                                     $annee=date('Y');
                                     $rubriques_assure= \DB::table('rubriques_assure')->where('id_assure',$dossier->ID_assure)->where('annee',$annee)->get();
                                        if(count($rubriques_assure)>0){ ?>
                                        <table class="table table-striped" id="tabgr">
                                        <tr>
                                        <th>Garantie</th><th>Rubrique</th><th>Plafond</th><th>Montant</th>
                                        </tr>
                                    <?php   
                                    foreach($rubriques_assure as $rb)
                                         { 
                                         $rubriqueinit = \App\RubriqueInitial::where('id',$rb->rubriqueinitial)->first();
$rubrique = \App\Rubrique::where('id',$rb->rubrique)->first();
                                         $garantie = \App\Garantie::where('id',$rubrique->garantie)->first();
                                         
                                    echo '<tr><td> '.$garantie->nom.'</td><td>'.$rubriqueinit->nom.'</td><td> '.$rubrique->montant.' '.$rubrique->devise.'</td><td> '.$rb->mrestant.'</td></tr>';
                                         }
                                        } // count
                                         ?>
                                        </table>
                                    <style>
                                    #tabgr td{min-width:150px;padding:5px 5px 5px 5px;}
                                    </style>
                                  </div>    

                                                                    
                                                                </div>

                                                                    
                                                                <?php }?>

                                                                <div class="row" style="margin-top:50px">
                                                                    <div class="col-md-8">
                                                                        <h4><i class="fa fa-lg fa-user"></i> Numéros Tels</h4>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <span style="float:right" id="addtel" class="btn btn-md btn-default" data-toggle="modal" data-target="#adding6" ><b><i class="fa fa-user"></i> Ajouter un numéro de téléphone</b></span>
                                                                    </div>

                                                                </div>

                                                                <table class="table table-striped"  style="width:100%;margin-top:25px;font-size:16px;">
                                                                    <thead>
                                                                    <tr class="headtable">
                                                                        <th style="width:20%">Nom et Prénom</th>
                                                                        <th style="width:20%">Qualité</th>
                                                                        <th style="width:20%">Téléphone</th>
                                                                        <th style="width:14%">Type</th>
                                                                        <th style="width:22%">Remarque</th>
                                                                        <th style="width:5%">Supp</th>
                                                                    </tr>

                                                                    </thead>
                                                                    <tbody>
                                                                    @foreach($phones as $phone)
                                                                        <tr>
                                                                            <td style="width:20%;"><input placeholder="Nom"  id='tel-nom-<?php echo $phone->id;?>' type="text" pattern="[0-9]" style="width:100%" value="<?php echo $phone->nom; ?>" onchange="changingAddress('<?php echo $phone->id; ?>','nom',this)" /><br><input placeholder="Prenom"   id='tel-prenom-<?php echo $phone->id;?>' type="text" pattern="[0-9]" style="width:100%" value="<?php echo $phone->prenom; ?>" onchange="changingAddress('<?php echo $phone->id; ?>','prenom',this)" /> </td>
                                                                            <td style="width:20%;"><input   id='tel-fonc-<?php echo $phone->id;?>' type="text"  style="width:100%" value="<?php echo $phone->fonction; ?>" onchange="changingAddress('<?php echo $phone->id; ?>','fonction',this)" /></td>
                                                                            <td style="width:20%;"><input   id='tel-tel-<?php echo $phone->id;?>' type="text"  style="width:100%" value="<?php echo $phone->tel; ?>" onchange="changingAddress('<?php echo $phone->id; ?>','tel',this)" /></td>
                                                                            <td style="width:14%;"><input   id='tel-tt-<?php echo $phone->id;?>' type="text"  style="width:100%" value="<?php echo $phone->typetel; ?>" onchange="changingAddress('<?php echo $phone->id; ?>','typetel',this)" /><?php  '<br>'; if($phone->typetel=='Mobile') {?> <a onclick="setTel(this);" class="<?php echo $phone->tel;?>" style="margin-left:5px;cursor:pointer" data-toggle="modal"  data-target="#sendsms" ><i class="fas fa-sms"></i>Envoyer un SMS </a><?php } ?> </td>
                                                                            <td style="width:22%;"><textarea   id='tel-rem-<?php echo $phone->id;?>'    style="width:100%" onchange="changingAddress('<?php echo $phone->id; ?>','remarque',this)" ><?php echo $phone->remarque; ?></textarea></td>
                                                                            <td style="width:4%;">
                                                                                <a onclick="return confirm('Êtes-vous sûrs ?')"  href="{{action('ClientsController@deleteaddress', $phone->id) }}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer" >
                                                                                    <span class="fa fa-fw fa-trash-alt"></span>
                                                                                </a>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach

                                                                    </tbody>
                                                                </table>

                                                                <div class="row" style="margin-top:50px">
                                                                    <div class="col-md-8">
                                                                        <h4><i class="fa fa-lg fa-user"></i> Adresses Emails </h4>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <span style="float:right" id="addemail" class="btn btn-md btn-default"   data-toggle="modal" data-target="#adding7"><b><i class="fa fa-user"></i> Ajouter une adresse email</b></span>
                                                                    </div>

                                                                </div>

                                                                <table class="table table-striped"  style="width:100%;margin-top:25px;margin-bottom:25px;font-size:16px;">
                                                                    <thead>
                                                                    <tr class="headtable">
                                                                        <th style="width:30%">Nom et Prénom</th>
                                                                        <th style="width:26%">Qualité</th>
                                                                        <th style="width:30%">Email</th>
                                                                        <th style="width:10%">Remarque</th>
                                                                        <th style="width:4%">Supp</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    @foreach($emailads as $emailad)
                                                                        <tr>
                                                                            <td style="width:30%;"><input placeholder="Nom"   id='em-nom-<?php echo $emailad->id;?>' type="text" pattern="[0-9]" style="width:100%" value="<?php echo $emailad->nom; ?>" onchange="changingAddress('<?php echo $emailad->id; ?>','nom',this)" /><br><input  placeholder="Prenom"  id='em-prenom-<?php echo $emailad->id;?>' type="text" pattern="[0-9]" style="width:100%" value="<?php echo $emailad->prenom; ?>" onchange="changingAddress('<?php echo $emailad->id; ?>','prenom',this)" /> </td>
                                                                            <td style="width:26%;"><input   id='em-fonc-<?php echo $emailad->id;?>' type="text"  style="width:100%" value="<?php echo $emailad->fonction; ?>" onchange="changingAddress('<?php echo $emailad->id; ?>','fonction',this)" /></td>
                                                                            <td style="width:30%;"><input   id='em-em-<?php echo $emailad->id;?>' type="text"  style="width:100%" value="<?php echo $emailad->mail; ?>" onchange="changingAddress('<?php echo $emailad->id; ?>','mail',this)" /></td>
                                                                             <td style="width:10%;"><textarea   id='em-rem-<?php echo $emailad->id;?>'    style="width:100%" onchange="changingAddress('<?php echo $emailad->id; ?>','remarque',this)" ><?php echo $emailad->remarque; ?></textarea></td>
                                                                            <td style="width:4%;">
                                                                                <a onclick="return confirm('Êtes-vous sûrs ?')"  href="{{action('ClientsController@deleteaddress', $emailad->id) }}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer" >
                                                                                    <span class="fa fa-fw fa-trash-alt"></span>
                                                                                </a>
                                                                            </td>
                                                                         </tr>
                                                                    @endforeach

                                                                    </tbody>
                                                                </table>


                                                                <div class="row">

                                                                    <?php if ($dossier->subscriber_phone_cell !='') { ?>

                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Tel mobile 1</label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="subscriber_phone_cell" name="subscriber_phone_cell" class="form-control"  value="{{ $dossier->subscriber_phone_cell }}"  >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <?php  } ?>
                                                                    <?php if ($dossier->subscriber_phone_domicile !='') { ?>

                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Tel mobile 2</label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="subscriber_phone_domicile" name="subscriber_phone_domicile" class="form-control"   value="{{ $dossier->subscriber_phone_domicile }}" >
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                        <?php }?>
                                                                        <?php if ($dossier->subscriber_phone_home !='') { ?>
                                                                        <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Tel Autre </label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="subscriber_phone_home" name="subscriber_phone_home" class="form-control"   value="{{ $dossier->subscriber_phone_home }}" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                        <?php }?>
                                                                        <?php if ($dossier->subscriber_phone_4 !='') { ?>

                                                                        <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Tel autre 2</label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="subscriber_phone_4" name="subscriber_phone_4" class="form-control"   value="{{ $dossier->subscriber_phone_4 }}" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                        <?php }?>

                                                                </div>


                                                                    <?php if ($dossier->to_phone !='') { ?>

                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Tel </label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)"  type="text" id="to_phone" name="to_phone" class="form-control"   value="{{ $dossier->to_phone }}" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <?php }?>

                                                                </div>


                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Date arrivée </label>
                                                                            <input onchange="changing(this)"  data-format="dd-MM-yyyy hh:mm:ss" placeholder="jj-mm-aaaa" class="form-control datepicker-default form-control" name="initial_arrival_date" id="initial_arrival_date" type="text"   value="{{ $dossier->initial_arrival_date }}" >
                                                                        </div>

                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Départ prévu</label>
                                                                            <input onchange="changing(this)"  data-format="dd-MM-yyyy hh:mm:ss" placeholder="jj-mm-aaaa" class="form-control datepicker-default form-control" name="departure" id="departure" type="text"   value="{{ $dossier->departure }}" >
                                                                        </div>

                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Destination </label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="destination" name="destination" class="form-control"   value="{{ $dossier->destination }}" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            <div class="row">
                                                                <div class="form-group col-md-10">
                                                                    <label for="inputError" class="control-label">Adresse étranger</label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)" type="text" id="adresse_etranger" name="adresse_etranger" class="form-control"   value="{{ $dossier->adresse_etranger }}" >
                                                                    </div>
                                                                </div>

                                                            </div>

                                                                <div class="row" id="adresse3"  <?php if ($dossier->subscriber_local_address3 =='') {echo 'style="display:none;"';}  ?> >
                                                                    <div class="form-group col-md-10">
                                                                        <label for="inputError" class="control-label"><label id="derniere3">Dernière</label> Adresse en Tunisie  </label>

                                                                        <div class="input-group-control">
                                                                            <input onchange="changing(this)"  type="text" id="subscriber_local_address3" name="subscriber_local_address3" class="form-control"   value="{{ $dossier->subscriber_local_address3 }}" >
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-1" style="padding-top:30px">
                                                                        <span title="cacher l'adresse" style="margin-top:20px;width:20px" class=" btn-md" id="btn04moins"><i class="fa  fa-minus"></i> </span>

                                                                    </div>
                                                                </div>
                                                                <div class="row" id="adresse2"   <?php if ($dossier->subscriber_local_address2 =='') {echo 'style="display:none;"';}  ?> >
                                                                    <div class="form-group col-md-10">
                                                                        <label for="inputError" class="control-label"><label id="derniere2">Dernière</label> Adresse en Tunisie  </label>

                                                                        <div class="input-group-control">
                                                                            <input onchange="changing(this)"  type="text" id="subscriber_local_address2" name="subscriber_local_address2" class="form-control"   value="{{ $dossier->subscriber_local_address2 }}" >
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-1" style="padding-top:30px">
                                                                        <span title="Afficher une autre adresse" style="margin-top:20px;width:20px" class=" btn-md" id="btn04plus"><i class="fa fa-plus"></i> </span>
                                                                        <span title="cacher l'adresse" style="margin-top:20px;width:20px" class=" btn-md" id="btn03moins"><i class="fa   fa-minus"></i> </span>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="form-group col-md-10">
                                                                        <label for="inputError" class="control-label"><label id="derniere1">Dernière</label> Adresse en Tunisie </label>

                                                                        <div class="input-group-control">
                                                                            <input onchange="changing(this)"  type="text" id="subscriber_local_address" name="subscriber_local_address" class="form-control"   value="{{ $dossier->subscriber_local_address }}">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-1" style="padding-top:30px">
                                                                        <span title="Afficher une autre adresse" style="margin-top:20px;width:20px" class=" btn-md" id="btn03plus"><i class="fa   fa-plus"></i> </span>
                                                                     </div>
                                                                </div>



                                                                <div class="row">

                                                                    <div class="col-md-12">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Ville</label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)"  type="text" autocomplete="off" id="ville" name="ville" class="form-control"   value="{{ $dossier->ville }}" >
                                                                            </div>
                                                                            <script>
                                                                                var placesAutocomplete = places({
                                                                                    appId: 'plCFMZRCP0KR',
                                                                                    apiKey: 'aafa6174d8fa956cd4789056c04735e1',
                                                                                    container: document.querySelector('#ville')
                                                                                });
                                                                            </script>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">



                                                                    <div class="col-md-7">
                                                                        <!--<div class="form-group">
                                                                            <label for="inputError" class="control-label">Hôtel</label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)"  type="text" id="hotel" name="hotel" class="form-control"   value="{{ $dossier->hotel }}" >
                                                                            </div>
                                                                        </div>-->

                                                                            <div class="form-group">
                                                                                <label for="inputError" class="control-label">Hôtel </label>

                                                                                <div class="input-group-control">
                                                                                    <select onchange="changing(this);ajout_prest(this);"   id="hotel" name="hotel" class="form-control"   value="{{ $dossier->hotel }}">

                                                                                        <option></option>
                                                                                        <?php

                                                                                        foreach($hotels as $ht)
                                                                                        { if ($dossier->hotel == PrestatairesController::ChampById('name',$ht->prestataire_id)){ $selected='selected="selected"'; }else{ $selected=''; }
                                                                                            if( PrestatairesController::ChampById('name',$ht->prestataire_id)!=''){ echo '<option title="'.$ht->prestataire_id.'" '.$selected.' value="'.   PrestatairesController::ChampById('name',$ht->prestataire_id).'">'.   PrestatairesController::ChampById('name',$ht->prestataire_id).'</option>';}
                                                                                        }
                                                                                        ?>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                    </div>


                                                                    <div class="col-md-3">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Chambre</label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)"  type="text" id="subscriber_local_address_ch" name="subscriber_local_address_ch" class="form-control"   value="{{ $dossier->subscriber_local_address_ch }}" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <label for="inputError" class="control-label">Autre</label>

                                                                        <a style=""  href="{{route('prestataires.create',['id'=>$dossier->id])}}" class="btn btn-default btn-sm" role="button">+ Ajouter</a>

                                                                    </div>
                                                                </div>
                                                                    <div class="row">



                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <?php if($dossier->subscriber_mail1 !=''){ ?>

                                                                            <label for="inputError" class="control-label">Mail</label>
                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="email" id="subscriber_mail1" name="subscriber_mail1" class="form-control" placeholder="mail 1"   value="{{ $dossier->subscriber_mail1 }}" >
                                                                            </div><br> <?php }?>
                                                                            <?php if($dossier->subscriber_mail2 !=''){ ?>   <input onchange="changing(this)"  type="text" name="email1" class="form-control" id="subscriber_mail2" placeholder="mail 2"   value="{{ $dossier->subscriber_mail2 }}" ><br><?php }?>
                                                                            <?php if($dossier->subscriber_mail3 !=''){ ?>   <input onchange="changing(this)"  type="text" name="subscriber_mail3" class="form-control" id="subscriber_mail3" placeholder="mail 3"   value="{{ $dossier->subscriber_mail3 }}" ><br> <?php }?>
                                                                        </div>
                                                                    </div>
<!--
                                                                    <div class="row form-group">

                                                                            <div style="">
                                                                                <span style="float:right;margin-top:10px;margin-bottom: 15px;margin-right: 20px" id="addemail" class="btn btn-md btn-success"   data-toggle="modal" data-target="#createemail"><b><i class="fas fa-plus"></i> Ajouter une adresse email</b></span>

                                                                            </div>
                                                                            <table class="table table-striped" id="mytable2" style="width:100%;margin-top:15px;font-size:16px;">
                                                                                <thead>
                                                                                <tr id="headtable">
                                                                                    <th style="">Email</th>
                                                                                    <th style="">Nom</th>
                                                                                    <th style="">qualité</th>
                                                                                    <th style="">Tel</th>
                                                                                </tr>

                                                                                </thead>
                                                                                <tbody>


                                                                                </tbody>
                                                                            </table>

                                                                        </div>
-->
                                                                </div>




                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                               <!-- <div class="panel panel-success">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <a class="accordion-toggle" data-toggle="collapse">
                                                                Info Dossier</a>
                                                        </h4>
                                                    </div>
                                                </div>-->
                                            </div>
                                            <!--                                    </div>-->
                                           <!-- <div class="panel-collapse collapse in">
                                                <div class="panel-body">
                                                    <div class="col-md-12">

                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Statut</label>

                                                                    <div class="input-group-control">
                                                                        <input type="text" value="En cours" id="current_status" name="current_status" class="form-control" disabled=""  value="{{ $dossier->current_status }}" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Ouvert le </label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)" type="text" value="" id="opened_by_date" name="" class="form-control" disabled=""  value="{{ $dossier->opened_by_date }}" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">

                                                        </div>
                                                        <div class="row">

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="complexite"> Montant total des prestations</label>
                                                                    <input onchange="changing(this)" type="text" readonly="readonly" class="form-control" name="montant_tot" id="montant_tot"   value="{{ $dossier->montant_tot }}" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>-->
                                        </div>
                                        <!--                                    </div>-->

                                    <div class="col-md-12">

                                        <div class="panel panel-success" id="medical" style=" <?php if (trim($dossier->type_dossier) =='Technique'  ){echo 'display:none';}?>;">
                                            <div class="panel-heading">
                                                <h4 class="panel-title">
                                                    <a class="accordion-toggle" data-toggle="collapse">
                                                        Info Médical</a>
                                                </h4>
                                            </div>
                                            <div class="panel-collapse collapse in">
                                                <div class="panel-body">
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="is_hospitalized" class=""> Hospitalisé
                                                                        <div style="margin-right:20px" class="radio radio2" id="uniform-is_hospitalized"><span><input onclick="changing(this)"  type="radio" name="is_hospitalized" id="is_hospitalized" value="1" <?php if ($dossier->is_hospitalized ==1){echo 'checked';} ?> ></span>Outpatient</div>
                                                                    </label> <label for="nonis_hospitalized" class=""> <div class="radio radio2" id="uniform-nonis_hospitalized"><span class=""><input onclick="disabling('is_hospitalized')" type="radio" name="is_hospitalized" id="nonis_hospitalized" value="0"  <?php if ($dossier->is_hospitalized ==0){echo 'checked';} ?>  ></span> Inpatient </div>
                                                                    </label>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div <?php if ($dossier->is_hospitalized ==1){echo 'style="display:none"';} ?>id="hospital">

                                                        <div class="row">
                                                            <div class="col-md-8">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Hôspitalisé à </label>

                                                                    <div class="input-group-control">
                                                                        <select onchange="changing(this);ajout_prest(this);"  type="text" id="hospital_address" name="hospital_address" class="form-control"   value="{{ $dossier->hospital_address }}">

                                                                        <option></option>
                                                                        <?php

                                                                        foreach($hopitaux as $hp)
                                                                        { 
if (strcasecmp(trim($dossier->hospital_address), trim(PrestatairesController::ChampById('name',$hp->prestataire_id))) == 0) { $selected='selected="selected"'; }else{ $selected=''; }
                                                                          if( PrestatairesController::ChampById('name',$hp->prestataire_id)!=''){ echo '<option title="'.$hp->prestataire_id.'" '.$selected.' value="'.   PrestatairesController::ChampById('name',$hp->prestataire_id).'">'.   PrestatairesController::ChampById('name',$hp->prestataire_id).'</option>';}
                                                                      }
                                                                      ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Chambre  </label>
                                                                    <div class="input-group-control">
                                                                        <input   type="text" id="chambre_hoptial" name="chambre_hoptial" class="form-control"  onchange="changing(this);"  value="{{ $dossier->chambre_hoptial }}" >
                                                                    </div>
                                                                </div>
                                                            </div>


                                                        </div>

                                                            <div class="row">
                                                                <div class="col-md-8">
                                                                    <div class="form-group">
                                                                        <label for="inputError" class="control-label">Autre adresse  </label>
                                                                        <div class="input-group-control">
                                                                            <input   onchange="changing(this);" type="text" id="autre_hospital_address" name="autre_hospital_address" class="form-control"   value="{{ $dossier->autre_hospital_address }}" >
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        <div class="row">

                                                            <div class="col-md-5">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Médecin Traitant </label>


                                                                    <div class="input-group-control">
                                                                        <select onchange="changing(this);ajout_prest(this);"  id="medecin_traitant" name="medecin_traitant" class="form-control"   value="{{ $dossier->medecin_traitant }}">

                                                                            <option></option>
                                                                            <?php

                                                                            foreach($traitants as $tr)
                                                                            { if (trim($dossier->medecin_traitant) == trim(PrestatairesController::ChampById('name',$tr->prestataire_id))){ $selected='selected="selected"'; }else{ $selected=''; } $Tel=\App\Adresse::where('parent',$tr->prestataire_id)->where('nature','telinterv')->first(); if(isset($Tel)){$tel=$Tel->champ;}else{$tel='';}
                                                                                if (PrestatairesController::ChampById('name',$tr->prestataire_id)!='') {echo '<option title="'.$tr->prestataire_id.'" '.$selected.' value="'. PrestatairesController::ChampById('name',$tr->prestataire_id).'">'. PrestatairesController::ChampById('prenom',$tr->prestataire_id).' '.PrestatairesController::ChampById('name',$tr->prestataire_id).' - Fixe: '. PrestatairesController::ChampById('phone_home',$tr->prestataire_id) .' - Tel: './*PrestatairesController::ChampById('phone_cell',$tr->prestataire_id).*/' ' . $tel .'</option>';}
                                                                            }

                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Autre Médecin Traitant  </label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)" type="text" id="medecin_traitant2" name="medecin_traitant2" class="form-control" value="{{ $dossier->medecin_traitant2 }}" >
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Tel Autre M T</label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)" type="text" id="hospital_phone2" name="hospital_phone2" class="form-control"   value="{{ $dossier->hospital_phone2 }}" >
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>


                                                        <div class="row"   id="adresse03" <?php if ($dossier->empalcement_medic3 =='') {echo 'style="display:none;"';}  ?> >
                                                            <div class="col-md-5">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label"><label id="derniere03">Dernière</label> structure d’hospitalisation  </label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)" type="text" id="empalcement_medic3" name="empalcement_medic3" class="form-control" value="{{ $dossier->empalcement_medic3 }}" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">De (Date)</label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)" type="text" id="date_debut_medic3"  class="form-control datepicker-default" data-format="dd-MM-yyyy hh:mm:ss"  value="{{ $dossier->date_debut_medic3 }}" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">A (Date)</label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)" type="text" id="date_fin_medic3"   class="form-control datepicker-default"   value="{{ $dossier->date_fin_medic3 }}" >
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-1" style="padding-top:30px">
                                                                <span title="cacher l'adresse" style="margin-top:20px;width:20px" class=" btn-md" id="btn004moins"><i class="fa  fa-minus"></i> </span>

                                                            </div>
                                                        </div>
                                                        <div class="row" id="adresse02" <?php if ($dossier->empalcement_medic2 =='') {echo 'style="display:none;"';}  ?> >
                                                            <div class="col-md-5">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label"><label id="derniere02">Dernière</label> structure d’hospitalisation  </label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)" type="text" id="empalcement_medic2"   class="form-control" value="{{ $dossier->empalcement_medic2 }}" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">De (Date)</label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)" type="text" id="date_debut_medic2"  class="form-control datepicker-default"   value="{{ $dossier->date_debut_medic2 }}" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">A (Date)</label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)" type="text" id="date_fin_medic2"  class="form-control datepicker-default"   value="{{ $dossier->date_fin_medic2 }}" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1" style="padding-top:30px">
                                                                <span title="Afficher une autre adresse" style="margin-top:20px;width:20px" class=" btn-md" id="btn004plus"><i class="fa fa-plus"></i> </span>
                                                                <span title="cacher l'adresse" style="margin-top:20px;width:20px" class=" btn-md" id="btn003moins"><i class="fa   fa-minus"></i> </span>
                                                            </div>
                                                        </div>

                                                        <div class="row" id="adresse01">
                                                            <div class="col-md-5">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label"><label id="derniere01">Dernière </label> structure d’hospitalisation  </label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)" type="text" id="empalcement_medic"    class="form-control" value="{{ $dossier->empalcement_medic }}" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">De (Date)</label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)" type="text" id="date_debut_medic" class="form-control datepicker-default"   value="{{ $dossier->date_debut_medic }}" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">A (Date)</label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)" type="text" id="date_fin_medic"   class="form-control datepicker-default"   value="{{ $dossier->date_fin_medic }}" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1" style="padding-top:30px">
                                                                <div class="col-md-1" style="padding-top:30px">
                                                                    <span title="Afficher une autre adresse" style="margin-top:20px;width:20px" class=" btn-md" id="btn003plus"><i class="fa   fa-plus"></i> </span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        </div>



                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel panel-success " id="technique" style=" <?php if ($dossier->type_dossier =='Medical' || $dossier->type_dossier =='Transport' ){echo 'display:none';}?>;">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a class="accordion-toggle" data-toggle="collapse">
                                                    Info Technique</a>
                                            </h4>
                                        </div>
                                        <div class="panel-collapse collapse in">
                                            <div class="panel-body">
                                                <div class="col-md-12">

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label"> Marque du véhicule</label>

                                                                <select onchange="changing(this)" type="text" id="vehicule_marque" name="vehicule_marque" class="form-control"   value="{{ $dossier->vehicule_marque }}"     >
                                                                    <option>Choisir la marque</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="ABARTH"){echo 'selected="selected"';}?> value="Abarth">Abarth</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="AC"){echo 'selected="selected"';}?> value="Ac">Ac</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="ACURA"){echo 'selected="selected"';}?> value="Acura">Acura</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="AIXAM"){echo 'selected="selected"';}?> value="Aixam">Aixam</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="ALFA ROMEO"){echo 'selected="selected"';}?> value="Alfa Romeo">Alfa Romeo</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="ALKE"){echo 'selected="selected"';}?> value="Alke">Alke</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="ALLARD"){echo 'selected="selected"';}?> value="Allard">Allard</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="ALPINA"){echo 'selected="selected"';}?> value="Alpina">Alpina</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="ALPINE"){echo 'selected="selected"';}?> value="Alpine">Alpine</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="ALVIS"){echo 'selected="selected"';}?> value="Alvis">Alvis</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="AMC"){echo 'selected="selected"';}?> value="AMC">AMC</option>AMC
<option <?php if(strtoupper($dossier->vehicule_marque)=="AMILCAR"){echo 'selected="selected"';}?> value="Amilcar">Amilcar</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="ARIEL"){echo 'selected="selected"';}?> value="Ariel">Ariel</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="ARO"){echo 'selected="selected"';}?> value="ARO">ARO</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="ASHOK LEYLAND"){echo 'selected="selected"';}?> value="Ashok Leyland">Ashok Leyland</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="ASTON MARTIN"){echo 'selected="selected"';}?> value="Aston Martin">Aston Martin</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="AUBURN"){echo 'selected="selected"';}?> value="Auburn">Auburn</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="AUDI"){echo 'selected="selected"';}?> value="Audi">Audi</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="AUSTIN"){echo 'selected="selected"';}?> value="Austin">Austin</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="AUSTIN HEALEY"){echo 'selected="selected"';}?> value="Austin Healey">Austin Healey</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="AUTO UNION"){echo 'selected="selected"';}?> value="Auto Union">Auto Union</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="AUTOBIANCHI"){echo 'selected="selected"';}?> value="Autobianchi">Autobianchi</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="AVTOGAZ"){echo 'selected="selected"';}?> value="AvtoGAZ">AvtoGAZ</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="AVTOVAZ"){echo 'selected="selected"';}?> value="AvtoVAZ">AvtoVAZ</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="BELLIER"){echo 'selected="selected"';}?> value="Bellier">Bellier</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="BENTLEY"){echo 'selected="selected"';}?> value="Bentley">Bentley</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="BMW"){echo 'selected="selected"';}?> value="BMW">BMW</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="BOLLORE"){echo 'selected="selected"';}?> value="Bolloré">Bollore</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="BRISTOL"){echo 'selected="selected"';}?> value="Bristol">Bristol</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="BUGATTI"){echo 'selected="selected"';}?> value="Bugatti">Bugatti</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="BUICK"){echo 'selected="selected"';}?> value="Buick">Buick</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="CADILLAC"){echo 'selected="selected"';}?> value="Cadillac">Cadillac</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="CASALINI"){echo 'selected="selected"';}?> value="Casalini">Casalini</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="CATERHAM"){echo 'selected="selected"';}?> value="Caterham">Caterham</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="CHATENET"){echo 'selected="selected"';}?> value="Chatenet">Chatenet</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="CHAUSSON"){echo 'selected="selected"';}?> value="Chausson">Chausson</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="CHENARD ET WALCKER"){echo 'selected="selected"';}?> value="Chenard et Walcker">Chenard et Walcker</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="CHEVROLET"){echo 'selected="selected"';}?> value="Chevrolet">Chevrolet</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="CHRYSLER"){echo 'selected="selected"';}?> value="Chrysler">Chrysler</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="CITROEN"){echo 'selected="selected"';}?> value="Citroën">Citroen</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="DACIA"){echo 'selected="selected"';}?> value="Dacia">Dacia</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="DAEWOO"){echo 'selected="selected"';}?> value="Daewoo">Daewoo</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="DAIHATSU"){echo 'selected="selected"';}?> value="Daihatsu">Daihatsu</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="DATSUN"){echo 'selected="selected"';}?> value="Datsun">Datsun</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="DE DION-BOUTON"){echo 'selected="selected"';}?> value="De Dion-Bouton">De Dion-Bouton</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="DE TOMASO"){echo 'selected="selected"';}?> value="De Tomaso">De Tomaso</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="DELAGE"){echo 'selected="selected"';}?> value="Delage">Delage</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="Delahaye"){echo 'selected="selected"';}?> value="Delahaye">Delahaye</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="DELOREAN"){echo 'selected="selected"';}?> value="DeLorean">DeLorean</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="DODGE"){echo 'selected="selected"';}?> value="Dodge">Dodge</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="DONKERVOORT"){echo 'selected="selected"';}?> value="Donkervoort">Donkervoort</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="DS"){echo 'selected="selected"';}?> value="DS">DS</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="DUE"){echo 'selected="selected"';}?> value="Dué">DUE</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="EAGLE"){echo 'selected="selected"';}?> value="Eagle">Eagle</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="EXAGON"){echo 'selected="selected"';}?> value="Exagon">Exagon</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="EXCALIBUR"){echo 'selected="selected"';}?> value="Excalibur">Excalibur</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="FACEL VEGA"){echo 'selected="selected"';}?> value="Facel Vega">Facel Vega</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="FERRARI"){echo 'selected="selected"';}?> value="Ferrari">Ferrari</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="FIAT"){echo 'selected="selected"';}?> value="FIAT">FIAT</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="FISKER"){echo 'selected="selected"';}?> value="Fisker">Fisker</option> 
<option <?php if(strtoupper($dossier->vehicule_marque)=="FORD"){echo 'selected="selected"';}?> value="Ford">Ford</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="FORD MUSTANG"){echo 'selected="selected"';}?> value="Ford Mustang">Ford Mustang</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="FUSO"){echo 'selected="selected"';}?> value="Fuso">Fuso</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="GAZ"){echo 'selected="selected"';}?> value="GAZ">GAZ</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="GINETTA"){echo 'selected="selected"';}?> value="Ginetta">Ginetta</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="GMC"){echo 'selected="selected"';}?> value="GMC">GMC</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="GRECAV"){echo 'selected="selected"';}?> value="Grecav">Grecav</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="GUMPERT"){echo 'selected="selected"';}?> value="Gumpert">Gumpert</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="HOMMELL"){echo 'selected="selected"';}?> value="Hommell">Hommell</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="HONDA"){echo 'selected="selected"';}?> value="Honda">Honda</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="HORCH"){echo 'selected="selected"';}?> value="Horch">Horch</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="HUMMER"){echo 'selected="selected"';}?> value="Hummer">Hummer</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="HYUNDAI"){echo 'selected="selected"';}?> value="Hyundai">Hyundai</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="INFINITI"){echo 'selected="selected"';}?> value="Infiniti">Infiniti</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="INNOCENTI"){echo 'selected="selected"';}?> value="Innocenti">Innocenti</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="ISUZU"){echo 'selected="selected"';}?> value="Isuzu">Isuzu</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="IVECO"){echo 'selected="selected"';}?> value="Iveco">Iveco</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="JAGUAR"){echo 'selected="selected"';}?> value="Jaguar">Jaguar</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="JDM SIMPA"){echo 'selected="selected"';}?> value="JDM Simpa">JDM Simpa</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="JEEP"){echo 'selected="selected"';}?> value="Jeep">Jeep</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="KIA"){echo 'selected="selected"';}?> value="Kia">Kia</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="KOENIGSEGG"){echo 'selected="selected"';}?> value="Koenigsegg">Koenigsegg</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="KTM"){echo 'selected="selected"';}?> value="KTM">KTM</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="LADA"){echo 'selected="selected"';}?> value="Lada">Lada</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="LAMBORGHINI"){echo 'selected="selected"';}?> value="Lamborghini">Lamborghini</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="LANCIA"){echo 'selected="selected"';}?> value="Lancia">Lancia</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="LAND ROVER"){echo 'selected="selected"';}?> value="Land Rover">Land Rover</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="LEXUC"){echo 'selected="selected"';}?> value="Lexus">Lexus</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="LIGIER"){echo 'selected="selected"';}?> value="Ligier">Ligier</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="LINCOLN"){echo 'selected="selected"';}?> value="Lincoln">Lincoln</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="LOTUS"){echo 'selected="selected"';}?> value="Lotus">Lotus</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="MASERATI"){echo 'selected="selected"';}?> value="Maserati">Maserati</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="MATRA"){echo 'selected="selected"';}?> value="Matra">Matra</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="MAYBACH"){echo 'selected="selected"';}?> value="Maybach">Maybach</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="MAZDA"){echo 'selected="selected"';}?> value="Mazda">Mazda</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="MCLAREN"){echo 'selected="selected"';}?> value="McLaren">McLaren</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="MEGA"){echo 'selected="selected"';}?> value="Mega">Mega</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="MERCEDES-BENZ"){echo 'selected="selected"';}?> value="Mercedes-Benz">Mercedes-Benz</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="MG"){echo 'selected="selected"';}?> value="MG">MG</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="MAI ELECTRIC"){echo 'selected="selected"';}?> value="Mia electric">Mia electric</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="MINI"){echo 'selected="selected"';}?> value="Mini">Mini</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="MITSUBISHI"){echo 'selected="selected"';}?> value="Mitsubishi">Mitsubishi</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="MITSUOKA"){echo 'selected="selected"';}?> value="Mitsuoka">Mitsuoka</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="MORGAN"){echo 'selected="selected"';}?> value="Morgan">Morgan</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="MORRIS"){echo 'selected="selected"';}?> value="Morris">Morris</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="NASH"){echo 'selected="selected"';}?> value="Nash">Nash</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="NECKAR"){echo 'selected="selected"';}?> value="Neckar">Neckar</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="NISSAN"){echo 'selected="selected"';}?> value="Nissan">Nissan</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="NOBLE"){echo 'selected="selected"';}?> value="Noble">Noble</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="NSU"){echo 'selected="selected"';}?> value="NSU">NSU</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="OLDSMOBILE"){echo 'selected="selected"';}?> value="Oldsmobile">Oldsmobile</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="OPEL"){echo 'selected="selected"';}?> value="Opel">Opel</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="PACKARD"){echo 'selected="selected"';}?> value="Packard">Packard</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="PAGANI"){echo 'selected="selected"';}?> value="Pagani">Pagani</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="PANHARD"){echo 'selected="selected"';}?> value="Panhard">Panhard</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="PANOZ"){echo 'selected="selected"';}?> value="Panoz">Panoz</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="PANTHER"){echo 'selected="selected"';}?> value="Panther">Panther</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="PEUGEOT"){echo 'selected="selected"';}?> value="Peugeot">Peugeot</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="PGO"){echo 'selected="selected"';}?> value="PGO">PGO</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="PLYMOUTH"){echo 'selected="selected"';}?> value="Plymouth">Plymouth</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="PONTIAC"){echo 'selected="selected"';}?> value="Pontiac">Pontiac</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="PORSCHE"){echo 'selected="selected"';}?> value="Porsche">Porsche</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="RADICAL"){echo 'selected="selected"';}?> value="Radical">Radical</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="RENAULT"){echo 'selected="selected"';}?> value="Renault">Renault</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="RILEY"){echo 'selected="selected"';}?> value="Riley">Riley</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="ROLLS-ROYCE"){echo 'selected="selected"';}?> value="Rolls-Royce">Rolls-Royce</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="ROVER"){echo 'selected="selected"';}?> value="Rover">Rover</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="SAAB"){echo 'selected="selected"';}?> value="Saab">Saab</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="SALEEN"){echo 'selected="selected"';}?> value="Saleen">Saleen</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="SANTANA"){echo 'selected="selected"';}?> value="Santana">Santana</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="SEAT"){echo 'selected="selected"';}?> value="Seat">Seat</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="SECMA"){echo 'selected="selected"';}?> value="Secma">Secma</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="SHELBY"){echo 'selected="selected"';}?> value="Shelby">Shelby</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="SIMCA"){echo 'selected="selected"';}?> value="Simca">Simca</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="SKODA"){echo 'selected="selected"';}?> value="Skoda">Skoda</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="SMART"){echo 'selected="selected"';}?> value="Smart">Smart</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="SSANGYONG"){echo 'selected="selected"';}?> value="SsangYong">SsangYong</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="STUDEBAKER"){echo 'selected="selected"';}?> value="Studebaker">Studebaker</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="SUBARU"){echo 'selected="selected"';}?> value="Subaru">Subaru</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="SUZUKI"){echo 'selected="selected"';}?> value="Suzuki">Suzuki</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="TALBOT"){echo 'selected="selected"';}?> value="Talbot">Talbot</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="TAZZARI"){echo 'selected="selected"';}?> value="Tazzari">Tazzari</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="TESLA MOTORS"){echo 'selected="selected"';}?> value="Tesla Motors">Tesla Motors</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="TOYOTA"){echo 'selected="selected"';}?> value="Toyota">Toyota</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="TRIUMPH"){echo 'selected="selected"';}?> value="Triumph">Triumph</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="TVR"){echo 'selected="selected"';}?> value="Tvr">Tvr</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="ULTIMA"){echo 'selected="selected"';}?> value="Ultima">Ultima</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="VAZ"){echo 'selected="selected"';}?> value="VAZ">VAZ</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="VECTOR"){echo 'selected="selected"';}?> value="Vector">Vector</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="VENTURI"){echo 'selected="selected"';}?> value="Venturi">Venturi</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="VOLKSWAGEN"){echo 'selected="selected"';}?> value="Volkswagen">Volkswagen</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="VOLVO"){echo 'selected="selected"';}?> value="Volvo">Volvo</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="WEBER"){echo 'selected="selected"';}?> value="Weber">Weber</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="WIESMANN"){echo 'selected="selected"';}?> value="Wiesmann">Wiesmann</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="ZASTAVA"){echo 'selected="selected"';}?> value="Zastava">Zastava</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="ZENVO"){echo 'selected="selected"';}?> value="Zenvo">Zenvo</option>
<option <?php if(strtoupper($dossier->vehicule_marque)=="AUTRE"){echo 'selected="selected"';}?> value="AUTRE">AUTRE</option>

</select>
                                                                
                                                                
                                                                
                                                                </div>
                                                            </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label"> Type</label>

                                                                <div class="input-group-control">
                                                                    <input onchange="changing(this)" type="text" id="vehicule_type" name="vehicule_type" class="form-control"   value="{{ $dossier->vehicule_type }}" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Immatriculation</label>

                                                                <div class="input-group-control">
                                                                    <input onchange="changing(this)"  type="text" id="vehicule_immatriculation" name="vehicule_immatriculation" class="form-control"   value="{{ $dossier->vehicule_immatriculation }}" >
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>





                                                    <div class="row"   id="adresse003"  <?php if ($dossier->empalcement3 =='') {echo 'style="display:none;"';}  ?>  >
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label"><label id="derniere003">Dernière</label> adresse d'immobilisation  </label>

                                                                <div class="input-group-control">


                                                                    <select onchange="changing(this);ajout_prest(this);"  type="text" id="empalcement3" name="empalcement3" class="form-control"   value="{{ $dossier->empalcement_medic3 }}">

                                                                        <option></option>
                                                                        <?php

                                                                        foreach($garages as $gr)
                                                                        { if ($dossier->empalcement3 == PrestatairesController::ChampById('name',$gr->prestataire_id)){ $selected='selected="selected"'; }else{ $selected=''; }
                                                                            if (PrestatairesController::ChampById('name',$gr->prestataire_id)!='') {echo '<option  title="'.$gr->prestataire_id.'"  '.$selected.' value="'. PrestatairesController::ChampById('name',$gr->prestataire_id).'">'. PrestatairesController::ChampById('name',$gr->prestataire_id).'</option>';}
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">De (Date)</label>

                                                                <div class="input-group-control">
                                                                    <input   type="text" id="date_debut_emp3"  name='date_debut_emp3' class="form-control datepicker-default" data-format="dd-MM-yyyy hh:mm:ss"  value="{{ $dossier->date_debut_emp3 }}"  onchange="changing(this);">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">A (Date)</label>

                                                                <div class="input-group-control">
                                                                    <input   type="text" id="date_fin_emp3"  name="date_fin_emp3"   class="form-control datepicker-default"   value="{{ $dossier->date_fin_emp3 }}"  onchange="changing(this);">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-1" style="padding-top:30px">
                                                            <span title="cacher l'adresse" style="margin-top:20px;width:20px" class=" btn-md" id="0btn004moins"><i class="fa  fa-minus"></i></span>

                                                        </div>
                                                    </div>
                                                    <div class="row" id="adresse002"  <?php if ($dossier->empalcement2 =='') {echo 'style="display:none;"';}  ?> >
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label"><label id="derniere002">Dernière</label> adresse d'immobilisation </label>
                                                                <select onchange="changing(this);ajout_prest(this);"  id="empalcement2" name="empalcement2" class="form-control"   value="{{ $dossier->empalcement2 }}">

                                                                    <option></option>
                                                                    <?php

                                                                    foreach($garages as $gr)
                                                                    { if ($dossier->empalcement2 == PrestatairesController::ChampById('name',$gr->prestataire_id)){ $selected='selected="selected"'; }else{ $selected=''; }
                                                                        if (PrestatairesController::ChampById('name',$gr->prestataire_id)!='') {echo '<option  title="'.$gr->prestataire_id.'"  '.$selected.' value="'. PrestatairesController::ChampById('name',$gr->prestataire_id).'">'. PrestatairesController::ChampById('name',$gr->prestataire_id).'</option>';}
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">De (Date)</label>

                                                                <div class="input-group-control">
                                                                    <input   type="text" id="date_debut_emp2"  name="date_debut_emp2" class="form-control datepicker-default"    value="{{ $dossier->date_debut_emp2 }}"  onchange="changing(this);">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">A (Date)</label>

                                                                <div class="input-group-control">
                                                                    <input   type="text" id="date_fin_emp2"  name="date_debut_emp2" class="form-control datepicker-default"   value="{{ $dossier->date_fin_emp2 }}"  onchange="changing(this);">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1" style="padding-top:30px">
                                                            <span title="Afficher une autre adresse" style="margin-top:20px;width:20px" class=" btn-md" id="0btn004plus"><i class="fa fa-plus"></i></span>
                                                            <span title="cacher l'adresse" style="margin-top:20px;width:20px" class=" btn-md" id="0btn003moins"><i class="fa   fa-minus"></i></span>
                                                        </div>
                                                    </div>

                                                    <div class="row" id="adresse001">
                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label"><label id="derniere001">Dernière </label> adresse d'immobilisation  </label>

                                                                <div class="input-group-control">
                                                                    <select onchange="changing(this);ajout_prest(this);"    id="empalcement" name="empalcement" class="form-control"   value="{{ $dossier->empalcement }}">

                                                                        <option></option>
                                                                        <?php

                                                                        foreach($garages as $gr)
                                                                        { if ($dossier->empalcement == PrestatairesController::ChampById('name',$gr->prestataire_id)){ $selected='selected="selected"'; }else{ $selected=''; }
                                                                            if (PrestatairesController::ChampById('name',$gr->prestataire_id)!='') {echo '<option  title="'.$gr->prestataire_id.'"  '.$selected.' value="'. PrestatairesController::ChampById('name',$gr->prestataire_id).'">'. PrestatairesController::ChampById('name',$gr->prestataire_id).'</option>';}
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">De (Date)</label>

                                                                <div class="input-group-control">
                                                                    <input   type="text" id="date_debut_emp" name="date_debut_emp"  class="form-control datepicker-default"     value="{{ $dossier->date_debut_emp }}"  onchange="changing(this);">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">A (Date)</label>

                                                                <div class="input-group-control">
                                                                    <input   type="text" id="date_fin_emp" name="date_fin_emp"   class="form-control datepicker-default"    value="{{ $dossier->date_fin_emp }}"  onchange="changing(this);">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1" style="padding-top:30px">
                                                            <div class="col-md-1" style="padding-top:30px">
                                                                <span title="Afficher une autre adresse" style="margin-top:20px;width:20px" class=" btn-md" id="0btn003plus"><i class="fa   fa-plus"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>




                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Autre adresse  </label>

                                                                <div class="input-group-control">

                                                                    <div class="input-group-control">
                                                                        <input    type="text" id="vehicule_address2" name="vehicule_address2" class="form-control"   value="<?php echo $dossier->vehicule_address2 ; ?>" onchange="changing(this);"  >
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label"> Ville / localité</label>

                                                                <div class="input-group-control">
                                                                    <input   type="text" id="vehicule_address" name="vehicule_address" class="form-control"   value="<?php echo $dossier->vehicule_address ; ?>" onchange="changing(this);" >
                                                                </div>
                                                                <script>
                                                                    var placesAutocomplete = places({
                                                                        appId: 'plCFMZRCP0KR',
                                                                        apiKey: 'aafa6174d8fa956cd4789056c04735e1',
                                                                        container: document.querySelector('#vehicule_address')
                                                                    });
                                                                </script>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">De (Date)</label>

                                                                <div class="input-group-control">
                                                                    <input   type="text" id="date_debut_vehicule_address" name="date_debut_vehicule_address"  class="form-control datepicker-default"     value="<?php echo $dossier->date_debut_vehicule_address ; ?>" onchange="changing(this);"  >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">A (Date)</label>

                                                                <div class="input-group-control">
                                                                    <input   type="text" id="date_fin_vehicule_address" name="date_fin_vehicule_address"   class="form-control datepicker-default"  value="<?php echo $dossier->date_fin_vehicule_address ; ?>" onchange="changing(this);"  >
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>


                                                    <div class="row"   id="adresse13" <?php if ($dossier->empalcement_trans3 =='') {echo 'style="display:none;"';}  ?> >
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label"><label id="derniere13">Dernier</label> garage </label>

                                                                <div class="input-group-control">
                                                                    <input onchange="changing(this)" type="text" id="empalcement_trans3"  class="form-control" value="{{ $dossier->empalcement_trans3 }}" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Type</label>

                                                                <div class="input-group-control">
                                                                    <select onchange="changing(this)"   id="type_trans3" class="form-control "  value="{{ $dossier->type_trans3 }}" >
                                                                        <option  <?php if($dossier->type_trans3==""){echo 'selected="selected"';}?> value=""></option>
                                                                        <option  <?php if($dossier->type_trans3=="gardiennage"){echo 'selected="selected"';}?>  value="gardiennage">Gardiennage </option>
                                                                        <option  <?php if($dossier->type_trans3=="garage"){echo 'selected="selected"';}?> value="garage">Garage </option>
                                                                        <option  <?php if($dossier->type_trans3=="libre"){echo 'selected="selected"';}?> value="libre">Adresse Libre </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">De (Date)</label>

                                                                <div class="input-group-control">
                                                                    <input onchange="changing(this)" type="text" id="date_debut_trans3"  class="form-control datepicker-default" data-format="dd-MM-yyyy" style="padding:6px 3px 6px 3px!important;" value="{{ $dossier->date_debut_trans3 }}" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">A (Date)</label>

                                                                <div class="input-group-control">
                                                                    <input onchange="changing(this)" type="text" id="date_fin_trans3"   class="form-control datepicker-default"  style="padding:6px 3px 6px 3px!important;"  value="{{ $dossier->date_fin_trans3 }}" >
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-1" style="padding-top:30px">
                                                            <span title="cacher l'adresse" style="margin-top:20px;width:20px" class=" btn-md" id="btn014moins"><i class="fa  fa-minus"></i> </span>

                                                        </div>
                                                    </div>
                                                    <div class="row" id="adresse12" <?php if ($dossier->empalcement_trans2 =='') {echo 'style="display:none;"';}  ?> >
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label"><label id="derniere12">Dernier</label> garage  </label>

                                                                <div class="input-group-control">
                                                                    <input onchange="changing(this)" type="text" id="empalcement_trans2"   class="form-control" value="{{ $dossier->empalcement_trans2 }}" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Type</label>

                                                                <div class="input-group-control">
                                                                    <select onchange="changing(this)"   id="type_trans2" class="form-control "  value="{{ $dossier->type_trans2 }}" >
                                                                        <option  <?php if($dossier->type_trans2==""){echo 'selected="selected"';}?> value=""></option>
                                                                        <option  <?php if($dossier->type_trans2=="gardiennage"){echo 'selected="selected"';}?>  value="gardiennage">Gardiennage </option>
                                                                        <option  <?php if($dossier->type_trans2=="garage"){echo 'selected="selected"';}?> value="garage">Garage </option>
                                                                        <option  <?php if($dossier->type_trans2=="libre"){echo 'selected="selected"';}?> value="libre">Adresse Libre </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">De (Date)</label>

                                                                <div class="input-group-control">
                                                                    <input onchange="changing(this)" type="text" id="date_debut_trans2"  class="form-control datepicker-default"  style="padding:6px 3px 6px 3px!important;" value="{{ $dossier->date_debut_trans2 }}" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">A (Date)</label>

                                                                <div class="input-group-control">
                                                                    <input onchange="changing(this)" type="text" id="date_fin_trans2"  class="form-control datepicker-default"  style="padding:6px 3px 6px 3px!important;"  value="{{ $dossier->date_fin_trans2 }}" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1" style="padding-top:30px">
                                                            <span title="Afficher une autre adresse" style="margin-top:20px;width:20px" class=" btn-md" id="btn014plus"><i class="fa fa-plus"></i> </span>
                                                            <span title="cacher l'adresse" style="margin-top:20px;width:20px" class=" btn-md" id="btn013moins"><i class="fa   fa-minus"></i> </span>
                                                        </div>
                                                    </div>

                                                    <div class="row" id="adresse11">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label"><label id="derniere11">Dernier </label> garage  </label>

                                                                <div class="input-group-control">
                                                                    <input onchange="changing(this)" type="text" id="empalcement_trans"    class="form-control" value="{{ $dossier->empalcement_trans }}" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Type</label>

                                                                <div class="input-group-control">
                                                                    <select onchange="changing(this)"   id="type_trans" class="form-control "  value="{{ $dossier->type_trans }}" >
                                                                    <option  <?php if($dossier->type_trans==""){echo 'selected="selected"';}?> value=""></option>
                                                                    <option  <?php if($dossier->type_trans=="gardiennage"){echo 'selected="selected"';}?>  value="gardiennage">Gardiennage </option>
                                                                    <option  <?php if($dossier->type_trans=="garage"){echo 'selected="selected"';}?> value="garage">Garage </option>
                                                                    <option  <?php if($dossier->type_trans=="libre"){echo 'selected="selected"';}?> value="libre">Adresse Libre </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">De (Date)</label>

                                                                <div class="input-group-control">
                                                                    <input onchange="changing(this)" type="text" id="date_debut_trans" class="form-control datepicker-default"  style="padding:6px 3px 6px 3px!important;" value="{{ $dossier->date_debut_trans }}" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">A (Date)</label>

                                                                <div class="input-group-control">
                                                                    <input onchange="changing(this)" type="text" id="date_fin_trans"   class="form-control datepicker-default" style="padding:6px 3px 6px 3px!important;"  value="{{ $dossier->date_fin_trans }}" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1" style="padding-top:30px">
                                                            <div class="col-md-1" style="padding-top:30px">
                                                                <span title="Afficher une autre adresse" style="margin-top:20px;width:20px" class=" btn-md" id="btn013plus"><i class="fa  fa-plus"></i> </span>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div><br><br>


                                </div>
                            </div>
                            <div class="col-md-12">

                                Dossier créé par <B><?php echo $creator ;?></B> - Date :<?php echo $createdat ?>
                                <!--   <div class="form-actions right">
                                       <button type="button" id="editDos" class="btn btn-sm btn-info">Enregistrer</button>
                                   </div>-->
                            </div>
                        </form>
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
                            {{ csrf_field() }}

                            <input id="parent" name="parent" type="hidden" value="{{ $dossier->id}}">
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
<div class="modal fade" id="generatedoc"    role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
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
                                    <select class="form-control select2" style="width: 230px" required id="templatedoc" name="templatedoc" >
                                        <option value="Select">Selectionner</option>
                                    <?php
                                        $usedtemplates = Document::where('dossier',$dossier->id)->distinct()->get(['template']);
                                        $usedtid=array();
                                        foreach ($usedtemplates as $tempu) {
                                            $usedtid[]=$tempu['template'];
                                        }
                                        $templatesd = Template_doc::get();
                                        $docwithcl = array();
                                    ?>
                                        @foreach ($templatesd as $tempdoc)
                                           @if (! in_array($tempdoc["id"],$usedtid))
                                                <option value={{ $tempdoc["id"] }} >{{ $tempdoc["nom"] }}</option>
                                            @endif                                            
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
<!-- Modal template html doc-->
<div class="modal fade" id="templatehtmldoc"    role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
    <div class="modal-dialog" role="document" style="width:900px;height: 450px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModal2">Veuillez éditer les champs du document</h5>

            </div>
            <div class="modal-body">
                <div class="card-body">


                    <div class="form-group">
                        

                        <form id="gendocfromhtml" novalidate="novalidate" method="post" action="{{ route('documents.adddocument') }}">
                            {{ csrf_field() }}
                            <input id="dossdoc" name="dossdoc" type="hidden" value="{{ $dossier->id}}">
                            <input type="hidden" name="templatedocument" id="templatedocument" >
                            <input type="hidden" name="iddocparent" id="iddocparent" >
                            <iframe src="#" id="templatefilled" name="templatefilled" style="width:100%;height:100%">content</iframe>

                        </form>
                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" id="gendochtml" class="btn btn-primary">Générer</button>
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>


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
                                          <?php  if($dossier->affecte >0) {$agentname = User::where('id',$dossier->affecte)->first();}else{$agentname=null;}?>

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
                                <label for="adresse">Qualité</label>
                                <div class=" row  ">
                                    <input class="form-control" type="text" required id="fonctione"/>

                                </div>
                            </div>

                            <div class="form-group ">
                                <label for="code">Adresse Email </label>
                                <div class="row">
                                    <input type="email"   class="form-control"  id="emaildoss"   onchange="checkexiste(this,'mail')" />

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
<div class="modal fade" id="adding6"  role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" >Ajouter un numéro Tel </h5>

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
                                <label for="adresse">Qualité</label>
                                <div class=" row  ">
                                    <input class="form-control" type="text" required id="fonctiont"/>

                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="code">Tel</label>
                                <div class="row">
                                    <input type="number"   class="form-control"  id="teldoss"   onchange="checkexiste(this,'tel')"   />

                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="code">Type</label>
                                <div class="row">
                                    <select    class="form-control"  id="typetel"  >

                                        <option value="Mobile">Mobile</option>
                                        <option value="Fixe">Fixe</option>
                                    </select>

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



<!-- Modal SMS -->
<div class="modal fade" id="sendsms" role="dialog" aria-labelledby="sendingsms" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModal7">Envoyer un SMS </h5>

            </div>
            <form method="post" action="{{action('EmailController@sendsms')}}" >

                <div class="modal-body">
                    <div class="card-body">


                        <div class="form-group">
                            {{ csrf_field() }}
                            <label for="description">Dossier:</label>

                            <div class="form-group">
                                <select id ="dossier"  class="form-control " style="width: 120px">
                                    <option></option>
                                    <?php foreach($dossiers as $ds)

                                    {
                                        echo '<option value="'.$ds->reference_medic.'"> '.$ds->reference_medic.' </option>';}     ?>
                                </select>
                            </div>


                        </div>


                        <div class="form-group">
                            {{ csrf_field() }}
                            <label for="description">Description:</label>
                            <input id="description" type="text" class="form-control" name="description"     />
                        </div>

                        <div class="form-group">

                            <label for="destinataire">Destinataire:</label>
                            <input id="destinataire" type="number" class="form-control" name="destinataire"      />
                        </div>

                        <div class="form-group">
                            <label for="contenu">Message:</label>
                            <textarea  type="text" class="form-control" name="message"></textarea>
                        </div>
                    {{--  {!! NoCaptcha::renderJs() !!}     --}}
                    <!--  <script src="https://www.google.com/recaptcha/api.js" async defer></script>-->




                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button  type="submit"  class="btn btn-md  btn-primary btn_margin_top"><i class="fa fa-paper-plane" aria-hidden="true"></i> Envoyer</button>
                </div>
            </form>

        </div>
    </div>
</div>




<!-- Modal -->
<div class="modal fade" id="observations"    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Observations de dossier</h3>

            </div>
            <div class="modal-body">
                <div class="card-body">

                    {{ csrf_field() }}




                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <textarea onchange="changing(this)"  rows="3" class="form-control" name="observation" id="observation">{{ $dossier->observation }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <span title="Ajouter une observation " style="width:20px" class=" btn-md" id="btno1" onclick="document.getElementById('obser2').style.display='block'"><i class="fa fa-plus"></i>  </span>

                                    </div>

                                </div>
                                <div class="row" id="obser2"  <?php if ($dossier->observation2==''){ echo 'style="display:none"' ;} ?>  >
                                    <div class="col-md-10">
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <textarea onchange="changing(this)"  rows="3" class="form-control" name="observation2" id="observation2">{{ $dossier->observation2 }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <span title="Ajouter une observation " style="width:20px" class=" btn-md" id="btno2" onclick="document.getElementById('obser3').style.display='block'"><i class="fa fa-plus"></i>  </span>
                                    </div>

                                </div>
                                <div class="row" id="obser3" <?php if ($dossier->observation3==''){ echo 'style="display:none"' ;} ?>>
                                    <div class="col-md-10">
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <textarea onchange="changing(this)"  rows="3" class="form-control" name="observation3" id="observation3">{{ $dossier->observation3 }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-2"  >
                                        <span title="Ajouter une observation " style="width:20px" class=" btn-md" id="btno3" onclick="document.getElementById('obser4').style.display='block'"><i class="fa fa-plus"></i>  </span>


                                    </div>

                                </div>
                                <div class="row" id="obser4" <?php if ($dossier->observation4==''){ echo 'style="display:none"' ;} ?>>
                                    <div class="col-md-10">
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <textarea onchange="changing(this)"  rows="3" class="form-control" name="observation4" id="observation4">{{ $dossier->observation4 }}</textarea>
                                        </div>
                                    </div>

                                </div>



            </div>


        </div>
    </div>
</div>
</div>


<!-- Modal -->
<div class="modal fade" id="createAccuse"    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Envoyer un accusé de réception</h5>

            </div>
            <div class="modal-body">
                <div class="card-body">

                    {{ csrf_field() }}
                </div>
                <div class="form-group">

                    <?php $typea=trim(strtoupper($dossier->type_affectation));
                    $from='';
                    if($typea=='NAJDA'){$from='24ops@najda-assistance.com';}
                    if($typea=='VAT'){$from='hotels.vat@medicmultiservices.com';}
                    if($typea=='MEDIC'){$from='assistance@medicmultiservices.com';}
                    if($typea=='TRANSPORT MEDIC'){$from='ambulance.transp@medicmultiservices.com';}
                    if($typea=='TRANSPORT VAT'){$from='vat.transp@medicmultiservices.com';}
                    if($typea=='MEDIC INTERNATIONAL'){$from='operations@medicinternational.tn';}
                    if($typea=='NAJDA TPA'){$from='tpa@najda-assistance.com';}
                    if($typea=='TRANSPORT NAJDA'){$from='taxi@najda-assistance.com';}
                    if($typea=='X-PRESS'){$from='x-press@najda-assistance.com';}

                    $langue = app('App\Http\Controllers\ClientsController')->ClientChampById('langue1',$dossier->customer_id);

                    ?>
                        <label for="destinataire">Sujet:</label>

                        <input type="hidden"   name="from" id="from" value="<?php echo $from; ?>" />
                     <?php

                        $subscriber_name = app('App\Http\Controllers\DossiersController')->ChampById('subscriber_name',$dossier->id);
                        $subscriber_lastname = app('App\Http\Controllers\DossiersController')->ChampById('subscriber_lastname',$dossier->id);

                        if ($from=='tpa@najda-assistance.com') {
                            $nomabn = $subscriber_name . ' ' . $subscriber_lastname;
                        }else{
                            $nomabn = $subscriber_name ;
                        }

                        if ($langue=='francais'){
                        $sujet=  $nomabn.'  - V/Réf: '.$dossier->reference_customer .' - N/Réf: '.$dossier->reference_medic ;

                        }else{
                        $sujet=  $nomabn.'  - Y/Ref: '.$dossier->reference_customer .' - O/Ref: '.$dossier->reference_medic ;

                        }
                        ?>
                     <input style="width:100%" class="form-control" type="sujet"   name="sujet" id="sujet" value="<?php echo $sujet; ?>" />

                    <label for="destinataire">Destinataire:</label>

                    <select id="emaildestinataire"    required  class="form-control" name="destinataire[]" style="width:100%" multiple >
                       
                       <?php foreach($listeemails as  $mail)
                           { ?>
                            <option   value="<?php echo $mail ;?>"> <?php echo $mail ;?>  <small style="font-size:12px">(<?php echo PrestatairesController::NomByEmail( $mail) .' '.PrestatairesController::PrenomByEmail( $mail)  ;?>)  "</small> </option>
                        <?php } ?>
                    </select>
                </div>

                <div id="formaccuse"  >
                    {{ csrf_field() }}
                    <?php $message= EntreesController::GetParametre($dossier->customer_id);
$dossiersigent=Dossier::where('id',$dossier->id)->first();
if($dossiersigent['type_affectation']==="Najda")
{
$entite="Najda Assistance";

}
 if($dossiersigent['type_affectation']==="MEDIC")
{
$entite="Medic' Multiservices";


}
 if($dossiersigent['type_affectation']==="VAT")
{
$entite="Voyages Assistance Tunisie";



}
if($dossiersigent['type_affectation']==="Medic International")
{
$entite="Medic’ International";



}
if($dossiersigent['type_affectation']==="Najda TPA")
{
$entite="Najda TPA";



}
if($dossiersigent['type_affectation']==="Transport Najda")
{
$entite="Najda Transport";



}
if($dossiersigent['type_affectation']==="Transport MEDIC")
{
$entite="Medic' Multiservices";


}
if($dossiersigent['type_affectation']==="Transport VAT")
{
$entite="Voyages Assistance Tunisie";



}
if($dossiersigent['type_affectation']==="X-Press")
{
$entite="X-Press Remorquage";



}
$message=str_replace('Najda', $entite, $message);
                    //  echo json_encode($message);?>
               <!--     <div  style="width: 540px; height: 450px;padding:5px 5px 5px 5px;border:1px solid black" id="message" contenteditable="true" ><?php // echo $message   ;?></div>-->

                    <div class="form-group ">
                        <label for="contenu">Contenu:</label>
                        <div class="editor" >
                            <textarea style="min-height: 280px;" id="message"  class="textarea tex-com"   name="contenu" required  ><?php echo $message   ;?></textarea>
                        </div>
                    </div>

                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="submit" onclick="document.getElementById('sendaccuse').disabled=true" id="sendaccuse" class="btn btn-primary">Envoyer</button>
            </div>
        </div>
    </div>
</div>

<div class="modal  " id="crendu" >
    <?php     $listedossiers = DB::table('dossiers')->get();
?>
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="text-align:center"  id="modalalert0"><center>Compte Rendu </center> </h5>
            </div>
            <div class="modal-body">
                <div class="card-body">


                    <input   id="iddossier"  type="hidden"  value="<?php echo $dossier->id ?>" name="dossierid"     />
                    <input   id="refdossier"  type="hidden"  value="<?php echo $dossier->reference_medic ?>" name="refdossier"     />
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
                        <input type="text"  id="emetteur"   class="form-control" name="emetteur"    ></input>

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
                                    echo '<option class="telsassures" title="'.$title.'" value="'.$phone->champ.'">'.$phone->champ.'  ('.$phone->nom.' '.$phone->prenom.' | '.$phone->remarque.')</option>';

                                }
                                ?>
                                <?php foreach($phonesCl   as $phone)
                                {
$title=$phone->nom.' '.$phone->prenom.' ( '.$phone->remarque .' ) ';
                                    echo '<option class="telsclients" title="'.$title.'" value="'.$phone->champ.'">'.$phone->champ.'   ('.$phone->nom.' '.$phone->prenom.' | '.$phone->remarque.') </option>';

                                }
                                ?>
                                <?php foreach($phonesInt   as $phone)
                                {
$title=$phone->nom.' '.$phone->prenom.' ( '.$phone->remarque .' ) ';
                                    echo '<option class="telsintervs" title="'.$title.'" value="'.$phone->champ.'">'.$phone->champ.'  ('.$phone->nom.' '.$phone->prenom.' | '.$phone->remarque.')</option>';

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
<!--Modal Tel conference-->

    <div class="modal fade" style="z-index:10000!important;left: 20px;" id="numaconference1"    role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
        <div class="modal-dialog" role="numaconference1">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal2">Saisir le numéro</h5>

                </div>
                <div class="modal-body">
                    <div class="card-body" sytle="height:300px">

                        <div class="form-group">
                            {{ csrf_field() }}

                            <form id="numaconference1" novalidate="novalidate">

                                <input id="numaconf1" name="numaconf1" type="text" value="" />
                                   
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
<?php

?>

                    <button type="button"  class="btn btn-primary"  onclick="Conference5();">Conférence
</button>
           <button type="button"  class="btn btn-primary"  onclick="Conference6();">Confirmer la Conférence
</button>
  <button type="button"  class="btn btn-primary"  onclick="Conference7();">Annuler la Conférence
</button>
   
   
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>

                </div>
            </div>

        </div>

    </div>
<!--Modal Tel 2-->

    <div class="modal fade" id="appelinterfaceenvoi"    role="dialog" aria-labelledby="exampleModal2" aria-hidden="true" data-backdrop="static"  data-keyboard="false">
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


                   
 <button type="button"  class="btn btn-primary"  onclick="Hangup1();"><i class="fas fa-phone-slash"></i> Raccrocher</button>
 <div id="mettreenattenteenv" style="display :none;"><button type="button"  class="btn btn-primary" onclick="hold1(true);" ><i class="fas fa-pause"></i> En attente</button></div>
 <div id="reprendreappelenv" style="display :none;"><button type="button"  class="btn btn-primary"  onclick="hold1(false);"><i class="fas fa-phone"></i> Reprendre</button></div>
 <div id="coupersonenv" style="display :none;"><button type="button"  class="btn btn-primary" onclick="mute1(true,0);" ><i class="fas fa-microphone-slash"></i> Couper </button></div>
 <div id="reactivesonenv" style="display :none;"><button type="button"  class="btn btn-primary"  onclick="mute1(false,0);"><i class="fas fa-microphone"></i> Réactiver </button></div>
 <button id="transferappenv" style="display :none;" type="button"  class="btn btn-primary" data-toggle="modal" data-target="#numatransfer1"><i class="fas fa-reply-all"></i> Transférer</button>
<button id="conferenceappenv" style="display:none;" type="button"  class="btn btn-primary" data-toggle="modal" data-target="#numaconference1"><i class="fas fa-user-friends"></i> Conférence</button>
<button type="button" class="btn btn-secondary reloadclass" data-dismiss="modal">Fermer</button>
              <!--<button type="button"  class="btn btn-primary"  onclick="transfer();">Transférer</button>    
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>!-->

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

                                             <?php    

app('App\Http\Controllers\MissionController')->verifier_fin_missions($dossier['id']);
$count= App\Mission::where('dossier_id',$dossier['id'])
                        ->where('statut_courant','!=','annulee')
                        ->where('statut_courant','!=','achevee')
                        ->count();
if($count==0) {
?>
                        <center><B> Etes vous sûrs de vouloir clôturer ce Dossier ?</B><br> <br> </center>

                        <center><label  style="width:250px;text-align:center;margin-bottom:50px;" class="check "> Fermer Sans suite
                                <input type="checkbox" id="sanssuite" class="form-control">
                                <span class="checkmark"   ></span>

                            </label></center>
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

   <?php  if($dossier->entree >0 ) {
/*
   $entree= Entree::where('id',$dossier->entree)->get();
   $entree=    Entree::find($dossier->entree);
   ?>
   <!--  Modal Entree --->

    <div class="modal  " id="EntreeGen" >
        <div class="modal-dialog" >
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" style="text-align:center"  id=" "><center>Email Générateur </center> </h3>
                </div>
                <div class="modal-body">



    <?php

 
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



    $type=$entree['type'];
    $time=$entree['created_at'];$heure= "<small>Il y'a ".time_elapsed_string($time, false).'</small>';
    //  $emetteur= $entree['emetteur'] ;
    $emetteur=custom_echo($entree['emetteur'],'18');
    //  $sujet= $entree['sujet'] ;
    $sujet=custom_echo($entree['sujet'],'20');
    $attachs=$entree['nb_attach'];

    ?>
    <div class="agent" id="agent-<?php echo $entree->id;?>"   >
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
            <label for="contenu" id="contenulabel" style="cursor:pointer">Contenu:</label>
            <div    id="lecontenu" class="form-control" style=" <?php if($entree->type=='fax'){echo 'display:none';}?>;  overflow:scroll;min-height:400px">

                <?php

                if($entree['contenu']!= null)
                {$content= nl2br($entree['contenu']) ;}else{
                $content= nl2br($entree['contenutxt']);
                }
                echo ($content);  ?>
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



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width:100px">Fermer</button>
                </div>
            </div>
        </div>
    </div>


    <?php */
    }

    ?>

@endsection

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="{{ asset('public/js/select2/js/select2.js') }}"></script>

<script>
$('.reloadclass').click(function(){
 
                            window.location.reload();
});
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

function checkEmail(email) {
             var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
             return re.test(email);
         };
    function changing(elm) {
        var champ=elm.id;

        var val =document.getElementById(champ).value;
        //  var type = $('#type').val();
        var dossier = $('#iddossupdate').val();
        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('dossiers.updating') }}",
            method: "POST",
            data: {dossier: dossier , champ:champ ,val:val, _token: _token},
            success: function (data) {
                $('#'+champ).animate({
                    opacity: '0.3',
                });
                $('#'+champ).animate({
                    opacity: '1',
                });
                if(champ=="adresse_facturation"){location.reload();}

            }
        });
        // } else {

        // }
    }


function changing2(elm) {
    var champ=elm.id;


    var val =document.getElementById(champ).checked==1;

    if (val==true){val=1;}
    if (val==false){val=0;}
     var dossier = $('#iddossupdate').val();
    //if ( (val != '')) {
    var _token = $('input[name="_token"]').val();
    $.ajax({
        url: "{{ route('dossiers.updating') }}",
        method: "POST",
        data: {dossier: dossier , champ:champ ,val:val, _token: _token},
        success: function (data) {
            $('#'+champ).animate({
                opacity: '0.3',
            });
            $('#'+champ).animate({
                opacity: '1',
            });

        }
    });
    // } else {

    // }
}


function disabling(elm) {
        var champ=elm;

        var val =0;
        var dossier = $('#iddossupdate').val();
        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('dossiers.updating') }}",
            method: "POST",
            data: {dossier: dossier , champ:champ ,val:val, _token: _token},
            success: function (data) {
                if (elm=='franchise'){
                    $('#nonfranchise').animate({
                        opacity: '0.3',
                    });
                    $('#nonfranchise').animate({
                        opacity: '1',
                    });
                }
                if (elm=='is_hospitalized'){
                    $('#nonis_hospitalized').animate({
                        opacity: '0.3',
                    });
                    $('#nonis_hospitalized').animate({
                        opacity: '1',
                    });
                }
                if (elm=='documents'){
                    $('#documents-non').animate({
                        opacity: '0.3',
                    });
                    $('#documents-non').animate({
                        opacity: '1',
                    });
                }

            }
        });
        // } else {

        // }
    }



    $(document).ready(function() {

/*
  // tri ordre alphabtique
        function sortSelectOptions(selectElement) {
            var options = $(selectElement + " option");
             var arr = options.map(function(_, o) { return { t: $(o).text(), v: o.value }; }).get();
            arr.sort(function(o1, o2) { return o1.t > o2.t ? 1 : o1.t < o2.t ? -1 : 0; });
            options.each(function(i, o) {
                o.value = arr[i].v;
                $(o).text(arr[i].t);
            });
         }

        sortSelectOptions('#hotel');
        sortSelectOptions('#hospital_address');
        sortSelectOptions('#medecin_traitant');
        sortSelectOptions('#empalcement');

*/

        $("#specialite_TPA").select2();
        $("#customer_id").select2();
        $("#medecin_traitant").select2();
        $("#hospital_address").select2();
        $("#hotel").select2();
        $("#vehicule_marque").select2();
        $("#empalcement").select2();
        $("#garanties").select2();

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



        // fermerdossier
        $('#fermerdossier').click(function(){
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
        $('#ouvrirdossier').click(function(){
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






        $("#agent").select2();
        $("#templatedoc").select2();
        $('#emailadd').click(function () {
            var parent = $('#parent').val();
            var champ = $('#emaildoss').val();
            var nom = $('#DescrEmail').val();
            var tel = $('#telmail').val();
            var qualite = $('#qualite').val();
            if ((champ != '')) {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('dossiers.addemail') }}",
                    method: "POST",
                    data: {parent: parent, champ: champ, nom: nom, tel: tel, qualite: qualite, _token: _token},
                    success: function (data) {

                        //   alert('Added successfully');
                        window.location = data;

                    }
                });
            } else {
                // alert('ERROR');
            }
        });

// fonction du remplissage de la template web du document
        $('#gendoc').click(function () {
            var dossier = $('#dossier').val();
            var tempdoc = $("#templatedoc").val();
            $("#gendochtml").prop("disabled", false);
            // renitialise la val de parentdoc
            $('#iddocparent').attr('value', '');
            if ((dossier != '')) {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('documents.htmlfilled') }}",
                    method: "POST",
                    data: {dossier: dossier, template: tempdoc, _token: _token},
                    success: function (data) {
                        filltemplate(data, tempdoc);
                        //alert(JSON.stringify(data));
                    }
                });
            } else {
                // alert('ERROR');
            }
        });

        $('#btnaddemail').click(function () {
            var parent = $('#iddossupdate').val();
            var nom = $('#nome').val();
            var prenom = $('#prenome').val();
            var fonction = $('#fonctione').val();
            var email = $('#emaildoss').val();
            var observ = $('#remarquee').val();
            var nature = $('#natureem').val();
            if ((email != '')) {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('dossiers.addressadd') }}",
                    method: "POST",
                    data: {
                        parent: parent,
                        nom: nom,
                        prenom: prenom,
                        fonction: fonction,
                        email: email,
                        observ: observ,
                        nature: nature,
                        _token: _token
                    },
                    success: function (data) {

                        //   alert('Added successfully');
                        window.location = data;

                    }
                });
            } else {
                // alert('ERROR');
            }
        });


        $('#btnaddtel').click(function () {
            var parent = $('#iddossupdate').val();
            var nom = $('#nomt').val();
            var prenom = $('#prenomt').val();
            var fonction = $('#fonctiont').val();
            var tel = $('#teldoss').val();
            var observ = $('#remarquet').val();
            var typetel = $('#typetel').val();
            var nature = $('#naturetel').val();
            if ((tel != '')) {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('dossiers.addressadd2') }}",
                    method: "POST",
                    data: {
                        parent: parent,
                        nom: nom,
                        prenom: prenom,
                        fonction: fonction,
                        tel: tel,
                        observ: observ,
                        nature: nature,
                        typetel: typetel,
                        _token: _token
                    },
                    success: function (data) {

                        //   alert('Added successfully');
                        window.location = data;

                    }
                });
            } else {
                alert('ERROR');
            }
        });


        // generate doc from html templte
        $('#gendochtml').click(function () {
            //alert($("#templatedocument").val());
            //$("#gendocfromhtml").submit();
            var _token = $('input[name="_token"]').val();
            var dossier = $('#dossdoc').val();
            var tempdoc = $("#templatedocument").val();
            var idparent = '';
            // verifier si cest le cas de annule et remplace pour sauvegarder lid du parent
            if ($('#iddocparent').val()) {
                idparent = $('#iddocparent').val();
                console.log('parent: ' + idparent);
            }
            $.ajax({
                url: "{{ route('documents.adddocument') }}",
                method: "POST",
                //'&_token='+_token
                data: $("#templatefilled").contents().find('form').serialize() + '&_token=' + _token + '&dossdoc=' + dossier + '&templatedocument=' + tempdoc + '&parent=' + idparent,
                success: function (data) {
                    //alert(JSON.stringify(data));
                    console.log(data);
                    location.reload();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    //alert('status code: '+jqXHR.status+' errorThrown: ' + errorThrown + ' jqXHR.responseText: '+jqXHR.responseText);
                    alert('Erreur lors de la generation du document');
                    console.log('jqXHR:');
                    console.log(jqXHR);
                    console.log('textStatus:');
                    console.log(textStatus);
                    console.log('errorThrown:');
                    console.log(errorThrown);
                }
            });
        });

    });
 
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
                     if(webphone_api.isincall())
{webphone_api.setline(-2);
            webphone_api.hangup();
              conference = 0;   }
else
{conference = 0;
$('#appelinterfaceenvoi').modal('hide');
location.reload();}
            
        }
    function transfer1()
        {
numtrans=$('#numatrans1').val();
//numtrans.toString();
//alert(numtrans);
            webphone_api.transfer(numtrans);
        }
function Conference5()
        {
conference=1;
numtrans=$('#numaconf1').val();
numtrans.toString();
//alert(numtrans);
webphone_api.setline(1);
            webphone_api.hold(true);
webphone_api.setline(2);
            webphone_api.call(numtrans);

//alert("OK");
        }
 function Conference6()
        {

//numtrans=$('#numaconf2').val();

webphone_api.setline(1);
            webphone_api.hold(false);
            webphone_api.conference(numtrans);
$('#numaconference1').modal('hide');

//alert("OK");
        }
 function Conference7()
        {

//numtrans=$('#numaconf2').val();

webphone_api.setline(1);
            webphone_api.hold(false);
           webphone_api.setline(2);
if(webphone_api.isincall!=true)
{
            webphone_api.hangup();}
$('#numaconference1').modal('hide');

//alert("OK");
        }
  function hold1(state)
        {
if(state===true)

         {   webphone_api.hold(state);
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



</script>

@section('footer_scripts')


@stop

<script src="https://cdn.jsdelivr.net/npm/places.js@1.16.4"></script>

<script>

   /* $('#addtel').click(function () {
        $('#adding6').modal({show : true});
    });*/
    
 

        function addgarantie(garantie,elm,num){

        
    
  var sel = document.getElementById('garanties');

  var opt = sel.options[num];
    //   alert(opt.selected);
 

 
            var assure = $('#ID_assure').val();
            var _token = $('input[name="_token"]').val();
            //alert('Previous : '+previous);

            if(elm.checked)
            {/* 
            $.ajax({
                url:"{{ route('garanties.addgr') }}",
                method:"POST",
                data:{assure:assure,garantie:garantie, _token:_token},
                success:function(data){

                    //   console.log(data);
                   // alert('data : '+data);
                        window.location =data;

                } 

            });*/
            }else{ // suppression
                /*
                // confirmation
                  var r = confirm("Êtes-vous sûrs de supprimer cette table de garanties ?");
                if (r == true) {                
               $.ajax({
                url:"{{ route('garanties.removegr') }}",
                method:"POST",
                data:{assure:assure,garantie:garantie, _token:_token},
                success:function(data){

                    //   console.log(data);
                   // alert('data : '+data);
                        window.location =data;

                } 

            }); 
            
                } // confirmation   
                    */
            } // suppression
 
        }
        
    function changingAddress(id,champ,elm) { 

        var champid=elm.id;
        if (champid.slice(0, 6)=="em-em-") { 
            
     
         var email = document.getElementById(champid).value;
     
         if (!(checkEmail(email))) {
            Swal.fire({
                            type: 'Error',
                            title: 'Champs invalide...',
                            text:'Adresse e-mail non valide'
                        });
            $('#'+champid).val(''); 
             return false;
         } 
         
         
        };
        var val =document.getElementById(champid).value;

        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('clients.updateaddress') }}",
            method: "POST",
            data: {id: id , champ:champ ,val:val,  _token: _token},
            success: function (data) {
                $('#'+champid).animate({
                    opacity: '0.3',
                });
                $('#'+champid).animate({
                    opacity: '1',
                });            }
        });

    }



    function checkexiste( elm,type) {
        var id=elm.id;
        if (id=="emaildoss") { 
            
     
         var email = document.getElementById(id).value;
     
         if (!(checkEmail(email))) {
            Swal.fire({
                            type: 'Error',
                            title: 'Champs invalide...',
                            text:'Adresse e-mail non valide'
                        });
            $('#emaildoss').val(''); 
             return false;
         } 
         
         
        };
        var val =document.getElementById(id).value;
        //  var type = $('#type').val();

        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('prestataires.checkexiste') }}",
            method: "POST",
            data: {   val:val,type:type, _token: _token},
            success: function (data) {

                if(data!=''){
                    parsed = JSON.parse(data);
                    string='Existe deja ! ';
                    if(parsed['nom']!=null){string+='Nom : '+parsed['nom']+ ' - '; }
                    if(parsed['prenom']!=null){string+='Prénom : '+parsed['prenom']+ ' - '; }
                    if(parsed['fonction']!=null){string+='Fonction : '+parsed['fonction']+ ' - '; }
                    if(parsed['remarque']!=null){string+='Remarque : '+parsed['remarque']+ ' - '; }
                    if(parsed['type']!=null){string+='Type : '+parsed['type']+ ' '; }
                    if(parsed['typetel']!=null){string+=parsed['typetel']+ ' '; }

                    alert(string);
                    document.getElementById(id).style.background='#FD9883';
                    document.getElementById(id).style.color='white';
                } else{
                    document.getElementById(id).style.background='white';
                    document.getElementById(id).style.color='black';
                }


            }
        });
        // } else {

        // }
    }

    function ajout_prest(elm) {

        var prest = elm.id;
       // alert(prest);
        var prestataire = document.getElementById(prest) ;
      //  alert(prestataire);

        var title= parseInt(prestataire.options[prestataire.selectedIndex].title);



        if (title > 0) {

            var dossier = $('#iddossupdate').val();

            var _token = $('input[name="_token"]').val();
            $.ajax({
                url: "{{ route('intervenants.saving') }}",
                method: "POST",
                data: {prestataire: title, dossier: dossier, _token: _token},
                success: function (data) {

                    alert('intervenant ajouté ');


                }
            });
        }

    }

    $(function () {



        $('#emaildestinataire').select2({
            filter: true,
            language: {
                noResults: function () {
                    return 'Pas de résultats';
                }
            }
        });


        $('#sendaccuse').click(function(){
            var dossier = $('#dossier').val();
            var client = $('#customer_id').val();
            var destinataire = $('#emaildestinataire').val();
            var refclient = $('#reference_customer').val();
            var affecte = $('#affecte').val();
            var from = $('#from').val();
            var sujet = $('#sujet').val();


            var message = $('#message').val();


            if (destinataire !=null)
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('dossiers.sendaccuse') }}",
                    method:"POST",
                    data:{ from:from,refclient:refclient,message:message,affecte:affecte,client:client,dossier:dossier,destinataire:destinataire,sujet:sujet, _token:_token},

                    success:function(data){

                        window.location =data;

                    }
                });
            }else{
                  alert('sélectionnez le destinataire !');
                document.getElementById('sendaccuse').disabled=false;
            }
        });


        



        
        
        $('#add2').click(function(){
             var prestataire = $('#selectedprest').val();
            var dossier_id = $('#iddossupdate').val();

            var typeprest = $('#typeprest').val();
            var gouvernorat = $('#gouvcouv').val();
            var specialite = $('#specialite').val();

            //   gouvcouv
            ///if ((parseInt(prestataire) >0)&&(parseInt(dossier_id) >0)&&(parseInt(typeprest) >0))
            ///   {
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('prestations.saving') }}",
                method:"POST",
                data:{prestataire:prestataire,dossier_id:dossier_id,specialite:specialite,gouvernorat:gouvernorat,typeprest:typeprest, _token:_token},
                success:function(data){

                    //   console.log(data);
                   // alert('data : '+data);
                        window.location =data;

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('msg : '.jqXHR.status);
                    alert('msg 2 : '.errorThrown);
                }

            });
            ///  }else{
            // alert('ERROR');
            /// }
        });

        $('.radio1').click(function() {

            var   el1=document.getElementById('montantfr');
            var   el2=document.getElementById('devisefr');
            var franchise=document.getElementById('franchise').checked;
            var plaf=document.getElementById('is_plafond').checked;
            if(franchise)
            {
                el1.style.display='block';
                el2.style.display='block';
            }
            else
            {
                el1.style.display='none';
                el2.style.display='none';
            }

            var   el3=document.getElementById('plafondmt');
            var   el4=document.getElementById('plafonddv');
            if(plaf)
            {
                el3.style.display='block';
                el4.style.display='block';
            }
            else
            {
                el3.style.display='none';
                el4.style.display='none';
            }
        });


        $('#is_hospitalized').click(function() {

            var   div=document.getElementById('hospital');
            var hospital=document.getElementById('nonis_hospitalized').checked;

            if(hospital)
            {div.style.display='block';  }
            else
            {div.style.display='none';     }

        });


        $('#nonis_hospitalized').click(function() {

            var   div=document.getElementById('hospital');
            var hospital=document.getElementById('nonis_hospitalized').checked;

            if(hospital)
            {div.style.display='block';  }
            else
            {div.style.display='none';     }

        });


        $('#documents').click(function() {

            var   div=document.getElementById('documentsdiv');
            var docs=document.getElementById('documents').checked;

            if(docs)
            {div.style.display='block';  }
            else
            {div.style.display='none';     }

        });

        $('#documentsnon').click(function() {

            var   div=document.getElementById('documentsdiv');
            var docs=document.getElementById('documents').checked;

            if(docs)
            {div.style.display='block';  }
            else
            {div.style.display='none';     }

        });

        $('#btn01').click(function() {

            var   div=document.getElementById('ben2');
            if(div.style.display==='none')
            {
                div.style.display='block';
            }
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


        $('#btn03plus').click(function() {

            var   div=document.getElementById('adresse2');
            if(div.style.display==='none')
            {div.style.display='block';
                document.getElementById('derniere1').style.display='none';
                document.getElementById('derniere3').style.display='none';
                document.getElementById('derniere2').style.display='inline';
            }
           /* else
            {div.style.display='none';     }*/


        });


        $('#btn04plus').click(function() {

            var   div=document.getElementById('adresse3');
            if(div.style.display==='none')
            {div.style.display='block';
                document.getElementById('derniere1').style.display='none';
                document.getElementById('derniere2').style.display='none';
                document.getElementById('derniere3').style.display='inline';
            }


        });

        $('#btn03moins').click(function() {
            var   div=document.getElementById('adresse2');

               document.getElementById('derniere1').style.display='inline';
            document.getElementById('derniere2').style.display='none';
            document.getElementById('derniere3').style.display='none';
            document.getElementById('adresse2').style.display='none';
            document.getElementById('adresse3').style.display='none';
        /*    else
            {div.style.display='none';     }*/


        });


        $('#btn04moins').click(function() {

            var   div=document.getElementById('adresse3');
            if(div.style.display==='block')
            {div.style.display='none';

                document.getElementById('derniere1').style.display='none';
                document.getElementById('derniere2').style.display='block';
                document.getElementById('derniere3').style.display='none';
            }


        });
///////



        $('#btn003plus').click(function() {

            var   div=document.getElementById('adresse02');
            if(div.style.display==='none')
            {div.style.display='block';
                document.getElementById('derniere01').style.display='none';
                document.getElementById('derniere03').style.display='none';
                document.getElementById('derniere02').style.display='inline';
            }


        });


        $('#btn004plus').click(function() {

            var   div=document.getElementById('adresse03');
            if(div.style.display==='none')
            {div.style.display='block';
                document.getElementById('derniere01').style.display='none';
                document.getElementById('derniere02').style.display='none';
                document.getElementById('derniere03').style.display='inline';
            }

        });

        $('#btn003moins').click(function() {


            document.getElementById('derniere01').style.display='inline';
            document.getElementById('derniere02').style.display='none';
            document.getElementById('derniere03').style.display='none';
            document.getElementById('adresse02').style.display='none';
            document.getElementById('adresse03').style.display='none';

        });


        $('#btn004moins').click(function() {

            var   div=document.getElementById('adresse03');
            if(div.style.display==='block')
            {div.style.display='none';

                document.getElementById('derniere01').style.display='none';
                document.getElementById('derniere02').style.display='inline';
                document.getElementById('derniere03').style.display='none';
            }

        });

        ////


        $('#0btn003plus').click(function() {

            var   div=document.getElementById('adresse002');
            if(div.style.display==='none')
            {div.style.display='block';
                document.getElementById('derniere001').style.display='none';
                document.getElementById('derniere003').style.display='none';
                document.getElementById('derniere002').style.display='inline';
            }
            /* else
             {div.style.display='none';     }*/


        });


        $('#0btn004plus').click(function() {

            var   div=document.getElementById('adresse003');
            if(div.style.display==='none')
            {div.style.display='block';
                document.getElementById('derniere001').style.display='none';
                document.getElementById('derniere002').style.display='none';
                document.getElementById('derniere003').style.display='inline';
            }
            /*  else
             {div.style.display='none';     }*/


        });

        $('#0btn003moins').click(function() {


            document.getElementById('derniere001').style.display='inline';
            document.getElementById('derniere002').style.display='none';
            document.getElementById('derniere003').style.display='none';
            document.getElementById('adresse002').style.display='none';
            document.getElementById('adresse003').style.display='none';
            /*    else
             {div.style.display='none';     }*/


        });


        $('#0btn004moins').click(function() {

            var   div=document.getElementById('adresse003');
            if(div.style.display==='block')
            {div.style.display='none';

                document.getElementById('derniere001').style.display='none';
                document.getElementById('derniere002').style.display='inline';
                document.getElementById('derniere003').style.display='none';
            }
            /*     else
             {div.style.display='none';     }*/


        });

        //////


        $('#btn013plus').click(function() {

            var   div=document.getElementById('adresse12');
            if(div.style.display==='none')
            {div.style.display='block';
                document.getElementById('derniere11').style.display='none';
                document.getElementById('derniere13').style.display='none';
                document.getElementById('derniere12').style.display='inline';
            }
            /* else
             {div.style.display='none';     }*/


        });


        $('#btn014plus').click(function() {

            var   div=document.getElementById('adresse13');
            if(div.style.display==='none')
            {div.style.display='block';
                document.getElementById('derniere11').style.display='none';
                document.getElementById('derniere12').style.display='none';
                document.getElementById('derniere13').style.display='inline';
            }
        });

        $('#btn013moins').click(function() {


            document.getElementById('derniere11').style.display='inline';
            document.getElementById('derniere12').style.display='none';
            document.getElementById('derniere13').style.display='none';
            document.getElementById('adresse12').style.display='none';
            document.getElementById('adresse13').style.display='none';
        });


        $('#btn014moins').click(function() {

            var   div=document.getElementById('adresse13');
            if(div.style.display==='block')
            {div.style.display='none';

                document.getElementById('derniere11').style.display='none';
                document.getElementById('derniere12').style.display='inline';
                document.getElementById('derniere13').style.display='none';
            }

        });

        $('#docs').select2({
            filter: true,
            language: {
                noResults: function () {
                    return 'Pas de résultats';
                }
            }

        });


        var $topo1 = $('#docs');

        var valArray0 = ($topo1.val()) ? $topo1.val() : [];

        $topo1.change(function() {
            var val0 = $(this).val(),
                numVals = (val0) ? val0.length : 0,
                changes;
            if (numVals != valArray0.length) {
                var longerSet, shortSet;
                (numVals > valArray0.length) ? longerSet = val0 : longerSet = valArray0;
                (numVals > valArray0.length) ? shortSet = valArray0 : shortSet = val0;
                //create array of values that changed - either added or removed
                changes = $.grep(longerSet, function(n) {
                    return $.inArray(n, shortSet) == -1;
                });

                UpdatingS(changes, (numVals > valArray0.length) ? 'selected' : 'removed');

            }else{
                // if change event occurs and previous array length same as new value array : items are removed and added at same time
                UpdatingS( valArray0, 'removed');
                UpdatingS( val0, 'selected');
            }
            valArray0 = (val0) ? val0 : [];
        });



        function UpdatingS(array, type) {
            $.each(array, function(i, item) {

                if (type=="selected"){


                    var dossier = $('#iddossupdate').val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('docs.createdocdossier') }}",
                        method: "POST",
                        data: {dossier: dossier , doc:item ,  _token: _token},
                        success: function () {
                            $('.select2-selection').animate({
                                opacity: '0.3',
                            });
                            $('.select2-selection').animate({
                                opacity: '1',
                            });

                        }
                    });

                }

                if (type=="removed"){

                    var dossier = $('#iddossupdate').val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('docs.removedocdossier') }}",
                        method: "POST",
                        data: {dossier: dossier , doc:item ,  _token: _token},
                        success: function () {
                            $( ".select2-selection--multiple" ).hide( "slow", function() {
                                // Animation complete.
                            });
                            $( ".select2-selection--multiple" ).show( "slow", function() {
                                // Animation complete.
                            });
                        }
                    });

                }

            });
        } // updating


        
        
        
    
        var $topo2 = $('#garanties');

        var valArray2 = ($topo2.val()) ? $topo2.val() : [];

        $topo2.change(function() {
            var val0 = $(this).val(),
                numVals = (val0) ? val0.length : 0,
                changes;
            if (numVals != valArray2.length) {
                var longerSet, shortSet;
                (numVals > valArray2.length) ? longerSet = val0 : longerSet = valArray2;
                (numVals > valArray2.length) ? shortSet = valArray2 : shortSet = val0;
                //create array of values that changed - either added or removed
                changes = $.grep(longerSet, function(n) {
                    return $.inArray(n, shortSet) == -1;
                });

                UpdatingG(changes, (numVals > valArray2.length) ? 'selected' : 'removed');

            }else{
                // if change event occurs and previous array length same as new value array : items are removed and added at same time
                UpdatingG( valArray2, 'removed');
                UpdatingG( val0, 'selected');
            }
            valArray2 = (val0) ? val0 : [];
        });



        function UpdatingG(array, type) {
            $.each(array, function(i, item) {

            
            var assure = $('#ID_assure').val();
            var _token = $('input[name="_token"]').val();
            
                
  var sel = document.getElementById('garanties');

 // var opt = sel.options[item].value;
  
                if (type=="selected"){
                //  alert("added : "+item);

           $.ajax({
                url:"{{ route('garanties.addgr') }}",
                method:"POST",
                data:{assure:assure,garantie:item, _token:_token},
                success:function(data){

                    //   console.log(data);
                   // alert('data : '+data);
                        window.location =data;

                } 

            });  
                }

                if (type=="removed"){
            // alert("removed "+item);

 
                // confirmation
                  var r = confirm("Êtes-vous sûrs de supprimer cette table de garanties ?");
                if (r == true) {                
               $.ajax({
                url:"{{ route('garanties.removegr') }}",
                method:"POST",
                data:{assure:assure,garantie:item, _token:_token},
                success:function(data){

                    //   console.log(data);
                   // alert('data : '+data);
                        window.location =data;

                } 

            }); 
            
                } // confirmation   
                }

            });
        } // updating

    
        
        
         

    }); // $ function

  function showBen() {

      if (document.getElementById('benefdiff').value == 1) {
          $('#bens').css('display','block');

      }
  }

    function setTel(elm)
    {
        var num=elm.className;
        document.getElementById('destinataire').value=parseInt(num);

    }

</script>
<style>.headtable{background-color: grey!important;color:white;}
    table{margin-bottom:40px;}


    .overme {
        overflow:hidden;
        white-space:nowrap;
        text-overflow: ellipsis;
        max-width:300px;
    }

.form-control .datepicker-default{
    padding:6px 3px 6px 3px;
}

    textarea{min-height:80px;}


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


</style>
