@extends('layouts.mainlayout')

@section('content')
<div class="form-group">
     {{ csrf_field() }}
<!--
<div class="form-group">
    <label for="affecte">Agent :</label>
    <input id="affecte" type="text" class="form-control" name="affecte" style="width:100px" value={{ $dossier->created_by }} />
</div>
-->

         <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
         <link rel="stylesheet" href="/resources/demos/style.css">
         <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
         <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
         <script>
             $( function() {
                 $( "#initial_arrival_date" ).datepicker();
             } );
         </script>

                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_dossier">
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
                                                                                    <input onchange="changing(this)" type="text1" id="reference_medic" name="reference_medic" class="form-control" disabled=""   value={{ $dossier->referance_medic }} >
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
                                                                            <select id="customer_id" name="customer_id" class="form-control js-example-placeholder-single"><option value="0">Sélectionner..... </option>
                                                                                <option value="87">ACI Global</option><option value="82">ADAC Ambulance Services</option>
                                                                                <option value="201">AFRIC ASSISTANCE &amp; SERVICES</option>
                                                                                <option value="187">Afrique Assistance</option><option value="90">Aide Barcelona </option><option value="237">Allianz Care Helpline Libya</option><option value="67">ALLIANZ GLOBAL ASSISTANCE  Switzerland (AGA)</option><option value="212">ALLIANZ GLOBAL ASSISTANCE Australia (AGA)</option><option value="19">ALLIANZ GLOBAL ASSISTANCE Belgium (AGA)</option><option value="70">ALLIANZ GLOBAL ASSISTANCE Canada (AGA)</option><option value="115">ALLIANZ GLOBAL ASSISTANCE China (AGA)</option><option value="147">ALLIANZ GLOBAL ASSISTANCE Germany (AGA)</option><option value="21">ALLIANZ GLOBAL ASSISTANCE Italia (AGA)</option><option value="20">ALLIANZ GLOBAL ASSISTANCE Spain (AGA)</option><option value="211">ALLIANZ GLOBAL ASSISTANCE Turkey (AGA)</option><option value="30">ALLIANZ GLOBAL ASSISTANCE UK (AGA)</option><option value="143">ALLIANZ GLOBAL ASSISTANCE USA (AGA)</option><option value="85">ALLIANZ WOLRDWIDE PARTNERS Portugal (AWP)</option><option value="170">ALLIANZ WORLDWIDE CARE</option><option value="17">ALLIANZ WORLDWIDE PARTNERS France (AWP) </option><option value="28">ALLIANZ WORLDWIDE PARTNERS Greece (AWP)</option><option value="31">ALLIANZ WORLDWIDE PARTNERS Netherlands (AWP)</option><option value="29">ALLIANZ WORLDWIDE PARTNERS Poland (AWP)</option><option value="99">ALLIANZ WORLDWIDE PARTNERS Tchequie (AWP)</option><option value="16">almeda GmbH</option><option value="164">Alpine Air Ambulance</option><option value="61">Ambassade d'Allemagne à Tunis</option><option value="14">ANWB</option><option value="216">APO (Amilcar Petroleum Operations S.A)</option><option value="96">Apollo HCA</option><option value="137">ARAG, Compañía Internacional de Seguros y Reasegur</option><option value="158">ARIA Assistance UK</option><option value="40">ASB ASSISTANCE</option><option value="128">ASIAN MEDICAL ASSISTANCE (ARC)</option><option value="183">Assist Card Argentina</option><option value="160">Assist Card Korea</option><option value="79">Assist Card Spain</option><option value="41">ASSISTANCE INTERNATIONAL UK</option><option value="43">ASSURE ASSISTANCE</option><option value="134">ATHENS ASSISTANCE</option><option value="167">Augsburg Air Ambulance</option><option value="110">AXA ASSISTANCE CZ</option><option value="8">AXA ASSISTANCE DEUTSCHLAND</option><option value="5">AXA ASSISTANCE FRANCE</option><option value="208">AXA ASSISTANCE MAROC</option><option value="6">AXA ASSISTANCE UK</option><option value="7">AXA ASSISTANCE USA</option><option value="98">AXA Mauritius</option><option value="186">AXA Travel Insurance (AI &amp; EI)</option><option value="229">Blue Dot Indonesia</option><option value="109">Bupa Global Assistance (Denmark)</option><option value="171">C3 Médical</option><option value="153">CALL ASSISTANCE 24 s.r.o.</option><option value="182">Call Us Assistance International (Axa Autriche)</option><option value="50">CEGA ASSISTANCE</option><option value="154">Clinique Elyousr</option><option value="63">Clinique Essalem</option><option value="64">Clinique La Corniche</option><option value="65">Clinique Les Oliviers</option><option value="62">CORIS Tunisie</option><option value="161">Customer Care PTY Ltd</option><option value="93">Customer Care Solutions</option><option value="146">DRF Luftrettung (DRF Stiftung Luftrettung Gemeinnü</option><option value="140">DRK Assistance (German Red Cross)</option><option value="162">DUALEX</option><option value="9">DZ ASSISTANCE</option><option value="231">Egypt In-Touch Assistance Co</option><option value="234">EMA Group Oy aka Euro-Center Helsinki</option><option value="168">Emergency Assistance Facilities UK</option><option value="176">Euro Union Assistance GmbH (Auto-Club Europa Euro-</option><option value="39">EURO-ALARM DANEMARK</option><option value="22">EUROCENTER Afrique du sud</option><option value="190">EUROCENTER MADRID</option><option value="11">EUROCENTER PRAGUE</option><option value="95">EuroCross Assistance Bulgaria</option><option value="198">EuroCross Assistance Czech Republic</option><option value="55">Eurocross Assistance Netherlands</option><option value="56">Eurocross International Assistance Polska</option><option value="46">EUROP ASSISTANCE AUTRICHE</option><option value="37">EUROP ASSISTANCE FRANCE</option><option value="74">EUROP ASSISTANCE HONGRIE</option><option value="44">EUROP ASSISTANCE ITALIE</option><option value="38">EUROP ASSISTANCE UK</option><option value="84">FAI rent-a-jet AG</option><option value="145">Fidelia Assistance</option><option value="2">FILASSISTANCE</option><option value="107">FLYMEX</option><option value="121">Global Doctor Alarm Center</option><option value="45">GLOBAL VOYAGER ASSISTANCE</option><option value="138">Globemedltd ICSC</option><option value="197">GOBBO Quality Assistance</option><option value="188">Healix International</option><option value="220">IKE Assistência Brasil</option><option value="108">IMA BENELUX</option><option value="24">IMA France (INTER MUTUELLES ASSISTANCE) </option><option value="172">IMA Iberica</option><option value="235">IMA MarocAssistance</option><option value="18">IMA Servizi SRL (Italia)</option><option value="104">IMA UK Assistance Ltd</option><option value="224">IMG Global Response (only for ALC products)</option><option value="223">IMG Global Response, Ltd (except ALC products)</option><option value="114">INTANA GLOBAL</option><option value="151">International SOS Emergency Services (Deutschland)</option><option value="112">International SOS Genève</option><option value="73">International SOS Paris</option><option value="174">IPA Alger</option><option value="214">IPA Athens</option><option value="126">IPA Bruxelles</option><option value="103">IPA ESPAÑA</option><option value="94">IPA Geneva</option><option value="130">IPA Lisbonne Portugal</option><option value="149">IPA Polska</option><option value="150">IPA Roma</option><option value="228">ISAS Inter Secours Assistance Maroc</option><option value="155">Luxembourg Air Ambulance / EAA European Air Ambula</option><option value="159">MALTESER Service Center</option><option value="47">MARM ASSISTANCE</option><option value="106">MD Medicus Assistance Service GmbH</option><option value="113">meda gmbh; InternationaleR Flugkoordination</option><option value="71">Medevasan</option><option value="209">Medic' International</option><option value="58">Medic' Multiservices</option><option value="230">Medical Flight Service</option><option value="13">MEDICALL AG</option><option value="185">MEDILINK (Assistance Cases)</option><option value="207">Medilink TPA divers</option><option value="191">Mideast Assistance International S.A.R.L (Lebanon)</option><option value="68">MONDIAL ASSISTANCE Austria</option><option value="213">MONDIAL ASSISTANCE Brazil</option><option value="210">MONDIAL ASSISTANCE Ireland</option><option value="165">MONDIAL ASSISTANCE Russia</option><option value="181">MSO (Medical Services Organisation South Africa)</option><option value="173">MULTI ASSISTANCE INTERNATIONALE</option><option value="166">MUTAS</option><option value="1">MUTUAIDE ASSISTANCE</option><option value="202">NAJDA ASSISTANCE</option><option value="144">NETCARE</option><option value="12">Nobis Filodiretto (International Care Company s.r.</option><option value="226">Nosok (Yemen)</option><option value="129">OMV Tunesien Production GmbH</option><option value="83">ON CALL INTERNATIONAL</option><option value="66">Opteven Assistance</option><option value="116">PA Resources</option><option value="193">PAS Pronto Assistance Servizi S.c.r.l.</option><option value="60">Privé</option><option value="80">PRO-JET Ambulance</option><option value="133">QBE Atlasz</option><option value="175">Racc Medical Service</option><option value="35">RACE ASISTENCIA</option><option value="196">Rede Nacional de Assistencia</option><option value="101">Rega Swiss Air Ambulance</option><option value="48">REMED ASSISTANCE</option><option value="217">ROLAND Assistance</option><option value="33">SAHAM ASSISTANCE</option><option value="125">Sanitrans - SAA International Assistance Srl</option><option value="157">SAROST</option><option value="118">SEREPT</option><option value="233">SESAME SANTE</option><option value="184">Seven Corners</option><option value="97">SHELL</option><option value="232">SMEDI</option><option value="100">SOS International Amsterdam</option><option value="127">SOS International Danemark (SOS Dansk Autohjaelp)</option><option value="218">SOS International Finland</option><option value="163">SOS International Norvege</option><option value="219">SOS International Suede</option><option value="139">STORM Ventures International</option><option value="236">SWAN International Assistance</option><option value="227">TBS</option><option value="225">THF</option><option value="169">THYNA PETROLEUM SERVICES (TPS)</option><option value="215">TMS</option><option value="52">TOURING CLUB BELGIQUE</option><option value="51">TOURING CLUB SUISSE</option><option value="92">Tyrolair TAA</option><option value="4">VAB S.A.</option><option value="59">Voyages Assistance Tunisie (VAT)</option><option value="36">WORLDWIDE ASSISTANCE</option><option value="122">WTP ASSIST</option><option value="34">ÖAMTC - Schutzbriefverrechnung</option></select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="inputError" class="control-label">Référence *</label>

                                                                            <div class="input-group-control">
                                                                                <input onchange="changing(this)" type="text" id="reference_customer" name="reference_customer" class="form-control"   value={{ $dossier->reference_customer }} >
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
	
@endsection

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>


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
        //  var type = $('#type').val();
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



</script>
