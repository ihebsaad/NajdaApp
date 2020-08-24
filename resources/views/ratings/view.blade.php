@extends('layouts.mainlayout')

@section('content')

    

    <div class="form-group">
     {{ csrf_field() }}

             <div id="addpresform" novalidate="novalidate">

                 <div class="row" >
                 <div class="form-group col-md-6  ">
                     <h3>Prestation</h3>
                     <?php use \App\Http\Controllers\PrestationsController;
                      use \App\Http\Controllers\PrestatairesController;
                      use \App\Http\Controllers\DossiersController;

                      ?>
                     <?php
  $param= App\Parametre::find(1);$env=$param->env;
$urlapp="http://$_SERVER[HTTP_HOST]/".$env;

?>
                  </div>
                </div>
         <div class="form-group">
                  <label class="control-label">ID  </label>
                 <input id="idrating" name="idrating" type="text" class="form-control" readonly value="{{$rating->id}}">
                </div>

               
                <div class="form-group">
                  <label class="control-label">Prestation  </label>
                  <a href="{{action('PrestationsController@view', $rating->prestation)}}" ><?php echo sprintf("%05d",$rating->prestation);?></a>
                </div>
                 <div class="form-group">
                     <label class="control-label">Prestataire  </label>
                   <a href="{{action('PrestatairesController@view', $rating->prestataire)}}" ><?php echo PrestationsController::PrestataireById($rating->prestataire); ?></a>
                 </div>
                 <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label style="padding-top:10px">Disponibilité</label>
                                </div>
                                <div class="radio-list">
                                    <div class="col-md-3">
                                        <label for="annule" class="">
                                            <div class="radio" id="uniform-actif"><span class="checked">
                                                <input  onclick="changing(this);" type="radio" name="disponible" id="disponible" value="1"   <?php if ($rating->disponibilite ==1){echo 'checked';} ?>></span></div> Oui
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="nonactif" class="">
                                            <div class="radio" id="uniform-nonactif"><span>
                                                <input onclick="disabling('disponible');" type="radio" name="nondisponible" id="nondisponible" value="0"  <?php if ($rating->disponibilite  ==0){echo 'checked';} ?>></span></div> Non
                                        </label>
                                    </div>
                                </div>
                            </div>
                 </div>
				 
                 <div class="form-group">
                     <label class="control-label">Gouvernorat  </label>
                     <input value="<?php  echo PrestationsController::GouvById($rating->gouvernorat);?>" class="form-control" name="gouvernorat" id="gouvernorat" readonly data-required="1" required="" aria-required="true">
                 </div>
                 <div class="form-group">
                     <label class="control-label">Ville  </label>
                     <input  value="{{$rating->ville}}" class="form-control" name="ville" id="ville" readonly data-required="1" required="" aria-required="true">
                 </div>
                <div class="form-group">
                     <label class="control-label">Document/OM  </label>
                     <input  value="{{$rating->oms_docs}}" class="form-control" name="ville" id="ville" readonly data-required="1" required="" aria-required="true">
                 </div>
                 <div class="form-group">
                            <label>Prix / Fourchette</label>
                            <input onchange="changing(this)" value="{{$rating->price}}" class="form-control" name="price" id="price">
                 </div>
                 <?php if ($rating->autorise !='') {?>
                 <div class="form-group">
                     <label>Autorisée par</label>
                     <input readonly  value="{{strtoupper($rating->autorise)}}" class="form-control" name="autorise" >
                 </div>
                 <?php } ?>

                 <div class="form-group">
                     <label>Détails  </label>
                     <textarea  onchange="changing(this)"   class="form-control" name="details" id="details" >{{$rating->details}}</textarea>
                 </div>

                 <div class="form-group">
                  <text id="textregle" style="font-weight: bold;font-size: large; float: left;">Parvenu  : &nbsp;</text>
                 <input onchange="changingParvenu(this);" type="checkbox" name="parvenu" id="parvenu" value="" style="font-weight: bold;font-size: medium; float: left;" <?php if($rating->parvenu==1) {echo "checked" ; }?> > &nbsp;&nbsp;      
                  <br>
                 </div>

                 <div class="form-group">
                     <B>Facture :  </B>
                        <?php $facture= \App\Facture::where('prestation',$rating->id )->first();  ?>
                        <?php   if(isset($facture)) { ?>  <a href="{{action('FacturesController@view', $facture->id)}}"    ><?php if(isset($facture) ){echo $facture->reference   ;}   ?> </a> <?php } ?>
                 </div>

                </div>

    </div>
@endsection
 
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>


<script>
  

            function changing(elm) {
        var champ=elm.id;

        var val =document.getElementById(champ).value;
        //  var type = $('#type').val();
        var prestataire = $('#idrating').val();
         //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('prestataires.updaterating') }}",
            method: "POST",
            data: {prestataire: prestataire , champ:champ ,val:val, _token: _token},
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
         var prestataire = $('#idrating').val();
        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('prestataires.updaterating') }}",
            method: "POST",
            data: {prestataire: prestataire , champ:champ ,val:val, _token: _token},
            success: function (data) {
                if (elm=='disponible'){
                $('#nondisponible').animate({
                    opacity: '0.3',
                });
                $('#nondisponible').animate({
                    opacity: '1',
                });
                }


            }
        });
        // } else {

        // }
    }



</script>
