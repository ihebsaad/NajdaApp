@extends('layouts.mainlayout')

@section('content')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


    <div class="form-group">
     {{ csrf_field() }}

             <div id="addpresform" novalidate="novalidate">

                <input id="idprestation" name="idprestation" type="hidden" value="{{$prestation->id}}">
                <input id="idprestataire" name="idprestataire" type="hidden" value="{{$prestation->prestataire_id}}">
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
				   <div class="form-group col-md-6  ">
				   <?php 
				  $ratings = \App\Rating::where('prestation',$prestation->id)->count();
				   if($ratings>0){
					   $rating  = \App\Rating::where('prestation',$prestation->id)->first();
?>
					 Évaluation : <a href="{{action('PrestatairesController@view_rating', $rating->id)}}" ><?php echo sprintf("%05d",$rating->id);?></a>
   
				 <?php  }else{ ?>

					  <button style="float:right;margin-top:10px;margin-bottom: 15px;margin-right: 20px" id="addev" class="btn btn-md btn-success"   data-toggle="modal" data-target="#createeval"><b><i class="fas fa-plus"></i> Ajouter une Évaluation</b></button>
   
				 <?php   }
				   ?>
				   
					</div>
                </div>

                         <div class="row" style="margin-top:10px;margin-bottom: 20px">
                                <div class="col-md-6"><span style="color:#a0d468" class="fa fa-lg fa-folder"></span>  Dossier <a href="<?php echo $urlapp.'/dossiers/view/'.$prestation['dossier_id'].'#tab3' ;?> " >  <?php echo DossiersController::RefDossierById($prestation['dossier_id']) .' - '.DossiersController::FullnameAbnDossierById($prestation['dossier_id']) ;?></a></div>
                            </div>
                            <div class="prestataire form-group">
                                <div class="row" style=";margin-bottom: 5px">
                                    <div class="col-md-8"><span style="color:grey" class="fa  fa-user-md"></span><a href="{{action('PrestatairesController@view', $prestation->prestataire_id)}}" > <b>  <?php echo PrestatairesController::ChampById('civilite',$prestation->prestataire_id).' '. PrestatairesController::ChampById('name',$prestation->prestataire_id).' '.PrestatairesController::ChampById('prenom',$prestation->prestataire_id) ;?></b></a></div>
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
                            <input onchange="changing(this)" value="{{$prestation->date_prestation}}" class="form-control datepicker-default" name="date_prestation" id="date_prestation" data-required="1" required="" aria-required="true">
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
                     <label class="control-label">Document/OM <span class="required" aria-required="true">   </span></label>
                     <input  value="{{$prestation->oms_docs}}" class="form-control" name="ville" id="ville" readonly data-required="1" required="" aria-required="true">
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

                 <div class="form-group">
<div class="row">
<div class="col-md-3">
                  <text id="textregle" style="font-weight: bold;font-size: large; float: left;">Parvenu  : &nbsp;</text>
                 <input onchange="changingParvenu(this);" type="checkbox" name="parvenu" id="parvenu" value="" style="font-weight: bold;font-size: medium; float: left;" <?php if($prestation->parvenu==1) {echo "checked" ; }?> > &nbsp;&nbsp;      
              

                      </div>     
                                <div class="col-md-3">
                                    <label style="padding-top:10px">Effectuée:</label>
                                </div>
                                <div class="radio-list">
                                    <div class="col-md-3">
                                    <div class="radio" id="uniform-actif"> 
									<label for="effectue" class="">
									<input  onclick="changing(this)" type="radio" name="effectue" id="effectue" value="1"   <?php if ($prestation->effectue ==1){echo 'checked';} ?>>Oui   
                                    </label>
                                    </div>
									</div>
									
                                    <div class="col-md-3">
                                    <div class="radio" id="uniform-nonactif"> 
                                      <label for="nonactif" class="">
										<input onclick="disabling('effectue')" type="radio" name="effectue" id="nonactif" value="0"  <?php if ($prestation->effectue ==0){echo 'checked';} ?>>Non  
                                        </label>
									 </div>
                                    </div>
                               
                 </div>
</div>
 </div>
                 <div class="form-group">
                     <B>Facture :  </B>
                        <?php $facture= \App\Facture::where('prestation',$prestation->id )->first();  ?>
                        <?php   if(isset($facture)) { ?>  <a href="{{action('FacturesController@view', $facture->id)}}"    ><?php if(isset($facture) ){echo $facture->reference   ;}   ?> </a> <?php } ?>
                 </div>

                </div>

    </div>
	
	
 <!-- Modal Evaluation-->
    <div class="modal fade" id="createeval"    role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal2">Ajouter une Évaluation </h5>

                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <div class="form-group">
                            {{ csrf_field() }}

                            <form id="addevalform" novalidate="novalidate"   >

           
                 <div class="form-group">
                            <div class="row">
                                     <label style="padding-top:10px">Disponibilité</label>
                                 <div class="radio-list">
                                    <div class="col-md-3">
                                        <label for="disponibilite" class="">
 											<span class="checked">
                                                <input  onclick="hiding()" type="radio" name="disponibilite" id="disponibilite" value="1"  checked >  Oui  </span>  
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="nondisponible" class="">
                                           <span>
                                                <input onclick="showing()" type="radio" name="disponibilite" id="nondisponible" value="0"  >  Non  </span>  
                                        </label>
                                    </div>
                                </div>
                            </div>
                 </div>
				 
                 <div class="form-group"  id="divraison"   style="display:none"  >
                     <label>Raison  </label>
                     <textarea    class="form-control" name="raison" id="raison" ></textarea>
                 </div>
				 
				  <div class="form-group"    >
                     <label>Ponctualité  </label>
                     <select  onchange="changing(this)"   class="form-control" name="ponctualite" id="ponctualite" >
					 <option value=""></option>
					 <option value="avant"      >Avant RDV</option>
					 <option value="heure"    >A l'heure</option>
					 <option value="apres"   >Après RDV</option>
					 </select>
                 </div>
				 
				  <div class="form-group">
                            <div class="row">
                                     <label style="padding-top:10px">Réactivité</label>
                                 <div class="radio-list">
                                    <div class="col-md-3">
                                        <label for="reactivite" class="">
 											<span class="checked">
                                                <input   type="radio" name="reactivite" id="reactivite" value="1"   checked >  Oui  </span>  
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="nonreactivite" class="">
                                           <span>
                                                <input   type="radio" name="reactivite" id="nonreactivite" value="0"     >  Non  </span>  
                                        </label>
                                    </div>
                                </div>
                            </div>
                 </div>

				 <div class="form-group">
                     <label>Commentaire  </label>
                     <textarea     class="form-control" name="commentaire" id="commentaire" ></textarea>
                 </div>

				  <div class="form-group">
                            <div class="row">
                                     <label style="padding-top:10px">Retour d'information</label>
                                 <div class="radio-list">
                                    <div class="col-md-3">
                                        <label for="retour" class="">
 											<span class="checked">
                                                <input    type="radio" name="retour" id="retour" value="1"    checked >  Oui  </span>  
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="nonretour" class="">
                                           <span>
                                                <input   type="radio" name="retour" id="nonretour" value="0"   >  Non  </span>  
                                        </label>
                                    </div>
                                </div>
                            </div>
                 </div>               

                              

 
                            </form>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="button" id="evaladd" class="btn btn-primary">Ajouter</button>
                </div>
            </div>
        </div>
    </div>	
	
	
	<script>
	

  $('#evaladd').click(function(){
            var prestation = $('#idprestation').val();
            var prestataire = $('#idprestataire').val();
            var raison = $('#raison').val();
            var ponctualite = $('#ponctualite').val();
            var commentaire = $('#commentaire').val();
			var disponibilite = reactivite = retour =  0 ;
		  var disp =document.getElementById('disponibilite').checked==1;
		  var react =document.getElementById('reactivite').checked==1;
		  var ret =document.getElementById('retour').checked==1;

        if (disp==true){disponibilite=1;}
        if (disp==false){disponibilite=0;}
		
		 if (react==true){reactivite=1;}
        if (react==false){reactivite=0;}
		
		 if (ret==true){retour=1;}
        if (ret==false){retour=0;}
		
			 
             
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('prestataires.addrating') }}",
                    method:"POST",
                    data:{prestation:prestation,prestataire:prestataire, raison:raison,ponctualite:ponctualite,disponibilite:disponibilite,reactivite:reactivite,retour:retour ,commentaire:commentaire, _token:_token},
                    success:function(data){
                     //   alert('Added successfully');
                     window.location =data;


                    }
                }).fail(function() {
                alert("erreur lors de l'ajout d evaluation ");
            });
                ;
            
        })
	
	
	
	</script>
	
	
	
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

        function changingParvenu(elm) {
        var champ=elm.id;
        var val =null;
        if($('#'+champ).is(":checked"))
        {
          // alert('checked');
          val=1;
        }
        else
        {
           // alert('is not checked');
           val=0;

        }


        //  var type = $('#type').val();
        var prest = $('#idprestation').val();
         //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('Prestation.updatingParvenu') }}",
            method: "POST",
            data: {prest: prest , champ:champ ,val:val, _token: _token},
            success: function (data) {
                $('#textregle').animate({
                    opacity: '0.3',
                });
                $('#textregle').animate({
                    opacity: '1',
                });
              
            }
        });

        
    }



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
function disabling(elm) {
        var champ=elm;

        var val =0;
         var prestation = $('#idprestation').val();
        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('prestations.updating') }}",
            method: "POST",
            data: {prestation: prestation , champ:champ ,val:val, _token: _token},
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
function showing() {
	$('#divraison').fadeIn('slow') ;
}

function hiding() {
	$('#divraison').fadeOut('slow') ;
}



		
</script>
