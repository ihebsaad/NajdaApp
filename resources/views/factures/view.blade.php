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

    $date_valid=$facture['date_valid'];
    $date_arrive=$facture['date_arrive'];
    $dateposte=$facture['date_poste'];

    if($date_valid!=''){$dateemail=$date_valid;}else{$dateemail=$date_arrive;}

    $dateEmail=str_replace('/','-',$dateemail) ;
    $datePoste=str_replace('/','-',$dateposte) ;
    $dateEmail= new DateTime($dateEmail);
    $datePoste= new DateTime($datePoste);
   // $dateEmail=date_create($dateEmail);
   // $datePoste=date_create($dateEmail);

    $today=date('d-m-Y');
    $today=new DateTime($today);;

    $diffEmail=date_diff($dateEmail,$today);
   // $diffEmail->format("%R%a ");
    $diffPoste=date_diff($datePoste,$today);
   // $diffPoste->format("%R%a ");

	?>
    <form id="updateform">

                    <div class="row">
					<h4 style="margin-left:30px;margin-bottom:30px"> Créée Par : <b><?php echo UsersController::ChampById('name',$facture->par).' '.UsersController::ChampById('lastname',$facture->par);?>     le  <?php echo   $createdat   ?> </b></h4>
					</div>
                    <div class="row">
					
					    <div class="col-md-3">
                            <div class="form-group">
                                <label for="inputError" class="control-label">N° de Facture</label>
                                <input autocomplete="off" onchange="changing(this)" class="form-control input" name="reference" id="reference"  value="{{ $facture->reference }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Dossier</label>
                                <?php $iddossier= $facture->iddossier; $dossier= App\Dossier::where('id',$iddossier)->first();$ref=$dossier->reference_medic ; $refC= $dossier->customer_id ;$abn= $dossier->subscriber_name .' '.$dossier->subscriber_lastname ;
                                ?>
                                <h4 style="font-weight:bold;"><a  href="{{action('DossiersController@view',$dossier->id)}}" ><?php echo $ref. ' | '.$abn ; ?></a></h4>

                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Assistance</label>
                                <select   name="client" class="form-control js-example-placeholder-single"     >
                                    <option></option>
                                    @foreach($clients as $cl  )
                                        <option
                                                @if($refC==$cl->id)selected="selected"@endif

                                        value="{{$cl->id}}"    >{{$cl->name}}</option>

                                    @endforeach


                                </select>

                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Adresse de facturation</label>

                                <h4 style="font-weight:bold;"> <?php echo  $dossier->adresse_facturation ?></h4>

                            </div>
                        </div>

                        </div><!------ Row 1 ------>




        <div class="row" style="margin-top:20px">
<?php $prestataires =App\Prestataire::get();?>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="inputError" class="control-label">Intervenant</label>
                    <select onchange="changing(this)" class="form-control input" name="prestataire" id="prestataire"  style="width:100%"  >
                        <option value=""></option>
                            @foreach($prestataires as $p)

                                <option    @if($facture->prestataire==$p->id)selected="selected"@endif
                                        value="{{$p->id}}">{{$p->name}}</option>

                            @endforeach
                     </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="inputError" class="control-label">Référence Intervenant</label>
                    <input onchange="changing(this)" class="form-control input" name="facture_prestataire" id="facture_prestataire"  value="{{ $facture->facture_prestataire }}">
                </div>
            </div>

        </div><!------ Row 2 ------>

        <div class="row" style="margin-top:20px">

            <div class="col-md-3">
                <div class="form-group">
                    <label for="inputError" class="control-label">Mois</label>
                    <select onchange="changing(this)"    class="form-control input"   name="mois" id="mois"  value="{{ $facture->mois }}" style="width:150px">
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


            <div class="col-md-3">
                <div class="form-group">
                    <label for="inputError" class="control-label">Date d'arrivée</label>
                    <input onchange="changing(this);location.reload()"  class="form-control datepicker-default "  name="date_arrive" id="date_arrive"  autocomplete="off" value="{{ $facture->date_arrive }}">
                </div>
            </div>

				<div class="col-md-3">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Date de validation</label>
                                <input onchange="changing(this);location.reload()"   class="form-control datepicker-default "  name="date_valid" id="date_valid"  autocomplete="off" value="{{ $facture->date_valid }}">
                            </div>
                </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="inputError" class="control-label">Délai Email</label>
                     <span class="form-control" style="font-weight:bold;border:none;;width:150px"><?php echo     $diffEmail->format("%R%a "); ?> jours</span>
                </div>
            </div>

        </div> <!-------- Row 3  ------------>

        <div class="row" style="margin-top:20px">

   				<div class="col-md-3">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Date de Facturation</label>
                                <input onchange="changing(this)"   class="form-control datepicker-default " name="date_facture" id="date_facture" autocomplete="off" value="{{ $facture->date_facture }}">
                            </div>
                </div>

				 <div class="col-md-3">

                <div class="form-group">
                                <label for="inputError" class="control-label">Date de Réception/Fact</label>
                                <input onchange="changing(this)"   class="form-control datepicker-default " name="date_reception" id="date_reception" autocomplete="off" value="{{ $facture->date_reception }}">
                 </div>
                </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="inputError" class="control-label">Date de Scan</label>
                    <input onchange="changing(this)"  class="form-control datepicker-default " name="date_scan" id="date_scan" autocomplete="off" value="{{ $facture->date_scan }}">
                </div>
            </div>
        </div>

        <div class="row" style="margin-top:20px">



                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Date d'envoi par Email</label>
                                <input onchange="changing(this)"   class="form-control datepicker-default " name="date_email" id="date_email" autocomplete="off" value="{{ $facture->date_email }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Date Bordereau</label>
                                <input onchange="changing(this)"  class="form-control datepicker-default " name="date_bord" id="date_bord" autocomplete="off" value="{{ $facture->date_bord }}">
                            </div>
                        </div>
				


				<div class="col-md-3">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Date d'envoi par Poste</label>
                                <input onchange="changing(this);location.reload() "   class="form-control datepicker-default "name="date_poste" id="date_poste" autocomplete="off" value="{{ $facture->date_poste }}">
                            </div>
                </div>
				

	  
	  	  		<div class="col-md-3">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Délai de Poste</label>
                                 <span class="form-control" style="font-weight:bold;border:none;width:150px"><?php   echo      $diffPoste->format("%R%a "); ?> jours</span>
                            </div>
                </div>
				
        </div>
        <input type="hidden" id="id" class="form-control"   value="{{ $facture->id }}"  ></input>
    </form>
      </div>

  </div>


  

@endsection

@section('footer_scripts')

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>



<!--select css-->
<link href="{{ asset('public/js/select2/css/select2.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('public/js/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>

<script src="{{ asset('public/js/select2/js/select2.js') }}"></script>

<script>

    $(document).ready(function() {

        $("#prestataire").select2();

    });
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






    function calculduree() {
        var inputdate1 = document.getElementById("date_poste").value;
        var inputdate2 =  '<?php // echo $today;?>' ; //document.getElementById("CL_date_fin_location").value;
        var date1 = inputdate1.substring(0, 10);
        var date2 = inputdate2.substring(0, 10);

alert(date1);
alert(date2);
        var datedeb = new Date(date1);
        var datefin = new Date(date2);
        alert(datedeb);
        alert(datefin);

        diffTime = Math.abs(datefin.getTime() - datedeb.getTime());
        diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
         document.getElementById("delai_poste").value = diffDays;
     }



</script>
<style>

   .stats{float:left; width:100%; margin-top:10px;}
   .stats span{float:left; margin-right:10px; font-size:14px;}
   .stats span i{margin-right:7px; color:#7ecce7;}
    </style>


@endsection
