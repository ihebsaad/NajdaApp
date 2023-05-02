@extends('layouts.mainlayout')

@section('content')
<div class="form-group">
     {{ csrf_field() }}

<div class="modal-body">
    <div class="portlet box grey">
        <div class="modal-header"><b>Intervenant</b></div>
    </div>
    
    <form id="updateform">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Nom *</label>
                                <input onchange="changing(this)" type="text" class="form-control input" name="name" id="name"  value="{{ $intervenant->name }}">
                            </div>
                        </div>

                        <div class="col-md-6">
          
                         </div>
                     </div>
 

        <div class="row" style="margin-top:20px">

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputError" class="control-label">Type</label>
                <select onchange="changing(this)"  style="" class="form-control " name="type" id="type" type="text"    }} >
                    <option  <?php if ($intervenant->type ==''){echo 'selected="selected"';} ?> ><option>
                    <option  <?php if ($intervenant->type =='chauffeur'){echo 'selected="selected"';} ?> >Chauffeur<option>
                    <option  <?php if ($intervenant->type =='paramedical'){echo 'selected="selected"';} ?> >Paramédical <option>
                    <option  <?php if ($intervenant->type =='autre'){echo 'selected="selected"';} ?> >Autre<option>
                 </select>
             </div>

        </div>

        </div>


        <input type="hidden" id="id" class="form-control"   value={{ $intervenant->id }}>
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
        var intervenant = $('#id').val();
         //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('intervenants.updating') }}",
            method: "POST",
            data: {intervenant: intervenant , champ:champ ,val:val, _token: _token},
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
            url: "{{ route('intervenants.updating') }}",
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
