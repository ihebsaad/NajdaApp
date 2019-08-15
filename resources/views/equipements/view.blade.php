@extends('layouts.adminlayout')

@section('content')
<div class="form-group">
     {{ csrf_field() }}

<div class="modal-body">
    <div class="portlet box grey">
        <div class="modal-header"><b>Equipement</b></div>
    </div>

    <form id="updateform">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Nom *</label>
                                <input onchange="changing(this)" type="text" class="form-control input" name="nom" id="nom"  value="{{ $equipement->nom }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Référence *</label>
                                <input onchange="changing(this)" type="text" class="form-control input" name="reference" id="reference"  value="{{ $equipement->reference }}">
                            </div>
                        </div>
                    </div>
					  <div class="row">

						<div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Numéro *</label>
                                <input onchange="changing(this)" type="text" class="form-control input" name="numero" id="numero"  value="{{ $equipement->numero }}">
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
                                                <input  onclick="changing(this)" type="radio" name="annule" id="annule" value="0"   <?php if ($equipement->annule ==0){echo 'checked';} ?>></span></div> Oui
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="nonactif" class="">
                                            <div class="radio" id="uniform-nonactif"><span>
                                                <input onclick="disabling('annule')" type="radio" name="annule" id="nonactif" value="1"  <?php if ($equipement->annule ==1){echo 'checked';} ?>></span></div> Non
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                     </div>
        <div class="row" style="margin-top:20px">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="inputError" class="control-label">Date de Début indisponibilité * </label>
                    <input onchange="changing(this)"  type="datetime-local"   class="form-control  " name="date_deb_indisponibilite" id="date_deb_indisponibilite" type="text"   value="{{ $equipement->date_deb_indisponibilite }}" >
                </div>

            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="inputError" class="control-label">Date de Fin indisponibilité *</label>
                    <input onchange="changing(this)"  type="datetime-local"   class="form-control  " name="date_fin_indisponibilite" id="date_fin_indisponibilite" type="text"   value="{{ $equipement->date_fin_indisponibilite }}" >
                </div>

            </div>

			
        </div>


		
        <input type="hidden" id="id" class="form-control"   value={{ $equipement->id }}>
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
        var equipement = $('#id').val();
         //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('equipements.updating') }}",
            method: "POST",
            data: {equipement: equipement , champ:champ ,val:val, _token: _token},
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
        var champ='annule';

        var val =0;
         var equipement = $('#id').val();
        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('equipements.updating2') }}",
            method: "POST",
            data: {equipement: equipement ,  _token: _token},
            success: function (data) {
                 $('#nonactif').animate({
                    opacity: '0.3',
                });
                $('#nonactif').animate({
                    opacity: '1',
                });
                 


            }
        });
        // } else {

        // }
    }

</script>
