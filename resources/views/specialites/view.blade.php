@extends('layouts.adminlayout')

<!--select css-->
<link href="{{ asset('public/js/select2/css/select2.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('public/js/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>

@section('content')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


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
                        <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Type de Prestation *</label>


                                <select class="  form-control select2 "  style="width:100%" multiple  id="type_prestation">

                                <option></option>
                                <?php if ( count($relations) > 0 ) {

                                foreach($relations as $rel  ){
                                    foreach($typesprestations as $aKey){ ?>
                                        <option  @if($rel->type_prestation==$aKey->id)selected="selected"@endif    onclick="createspec('spec<?php echo $aKey->id; ?>')"  value="<?php echo $aKey->id;?>"> <?php  echo  $aKey->name; //echo NomTPByid($aKey->type_prestation);?></option>
                                   <?php }
                                }

                                } else {
                                foreach($typesprestations as $aKey){ ?>
                                    <option    onclick="createspec('spec<?php echo $aKey->id; ?>')"  value="<?php echo $aKey->id;?>"> <?php echo $aKey->name; // echo  ;?></option>
                               <?php }

                                 }  ?>
                                </select>
                            </div>
                        </div>
                        </div>



                        <input type="hidden" id="idsp" class="form-control"   value="{{ $specialite->id }}">
             </div>

    </form>
                </div>

  </div>


@endsection

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<script src="{{ asset('public/js/select2/js/select2.js') }}"></script>


<script>
    $(document).ready(function() {

    $('#type_prestation').select2({
        filter: true,
        language: {
            noResults: function () {
                return 'Pas de résultats';
            }
        }

       });





        var $topo1 = $('#type_prestation');

        var valArray0 = ($topo1.val()) ? $topo1.val() : [];

        $topo1.change(function() {
            var val0 = $(this).val(),
                numVals = (val0) ? val0.length : 0,
                changes;
            if (numVals != valArray0.length) {
                var longerSet, shortSet;
                (numVals > valArray0.length) ? longerSet = val0 : longerSet = valArray0;
                (numVals > valArray0.length) ? shortSet = valArray0 : shortSet = val0;
                //create array of values that changed - either added or removed
                changes = $.grep(longerSet, function(n) {
                    return $.inArray(n, shortSet) == -1;
                });

                UpdatingS(changes, (numVals > valArray0.length) ? 'selected' : 'removed');

            }else{
                // if change event occurs and previous array length same as new value array : items are removed and added at same time
                UpdatingS( valArray0, 'removed');
                UpdatingS( val0, 'selected');
            }
            valArray0 = (val0) ? val0 : [];
        });



        function UpdatingS(array, type) {
            $.each(array, function(i, item) {

                if (type=="selected"){


                    var specialite = $('#idsp').val();

                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('specialites.createspec') }}",
                        method: "POST",
                        data: {specialite: specialite , typep:item ,  _token: _token},
                        success: function () {
                            $('.select2-selection').animate({
                                opacity: '0.3',
                            });
                            $('.select2-selection').animate({
                                opacity: '1',
                            });

                        }
                    });

                }

                if (type=="removed"){

                    var specialite = $('#idsp').val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('specialites.removespec') }}",
                        method: "POST",
                        data: {specialite: specialite , typep:item ,  _token: _token},
                        success: function () {
                            $( ".select2-selection--multiple" ).hide( "slow", function() {
                                // Animation complete.
                            });
                            $( ".select2-selection--multiple" ).show( "slow", function() {
                                // Animation complete.
                            });
                        }
                    });

                }

            });
        } // updating

       });




    function changing(elm) {
        var champ=elm.id;

        var val =document.getElementById(champ).value;
        //  var type = $('#type').val();
        var specialite = $('#idsp').val();

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

</script>
