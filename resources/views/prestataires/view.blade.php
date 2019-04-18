@extends('layouts.mainlayout')

@section('content')
<div class="form-group">
     {{ csrf_field() }}

<div class="modal-body">
    <form id="updateform">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Nom *</label>
                                <input onchange="changing(this)" type="text" class="form-control input" name="nom" id="nom"  value={{ $prestataire->nom }}>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="inputError" class="control-label">Spécialité *</label>
                                    <input onchange="changing(this)" type="text" class="form-control input" name="specialite" id="specialite"  value={{ $prestataire->specialite }}>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <label for="form_control_1">Observation prestataire<span class="required"> * </span></label>
                                <textarea onchange="changing(this)" rows="2" class="form-control" name="observation" id="observation"> {{$prestataire->typepres}} </textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pririoté</label>
                                <select onchange="changing(this)" id="priorite" name="priorite" class="form-control"   value={{ $prestataire->priorite }}>
                                    <option <?php if ($prestataire->priorite ==''){echo 'selected="selected"';} ?> value="0"></option>
                                    <option  <?php if ($prestataire->priorite =='1'){echo 'selected="selected"';} ?>value="1">1</option>
                                    <option  <?php if ($prestataire->priorite =='2'){echo 'selected="selected"';} ?>value="2">2</option>
                                    <option  <?php if ($prestataire->priorite =='3'){echo 'selected="selected"';} ?> value="3">3</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Type prestation *</label>
                                <div class="select2-container select2-container-multi form-control" id="s2id_typepres">


                                </div>
                                <select onchange="changing(this)" id="typepres" name="typepres[]" multiple="multiple" class="form-control select2-offscreen" tabindex="-1" value={{ $prestataire->typepres }}>
                                    <option value="0"></option>
                                    <option value="51">Abandon de véhicule</option>
                                    <option value="12">Accompagnement Médical</option>
                                    <option value="14">Accompagnement non-médical</option>
                                    <option value="13">Accompagnement Paramédical</option>
                                    <option value="16">Agence de voyage</option>
                                    <option value="4">Ambulances</option>
                                    <option value="70">Appartements en location</option>
                                    <option value="48">Apuration de passeport</option>
                                    <option value="38">Assistance au port</option><option value="24">Assistance Douane</option>
                                    <option value="49">Autorisation de sortie sans véhicule</option><option value="29">Autres</option>
                                    <option value="28">Avance de fonds</option><option value="27">Avocat</option><option value="19">Billetterie aérienne</option><option value="20">Billetterie maritime</option><option value="36">Cabinet médical consultation</option><option value="63">Centre de Dialyse</option><option value="6">Centre d’imagerie médicale</option><option value="8">Clinique</option><option value="3">Concessionnaire</option><option value="46">Contrôle de facture de frais médicaux</option><option value="26">Correspondant</option><option value="33">Correspondant étranger</option><option value="35">Couverture médicale</option><option value="57">Couveuse</option><option value="44">Dentiste</option><option value="47">Dépannage</option><option value="55">Déplacement</option><option value="21">EVASAN</option><option value="23">Expertise auto</option><option value="56">Extracteur d'oxygène</option><option value="34">Frais médicaux</option><option value="22">Garages</option><option value="30">Gardiennage</option><option value="9">Hôpital</option><option value="18">Hôtel</option><option value="45">Infirmier (soins)</option><option value="61">Kinésitherapeute/Physiothérapeute</option><option value="7">Laboratoire d’analyses</option><option value="17">Location de voitures</option><option value="41">Lot ADL</option><option value="42">Lot ADL renforcé</option><option value="43">Lot complet evasan</option><option value="53">Matériel médical - location</option><option value="10">Matériel médical - vente</option><option value="15">Médecin traitant</option><option value="37">Médecin transporteur</option><option value="67">Médecin transporteur pédiatrique</option><option value="66">Médecin transporteur réa</option><option value="60">Opticien</option><option value="64">Pharmacie</option><option value="32">Pompes funèbres</option><option value="1">Remorquage</option><option value="54">Remorquage et transfert de personnes</option><option value="58">Respirateur spécial Elysée</option><option value="65">Super poids lourd</option><option value="2">Taxi</option><option value="69">Traduction non médicale</option><option value="52">Traduction rapport médical</option><option value="40">Transitaire</option><option value="71">Transport sous assistance</option><option value="5">Visite médicale</option><option value="68">Visite pédiatrique</option><option value="39">VSL</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row">


                        <div class="col-md-10">
                            <div class="form-group">
                                <label>Ville du siège social</label>

                                <input onchange="changing(this)" type="text" class="form-control input" name="ville" id="ville" value={{ $prestataire->fax }}>

                            </div>
                        </div>

                        <script>
                            var placesAutocomplete = places({
                                appId: 'plCFMZRCP0KR',
                                apiKey: 'aafa6174d8fa956cd4789056c04735e1',
                                container: document.querySelector('#ville')
                            });
                        </script>

                    </div>


                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Adresse </label>
                                <input onchange="changing(this)" type="text" class="form-control input" name="adresse" id="adresse"  value={{ $prestataire->adresse }}>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Fax</label>
                                <input onchange="changing(this)" type="text" id="fax" class="form-control" name="fax"  value={{ $prestataire->fax }}>
                            </div>
                        </div>

                    </div>
        
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Mobile 1</label>
                                <input onchange="changing(this)" type="text" id="mobile" class="form-control" name="mobile"  value={{ $prestataire->mobile }}>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Mobile 2 </label>
                                <input onchange="changing(this)" type="text" id="mobile2" class="form-control" name="mobile2"  value={{ $prestataire->mobile2 }}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Téléphone 1</label>
                                <input onchange="changing(this)" type="text" id="telephone" class="form-control" name="telephone"  value={{ $prestataire->telephone }}>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Téléphone 2</label>
                                <input onchange="changing(this)" type="text" id="telephone2" class="form-control" name="telephone2"  value={{ $prestataire->telephone2 }}>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Email </label>
                                <input onchange="changing(this)"  type="text" id="email" class="form-control" name="email" placeholder="Email"  value={{ $prestataire->email }}>
                                <br>
                                <span id="email01">
                                    <input onchange="changing(this)"  type="text" id="email2" name="email2" class="form-control" placeholder="Email2"  value={{ $prestataire->email2 }}>
                                    <br>
                                    <span id="email02">
                                        <input onchange="changing(this)" type="text" id="email3"  name="email3" class="form-control" placeholder="Email3"  value={{ $prestataire->email3 }}>
                                        <br>
                                        <span id="email03">
                                            <input onchange="changing(this)" type="text" id="email4" name="email4" class="form-control" placeholder="Email4"  value={{ $prestataire->email4 }}>
                                            <br>
                                            <span id="email04">
                                                <input onchange="changing(this)" type="text" id="email5"  name="email5" class="form-control" placeholder="Email5"  value={{ $prestataire->email5 }}>
                                            </span>
                                        </span>
                                    </span>
                                </span>
                                
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="row">
                            <div class="col-md-4">
                                <label style="padding-top:10px">Actif</label>
                            </div>
                                <div class="radio-list">
                                    <div class="col-md-3">
                                    <label for="actif" class="">
                                        <div class="radio" id="uniform-actif"><span class="checked">
                                                <input  onclick="changing(this)" type="radio" name="annule" id="annule" value="0"   <?php if ($prestataire->annule ==0){echo 'checked';} ?>></span></div> Oui
                                    </label>
                                    </div>
                                    <div class="col-md-3">
                                    <label for="nonactif" class="">
                                        <div class="radio" id="uniform-nonactif"><span>
                                                <input onclick="disabling('annule')" type="radio" name="annule" id="nonactif" value="1"  <?php if ($prestataire->annule ==1){echo 'checked';} ?>></span></div> Non
                                    </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                     </div>
                    <input type="hidden" id="idpres" class="form-control"   value={{ $prestataire->id }}>
    </form>
                </div>	 

  </div>

@endsection

<script src="https://cdn.jsdelivr.net/npm/places.js@1.16.4"></script>


<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>


<script>

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
