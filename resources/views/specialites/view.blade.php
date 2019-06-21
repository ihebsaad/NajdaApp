@extends('layouts.mainlayout')

@section('content')
    <div class="portlet box grey">
        <div class="modal-header">Spécialités</div>
    </div><div class="form-group">
     {{ csrf_field() }}

<div class="modal-body">
    <form id="updateform">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Nom *</label>
                                <input onchange="changing(this)" type="text" class="form-control input" name="nom" id="nom"  value="{{ $specialite->nom }}">
                            </div>
                        </div>

 


                        <input type="hidden" id="idtp" class="form-control"   value="{{ $specialite->id }}">
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
        var specialite = $('#idtp').val();

        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('specialites.updating') }}",
            method: "POST",
            data: {specialite: specialite , champ:champ ,val:val, _token: _token},
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

    function disabling(elm) {
        var champ=elm;

        var val =1;
        var specialite = $('#idtp').val();

        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('specialites.updating') }}",
            method: "POST",
            data: {specialite: specialite , champ:champ ,val:val, _token: _token},
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

    }

</script>
