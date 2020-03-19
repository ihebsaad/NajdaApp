@extends('layouts.adminlayout')

@section('content')
    <div class="portlet box grey">
        <div class="modal-header">Contrat Client</div>
    </div><div class="form-group">
     {{ csrf_field() }}

<div class="modal-body">
    <form id="updateform">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Nom *</label>
                                <input onchange="changing(this)" type="text" class="form-control input" name="nom" id="nom"  value="{{ $contrat->nom }}">
                            </div>
                        </div>
                        <div class="col-md-6">

 						   <div class="form-group">
                                <label for="type">Type :</label>
                                <select class="form-control"  name="type" id="type" onchange="changing(this)"  >
								<option <?php if($contrat->type=='commun'){echo 'selected="selected"';} ?>  value="commun">Commun</option>
								<option <?php if($contrat->type=='particulier'){echo 'selected="selected"';} ?>  value="particulier">Particulier</option>
								</select>

                            </div>
                       </div>


                        <input type="hidden" id="idtp" class="form-control"   value="{{ $contrat->id }}">
             </div>

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
        var contrat = $('#idtp').val();

        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('contrats.updating') }}",
            method: "POST",
            data: {contrat: contrat , champ:champ ,val:val, _token: _token},
            success: function (data) {
                $('#'+champ).animate({
                    opacity: '0.3',
                });
                $('#'+champ).animate({
                    opacity: '1',
                });

            }
        });

    }



</script>
