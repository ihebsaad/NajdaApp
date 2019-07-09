@extends('layouts.fulllayout')

@section('content')
    <div class="portlet box grey">
        <div class="modal-header">Document à signer</div>
    </div><div class="form-group">
     {{ csrf_field() }}

<div class="modal-body">
    <form id="updateform">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Nom *</label>
                                <input onchange="changing(this)" type="text" class="form-control input" name="nom" id="nom"  value="{{ $doc->nom }}">
                            </div>
                        </div>

 


                        <input type="hidden" id="idtp" class="form-control"   value="{{ $doc->id }}">
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
        var doc = $('#idtp').val();

        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('docs.updating') }}",
            method: "POST",
            data: {doc: doc , champ:champ ,val:val, _token: _token},
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
        var doc = $('#idtp').val();

        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('docs.updating') }}",
            method: "POST",
            data: {doc: doc , champ:champ ,val:val, _token: _token},
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
