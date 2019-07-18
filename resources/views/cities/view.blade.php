@extends('layouts.adminlayout')

@section('content')
<div class="form-group">
     {{ csrf_field() }}

<div class="modal-body">
    <form id="updateform">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Nom *</label>
                                <input onchange="changing(this)" type="text" class="form-control input" name="name" id="name"  value="{{ $citie->name }}">
                            </div>
                        </div>
  

                     </div>
                    <input type="hidden" id="id" class="form-control"   value={{ $citie->id }}>
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
        var citie = $('#id').val();
         //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('cities.updating') }}",
            method: "POST",
            data: {citie: citie , champ:champ ,val:val, _token: _token},
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
            url: "{{ route('cities.updating') }}",
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
