@extends('layouts.adminlayout')

@section('content')
<div class="form-group">
     {{ csrf_field() }}

<div class="modal-body">
    <div class="portlet box grey">
        <div class="modal-header"><b>Véhicule</b></div>
    </div>

    <form id="updateform">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Nom *</label>
                                <input onchange="changing(this)" type="text" class="form-control input" name="name" id="name"  value="{{ $voiture->name }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row" style="padding-top:20px">
                                <div class="col-md-2">
                                    <label style="padding-top:10px">Actif:</label>
                                </div>
                                <div class="radio-list">
                                    <div class="col-md-2">
                                        <label for="annule" class="">
                                            <div class="radio" id="uniform-actif"><span class="checked">
                                                <input  onclick="changing(this)" type="radio" name="annule" id="annule" value="0"   <?php if ($voiture->annule ==0){echo 'checked';} ?>></span></div> Oui
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="nonactif" class="">
                                            <div class="radio" id="uniform-nonactif"><span>
                                                <input onclick="disabling('annule')" type="radio" name="annule" id="nonactif" value="1"  <?php if ($voiture->annule ==1){echo 'checked';} ?>></span></div> Non
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                     </div>
        <div class="row" style="margin-top:20px">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="inputError" class="control-label">Date de Début indisponibilité * </label>
                    <input onchange="changing(this)"   class="form-control  " name="date_deb_indisponibilite" id="date_deb_indisponibilite" type="datetime-local"   value="<?php $datedeb=date('Y-m-d H:i', strtotime($voiture->date_deb_indisponibilite)); $datedeb1=str_replace(' ', 'T', $datedeb); if($voiture->date_deb_indisponibilite==null) {$datedeb1="";} echo $datedeb1;?>" >
                </div>

            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="inputError" class="control-label">Date de Fin indisponibilité *</label>
                    <input onchange="changing(this)"   class="form-control  " name="date_fin_indisponibilite" id="date_fin_indisponibilite" type="datetime-local"   value="<?php  $datefin=date('Y-m-d H:i', strtotime($voiture->date_fin_indisponibilite)); $datefin1=str_replace(' ', 'T', $datefin); if($voiture->date_fin_indisponibilite==null) {$datefin1="";} echo $datefin1;?>" >
                </div>

            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="inputError" class="control-label">KM</label>
                    <input onchange="changing(this)"  style="width:120px" class="form-control " name="km" id="km" type="text"   value="{{ $voiture->km }}" >
                </div>

            </div>
        </div>

        <div class="row" style="margin-top:20px">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="" class="control-label">Type </label>
                    <select onchange="changing(this)"  class="form-control " name="type" id="type" type="text"   value="{{ $voiture->type }}" >
                        <option></option>
                        <option <?php if ($voiture['type'] =='Voiture Tourisme'){echo 'selected="selected"';}?> value="Voiture Tourisme">Voiture Tourisme</option>
                        <option <?php if ($voiture['type'] =='4X4'){echo 'selected="selected"';}?> value="4X4">4X4</option>
                        <option <?php if ($voiture['type'] =='Ambulance A Gros Volume'){echo 'selected="selected"';}?>value="Ambulance A Gros Volume">Ambulance A Gros Volume</option>
                        <option <?php if ($voiture['type'] =='Ambulance B'){echo 'selected="selected"';}?>value="Ambulance B">Ambulance B</option>
                        <option <?php if ($voiture['type'] =='Plateau Remorquage'){echo 'selected="selected"';}?>value="Plateau Remorquage">Plateau Remorquage</option>
                    </select>
                </div>

            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="" class="control-label">Fonction </label>
                    <select onchange="changing(this)"  class="form-control " name="fonction" id="fonction" type="text"   value="{{ $voiture->fonction }}" >
                        <option></option>
                        <option <?php if ($voiture['fonction'] =='Taxi'){echo 'selected="selected"';}?> value="Taxi">Taxi</option>
                        <option <?php if ($voiture['fonction'] =='Ambulance'){echo 'selected="selected"';}?> value="Ambulance">Ambulance</option>
                        <option <?php if ($voiture['fonction'] =='Remorquage'){echo 'selected="selected"';}?>value="Remorquage">Remorquage</option>
                        <option <?php if ($voiture['fonction'] =='Location'){echo 'selected="selected"';}?>value="Location">Location</option>
                    </select>
                </div>

            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="" class="control-label">Immarticulation</label>
                    <input onchange="changing(this)"    class="form-control  " name="immarticulation" id="immarticulation" type="text"   value="{{ $voiture->immarticulation }}" >
                </div>
            </div>
        </div>

        <div class="row" style="margin-top:20px">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="" class="control-label">Carte de Carburant</label>
                    <input onchange="changing(this);check(this,100);"  <?php if($voiture->carburant<100){ echo 'style="background-color:#fd9883;color:#ffffff;"';} ?>  class="form-control  " name="carburant" type="number" step="0.1" id="carburant" value="{{ $voiture->carburant }}" >

                </div>
            </div>


             <div class="col-md-4">
                <div class="form-group">
                    <label for="" class="control-label">Carte de télépéage </label>
                    <input <?php if($voiture->telepeage<20){echo 'style="background-color:#fd9883;color:#ffffff"';} ?> onchange="changing(this);check(this,20);"    class="form-control  " name="telepeage" type="number" step="0.1" id="telepeage" value="{{ $voiture->telepeage  }}" >

                </div>
            </div>

        </div>

        <input type="hidden" id="id" class="form-control"   value="{{ $voiture->id }}">
    </form>
      </div>

  </div>

@endsection

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>


<script>
    function check(elm,max) {
        var champ=elm.id;

        var val =document.getElementById(champ).value;
        if(val<max)
        {
            document.getElementById(champ).style.background="#fd9883";
            document.getElementById(champ).style.color="#ffffff";
        }else{
            document.getElementById(champ).style.background="#ffffff";
            document.getElementById(champ).style.color="#000000";
        }

    }
    function changing(elm) {
        var champ=elm.id;

        var val =document.getElementById(champ).value;
        //  var type = $('#type').val();
        var voiture = $('#id').val();
         //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('voitures.updating') }}",
            method: "POST",
            data: {voiture: voiture , champ:champ ,val:val, _token: _token},
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
         var voiture = $('#id').val();
        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('voitures.updating') }}",
            method: "POST",
            data: {voiture: voiture , champ:champ ,val:val, _token: _token},
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
