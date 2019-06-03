@extends('layouts.mainlayout')

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
                                <div class="col-md-4">
                                    <label style="padding-top:10px">Actif</label>
                                </div>
                                <div class="radio-list">
                                    <div class="col-md-3">
                                        <label for="annule" class="">
                                            <div class="radio" id="uniform-actif"><span class="checked">
                                                <input  onclick="changing(this)" type="radio" name="annule" id="annule" value="0"   <?php if ($voiture->annule ==0){echo 'checked';} ?>></span></div> Oui
                                        </label>
                                    </div>
                                    <div class="col-md-3">
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
                    <input onchange="changing(this)"  data-format="dd-MM-yyyy hh:mm:ss" placeholder="jj-mm-aaaa" class="form-control datepicker-default form-control" name="date_deb_indisponibilite" id="date_deb_indisponibilite" type="text"   value={{ $voiture->date_deb_indisponibilite }} >
                </div>

            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="inputError" class="control-label">Date de Fin indisponibilité *</label>
                    <input onchange="changing(this)"  data-format="dd-MM-yyyy hh:mm:ss" placeholder="jj-mm-aaaa" class="form-control datepicker-default form-control" name="date_fin_indisponibilite" id="date_fin_indisponibilite" type="text"   value={{ $voiture->date_fin_indisponibilite }} >
                </div>

            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="inputError" class="control-label">KM</label>
                    <input onchange="changing(this)"  style="width:120px" class="form-control " name="km" id="km" type="text"   value={{ $voiture->km }} >
                </div>

            </div>
        </div>

        <div class="row" style="margin-top:20px">
            <div class="col-md-5">
                <div class="form-group">
                    <label for="" class="control-label">Type </label>
                    <input onchange="changing(this)"  class="form-control " name="type" id="type" type="text"   value={{ $voiture->type }} >
                </div>

            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label for="" class="control-label">Immarticulation</label>
                    <input onchange="changing(this)"    class="form-control  " name="immarticulation" id="immarticulation" type="text"   value={{ $voiture->immarticulation }} >
                </div>

            </div>

        </div>
        <input type="hidden" id="id" class="form-control"   value={{ $voiture->id }}>
    </form>
      </div>

  </div>

@endsection

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>


<script>

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
         var citie = $('#id').val();
        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('voitures.updating') }}",
            method: "POST",
            data: {citie: citie , champ:champ ,val:val, _token: _token},
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
