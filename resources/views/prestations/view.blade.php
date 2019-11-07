@extends('layouts.mainlayout')

@section('content')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>



    <div class="form-group">
     {{ csrf_field() }}

             <div id="addpresform" novalidate="novalidate">

                        <input id="idprestation" name="idprestation" type="hidden" value="{{$prestation->id}}">
                 <div class="row" >
                 <div class="form-group col-md-6  ">
                     <h3>Prestation</h3>
                     <?php use \App\Http\Controllers\PrestationsController;
                      use \App\Http\Controllers\PrestatairesController;     ?>
                     <?php
$urlapp="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";?>
                  </div>
                </div>

                         <div class="row" style="margin-top:10px;margin-bottom: 20px">
                                <div class="col-md-6"><span style="color:#a0d468" class="fa fa-lg fa-folder"></span>  Dossier <a href="<?php echo $urlapp.'/dossiers/view/'.$prestation['dossier_id'].'#tab3' ;?> " >  <?php echo $prestation['dossier_id'] ;?></a></div>
                            </div>
                            <div class="prestataire form-group">
                                <div class="row" style=";margin-bottom: 5px">
                                    <div class="col-md-8"><span style="color:grey" class="fa  fa-user-md"></span> <b>  <?php echo PrestatairesController::ChampById('civilite',$prestation->prestataire_id).' '. PrestatairesController::ChampById('name',$prestation->prestataire_id).' '.PrestatairesController::ChampById('prenom',$prestation->prestataire_id) ;?></b></div>
                                    <div class="col-md-8"><span style="color:grey" class="fa  fa-map-marker"></span>  Adresse : <?php echo  PrestatairesController::ChampById('adresse',$prestation->prestataire_id).' '. PrestatairesController::ChampById('ville',$prestation->prestataire_id);?></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8"><span style="color:grey" class="fas  fa-clipboard"></span>  Observation:  <?php echo PrestatairesController::ChampById('observation_prestataire',$prestation->prestataire_id);?></div>
                                </div>
                            </div>
                            <table style="margin-left:25px;margin-bottom:10px">

                    <?php
                                foreach ($tels as $tel) {?>
                                  <tr>
                                    <td style="padding-right:8px;padding-bottom:5px;"><i class="fa fa-phone"></i> <?php echo $tel->champ ;?></td>
                                    <td style="padding-right:8px;padding-bottom:5px;"><?php echo $tel->remarque;?></td>
                                </tr>
                             <?php       }
                             ?>
                                 </table>



                        <div class="form-group">
                            <label class="control-label">Date de prestation <span class="required" aria-required="true">   </span></label>
                            <input value="{{$prestation->date_prestation}}" class="form-control datepicker-default" name="date_prestation" id="date_prestation" data-required="1" required="" aria-required="true">
                        </div>
                 <div class="form-group">
                     <label class="control-label">Type de Prestation <span class="required" aria-required="true">   </span></label>
                     <input value="<?php  echo PrestationsController::TypePrestationById($prestation->type_prestations_id);?>" class="form-control" name="type_prestations_id" readonly id="date_prestation" data-required="1" required="" aria-required="true">
                 </div>
                 <div class="form-group">
                     <label class="control-label">Spécialité <span class="required" aria-required="true">   </span></label>
                     <input value="<?php  echo PrestationsController::SpecialiteById($prestation->specialite);?>" class="form-control " name="specialite" readonly id="specialite" data-required="1" required="" aria-required="true">
                 </div>
                 <div class="form-group">
                     <label class="control-label">Gouvernorat <span class="required" aria-required="true">   </span></label>
                     <input value="<?php  echo PrestationsController::GouvById($prestation->gouvernorat);?>" class="form-control" name="gouvernorat" id="gouvernorat" readonly data-required="1" required="" aria-required="true">
                 </div>
                 <div class="form-group">
                     <label class="control-label">Ville <span class="required" aria-required="true">   </span></label>
                     <input  value="{{$prestation->ville}}" class="form-control" name="ville" id="ville" readonly data-required="1" required="" aria-required="true">
                 </div>
                 <div class="form-group">
                            <label>Prix / Fourchette</label>
                            <input onchange="changing(this)" value="{{$prestation->price}}" class="form-control" name="price" id="price">
                 </div>
                 <?php if ($prestation->autorise !='') {?>
                 <div class="form-group">
                     <label>Autorisée par</label>
                     <input readonly  value="{{strtoupper($prestation->autorise)}}" class="form-control" name="autorise" >
                 </div>
                 <?php } ?>

                 <div class="form-group">
                     <label>Détails  </label>
                     <textarea  onchange="changing(this)"   class="form-control" name="details" id="details" >{{$prestation->details}}</textarea>
                 </div>

                </div>

    </div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/places.js@1.16.4"></script>


<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>


<script>
    $(function () {

        $('#gouvcouv').select2({
            filter: true,
            language: {
                noResults: function () {
                    return 'Pas de résultats';
                }
            }

        });

        $('#typeprest').select2({
            filter: true,
            language: {
                noResults: function () {
                    return 'Pas de résultats';
                }
            }

        });

    });


    function changing(elm) {
        var champ=elm.id;

        var val =document.getElementById(champ).value;
        //  var type = $('#type').val();
        var prestation = $('#idprestation').val();
         //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('prestations.updating') }}",
            method: "POST",
            data: {prestation: prestation , champ:champ ,val:val, _token: _token},
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

    function changing2(elm) {
        var champ=elm.id;

        var val =document.getElementById(champ).checked==1;

        if (val==true){val=1;}
        if (val==false){val=0;}
         //  var type = $('#type').val();
        var prestation = $('#idprestation').val();
        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('prestations.updating') }}",
            method: "POST",
            data: {prestation: prestation , champ:champ ,val:val, _token: _token},
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


</script>
