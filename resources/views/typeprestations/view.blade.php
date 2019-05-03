@extends('layouts.mainlayout')

@section('content')
    <div class="portlet box grey">
        <div class="modal-header">Type de prestation</div>
    </div><div class="form-group">
     {{ csrf_field() }}

<div class="modal-body">
    <form id="updateform">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Nom *</label>
                                <input onchange="changing(this)" type="text" class="form-control input" name="name" id="name"  value="{{ $typeprestation->name }}">
                            </div>
                        </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Type</label>
                                <select onchange="changing(this)" id="type" name="type" class="form-control"   value="{{ $typeprestation->type }}">

                                    <option <?php if ($typeprestation->type =='0'){echo 'selected="selected"';} ?> value="0">Indéterminé</option>
                                    <option  <?php if ($typeprestation->type =='1'){echo 'selected="selected"';} ?>value="1">Technique</option>
                                    <option  <?php if ($typeprestation->type =='2'){echo 'selected="selected"';} ?>value="2">Médical</option>

                                </select>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="idtp" class="form-control"   value="{{ $typeprestation->id }}">
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
        var typeprestation = $('#idtp').val();

        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('typeprestations.updating') }}",
            method: "POST",
            data: {typeprestation: typeprestation , champ:champ ,val:val, _token: _token},
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
        var typeprestation = $('#idtp').val();

        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('typeprestations.updating') }}",
            method: "POST",
            data: {typeprestation: typeprestation , champ:champ ,val:val, _token: _token},
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
