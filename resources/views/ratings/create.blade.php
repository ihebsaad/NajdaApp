@extends('layouts.mainlayout')

@section('content')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

    Pas encore terminée  ******

    <div class="form-group">
        {{ csrf_field() }}

        <form id="addpresform" novalidate="novalidate">

            <input id="idprestation" name="idprestation" type="hidden" value="68356">
            <div class="row" >
                <div class="form-group col-md-6  ">
                    <label>Type de prestations</label>
                    <select class="itemName form-control col-lg-6" style="" name="itemName"  multiple  id="typeprest">
                        <option></option>
                        @foreach($typesprestations as $aKey)
                            <option     value="<?php echo $aKey->id;?>"> <?php echo $aKey->name;?></option>
                        @endforeach

                    </select>

                </div>
                <div class="form-group col-md-6">
                    <label>Gouvernorat de couverture</label>
                    <select class="form-control col-lg-6" style="" name="gouv"  multiple  id="gouvcouv">
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
                    <div class="select2-container form-control" id="s2id_all_pres_id"><a href="javascript:void(0)" class="select2-choice"   >   <span class="select2-chosen" id="select2-chosen-8">&nbsp;</span><abbr class="select2-search-choice-close"></abbr>   <span class="select2-arrow" role="presentation"><b role="presentation"></b></span></a><label for="s2id_autogen8" class="select2-offscreen"></label><input class="select2-focusser select2-offscreen" type="text" aria-haspopup="true" role="button" aria-labelledby="select2-chosen-8" id="s2id_autogen8"><div class="select2-drop select2-display-none select2-with-searchbox">   <div class="select2-search">       <label for="s2id_autogen8_search" class="select2-offscreen"></label>       <input type="text" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" class="select2-input" role="combobox" aria-expanded="true" aria-autocomplete="list" aria-owns="select2-results-8" id="s2id_autogen8_search" placeholder="">   </div>   <ul class="select2-results" role="listbox" id="select2-results-8">   </ul></div></div><select class="form-control select2-offscreen" id="all_pres_id"    title="">
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
            <div class="form-group" id="authDiv" style="display:none">
                <label class="control-label"><i class="icon-user-following"></i> Autorisé par</label>
                <select id="user_auth" name="user_auth" class="form-control" placeholder="choisir un utilisateur...">
                    <option value="223">Iheb Esolutions</option>
                </select>
            </div>
            <!-- div class="form-group">
                <label class="control-label">Prestataire <span class="required"> * </span></label>
                <select name="pres_id" id="pres_id" class="form-control" data-required="1" required>
                </select>
            </div -->
            <div class="form-group">
                <label class="control-label">Date de prestation <span class="required" aria-required="true"> * </span></label>
                <input class="form-control datepicker-default" name="pres_date" id="pres_date" data-required="1" required="" aria-required="true">
            </div>
            <div class="form-group">
                <label>Prix</label>
                <input class="form-control" name="pres_cost" id="pres_cost">
            </div>
            <div class="form-group">
                <label>Marge du prix</label>
                <input class="form-control" name="pres_margin" id="pres_margin">
            </div>
            <div class="form-group">
                <div class="checkbox-list">
                    <label>
                        <div class="checker" id="uniform-pres_parv"><span><input type="checkbox" name="pres_parv" id="pres_parv" value="1"></span></div> Parvenue </label>
                    <label>
                        <div class="checker" id="uniform-pres_fact"><span><input type="checkbox" name="pres_fact" id="pres_fact" value="1"></span></div> Facturée au client </label>
                    <label>
                    </label></div>
            </div>
            <!-- BEGIN BLOCK VAT -->
            <div id="bloc_vat" style="display:none">
                <div class="form-group">
                    <div class="checkbox-list">
                        <label>
                            <div class="checker" id="uniform-pres_vat_valid"><span><input type="checkbox" name="pres_vat_valid" id="pres_vat_valid" value="1"></span></div> Valide ? </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="checkbox-list">
                        <label class="checkbox-inline"><div class="checker" id="uniform-pres_vat_transfert"><span><input type="checkbox" name="pres_vat_transfert" id="pres_vat_transfert" value="1"></span></div> Transfert </label>
                        <label class="checkbox-inline"><div class="checker" id="uniform-pres_vat_circuit"><span><input type="checkbox" name="pres_vat_circuit" id="pres_vat_circuit" value="1"></span></div> Circuit </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Pour le</label>
                    <input class="form-control" name="pres_vat_date" id="pres_vat_date">

                </div>
                <div class="form-group">
                    <div class="checkbox-list">
                        <label class="checkbox-inline"><div class="checker" id="uniform-pres_vat_date_dimanche"><span><input type="checkbox" name="pres_vat_date_dimanche" id="pres_vat_date_dimanche" value="1"></span></div> Dimanche </label>
                        <label class="checkbox-inline"><div class="checker" id="uniform-pres_vat_date_ferie"><span><input type="checkbox" name="pres_vat_date_ferie" id="pres_vat_date_ferie" value="1"></span></div> Férié </label>
                        <label class="checkbox-inline"><div class="checker" id="uniform-pres_vat_date_nuit"><span><input type="checkbox" name="pres_vat_date_nuit" id="pres_vat_date_nuit" value="1"></span></div> Nuit </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Date demande</label>
                    <input class="form-control" name="pres_vat_date_demande" id="pres_vat_date_demande">

                </div>
                <div class="form-group">
                    <label class="control-label">Heure</label>
                    <input class="form-control" name="pres_vat_heure" id="pres_vat_heure">

                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Origine de la demande</label>
                            <input class="form-control" name="pres_vat_origin" id="pres_vat_origin">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Tel</label>
                            <input class="form-control" name="pres_vat_tel" id="pres_vat_tel">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Personne ayant fait la demande</label>
                    <input class="form-control" name="pres_vat_pers_init" id="pres_vat_pers_init">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Si circuit Nature/Destination</label>
                            <input class="form-control" name="pres_vat_nature_crct" id="pres_vat_nature_crct">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Durée</label>
                            <input class="form-control" name="pres_vat_duree_crct" id="pres_vat_duree_crct">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Identité personne / groupe à transporter</label>
                    <input class="form-control" name="pres_vat_identite_trans" id="pres_vat_identite_trans">
                </div>
                <div class="form-group">
                    <label class="control-label">Nombre de passagers</label>
                    <input class="form-control" name="pres_vat_nb_passagers" id="pres_vat_nb_passagers">
                </div>
                <div class="form-group">
                    <label class="control-label">Accompagnants</label>
                    <textarea class="form-control" name="pres_vat_accompagnants" id="pres_vat_accompagnants"></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Lieu de prise en charge </label>
                            <input class="form-control" name="pres_vat_lieu_pec" id="pres_vat_lieu_pec">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Tel </label>
                            <input class="form-control" name="pres_vat_tel_pec" id="pres_vat_tel_pec">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Lieu de décharge </label>
                            <input class="form-control" name="pres_vat_lieu_dec" id="pres_vat_lieu_dec">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Tel </label>
                            <input class="form-control" name="pres_vat_tel_dec" id="pres_vat_tel_dec">
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <label class="control-label">De / Vers aéroport </label>
                    <select class="form-control" name="pres_vat_aeroport" id="pres_vat_aeroport">
                        <option value="Destination">Destination</option>
                        <option value="Origine">Origine</option>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Pays </label>
                            <input class="form-control" name="pres_vat_pays" id="pres_vat_pays">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Vol N° </label>
                            <input class="form-control" name="pres_vat_vol" id="pres_vat_vol">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Type de siège </label>
                    <select class="form-control" name="pres_vat_type_siege" id="pres_vat_type_siege">
                        <option value="Siege normal">Siege normal</option>
                        <option value="Extra-seat">Extra-seat</option>
                        <option value="Civiere">Civière</option>
                        <option value="Avion sanitaire">Avion sanitaire</option>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Heure décollage/atterrissage </label>
                            <input class="form-control" name="pres_vat_takeoff" id="pres_vat_takeoff">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Heure d'arrivée souhaitée </label>
                            <input class="form-control" name="pres_vat_landing" id="pres_vat_landing">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Récupération billets à </label>
                            <input class="form-control" name="pres_vat_billet" id="pres_vat_billet">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">PNR </label>
                            <input class="form-control" name="pres_vat_pnr" id="pres_vat_pnr">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Remarque </label>
                    <textarea class="form-control" name="pres_vat_remarque" id="pres_vat_remarque"></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label">Date départ base </label>
                    <input class="form-control" name="pres_vat_depart_base" id="pres_vat_depart_base">
                </div>
                <div class="form-group">
                    <label class="control-label">Date retour base </label>
                    <input class="form-control" name="pres_vat_retour_base" id="pres_vat_retour_base">
                </div>
                <div class="form-group">
                    <label class="control-label">Heure de départ</label>
                    <div class="row">
                        <div class="col-md-6">
                            <select class="form-control" name="pres_vat_heure_depart" id="pres_vat_heure_depart">
                                <option value="00">00</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16" selected="selected">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select class="form-control" name="pres_vat_minute_depart" id="pres_vat_minute_depart">
                                <option value="00" selected="selected">00</option>
                                <option value="15">15</option>
                                <option value="30">30</option>
                                <option value="45">45</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Heure d'arrivée</label>
                    <div class="row">
                        <div class="col-md-6">
                            <select class="form-control" name="pres_vat_heure_arrivee" id="pres_vat_heure_arrivee">
                                <option value="00">00</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16" selected="selected">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select class="form-control" name="pres_vat_minute_arrivee" id="pres_vat_minute_arrivee">
                                <option value="00" selected="selected">00</option>
                                <option value="15">15</option>
                                <option value="30">30</option>
                                <option value="45">45</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="ctrl-label">Voiture</label>
                    <select class="form-control" name="pres_vat_voiture" id="pres_vat_voiture"></select>
                </div>
                <div class="form-group">
                    <label class="ctrl-label">Chauffeur</label>
                    <select class="form-control" name="pres_vat_chauffeur" id="pres_vat_chauffeur"></select>
                </div>

                <div class="form-group">
                    <label class="control-label">Première étape (hôtel ?)</label>
                    <input class="form-control" name="pres_vat_hotel" id="pres_vat_hotel">
                </div>
                <div class="form-group">
                    <label class="control-label">Heures supplémentaires</label>
                    <input class="form-control" name="pres_vat_heuresup" id="pres_vat_heuresup">
                </div>
                <div class="form-group">
                    <label class="control-label">Départ base mission</label>
                    <input class="form-control" name="pres_vat_depart_mission" id="pres_vat_depart_mission">
                </div>
                <div class="form-group">
                    <label class="control-label">Lieu d'arrivée</label>
                    <input class="form-control" name="pres_vat_lieu_arrivee" id="pres_vat_lieu_arrivee">
                </div>
                <div class="form-group">
                    <label class="control-label">Lieu de départ</label>
                    <input class="form-control" name="pres_vat_lieu_depart" id="pres_vat_lieu_depart">
                </div>
                <div class="form-group">
                    <label class="control-label">Destination arrivée</label>
                    <input class="form-control" name="pres_vat_dest_arrivee" id="pres_vat_dest_arrivee">
                </div>
                <div class="form-group">
                    <label class="control-label">Départ vers base</label>
                    <input class="form-control" name="pres_vat_depart_vers_base" id="pres_vat_depart_vers_base">
                </div>
                <div class="form-group">
                    <label class="control-label">Arrivée base</label>
                    <input class="form-control" name="pres_vat_arrivee_base" id="pres_vat_arrivee_base">
                </div>
                <div class="form-group">
                    <label class="control-label">Distance parcourue</label>
                    <input class="form-control" name="pres_vat_distance" id="pres_vat_distance">
                </div>
                <div class="form-group">
                    <label class="control-label">Durée de mission</label>
                    <input class="form-control" name="pres_vat_duree_mission" id="pres_vat_duree_mission">
                </div>
            </div>
            <!-- END BLOCK VAT -->
            <div id="bloc_medic" style="display:none">
                <div class="form-group">
                    <div class="checkbox-list">
                        <label>
                            <div class="checker" id="uniform-pres_medic_valid"><span><input type="checkbox" name="pres_medic_valid" id="pres_medic_valid" value="1"></span></div> Valide ? </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Vecteur de transport</label>
                    <select class="form-control" name="pres_medic_vect" id="pres_medic_vect">
                        <option value="VSL">VSL</option>
                        <option value="Ambulance">Ambulance</option>
                        <option value="Avion sanitaire">Avion sanitaire</option>
                    </select>
                </div>
                <div class="form-group">
                    <div class="checkbox-list">
                        <label>
                            <div class="checker" id="uniform-pres_medic_allerret"><span><input type="checkbox" name="pres_medic_allerret" id="pres_medic_allerret" value="1"></span></div> Aller / Retour ? </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Pour le</label>
                    <input class="form-control" name="pres_medic_date" id="pres_medic_date">

                </div>
                <div class="form-group">
                    <div class="checkbox-list">
                        <label class="checkbox-inline"><div class="checker" id="uniform-pres_medic_date_dimanche"><span><input type="checkbox" name="pres_medic_date_dimanche" id="pres_medic_date_dimanche" value="1"></span></div> Dimanche </label>
                        <label class="checkbox-inline"><div class="checker" id="uniform-pres_medic_date_ferie"><span><input type="checkbox" name="pres_medic_date_ferie" id="pres_medic_date_ferie" value="1"></span></div> Férié </label>
                        <label class="checkbox-inline"><div class="checker" id="uniform-pres_medic_date_nuit"><span><input type="checkbox" name="pres_medic_date_nuit" id="pres_medic_date_nuit" value="1"></span></div> Nuit </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Date demande</label>
                    <input class="form-control" name="pres_medic_date_demande" id="pres_medic_date_demande">

                </div>
                <div class="form-group">
                    <label class="control-label">Heure</label>
                    <input class="form-control" name="pres_medic_heure" id="pres_medic_heure">

                </div>
                <div class="form-group">
                    <label class="control-label">Heure de PEC annoncée au client</label>
                    <div class="row">
                        <div class="col-md-6">
                            <select class="form-control" name="pres_medic_heure_pec" id="pres_medic_heure_pec">
                                <option value="00">00</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16" selected="selected">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select class="form-control" name="pres_medic_minute_pec" id="pres_medic_minute_pec">
                                <option value="00" selected="selected">00</option>
                                <option value="15">15</option>
                                <option value="30">30</option>
                                <option value="45">45</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Origine de la demande</label>
                            <input class="form-control" name="pres_medic_origin" id="pres_medic_origin">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Tel</label>
                            <input class="form-control" name="pres_medic_tel" id="pres_medic_tel">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Personne ayant fait la demande</label>
                    <input class="form-control" name="pres_medic_pers_init" id="pres_medic_pers_init">
                </div>
                <div class="form-group">
                    <label class="control-label">Type de transport demandé</label>
                    <select class="form-control" name="pres_medic_type_trans" id="pres_medic_type_trans">
                        <option value="Sanitaire">Sanitaire Simple</option>
                        <option value="SanitaireAvecTechnicien">Sanitaire avec technicien</option>
                        <option value="Médicalisé">Médicalisé</option>
                        <option value="Rea">Réanimation</option>
                        <option value="EVASAN">EVASAN</option>
                        <option value="VSL">VSL</option>
                        <option value="MédicaliséAvecCouveuse">Médicalisé avec Couveuse</option>
                        <option value="ReaAvecCouveuse">Réa avec couveuse</option>
                    </select>
                </div>
                <div class="form-group">
                    <div class="checkbox-list">
                        <label class="checkbox-inline">
                            <div class="checker" id="uniform-pres_medic_oxygene"><span><input type="checkbox" id="pres_medic_oxygene" name="pres_medic_oxygene" value="1"></span></div> Oxygène
                        </label>
                        <label class="checkbox-inline">
                            <div class="checker" id="uniform-pres_medic_couveuse"><span><input type="checkbox" id="pres_medic_couveuse" name="pres_medic_couveuse" value="1"></span></div> Couveuse
                        </label>
                        <label class="checkbox-inline">
                            <div class="checker" id="uniform-pres_medic_respirateur"><span><input type="checkbox" id="pres_medic_respirateur" name="pres_medic_respirateur" value="1"></span></div> Respirateur
                        </label>
                        <label class="checkbox-inline">
                            <div class="checker" id="uniform-pres_medic_psevoie"><span><input type="checkbox" id="pres_medic_psevoie" name="pres_medic_psevoie" value="1"></span></div> PSE / Voie
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Identité personne à transporter</label>
                    <input class="form-control" name="pres_medic_pers_trans" id="pres_medic_pers_trans">
                </div>
                <p><strong>Accompagnants : </strong></p>
                <div class="form-group">
                    <label class="control-label">Nombre de passagers</label>
                    <input class="form-control" name="pres_medic_nb_passagers" id="pres_medic_nb_passagers">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Famille </label>
                            <input class="form-control" name="pres_medic_acc_fam" id="pres_medic_acc_fam">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Méd ou paraméd </label>
                            <input class="form-control" name="pres_medic_acc_med" id="pres_medic_acc_med">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Lieu de prise en charge </label>
                            <input class="form-control" name="pres_medic_lieu_pec" id="pres_medic_lieu_pec">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Tel </label>
                            <input class="form-control" name="pres_medic_tel_pec" id="pres_medic_tel_pec">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Lieu de décharge </label>
                            <input class="form-control" name="pres_medic_lieu_dec" id="pres_medic_lieu_dec">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Tel </label>
                            <input class="form-control" name="pres_medic_tel_dec" id="pres_medic_tel_dec">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Accepté par (contact sur place) </label>
                    <input class="form-control" name="pres_medic_contact_dec" id="pres_medic_contact_dec">
                </div>
                <div class="form-group">
                    <label class="control-label">Résumé clinique </label>
                    <textarea class="form-control" name="pres_medic_resume_clinic" id="pres_medic_resume_clinic"></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label">De / Vers aéroport </label>
                    <select class="form-control" name="pres_medic_aeroport" id="pres_medic_aeroport">
                        <option value="Destination">Destination</option>
                        <option value="Origine">Origine</option>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Pays </label>
                            <input class="form-control" name="pres_medic_pays" id="pres_medic_pays">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Vol N° </label>
                            <input class="form-control" name="pres_medic_vol" id="pres_medic_vol">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Type de siège </label>
                    <select class="form-control" name="pres_medic_type_siege" id="pres_medic_type_siege">
                        <option value="Siege normal">Siege normal</option>
                        <option value="Extra-seat">Extra-seat</option>
                        <option value="Civiere">Civière</option>
                        <option value="Avion sanitaire">Avion sanitaire</option>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Heure décollage/atterrissage </label>
                            <input class="form-control" name="pres_medic_takeoff" id="pres_medic_takeoff">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Heure d'arrivée souhaitée </label>
                            <input class="form-control" name="pres_medic_landing" id="pres_medic_landing">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Récupération billets à </label>
                            <input class="form-control" name="pres_medic_billet" id="pres_medic_billet">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">PNR </label>
                            <input class="form-control" name="pres_medic_pnr" id="pres_medic_pnr">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Remarque </label>
                    <textarea class="form-control" name="pres_medic_remarque" id="pres_medic_remarque"></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label">Date départ base </label>
                    <input class="form-control" name="pres_medic_depart_base" id="pres_medic_depart_base">
                </div>
                <div class="form-group">
                    <label class="control-label">Date retour base </label>
                    <input class="form-control" name="pres_medic_retour_base" id="pres_medic_retour_base">
                </div>
                <div class="form-group">
                    <label class="control-label">Heure de départ</label>
                    <div class="row">
                        <div class="col-md-6">
                            <select class="form-control" name="pres_medic_heure_depart" id="pres_medic_heure_depart">
                                <option value="00">00</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16" selected="selected">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select class="form-control" name="pres_medic_minute_depart" id="pres_medic_minute_depart">
                                <option value="00" selected="selected">00</option>
                                <option value="15">15</option>
                                <option value="30">30</option>
                                <option value="45">45</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Heure d'arrivée</label>
                    <div class="row">
                        <div class="col-md-6">
                            <select class="form-control" name="pres_medic_heure_arrivee" id="pres_medic_heure_arrivee">
                                <option value="00">00</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16" selected="selected">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select class="form-control" name="pres_medic_minute_arrivee" id="pres_medic_minute_arrivee">
                                <option value="00" selected="selected">00</option>
                                <option value="15">15</option>
                                <option value="30">30</option>
                                <option value="45">45</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Véhicule</label>
                    <select class="form-control" name="pres_medic_vehic" id="pres_medic_vehic">
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Médecin</label>
                    <input class="form-control" name="pres_medic_medecin" id="pres_medic_medecin">
                </div>
                <div class="form-group">
                    <label class="control-label">Première étape (hôtel ?)</label>
                    <input class="form-control" name="pres_medic_hotel" id="pres_medic_hotel">
                </div>
                <div class="form-group">
                    <div class="checkbox-list">
                        <label><div class="checker" id="uniform-pres_medic_rea"><span><input type="checkbox" name="pres_medic_rea" id="pres_medic_rea" value="1"></span></div> Réa</label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Infirmier(e)</label>
                    <select class="form-control" name="pres_medic_infirmier" id="pres_medic_infirmier">
                    </select>
                </div>
                <div class="form-group">
                    <div class="checkbox-list">
                        <label><div class="checker" id="uniform-pres_medic_urg_anesth"><span><input type="checkbox" name="pres_medic_urg_anesth" id="pres_medic_urg_anesth" value="1"></span></div> Urgentiste / Anesthésiste</label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Ambulancier 1</label>
                    <select class="form-control" name="pres_medic_ambu1" id="pres_medic_ambu1">
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Heures supplémentaires</label>
                    <input class="form-control" name="pres_medic_heuresup1" id="pres_medic_heuresup1">
                </div>
                <div class="form-group">
                    <label class="control-label">Ambulancier 2</label>
                    <select class="form-control" name="pres_medic_ambu2" id="pres_medic_ambu2">
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Heures supplémentaires</label>
                    <input class="form-control" name="pres_medic_heuresup2" id="pres_medic_heuresup2">
                </div>
                <div class="form-group">
                    <label class="control-label">Départ base mission</label>
                    <input class="form-control" name="pres_medic_depart_mission" id="pres_medic_depart_mission">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Lieu d'arrivée</label>
                            <input class="form-control" name="pres_medic_lieu_arrivee" id="pres_medic_lieu_arrivee">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Gouvernorat d'arrivée</label>
                            <select class="form-control" name="pres_medic_gouv_arrivee" id="pres_medic_gouv_arrivee">
                                <option value="ARIANA">ARIANA</option>
                                <option value="MANOUBA">MANOUBA</option>
                                <option value="BEN AROUS">BEN AROUS</option>
                                <option value="BIZERTE">BIZERTE</option>
                                <option value="NABEUL">NABEUL</option>
                                <option value="ZAGHOUAN">ZAGHOUAN</option>
                                <option value="SOUSSE">SOUSSE</option>
                                <option value="MONASTIR">MONASTIR</option>
                                <option value="MAHDIA">MAHDIA</option>
                                <option value="SFAX">SFAX</option>
                                <option value="GABES">GABES</option>
                                <option value="GAFSA">GAFSA</option>
                                <option value="TOZEUR">TOZEUR</option>
                                <option value="KEBILI">KEBILI</option>
                                <option value="MEDENINE">MEDENINE</option>
                                <option value="KAIROUAN">KAIROUAN</option>
                                <option value="KASSERINE">KASSERINE</option>
                                <option value="BEJA">BEJA</option>
                                <option value="JENDOUBA">JENDOUBA</option>
                                <option value="LE KEF">LE KEF</option>
                                <option value="SILIANA">SILIANA</option>
                                <option value="SIDI BOUZID">SIDI BOUZID</option>
                                <option value="TUNIS">TUNIS</option>
                                <option value="TATAOUINE">TATAOUINE</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Lieu de départ</label>
                            <input class="form-control" name="pres_medic_lieu_depart" id="pres_medic_lieu_depart">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Gouvernorat de départ</label>
                            <select class="form-control" name="pres_medic_gouv_depart" id="pres_medic_gouv_depart">
                                <option value="ARIANA">ARIANA</option>
                                <option value="MANOUBA">MANOUBA</option>
                                <option value="BEN AROUS">BEN AROUS</option>
                                <option value="BIZERTE">BIZERTE</option>
                                <option value="NABEUL">NABEUL</option>
                                <option value="ZAGHOUAN">ZAGHOUAN</option>
                                <option value="SOUSSE">SOUSSE</option>
                                <option value="MONASTIR">MONASTIR</option>
                                <option value="MAHDIA">MAHDIA</option>
                                <option value="SFAX">SFAX</option>
                                <option value="GABES">GABES</option>
                                <option value="GAFSA">GAFSA</option>
                                <option value="TOZEUR">TOZEUR</option>
                                <option value="KEBILI">KEBILI</option>
                                <option value="MEDENINE">MEDENINE</option>
                                <option value="KAIROUAN">KAIROUAN</option>
                                <option value="KASSERINE">KASSERINE</option>
                                <option value="BEJA">BEJA</option>
                                <option value="JENDOUBA">JENDOUBA</option>
                                <option value="LE KEF">LE KEF</option>
                                <option value="SILIANA">SILIANA</option>
                                <option value="SIDI BOUZID">SIDI BOUZID</option>
                                <option value="TUNIS">TUNIS</option>
                                <option value="TATAOUINE">TATAOUINE</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- div class="form-group">
                    <label>Distance à parcourir (km)</label>
                    <input type="text" readonly="readonly" value="pres_medic_distance" id="pres_medic_distance" class="form-control">
                </div -->
                <div class="form-group">
                    <label class="control-label">Destination arrivée</label>
                    <input class="form-control" name="pres_medic_dest_arrivee" id="pres_medic_dest_arrivee">
                </div>
                <div class="form-group">
                    <label class="control-label">Départ vers base</label>
                    <input class="form-control" name="pres_medic_depart_vers_base" id="pres_medic_depart_vers_base">
                </div>
                <div class="form-group">
                    <label class="control-label">Arrivée base</label>
                    <input class="form-control" name="pres_medic_arrivee_base" id="pres_medic_arrivee_base">
                </div>

                <div class="form-group">
                    <label class="control-label">Heures d'attente</label>
                    <input class="form-control" name="pres_medic_heures_attente" id="pres_medic_heures_attente">
                </div>

                <div class="form-group">
                    <label class="control-label">Distance parcourue</label>
                    <div class="input-group">
                        <input class="form-control" name="pres_medic_distance" id="pres_medic_distance">
                        <span class="input-group-btn">
                                        <button id="calcMontantBtn" class="btn btn-success" type="button"><i class="fa fa-dollar fa-fw"></i> Calculer montant</button>
                                    </span>
                    </div>
                </div>
                <div class="form-group" style="display:none">
                    <label class="control-label">Montant</label>
                    <input class="form-control" name="pres_medic_montant2" id="pres_medic_montant2" readonly="readonly">
                </div>
                <div class="form-group">
                    <label class="control-label">Durée de mission</label>
                    <input class="form-control" name="pres_medic_duree_mission" id="pres_medic_duree_mission">
                </div>
            </div>

            <!-- BEGIN BLOC X-PRESS REMORQUAGE -->
            <div id="bloc_xpress" style="display:none">
                <div class="form-group">
                    <div class="checkbox-list">
                        <label>
                            <div class="checker" id="uniform-pres_xpress_valid"><span><input type="checkbox" name="pres_xpress_valid" id="pres_xpress_valid" value="1"></span></div> Valide ? </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Pour le</label>
                    <input class="form-control" name="pres_xpress_date" id="pres_xpress_date">

                </div>
                <div class="form-group">
                    <div class="checkbox-list">
                        <label class="checkbox-inline"><div class="checker" id="uniform-pres_xpress_date_dimanche"><span><input type="checkbox" name="pres_xpress_date_dimanche" id="pres_xpress_date_dimanche" value="1"></span></div> Dimanche </label>
                        <label class="checkbox-inline"><div class="checker" id="uniform-pres_xpress_date_ferie"><span><input type="checkbox" name="pres_xpress_date_ferie" id="pres_xpress_date_ferie" value="1"></span></div> Férié </label>
                        <label class="checkbox-inline"><div class="checker" id="uniform-pres_xpress_date_nuit"><span><input type="checkbox" name="pres_xpress_date_nuit" id="pres_xpress_date_nuit" value="1"></span></div> Nuit </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Date demande</label>
                    <input class="form-control" name="pres_xpress_date_demande" id="pres_xpress_date_demande">

                </div>
                <div class="form-group">
                    <label class="control-label">Heure</label>
                    <input class="form-control" name="pres_xpress_heure" id="pres_xpress_heure">

                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Origine de la demande</label>
                            <input class="form-control" name="pres_xpress_origin" id="pres_xpress_origin">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Tel</label>
                            <input class="form-control" name="pres_xpress_tel" id="pres_xpress_tel">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Personne ayant fait la demande</label>
                    <input class="form-control" name="pres_xpress_pers_init" id="pres_xpress_pers_init">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Type voiture </label>
                            <input class="form-control" name="pres_xpress_type_voiture" id="pres_xpress_type_voiture">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Immatriculation voiture </label>
                            <input class="form-control" name="pres_xpress_immatriculation" id="pres_xpress_immatriculation">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Identité personne à transporter</label>
                    <input class="form-control" name="pres_xpress_pers_trans" id="pres_xpress_pers_trans">
                </div>
                <p><strong>Accompagnants : </strong></p>
                <div class="form-group">
                    <label class="control-label">Nombre de personnes à transporter</label>
                    <input class="form-control" name="pres_xpress_nb_passagers" id="pres_xpress_nb_passagers">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Lieu de prise en charge </label>
                            <input class="form-control" name="pres_xpress_lieu_pec" id="pres_xpress_lieu_pec">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Tel </label>
                            <input class="form-control" name="pres_xpress_tel_pec" id="pres_xpress_tel_pec">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Lieu de décharge </label>
                            <input class="form-control" name="pres_xpress_lieu_dec" id="pres_xpress_lieu_dec">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Tel </label>
                            <input class="form-control" name="pres_xpress_tel_dec" id="pres_xpress_tel_dec">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Bateau à prendre </label>
                            <input class="form-control" name="pres_xpress_bateau" id="pres_xpress_bateau">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Départ bateau </label>
                            <input class="form-control" name="pres_xpress_depart_bateau" id="pres_xpress_depart_bateau">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Présentation au port </label>
                    <input class="form-control" name="pres_xpress_pres_port" id="pres_xpress_pres_port">
                </div>
                <div class="form-group">
                    <div class="checkbox-list">
                        <label>
                            <div class="checker" id="uniform-pres_xpress_assist_port"><span><input type="checkbox" name="pres_xpress_assist_port" id="pres_xpress_assist_port" value="1"></span></div> Assistance au port ? </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Nombre d'heures d'attente à remplir par le chauffeur </label>
                    <input class="form-control" name="pres_xpress_hattente" id="pres_xpress_hattente">
                </div>
                <div class="form-group">
                    <label class="control-label">Compagnie maritime </label>
                    <input class="form-control" name="pres_xpress_compagnie" id="pres_xpress_compagnie">
                </div>
                <div class="form-group">
                    <label class="control-label">Majoration </label>
                    <input class="form-control" name="pres_xpress_majoration" id="pres_xpress_majoration">
                </div>
                <div class="form-group">
                    <label class="control-label">Remarque </label>
                    <textarea class="form-control" name="pres_xpress_remarque" id="pres_xpress_remarque"></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label">Date départ base </label>
                    <input class="form-control" name="pres_xpress_depart_base" id="pres_xpress_depart_base">
                </div>
                <div class="form-group">
                    <label class="control-label">Date retour base </label>
                    <input class="form-control" name="pres_xpress_retour_base" id="pres_xpress_retour_base">
                </div>
                <div class="form-group">
                    <label class="control-label">Heure de départ</label>
                    <div class="row">
                        <div class="col-md-6">
                            <select class="form-control" name="pres_xpress_heure_depart" id="pres_xpress_heure_depart">
                                <option value="00">00</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16" selected="selected">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select class="form-control" name="pres_xpress_minute_depart" id="pres_xpress_minute_depart">
                                <option value="00" selected="selected">00</option>
                                <option value="15">15</option>
                                <option value="30">30</option>
                                <option value="45">45</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Heure d'arrivée</label>
                    <div class="row">
                        <div class="col-md-6">
                            <select class="form-control" name="pres_xpress_heure_arrivee" id="pres_xpress_heure_arrivee">
                                <option value="00">00</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16" selected="selected">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select class="form-control" name="pres_xpress_minute_arrivee" id="pres_xpress_minute_arrivee">
                                <option value="00" selected="selected">00</option>
                                <option value="15">15</option>
                                <option value="30">30</option>
                                <option value="45">45</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Véhicule</label>
                    <select class="form-control" name="pres_xpress_vehic" id="pres_xpress_vehic">
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Chauffeur</label>
                    <select class="form-control" name="pres_xpress_chaffeur" id="pres_xpress_chauffeur">
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Heures supplémentaires</label>
                    <input class="form-control" name="pres_xpress_heuresup1" id="pres_xpress_heuresup1">
                </div>
                <div class="form-group">
                    <label class="control-label">Départ base mission</label>
                    <input class="form-control" name="pres_xpress_depart_mission" id="pres_xpress_depart_mission">
                </div>
                <div class="form-group">
                    <label class="control-label">Lieu d'arrivée</label>
                    <input class="form-control" name="pres_xpress_lieu_arrivee" id="pres_xpress_lieu_arrivee">
                </div>
                <div class="form-group">
                    <label class="control-label">Lieu de départ</label>
                    <input class="form-control" name="pres_xpress_lieu_depart" id="pres_xpress_lieu_depart">
                </div>
                <div class="form-group">
                    <label class="control-label">Destination arrivée</label>
                    <input class="form-control" name="pres_xpress_dest_arrivee" id="pres_xpress_dest_arrivee">
                </div>
                <div class="form-group">
                    <label class="control-label">Départ vers base</label>
                    <input class="form-control" name="pres_xpress_depart_vers_base" id="pres_xpress_depart_vers_base">
                </div>
                <div class="form-group">
                    <label class="control-label">Arrivée base</label>
                    <input class="form-control" name="pres_xpress_arrivee_base" id="pres_xpress_arrivee_base">
                </div>
                <div class="form-group">
                    <label class="control-label">Distance parcourue</label>
                    <input class="form-control" name="pres_xpress_distance" id="pres_xpress_distance">
                </div>
                <div class="form-group">
                    <label class="control-label">Durée de mission</label>
                    <input class="form-control" name="pres_xpress_duree_mission" id="pres_xpress_duree_mission">
                </div>
            </div>
            <!-- END BLOC X-PRESS REMORQUAGE -->

            <!-- BEGIN BLOC MEDIC INTERNATIONAL -->
            <div id="bloc_medic_i" style="display:none">
                <div class="form-group">
                    <div class="checkbox-list">
                        <label>
                            <div class="checker" id="uniform-pres_medici_valid"><span><input type="checkbox" name="pres_medici_valid" id="pres_medici_valid" value="1"></span></div> Valide ? </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="checkbox-list">
                        <label class="checkbox-inline"><div class="checker" id="uniform-pres_medici_concent_o2"><span><input type="checkbox" name="pres_medici_concent_o2" id="pres_medici_concent_o2" value="1"></span></div> Concentrateur d'oxygène </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="checkbox-list">
                        <label class="checkbox-inline"><div class="checker" id="uniform-pres_medici_adl_simple"><span><input type="checkbox" name="pres_medici_adl_simple" id="pres_medici_adl_simple" value="1"></span></div> Lot ADL simple </label>
                        <label class="checkbox-inline"><div class="checker" id="uniform-pres_medici_adl_renforce"><span><input type="checkbox" name="pres_medici_adl_renforce" id="pres_medici_adl_renforce" value="2"></span></div> Lot ADL renforcé </label>
                        <label class="checkbox-inline"><div class="checker" id="uniform-pres_medici_evasan_complet"><span><input type="checkbox" name="pres_medici_evasan_complet" id="pres_medici_evasan_complet" value="3"></span></div> Lot EVASAN complet </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Date demande</label>
                    <input class="form-control" name="pres_medici_date_demande" id="pres_medici_date_demande">

                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Origine de la demande</label>
                            <input class="form-control" name="pres_medici_origin" id="pres_medici_origin">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Tel</label>
                            <input class="form-control" name="pres_medici_tel" id="pres_medici_tel">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Personne ayant fait la demande</label>
                    <input class="form-control" name="pres_medici_pers_init" id="pres_medici_pers_init">
                </div>
                <div class="form-group">
                    <label class="control-label">Identité patient</label>
                    <input class="form-control" name="pres_medici_nom_patient" id="pres_medici_nom_patient">
                </div>
                <div class="form-group">
                    <label class="control-label">Hospitalisé/Hôtel</label>
                    <input class="form-control" name="pres_medici_hosp_hotel" id="pres_medici_hosp_hotel">
                </div>
                <div class="form-group">
                    <label class="control-label">Date de départ </label>
                    <input class="form-control" name="pres_medici_date_depart" id="pres_medici_date_depart">
                </div>
                <div class="form-group">
                    <label class="control-label">Heure de départ </label>
                    <div class="row">
                        <div class="col-md-6">
                            <select class="form-control" name="pres_medici_heure_depart" id="pres_medici_heure_depart">
                                <option value="00">00</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16" selected="selected">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select class="form-control" name="pres_medici_minute_depart" id="pres_medici_minute_depart">
                                <option value="00" selected="selected">00</option>
                                <option value="15">15</option>
                                <option value="30">30</option>
                                <option value="45">45</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Lieu de départ </label>
                    <input class="form-control" name="pres_medici_lieu_depart" id="pres_medici_lieu_depart">
                </div>
                <div class="form-group">
                    <label class="control-label">Heure de PEC annoncée au client</label>
                    <div class="row">
                        <div class="col-md-6">
                            <select class="form-control" name="pres_medici_heure_pec" id="pres_medici_heure_pec">
                                <option value="00">00</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16" selected="selected">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select class="form-control" name="pres_medici_minute_pec" id="pres_medici_minute_pec">
                                <option value="00" selected="selected">00</option>
                                <option value="15">15</option>
                                <option value="30">30</option>
                                <option value="45">45</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">Heure départ clinique</label>
                    <div class="row">
                        <div class="col-md-6">
                            <select class="form-control" name="pres_medici_heure_depart_clinique" id="pres_medici_heure_depart_clinique">
                                <option value="00">00</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16" selected="selected">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select class="form-control" name="pres_medici_minute_depart_clinique" id="pres_medici_minute_depart_clinique">
                                <option value="00" selected="selected">00</option>
                                <option value="15">15</option>
                                <option value="30">30</option>
                                <option value="45">45</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label">N° de vol </label>
                    <input class="form-control" name="pres_medici_num_vol" id="pres_medici_num_vol">
                </div>
                <div class="form-group">
                    <label class="control-label">Lieu d'arrivée </label>
                    <input class="form-control" name="pres_medici_lieu_arrivee" id="pres_medici_lieu_arrivee">
                </div>
                <div class="form-group">
                    <label class="control-label">Ambulance(s) </label>
                    <input class="form-control" name="pres_medici_ambu_arrivee" id="pres_medici_ambu_arrivee">
                </div>
                <div class="form-group">
                    <label class="control-label">Accepté par (contact sur place) </label>
                    <input class="form-control" name="pres_medici_contact_dec" id="pres_medici_contact_dec">
                </div>
                <div class="form-group">
                    <label class="control-label">Aéroport / Hôtel </label>
                    <input class="form-control" name="pres_medici_aerop_hotel" id="pres_medici_aerop_hotel">
                </div>
                <div class="form-group">
                    <label class="control-label">Date retour</label>
                    <input class="form-control" name="pres_medici_date_retour" id="pres_medici_date_retour">
                </div>
                <div class="form-group">
                    <label class="control-label">Heure de passage du taxi </label>
                    <input class="form-control" name="pres_medici_heure_taxi" id="pres_medici_heure_taxi">
                </div>
                <div class="form-group">
                    <label class="control-label">Aéroport retour </label>
                    <input class="form-control" name="pres_medici_aerop_retour" id="pres_medici_aerop_retour">
                </div>
                <div class="form-group">
                    <label class="control-label">Vol retour </label>
                    <input class="form-control" name="pres_medici_vol_retour" id="pres_medici_vol_retour">
                </div>
            </div>
            <!-- END BLOC MEDIC INTERNATION -->


        </form>
    </div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/places.js@1.16.4"></script>


<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>


<script>
    $(function () {

        $('#gouvcouv').select2({
            filter: true,
            language: {
                noResults: function () {
                    return 'Pas de résultats';
                }
            }

        });

        $('#typeprest').select2({
            filter: true,
            language: {
                noResults: function () {
                    return 'Pas de résultats';
                }
            }

        });

    });


    function changing(elm) {
        var champ=elm.id;

        var val =document.getElementById(champ).value;
        //  var type = $('#type').val();
        var prestataire = $('#idpres').val();
        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('prestataires.updating') }}",
            method: "POST",
            data: {prestataire: prestataire , champ:champ ,val:val, _token: _token},
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

        var val =1;
        var prestataire = $('#idpres').val();
        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('prestataires.updating') }}",
            method: "POST",
            data: {prestataire: prestataire , champ:champ ,val:val, _token: _token},
            success: function (data) {
                if (elm=='annule'){
                    $('#nonactif').animate({
                        opacity: '0.3',
                    });
                    $('#nonactif').animate({
                        opacity: '1',
                    });
                }


            }
        });
        // } else {

        // }
    }

</script>
