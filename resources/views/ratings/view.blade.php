@extends('layouts.mainlayout')

@section('content')

    

    <div class="form-group">
     {{ csrf_field() }}

             <div id="addpresform" novalidate="novalidate">

                 <div class="row" >
                 <div class="form-group col-md-6  ">
                     <h3>Évaluation</h3>
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
                   <input style="width:200px" id="idrating" name="idrating" type="hidden" class="form-control" readonly value="{{$rating->id}}">
                 
                <div class="form-group">
                  <label class="control-label">Prestation:  </label>
                  <a href="{{action('PrestationsController@view', $rating->prestation)}}" ><?php echo sprintf("%05d",$rating->prestation);?></a>
                </div>
                 <div class="form-group">
                     <label class="control-label">Prestataire:  </label>
                   <a href="{{action('PrestatairesController@view', $rating->prestataire)}}" ><?php echo PrestationsController::PrestataireById($rating->prestataire); ?></a>
                 </div>
                 <div class="form-group">
                            <div class="row">
                                     <label style="padding-top:10px">Disponibilité</label>
                                 <div class="radio-list">
                                    <div class="col-md-3">
                                        <label for="disponibilite" class="">
 											<span class="checked">
                                                <input  onclick="changing(this);hiding()" type="radio" name="disponibilite" id="disponibilite" value="1"   <?php if ($rating->disponibilite ==1){echo 'checked';} ?>>  Oui  </span>  
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="nondisponible" class="">
                                           <span>
                                                <input onclick="disabling('disponibilite');showing()" type="radio" name="disponibilite" id="nondisponible" value="0"  <?php if ($rating->disponibilite  ==0){echo 'checked';} ?>>  Non  </span>  
                                        </label>
                                    </div>
                                </div>
                            </div>
                 </div>
				 
                 <div class="form-group"  id="divraison"     <?php if ($rating->disponibilite ==1){echo 'style="display:none"';} ?> >
                     <label>Raison  </label>
                     <textarea  onchange="changing(this)"   class="form-control" name="raison" id="raison" >{{$rating->raison}}</textarea>
                 </div>
				 
				  <div class="form-group"    >
                     <label>Ponctualité  </label>
                     <select  onchange="changing(this)"   class="form-control" name="ponctualite" id="ponctualite" >
					 <option value=""></option>
					 <option value="avant"   <?php if ($rating->ponctualite=='avant'){echo 'selected="selected"';} ?>   >Avant RDV</option>
					 <option value="heure"   <?php if ($rating->ponctualite=='heure'){echo 'selected="selected"';} ?> >A l'heure</option>
					 <option value="apres"   <?php if ($rating->ponctualite=='apres'){echo 'selected="selected"';} ?> >Après RDV</option>
					 </select>
                 </div>
				 
				  <div class="form-group">
                            <div class="row">
                                     <label style="padding-top:10px">Réactivité</label>
                                 <div class="radio-list">
                                    <div class="col-md-3">
                                        <label for="reactivite" class="">
 											<span class="checked">
                                                <input  onclick="changing(this);" type="radio" name="reactivite" id="reactivite" value="1"   <?php if ($rating->reactivite ==1){echo 'checked';} ?>>  Oui  </span>  
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="nonreactivite" class="">
                                           <span>
                                                <input onclick="disabling('reactivite');" type="radio" name="reactivite" id="nonreactivite" value="0"  <?php if ($rating->reactivite  ==0){echo 'checked';} ?>>  Non  </span>  
                                        </label>
                                    </div>
                                </div>
                            </div>
                 </div>

				 <div class="form-group">
                     <label>Commentaire  </label>
                     <textarea  onchange="changing(this)"   class="form-control" name="commentaire" id="commentaire" >{{$rating->commentaire}}</textarea>
                 </div>

				  <div class="form-group">
                            <div class="row">
                                     <label style="padding-top:10px">Retour d'information</label>
                                 <div class="radio-list">
                                    <div class="col-md-3">
                                        <label for="retour" class="">
 											<span class="checked">
                                                <input  onclick="changing(this);" type="radio" name="retour" id="retour" value="1"   <?php if ($rating->retour ==1){echo 'checked';} ?>>  Oui  </span>  
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="nonretour" class="">
                                           <span>
                                                <input onclick="disabling('retour');" type="radio" name="retour" id="nonretour" value="0"  <?php if ($rating->retour  ==0){echo 'checked';} ?>>  Non  </span>  
                                        </label>
                                    </div>
                                </div>
                            </div>
                 </div>
				 
                </div>

    </div>
@endsection
 <!--
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

-->
<script>
  

            function changing(elm) {
        var champ=elm.id;

        var val =document.getElementById(champ).value;
        //  var type = $('#type').val();
        var rating = $('#idrating').val();
         //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('prestataires.updaterating') }}",
            method: "POST",
            data: {rating: rating , champ:champ ,val:val, _token: _token},
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

        var val =0;
         var rating = $('#idrating').val();
        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('prestataires.updaterating') }}",
            method: "POST",
            data: {rating: rating , champ:champ ,val:val, _token: _token},
            success: function (data) {
                if (elm=='disponibilite'){
                $('#nondisponible').animate({
                    opacity: '0.3',
                });
                $('#nondisponible').animate({
                    opacity: '1',
                });
                }

				 if (elm=='reactivite'){
                $('#nonreactivite').animate({
                    opacity: '0.3',
                });
                $('#nonreactivite').animate({
                    opacity: '1',
                });
                }
				
				 if (elm=='retour'){
                $('#nonretour').animate({
                    opacity: '0.3',
                });
                $('#nonretour').animate({
                    opacity: '1',
                });
                }
				
            }
        });
        // } else {

        // }
    }

function showing() {
	$('#divraison').fadeIn('slow') ;
}

function hiding() {
	$('#divraison').fadeOut('slow') ;
}
	
 



</script>
