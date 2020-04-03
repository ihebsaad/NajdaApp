@extends('layouts.adminlayout')

@section('content')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


    <div class="form-group">
     {{ csrf_field() }}

<div class="modal-body">
    <h2>Groupe du client </h2><br>
    <form id="updateform">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Nom *</label>
                                <input onchange="changing(this)" type="text" class="form-control input" name="label" id="label"  value={{ $clientgroupe->label }}>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                            <div class="col-md-4">
                                <label style="padding-top:10px">Actif</label>
                            </div>
                                <div class="radio-list">
                                    <div class="col-md-3">
                                    <label for="annule" class="">
                                        <div class="radio" id="uniform-actif"><span class="checked">
                                                <input  onclick="changing(this)" type="radio" name="annule" id="annule" value="0"   <?php if ($clientgroupe->annule ==0){echo 'checked';} ?>></span></div> Oui
                                    </label>
                                    </div>
                                    <div class="col-md-3">
                                    <label for="nonactif" class="">
                                        <div class="radio" id="uniform-nonactif"><span>
                                                <input onclick="disabling('annule')" type="radio" name="annule" id="nonactif" value="1"  <?php if ($clientgroupe->annule ==1){echo 'checked';} ?>></span></div> Non
                                    </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                     </div>


        <div class="row ">

        <div class="form-group ">
            <h3>Contrats</h3>

            <div class="row">
                <select class="form-control  col-lg-12 itemName " style="width:400px" name="contrats"  multiple  id="contrats">


                    <option></option>
                    <?php if ( count($relaContr) > 0 ) {?>

                    @foreach($relaContr as $relc  )
                        @foreach($contrats as $contrat)
                            <option  @if($relc->contrat==$contrat->id)selected="selected"@endif    onclick="createspec('spec<?php echo $contrat->id; ?>')"  value="<?php echo $contrat->id;?>"> <?php echo $contrat->nom;?></option>
                        @endforeach
                    @endforeach

                    <?php
                    } else { ?>
                    @foreach($contrats as $contrat)
                        <option    onclick="createspec('spec<?php echo $contrat->id; ?>')"  value="<?php echo $contrat->id;?>"> <?php echo $contrat->nom;?></option>
                    @endforeach

                    <?php }  ?>

                </select>

            </div>
        </div>
    </div>


    <input type="hidden" id="id" class="form-control"   value={{ $clientgroupe->id }}>
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
        var clientgroupe = $('#id').val();
         //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('clientgroupes.updating') }}",
            method: "POST",
            data: {clientgroupe: clientgroupe , champ:champ ,val:val, _token: _token},
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
         var clientgroupe = $('#id').val();
        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('clientgroupes.updating') }}",
            method: "POST",
            data: {clientgroupe: clientgroupe , champ:champ ,val:val, _token: _token},
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


    $(function () {

        $('#contrats').select2({
            filter: true,
            language: {
                noResults: function () {
                    return 'Pas de résultats';
                }
            }

        });






        var $topo2 = $('#contrats');

        var valArray0 = ($topo2.val()) ? $topo2.val() : [];

        $topo2.change(function() {
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

                    var parent = $('#id').val();
                     var typec = 'commun';
                    var _token = $('input[name="_token"]').val();
                   // alert(parent+''+typec);
                    $.ajax({
                        url: "{{ route('contrats.createspec') }}",
                        method: "POST",
                        data: {parent: parent , contrat:item , type:typec, _token: _token},
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

                    var parent = $('#idcl').val();
                    var typec = 'commun';
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('contrats.removespec') }}",
                        method: "POST",
                        data: {parent: parent , contrat:item , type:typec, _token: _token},
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
</script>
