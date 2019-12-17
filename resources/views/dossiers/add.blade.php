@extends('layouts.mainlayout')
<?php
use App\User ;

use App\Document ;


use \App\Http\Controllers\PrestationsController;
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

            <form id="savefolder"  method="post"  action="{{route('dossiers.save')}}" >
                {{ csrf_field() }}
                <input type="hidden" name="entree" value="0" />
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Affecté à </label>
                            <select required id="type_affectation" name="type_affectation" class="form-control js-example-placeholder-single" onchange="changetype();hidediv()" >
                                <option   value="">Sélectionnez</option>
                                <option  value="Najda">Najda</option>
                                <option  value="VAT">VAT</option>
                                <option  value="MEDIC">MEDIC</option>
                                <option  value="Transport MEDIC">Transport MEDIC</option>
                                <option  value="Transport VAT">Transport VAT</option>
                                <option  value="Medic International">Medic International</option>
                                <option  value="Najda TPA">Najda TPA</option>
                                <option  value="Transport Najda">Transport Najda</option>
                                <option  value="X-Press">X-Press</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Type de dossier</label>
                            <select required  onchange="hidediv() "  id="type_dossier" name="type_dossier" class="form-control js-example-placeholder-single">
                                <option   value="">Sélectionnez</option>
                                <option   value="Mixte">Mixte</option>
                                <option  value="Medical">Medical</option>
                                <option  value="Technique">Technique</option>
                                <option  value="Transport">Transport</option>
                            </select>
                        </div>
                    </div>



                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="complexite"> Complexité</label>
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


                                            <div class="panel panel-success">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <a class="accordion-toggle" data-toggle="collapse">
                                                            Info Client</a>
                                                    </h4>
                                                </div>
                                            </div>

                                        <div class="panel-collapse collapse in">
                                            <div class="panel-body">
                                                <div class="col-md-12">
                                                    <div class="row">

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Client </label>
                                                            <select required id="customer_id" name="customer_id" class="form-control js-example-placeholder-single"    >
                                                                <option value="0">Sélectionner..... </option>

                                                                @foreach($clients as $cl  )
                                                                    <option
                                                                            value="{{$cl->id}}">{{$cl->name}}</option>
                                                                @endforeach

                                                            </select>
                                                        </div>
                                                    </div>


                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Référence du Client </label>
                                                            <input required onchange="checkexiste();"  type="text" id="reference_customer" name="reference_customer" class="form-control"   >

                                                        </div>
                                                    </div>

                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">

                                                                <label for="franchise" class=""> Franchise &nbsp;&nbsp;
                                                                    <div class="radio radio1" id="uniform-franchise"><span><input   type="radio" name="franchise" id="franchise" value="1"  ></span></div> Oui
                                                                </label>

                                                                <label for="nonfranchise" class="">

                                                                    <div class="radio radio1" id="uniform-nonfranchise"><span class="checked"><input onclick="" type="radio" name="franchise" id="nonfranchise" value="0"    ></span></div> Non
                                                                </label>

                                                            </div>
                                                        </div>

                                                        <div class="col-md-4"  id="montantfr" style="display:none">
                                                            <div class="form-group">
                                                                <label class="control-label">Montant Franchise
                                                                </label>

                                                                <div class="input-group-control">
                                                                    <input    type="number" id="montant_franchise" name="montant_franchise" class="form-control" style="width: 100px;" placeholder="Montant"     >
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4" id="plafondfr"  style="display:none">
                                                            <div class="form-group">
                                                                <label class="control-label">Plafond
                                                                </label>

                                                                <div class="input-group-control">
                                                                    <input    type="number" id="plafond" name="plafond" class="form-control" style="width: 100px;" placeholder="Plafond"    >
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
                                                                <label for="inputError" class="control-label">Nom de l'assuré *</label>

                                                                <div class="input-group-control">
                                                                    <input  required type="text" id="subscriber_name" name="subscriber_name" class="form-control"    >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Prénom de l'assuré   * </label>

                                                                <div class="input-group-control">
                                                                    <input  required type="text" id="subscriber_lastname" name="subscriber_lastname" class="form-control"    >
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="ben" class="control-label">bénéficiaire différent</label><br>

                                                                <label for="annule" class="">
                                                                    <div class="radio" id="uniform-actif">
                                                                                        <span class="checked">
                                                                       <input    type="checkbox"  id="benefdiff" name="benefdiff"  value="1"    onclick="showBen();" >
                                                                                        </span>Oui</div>
                                                                </label>
                                                            </div>
                                                        </div>

                                                    </div>

                                                    <div class="row" id="bens"  style="display:none" >

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Nom du Bénéficaire </label>

                                                                <div class="input-group-control">
                                                                    <input   type="text" id="beneficiaire" name="beneficiaire" class="form-control"     >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Prénom du Bénéficaire</label>

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
                                                                <label for="inputError" class="control-label">Nom du Bénéficaire 2</label>

                                                                <div class="input-group-control">
                                                                    <input   type="text" id="beneficiaire2" name="beneficiaire2" class="form-control"     >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Prénom du Bénéficaire 2</label>

                                                                <div class="input-group-control">
                                                                    <input   type="text" id="prenom_benef2" name="prenom_benef2" class="form-control"     >
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Parenté </label>

                                                                <div class="input-group-control">
                                                                    <input   type="text" id="parente2" name="parente2" class="form-control"   >
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-1" style="padding-top:30px">
                                                            <span title="Afficher le bénéficiaire 3" style="width:20px" class=" btn-md" id="btn02"><i class="fa fa-plus"></i> <i class="fa fa-minus"></i></span>
                                                        </div>
                                                    </div>

                                                    <div class="row" id="ben3"    style="display:none"    >


                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Nom du Bénéficaire 3 </label>

                                                                <div class="input-group-control">
                                                                    <input   type="text" id="beneficiaire3" name="beneficiaire3" class="form-control"   >
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Prénom du Bénéficaire 3</label>

                                                                <div class="input-group-control">
                                                                    <input   type="text" id="prenom_benef3" name="prenom_benef3" class="form-control"   >
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Parenté </label>

                                                                <div class="input-group-control">
                                                                    <input   type="text" id="parente3" name="parente3" class="form-control"     >
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

                                                    <div class="row">
                                                        <div class="form-group col-md-10">
                                                            <label for="inputError" class="control-label">Adresse étranger</label>

                                                            <div class="input-group-control">
                                                                <input   type="text" id="adresse_etranger" name="adresse_etranger" class="form-control"    >
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

                                                        <div class="col-md-4">


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

                                                        <div class="col-md-3">
                                                            <div class="form-group">
                                                                <label for="inputError" class="control-label">Chambre</label>

                                                                <div class="input-group-control">
                                                                    <input    type="text" id="subscriber_local_address_ch" name="subscriber_local_address_ch" class="form-control"   >
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                            <!--                                    </div>-->

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
                                                            <div style="margin-right:20px" class="radio radio2" id="uniform-is_hospitalized"><span><input    type="radio" name="is_hospitalized" id="is_hospitalized" value="1"   ></span>Outpatient</div>
                                                        </label> <label for="nonis_hospitalized" class=""> <div class="radio radio2" id="uniform-nonis_hospitalized"><span class=""><input  type="radio" name="is_hospitalized" id="nonis_hospitalized" value="0"     ></span> Inpatient </div>
                                                        </label>
                                                    </div>
                                                </div>

                                            </div>
                                            <div id="hospital">
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
                                                            <input   type="number" id="hospital_phone2" name="hospital_phone2" class="form-control"    >
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>


                                            <div class="row"   id="adresse03"  style="display:none;" >
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label"><label id="derniere03">Dernière</label> structure d’hospitalisation  </label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="empalcement_medic3" name="empalcement_medic3" class="form-control"   >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">De (Date)</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="date_debut_medic3"  name='date_debut_medic3' class="form-control datepicker-default" data-format="dd-MM-yyyy hh:mm:ss"   >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">A (Date)</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="date_fin_medic3"  name="date_fin_medic3"   class="form-control datepicker-default"   >
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
                                                            <input   type="text" id="empalcement_medic2"  name="empalcement_medic2"   class="form-control"   >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">De (Date)</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="date_debut_medic2"  name="date_debut_medic2" class="form-control datepicker-default"    >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">A (Date)</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="date_fin_medic2"  name="date_fin_medic2" class="form-control datepicker-default"    >
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
                                                            <input   type="text" id="empalcement_medic"  name="empalcement_medic"    class="form-control"  >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">De (Date)</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="date_debut_medic" name="date_debut_medic"  class="form-control datepicker-default"     >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">A (Date)</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="date_fin_medic" name="date_fin_medic"   class="form-control datepicker-default"    >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-1" style="padding-top:30px">
                                                    <div class="col-md-1" style="padding-top:30px">
                                                        <span title="Afficher une autre adresse" style="margin-top:20px;width:20px" class=" btn-md" id="btn003plus"><i class="fa   fa-plus"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                      </div><!-- hospital -->

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



                                            <div class="row"   id="adresse003"  style="display:none;" >
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label"><label id="derniere003">Dernière</label> adresse d'immobilisation  </label>

                                                        <div class="input-group-control">

                                                            <select   type="text" id="empalcement3" name="empalcement3" class="form-control"   >

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
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">De (Date)</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="date_debut_emp3"  name='date_debut_emp3' class="form-control datepicker-default" data-format="dd-MM-yyyy hh:mm:ss"   >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">A (Date)</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="date_fin_emp3"  name="date_fin_emp3"   class="form-control datepicker-default"   >
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-1" style="padding-top:30px">
                                                    <span title="cacher l'adresse" style="margin-top:20px;width:20px" class=" btn-md" id="0btn004moins"><i class="fa  fa-minus"></i></span>

                                                </div>
                                            </div>
                                            <div class="row" id="adresse002"  style="display:none;"  >
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label"><label id="derniere002">Dernière</label> adresse d'immobilisation </label>

                                                        <select    type="text" id="empalcement2" name="empalcement2" class="form-control"   >

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
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">De (Date)</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="date_debut_emp2"  name="date_debut_emp2" class="form-control datepicker-default"    >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">A (Date)</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="date_fin_emp2"  name="date_fin_emp2" class="form-control datepicker-default"    >
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

                                                            <select    type="text" id="empalcement" name="empalcement" class="form-control"   >

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
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">De (Date)</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="date_debut_emp" name="date_debut_emp"  class="form-control datepicker-default"     >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">A (Date)</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="date_fin_emp" name="date_fin_emp"   class="form-control datepicker-default"    >
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
                                                                <input    type="text" id="vehicule_address2" name="vehicule_address2" class="form-control"   >
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-md-5">
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

                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">De (Date)</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="date_debut_vehicule_address" name="date_debut_vehicule_address"  class="form-control datepicker-default"     >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">A (Date)</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="date_fin_vehicule_address" name="date_fin_vehicule_address"   class="form-control datepicker-default"    >
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>


                                            <div class="row"   id="adresse13" style="display:none;"   >
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label"><label id="derniere13">Dernier</label> garage </label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="empalcement_trans3" name="empalcement_trans3"  class="form-control"  >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">Type</label>

                                                        <div class="input-group-control">
                                                            <select      id="type_trans3" name="type_trans3" class="form-control "   >
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
                                                            <input   type="text" id="date_debut_trans3" name="date_debut_trans3"  class="form-control datepicker-default" data-format="dd-MM-yyyy" style="padding:6px 3px 6px 3px!important;"   >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">A (Date)</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="date_fin_trans3" name="date_fin_trans3"   class="form-control datepicker-default"  style="padding:6px 3px 6px 3px!important;"   >
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
                                                            <input   type="text" id="empalcement_trans2" name="empalcement_trans2"   class="form-control"   >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">Type</label>

                                                        <div class="input-group-control">
                                                            <select     id="type_trans2"  name="type_trans2" class="form-control "    >
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
                                                            <input   type="text" id="date_debut_trans2" name="date_debut_trans2"  class="form-control datepicker-default"  style="padding:6px 3px 6px 3px!important;"  >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">A (Date)</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="date_fin_trans2"  name="date_fin_trans2"  class="form-control datepicker-default"  style="padding:6px 3px 6px 3px!important;"   >
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
                                                            <input   type="text" id="empalcement_trans"  name="empalcement_trans"    class="form-control"   >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">Type</label>

                                                        <div class="input-group-control">
                                                            <select     id="type_trans" name="type_trans"  class="form-control "   >
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
                                                            <input   type="text"  id="date_debut_trans" name="date_debut_trans" class="form-control datepicker-default"  style="padding:6px 3px 6px 3px!important;"   >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label for="inputError" class="control-label">A (Date)</label>

                                                        <div class="input-group-control">
                                                            <input   type="text" id="date_fin_trans"  name="date_fin_trans"   class="form-control datepicker-default" style="padding:6px 3px 6px 3px!important;"    >
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
                    <div class="row" style="margin-bottom:30px">


                        <div class="form-actions pull-right  col-md-4">
                            <a href="{{route('entrees.index')}}" type="button" id="annuler" class="btn btn-sm btn-danger">Annuler</a>
                        </div>

                        <div class="form-actions pull-right col-md-6">
                            <input type="submit" value="Enregistrer" id="editDos" class="btn btn-sm btn-info"></input>
                        </div>
                    </div>
            </form>
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



@section('footer_scripts')


@stop

<script src="https://cdn.jsdelivr.net/npm/places.js@1.16.4"></script>

<script>

    function hidediv()
    {

       if (document.getElementById('type_dossier').value=="Mixte")
       {
           document.getElementById('medical').style.display = 'block';
           document.getElementById('technique').style.display = 'block';

       }
        if (document.getElementById('type_dossier').value=="Medical")
        {
            document.getElementById('medical').style.display = 'block';
            document.getElementById('technique').style.display = 'none';
        }
        if (document.getElementById('type_dossier').value=="Technique")
        {
            document.getElementById('medical').style.display = 'none';
            document.getElementById('technique').style.display = 'block';
        }


    }

    function changetype()
    { var ta= document.getElementById('type_affectation');
        if (ta.options[ta.selectedIndex].value=="VAT")
        {
            document.getElementById('type_dossier').selectedIndex=3;
        }

        if ((ta.options[ta.selectedIndex].value=="MEDIC")||(ta.options[ta.selectedIndex].value=="Medic International") ||(ta.options[ta.selectedIndex].value=="Najda TPA") )
        {
            document.getElementById('type_dossier').selectedIndex=2;
        }

        if ((ta.options[ta.selectedIndex].value=="Transport Najda")||(ta.options[ta.selectedIndex].value=="Transport MEDIC") ||(ta.options[ta.selectedIndex].value=="Transport VAT") ||(ta.options[ta.selectedIndex].value=="X-Press")     )
        {
            document.getElementById('type_dossier').selectedIndex=4;
        }

    }

    function checkexiste( ) {

        var val =document.getElementById('reference_customer').value;
        //  var type = $('#type').val();

        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('dossiers.checkexiste') }}",
            method: "POST",
            data: {   val:val, _token: _token},
            success: function (data) {

                if(data==1){
                    alert('Référence Existe deja !');
                    document.getElementById('reference_customer').style.background='#FD9883';
                } else{
                    document.getElementById('reference_customer').style.background='white';
                }


            }
        });
        // } else {

        // }
    }

    function showBen() {

        if (document.getElementById('benefdiff').value == 1) {
            $('#bens').css('display','block');

        }
    }

    function  annuler()
    {

    }

    function setTel(elm)
    {
        var num=elm.className;
        document.getElementById('destinataire').value=parseInt(num);

    }

    $(function () {



        $('.radio1').click(function() {

            var   div=document.getElementById('montantfr');
            var franchise=document.getElementById('franchise').checked;

            if(franchise)
            {div.style.display='block';	 }
            else
            {div.style.display='none';     }

            var   div2=document.getElementById('plafondfr');
            if(franchise)
            {div2.style.display='block';	 }
            else
            {div2.style.display='none';     }
        });


        $('#is_hospitalized').click(function() {

            var   div=document.getElementById('hospital');
            var hospital=document.getElementById('nonis_hospitalized').checked;
            if(hospital)
             {div.style.display='block';	 }
            else
            {div.style.display='none';     }

        });


        $('#nonis_hospitalized').click(function() {

            var   div=document.getElementById('hospital');
            var hospital=document.getElementById('nonis_hospitalized').checked;

            if(hospital)
            {div.style.display='block';	 }
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
////

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


</style>
