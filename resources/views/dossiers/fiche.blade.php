@extends('layouts.mainlayout')
<?php 
use App\User ; 
use App\Template_doc ; 
use App\Document ; 

?>
<?php use \App\Http\Controllers\PrestationsController;
use  \App\Http\Controllers\PrestatairesController;
use  \App\Http\Controllers\DocsController;
?>

<link rel="stylesheet" href="{{ asset('public/css/timelinestyle.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('public/css/timeline.css') }}" type="text/css">
<!--select css-->
<link href="{{ asset('public/js/select2/css/select2.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('public/js/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@section('content')

<div class="row">

    <div class="col-md-3">

        <h2>Fiche de Dossier:<?php echo   $dossier->reference_medic ;?></h2>
    </div>

     <div class="col-md-3">
        <?php
         // les agents ne voient pas l'aaffectation - à vérifier
         if (Gate::check('isAdmin') || Gate::check('isSupervisor') ) { ?>
        <?php if ((isset($dossier->affecte)) && (!empty($dossier->affecte))) { ?>

        <b>Affecté à:</b>
        <?php 
        $agentname = User::where('id',$dossier->affecte)->first();
        if ((Gate::check('isAdmin') || Gate::check('isSupervisor')) && !empty ($agentname))
            { echo '<a href="#" data-toggle="modal" data-target="#attrmodal">';}
        echo $agentname['name']; 
        if(Gate::check('isAdmin') || Gate::check('isSupervisor'))
            { echo '</a>';}

        ?>
        <?php }
        else
        {
            if ((Gate::check('isAdmin') || Gate::check('isSupervisor')))
            {echo '<a href="#" data-toggle="modal" data-target="#attrmodal">Non affecté</a>';}
            else
            {echo '<b>Non affecté</b>';} 
        } ?>

         <?php   } ?>

     </div>
    <div class="col-md-6" style="text-align: right;padding-right: 35px">
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
                <button type="button" class="btn btn-default" id="sms">
                    <a style="color:black" href="{{action('EmailController@sms',$dossier->id)}}"> <i class="fas fa-sms"></i> SMS</a>
                </button>
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
                <button type="button" class="btn btn-default" id="newcalldossier">
                    <i class="fa fa-phone"></i>
                    Tél

                </button>
            </div>
        </div>
    </div>
    </div>

</div>
    <section class="content form_layouts">

<br>
        <B><a class="pull-right" href="{{action('DossiersController@view',$dossier->id)}}"  > <i class="fas fa-lg fa-folder-open"></i> Allez vers Détails du dossier </a></B>
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
                                            <option <?php if ($dossier->type_dossier =='Mixte'){echo 'selected="selected"';} ?> value="Mixte">Mixte</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Affecté à </label>
                                        <select id="type_affectation" name="type_affectation" class="form-control js-example-placeholder-single" readonly="readonly">
                                            <option <?php if ($dossier->type_affectation =='Najda'){echo 'selected="selected"';} ?> value="Najda">Najda</option>
                                            <option <?php if ($dossier->type_affectation =='VAT'){echo 'selected="selected"';} ?> value="VAT">VAT</option>
                                            <option <?php if ($dossier->type_affectation =='MEDIC'){echo 'selected="selected"';} ?> value="MEDIC">MEDIC</option>
                                            <option <?php if ($dossier->type_affectation =='Transport MEDIC'){echo 'selected="selected"';} ?> value="Transport MEDIC">Transport MEDIC</option>
                                            <option <?php if ($dossier->type_affectation =='Transport VAT'){echo 'selected="selected"';} ?> value="Transport VAT">Transport VAT</option>
                                            <option <?php if ($dossier->type_affectation =='Medic International'){echo 'selected="selected"';} ?> value="Medic International">Medic International</option>
                                            <option <?php if ($dossier->type_affectation =='Najda TPA'){echo 'selected="selected"';} ?> value="Najda TPA">Najda TPA</option>
                                            <option <?php if ($dossier->type_affectation =='Transport Najda'){echo 'selected="selected"';} ?> value="Transport Najda">Transport Najda</option>
                                        </select>
                                    </div>
                                </div>


                                 <div class="col-md-3">
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
                            <!--    <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputError" class="control-label">Référence *</label>

                                        <div class="input-group-control">
                                            <input    type="text" id="customer" name="reference_customer" class="form-control"   value="{{ $dossier->reference_customer }}" >
                                        </div>
                                    </div>
                                </div>-->

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="complexite"> Degré de complexité</label>
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
                                        <div id="tab_1" class="tab-pane active">
                                            <div class="col-md-12">
                                                <div class="panel panel-success">

                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <a class="accordion-toggle" data-toggle="collapse">
                                                                Info Abonné</a>
                                                        </h4>
                                                    </div>
                                                    <div class="panel-collapse collapse in">
                                                        <div class="panel-body">
                                                            <div class="col-md-12">
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Nom abonné * </label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="subscriber_name" name="subscriber_name" class="form-control" value="{{ $dossier->subscriber_name }}"  >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Prénom *</label>

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
                                                                            <label for="inputError" class="control-label">Bénéficaire </label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="beneficiaire" name="beneficiaire" class="form-control"   value="{{ $dossier->beneficiaire }}" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Prénom Bénéficaire</label>

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
                                                                            <label for="inputError" class="control-label">Bénéficaire 2</label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="beneficiaire2" name="beneficiaire" class="form-control"   value="{{ $dossier->beneficiaire2 }}" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Prénom Bénéficaire 2</label>

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
                                                                            <label for="inputError" class="control-label">Bénéficaire 3 </label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="beneficiaire3" name="beneficiaire" class="form-control"   value="{{ $dossier->beneficiaire3 }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Prénom Bénéficaire 3</label>

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



                                                                <div class="row" style="margin-top:30px">
                                                                    <div class="col-md-8">
                                                                        <h4><i class="fa fa-lg fa-user"></i> Numéros Tels</h4>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <span style="float:right" id="addtel" class="btn btn-md btn-default"   data-toggle="modal" data-target="#adding6"><b><i class="fa fa-user"></i> Ajouter un numéro de téléphone</b></span>
                                                                    </div>

                                                                </div>

                                                                <table class="table table-striped"  style="width:100%;margin-top:25px;font-size:16px;">
                                                                    <thead>
                                                                    <tr class="headtable">
                                                                        <th style="width:20%">Nom et Prénom</th>
                                                                        <th style="width:20%">Qualité</th>
                                                                        <th style="width:10%">Téléphone</th>
                                                                        <th style="width:30%">Type</th>
                                                                        <th style="width:20%">Remarque</th>
                                                                    </tr>

                                                                    </thead>
                                                                    <tbody>
                                                                    @foreach($phones as $phone)
                                                                        <tr>
                                                                            <td style="width:20%;"><?php echo $phone->nom; ?>  <?php echo $phone->prenom; ?></td>
                                                                            <td style="width:20%;"><?php echo $phone->fonction; ?></td>
                                                                            <td style="width:10%;"><?php echo $phone->tel; ?></td>
                                                                            <td style="width:30%;"><?php echo $phone->typetel.'<br>'; if($phone->typetel=='Mobile') {?> <a onclick="setTel(this);" class="<?php echo $phone->tel;?>" style="margin-left:5px;cursor:pointer" data-toggle="modal"  data-target="#sendsms" ><i class="fas fa-sms"></i>Envoyer un SMS </a><?php } ?>
                                                                            </td>
                                                                            <td style="width:20%;"><?php echo $phone->remarque; ?></td>
                                                                        </tr>
                                                                    @endforeach

                                                                    </tbody>
                                                                </table>

                                                                <div class="row" style="margin-top:30px">
                                                                    <div class="col-md-8">
                                                                        <h4><i class="fa fa-lg fa-user"></i> Emails </h4>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <span style="float:right" id="addemail" class="btn btn-md btn-default"   data-toggle="modal" data-target="#adding7"><b><i class="fa fa-user"></i> Ajouter une adresse email</b></span>
                                                                    </div>

                                                                </div>

                                                                <table class="table table-striped"  style="width:100%;margin-top:25px;margin-bottom:25px;font-size:16px;">
                                                                    <thead>
                                                                    <tr class="headtable">
                                                                        <th style="width:20%">Nom et Prénom</th>
                                                                        <th style="width:20%">Qualité</th>
                                                                        <th style="width:30%">Email</th>
                                                                        <th style="width:10%">Remarque</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    @foreach($emailads as $emailad)
                                                                        <tr>
                                                                            <td style="width:20%;"><?php echo $emailad->nom; ?>  <?php echo $emailad->prenom; ?></td>
                                                                            <td style="width:20%;"><?php echo $emailad->fonction; ?></td>
                                                                            <td style="width:30%;"><?php echo $emailad->mail; ?></td>
                                                                            <td style="width:30%;"><?php echo $emailad->remarque; ?></td>
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

                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">To </label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="to" name="to" class="form-control"   value="{{ $dossier->to }}" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Guide</label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)"  type="text" id="to_guide" name="to_guide" class="form-control"   value="{{ $dossier->to_guide }}" >
                                                                            </div>
                                                                        </div>
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
                                                                <div class="row" id="adresse3"  <?php if ($dossier->subscriber_local_address3 =='') {echo 'style="display:none;"';}  ?> >
                                                                    <div class="form-group col-md-10">
                                                                        <label for="inputError" class="control-label"><p id="derniere3">Dernière</p> Adresse en Tunisie  </label>

                                                                        <div class="input-group-control">
                                                                            <input onchange="changing(this)"  type="text" id="subscriber_local_address3" name="subscriber_local_address3" class="form-control"   value="{{ $dossier->subscriber_local_address3 }}" >
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-1"style="padding-top:30px">
                                                                        <span title="cacher l'adresse" style="margin-top:20px;width:20px" class=" btn-md" id="btn04moins"><i class="fa  fa-minus"></i> </span>

                                                                    </div>
                                                                </div>
                                                                <div class="row" id="adresse2"   <?php if ($dossier->subscriber_local_address2 =='') {echo 'style="display:none;"';}  ?> >
                                                                    <div class="form-group col-md-10">
                                                                        <label for="inputError" class="control-label"><p id="derniere2">Dernière</p> Adresse en Tunisie  </label>

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
                                                                        <label for="inputError" class="control-label"><p id="derniere1">Dernière</p> Adresse en Tunisie </label>

                                                                        <div class="input-group-control">
                                                                            <input onchange="changing(this)"  type="text" id="subscriber_local_address" name="subscriber_local_address" class="form-control"   value="{{ $dossier->subscriber_local_address }}">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-1" style="padding-top:30px">
                                                                        <span title="Afficher une autre adresse" style="margin-top:20px;width:20px" class=" btn-md" id="btn03plus"><i class="fa   fa-plus"></i> </span>
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
                                                                <div class="row">

                                                                    <div class="col-md-5">
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

                                                                    <div class="col-md-5">
                                                                        <!--<div class="form-group">
                                                                            <label for="inputError" class="control-label">Hôtel</label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)"  type="text" id="hotel" name="hotel" class="form-control"   value="{{ $dossier->hotel }}" >
                                                                            </div>
                                                                        </div>-->

                                                                            <div class="form-group">
                                                                                <label for="inputError" class="control-label">Hôtel </label>

                                                                                <div class="input-group-control">
                                                                                    <select onchange="changing(this)"  type="text" id="hotel" name="hotel" class="form-control"   value="{{ $dossier->hotel }}">

                                                                                        <option></option>
                                                                                        <?php

                                                                                        foreach($hotels as $ht)
                                                                                        { if ($dossier->hotel == PrestatairesController::ChampById('name',$ht->prestataire_id)){ $selected='selected="selected"'; }else{ $selected=''; }
                                                                                            if( PrestatairesController::ChampById('name',$ht->prestataire_id)!=''){ echo '<option  '.$selected.' value="'.   PrestatairesController::ChampById('name',$ht->prestataire_id).'">'.   PrestatairesController::ChampById('name',$ht->prestataire_id).'</option>';}
                                                                                        }
                                                                                        ?>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <label for="inputError" class="control-label">Autre : </label>

                                                                        <a style="" href="{{ route('prestataires') }}" class="btn btn-default btn-sm" role="button">+ Ajouter</a>

                                                                    </div>
                                                                </div>
                                                                    <div class="row">

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Chambre</label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)"  type="text" id="subscriber_local_address_ch" name="subscriber_local_address_ch" class="form-control"   value="{{ $dossier->subscriber_local_address_ch }}" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Tel Chambre</label>
                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="tel_chambre" name="tel_chambre" class="form-control"   value="{{ $dossier->tel_chambre }}" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
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
                                            <div class="panel panel-success">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <a class="accordion-toggle" data-toggle="collapse">
                                                            Info Demandeur</a>
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-collapse collapse in">
                                            <div class="panel-body">
                                                <div class="col-md-12">


                                                    <div class="row">
                                                        <div class="col-md-9">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Entité de facturation  </label>

                                                                <div class="input-group-control">
                                                                    <select onchange="changing(this)" type="text" id="adresse_facturation" name="adresse_facturation" class="form-control"    >
                                                                        <option></option>
                                                                        <option  <?php if ($dossier->adresse_facturation==$entite){echo 'selected="selected"';} ?> value="<?php echo $entite;?>"><?php echo $entite .' <small>'.$adresse.'</small>';?></option>
                                                                        <?php foreach ($liste as $l)
                                                                        {?>
                                                                            <option  <?php  if ($dossier->adresse_facturation==$l->nom ){echo 'selected="selected"';} ?> value="<?php $l->nom;?>" ><?php $l->nom ;?>   <small>  <?php $l->champ;?> </small></option>
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

                                                        <div class="col-md-4"  id="montantfr">
                                                            <div class="form-group">
                                                                <label class="control-label">Montant Franchise
                                                                </label>

                                                                <div class="input-group-control">
                                                                    <input onchange="changing(this)"  type="text" id="montant_franchise" name="montant_franchise" class="form-control" style="width: 100px;" placeholder="Montant"   value="{{ $dossier->montant_franchise }}" >
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4" id="plafondfr">
                                                            <div class="form-group">
                                                                <label class="control-label">Plafond
                                                                </label>

                                                                <div class="input-group-control">
                                                                    <input onchange="changing(this)"  type="text" id="plafond" name="plafond" class="form-control" style="width: 100px;" placeholder="Plafond"   value="{{ $dossier->plafond }}" >
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="panel panel-success" id="medical" style=" <?php if ($dossier->type_dossier =='Technique'){echo 'display:none';}?>;">
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
                                                                        <div style="margin-right:20px" class="radio" id="uniform-is_hospitalized"><span><input onclick="changing(this)"  type="radio" name="is_hospitalized" id="is_hospitalized" value="1" <?php if ($dossier->is_hospitalized ==1){echo 'checked';} ?> ></span>Outpatient</div>
                                                                    </label> <label for="nonis_hospitalized" class=""> <div class="radio" id="uniform-nonis_hospitalized"><span class=""><input onclick="disabling('is_hospitalized')" type="radio" name="is_hospitalized" id="nonis_hospitalized" value="0"  <?php if ($dossier->is_hospitalized ==0){echo 'checked';} ?>  ></span> Inpatient </div>
                                                                    </label>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Hôspitalisé à </label>

                                                                    <div class="input-group-control">
                                                                        <select onchange="changing(this)"  type="text" id="hospital_address" name="hospital_address" class="form-control"   value="{{ $dossier->hospital_address }}">

                                                                        <option></option>
                                                                            <?php

                                                                        foreach($hopitaux as $hp)
                                                                        { if ($dossier->hospital_address == PrestatairesController::ChampById('name',$hp->prestataire_id)){ $selected='selected="selected"'; }else{ $selected=''; }
                                                                          if( PrestatairesController::ChampById('name',$hp->prestataire_id)!=''){ echo '<option  '.$selected.' value="'.   PrestatairesController::ChampById('name',$hp->prestataire_id).'">'.   PrestatairesController::ChampById('name',$hp->prestataire_id).'</option>';}
                                                                      }
                                                                      ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Médecin Traitant </label>
                                                                <!--
                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)"  type="text" id="medecin_traitant" name="medecin_traitant" class="form-control"   value="{{ $dossier->medecin_traitant }}" >
                                                                    </div>
                                        -->
                                                                    <div class="input-group-control">
                                                                        <select onchange="changing(this)"  type="text" id="medecin_traitant" name="medecin_traitant" class="form-control"   value="{{ $dossier->medecin_traitant }}">

                                                                            <option></option>
                                                                            <?php

                                                                            foreach($traitants as $tr)
                                                                            { if ($dossier->medecin_traitant == PrestatairesController::ChampById('name',$tr->prestataire_id)){ $selected='selected="selected"'; }else{ $selected=''; }
                                                                                if (PrestatairesController::ChampById('name',$tr->prestataire_id)!='') {echo '<option '.$selected.' value="'. PrestatairesController::ChampById('name',$tr->prestataire_id).'">'. PrestatairesController::ChampById('name',$tr->prestataire_id).' Fixe: '. PrestatairesController::ChampById('phone_home',$tr->prestataire_id) .' Tel: '.PrestatairesController::ChampById('phone_cell',$tr->prestataire_id) .'</option>';}
                                                                            }

                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!--  <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Ch</label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)"  type="text" id="hospital_ch" name="hospital_ch" class="form-control"   value="{{ $dossier->hospital_ch }}" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Tel </label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)"  type="text" id="hospital_phone" name="hospital_phone" class="form-control"   value="{{ $dossier->hospital_phone }}" >
                                                                    </div>
                                                                </div>
                                                            </div>-->

                                                        </div>

                                                        <div class="row">

                                                            <div class="col-md-5">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Autre Médecin Traitant  </label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)" type="text" id="medecin_traitant2" name="medecin_traitant2" class="form-control" value="{{ $dossier->medecin_traitant2 }}" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-5">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Tel Autre Médecin Traitant</label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)" type="text" id="hospital_phone2" name="hospital_phone2" class="form-control"   value="{{ $dossier->hospital_phone2 }}" >
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
<!--
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Adresse Hopital3 </label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)"  type="text" id="hospital_address3" name="hospital_address3" class="form-control"  value="{{ $dossier->hospital_address3 }}" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Ch3</label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)" type="text" id="hospital_ch3" name="hospital_ch3" class="form-control"  value="{{ $dossier->hospital_ch3 }}" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Tel3 </label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)"  type="text" id="hospital_phone3" name="hospital_phone3" class="form-control"  value="{{ $dossier->hospital_phone3 }}" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Médecin Traitant3 </label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)" type="text" id="medecin_traitant3" name="medecin_traitant3" class="form-control"   value="{{ $dossier->medecin_traitant3 }}" >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>-->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel panel-success " id="technique" style=" <?php if ($dossier->type_dossier =='Medical'){echo 'display:none';}?>;">
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
                                                                    <option <?php if($dossier->vehicule_marque=="AUTRE"){echo 'selected="selected"';}?> value="AUTRE">AUTRE**</option>
                                                                    <option  <?php if($dossier->vehicule_marque=="ABARTH"){echo 'selected="selected"';}?> value="ABARTH">ABARTH</option>
                                                                    <option  <?php if($dossier->vehicule_marque=="ALFA ROMEO"){echo 'selected="selected"';}?> value="ALFA ROMEO">ALFA ROMEO</option>
                                                                    <option <?php if($dossier->vehicule_marque=="ASTON MARTIN"){echo 'selected="selected"';}?> value="ASTON MARTIN">ASTON MARTIN</option>
                                                                    <option <?php if($dossier->vehicule_marque=="AUDI"){echo 'selected="selected"';}?> value="AUDI">AUDI</option>
                                                                    <option <?php if($dossier->vehicule_marque=="BENTLEY"){echo 'selected="selected"';}?> value="BENTLEY">BENTLEY</option>
                                                                    <option <?php if($dossier->vehicule_marque=="BMW"){echo 'selected="selected"';}?> value="BMW">BMW</option>
                                                                    <option <?php if($dossier->vehicule_marque=="CITROEN"){echo 'selected="selected"';}?> value="CITROEN">CITROEN</option>
                                                                    <option <?php if($dossier->vehicule_marque=="DACIA"){echo 'selected="selected"';}?> value="DACIA">DACIA</option>
                                                                    <option <?php if($dossier->vehicule_marque=="DS"){echo 'selected="selected"';}?> value="DS">DS</option>
                                                                    <option <?php if($dossier->vehicule_marque=="FERRARI"){echo 'selected="selected"';}?> value="FERRARI">FERRARI</option>
                                                                    <option <?php if($dossier->vehicule_marque=="FIAT"){echo 'selected="selected"';}?> value="FIAT">FIAT</option>
                                                                    <option <?php if($dossier->vehicule_marque=="FORD"){echo 'selected="selected"';}?> value="FORD">FORD</option>
                                                                    <option <?php if($dossier->vehicule_marque=="HONDA"){echo 'selected="selected"';}?> value="HONDA">HONDA</option>
                                                                    <option <?php if($dossier->vehicule_marque=="HYUNDAI"){echo 'selected="selected"';}?> value="HYUNDAI">HYUNDAI</option>
                                                                    <option <?php if($dossier->vehicule_marque=="IINFINITI"){echo 'selected="selected"';}?> value="IINFINITI">IINFINITI</option>
                                                                    <option <?php if($dossier->vehicule_marque=="JAGUAR"){echo 'selected="selected"';}?> value="JAGUAR">JAGUAR</option>
                                                                    <option <?php if($dossier->vehicule_marque=="JEEP"){echo 'selected="selected"';}?> value="JEEP">JEEP</option>
                                                                    <option <?php if($dossier->vehicule_marque=="KIA"){echo 'selected="selected"';}?> value="KIA">KIA</option>
                                                                    <option <?php if($dossier->vehicule_marque=="LADA"){echo 'selected="selected"';}?> value="LADA">LADA</option>
                                                                    <option <?php if($dossier->vehicule_marque=="LAMBORGHINI"){echo 'selected="selected"';}?> value="LAMBORGHINI">LAMBORGHINI</option>
                                                                    <option <?php if($dossier->vehicule_marque=="LAND ROVER"){echo 'selected="selected"';}?> value="LAND ROVER">LAND ROVER</option>
                                                                    <option <?php if($dossier->vehicule_marque=="LEXUS"){echo 'selected="selected"';}?> value="LEXUS">LEXUS</option>
                                                                    <option <?php if($dossier->vehicule_marque=="LOTUS"){echo 'selected="selected"';}?> value="LOTUS">LOTUS</option>
                                                                    <option <?php if($dossier->vehicule_marque=="MASERATI"){echo 'selected="selected"';}?> value="MASERATI">MASERATI</option>
                                                                    <option <?php if($dossier->vehicule_marque=="MAZDA"){echo 'selected="selected"';}?> value="MAZDA">MAZDA</option>
                                                                    <option <?php if($dossier->vehicule_marque=="MCLAREN"){echo 'selected="selected"';}?> value="MCLAREN">MCLAREN</option>
                                                                    <option <?php if($dossier->vehicule_marque=="MERCEDES-BENZ"){echo 'selected="selected"';}?> value="MERCEDES-BENZ">MERCEDES-BENZ</option>
                                                                    <option <?php if($dossier->vehicule_marque=="MINI"){echo 'selected="selected"';}?> value="MINI">MINI</option>
                                                                    <option <?php if($dossier->vehicule_marque=="MITSUBISHI"){echo 'selected="selected"';}?> value="MITSUBISHI">MITSUBISHI</option>
                                                                    <option <?php if($dossier->vehicule_marque=="NISSAN"){echo 'selected="selected"';}?> value="NISSAN">NISSAN</option>
                                                                    <option <?php if($dossier->vehicule_marque=="OPEL"){echo 'selected="selected"';}?> value="OPEL">OPEL</option>
                                                                    <option <?php if($dossier->vehicule_marque=="PEUGEOT"){echo 'selected="selected"';}?> value="PEUGEOT">PEUGEOT</option>
                                                                    <option <?php if($dossier->vehicule_marque=="PORSCHE"){echo 'selected="selected"';}?>  value="PORSCHE">PORSCHE</option>
                                                                    <option <?php if($dossier->vehicule_marque=="RENAULT"){echo 'selected="selected"';}?> value="RENAULT">RENAULT</option>
                                                                    <option <?php if($dossier->vehicule_marque=="ROLLS ROYCE"){echo 'selected="selected"';}?> value="ROLLS ROYCE">ROLLS ROYCE</option>
                                                                    <option <?php if($dossier->vehicule_marque=="SEAT"){echo 'selected="selected"';}?> value="SEAT">SEAT</option>
                                                                    <option <?php if($dossier->vehicule_marque=="SKODA"){echo 'selected="selected"';}?> value="SKODA">SKODA</option>
                                                                    <option <?php if($dossier->vehicule_marque=="SMART"){echo 'selected="selected"';}?> value="SMART">SMART</option>
                                                                    <option <?php if($dossier->vehicule_marque=="SSANGYONG"){echo 'selected="selected"';}?> value="SSANGYONG">SSANGYONG</option>
                                                                    <option <?php if($dossier->vehicule_marque=="SUBARU"){echo 'selected="selected"';}?> value="SUBARU">SUBARU</option>
                                                                    <option <?php if($dossier->vehicule_marque=="SUZUKI"){echo 'selected="selected"';}?> value="SUZUKI">SUZUKI</option>
                                                                    <option <?php if($dossier->vehicule_marque=="TESLA"){echo 'selected="selected"';}?> value="TESLA">TESLA</option>
                                                                    <option <?php if($dossier->vehicule_marque=="TOYOTA"){echo 'selected="selected"';}?> value="TOYOTA" >TOYOTA</option>
                                                                    <option <?php if($dossier->vehicule_marque=="VOLKSWAGEN"){echo 'selected="selected"';}?> value="VOLKSWAGEN">VOLKSWAGEN</option>
                                                                    <option <?php if($dossier->vehicule_marque=="VOLVO"){echo 'selected="selected"';}?> value="VOLVO">VOLVO</option>
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

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Dernière adresse d'immobilisation </label>

                                                                <div class="input-group-control">

                                                                    <select onchange="changing(this)"  type="text" id="lieu_immobilisation" name="medecin_traitant" class="form-control"   value="{{ $dossier->lieu_immobilisation }}">

                                                                        <option></option>
                                                                        <?php

                                                                        foreach($garages as $gr)
                                                                        { if ($dossier->lieu_immobilisation == PrestatairesController::ChampById('name',$gr->prestataire_id)){ $selected='selected="selected"'; }else{ $selected=''; }
                                                                            if (PrestatairesController::ChampById('name',$gr->prestataire_id)!='') {echo '<option '.$selected.' value="'. PrestatairesController::ChampById('name',$gr->prestataire_id).'">'. PrestatairesController::ChampById('name',$gr->prestataire_id).'</option>';}
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Autre  </label>

                                                                <div class="input-group-control">
                                                                    <input onchange="changing(this)"  type="text" id="vehicule_address2" name="vehicule_address2" class="form-control"   value="{{ $dossier->vehicule_address2 }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label"> Ville / localité</label>

                                                                <div class="input-group-control">
                                                                    <input onchange="changing(this)" type="text" id="vehicule_address" name="vehicule_address" class="form-control"   value="{{ $dossier->vehicule_address }}" >
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

                                                    <!--   <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Adresse véhicule2</label>

                                                                <div class="input-group-control">
                                                                    <input onchange="changing(this)"  type="text" id="vehicule_address2" name="vehicule_address2" class="form-control"   value="{{ $dossier->vehicule_address2 }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Tel </label>

                                                                <div class="input-group-control">
                                                                    <input onchange="changing(this)"  type="text" id="vehicule_phone" name="vehicule_phone" class="form-control"   value="{{ $dossier->vehicule_phone }}" >
                                                                </div>
                                                            </div>
                                                        </div>-->
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a class="accordion-toggle" data-toggle="collapse">
                                                    Observations</a>
                                            </h4>
                                        </div>
                                        <div class="panel-collapse collapse in">
                                            <div class="panel-body">
                                                <label for="form_control_1">Observations de dossier<span class="required"> * </span></label>

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
                            <div class="col-md-12">
                                <!--   <div class="form-actions right">
                                       <button type="button" id="editDos" class="btn btn-sm btn-info">Enregistrer</button>
                                   </div>-->
                            </div>
                        </form>
                    </div>


 <!--
                                    <div class="tab-pane" id="tab_transmedic">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="portlet light">
                                                    <div class="portlet-title">
                                                        <div class="caption">
                                                            <i class="icon-list"></i>
                                                            <span class="caption-subject bold uppercase"> Liste des transports Medic</span>
                                                        </div>
                                                        <div class="actions">

                                                        </div>
                                                    </div>
                                                    <div class="portlet-body">
                                                        <div class="table-toolbar">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="btn-group">

                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div id="medic_ajax_wrapper" class="dataTables_wrapper no-footer"><div class="row"><div class="col-md-6 col-sm-6"><div class="dataTables_length" id="medic_ajax_length"><label>Afficher <select name="medic_ajax_length" aria-controls="medic_ajax" class="form-control input-xsmall input-inline"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> enregistrements</label></div></div><div class="col-md-6 col-sm-6"><div id="medic_ajax_filter" class="dataTables_filter"><label>Rechercher&nbsp;:<input type="search" class="form-control input-small input-inline" placeholder="" aria-controls="medic_ajax"></label></div></div></div><div class="table-scrollable"><table class="table table-striped table-bordered table-hover dataTable no-footer" id="medic_ajax" role="grid" aria-describedby="medic_ajax_info" style="width: 100%;">
                                                            <thead>
                                                            <tr role="row"><th class="sorting_asc" tabindex="0" aria-controls="medic_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Type de prestation
                                                                : activer pour trier la colonne par ordre croissant" aria-sort="ascending">
                                                                    Type de prestation
                                                                </th><th class="sorting" tabindex="0" aria-controls="medic_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Prix
                                                                : activer pour trier la colonne par ordre croissant">
                                                                    Prix
                                                                </th><th class="sorting" tabindex="0" aria-controls="medic_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Valide
                                                                : activer pour trier la colonne par ordre croissant">
                                                                    Valide
                                                                </th><th class="sorting" tabindex="0" aria-controls="medic_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Référence
                                                                : activer pour trier la colonne par ordre croissant">
                                                                    Référence
                                                                </th><th class="sorting" tabindex="0" aria-controls="medic_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Actions
                                                                : activer pour trier la colonne par ordre croissant">
                                                                    Actions
                                                                </th></tr>
                                                            </thead>
                                                            <tbody>

                                                            <tr role="row" class="odd"><td class="sorting_1">Ambulances</td><td>29d (75% du tarif) déplacement</td><td><span class="label label-success"> Validé </span></td><td>10521</td><td><div class="btn-group"><center><a data-idpres="67959" data-toggle="tooltip" data-original-title="Editer" class="update_link_pres yellow filter-submit margin-bottom"><i class="fa fa-pencil font-yellow-crusta"></i></a>&nbsp;&nbsp;<a data-idpres="67959" data-toggle="tooltip" data-original-title="Annuler" class="delete_link_pres red filter-submit margin-bottom"><i class="fa fa-trash font-red-thunderbird"></i></a>&nbsp;&nbsp;<a data-idpres="67959" data-toggle="tooltip" data-original-title="Télécharger" class="blue filter-submit margin-bottom" target="_blank" href="http://197.14.53.86:10080/medic/agent/gestionprestations/odm_medic_html/67959"><i class="fa fa-download font-blue"></i></a>&nbsp;&nbsp;<a data-idpres="67959" data-toggle="tooltip" data-original-title="Attacher" data-typeodm="medic" class="save_link_pres blue filter-submit margin-bottom"><i class="fa fa-save font-green"></i></a></center></div></td></tr></tbody>
                                                        </table></div><div class="row"><div class="col-md-5 col-sm-5"><div class="dataTables_info" id="medic_ajax_info" role="status" aria-live="polite">Affichage de l'élement 1 à 1 sur 1 éléments</div></div><div class="col-md-7 col-sm-7"><div class="dataTables_paginate paging_simple_numbers" id="medic_ajax_paginate"><ul class="pagination"><li class="paginate_button previous disabled" aria-controls="medic_ajax" tabindex="0" id="medic_ajax_previous"><a href="http://197.14.53.86:10080/medic/agent/paneldossier/view/37301#"><i class="fa fa-angle-left"></i></a></li><li class="paginate_button active" aria-controls="medic_ajax" tabindex="0"><a href="http://197.14.53.86:10080/medic/agent/paneldossier/view/37301#">1</a></li><li class="paginate_button next disabled" aria-controls="medic_ajax" tabindex="0" id="medic_ajax_next"><a href="http://197.14.53.86:10080/medic/agent/paneldossier/view/37301#"><i class="fa fa-angle-right"></i></a></li></ul></div></div></div></div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_transmedic_int">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="portlet light">
                                                    <div class="portlet-title">
                                                        <div class="caption">
                                                            <i class="icon-list"></i>
                                                            <span class="caption-subject bold uppercase"> Liste des transports Medic International</span>
                                                        </div>
                                                        <div class="actions">

                                                        </div>
                                                    </div>
                                                    <div class="portlet-body">
                                                        <div class="table-toolbar">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="btn-group">

                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div id="medici_ajax_wrapper" class="dataTables_wrapper no-footer"><div class="row"><div class="col-md-6 col-sm-6"><div class="dataTables_length" id="medici_ajax_length"><label>Afficher <select name="medici_ajax_length" aria-controls="medici_ajax" class="form-control input-xsmall input-inline"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> enregistrements</label></div></div><div class="col-md-6 col-sm-6"><div id="medici_ajax_filter" class="dataTables_filter"><label>Rechercher&nbsp;:<input type="search" class="form-control input-small input-inline" placeholder="" aria-controls="medici_ajax"></label></div></div></div><div class="table-scrollable"><table class="table table-striped table-bordered table-hover dataTable no-footer" id="medici_ajax" role="grid" aria-describedby="medici_ajax_info" style="width: 100%;">
                                                            <thead>
                                                            <tr role="row"><th class="sorting_asc" tabindex="0" aria-controls="medici_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Type de prestation
                                                                : activer pour trier la colonne par ordre croissant" aria-sort="ascending">
                                                                    Type de prestation
                                                                </th><th class="sorting" tabindex="0" aria-controls="medici_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Prix
                                                                : activer pour trier la colonne par ordre croissant">
                                                                    Prix
                                                                </th><th class="sorting" tabindex="0" aria-controls="medici_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Valide
                                                                : activer pour trier la colonne par ordre croissant">
                                                                    Valide
                                                                </th><th class="sorting" tabindex="0" aria-controls="medici_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Référence
                                                                : activer pour trier la colonne par ordre croissant">
                                                                    Référence
                                                                </th><th class="sorting" tabindex="0" aria-controls="medici_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Actions
                                                                : activer pour trier la colonne par ordre croissant">
                                                                    Actions
                                                                </th></tr>
                                                            </thead>
                                                            <tbody>

                                                            <tr class="odd"><td valign="top" colspan="5" class="dataTables_empty">Aucune donnée disponible dans le tableau</td></tr></tbody>
                                                        </table></div><div class="row"><div class="col-md-5 col-sm-5"><div class="dataTables_info" id="medici_ajax_info" role="status" aria-live="polite">Affichage de l'élement 0 à 0 sur 0 éléments</div></div><div class="col-md-7 col-sm-7"><div class="dataTables_paginate paging_simple_numbers" id="medici_ajax_paginate"><ul class="pagination"><li class="paginate_button previous disabled" aria-controls="medici_ajax" tabindex="0" id="medici_ajax_previous"><a href="http://197.14.53.86:10080/medic/agent/paneldossier/view/37301#"><i class="fa fa-angle-left"></i></a></li><li class="paginate_button next disabled" aria-controls="medici_ajax" tabindex="0" id="medici_ajax_next"><a href="http://197.14.53.86:10080/medic/agent/paneldossier/view/37301#"><i class="fa fa-angle-right"></i></a></li></ul></div></div></div></div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_transvat">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="portlet light">
                                                    <div class="portlet-title">
                                                        <div class="caption">
                                                            <i class="icon-list"></i>
                                                            <span class="caption-subject bold uppercase"> Liste des transports VAT</span>
                                                        </div>
                                                        <div class="actions">

                                                        </div>
                                                    </div>
                                                    <div class="portlet-body">
                                                        <div class="table-toolbar">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="btn-group">

                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div id="vat_ajax_wrapper" class="dataTables_wrapper no-footer"><div class="row"><div class="col-md-6 col-sm-6"><div class="dataTables_length" id="vat_ajax_length"><label>Afficher <select name="vat_ajax_length" aria-controls="vat_ajax" class="form-control input-xsmall input-inline"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> enregistrements</label></div></div><div class="col-md-6 col-sm-6"><div id="vat_ajax_filter" class="dataTables_filter"><label>Rechercher&nbsp;:<input type="search" class="form-control input-small input-inline" placeholder="" aria-controls="vat_ajax"></label></div></div></div><div class="table-scrollable"><table class="table table-striped table-bordered table-hover dataTable no-footer" id="vat_ajax" role="grid" aria-describedby="vat_ajax_info" style="width: 100%;">
                                                            <thead>
                                                            <tr role="row"><th class="sorting_asc" tabindex="0" aria-controls="vat_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Type de prestation
                                                                : activer pour trier la colonne par ordre croissant" aria-sort="ascending">
                                                                    Type de prestation
                                                                </th><th class="sorting" tabindex="0" aria-controls="vat_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Prix
                                                                : activer pour trier la colonne par ordre croissant">
                                                                    Prix
                                                                </th><th class="sorting" tabindex="0" aria-controls="vat_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Valide
                                                                : activer pour trier la colonne par ordre croissant">
                                                                    Valide
                                                                </th><th class="sorting" tabindex="0" aria-controls="vat_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Référence
                                                                : activer pour trier la colonne par ordre croissant">
                                                                    Référence
                                                                </th><th class="sorting" tabindex="0" aria-controls="vat_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Actions
                                                                : activer pour trier la colonne par ordre croissant">
                                                                    Actions
                                                                </th></tr>
                                                            </thead>
                                                            <tbody>

                                                            <tr class="odd"><td valign="top" colspan="5" class="dataTables_empty">Aucune donnée disponible dans le tableau</td></tr></tbody>
                                                        </table></div><div class="row"><div class="col-md-5 col-sm-5"><div class="dataTables_info" id="vat_ajax_info" role="status" aria-live="polite">Affichage de l'élement 0 à 0 sur 0 éléments</div></div><div class="col-md-7 col-sm-7"><div class="dataTables_paginate paging_simple_numbers" id="vat_ajax_paginate"><ul class="pagination"><li class="paginate_button previous disabled" aria-controls="vat_ajax" tabindex="0" id="vat_ajax_previous"><a href="http://197.14.53.86:10080/medic/agent/paneldossier/view/37301#"><i class="fa fa-angle-left"></i></a></li><li class="paginate_button next disabled" aria-controls="vat_ajax" tabindex="0" id="vat_ajax_next"><a href="http://197.14.53.86:10080/medic/agent/paneldossier/view/37301#"><i class="fa fa-angle-right"></i></a></li></ul></div></div></div></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="tab_transnajda">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="portlet light">
                                                    <div class="portlet-title">
                                                        <div class="caption">
                                                            <i class="icon-list"></i>
                                                            <span class="caption-subject bold uppercase"> Liste des transports Najda</span>
                                                        </div>
                                                        <div class="actions">

                                                        </div>
                                                    </div>
                                                    <div class="portlet-body">
                                                        <div class="table-toolbar">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="btn-group">

                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div id="najda_ajax_wrapper" class="dataTables_wrapper no-footer"><div class="row"><div class="col-md-6 col-sm-6"><div class="dataTables_length" id="najda_ajax_length"><label>Afficher <select name="najda_ajax_length" aria-controls="najda_ajax" class="form-control input-xsmall input-inline"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> enregistrements</label></div></div><div class="col-md-6 col-sm-6"><div id="najda_ajax_filter" class="dataTables_filter"><label>Rechercher&nbsp;:<input type="search" class="form-control input-small input-inline" placeholder="" aria-controls="najda_ajax"></label></div></div></div><div class="table-scrollable"><table class="table table-striped table-bordered table-hover dataTable no-footer" id="najda_ajax" role="grid" aria-describedby="najda_ajax_info" style="width: 100%;">
                                                            <thead>
                                                            <tr role="row"><th class="sorting_asc" tabindex="0" aria-controls="najda_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Type de prestation
                                                                : activer pour trier la colonne par ordre croissant" aria-sort="ascending">
                                                                    Type de prestation
                                                                </th><th class="sorting" tabindex="0" aria-controls="najda_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Prix
                                                                : activer pour trier la colonne par ordre croissant">
                                                                    Prix
                                                                </th><th class="sorting" tabindex="0" aria-controls="najda_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Valide
                                                                : activer pour trier la colonne par ordre croissant">
                                                                    Valide
                                                                </th><th class="sorting" tabindex="0" aria-controls="najda_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Référence
                                                                : activer pour trier la colonne par ordre croissant">
                                                                    Référence
                                                                </th><th class="sorting" tabindex="0" aria-controls="najda_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Actions
                                                                : activer pour trier la colonne par ordre croissant">
                                                                    Actions
                                                                </th></tr>
                                                            </thead>
                                                            <tbody>

                                                            <tr class="odd"><td valign="top" colspan="5" class="dataTables_empty">Aucune donnée disponible dans le tableau</td></tr></tbody>
                                                        </table></div><div class="row"><div class="col-md-5 col-sm-5"><div class="dataTables_info" id="najda_ajax_info" role="status" aria-live="polite">Affichage de l'élement 0 à 0 sur 0 éléments</div></div><div class="col-md-7 col-sm-7"><div class="dataTables_paginate paging_simple_numbers" id="najda_ajax_paginate"><ul class="pagination"><li class="paginate_button previous disabled" aria-controls="najda_ajax" tabindex="0" id="najda_ajax_previous"><a href="http://197.14.53.86:10080/medic/agent/paneldossier/view/37301#"><i class="fa fa-angle-left"></i></a></li><li class="paginate_button next disabled" aria-controls="najda_ajax" tabindex="0" id="najda_ajax_next"><a href="http://197.14.53.86:10080/medic/agent/paneldossier/view/37301#"><i class="fa fa-angle-right"></i></a></li></ul></div></div></div></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="tab_pec">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="portlet light">
                                                    <div class="portlet-title">
                                                        <div class="caption">
                                                            <i class="icon-list"></i>
                                                            <span class="caption-subject bold uppercase"> Liste des prises en charge</span>
                                                        </div>
                                                        <div class="actions">
                                                            <a href="javascript:;" class="btn btn-circle btn-default" id="addPriseEnCharge"><i class="fa fa-plus"></i> Ajouter </a>
                                                        </div>
                                                    </div>
                                                    <div class="portlet-body">
                                                        <div class="table-toolbar">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="btn-group">

                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div id="pec_ajax_wrapper" class="dataTables_wrapper no-footer"><div class="row"><div class="col-md-6 col-sm-6"><div class="dataTables_length" id="pec_ajax_length"><label>Afficher <select name="pec_ajax_length" aria-controls="pec_ajax" class="form-control input-xsmall input-inline"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> enregistrements</label></div></div><div class="col-md-6 col-sm-6"><div id="pec_ajax_filter" class="dataTables_filter"><label>Rechercher&nbsp;:<input type="search" class="form-control input-small input-inline" placeholder="" aria-controls="pec_ajax"></label></div></div></div><div class="table-scrollable"><table class="table table-striped table-bordered table-hover dataTable no-footer" id="pec_ajax" role="grid" aria-describedby="pec_ajax_info" style="width: 100%;">
                                                            <thead>
                                                            <tr role="row"><th class="sorting_asc" tabindex="0" aria-controls="pec_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Type de prise en charge
                                                                : activer pour trier la colonne par ordre croissant" aria-sort="ascending">
                                                                    Type de prise en charge
                                                                </th><th class="sorting" tabindex="0" aria-controls="pec_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Date de création
                                                                : activer pour trier la colonne par ordre croissant">
                                                                    Date de création
                                                                </th><th class="sorting" tabindex="0" aria-controls="pec_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Actions
                                                                : activer pour trier la colonne par ordre croissant">
                                                                    Actions
                                                                </th></tr>
                                                            </thead>
                                                            <tbody>

                                                            <tr class="odd"><td valign="top" colspan="3" class="dataTables_empty">Aucune donnée disponible dans le tableau</td></tr></tbody>
                                                        </table></div><div class="row"><div class="col-md-5 col-sm-5"><div class="dataTables_info" id="pec_ajax_info" role="status" aria-live="polite">Affichage de l'élement 0 à 0 sur 0 éléments</div></div><div class="col-md-7 col-sm-7"><div class="dataTables_paginate paging_simple_numbers" id="pec_ajax_paginate"><ul class="pagination"><li class="paginate_button previous disabled" aria-controls="pec_ajax" tabindex="0" id="pec_ajax_previous"><a href="http://197.14.53.86:10080/medic/agent/paneldossier/view/37301#"><i class="fa fa-angle-left"></i></a></li><li class="paginate_button next disabled" aria-controls="pec_ajax" tabindex="0" id="pec_ajax_next"><a href="http://197.14.53.86:10080/medic/agent/paneldossier/view/37301#"><i class="fa fa-angle-right"></i></a></li></ul></div></div></div></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>




                                    -->
                </div>

 
  



    </section>





<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


<?php use \App\Http\Controllers\UsersController;
$users=UsersController::ListeUsers();

$CurrentUser = auth()->user();

$iduser=$CurrentUser->id;

?>


<!-- Modal Email
<div class="modal fade" id="createemail" tabindex="-1" role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
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
<div class="modal fade" id="generatedoc" tabindex="-1" role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
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
<div class="modal fade" id="templatehtmldoc" tabindex="-1" role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
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
<div class="modal fade" id="modalhistodoc" tabindex="-1" role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
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

<?php if ((Gate::check('isAdmin') || Gate::check('isSupervisor'))) { ?>
<!-- Modal attribution dossier-->
<div class="modal fade" id="attrmodal" role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModal2">Affectation dossier</h5>

            </div>
            <div class="modal-body">
                <div class="card-body">


                    <div class="form-group">
                        
                        <form  method="post" action="{{ route('dossiers.attribution') }}">
                            {{ csrf_field() }}
                            <input id="dossierid" name="dossierid" type="hidden" value="{{ $dossier->id}}">
                            <div class="form-group " >
                                <div class=" row  ">
                                    <div class="form-group mar-20">
                                        <label for="agent" class="control-label" style="padding-right: 20px">Agent</label>
                                        <select id="agent" name="agent" class="form-control select2" style="width: 230px">
                                            <option value="Select">Selectionner</option>
                                            <?php $agents = User::get(); ?>
                                           
                                                @foreach ($agents as $agt)
                                                <?php if (!empty ($agentname)) { ?>
                                                @if ($agentname["id"] == $agt["id"])
                                                    <option value={{ $agt["id"] }} selected >{{ $agt["name"] }}</option>
                                                @else
                                                    <option value={{ $agt["id"] }} >{{ $agt["name"] }}</option>
                                                @endif
                                                
                                                <?php }
                                                else
                                                      {  echo '<option value='.$agt["id"] .' >'.$agt["name"].'</option>';}
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
                        </form>
        </div>
    </div>
</div>

<!-- Modal Email -->
<div class="modal fade" id="adding7" tabindex="-1" role="dialog" aria-labelledby="exampleModal7" aria-hidden="true">
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
<div class="modal fade" id="adding6" tabindex="-1" role="dialog" aria-hidden="true">
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
                                <label for="code">Type</label>
                                <div class="row">
                                    <select    class="form-control"  id="typetel"  >
                                        <option value="Fixe">Fixe</option>
                                        <option value="Mobile">Mobile</option>
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
<div class="modal fade" id="sendsms" tabindex="-1" role="dialog" aria-labelledby="sendingsms" aria-hidden="true">
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


<?php } ?>

@endsection

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="{{ asset('public/js/select2/js/select2.js') }}"></script>

<script>

function remplacedoc(iddoc,template)
{
    //alert(iddoc+' | '+template);

        var dossier = $('#dossier').val();
        var tempdoc = template;
        $("#gendochtml").prop("disabled",false);
        if ((dossier != '') )
        {
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('documents.htmlfilled') }}",
                method:"POST",
                data:{dossier:dossier,template:tempdoc,parent:iddoc, _token:_token},
                success:function(data){
                    filltemplate(data,tempdoc);
                    // set iddocparent value
                    $('#iddocparent').val(iddoc);
                    //alert(JSON.stringify(data));
                }
            });
        }else{
            // alert('ERROR');
        }
}

function annuledoc(iddoc,template)
{
    //alert(iddoc+' | '+template);

        var dossier = $('#dossier').val();
        var tempdoc = template;
        $("#gendochtml").prop("disabled",false);
        /*if ((dossier != '') )
        {
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('documents.htmlfilled') }}",
                method:"POST",
                data:{dossier:dossier,template:tempdoc,parent:iddoc,annule:iddoc, _token:_token},
                success:function(data){
                    filltemplate(data,tempdoc);
                    // set iddocparent value
                    $('#iddocparent').val(iddoc);
                    //alert(JSON.stringify(data));
                }
            });
        }*/
        alert(tempdoc);
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
                    //alert(JSON.stringify(data));
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

                    //alert(val[0]+" | "+val[1]['emplacement']+" | "+val[1]['updated_at']);
                    urlf="{{ URL::asset('storage'.'/app/') }}";
                    aurlf="<a style='color:black' href='"+urlf+"/"+val[1]['emplacement']+"' ><i class='fa fa-download'></i> Télécharger</a>";
                    $("#tabledocshisto tbody").append("<tr><td>"+val[1]['updated_at']+"</td><td>"+aurlf+"</td></tr>");

                    });

                    $("#modalhistodoc").modal('show');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    //alert('status code: '+jqXHR.status+' errorThrown: ' + errorThrown + ' jqXHR.responseText: '+jqXHR.responseText);
                    alert('Erreur lors de recuperation de l historique du document');
                    console.log('jqXHR:');
                    console.log(jqXHR);
                    console.log('textStatus:');
                    console.log(textStatus);
                    console.log('errorThrown:');
                    console.log(errorThrown);
                }
            });
    }

function filltemplate(data,tempdoc)
{
   // window.location =data; hde gendocform and display template filled
   $("#generatedoc").modal('hide');
   //change html template content
   var templateexist = true;
   var parsed = JSON.parse(data);
   var items = [];
   var html_string="";
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
                    alert("la template html du document n'est pas bien défini ");
                }
                else
                {    
                    html_string= "{{asset('public/') }}"+"/"+val[1];

                }
                
            }
        //verifier la templte rtf du document
        if(val[0] ==='templatertf') 
            {
                if ((val[1].includes(undefined)) || (!val[1])) 
                {
                    $("#gendochtml").prop("disabled",true);
                    alert("la template rtf du document n'est pas bien défini ");
                }
                else
                {    
                    $("#templatedocument").val(tempdoc);
                }
                
            }

    });

  if (templateexist)
    {

        // remplissage de la template dans iframe
        var numparam = 0;
        $.each(items, function(index, val) {
            // les champs du document
            if ((val[0] !=='templatertf') && (val[0] !=='templatehtml') /* && (val[0].indexOf("CL_") == -1)*/ )
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



        //chargement du contenu et affichage du preview du document
        //alert(html_string);
        document.getElementById('templatefilled').src = html_string;
        $("#templatehtmldoc").modal('show');


    }
}

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


            }
        });
        // } else {

        // }
    }



    $(document).ready(function() {
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



/*
        $('#subscriber_name').change(function() {
            var subscriber_name = $('#subscriber_name').val();
            var beneficiaire = $('#beneficiaire').val();
            if( beneficiaire==''){
                $('#beneficiaire').val(subscriber_name);
            }


        });

        $('#subscriber_lastname').change(function() {
            var subscriber_lastname = $('#subscriber_lastname').val();
            var prenom_benef = $('#prenom_benef').val();
            if( prenom_benef==''){
                $('#prenom_benef').val(subscriber_lastname);
            }
        });

        */

    });




</script>

@section('footer_scripts')


@stop

<script src="https://cdn.jsdelivr.net/npm/places.js@1.16.4"></script>

<script>


    $(function () {


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

            var   div=document.getElementById('montantfr');
            if(div.style.display==='none')
            {div.style.display='block';	 }
            else
            {div.style.display='none';     }

            var   div2=document.getElementById('plafondfr');
            if(div2.style.display==='none')
            {div2.style.display='block';	 }
            else
            {div2.style.display='none';     }
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
            {div.style.display='block';	 }
            else
            {div.style.display='none';     }


        });


        $('#btn03plus').click(function() {

            var   div=document.getElementById('adresse2');
            if(div.style.display==='none')
            {div.style.display='block';
                document.getElementById('derniere1').style.display='none';
                document.getElementById('derniere3').style.display='none';
                document.getElementById('derniere2').style.display='block';
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
                document.getElementById('derniere3').style.display='block';
            }
          /*  else
            {div.style.display='none';     }*/


        });

        $('#btn03moins').click(function() {
            var   div=document.getElementById('adresse2');
          /*  if(div.style.display==='block')
            {div.style.display='none';
                document.getElementById('derniere1').style.display='block';
                document.getElementById('derniere2').style.display='none';
                document.getElementById('derniere3').style.display='none';
                document.getElementById('adresse3').style.display='none';
            }*/
            document.getElementById('derniere1').style.display='block';
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
            /*     else
             {div.style.display='none';     }*/


        });

        $("#typeprest").change(function() {

            document.getElementById('termine').style.display = 'none';
            document.getElementById('showNext').style.display='none';
            document.getElementById('choisir').style.display='none';
            document.getElementById('selectedprest').value=0;

        });

        $("#gouvcouv").change(function(){
            //  prest = $(this).val();
            document.getElementById('selectedprest').value=0;

            var  type =document.getElementById('typeprest').value;
            var  gouv =document.getElementById('gouvcouv').value;
            if((type !="")&&(gouv !=""))
            {
                var _token = $('input[name="_token"]').val();

                document.getElementById('termine').style.display = 'none';

                $.ajax({
                    url:"{{ route('dossiers.listepres') }}",
                    method:"post",

                    data:{gouv:gouv,type:type, _token:_token},
                    success:function(data){

                        //     alert('1'+data);
                        //   alert('Added successfully');
                        // alert('2'+JSON.parse((data)));
                        $('#data').html(data);
                        //window.location =data;
                        console.log(data);
                        ////       data.map((item, i) => console.log('Index:', i, 'Id:', item.id));
                        var  total =document.getElementById('total').value;

                        if(parseInt(total)>0)
                        {
                            document.getElementById('showNext').style.display='block';
                        }

                    }
                }); // ajax

            }else{
                alert('SVP, Sélectionner le gouvernorat et la spécialité');
            }
        }); // change

        $("#choisir").click(function() {
            //selected= document.getElementById('selected').value;
            selected=    $("#selected").val();
            document.getElementById('selectedprest').value = document.getElementById('prestataire_id_'+selected).value ;


        });


        $("#essai2").click(function() {
            document.getElementById('termine').style.display = 'none';
            document.getElementById('choisir').style.display = 'block';
            document.getElementById('showNext').style.display = 'block';
            document.getElementById('item1').style.display = 'block';
            document.getElementById('selected').value = 1;
            document.getElementById('selectedprest').value = 0;


        });


        $("#showNext").click(function() {
            document.getElementById('selectedprest').value = 0;

            var selected = document.getElementById('selected').value;
            var total = document.getElementById('total').value;
            //     alert(selected);
            //    alert(total);
            var next = parseInt(selected) + 1;
            document.getElementById('selected').value = next;

            if ((selected == 0)) {
                document.getElementById('termine').style.display = 'none';
                document.getElementById('item1').style.display = 'block';
                document.getElementById('choisir').style.display = 'block';

                //document.getElementById('selected').value=1;
                // $("#selected").val('1');

            }

            if ((selected) == (total  )) {//alert("Il n y'a plus de prestataires, Ressayez");
                document.getElementById('termine').style.display = 'block';

                document.getElementById('item'+(selected)).style.display = 'none';
                document.getElementById('showNext').style.display = 'none';
                document.getElementById('choisir').style.display = 'none';


            } else {

                if ((selected != 0) && (selected <= total + 1)) {
                    document.getElementById('choisir').style.display = 'block';
                    document.getElementById('termine').style.display = 'none';
                    document.getElementById('item' + selected).style.display = 'none';
                    document.getElementById('item' + next).style.display = 'block';


                    $("#selected").val(next);



                }
            }

            if(next>parseInt(total)+1) {
                document.getElementById('item' + selected).style.display = 'none';
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
</style>



<style>

    section#timeline {
        width: 80%;
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


    .overme {
        overflow:hidden;
        white-space:nowrap;
        text-overflow: ellipsis;
        max-width:300px;
    }




</style>
