@extends('layouts.adminlayout')

@section('content')
    <div class="portlet box grey">
        <div class="modal-header">Garantie</div>
    </div><div class="form-group">
     {{ csrf_field() }}

<div class="modal-body">
    <form id="updateform">
<?php
 $dossier=\App\Dossier::where('ID_assure',trim($garantie->id_assure))->first();

?>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="inputError" class="control-label">ID Assuré</label>
                                <input readonly onchange="changing(this)" type="text" class="form-control input" name="id_assure" id="id_assure"  value="{{ $garantie->id_assure }}">
                            </div>
                        </div>
						     <div class="col-md-3">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Assuré</label>
                                <input readonly onchange="changing(this)" type="text" class="form-control input"   value="<?php echo   $dossier->subscriber_name.' '.$dossier->subscriber_lastname ;?>">
                            </div>
                        </div>
						
                    </div>
                    <div class="row">					
                        <div class="col-md-3">
                         <label for="inputError" class="control-label">Val 1</label>
 		               <input onchange="changing(this)" type="number" class="form-control input" name="val1" id="val1"  value="{{ $garantie->val1 }}">
                       </div>
					 <div class="col-md-3">
                         <label for="inputError" class="control-label">Val 2</label>
 		               <input onchange="changing(this)" type="number" class="form-control input" name="val2" id="val2"  value="{{ $garantie->val2 }}">
                      </div>	
					 <div class="col-md-3">
                         <label for="inputError" class="control-label">Val 3</label>
 		               <input onchange="changing(this)" type="number" class="form-control input" name="val3" id="val3"  value="{{ $garantie->val3 }}">
                      </div>
					  <div class="col-md-3">
                         <label for="inputError" class="control-label">Val 4</label>
 		               <input onchange="changing(this)" type="number" class="form-control input" name="val4" id="val4"  value="{{ $garantie->val4 }}">
                      </div>
                    </div>


                        <input type="hidden" id="idtp" class="form-control"   value="{{ $garantie->id }}">
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
        var garantie = $('#idtp').val();

        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('garanties.updating') }}",
            method: "POST",
            data: {garantie: garantie , champ:champ ,val:val, _token: _token},
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
