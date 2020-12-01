@extends('layouts.adminlayout')



<link href="{{ asset('public/js/select2/css/select2.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('public/js/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>

@section('content')
<div class="form-group">
     {{ csrf_field() }}

<div class="modal-body">
    <div class="portlet box grey">
        <div class="modal-header"><b>Personnel</b></div>
    </div>
    
    <form id="updateform">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Nom *</label>
                                <input onchange="changing(this)" type="text" class="form-control input" name="name" id="name"  value="{{ $personne->name }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row" style="padding-top:20px">
                                <div class="col-md-2">
                                    <label style="padding-top:10px">Actif:</label>
                                </div>
                                <div class="radio-list">
                                    <div class="col-md-2">
                                        <label for="annule" class="">
                                            <div class="radio" id="uniform-actif"><span class="checked">
                                                <input  onclick="changing(this)" type="radio" name="annule" id="annule" value="0"   <?php if ($personne->annule ==0){echo 'checked';} ?>></span></div> Oui
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="nonactif" class="">
                                            <div class="radio" id="uniform-nonactif"><span>
                                                <input onclick="disabling('annule')" type="radio" name="annule" id="nonactif" value="1"  <?php if ($personne->annule ==1){echo 'checked';} ?>></span></div> Non
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                     </div>
        <div class="row" style="margin-top:20px">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="inputError" class="control-label">Date de Début indisponibilité * </label>
                    <input onchange="changing(this)"   type="datetime-local" class="  form-control" name="date_deb_indisponibilite" id="date_deb_indisponibilite"     value="<?php $datedeb=date('Y-m-d H:i', strtotime($personne->date_deb_indisponibilite)); $datedeb1=str_replace(' ', 'T', $datedeb); if($personne->date_deb_indisponibilite==null) {$datedeb1="";} echo $datedeb1;?>" >
                </div>

            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="inputError" class="control-label">Date de Fin indisponibilité *</label>
                    <input onchange="changing(this)"  type="datetime-local"  class="form-control    " name="date_fin_indisponibilite" id="date_fin_indisponibilite"   value="<?php  $datefin=date('Y-m-d H:i', strtotime($personne->date_fin_indisponibilite)); $datefin1=str_replace(' ', 'T', $datefin); if($personne->date_fin_indisponibilite==null) {$datefin1="";} echo $datefin1;?>" >
                </div>

            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="inputError" class="control-label">Type</label>
                    <select onchange="changing(this)"  style="" class="form-control " name="type" id="type"   >
                        <option value="" <?php if ($personne->type ==''){echo 'selected="selected"';} ?> ></option>
                        <option value="chauffeur"  <?php if ($personne->type =='chauffeur'){echo 'selected="selected"';} ?> >Chauffeur</option>
                        <option value="paramedical"  <?php if ($personne->type =='paramedical'){echo 'selected="selected"';} ?> >Paramédical </option>
                        <option value="autre"  <?php if ($personne->type =='autre'){echo 'selected="selected"';} ?> >Autre</option>
                    </select>
                </div>

            </div>

        </div>

        <div class="row" style="margin-top:20px">


            <div class="col-md-3">
                <div class="form-group">
                    <label for="inputError" class="control-label">Téléphone</label>
                    <input onchange="changing(this)"  style="" class="form-control " name="tel" id="tel" type="number"   value="{{ $personne->tel }}" >
                </div>

            </div>

            <div class="col-md-2" style="margin-top:20px">
                <a style="margin-left:30px;cursor:pointer" data-toggle="modal"  data-target="#sendsms" ><i class="fas    fa-sms"></i>  Envoyer un SMS </a>
            </div>

            <div class="col-md-2" style="margin-top:20px">
                <a  style="margin-left:30px;cursor:pointer"  data-toggle="modal"  data-target="#listesms" ><i class="fa fa-list"></i>  Liste des SMS </a>
            </div>
        </div>

        <div class="row" style="margin-top:20px">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="inputError" class="control-label">Téléphone 2</label>
                    <input onchange="changing(this)"  style="" class="form-control " name="tel2" id="tel2" type="number"   value="{{ $personne->tel2 }}" >
                </div>

            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="inputError" class="control-label">Email</label>
                    <input onchange="changing(this)"  style="" class="form-control " name="email" id="email" type="email"   value="{{ $personne->email }}" >
                </div>

            </div>
        </div>
        <input type="hidden" id="id" class="form-control"   value="{{ $personne->id }}">
    </form>
      </div>

  </div>




<?php
use App\Dossier ;
use App\Envoye ;
$dossiers = Dossier::get();
if ($personne->tel!=''){
    $envoyes = Envoye::orderBy('id', 'desc')->where('type','sms' )->where('destinataire', $personne->tel)->paginate(5);
}else{

}

?>


<!-- Modal SMS -->
<div class="modal fade" id="sendsms" tabindex="-1" role="dialog" aria-labelledby="sendingsms" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModal7">Envoyer un SMS </h5>

            </div>
            <form method="post" action="{{action('EmailController@sendsmsxml')}}" >
<input type="hidden"  name="smsper" id="smsper" class="form-control"  value="{{ $personne->id }}">
            <div class="modal-body">
                <div class="card-body">


                        <div class="form-group">
                            {{ csrf_field() }}
                            <label for="description">Dossier:</label>

                            <div class="form-group">
                                 <select id ="dossier"  class="form-control " style="width: 120px">
                                    <option></option>
                                    <?php foreach($dossiers as $ds)

                                    {
                                        echo '<option value="'.$ds->reference_medic.'"> '.$ds->reference_medic.' </option>';}     ?>
                                </select>
                             </div>


                        </div>


                        <div class="form-group">
                            {{ csrf_field() }}

                            <label for="description">Description:</label>
                            <input id="description" type="text" class="form-control" name="description"     />
                        </div>

                        <div class="form-group">

                            <label for="destinataire">Destinataire:</label>
                            <input id="destinataire" type="number" class="form-control" name="destinataire"   value="{{ $personne->tel }}"   />
                        </div>

                        <div class="form-group">
                            <label for="contenu">Message:</label>
                            <textarea  type="text" class="form-control" name="message"></textarea>
                        </div>
                        {{--  {!! NoCaptcha::renderJs() !!}     --}}
                      <!--  <script src="https://www.google.com/recaptcha/api.js" async defer></script>-->




                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button  type="submit"  class="btn btn-md  btn-primary btn_margin_top"><i class="fa fa-paper-plane" aria-hidden="true"></i> Envoyer</button>
            </div>
            </form>

        </div>
    </div>
</div>


<!-- Modal Liste sms -->
<div class="modal fade" id="listesms" tabindex="-1" role="dialog" aria-labelledby="liste" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModal7">SMS Envoyés </h5>

            </div>

                <div class="modal-body">
                    <div class="card-body" style="min-height: 100px">


    <div class="uper">
    <?php if (isset($envoyes)) { ?>
        @foreach($envoyes as $envoye)
            <div class="email">
                <div class="fav-box">
                   <!-- <a href="{{action('EnvoyesController@destroy', $envoye['id'])}}" class="btn btn-sm btn-danger btn-responsive" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom"  data-original-title="Supprimer">
                        <i class="fa fa-lg fa-fw fa-trash-alt"></i>

                    </a>-->
                </div>
                <div class="media-body pl-3">
                    <div class="subject">
                         <i class="fa fa-lg fa-sms"></i>
                        <?php echo $envoye->description; ?>
                    </div>

                <div class="stats">
                        <div class="row" style="margin-left:15px;margin-bottom:20px;margin-right:15px ;">
                            <p><?php echo $envoye->contenu; ?></p>
                             </div>

                    <div class="row">
                        <span><i class="fa fa-fw fa-clock-o"></i><?php echo  date('d/m/Y H:i', strtotime($envoye->created_at)) ; ?></span>
                    </div>

                    </div>
                </div>
            </div>
    </div>
    @endforeach


    <?php  $envoyes->links(); } ?>

             </div>

                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
             </div>

        </div>
    </div>
</div>


@endsection



<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<script src="{{ asset('public/js/select2/js/select2.js') }}"></script>
<script>

/*
    $(document).ready(function() {
        $("#dossier").select2();
    });*/


  // $('#dossier').select2( );

    function changing(elm) {
        var champ=elm.id;

        var val =document.getElementById(champ).value;
        //  var type = $('#type').val();
        var personne = $('#id').val();
         //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('personnes.updating') }}",
            method: "POST",
            data: {personne: personne , champ:champ ,val:val, _token: _token},
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
         var personne = $('#id').val();
        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('personnes.updating') }}",
            method: "POST",
            data: {personne: personne , champ:champ ,val:val, _token: _token},
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
