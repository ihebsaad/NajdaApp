@extends('layouts.dossierlayout')
<?php
use App\User ;

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


            <h2 style="margin-left:50px;">Créer un nouveau Dossier:</h2>

    </div>
    <section class="content form_layouts">

        <div class="form-group" style="margin-top:25px;">
            {{ csrf_field() }}

            <form id="updatedossform">
                <input type="hidden" name="iddossupdate" id="iddossupdate"  >

                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Type de dossier</label>
                            <select  onchange="changing(this);location.reload();"  id="type_dossier" name="type_dossier" class="form-control js-example-placeholder-single">
                                <option  value="Medical">Medical</option>
                                <option  value="Technique">Technique</option>
                                <option   value="Mixte">Mixte</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Affecté à </label>
                            <select id="type_affectation" name="type_affectation" class="form-control js-example-placeholder-single" readonly="readonly">
                                <option  value="Najda">Najda</option>
                                <option  value="VAT">VAT</option>
                                <option  value="MEDIC">MEDIC</option>
                                <option  value="Transport MEDIC">Transport MEDIC</option>
                                <option  value="Transport VAT">Transport VAT</option>
                                <option  value="Medic International">Medic International</option>
                                <option  value="Najda TPA">Najda TPA</option>
                                <option  value="Transport Najda">Transport Najda</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Client </label>
                            <select  id="customer_id" name="customer_id" class="form-control js-example-placeholder-single"    >
                                <option value="0">Sélectionner..... </option>

                                @foreach($clients as $cl  )
                                    <option

                                    value="{{$cl->id}}">{{$cl->name}}</option>

                                @endforeach


                            </select>
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Référence Client </label>
                            <input onchange="verifier();"  type="text" id="reference_customer" name="reference_customer" class="form-control"   >

                        </div>
                    </div>
                <!--    <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputError" class="control-label">Référence *</label>

                                        <div class="input-group-control">
                                            <input    type="text" id="customer" name="reference_customer" class="form-control"     >
                                        </div>
                                    </div>
                                </div>-->

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="complexite"> Degré de complexité</label>
                            <select   class="form-control" name="complexite" id="complexite"  >
                                <option  value="1">1</option>
                                <option  value="2">2</option>
                                <option  value="3">3</option>
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
                                                                    <input   type="text" id="subscriber_name" name="subscriber_name" class="form-control"    >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Prénom *</label>

                                                                <div class="input-group-control">
                                                                    <input   type="text" id="subscriber_lastname" name="subscriber_lastname" class="form-control"    >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="ben" class="control-label">bénéficiaire différent</label><br>

                                                                <label for="annule" class="">
                                                                    <div class="radio" id="uniform-actif">
                                                                                        <span class="checked">
                                                                               <input    type="checkbox"  id="benefdiff"   value="1"    onclick="showBen();" >
                                                                                        </span>Oui</div>
                                                                </label>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="row" id="bens"  style="display:none" >
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Bénéficaire </label>

                                                                <div class="input-group-control">
                                                                    <input   type="text" id="beneficiaire" name="beneficiaire" class="form-control"     >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Prénom Bénéficaire</label>

                                                                <div class="input-group-control">
                                                                    <input   type="text" id="prenom_benef" name="prenom_benef" class="form-control"     >
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Parenté </label>

                                                                <div class="input-group-control">
                                                                    <input   type="text" id="parente" name="parente" class="form-control"     >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1" style="padding-top:30px">
                                                            <span title="Afficher le bénéficiaire 2 " style="width:20px" class=" btn-md" id="btn01"><i class="fa fa-plus"></i> <i class="fa fa-minus"></i></span>
                                                        </div>

                                                    </div>
                                                    <div class="row" id="ben2"   style="display:none"    >
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Bénéficaire 2</label>

                                                                <div class="input-group-control">
                                                                    <input   type="text" id="beneficiaire2" name="beneficiaire" class="form-control"     >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Prénom Bénéficaire 2</label>

                                                                <div class="input-group-control">
                                                                    <input   type="text" id="prenom_benef2" name="prenom_benef" class="form-control"     >
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Parenté </label>

                                                                <div class="input-group-control">
                                                                    <input   type="text" id="parente2" name="parente" class="form-control"   >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1" style="padding-top:30px">
                                                            <span title="Afficher le bénéficiaire 3" style="width:20px" class=" btn-md" id="btn02"><i class="fa fa-plus"></i> <i class="fa fa-minus"></i></span>
                                                        </div>
                                                    </div>

                                                    <div class="row" id="ben3"  <  style="display:none"    >
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Bénéficaire 3 </label>

                                                                <div class="input-group-control">
                                                                    <input   type="text" id="beneficiaire3" name="beneficiaire" class="form-control"   >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Prénom Bénéficaire 3</label>

                                                                <div class="input-group-control">
                                                                    <input   type="text" id="prenom_benef3" name="prenom_benef" class="form-control"   >
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Parenté </label>

                                                                <div class="input-group-control">
                                                                    <input   type="text" id="parente3" name="parente" class="form-control"     >
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>


                                                    <div class="row">



                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Tel   1</label>

                                                                <div class="input-group-control">
                                                                    <input   type="text" id="subscriber_phone_cell" name="subscriber_phone_cell" class="form-control"   >
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Tel   2</label>

                                                                <div class="input-group-control">
                                                                    <input   type="text" id="subscriber_phone_domicile" name="subscriber_phone_domicile" class="form-control"   >
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">To </label>

                                                                <div class="input-group-control">
                                                                    <input   type="text" id="to" name="to" class="form-control"    >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Guide</label>

                                                                <div class="input-group-control">
                                                                    <input    type="text" id="to_guide" name="to_guide" class="form-control"     >
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>


                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Date arrivée </label>
                                                                <input    data-format="dd-MM-yyyy hh:mm:ss" placeholder="jj-mm-aaaa" class="form-control datepicker-default form-control" name="initial_arrival_date" id="initial_arrival_date" type="text"   >
                                                            </div>

                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Départ prévu</label>
                                                                <input    data-format="dd-MM-yyyy hh:mm:ss" placeholder="jj-mm-aaaa" class="form-control datepicker-default form-control" name="departure" id="departure" type="text"   >
                                                            </div>

                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Destination </label>

                                                                <div class="input-group-control">
                                                                    <input   type="text" id="destination" name="destination" class="form-control"    >
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row" id="adresse3" style="display:none;" >
                                                        <div class="form-group col-md-10">
                                                            <label for="inputError" class="control-label"><label id="derniere3">Dernière</label> Adresse en Tunisie  </label>

                                                            <div class="input-group-control">
                                                                <input    type="text" id="subscriber_local_address3" name="subscriber_local_address3" class="form-control"    >
                                                            </div>
                                                        </div>

                                                        <div class="col-md-1" style="padding-top:30px">
                                                            <span title="cacher l'adresse" style="margin-top:20px;width:20px" class=" btn-md" id="btn04moins"><i class="fa  fa-minus"></i> </span>

                                                        </div>
                                                    </div>
                                                    <div class="row" id="adresse2"   style="display:none;"  >
                                                        <div class="form-group col-md-10">
                                                            <label for="inputError" class="control-label"><label id="derniere2">Dernière</label> Adresse en Tunisie  </label>

                                                            <div class="input-group-control">
                                                                <input    type="text" id="subscriber_local_address2" name="subscriber_local_address2" class="form-control"    >
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
                                                                <input    type="text" id="subscriber_local_address" name="subscriber_local_address" class="form-control"   >
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
                                                                <input   type="text" id="adresse_etranger" name="adresse_etranger" class="form-control"    >
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="row">

                                                        <div class="col-md-5">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Ville</label>

                                                                <div class="input-group-control">
                                                                    <input    type="text" autocomplete="off" id="ville" name="ville" class="form-control"    >
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



                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Hôtel </label>

                                                                <div class="input-group-control">
                                                                    <select onchange=" "   id="hotel" name="hotel" class="form-control"  >

                                                                        <option></option>
                                                                        <?php

                                                                        foreach($hotels as $ht)
                                                                        {
                                                                            if( PrestatairesController::ChampById('name',$ht->prestataire_id)!=''){ echo '<option title="'.$ht->prestataire_id.'"  value="'.   PrestatairesController::ChampById('name',$ht->prestataire_id).'">'.   PrestatairesController::ChampById('name',$ht->prestataire_id).'</option>';}
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
                                                                    <input    type="text" id="subscriber_local_address_ch" name="subscriber_local_address_ch" class="form-control"   >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Tel Chambre</label>
                                                                <div class="input-group-control">
                                                                    <input   type="text" id="tel_chambre" name="tel_chambre" class="form-control"    >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">

                                                                <label for="inputError" class="control-label">Mail</label>
                                                                <div class="input-group-control">
                                                                    <input   type="email" id="subscriber_mail1" name="subscriber_mail1" class="form-control" placeholder="mail 1"    >
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">

                                                                <label for="inputError" class="control-label">Mail 2</label>
                                                                <div class="input-group-control">
                                                                    <input   type="email" id="subscriber_mail2" name="subscriber_mail2" class="form-control" placeholder="mail 2"    >
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

<!--
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label for="inputError" class="control-label">Entité de facturation  </label>

                                                    <div class="input-group-control">
                                                        <select   type="text" id="adresse_facturation" name="adresse_facturation" class="form-control"    >
                                                            <option></option>
                                                            <option   > <small></small></option>
                                                            <?php /* foreach ($liste as $l)
                                                            {?>
                                                            <option  value="<?php $l->nom;?>" ><?php $l->nom ;?>   <small>  <?php $l->champ;?> </small></option>
                                                            <?php
                                                            } */
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
-->
<!--

                                        <div class="form-group row ">
                                            <h4>Documents à signer</h4>


                                            <div class="row">
                                                <select class="form-control  col-lg-12 itemName " style="width:400px" name="docs"  multiple  id="docs">


                                                    <option></option>


                                                  {{--  @foreach($cldocs as $doc)
                                                        <option    onclick="createdocdossier('spec<?php echo $doc->doc; ?>')"  value="<?php echo $doc->doc;?>"> <?php echo DocsController::ChampById('nom',$doc->doc);?></option>
                                                    @endforeach --}}


                                                </select>

                                            </div>
                                        </div>
-->

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">

                                                    <label for="franchise" class=""> Franchise &nbsp;&nbsp;
                                                        <div class="radio radio1" id="uniform-franchise"><span><input onclick="" type="radio" name="franchise" id="franchise" value="1"  ></span></div> Oui
                                                    </label>

                                                    <label for="nonfranchise" class="">

                                                        <div class="radio radio1" id="uniform-nonfranchise"><span class="checked"><input onclick="disabling('franchise');hidingd();" type="radio" name="franchise" id="nonfranchise" value="0"    ></span></div> Non
                                                    </label>

                                                </div>
                                            </div>

                                            <div class="col-md-4"  id="montantfr">
                                                <div class="form-group">
                                                    <label class="control-label">Montant Franchise
                                                    </label>

                                                    <div class="input-group-control">
                                                        <input    type="text" id="montant_franchise" name="montant_franchise" class="form-control" style="width: 100px;" placeholder="Montant"     >
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4" id="plafondfr">
                                                <div class="form-group">
                                                    <label class="control-label">Plafond
                                                    </label>

                                                    <div class="input-group-control">
                                                        <input    type="text" id="plafond" name="plafond" class="form-control" style="width: 100px;" placeholder="Plafond"    >
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="panel panel-success" id="medical" >
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
                                                            <div style="margin-right:20px" class="radio" id="uniform-is_hospitalized"><span><input 	type="radio" name="is_hospitalized" id="is_hospitalized" value="1"   ></span>Outpatient</div>
                                                        </label> <label for="nonis_hospitalized" class=""> <div class="radio" id="uniform-nonis_hospitalized"><span class=""><input onclick="disabling('is_hospitalized')" type="radio" name="is_hospitalized" id="nonis_hospitalized" value="0"    ></span> Inpatient </div>
                                                        </label>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">Hôspitalisé à </label>

                                                        <div class="input-group-control">
                                                            <select onchange="changing(this);ajout_prest(this);"  type="text" id="hospital_address" name="hospital_address" class="form-control"   >

                                                                <option></option>
                                                                <?php

                                                                foreach($hopitaux as $hp)
                                                                {
                                                                    if( PrestatairesController::ChampById('name',$hp->prestataire_id)!=''){ echo '<option title="'.$hp->prestataire_id.'"  value="'.   PrestatairesController::ChampById('name',$hp->prestataire_id).'">'.   PrestatairesController::ChampById('name',$hp->prestataire_id).'</option>';}
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">Médecin Traitant </label>

														
                                                        <div class="input-group-control">
                                                            <select onchange=""  type="text" id="medecin_traitant" name="medecin_traitant" class="form-control"    >

                                                                <option></option>
                                                                <?php

                                                                foreach($traitants as $tr)
                                                                {
                                                                    if (PrestatairesController::ChampById('name',$tr->prestataire_id)!='') {echo '<option title="'.$tr->prestataire_id.'"  value="'. PrestatairesController::ChampById('name',$tr->prestataire_id).'">'. PrestatairesController::ChampById('name',$tr->prestataire_id).' Fixe: '. PrestatairesController::ChampById('phone_home',$tr->prestataire_id) .' Tel: '.PrestatairesController::ChampById('phone_cell',$tr->prestataire_id) .'</option>';}
                                                                }

                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>

                                            <div class="row">

                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">Autre Médecin Traitant  </label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="medecin_traitant2" name="medecin_traitant2" class="form-control"   >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">Tel Autre Médecin Traitant</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="hospital_phone2" name="hospital_phone2" class="form-control"    >
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>


                                            <div class="row"   id="adresse03"  style="display:none;" >
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label"><label id="derniere03">Dernière</label> structure d’hospitalisation  </label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="empalcement_medic3" name="medecin_traitant2" class="form-control"   >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">De (Date)</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="date_debut_medic3"  class="form-control datepicker-default" data-format="dd-MM-yyyy hh:mm:ss"   >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">A (Date)</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="date_fin_medic3"   class="form-control datepicker-default"   >
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-1" style="padding-top:30px">
                                                    <span title="cacher l'adresse" style="margin-top:20px;width:20px" class=" btn-md" id="btn004moins"><i class="fa  fa-minus"></i></span>

                                                </div>
                                            </div>
                                            <div class="row" id="adresse02"  style="display:none;"  >
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label"><label id="derniere02">Dernière</label> structure d’hospitalisation  </label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="empalcement_medic2"   class="form-control"   >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">De (Date)</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="date_debut_medic2"  class="form-control datepicker-default"    >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">A (Date)</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="date_fin_medic2"  class="form-control datepicker-default"    >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-1" style="padding-top:30px">
                                                    <span title="Afficher une autre adresse" style="margin-top:20px;width:20px" class=" btn-md" id="btn004plus"><i class="fa fa-plus"></i></span>
                                                    <span title="cacher l'adresse" style="margin-top:20px;width:20px" class=" btn-md" id="btn003moins"><i class="fa   fa-minus"></i></span>
                                                </div>
                                            </div>

                                            <div class="row" id="adresse01">
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label"><label id="derniere01">Dernière </label> structure d’hospitalisation  </label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="empalcement_medic"    class="form-control"  >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">De (Date)</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="date_debut_medic" class="form-control datepicker-default"     >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">A (Date)</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="date_fin_medic"   class="form-control datepicker-default"    >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-1" style="padding-top:30px">
                                                    <div class="col-md-1" style="padding-top:30px">
                                                        <span title="Afficher une autre adresse" style="margin-top:20px;width:20px" class=" btn-md" id="btn003plus"><i class="fa   fa-plus"></i></span>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel panel-success " id="technique"  >
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

                                                        <select   type="text" id="vehicule_marque" name="vehicule_marque" class="form-control"       >
                                                            <option>Choisir la marque</option>
                                                            <option  value="AUTRE">AUTRE**</option>
                                                            <option  value="ABARTH">ABARTH</option>
                                                            <option  value="ALFA ROMEO">ALFA ROMEO</option>
                                                            <option  value="ASTON MARTIN">ASTON MARTIN</option>
                                                            <option  value="AUDI">AUDI</option>
                                                            <option  value="BENTLEY">BENTLEY</option>
                                                            <option  value="BMW">BMW</option>
                                                            <option  value="CITROEN">CITROEN</option>
                                                            <option  value="DACIA">DACIA</option>
                                                            <option  value="DS">DS</option>
                                                            <option  value="FERRARI">FERRARI</option>
                                                            <option  value="FIAT">FIAT</option>
                                                            <option  value="FORD">FORD</option>
                                                            <option  value="HONDA">HONDA</option>
                                                            <option  value="HYUNDAI">HYUNDAI</option>
                                                            <option  value="IINFINITI">IINFINITI</option>
                                                            <option  value="JAGUAR">JAGUAR</option>
                                                            <option  value="JEEP">JEEP</option>
                                                            <option  value="KIA">KIA</option>
                                                            <option  value="LADA">LADA</option>
                                                            <option  value="LAMBORGHINI">LAMBORGHINI</option>
                                                            <option  value="LAND ROVER">LAND ROVER</option>
                                                            <option  value="LEXUS">LEXUS</option>
                                                            <option  value="LOTUS">LOTUS</option>
                                                            <option  value="MASERATI">MASERATI</option>
                                                            <option  value="MAZDA">MAZDA</option>
                                                            <option  value="MCLAREN">MCLAREN</option>
                                                            <option  value="MERCEDES-BENZ">MERCEDES-BENZ</option>
                                                            <option  value="MINI">MINI</option>
                                                            <option  value="MITSUBISHI">MITSUBISHI</option>
                                                            <option  value="NISSAN">NISSAN</option>
                                                            <option  value="OPEL">OPEL</option>
                                                            <option  value="PEUGEOT">PEUGEOT</option>
                                                            <option  value="PORSCHE">PORSCHE</option>
                                                            <option  value="RENAULT">RENAULT</option>
                                                            <option  value="ROLLS ROYCE">ROLLS ROYCE</option>
                                                            <option  value="SEAT">SEAT</option>
                                                            <option  value="SKODA">SKODA</option>
                                                            <option  value="SMART">SMART</option>
                                                            <option  value="SSANGYONG">SSANGYONG</option>
                                                            <option  value="SUBARU">SUBARU</option>
                                                            <option  value="SUZUKI">SUZUKI</option>
                                                            <option  value="TESLA">TESLA</option>
                                                            <option  value="TOYOTA" >TOYOTA</option>
                                                            <option  value="VOLKSWAGEN">VOLKSWAGEN</option>
                                                            <option  value="VOLVO">VOLVO</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label"> Type</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="vehicule_type" name="vehicule_type" class="form-control"   >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">Immatriculation</label>

                                                        <div class="input-group-control">
                                                            <input    type="text" id="vehicule_immatriculation" name="vehicule_immatriculation" class="form-control"    >
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">Dernière adresse d'immobilisation </label>

                                                        <div class="input-group-control">

                                                            <select onchange="changing(this);ajout_prest(this);"  type="text" id="lieu_immobilisation" name="medecin_traitant" class="form-control"   >

                                                                <option></option>
                                                                <?php

                                                                foreach($garages as $gr)
                                                                {  
                                                                    if (PrestatairesController::ChampById('name',$gr->prestataire_id)!='') {echo '<option  title="'.$gr->prestataire_id.'"   value="'. PrestatairesController::ChampById('name',$gr->prestataire_id).'">'. PrestatairesController::ChampById('name',$gr->prestataire_id).'</option>';}
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
                                                            <input    type="text" id="vehicule_address2" name="vehicule_address2" class="form-control"   >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label"> Ville / localité</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="vehicule_address" name="vehicule_address" class="form-control"    >
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

                                            </div>


                                            <div class="row"   id="adresse13" style="display:none;"   >
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label"><label id="derniere13">Dernier</label> garage </label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="empalcement_trans3"  class="form-control"  >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">Type</label>

                                                        <div class="input-group-control">
                                                            <select     id="type_trans3" class="form-control "   >
                                                                <option   value=""></option>
                                                                <option   value="gardiennage">Gardiennage </option>
                                                                <option   value="garage">Garage </option>
                                                                <option   value="libre">Adresse Libre </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">De (Date)</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="date_debut_trans3"  class="form-control datepicker-default" data-format="dd-MM-yyyy" style="padding:6px 3px 6px 3px!important;"   >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">A (Date)</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="date_fin_trans3"   class="form-control datepicker-default"  style="padding:6px 3px 6px 3px!important;"   >
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-1" style="padding-top:30px">
                                                    <span title="cacher l'adresse" style="margin-top:20px;width:20px" class=" btn-md" id="btn014moins"><i class="fa  fa-minus"></i> </span>

                                                </div>
                                            </div>
                                            <div class="row" id="adresse12"  style="display:none;"   >
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label"><label id="derniere12">Dernier</label> garage  </label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="empalcement_trans2"   class="form-control"   >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">Type</label>

                                                        <div class="input-group-control">
                                                            <select     id="type_trans2" class="form-control "    >
                                                                <option   value=""></option>
                                                                <option   value="gardiennage">Gardiennage </option>
                                                                <option   value="garage">Garage </option>
                                                                <option   value="libre">Adresse Libre </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">De (Date)</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="date_debut_trans2"  class="form-control datepicker-default"  style="padding:6px 3px 6px 3px!important;"  >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">A (Date)</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="date_fin_trans2"  class="form-control datepicker-default"  style="padding:6px 3px 6px 3px!important;"   >
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
                                                            <input   type="text" id="empalcement_trans"    class="form-control"   >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">Type</label>

                                                        <div class="input-group-control">
                                                            <select     id="type_trans" class="form-control "   >
                                                                <option   value=""></option>
                                                                <option    value="gardiennage">Gardiennage </option>
                                                                <option    value="garage">Garage </option>
                                                                <option    value="libre">Adresse Libre </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">De (Date)</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="date_debut_trans" class="form-control datepicker-default"  style="padding:6px 3px 6px 3px!important;"   >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">A (Date)</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="date_fin_trans"   class="form-control datepicker-default" style="padding:6px 3px 6px 3px!important;"    >
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
                                                    <textarea    rows="3" class="form-control" name="observation" id="observation"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <span title="Ajouter une observation " style="width:20px" class=" btn-md" id="btno1" onclick="document.getElementById('obser2').style.display='block'"><i class="fa fa-plus"></i>  </span>

                                            </div>

                                        </div>
                                        <div class="row" id="obser2"   style="display:none"   >
                                            <div class="col-md-10">
                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <textarea    rows="3" class="form-control" name="observation2" id="observation2"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <span title="Ajouter une observation " style="width:20px" class=" btn-md" id="btno2" onclick="document.getElementById('obser3').style.display='block'"><i class="fa fa-plus"></i>  </span>
                                            </div>

                                        </div>
                                        <div class="row" id="obser3"  style="display:none" >
                                            <div class="col-md-10">
                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <textarea    rows="3" class="form-control" name="observation3" id="observation3"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-2"  >
                                                <span title="Ajouter une observation " style="width:20px" class=" btn-md" id="btno3" onclick="document.getElementById('obser4').style.display='block'"><i class="fa fa-plus"></i>  </span>


                                            </div>

                                        </div>
                                        <div class="row" id="obser4"  style="display:none" >
                                            <div class="col-md-10">
                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <textarea    rows="3" class="form-control" name="observation4" id="observation4"></textarea>
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
                Swal.fire({
                    type: 'error',
                    title: 'oups...',
                    text: "Erreur lors de recuperation de l historique du document"
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
                    Swal.fire({
                        type: 'error',
                        title: 'oups...',
                        text: "la template html du document n'est pas bien défini"
                    });
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
                    Swal.fire({
                        type: 'error',
                        title: 'oups...',
                        text: "la template rtf du document n'est pas bien défini"
                    });
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
            /*     else
             {div.style.display='none';     }*/


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
            /* else
             {div.style.display='none';     }*/


        });


        $('#btn004plus').click(function() {

            var   div=document.getElementById('adresse03');
            if(div.style.display==='none')
            {div.style.display='block';
                document.getElementById('derniere01').style.display='none';
                document.getElementById('derniere02').style.display='none';
                document.getElementById('derniere03').style.display='inline';
            }
            /*  else
             {div.style.display='none';     }*/


        });

        $('#btn003moins').click(function() {


            document.getElementById('derniere01').style.display='inline';
            document.getElementById('derniere02').style.display='none';
            document.getElementById('derniere03').style.display='none';
            document.getElementById('adresse02').style.display='none';
            document.getElementById('adresse03').style.display='none';
            /*    else
             {div.style.display='none';     }*/


        });


        $('#btn004moins').click(function() {

            var   div=document.getElementById('adresse03');
            if(div.style.display==='block')
            {div.style.display='none';

                document.getElementById('derniere01').style.display='none';
                document.getElementById('derniere02').style.display='inline';
                document.getElementById('derniere03').style.display='none';
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
            /*  else
             {div.style.display='none';     }*/


        });

        $('#btn013moins').click(function() {


            document.getElementById('derniere11').style.display='inline';
            document.getElementById('derniere12').style.display='none';
            document.getElementById('derniere13').style.display='none';
            document.getElementById('adresse12').style.display='none';
            document.getElementById('adresse13').style.display='none';
            /*    else
             {div.style.display='none';     }*/


        });


        $('#btn014moins').click(function() {

            var   div=document.getElementById('adresse13');
            if(div.style.display==='block')
            {div.style.display='none';

                document.getElementById('derniere11').style.display='none';
                document.getElementById('derniere12').style.display='inline';
                document.getElementById('derniere13').style.display='none';
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

    .form-control .datepicker-default{
        padding:6px 3px 6px 3px;
    }


</style>
