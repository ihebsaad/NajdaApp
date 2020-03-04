@extends('layouts.adminlayout')



<link href="{{ asset('public/js/select2/css/select2.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('public/js/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>

@section('content')
<div class="form-group">
     {{ csrf_field() }}

<div class="modal-body">
    <div class="portlet box grey">
        <div class="modal-header"><b>Facture</b></div>
    </div>
    <?php
	     use \App\Http\Controllers\UsersController;
$createdat=  date('d/m/Y H:i', strtotime($facture->created_at ));
	?>
    <form id="updateform">

                    <div class="row">
					<h4 style="margin-left:30px;margin-bottom:30px"> Créée Par : <B><?php echo UsersController::ChampById('name',$facture->par).' '.UsersController::ChampById('lastname',$facture->par).'</b>     le <i> '. $createdat;  ?></i></h4>
					</div>
                    <div class="row">
					
					    <div class="col-md-3">
                            <div class="form-group">
                                <label for="inputError" class="control-label">N° de Facture</label>
                                <input onchange="changing(this)" class="form-control input" name="reference" id="reference"  value="{{ $facture->reference }}">
                            </div>
                        </div>
						
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Date d'arrivée</label>
                                <input onchange="changing(this)"  class="form-control datepicker-default "class="form-control input" name="date_arrive" id="date_arrive"  value="{{ $facture->date_arrive }}">
                            </div>
                        </div>

			
        
				<div class="col-md-4">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Client</label>
                                                            <select onchange="changing(this);" id="client" name="client" class="form-control js-example-placeholder-single"   value="{{ $facture->client }}" >
                                                                <option value="0">Sélectionner..... </option>

                                                                @foreach($clients as $cl  )
                                                                    <option
                                                                            @if($facture->client==$cl->id)selected="selected"@endif

                                                                    value="{{$cl->id}}">{{$cl->name}}</option>

                                                                @endforeach


                                                            </select>

								</div>
                    </div>
					
					<div class="col-md-2">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Mois</label>
                                <select onchange="changing(this)"    class="form-control input"   name="mois" id="mois"  value="{{ $facture->mois }}">
                                    <option value=""></option>
                                    <option <?php if($facture->mois==1){echo 'selected="selected"';}?> value="1">  1  </option>
                                    <option <?php if($facture->mois==2){echo 'selected="selected"';}?> value="1">  2  </option>
                                    <option <?php if($facture->mois==3){echo 'selected="selected"';}?> value="1">  3  </option>
                                    <option <?php if($facture->mois==4){echo 'selected="selected"';}?> value="1">  4  </option>
                                    <option <?php if($facture->mois==5){echo 'selected="selected"';}?> value="1">  5  </option>
                                    <option <?php if($facture->mois==6){echo 'selected="selected"';}?> value="1">  6  </option>
                                    <option <?php if($facture->mois==7){echo 'selected="selected"';}?> value="1">  7  </option>
                                    <option <?php if($facture->mois==8){echo 'selected="selected"';}?> value="1">  8  </option>
                                    <option <?php if($facture->mois==9){echo 'selected="selected"';}?> value="1">  9  </option>
                                    <option <?php if($facture->mois==10){echo 'selected="selected"';}?> value="1">  10  </option>
                                    <option <?php if($facture->mois==11){echo 'selected="selected"';}?> value="1">  11  </option>
                                    <option <?php if($facture->mois==12){echo 'selected="selected"';}?> value="1">  12  </option>
                                </select>
                            </div>
                    </div>
                     </div>
        <div class="row" style="margin-top:20px">
				<div class="col-md-3">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Date de validation</label>
                                <input onchange="changing(this)"   class="form-control datepicker-default "  name="date_valid" id="date_valid"  value="{{ $facture->date_valid }}">
                            </div>
                </div>

   				<div class="col-md-3">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Date de Facturation</label>
                                <input onchange="changing(this)"   class="form-control datepicker-default " name="date_facture" id="date_facture"  value="{{ $facture->date_facture }}">
                            </div>
                </div>

				 <div class="col-md-3">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Date de Réception/Fact</label>
                                <input onchange="changing(this)"   class="form-control datepicker-default " name="date_reception" id="date_reception"  value="{{ $facture->date_reception }}">
                            </div>
                </div>
				
				
            </div>
 

        <div class="row" style="margin-top:20px">

   				<div class="col-md-3">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Date de Scan</label>
                                <input onchange="changing(this)"  class="form-control datepicker-default " name="date_scan" id="date_scan"  value="{{ $facture->date_scan }}">
                            </div>
                </div>
        
		   		 <div class="col-md-3">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Date d'envoi par Email</label>
                                <input onchange="changing(this)"   class="form-control datepicker-default " name="date_email" id="date_email"  value="{{ $facture->date_email }}">
                            </div>
                </div>
		
			    <div class="col-md-3">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Date Bordereau</label>
                                <input onchange="changing(this)"  class="form-control datepicker-default " name="date_bord" id="date_bord"  value="{{ $facture->date_bord }}">
                            </div>
                </div>
				
        </div>

        <div class="row" style="margin-top:20px">

				<div class="col-md-3">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Date d'envoi par Poste</label>
                                <input onchange="changing(this)"   class="form-control datepicker-default "name="date_poste" id="date_poste"  value="{{ $facture->date_poste }}">
                            </div>
                </div>
				
      
	  			<div class="col-md-3">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Délai Email</label>
                                <input onchange="changing(this)"    type="number" class="form-control" name="delai_email" id="delai_email"  value="{{ $facture->delai_email }}">
                            </div>
                </div>
	  
	  
	  	  		<div class="col-md-3">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Délai de Poste</label>
                                <input onchange="changing(this)"  type="number" class="form-control" name="delai_poste" id="delai_poste"  value="{{ $facture->delai_poste }}">
                            </div>
                </div>
				
        </div>
        <input type="hidden" id="id" class="form-control"   value="{{ $facture->id }}">
    </form>
      </div>

  </div>


  

@endsection



<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<script src="{{ asset('public/js/select2/js/select2.js') }}"></script>
<script>


    function changing(elm) {
        var champ=elm.id;

        var val =document.getElementById(champ).value;
        //  var type = $('#type').val();
        var facture = $('#id').val();
         //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('factures.updating') }}",
            method: "POST",
            data: {facture: facture , champ:champ ,val:val, _token: _token},
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
         var citie = $('#id').val();
        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('factures.updating') }}",
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
<style>

   .stats{float:left; width:100%; margin-top:10px;}
   .stats span{float:left; margin-right:10px; font-size:14px;}
   .stats span i{margin-right:7px; color:#7ecce7;}
    </style>