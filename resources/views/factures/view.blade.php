@extends('layouts.adminlayout')


<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>


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
    $dateemail=$facture['date_email'];
 
    $dateEmail=str_replace('/','-',$dateemail) ;
    $datePoste=str_replace('/','-',$dateposte) ;
    $dateValid=str_replace('/','-',$date_valid) ;
    
        if( ( strlen($dateEmail) > 9 ) 
        && (strlen($datePoste) > 9 ) 
    &&  (strlen($dateEmail)  > 9 )  ){
        
    $dateEmail= new DateTime($dateEmail);
    $datePoste= new DateTime($datePoste);
    $dateValid= new DateTime($dateValid);
    

    $today=date('d-m-Y');
    $today=new DateTime($today);;


    $diffEmail=date_diff($dateValid,$dateEmail);
   // $diffEmail->format("%R%a ");
    $diffPoste=date_diff($dateValid,$datePoste);
   
    }else{
        $diffEmail='';
       $diffPoste='';
      
        
    }

if($date_arrive !=''){
$mois=substr ( $date_arrive , 3  ,2 );
}else{$mois='';}
    ?>
    <form id="updateform">

                    <div class="row">
                    <h4 style="margin-left:30px;margin-bottom:30px"> Créée Par : <b><?php echo UsersController::ChampById('name',$facture->par).' '.UsersController::ChampById('lastname',$facture->par);?>     le  <?php echo   $createdat   ?> </b></h4>
                    </div>
                    <div class="row">
                        <input type="hidden"  name="id" id="id"  value="{{ $facture->id }}">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="inputError" class="control-label">N° de Facture</label>
                                <input autocomplete="off" onchange="changing(this)" class="form-control input" name="reference" id="reference"  value="{{ $facture->reference }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Dossier</label>
                                <?php $refC='';
                                $iddossier= $facture->iddossier; if($iddossier >0) {$dossier= App\Dossier::where('id',$iddossier)->first();$ref=$dossier->reference_medic ; $refC= $dossier->customer_id ;$abn= $dossier->subscriber_name .' '.$dossier->subscriber_lastname ;
                                ?>
                                <h4 style="font-weight:bold;"><a  href="{{action('DossiersController@view',$dossier->id)}}" ><?php echo $ref. ' | '.$abn ; ?></a></h4>
                                <?php }  ?>
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

                                <h4 style="font-weight:bold;"> <?php if($iddossier >0) { echo  $dossier->adresse_facturation ;} ?></h4>

                            </div>
                        </div>

                        </div><!------ Row 1 ------>




        <div class="row" style="margin-top:20px">
        
          <div class="col-md-3">
             <div class="form-group">
             <label for="inputError" class="control-label">Montant</label>
               <input autocomplete="off" onchange="changing(this)" class="form-control input" name="montant" id="montant"  value="{{ $facture->montant }}">

             </div>
           </div>
<?php $prestataires =App\Prestataire::get();?>

          <div class="col-md-3">
             <div class="form-group">
                  Type de facture 
                <div style="width:100%;margin-top:8px "  >
                <label for="honoraire" class="">
                <input onclick="changing(this) ;$('#prest').hide('slow')"  type="radio" name="honoraire" id="honoraire"   value="1" <?php if ($facture->honoraire ==1){echo 'checked';} ?> >
                Honoraire de Dossier
                </label>
                 <label for="non_honoraire" style="margin-left:20px"><!--  document.getElementById('prest').style.display = 'block'-->
                 <input onclick="disabling('honoraire');$('#prest').show('slow') ;" type="radio" name="honoraire" id="non_honoraire" value="0"  <?php if ($facture->honoraire ==0){echo 'checked';} ?>  >
                 Prestation 
                 </label> 
                </div>
              
            </div>
           </div>


          <div id="prest"   <?php if ($facture->honoraire ==1){echo 'style="display:none"';} ?>  >
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
          </div>
        </div><!------ Row 2 ------>

        <div class="row" style="margin-top:20px">

            <div class="col-md-3">
                <div class="form-group">
                    <label for="inputError" class="control-label">Mois</label>
                    <select     class="form-control input"   name="mois" id="mois"    style="width:150px">
                        <option value=""></option>
                        <option <?php if($mois=='01'){echo 'selected="selected"';}?>  >  1  </option>
                        <option <?php if($mois=='02'){echo 'selected="selected"';}?> >   2  </option>
                        <option <?php if($mois=='03'){echo 'selected="selected"';}?>  >  3  </option>
                        <option <?php if($mois=='04'){echo 'selected="selected"';}?>  >  4  </option>
                        <option <?php if($mois=='05'){echo 'selected="selected"';}?>  >  5  </option>
                        <option <?php if($mois=='06'){echo 'selected="selected"';}?>  >  6  </option>
                        <option <?php if($mois=='07'){echo 'selected="selected"';}?>  >  7  </option>
                        <option <?php if($mois=='08'){echo 'selected="selected"';}?>  >  8  </option>
                        <option <?php if($mois=='09'){echo 'selected="selected"';}?>  >  9  </option>
                        <option <?php if($mois=='10'){echo 'selected="selected"';}?>  >  10  </option>
                        <option <?php if($mois=='11'){echo 'selected="selected"';}?>  >  11  </option>
                        <option <?php if($mois=='12'){echo 'selected="selected"';}?>  >  12  </option>
                    </select>
                </div>
            </div>


            <div class="col-md-3">
                <div class="form-group">
                    <label for="inputError" class="control-label">Date d'arrivée</label>
                    <input onchange="changing(this); "  class="form-control datepicker-default "  name="date_arrive" id="date_arrive"  autocomplete="off" value="{{ $facture->date_arrive }}"  placeholder="jj/mm/aaaa">
                </div>
            </div>

                <div class="col-md-3">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Date de validation</label>
                                <input onchange="changing(this);"   class="form-control datepicker-default "  name="date_valid" id="date_valid"  autocomplete="off" value="{{ $facture->date_valid }}" placeholder="jj/mm/aaaa">
                            </div>
                </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="inputError" class="control-label">Délai Email</label>
                     <span class="form-control" style="font-weight:bold;border:none;;width:150px"><?php if($date_valid !='' && $dateemail!='' && $diffEmail!=''){ echo     $diffEmail->format("%R%a ").' jours'; } ?> </span>
                </div>
            </div>

        </div> <!-------- Row 3  ------------>

        <div class="row" style="margin-top:20px">

                <div class="col-md-3">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Date de Facturation</label>
                                <input onchange="changing(this)"   class="form-control datepicker-default " name="date_facture" id="date_facture" autocomplete="off" value="{{ $facture->date_facture }}" placeholder="jj/mm/aaaa">
                            </div>
                </div>

                 <div class="col-md-3">

                <div class="form-group">
                                <label for="inputError" class="control-label">Date de Réception/Fact</label>
                                <input onchange="changing(this)"   class="form-control datepicker-default " name="date_reception" id="date_reception" autocomplete="off" value="{{ $facture->date_reception }}" placeholder="jj/mm/aaaa">
                 </div>
                </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="inputError" class="control-label">Date de Scan</label>
                    <input onchange="changing(this)"  class="form-control datepicker-default " name="date_scan" id="date_scan" autocomplete="off" value="{{ $facture->date_scan }}" placeholder="jj/mm/aaaa">
                </div>
            </div>
        </div>

        <div class="row" style="margin-top:20px">



                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Date d'envoi par Email</label>
                                <input onchange="changing(this)"   class="form-control datepicker-default " name="date_email" id="date_email" autocomplete="off" value="{{ $facture->date_email }}"  placeholder="jj/mm/aaaa">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Date Bordereau</label>
                                <input onchange="changing(this)"  class="form-control datepicker-default " name="date_bord" id="date_bord" autocomplete="off" value="{{ $facture->date_bord }}" placeholder="jj/mm/aaaa">
                            </div>
                        </div>
                


                <div class="col-md-3">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Date d'envoi par Poste</label>
                                <input onchange="changing(this); "   class="form-control datepicker-default "name="date_poste" id="date_poste" autocomplete="off" value="{{ $facture->date_poste }}"  placeholder="jj/mm/aaaa">
                            </div>
                </div>
                

      
                <div class="col-md-3">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Délai de Poste</label>
                                 <span class="form-control" style="font-weight:bold;border:none;width:150px">  <?php if($date_valid !='' && $dateposte!='' && $diffPoste!=''){   echo      $diffPoste->format("%R%a ").' jours'; } ?> </span>
                            </div>
                </div>
                
        </div>
        <input type="hidden" id="id" class="form-control"   value="{{ $facture->id }}"  ></input>
    </form>
      </div>

  </div>


  

@endsection

@section('footer_scripts')



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
                if( champ =='date_valid' || champ =='date_arrive' || champ =='date_poste' || champ =='date_email' ){location.reload();}
            }
        });

        
    }

    function disabling(elm) {
        var champ=elm;

        var val =0;
         var facture = $('#id').val();
        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('factures.updating') }}",
            method: "POST",
            data: {facture: facture , champ:champ ,val:val, _token: _token},
            success: function (data) {
                if (elm=='honoraire'){
                $('#non_honoraire').animate({
                    opacity: '0.3',
                });
                $('#non_honoraire').animate({
                    opacity: '1',
                });
                }


            }
        });
        // } else {

        // }
    }



 
 $( "#date_facture" ).datepicker({

            altField: "#datepicker",
            closeText: 'Fermer',
            prevText: 'Précédent',
            nextText: 'Suivant',
            currentText: 'Aujourd\'hui',
            monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
            dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
            dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
            dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
            weekHeader: 'Sem.',
            buttonImage: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABEAAAATCAYAAAB2pebxAAABGUlEQVQ4jc2UP06EQBjFfyCN3ZR2yxHwBGBCYUIhN1hqGrWj03KsiM3Y7p7AI8CeQI/ATbBgiE+gMlvsS8jM+97jy5s/mQCFszFQAQN1c2AJZzMgA3rqpgcYx5FQDAb4Ah6AFmdfNxp0QAp0OJvMUii2BDDUzS3w7s2KOcGd5+UsRDhbAo+AWfyU4GwnPAYG4XucTYOPt1PkG2SsYTbq2iT2X3ZFkVeeTChyA9wDN5uNi/x62TzaMD5t1DTdy7rsbPfnJNan0i24ejOcHUPOgLM0CSTuyY+pzAH2wFG46jugupw9mZczSORl/BZ4Fq56ArTzPYn5vUA6h/XNVX03DZe0J59Maxsk7iCeBPgWrroB4sA/LiX/R/8DOHhi5y8Apx4AAAAASUVORK5CYII=",

            firstDay: 1,
            dateFormat: "dd/mm/yy"

        });
 


</script>
<style>

   .stats{float:left; width:100%; margin-top:10px;}
   .stats span{float:left; margin-right:10px; font-size:14px;}
   .stats span i{margin-right:7px; color:#7ecce7;}
    </style>


@endsection
