@extends('layouts.mainlayout')

<link rel="stylesheet" href="{{ asset('public/css/timelinestyle.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('public/css/timeline.css') }}" type="text/css">

@section('content')

<div class="row">
    <div class="col-md-8"></div>
    <div class="col-md-2"><a data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" style="float:right;margin-right:20px;margin-bottom:25px;padding:3px 3px 3px 3px;border:1px solid #4fc1e9;" href="{{action('EmailController@envoimail',$dossier->id)}}"><span role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Envoyer un email"  class="fa fa-fw fa-envelope-open fa-2x"></span></a></div>
    <div class="col-md-2"><a data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" style="float:right;margin-right:20px;margin-bottom:25px;padding:3px 3px 3px 3px;border:1px solid #4fc1e9;" href="{{action('EmailController@envoifax',$dossier->id)}}"><span role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Envoyer un Fax"  class="fa fa-fw fa-fax fa-2x"></span></a>
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
                                <i class="fas  fa-lg fa-envelope"></i>  Emails
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
                            <input type="hidden" name="iddossupdate" id="iddossupdate" value={{ $dossier->id }}>
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
                                                                                <input onchange="changing(this)" type="text" id="subscriber_name" name="subscriber_name" class="form-control" value={{ $dossier->subscriber_name }}  >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Prénom *</label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="subscriber_lastname" name="subscriber_lastname" class="form-control"  value={{ $dossier->subscriber_lastname }} >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Adresse étranger</label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="adresse_etranger" name="adresse_etranger" class="form-control"   value={{ $dossier->adresse_etranger }} >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Bénéficaire </label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="beneficiaire" name="beneficiaire" class="form-control"   value={{ $dossier->beneficiaire }} >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Prénom Bénéficaire</label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="prenom_benef" name="prenom_benef" class="form-control"   value={{ $dossier->prenom_benef }} >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Tel mobile 1</label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="subscriber_phone_cell" name="subscriber_phone_cell" class="form-control"  value={{ $dossier->subscriber_phone_cell }}  >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Tel mobile 2</label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="subscriber_phone_domicile" name="subscriber_phone_domicile" class="form-control"   value={{ $dossier->subscriber_phone_domicile }} >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Tel Autre </label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="subscriber_phone_home" name="subscriber_phone_home" class="form-control"   value={{ $dossier->subscriber_phone_home }} >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Tel autre 2</label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="subscriber_phone_4" name="subscriber_phone_4" class="form-control"   value={{ $dossier->subscriber_phone_4 }} >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">To </label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="to" name="to" class="form-control"   value={{ $dossier->to }} >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Guide</label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)"  type="text" id="to_guide" name="to_guide" class="form-control"   value={{ $dossier->to_guide }} >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Tel </label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)"  type="text" id="to_phone" name="to_phone" class="form-control"   value={{ $dossier->to_phone }} >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Date arrivée </label>
                                                                            <input onchange="changing(this)"  data-format="dd-MM-yyyy hh:mm:ss" placeholder="jj-mm-aaaa" class="form-control datepicker-default form-control" name="initial_arrival_date" id="initial_arrival_date" type="text"   value={{ $dossier->initial_arrival_date }} >
                                                                        </div>

                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Départ prévu</label>
                                                                            <input onchange="changing(this)"  data-format="dd-MM-yyyy hh:mm:ss" placeholder="jj-mm-aaaa" class="form-control datepicker-default form-control" name="departure" id="departure" type="text"   value={{ $dossier->departure }} >
                                                                        </div>

                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Destination </label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="destination" name="destination" class="form-control"   value={{ $dossier->destination }} >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Adresse Tunisie </label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)"  type="text" id="subscriber_local_address" name="subscriber_local_address" class="form-control"   value={{ $dossier->subscriber_local_address }} >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Chambre</label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)"  type="text" id="subscriber_local_address_ch" name="subscriber_local_address_ch" class="form-control"   value={{ $dossier->subscriber_local_address_ch }} >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Tel Chambre</label>
                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="tel_chambre" name="tel_chambre" class="form-control"   value={{ $dossier->tel_chambre }} >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Mail</label>
                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="email" id="subscriber_mail1" name="subscriber_mail1" class="form-control" placeholder="mail 1"   value={{ $dossier->subscriber_mail1 }} >
                                                                            </div><br>
                                                                            <span id="mail2">
                                                                                            <input onchange="changing(this)"  type="text" name="email1" class="form-control" id="subscriber_mail2" placeholder="mail 2"   value={{ $dossier->subscriber_mail2 }} ><br>
                                                                                            <span id="mail3">
                                                                                                <input onchange="changing(this)"  type="text" name="subscriber_mail3" class="form-control" id="subscriber_mail3" placeholder="mail 3"   value={{ $dossier->subscriber_mail3 }} ></span></span>
                                                                        </div>

                                                                    </div>

                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="panel panel-success">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <a class="accordion-toggle" data-toggle="collapse">
                                                                Info Dossier</a>
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                            <!--                                    </div>-->
                                            <div class="panel-collapse collapse in">
                                                <div class="panel-body">
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Type de dossier</label>
                                                                    <select  onchange="changing(this);location.reload();"  id="type_dossier" name="type_dossier" class="form-control js-example-placeholder-single">
                                                                        <option <?php if ($dossier->type_dossier =='Medical'){echo 'selected="selected"';} ?> value="Medical">Medical</option>
                                                                        <option <?php if ($dossier->type_dossier =='Technique'){echo 'selected="selected"';} ?> value="Technique">Technique</option>
                                                                        <option <?php if ($dossier->type_dossier =='Mixte'){echo 'selected="selected"';} ?> value="Mixte">Mixte</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
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
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Statut</label>

                                                                    <div class="input-group-control">
                                                                        <input type="text" value="En cours" id="current_status" name="current_status" class="form-control" disabled=""  value={{ $dossier->current_status }} >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Ouvert le </label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)" type="text" value="" id="opened_by_date" name="" class="form-control" disabled=""  value={{ $dossier->opened_by_date }} >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Réf Dossier</label>

                                                                    <div class="input-group-control">
                                                                        <input  type="text" id="reference_medic" name="reference_medic" class="form-control" disabled=""   value={{ $dossier->reference_medic }} >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="complexite"> Degré de complexité</label>
                                                                    <select onchange="changing(this)" class="form-control" name="complexite" id="complexite"  >
                                                                        <option <?php if ($dossier['complexite'] ==1){echo 'selected="selected"';}?> value="1">1</option>
                                                                        <option <?php if ($dossier['complexite'] ==2){echo 'selected="selected"';}?>value="2">2</option>
                                                                        <option <?php if ($dossier['complexite'] ==3){echo 'selected="selected"';}?>value="3">3</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="complexite"> Montant total des prestations</label>
                                                                    <input onchange="changing(this)" type="text" readonly="readonly" class="form-control" name="montant_tot" id="montant_tot"   value={{ $dossier->montant_tot }} >
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
                                                                <label>Client </label>
                                                                <select onchange="changing(this);location.reload()" id="customer_id" name="customer_id" class="form-control js-example-placeholder-single"   value="{{ $dossier->customer_id }}" >
                                                                    <option value="0">Sélectionner..... </option>

                                                                    @foreach($clients as $cl  )
                                                                        <option
                                                                                @if($dossier->customer_id==$cl->id)selected="selected"@endif

                                                                        value="{{$cl->id}}">{{$cl->name}}</option>

                                                                    @endforeach


                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Référence *</label>

                                                                <div class="input-group-control">
                                                                    <input  readonly type="text" id="customer" name="customer_id" class="form-control"   value="{{ $dossier->customer_id }}" >
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Telephone </label>

                                                                <div class="input-group-control">
                                                                    <input onchange="changing(this)"  type="text" id="tel" name="tel" class="form-control" readonly=""   value={{ $dossier->tel }} >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Fax</label>

                                                                <div class="input-group-control">
                                                                    <input onchange="changing(this)"  type="text" id="fax" name="fax" class="form-control" readonly=""   value={{ $dossier->fax }} >
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Adresse de facturation (si différente) </label>

                                                                <div class="input-group-control">
                                                                    <input onchange="changing(this)" type="text" id="adresse_facturation" name="adresse_facturation" class="form-control"   value={{ $dossier->adresse_facturation }} >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Mail</label>

                                                                <div class="input-group-control">
                                                                    <input onchange="changing(this)" type="text" id="mail" name="mail" class="form-control" readonly=""   value={{ $dossier->mail }} >
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">

                                                                <label for="franchise" class=""> Franchise &nbsp;&nbsp;
                                                                    <div class="radio" id="uniform-franchise"><span><input onclick="changing(this)" type="radio" name="franchise" id="franchise" value="1" <?php if ($dossier->franchise ==1){echo 'checked';} ?>></span></div> Oui
                                                                </label>

                                                                <label for="nonfranchise" class="">

                                                                    <div class="radio" id="uniform-nonfranchise"><span class="checked"><input onclick="disabling('franchise')" type="radio" name="franchise" id="nonfranchise" value="0"  <?php if ($dossier->franchise ==0){echo 'checked';} ?> ></span></div> Non
                                                                </label>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label">Montant Franchise
                                                                </label>

                                                                <div class="input-group-control">
                                                                    <input onchange="changing(this)"  type="text" id="montant_franchise" name="montant_franchise" class="form-control" style="width: 100px;" placeholder="Montant"   value={{ $dossier->montant_franchise }} >
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
                                                                        <div class="radio" id="uniform-is_hospitalized"><span><input onclick="changing(this)"  type="radio" name="is_hospitalized" id="is_hospitalized" value="1" <?php if ($dossier->is_hospitalized ==1){echo 'checked';} ?> ></span></div> Oui
                                                                    </label> <label for="nonis_hospitalized" class=""> <div class="radio" id="uniform-nonis_hospitalized"><span class=""><input onclick="disabling('is_hospitalized')" type="radio" name="is_hospitalized" id="nonis_hospitalized" value="0"  <?php if ($dossier->is_hospitalized ==0){echo 'checked';} ?>  ></span></div> Non
                                                                    </label>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Adresse Hopital </label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)"  type="text" id="hospital_address" name="hospital_address" class="form-control"   value={{ $dossier->hospital_address }} >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Ch</label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)"  type="text" id="hospital_ch" name="hospital_ch" class="form-control"   value={{ $dossier->hospital_ch }} >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Tel </label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)"  type="text" id="hospital_phone" name="hospital_phone" class="form-control"   value={{ $dossier->hospital_phone }} >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Médecin Traitant </label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)"  type="text" id="medecin_traitant" name="medecin_traitant" class="form-control"   value={{ $dossier->medecin_traitant }} >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Adresse Hopital2 </label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)" type="text" id="hospital_address2" name="hospital_address2" class="form-control"   value={{ $dossier->hospital_address2 }} >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Ch2</label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)" type="text" id="hospital_ch2" name="hospital_ch2" class="form-control"   value={{ $dossier->hospital_ch2 }} >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Tel2 </label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)" type="text" id="hospital_phone2" name="hospital_phone2" class="form-control"   value={{ $dossier->hospital_phone2 }} >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Médecin Traitant2 </label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)" type="text" id="medecin_traitant2" name="medecin_traitant2" class="form-control" value={{ $dossier->medecin_traitant2 }} >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Adresse Hopital3 </label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)"  type="text" id="hospital_address3" name="hospital_address3" class="form-control"  value={{ $dossier->hospital_address3 }} >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Ch3</label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)" type="text" id="hospital_ch3" name="hospital_ch3" class="form-control"  value={{ $dossier->hospital_ch3 }} >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Tel3 </label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)"  type="text" id="hospital_phone3" name="hospital_phone3" class="form-control"  value={{ $dossier->hospital_phone3 }} >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="form-group">
                                                                    <label for="inputError" class="control-label">Médecin Traitant3 </label>

                                                                    <div class="input-group-control">
                                                                        <input onchange="changing(this)" type="text" id="medecin_traitant3" name="medecin_traitant3" class="form-control"   value={{ $dossier->medecin_traitant3 }} >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel panel-success " id="technique" style=" <?php if ($dossier->type_dossier =='Medical'){echo 'display:none';}?>;"">
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
                                                                <label for="inputError" class="control-label"> Type et marque du véhicule</label>

                                                                <div class="input-group-control">
                                                                    <input onchange="changing(this)" type="text" id="vehicule_type" name="vehicule_type" class="form-control"   value={{ $dossier->vehicule_type }} >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Immatriculation</label>

                                                                <div class="input-group-control">
                                                                    <input onchange="changing(this)"  type="text" id="vehicule_immatriculation" name="vehicule_immatriculation" class="form-control"   value={{ $dossier->vehicule_immatriculation }} >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Lieu d'immobilisation </label>

                                                                <div class="input-group-control">
                                                                    <input onchange="changing(this)" type="text" id="lieu_immobilisation" name="lieu_immobilisation" class="form-control"   value={{ $dossier->lieu_immobilisation }} >
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label"> Adresse véhicule</label>

                                                                <div class="input-group-control">
                                                                    <input onchange="changing(this)" type="text" id="vehicule_address" name="vehicule_address" class="form-control"   value={{ $dossier->vehicule_address }} >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Adresse véhicule2</label>

                                                                <div class="input-group-control">
                                                                    <input onchange="changing(this)"  type="text" id="vehicule_address2" name="vehicule_address2" class="form-control"   value={{ $dossier->vehicule_address2 }}>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Tel </label>

                                                                <div class="input-group-control">
                                                                    <input onchange="changing(this)"  type="text" id="vehicule_phone" name="vehicule_phone" class="form-control"   value={{ $dossier->vehicule_phone }} >
                                                                </div>
                                                            </div>
                                                        </div>
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
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group form-md-line-input form-md-floating-label">
                                                                <label for="form_control_1">Observation dossier<span class="required"> * </span></label>
                                                                <textarea onchange="changing(this)"  rows="3" class="form-control" name="observation" id="observation">  {{ $dossier->observation }} </textarea>
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
                                <!--   <div class="form-actions right">
                                       <button type="button" id="editDos" class="btn btn-sm btn-info">Enregistrer</button>
                                   </div>-->
                            </div>
                        </form>
                    </div>



                    <!--
                                    <div class="tab-pane" id="tab_prestations">
                                        <div class="row">
                                            <div class="col-md-12">

                                                <div class="portlet light">
                                                    <div class="portlet-title">
                                                        <div class="caption">
                                                            <i class="icon-list"></i>
                                                            <span class="caption-subject bold uppercase"> Liste des prestations</span>
                                                        </div>
                                                        <div class="actions">
                                                            <a href="javascript:;" class="btn btn-circle btn-default" id="addPrestation"><i class="fa fa-plus"></i> Ajouter </a>
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
                                                        <div id="pres_ajax_wrapper" class="dataTables_wrapper no-footer"><div class="row"><div class="col-md-12 align-right"><div class="DTTT btn-group"><a class="btn btn-default DTTT_button_print" id="ToolTables_pres_ajax_3" title="View print view"><span>Print</span></a></div></div></div><div class="row"><div class="col-md-6 col-sm-12"><div class="dataTables_length" id="pres_ajax_length"><label>Afficher <select name="pres_ajax_length" aria-controls="pres_ajax" class="form-control input-xsmall input-inline"><option value="50">50</option><option value="-1">All</option></select> éléments</label></div></div><div class="col-md-6 col-sm-12"><div id="pres_ajax_filter" class="dataTables_filter"><label>Rechercher&nbsp;:<input type="search" class="form-control input-small input-inline" placeholder="" aria-controls="pres_ajax"></label></div></div></div><div class="table-scrollable"><table class="table table-striped table-bordered table-hover dataTable no-footer" id="pres_ajax" role="grid" aria-describedby="pres_ajax_info" style="width: 100%;">
                                                            <thead>
                                                            <tr role="row"><th class="sorting_asc" tabindex="0" aria-controls="pres_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Type de prestation
                                                                : activer pour trier la colonne par ordre croissant" aria-sort="ascending" style="width: 0px;">
                                                                    Type de prestation
                                                                </th><th class="sorting" tabindex="0" aria-controls="pres_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Prestataire
                                                                : activer pour trier la colonne par ordre croissant" style="width: 0px;">
                                                                    Prestataire
                                                                </th><th class="sorting" tabindex="0" aria-controls="pres_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Prix
                                                                : activer pour trier la colonne par ordre croissant" style="width: 0px;">
                                                                    Prix
                                                                </th><th class="sorting" tabindex="0" aria-controls="pres_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Parvenue
                                                                : activer pour trier la colonne par ordre croissant" style="width: 0px;">
                                                                    Parvenue
                                                                </th><th class="sorting" tabindex="0" aria-controls="pres_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Facturée au client
                                                                : activer pour trier la colonne par ordre croissant" style="width: 0px;">
                                                                    Facturée au client
                                                                </th><th class="sorting" tabindex="0" aria-controls="pres_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Actions
                                                                : activer pour trier la colonne par ordre croissant" style="width: 0px;">
                                                                    Actions
                                                                </th></tr>
                                                            </thead>
                                                            <tbody>

                                                            <tr role="row" class="odd"><td class="sorting_1"><center>Ambulances</center></td><td><center>Medic' Multiservices</center></td><td><center>29d (75% du tarif) déplacement</center></td><td><center><i class="font-red fa fa-times"></i></center></td><td><center><i class="font-green fa fa-check"></i></center></td><td><center><div class="btn-group"><center>
          <a data-toggle="tooltip" data-original-title="Editer" data-idpres="67959" class="update_link_pres yellow filter-submit margin-bottom"><i class="fa fa-pencil font-yellow-crusta"></i></a>&nbsp;&nbsp;
          <a data-toggle="tooltip" data-original-title="Annuler" data-idpres="67959" class="delete_link_pres red filter-submit margin-bottom"><i class="fa fa-trash font-red-thunderbird"></i></a>
          </center></div></center></td></tr></tbody>
                                                        </table></div><div class="row"><div class="col-md-5 col-sm-12"><div class="dataTables_info" id="pres_ajax_info" role="status" aria-live="polite">Affichage de l'élément 1 à 1 sur 1 éléments</div></div><div class="col-md-7 col-sm-12"><div class="dataTables_paginate paging_simple_numbers" id="pres_ajax_paginate"><ul class="pagination"><li class="paginate_button previous disabled" aria-controls="pres_ajax" tabindex="0" id="pres_ajax_previous"><a href="http://197.14.53.86:10080/medic/agent/paneldossier/view/37301#">Précédent</a></li><li class="paginate_button active" aria-controls="pres_ajax" tabindex="0"><a href="http://197.14.53.86:10080/medic/agent/paneldossier/view/37301#">1</a></li><li class="paginate_button next disabled" aria-controls="pres_ajax" tabindex="0" id="pres_ajax_next"><a href="http://197.14.53.86:10080/medic/agent/paneldossier/view/37301#">Suivant</a></li></ul></div></div></div></div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_exchange">
                                        <div class="mt-timeline-2">
                                            <div class="mt-timeline-line border-grey-steel" style="border-color: #e9edef!important;z-index:0 !important"></div>
                                            <ul class="mt-container" id="ul_timeline"></ul>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_checklists">
                                        <h3 class="col-md-10">Checklists</h3>
                                        <div class="actions col-md-2 text-right">
                                            <div class="btn-group">
                                                <a class="add_checklist btn btn-icon-only btn-circle green">
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                            </div>
                                        </div>
                                        &nbsp;

                                        <hr>
                                        <div class="col-md-12">
                                                                                </div>

                                    </div>


                                    <div class="tab-pane" id="tab_rappels">
                                        <div class="row">
                                            <div class="col-md-12">

                                                <div class="portlet light">
                                                    <div class="portlet-title">
                                                        <div class="caption">
                                                            <i class="fa fa-bell-o"></i><span class="caption-subject bold uppercase"> Liste Rappels</span>
                                                        </div>
                                                        <div class="tools">
                                                            <button id="add_reminder" data-dossier_ide="37301" class="add_reminder btn btn-circle btn-default"><i class="fa fa-plus"></i> Rappel</button>
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
                                                        <div id="table_reminders_wrapper" class="dataTables_wrapper no-footer"><div class="row"><div class="col-md-12"><div class="DTTT btn-group"><a class="btn btn-default DTTT_button_print" id="ToolTables_table_reminders_3" title="View print view"><span>Print</span></a></div></div></div><div class="row"><div class="col-md-6 col-sm-12"><div class="dataTables_length" id="table_reminders_length"><label>Afficher <select name="table_reminders_length" aria-controls="table_reminders" class="form-control input-xsmall input-inline"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="-1">All</option></select> éléments</label></div></div><div class="col-md-6 col-sm-12"><div id="table_reminders_filter" class="dataTables_filter"><label>Rechercher&nbsp;:<input type="search" class="form-control input-small input-inline" placeholder="" aria-controls="table_reminders"></label></div></div></div><div class="table-scrollable"><table class="table table-striped table-hover dataTable no-footer" id="table_reminders" role="grid" aria-describedby="table_reminders_info" style="width: 100%;">
                                                            <thead>
                                                            <tr role="row"><th class="sorting_asc" tabindex="0" aria-controls="table_reminders" rowspan="1" colspan="1" aria-label="
                                                                    Rappel
                                                                : activer pour trier la colonne par ordre croissant" aria-sort="ascending" style="width: 0px;">
                                                                    Rappel
                                                                </th><th class="sorting" tabindex="0" aria-controls="table_reminders" rowspan="1" colspan="1" aria-label="
                                                                    Dossier
                                                                : activer pour trier la colonne par ordre croissant" style="width: 0px;">
                                                                    <center>Dossier</center>
                                                                </th><th class="sorting" tabindex="0" aria-controls="table_reminders" rowspan="1" colspan="1" aria-label="
                                                                    Degré d&#39;urgence
                                                                : activer pour trier la colonne par ordre croissant" style="width: 0px;">
                                                                    <center>Degré d'urgence</center>
                                                                </th><th class="sorting" tabindex="0" aria-controls="table_reminders" rowspan="1" colspan="1" aria-label="
                                                                    Statut
                                                                : activer pour trier la colonne par ordre croissant" style="width: 0px;">
                                                                    <center>Statut</center>
                                                                </th><th class="sorting" tabindex="0" aria-controls="table_reminders" rowspan="1" colspan="1" aria-label="
                                                                    Heure Rappel
                                                                : activer pour trier la colonne par ordre croissant" style="width: 0px;">
                                                                    <center>Heure Rappel</center>
                                                                </th><th class="sorting" tabindex="0" aria-controls="table_reminders" rowspan="1" colspan="1" aria-label="
                                                                    Crée Par
                                                                : activer pour trier la colonne par ordre croissant" style="width: 0px;">
                                                                    <center>Crée Par</center>
                                                                </th><th class="sorting" tabindex="0" aria-controls="table_reminders" rowspan="1" colspan="1" aria-label="
                                                                    Annulé
                                                                : activer pour trier la colonne par ordre croissant" style="width: 0px;">
                                                                    <center>Annulé</center>
                                                                </th><th class="sorting" tabindex="0" aria-controls="table_reminders" rowspan="1" colspan="1" aria-label="
                                                                    Actions
                                                                : activer pour trier la colonne par ordre croissant" style="width: 0px;">
                                                                    <center>Actions</center>
                                                                </th></tr>
                                                            </thead>
                                                            <tbody>

                                                            <tr class="odd"><td valign="top" colspan="8" class="dataTables_empty">Aucune donnée disponible dans le tableau</td></tr></tbody>
                                                        </table></div><div class="row"><div class="col-md-5 col-sm-12"><div class="dataTables_info" id="table_reminders_info" role="status" aria-live="polite">Affichage de l'élément 0 à 0 sur 0 élément</div></div><div class="col-md-7 col-sm-12"><div class="dataTables_paginate paging_simple_numbers" id="table_reminders_paginate"><ul class="pagination"><li class="paginate_button previous disabled" aria-controls="table_reminders" tabindex="0" id="table_reminders_previous"><a href="http://197.14.53.86:10080/medic/agent/paneldossier/view/37301#">Précédent</a></li><li class="paginate_button next disabled" aria-controls="table_reminders" tabindex="0" id="table_reminders_next"><a href="http://197.14.53.86:10080/medic/agent/paneldossier/view/37301#">Suivant</a></li></ul></div></div></div></div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_comptesrendus">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="portlet light">
                                                    <div class="portlet-title">
                                                        <div class="caption">
                                                            <i class="icon-list"></i>
                                                            <span class="caption-subject bold uppercase"> Liste des comptes rendus</span>
                                                        </div>
                                                        <div class="actions">
                                                            <a href="javascript:;" class="btn btn-circle btn-default" id="addCompteRendu"><i class="fa fa-plus"></i> Ajouter </a>
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
                                                        <div id="cr_ajax_wrapper" class="dataTables_wrapper no-footer"><div class="row"><div class="col-md-6 col-sm-6"><div class="dataTables_length" id="cr_ajax_length"><label>Afficher <select name="cr_ajax_length" aria-controls="cr_ajax" class="form-control input-xsmall input-inline"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> enregistrements</label></div></div><div class="col-md-6 col-sm-6"><div id="cr_ajax_filter" class="dataTables_filter"><label>Rechercher&nbsp;:<input type="search" class="form-control input-small input-inline" placeholder="" aria-controls="cr_ajax"></label></div></div></div><div class="table-scrollable"><table class="table table-striped table-bordered table-hover dataTable no-footer" id="cr_ajax" role="grid" aria-describedby="cr_ajax_info" style="width: 100%;">
                                                            <thead>
                                                            <tr role="row"><th class="sorting_asc" tabindex="0" aria-controls="cr_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Date &amp;amp; Heure
                                                                : activer pour trier la colonne par ordre croissant" aria-sort="ascending">
                                                                    Date &amp; Heure
                                                                </th><th class="sorting" tabindex="0" aria-controls="cr_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Interlocuteur
                                                                : activer pour trier la colonne par ordre croissant">
                                                                    Interlocuteur
                                                                </th><th class="sorting" tabindex="0" aria-controls="cr_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Signé par
                                                                : activer pour trier la colonne par ordre croissant">
                                                                    Signé par
                                                                </th><th class="sorting" tabindex="0" aria-controls="cr_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Compte rendu
                                                                : activer pour trier la colonne par ordre croissant">
                                                                    Compte rendu
                                                                </th><th class="sorting" tabindex="0" aria-controls="cr_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Média
                                                                : activer pour trier la colonne par ordre croissant">
                                                                    Média
                                                                </th></tr>
                                                            </thead>
                                                            <tbody>

                                                            <tr class="odd"><td valign="top" colspan="5" class="dataTables_empty">Aucune donnée disponible dans le tableau</td></tr></tbody>
                                                        </table></div><div class="row"><div class="col-md-5 col-sm-5"><div class="dataTables_info" id="cr_ajax_info" role="status" aria-live="polite">Affichage de l'élement 0 à 0 sur 0 éléments</div></div><div class="col-md-7 col-sm-7"><div class="dataTables_paginate paging_simple_numbers" id="cr_ajax_paginate"><ul class="pagination"><li class="paginate_button previous disabled" aria-controls="cr_ajax" tabindex="0" id="cr_ajax_previous"><a href="http://197.14.53.86:10080/medic/agent/paneldossier/view/37301#"><i class="fa fa-angle-left"></i></a></li><li class="paginate_button next disabled" aria-controls="cr_ajax" tabindex="0" id="cr_ajax_next"><a href="http://197.14.53.86:10080/medic/agent/paneldossier/view/37301#"><i class="fa fa-angle-right"></i></a></li></ul></div></div></div></div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab_attachments">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="portlet light">
                                                    <div class="portlet-title">
                                                        <div class="caption">
                                                            <i class="icon-list"></i>
                                                            <span class="caption-subject bold uppercase"> Liste des pièces jointes</span>
                                                        </div>
                                                        <div class="actions">
                                                            <a href="javascript:;" class="btn btn-circle btn-default" id="addAttachment"><i class="fa fa-plus"></i> Ajouter </a>
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
                                                        <div id="attach_ajax_wrapper" class="dataTables_wrapper no-footer"><div class="row"><div class="col-md-6 col-sm-6"><div class="dataTables_length" id="attach_ajax_length"><label>Afficher <select name="attach_ajax_length" aria-controls="attach_ajax" class="form-control input-xsmall input-inline"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> enregistrements</label></div></div><div class="col-md-6 col-sm-6"><div id="attach_ajax_filter" class="dataTables_filter"><label>Rechercher&nbsp;:<input type="search" class="form-control input-small input-inline" placeholder="" aria-controls="attach_ajax"></label></div></div></div><div class="table-scrollable"><table class="table table-striped table-bordered table-hover dataTable no-footer" id="attach_ajax" role="grid" aria-describedby="attach_ajax_info" style="width: 100%;">
                                                            <thead>
                                                            <tr role="row"><th class="sorting_desc" tabindex="0" aria-controls="attach_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Date &amp;amp; Heure
                                                                : activer pour trier la colonne par ordre croissant" aria-sort="descending">
                                                                    Date &amp; Heure
                                                                </th><th class="sorting" tabindex="0" aria-controls="attach_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Description
                                                                : activer pour trier la colonne par ordre croissant">
                                                                    Description
                                                                </th><th class="sorting" tabindex="0" aria-controls="attach_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Titre
                                                                : activer pour trier la colonne par ordre croissant">
                                                                    Titre
                                                                </th><th class="sorting" tabindex="0" aria-controls="attach_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Média
                                                                : activer pour trier la colonne par ordre croissant">
                                                                    Média
                                                                </th><th class="sorting" tabindex="0" aria-controls="attach_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Type
                                                                : activer pour trier la colonne par ordre croissant">
                                                                    Type
                                                                </th><th class="sorting" tabindex="0" aria-controls="attach_ajax" rowspan="1" colspan="1" aria-label="
                                                                    Actions
                                                                : activer pour trier la colonne par ordre croissant">
                                                                    Actions
                                                                </th></tr>
                                                            </thead>
                                                            <tbody>

                                                            <tr role="row" class="odd"><td class="sorting_1">2019-03-28 11:58:32</td><td>ODM Medic </td><td> <a target="_blank" href="http://197.14.53.86:10080/medic/assets/uploads/odm_medic_20190328_115829.pdf">odm_medic_20190328_115829.pdf</a></td><td>Mail</td><td>Envoyé</td><td><div class="btn-group"><center><a data-idattach="736495" class="update_link_attach yellow filter-submit margin-bottom"><i class="fa fa-pencil font-yellow"></i></a>&nbsp;&nbsp;<a data-idattach="736495" class="delete_link_attach red filter-submit margin-bottom"><i class="fa fa-trash font-red-thunderbird"></i></a></center></div></td></tr></tbody>
                                                        </table></div><div class="row"><div class="col-md-5 col-sm-5"><div class="dataTables_info" id="attach_ajax_info" role="status" aria-live="polite">Affichage de l'élement 1 à 1 sur 1 éléments</div></div><div class="col-md-7 col-sm-7"><div class="dataTables_paginate paging_simple_numbers" id="attach_ajax_paginate"><ul class="pagination"><li class="paginate_button previous disabled" aria-controls="attach_ajax" tabindex="0" id="attach_ajax_previous"><a href="http://197.14.53.86:10080/medic/agent/paneldossier/view/37301#"><i class="fa fa-angle-left"></i></a></li><li class="paginate_button active" aria-controls="attach_ajax" tabindex="0"><a href="http://197.14.53.86:10080/medic/agent/paneldossier/view/37301#">1</a></li><li class="paginate_button next disabled" aria-controls="attach_ajax" tabindex="0" id="attach_ajax_next"><a href="http://197.14.53.86:10080/medic/agent/paneldossier/view/37301#"><i class="fa fa-angle-right"></i></a></li></ul></div></div></div></div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
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
                                    <p >

                                        <?php if ($communin['boite']==0)
                                        {  echo '<span class="commsujet" style="font-size:12px"><B>Emetteur: </B>'. $communin['emetteur'].'</span>';
                                        }
                                        ?>

                                        <?php if ($communin['commentaire']!=null)
                                        {  echo '<span style="font-size:12px"><B>Commentaire: </B>'. $communin['commentaire'].'</span>';
                                        }
                                        ?>


                                        <span class="cd-date">

                                            <?php echo /*date('d/m/Y H:i', (*/$communin['reception']/*))*/ ; ?> <i class="fa fa-fw fa-clock-o"></i>


                                        </span>
                                        <?php if($communin['type']=="email") {
                                        ?> <span style="font-size:12px"><i class="fa fa-fw fa-paperclip"></i>(<?php echo $communin['nb_attach'];?>) Attachements</span><br>
                                        <?php }  ?>

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
                                                {{$envoye->sujet}}
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
                <?php use \App\Http\Controllers\PrestationsController;     ?>
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

                <table class="table table-striped" id="mytable" style="width:100%;margin-top:15px;">
                    <thead>
                    <tr id="headtable">
                        <th style="">Date</th>
                        <th style="">Titre</th>

                        <th style="">type</th>
                        <th style="">Boite</th>
                    </tr>

                    </thead>
                    <tbody>
                      @foreach($attachements as $attach)

                        <tr>

                            <td style=";"><small><?php echo $attach->created_at;?></small></td>
                            <td style=";"><small><?php /* if ($attach->dossier!=null) {echo 'Fichier externe';}else{*/ echo $attach->nom; /*}*/ ?></small></td>

                            <td style=";"><small><?php
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
                <div style="">
                    <button style="float:right;margin-top:10px;margin-bottom: 15px;margin-right: 20px" id="addemail" class="btn btn-md btn-success"   data-toggle="modal" data-target="#createemail"><b><i class="fas fa-plus"></i> Ajouter un email</b></button>


                </div>
                <table class="table table-striped" id="mytable2" style="width:100%;margin-top:15px;">
                    <thead>
                    <tr id="headtable">
                        <th style="">Email</th>
                        <th style="">Description</th>
                     </tr>

                    </thead>
                    <tbody>
                    @foreach($emails as $email)
                        <tr>
                            <td style=";"><?php echo $email->champ; ?></td>
                            <td style=";"><?php echo $email->description; ?></td>
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
                            <td style=";"><a  href="{{ URL::asset('storage'.'app/'.$doc->emplacement) }}" class="btn"><i class="fa fa-download"></i> Télécharger</a></td>
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
                <h5 class="modal-title" id="exampleModalLabel">Ajouter un nouveau Client</h5>

            </div>
            <div class="modal-body">
                <div class="card-body">



                    <div class="form-group">
                        {{ csrf_field() }}

                        <form id="addpresform" novalidate="novalidate">

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
                                            <option      value="<?php echo $aKeyG->id;?>"> <?php echo $aKeyG->name;?></option>
                                        @endforeach

                                    </select>
                                    </div>
                                </div>
                            <div class="form-group">
                                <label class="control-label">Prestataire</label>
                                <input type="hidden" name="pres_id" id="pres_id" data-required="1" required="" aria-required="true" value="53">
                                <input type="hidden" name="is_forced" id="is_forced" value="0">
                                <div class="input-group" style="display:none" id="all_pres_div">
                                <span class="input-group-addon btn green font-white" onclick="unForceSelectPres()">
                                    Retour
                                </span>
                                    <div class="select2-container form-control" id="s2id_all_pres_id"><a href="javascript:void(0)" class="select2-choice" tabindex="-1">   <span class="select2-chosen" id="select2-chosen-8">&nbsp;</span><abbr class="select2-search-choice-close"></abbr>   <span class="select2-arrow" role="presentation"><b role="presentation"></b></span></a><label for="s2id_autogen8" class="select2-offscreen"></label><input class="select2-focusser select2-offscreen" type="text" aria-haspopup="true" role="button" aria-labelledby="select2-chosen-8" id="s2id_autogen8"><div class="select2-drop select2-display-none select2-with-searchbox">   <div class="select2-search">       <label for="s2id_autogen8_search" class="select2-offscreen"></label>       <input type="text" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" class="select2-input" role="combobox" aria-expanded="true" aria-autocomplete="list" aria-owns="select2-results-8" id="s2id_autogen8_search" placeholder="">   </div>   <ul class="select2-results" role="listbox" id="select2-results-8">   </ul></div></div><select class="form-control select2-offscreen" id="all_pres_id" tabindex="-1" title="">
                                    </select>
                                </div>


                                <div class="col-md-12" style="" id="autoPressDiv">
                                    <div class="well">
                                        <address id="autoPressFound">
                                            <strong id="autoPresName">Clinique Cardio-Vasculaire</strong><br>
                                            <i class="fa fa-envelope"></i> <span id="autoPresAddress"></span><br>
                                            <i class="fa fa-phone"></i> <span id="autoPresPhone">71908000</span><br>
                                            <i class="fa fa-mobile"></i> <span id="autoPresCell">50846277- 58573530  Lilia</span><br>
                                        </address>
                                        <address id="autoPressNotFound" style="display:none">
                                            <strong>Aucun prestataire disponible. Cliquez pour recommencer.</strong>
                                        </address>
                                        <p>
                                            <button type="button" class="btn btn-xs green" onclick="selectNewPres();"><i class="fa fa-refresh" style="cursor:pointer"></i> Sélectionner le suivant</button>
                                            <button type="button" class="btn btn-xs yellow-lemon" onclick="forceSelectPres();"><i class="fa fa-check" style="cursor:pointer"></i> Sélection manuelle</button>
                                        </p>
                                    </div>
                                </div>
                            </div>

                                 <input type="hidden" id="par" value="<?php echo $iduser;?>">


                            <div class="form-group">
                                <label class="control-label">Date de prestation <span class="required" aria-required="true"> * </span></label>
                                <input value='<?php echo date('d/m/Y'); ?>' class="form-control datepicker-default" name="pres_date" id="pres_date" data-required="1" required="" aria-required="true">
                            </div>

                        </form>
                    </div>





                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" id="add" class="btn btn-primary">Ajouter</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Email-->
<div class="modal fade" id="createemail" tabindex="-1" role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModal2">Ajouter un Email </h5>

            </div>
            <div class="modal-body">
                <div class="card-body">


                    <div class="form-group">
                        {{ csrf_field() }}

                        <form id="addemailform" novalidate="novalidate">

                            <input id="parent" name="parent" type="hidden" value="{{ $dossier->id}}">
                            <div class="form-group " >
                                <label for="emaildoss">Email</label>
                                <div class=" row  ">
                                    <input class="form-control" type="email" required id="emaildoss"/>

                                </div>
                            </div>

                            <div class="form-group ">
                                <label for="DescrEmail">Description</label>
                                <div class="row">
                                    <input type="text" class="form-control"  id="DescrEmail" />

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
<!-- Modal Document-->
<div class="modal fade" id="generatedoc" tabindex="-1" role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModal2">Générer un document </h5>

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
                                                    <option>PRISE EN CHARGE DEDOUANNEMENT</option>
                                                </select>
                                </div>
                            </div>

                            <!--<div class="form-group ">
                                <label for="DescrEmail">Description</label>
                                <div class="row">
                                    <input type="text" class="form-control"  id="DescrEmail" />

                                </div>
                            </div>-->

                        </form>
                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" id="gendoc" class="btn btn-primary">Générer</button>
            </div>
        </div>
    </div>
</div>


@endsection

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>


<script>
    /*
    $(function () {

    $('.itemName').select2({
        filter: true,
        language: {
            noResults: function () {
                return 'Pas de résultats';
            }
        }

    });

    $('#gouvcouv').select2({
        filter: true,
        language: {
            noResults: function () {
                return 'Pas de résultats';
            }
        }

    });

    }); // $ function

*/
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
        //  var type = $('#type').val();
        var dossier = $('#iddossupdate').val();
        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('dossiers.updating') }}",
            method: "GET",
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


    $('#emailadd').click(function(){
        var parent = $('#parent').val();
        var champ = $('#emaildoss').val();
        var description = $('#DescrEmail').val();
        if ((champ != '') )
        {
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('dossiers.addemail') }}",
                method:"POST",
                data:{parent:parent,champ:champ,description:description, _token:_token},
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
        if ((dossier != '') )
        {
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('dossiers.adddocument') }}",
                method:"POST",
                data:{dossier:dossier, _token:_token},
                success:function(data){

                    //   alert('Added successfully');
                    window.location =data;


                }
            });
        }else{
            // alert('ERROR');
        }
    });

    });







</script>


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
</style>

@section('footer_scripts')



@stop

