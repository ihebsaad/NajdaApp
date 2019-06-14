@extends('layouts.mainlayout')
<?php 
use App\User ; 
use App\Template_doc ; 

?>
<?php use \App\Http\Controllers\PrestationsController;
     use  \App\Http\Controllers\PrestatairesController;
?>

<link rel="stylesheet" href="{{ asset('public/css/timelinestyle.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('public/css/timeline.css') }}" type="text/css">
<!--select css-->
<link href="{{ asset('public/js/select2/css/select2.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('public/js/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@section('content')

<div class="row">



     <div class="col-md-6">
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
                            Au Prestataire </a>
                    </li>
                    <li>
                        <a href="{{route('emails.envoimail',['id'=>$dossier->id,'type'=> 'assure','prest'=> 0])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                            A l'assuré </a>
                    </li>

                </ul>
            </div>

            <div class="btn-group">
                <button type="button" class="btn btn-default" id="sms">
                    <a style="color:black" href="{{action('EmailController@sms',$dossier->id)}}"> <i class="fas fa-sms"></i> SMS</a>
                </button>
            </div>

            <div class="btn-group">
                <button type="button" class="btn btn-default" id="newfax">
                    <a style="color:black" href="{{action('EmailController@envoifax',$dossier->id)}}"> <i class="fa fa-fax"></i> Fax</a>
                </button>
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


        <div class="container-fluid">
<br>
            <div class="row" style="margin-top:10px">
                <div class="col-lg-12">
                    <ul class="nav  nav-tabs">
                        <li class=" nav-item active">
                            <a class="nav-link active show" href="#tab1" data-toggle="tab"  >
                                <i class="fas fa-lg fa-folder-open"></i>  Détails de dossier
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tab2" data-toggle="tab">
                               <i class="fas a-lg fa-exchange-alt"></i>  Echanges
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tab3" data-toggle="tab">
                                <i class="fas fa-lg  fa-ambulance"></i>  Prestations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tab4" data-toggle="tab">
                                <i class="fas  fa-lg fa-file-archive"></i>  Attachements
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tab5" data-toggle="tab">
                                <i class="fas  fa-lg fa-cog"></i>  Autres
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tab6" data-toggle="tab">
                                <i class="fas fa-lg fa-file-word"></i>  Docs
                            </a>
                        </li>

                    </ul>

                </div>
            </div>
            <div class="tab-content mar-top">
                <div id="tab1" class="tab-pane fade active  in">

                    <div class="form-group" style="margin-top:25px;">
                        {{ csrf_field() }}

                        <form id="updatedossform">
                            <input type="hidden" name="iddossupdate" id="iddossupdate" value="{{ $dossier->id }}">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputError" class="control-label">Réf Dossier</label>

                                        <div class="input-group-control">
                                            <input  type="text" id="reference_medic" name="reference_medic" class="form-control" disabled=""   value="{{ $dossier->reference_medic }}" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Type de dossier</label>
                                        <select  onchange="changing(this);location.reload();"  id="type_dossier" name="type_dossier" class="form-control js-example-placeholder-single">
                                            <option <?php if ($dossier->type_dossier =='Medical'){echo 'selected="selected"';} ?> value="Medical">Medical</option>
                                            <option <?php if ($dossier->type_dossier =='Technique'){echo 'selected="selected"';} ?> value="Technique">Technique</option>
                                            <option <?php if ($dossier->type_dossier =='Mixte'){echo 'selected="selected"';} ?> value="Mixte">Mixte</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
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
                            </div>


                            <div class="row">
                                <div class="col-md-4">
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
                                        <label for="inputError" class="control-label">Référence *</label>

                                        <div class="input-group-control">
                                            <input    type="text" id="customer" name="reference_customer" class="form-control"   value="{{ $dossier->reference_customer }}" >
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
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
                                                                    <div class="col-md-5">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Nom abonné * </label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="subscriber_name" name="subscriber_name" class="form-control" value="{{ $dossier->subscriber_name }}"  >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-5">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Prénom *</label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="subscriber_lastname" name="subscriber_lastname" class="form-control"  value="{{ $dossier->subscriber_lastname }}" >
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                <div class="row">
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
                                                                <div class="row">
                                                                    <div class="form-group col-md-10">
                                                                        <label for="inputError" class="control-label">Dernière adresse en Tunisie   </label>

                                                                        <div class="input-group-control">
                                                                            <input onchange="changing(this)"  type="text" id="subscriber_local_address" name="subscriber_local_address" class="form-control"   value="{{ $dossier->subscriber_local_address }}">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-1" style="padding-top:30px">
                                                                        <span title="Afficher une autre adresse" style="margin-top:20px;width:20px" class=" btn-md" id="btn03"><i class="fa   fa-plus"></i> <i class="fa fa-minus"></i></span>
                                                                    </div>
                                                                </div>
                                                                <div class="row" id="adresse2"   <?php if ($dossier->subscriber_local_address2 =='') {echo 'style="display:none;"';}  ?> >
                                                                    <div class="form-group col-md-10">
                                                                        <label for="inputError" class="control-label">Adresse en Tunisie </label>

                                                                        <div class="input-group-control">
                                                                            <input onchange="changing(this)"  type="text" id="subscriber_local_address2" name="subscriber_local_address2" class="form-control"   value="{{ $dossier->subscriber_local_address2 }}" >
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-1" style="padding-top:30px">
                                                                        <span style='; ' title="Afficher une autre adresse" style="margin-top:20px;width:20px" class=" btn-md" id="btn04"><i class="fa  fa-plus"></i> <i class="fa fa-minus"></i></span>
                                                                    </div>
                                                                </div>

                                                                <div class="row" id="adresse3"  <?php if ($dossier->subscriber_local_address3 =='') {echo 'style="display:none;"';}  ?> >
                                                                    <div class="form-group col-md-10">
                                                                        <label for="inputError" class="control-label">Adresse en Tunisie   </label>

                                                                        <div class="input-group-control">
                                                                            <input onchange="changing(this)"  type="text" id="subscriber_local_address3" name="subscriber_local_address3" class="form-control"   value="{{ $dossier->subscriber_local_address3 }}" >
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-1">
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

                                                                    <div class="col-md-6">
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

                                                                    <div class="col-md-6">
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




                                                                <div class="row" style="margin-top:30px">
                                                                    <div class="col-md-8">
                                                                        <h4><i class="fa fa-lg fa-user"></i> Numéros Tels</h4>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <span style="float:right" id="addtel" class="btn btn-md btn-default"   data-toggle="modal" data-target="#adding6"><b><i class="fa fa-user"></i> Ajouter un Tel</b></span>
                                                                    </div>

                                                                </div>

                                                                <table class="table table-striped"  style="width:100%;margin-top:25px;font-size:16px;">
                                                                    <thead>
                                                                    <tr class="headtable">
                                                                        <th style="width:20%">Nom et Prénom</th>
                                                                        <th style="width:20%">Qualité</th>
                                                                        <th style="width:30%">Tel</th>
                                                                        <th style="width:10%">Remarque</th>
                                                                    </tr>

                                                                    </thead>
                                                                    <tbody>
                                                                    @foreach($phones as $phone)
                                                                        <tr>
                                                                            <td style="width:20%;"><?php echo $phone->nom; ?>  <?php echo $phone->prenom; ?></td>
                                                                            <td style="width:20%;"><?php echo $phone->fonction; ?></td>
                                                                            <td style="width:50%;"><?php echo $phone->tel; ?></td>
                                                                             <td style="width:50%;"><?php echo $phone->remarque; ?></td>
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

                                                                <table class="table table-striped"  style="width:100%;margin-top:25px;font-size:16px;">
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
                                                                            <td style="width:50%;"><?php echo $emailad->mail; ?></td>
                                                                            <td style="width:50%;"><?php echo $emailad->remarque; ?></td>
                                                                        </tr>
                                                                    @endforeach

                                                                    </tbody>
                                                                </table>

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
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Adresse de facturation  </label>

                                                                <div class="input-group-control">
                                                                    <input onchange="changing(this)" type="text" id="adresse_facturation" name="adresse_facturation" class="form-control"   value="{{ $dossier->adresse_facturation }}" >
                                                                </div>
                                                            </div>

                                                        </div>


                                                        
                                                    </div>

                                                    <div class="row" style="margin-left:30px">
                                                        <label  style="color:grey"> Entité principale du client :  <b><?php echo $entite; ?></b>     Adresse :  <b><?php echo $adresse; ?></b></label></br><br>
                                                    </div>

                                                    <label style="color:grey">Autres Adresses:</label>
                                                        <?php foreach ($liste as $l)
                                                        {
                                                           echo ' <div class="row" style="margin-left:30px">';

                                                            echo '<label style="color:grey">Entité : <b>'. $l->nom.'</b>   Adresse: <b>'.$l->champ.'</b></label>';
                                                            echo ' </div>';

                                                        }
                                                        ?>       <br> <br>



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
                                                            <div class="col-md-6">
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
                                                                <label for="inputError" class="control-label"> marque du véhicule</label>

                                                                <div class="input-group-control">
                                                                    <input onchange="changing(this)" type="text" id="vehicule_marque" name="vehicule_marque" class="form-control"   value="{{ $dossier->vehicule_marque }}" >
                                                                </div>
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
                                                    Observation</a>
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

            </div>

            <div id="tab2" class="tab-pane fade in">


                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item active">
                        <a class="nav-link active" id="tous-tab" data-toggle="tab" href="#tous" role="tab" aria-controls="tous" aria-selected="false">Tous  <i class="fa  a-lg fa-exchange-alt "></i></a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link " id="rec-tab" data-toggle="tab" href="#rec" role="tab" aria-controls="rec" aria-selected="true"><i class="fas a-lg fa-level-down-alt"></i>  Réception</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="env-tab" data-toggle="tab" href="#env" role="tab" aria-controls="env" aria-selected="false">Envois  <i class="fas a-lg fa-level-up-alt"></i></a>
                    </li>


                </ul>
                <div class="tab-content" id="myTabContent">


                    <div class="tab-pane fade  active in" id="tous" role="tabpanel" aria-labelledby="tous-tab">


                        <br>

                        <section id="timeline">



                            <?php //echo 'Envoyes '.json_encode($envoyes) ?>

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

                                        <?php if ($communin['boite']==0)
                                        {  echo '<span class="commsujet" style="font-size:12px"><B>Emetteur: </B>'. $communin['emetteur'].'</span>';
                                        }
                                        ?>

                                        <?php if ($communin['commentaire']!=null)
                                        {  echo '<span style="font-size:12px"><B>Commentaire: </B>'. $communin['commentaire'].'</span>';
                                        }
                                        ?>


                                        <span class="cd-date">

                                            <?php echo /*date('d/m/Y H:i', (*/$communin['reception']/*))*/ ; ?> <i class="fa fa-fw fa-clock-o"></i><br>


                                        </span>
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
                    </div>


                    <div class="tab-pane fade " id="rec" role="tabpanel" aria-labelledby="rec-tab">


                        <section id="cd-timeline" class="cd-container">

                            @if($entrees)
                                @foreach($entrees as $entree)

                                    <div class="cd-timeline-block">
                                        <div class="cd-timeline-img cd-movie">
                                            <?php if($entree->type=="email") {?>

                                            <img  src="{{ asset('public/img/mail.png') }}"  width="60" height="60">

                                            <?php }?><?php if($entree->type=="sms") {?>
                                            <img  src="{{ asset('public/img/sms.png') }}"  width="60" height="60">
                                            <?php }?>
                                            <?php if($entree->type=="whatsapp") {?>
                                            <img  src="{{ asset('public/img/whatsapp.png') }}"  width="60" height="60">

                                            <?php }?>
                                            <?php if($entree->type=="tel") {?>
                                            <img  src="{{ asset('public/img/phone.png') }}"  width="60" height="60">

                                            <?php }?>

                                            <?php if($entree->type=="fax") {?>
                                            <img  src="{{ asset('public/img/fax.png') }}"  width="60" height="60">
                                            <?php }?>

                                            <?php if($entree->type=="rendu") {?>
                                            <img  src="{{ asset('public/img/rendu.png') }}"  width="60" height="60">
                                            <?php }?>

                                        </div>
                                        <div class="cd-timeline-content">
                                            <h2>{{$entree->emetteur}}</h2>
                                            <p>
                                                {{$entree->sujet}}
                                            </p>
                                            <?php if($entree->type=="email") {?> <span><i class="fa fa-fw fa-paperclip"></i>({{$entree->nb_attach}}) Attachements</span><br><?php }?>

                                            <a class="btn btn-md btn-success" href="{{action('EntreesController@show', $entree['id'])}}"> Voir les détails</a>
                                            <span class="cd-date">
                                <i class="fa fa-fw fa-clock-o"></i>
                                                <?php if($entree->type=="email") {    echo date('d/m/Y H:i', strtotime($entree->reception))     ;}else {echo date('d/m/Y H:i', strtotime($entree->created_at))  ;} ?>

                                 </span>
                                        </div>
                                        <!-- cd-timeline-content -->
                                    </div>
                                    <!-- cd-timeline-block -->

                                @endforeach
                            @endif

                        </section>

                    </div>
                    <div class="tab-pane fade" id="env" role="tabpanel" aria-labelledby="env-tab">

                        <br>

                        <section id="cd-timeline2" class="cd-container">

                            @if($envoyes)
                                @foreach($envoyes as $envoye)

                                    <div class="cd-timeline-block">
                                        <div class="cd-timeline-img cd-movie">
                                            <?php if($envoye->type=="email") {?>

                                            <img  src="{{ asset('public/img/mail.png') }}"  width="60" height="60">

                                            <?php }?><?php if($envoye->type=="sms") {?>
                                            <img  src="{{ asset('public/img/sms.png') }}"  width="60" height="60">
                                            <?php }?>
                                            <?php if($envoye->type=="whatsapp") {?>
                                            <img  src="{{ asset('public/img/whatsapp.png') }}"  width="60" height="60">

                                            <?php }?>
                                            <?php if($envoye->type=="tel") {?>
                                            <img  src="{{ asset('public/img/phone.png') }}"  width="60" height="60">

                                            <?php }?>

                                            <?php if($envoye->type=="fax") {?>
                                            <img  src="{{ asset('public/img/fax.png') }}"  width="60" height="60">
                                            <?php }?>

                                            <?php if($envoye->type=="rendu") {?>
                                            <img  src="{{ asset('public/img/rendu.png') }}"  width="60" height="60">
                                            <?php }?>

                                        </div>
                                        <div class="cd-timeline-content">
                                            <h2>{{$envoye->destinataire}}</h2>
                                            <p>
                                                {{$envoye->description}}
                                            </p>

                                            <a class="btn btn-md btn-success" href="{{action('EnvoyesController@view', $envoye['id'])}}"> Voir les détails</a>
                                            <span class="cd-date">
                                      <i class="fa fa-fw fa-clock-o"></i>
                                                <?php echo date('d/m/Y H:i', strtotime($envoye->created_at)) ; ?>

                                 </span>
                                        </div>
                                        <!-- cd-timeline-content -->
                                    </div>
                                    <!-- cd-timeline-block -->

                                @endforeach
                            @endif

                        </section>

                    </div>



                    </div>


            </div><!-- Tab2 : Timeline-->


            <div id="tab3" class="tab-pane fade">
                    <button style="float:right;margin-top:10px;margin-bottom: 15px;margin-right: 20px" id="addpres" class="btn btn-md btn-success"   data-toggle="modal" data-target="#create"><b><i class="fas fa-plus"></i> Ajouter une Prestation</b></button>

                <table class="table table-striped" id="mytable" style="width:100%;margin-top:15px;">
                    <thead>
                    <tr id="headtable">
                        <th style="width:30%">Prestataire</th>
                        <th style="width:30%">Type</th>
                        <th style="width:30%">Prix</th>
                    </tr>

                    </thead>
                    <tbody>
                    @foreach($prestations as $prestation)
                        <?php $dossid= $prestation['dossier_id'];?>

                        <tr>

                            <td style="width:30%">
                                <?php $prest= $prestation['prestataire_id'];
                                echo PrestationsController::PrestataireById($prest);  ?>
                            </td>
                            <td style="width:30%;">
                                <?php $typeprest= $prestation['type_prestations_id'];
                                echo PrestationsController::TypePrestationById($typeprest);  ?>
                            </td>
                            <td style="width:30%">{{$prestation->price}}</td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>

            <div id="tab4" class="tab-pane fade">

                <table class="table table-striped" id="mytable" style=" ;margin-top:15px;">
                    <thead>
                    <tr id="headtable">
                        <th style="width:15%">Date</th>
                        <th style="width:30%">Titre</th>
                        <th style="width:40%">Description</th>

                        <th style="width:5%">type</th>
                        <th style="width:10%">Boite</th>
                    </tr>

                    </thead>
                    <tbody>
                      @foreach($attachements as $attach)

                        <tr>

                            <td style="width:15%;"><small><?php echo $attach->created_at;?></small></td>
                            <td  class="overme" style="width:30%;"><small><?php /* if ($attach->dossier!=null) {echo 'Fichier externe';}else{*/ echo $attach->nom; /*}*/ ?></small></td>
                            <td class="overme" style="width:40%;"><small><?php  echo $attach->description;   ?></small></td>

                            <td style="width:5%;"><small><?php
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


                                    ?></small></td>
                            <td style="width:10%"><small><?php if ($attach->boite>0) {echo ' Envoi<i class="fas a-lg fa-level-up-alt" />';}else{echo 'Réception<i class="fas a-lg fa-level-down-alt"/>';}?></small></td>

                        </tr>
                    @endforeach

                    </tbody>
                </table>

            </div>

            <div id="tab5" class="tab-pane fade">

<br><br>

            </div>

            <div id="tab6" class="tab-pane fade">
                <div style="">
                    <button style="float:right;margin-top:10px;margin-bottom: 15px;margin-right: 20px" id="adddoc" class="btn btn-md btn-success"   data-toggle="modal" data-target="#generatedoc"><b><i class="fas fa-plus"></i> Générer un document</b></button>


                </div>
                <table class="table table-striped" id="mytable2" style="width:100%;margin-top:15px;">
                    <thead>
                    <tr id="headtable">
                        <th style="">Document</th>
                        <th style="">Description</th>
                        <th style="">Télécharger</th>
                     </tr>

                    </thead>
                    <tbody>
                    @foreach($documents as $doc)
                        <tr>
                            <td style=";"><?php echo $doc->titre; ?></td>
                            <td style=";"><?php echo $doc->description; ?></td>
                            <?php 
                            $pathdoc = storage_path().$doc->emplacement;
                            ?>
                            <td style=";"><a  href="{{ URL::asset('storage'.'/app/'.$doc->emplacement) }}" ><i class="fa fa-download"></i> Télécharger</a></td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>


            </div>


            </div>



    </section>









<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


<?php use \App\Http\Controllers\UsersController;
$users=UsersController::ListeUsers();

$CurrentUser = auth()->user();

$iduser=$CurrentUser->id;

?>
<!-- Modal -->
<div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Ajouter une Nouvelle prestation</h3>

            </div>
            <div class="modal-body">
                <div class="card-body">


                    <div class="form-group">

                        <form id="addpresform" novalidate="novalidate">
                            {{ csrf_field() }}

                            <input id="idprestation" name="idprestation" type="hidden" value="68356">
                            <div class="form-group " >
                                <label>Type de prestations</label>
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
                                    <label>Gouvernorat de couverture</label>
                                    <div class="row">
                                    <select class="form-control  col-lg-12 " style="width:400px" name="gouv"    id="gouvcouv">
                                        <option></option>
                                        @foreach($gouvernorats as $aKeyG)
                                            <option   value="<?php echo $aKeyG->id;?>"> <?php echo $aKeyG->name;?></option>
                                        @endforeach

                                    </select>
                                    </div>
                                </div>



                            <div class="form-group">
                                 <label class="control-label">Date de prestation <span class="required" aria-required="true"> * </span></label>
                                <input value='<?php echo date('d/m/Y'); ?>' class="form-control datepicker-default" name="pres_date" id="pres_date" data-required="1" required="" aria-required="true">
                            </div>
<!--
                            <div style="align:center;text-align:center">

                                <span style="align:center" id="check" class="btn btn-danger">Chercher des prestataires</span>

                            </div>
-->
                            <div id="data">

                            </div>
                            <link href="http://demo.chandra-admin.com/assets/vendors/Buttons/css/buttons.css" rel="stylesheet">
                            <link href="http://demo.chandra-admin.com/assets/vendors/hover/hover.css" rel="stylesheet">
                            <link href="http://demo.chandra-admin.com/assets/css/custom_css/advbuttons.css" rel="stylesheet">
                            <a href="#" class="hvr-shrink button button-3d button-success button-rounded" style="display:none;margin-top:40px;margin-bottom:30px" id="choisir"><i class="fa fa-check"></i>  Sélectionner</a>
                            <button style="display:none;margin-top:50px;margin-bottom:50px" id="showNext" type="button" class="hvr-wobble-horizontal btn btn-lg btn-labeled btn-info">
                                Suivant
                                <span class="btn-label" style="left: 13px;">
                                                    <i class="fa fa-chevron-right"></i>
                                                </span>
                            </button>

                            <div id="termine" style="display:none;height:120px;align:center;">
                                <center><br>   Fin de la liste.<br></center>

                                <button style="margin:20px 0px 20px 40px" id="essai2" type="button" class="btn btn-labeled btn-default btn-lg hvr-wobble-to-top-right right1">
                                                <span class="btn-label">
                                                    <i class="fa fa-refresh"></i>
                                                </span>
                                    Réessayez
                                </button>
                            </div>
                            <input type="hidden" id="selected" value="0">
                             <input type="hidden" id="par" value="<?php echo $iduser;?>">
                            <label>Prestataire</label>

                            <select style="margin-top:10px;margin-bottom:10px;" disabled id="selectedprest"  class="form-control" value=" ">
                                <option></option>
                            @foreach($prestataires as $prest)
                                <option    value="<?php echo $prest->id;?>"> <?php echo $prest->name;?></option>
                            @endforeach
                            </select>
                        </form>
                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" id="add2" class="btn btn-primary">Ajouter</button>
            </div>
        </div>
    </div>
</div>


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
                                    <select class="form-control" required id="templatedoc">
                                    <?php
                                        $templatesd = Template_doc::get();
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

@endsection

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="{{ asset('public/js/select2/js/select2.js') }}"></script>

<script>



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

                    //   alert('Added successfully');
                    window.location =data;

                }
            });
        }else{
            // alert('ERROR');
        }
    });

    $('#gendoc').click(function(){
        var dossier = $('#dossier').val();
        var tempdoc = $("#templatedoc").val();
        $("#gendochtml").prop("disabled",false);
        if ((dossier != '') )
        {
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('documents.htmlfilled') }}",
                method:"POST",
                data:{dossier:dossier,template:tempdoc, _token:_token},
                success:function(data){

                    //   alert('Added successfully');
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
                            if ((val[0] !=='templatertf') && (val[0] !=='templatehtml') && (val[0].indexOf("CL_") == -1) )
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


                   /*var dd = JSON.stringify(data);
                   var parsed = JSON.parse(dd);
                   $('#templatefilled').contents().find('html').html("/"+parsed[0]['templatehtml']);*/
                  // $("#templatehtmldoc").modal('show');
                   //alert(data[1]); 


                }
            });
        }else{
            // alert('ERROR');
        }
    });


        $('#btnaddemail').click(function(){
            var parent = $('#iddossupdate').val();
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

                        //   alert('Added successfully');
                        window.location =data;

                    }
                });
            }else{
                // alert('ERROR');
            }
        });


        $('#btnaddtel').click(function(){
            var parent = $('#iddossupdate').val();
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

                        //   alert('Added successfully');
                        window.location =data;

                    }
                });
            }else{
                  alert('ERROR');
            }
        });



    // generate doc from html templte

    $('#gendochtml').click(function(){
        //alert($("#templatedocument").val());
        //$("#gendocfromhtml").submit();
        var _token = $('input[name="_token"]').val();
        var dossier = $('#dossdoc').val();
        var tempdoc = $("#templatedocument").val();
        $.ajax({
                url:"{{ route('documents.adddocument') }}",
                method:"POST",
                //'&_token='+_token
                data:$("#templatefilled").contents().find('form').serialize()+'&_token='+_token+'&dossdoc='+dossier+'&templatedocument='+tempdoc,
                success:function(data){
                    //alert(JSON.stringify(data));
                    //console.log(data);
                    location.reload();
                }
            });
    });
     });







</script>

@section('footer_scripts')


    <script src="{{ asset('public/js/folderview.js') }}" />

@stop

<script src="https://cdn.jsdelivr.net/npm/places.js@1.16.4"></script>

<script>



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
