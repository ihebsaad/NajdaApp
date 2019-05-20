@extends('layouts.mainlayout')

@section('content')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

    <!--  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/3.5.1/select2.min.js"></script>-->


    <section class="content form_layouts">

        <div class="container-fluid">
    <div class="row" style="margin-top:10px">
        <div class="col-lg-12">
            <ul id="tabs" class="nav  nav-tabs"  data-tabs="tabs">
                <li class=" nav-item active">
                    <a class="nav-link active   " href="#tab01" data-toggle="tab" onclick="showinfos();" >
                        <i class="fas fa-lg fa-user-md"></i>  Détails du Prestataire
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tab02" data-toggle="tab"  onclick=";showinfos2">
                        <i class="fas fa-lg fa-ambulance"></i>  Prestations
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tab03" data-toggle="tab"  onclick="hideinfos();hideinfos2();">
                        <i class="fas fa-lg fa-sort-amount-down"></i>  Priorité
                    </a>
                </li>

            </ul>

        </div>
    </div>

            <div id="tab01" class="tab-pane fade active in    "  style="padding-top:30px">


     <form id="updateform">
         {{ csrf_field() }}

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Nom *</label>
                                <input onchange="changing(this)" type="text" class="form-control input" name="name" id="name"  value={{ $prestataire->name }}>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="inputError" class="control-label">Spécialité *</label>
                                    <input onchange="changing(this)" type="text" class="form-control input" name="specialite" id="specialite"  value={{ $prestataire->specialite }}>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <label for="form_control_1">Observation prestataire<span class="required"> * </span></label>
                                <textarea onchange="changing(this)" rows="2" class="form-control" name="observation_prestataire" id="observation_prestataire"> {{$prestataire->observation_prestataire}} </textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Pririoté</label>
                                <select onchange="changing(this)" id="ordre" name="ordre" class="form-control"   value={{ $prestataire->ordre }}>
                                    <option <?php if ($prestataire->ordre ==''){echo 'selected="selected"';} ?> value="0"></option>
                                    <option  <?php if ($prestataire->ordre =='1'){echo 'selected="selected"';} ?>value="1">1</option>
                                    <option  <?php if ($prestataire->ordre =='2'){echo 'selected="selected"';} ?>value="2">2</option>
                                    <option  <?php if ($prestataire->ordre =='3'){echo 'selected="selected"';} ?> value="3">3</option>

                                </select>
                            </div>
                        </div>
                        <!--
                        <style>.tags{font-size: 13px;}</style>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Type prestation *</label>
                           <div class="row form-group">
                           {{--    @foreach($relations as $prest  )
                                   @foreach($typesprestations as $aKey  )
                                         @if($prest->type_prestation_id==$aKey->id)
                                           <?php echo '<span class="tags" id="type'.$aKey->id.'" >'.$aKey->name.' <a onclick="removeprest(this)" id="prest'.$aKey->id.'" href="javascript:"> <i class="fas fa-times-circle "></i> </a></span><br>' ; ?>
                                       @endif
                                   @endforeach
                               @endforeach --}}
                           </div>

                                <select   id="typepres" name="typepres[]" multiple="multiple" class="form-control select2-offscreen" tabindex="-1" value={{ $prestataire->typepres }}>

                                  {{--  @foreach($relations as $prest  )
                                        @foreach($typesprestations as $aKey  )
                                             <option   onclick="createtypeprest('tpr<?php echo $aKey->id; ?>')"  value="{{$aKey->id}}" @if($prest->type_prestation_id==$aKey->id)selected="selected"@endif     >{{$aKey->name}}</option>
                                         @endforeach
                                    @endforeach  --}}

                                </select>
                            </div>
                        </div>
                        -->
                        <div class="form-group  ">
                            <label>Type de prestations</label>
                                 <div class="col-md-6">
                                    <select class="itemName form-control col-lg-6" style="" name="itemName"  multiple  id="typeprest">
                                        <option></option>
                                        <?php if ( count($relations) > 0 ) {?>

                                    @foreach($relations as $prest  )
                                            @foreach($typesprestations as $aKey)
                                                <option  @if($prest->type_prestation_id==$aKey->id)selected="selected"@endif    onclick="createtypeprest('tpr<?php echo $aKey->id; ?>')"  value="<?php echo $aKey->id;?>"> <?php echo $aKey->name;?></option>
                                            @endforeach
                                        @endforeach

                                        <?php
                                        } else { ?>
                                        @foreach($typesprestations as $aKey)
                                            <option    onclick="createtypeprest('tpr<?php echo $aKey->id; ?>')"  value="<?php echo $aKey->id;?>"> <?php echo $aKey->name;?></option>
                                        @endforeach

                                          <?php }  ?>

                                    </select>

                                </div>
                        </div>
                    </div>


                    <div class="row">
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label>Gouvernorat de couverture</label>
                                    <select class="form-control col-lg-6" style="" name="gouv"  multiple  id="gouvcouv">
                                        <option></option>

                                        <?php if ( count($relationsgv) > 0 ) {?>

                                    @foreach($relationsgv as $Rgv  )
                                            @foreach($gouvernorats as $aKeyG)
                                                <option  @if($Rgv->citie_id==$aKeyG->id)selected="selected"@endif    value="<?php echo $aKeyG->id;?>"> <?php echo $aKeyG->name;?></option>
                                            @endforeach
                                        @endforeach

                                        <?php
                                        } else { ?>

                                        @foreach($gouvernorats as $aKeyG)
                                            <option     value="<?php echo $aKeyG->id;?>"> <?php echo $aKeyG->name;?></option>
                                        @endforeach

                                       <?php }  ?>
                                    </select>
                                </div>
                             </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Ville du siège social</label><br>

                                <?php

                                if ($prestataire->ville_id >0)
                                    {

                                        $villeid=intval($prestataire['ville_id']);
                                        if (isset($villes[$villeid]['name']) ){$nomv=$villes[$villeid]['name'];}
                                        else{$nomv=$prestataire['ville'];}

                                        echo '<label style="font-weight:bold">'. $nomv .'</label>';

                                        ?>

                                 <?php    } else {?>


                                <input onchange="changing(this)" type="text" class="form-control input" name="ville" id="ville" value="{{ $prestataire->ville }}">

                                <script>
                                    var placesAutocomplete = places({
                                        appId: 'plCFMZRCP0KR',
                                        apiKey: 'aafa6174d8fa956cd4789056c04735e1',
                                        container: document.querySelector('#ville')
                                    });
                                </script>
                                <?php    }?>
                            </div>
                        </div>


                    </div>


                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Adresse </label>
                                <input onchange="changing(this)" type="text" class="form-control input" name="adresse" id="adresse"  value={{ $prestataire->adresse }}>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Fax</label>
                                <input onchange="changing(this)" type="text" id="fax" class="form-control" name="fax"  value={{ $prestataire->fax }}>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Mobile 1</label>
                                <input onchange="changing(this)" type="text" id="phone_cell" class="form-control" name="phone_cell"  value={{ $prestataire->phone_cell }}>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Mobile 2 </label>
                                <input onchange="changing(this)" type="text" id="phone_cell2" class="form-control" name="phone_cell2"  value={{ $prestataire->phone_cell2 }}>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Téléphone 1</label>
                                <input onchange="changing(this)" type="text" id="phone_home" class="form-control" name="phone_home"  value={{ $prestataire->phone_home }}>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Téléphone 2</label>
                                <input onchange="changing(this)" type="text" id="phone_home2" class="form-control" name="phone_home2"  value={{ $prestataire->phone_home2 }}>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Email </label>
                                <?php if($prestataire->mail!=''){ ?><input onchange="changing(this)"  type="text" id="mail" class="form-control" name="mail" placeholder="Email"  value={{ $prestataire->mail }}> <br><?php }?>

                                <?php if($prestataire->mail2!=''){ ?>   <input onchange="changing(this)"  type="text" id="mail2" name="mail2" class="form-control" placeholder="Email2"  value={{ $prestataire->mail2 }}><br> <?php }?>

                                <?php if($prestataire->mail3!=''){ ?>   <input onchange="changing(this)" type="text" id="mail3"  name="mail3" class="form-control" placeholder="Email3"  value={{ $prestataire->mail3 }}><br> <?php }?>

                                <?php if($prestataire->mail4!=''){ ?>   <input onchange="changing(this)" type="text" id="mail4" name="mail4" class="form-control" placeholder="Email4"  value={{ $prestataire->mail4 }}><br> <?php }?>

                                <?php if($prestataire->mail5!=''){ ?>   <input onchange="changing(this)" type="text" id="mail5"  name="email5" class="form-control" placeholder="Email5"  value={{ $prestataire->mail5 }}><br> <?php }?>

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
                                                <input  onclick="changing(this)" type="radio" name="annule" id="annule" value="0"   <?php if ($prestataire->annule ==0){echo 'checked';} ?>></span></div> Oui
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="nonactif" class="">
                                            <div class="radio" id="uniform-nonactif"><span>
                                                <input onclick="disabling('annule')" type="radio" name="annule" id="nonactif" value="1"  <?php if ($prestataire->annule ==1){echo 'checked';} ?>></span></div> Non
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
     </form>

                <div class="row form-group">

                                <div style="">
                                    <button style="float:right;margin-top:10px;margin-bottom: 15px;margin-right: 20px" id="addemail" class="btn btn-md btn-success"   data-toggle="modal" data-target="#createemail"><b><i class="fas fa-plus"></i> Ajouter un email</b></button>


                                </div>
                                <table class="table table-striped" id="mytable2" style="width:100%;margin-top:15px;font-size:16px;">
                                    <thead>
                                    <tr id="headtable">
                                        <th style="">Email</th>
                                        <th style="">Nom</th>
                                        <th style="">qualité</th>
                                        <th style="">Tel</th>
                                    </tr>

                                    </thead>
                                    <tbody>
                                    @foreach($emails as $email)
                                        <tr>
                                            <td style=";"><?php echo $email->champ; ?></td>
                                            <td style=";"><?php echo $email->nom; ?></td>
                                            <td style=";"><?php echo $email->qualite; ?></td>
                                            <td style=";"><?php echo $email->tel; ?></td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>

                            </div>



                    <input type="hidden" id="idpres" class="form-control"   value={{ $prestataire->id }}>

        </div>

    <div id="tab02" class="tab-pane fade   " style="padding-top:30px">


        <table class="table table-striped" id="mytable" style="width:100%">
        <thead>
        <tr id="headtable">
            <th style="width:35%">Dossier</th>
            <th style="width:25%">Prestataire</th>
            <th style="width:10%">Type</th>
            <th style="width:20%">Prix</th>
        </tr>
       <!-- <tr>
            <th style="width:35%">Dossier</th>
            <th style="width:25%">Prestataire</th>
            <th   style="width:10%">Type</th>
            <th style="width:20%">Prix</th>
        </tr>-->
        </thead>
        <tbody>
        <?php use \App\Http\Controllers\PrestationsController;     ?>

        @foreach($prestations as $prestation)
            <?php $dossid= $prestation['dossier_id'];?>

            <tr>
                <td style="width:35%"><a href="{{action('PrestationsController@view', $prestation['id'])}}" >
                        <?php  echo PrestationsController::DossierById($dossid);  ?>
                    </a></td>
                <td style="width:25%">
                    <?php $prest= $prestation['prestataire_id'];
                    echo PrestationsController::PrestataireById($prest);  ?>
                </td>
                <td style="width:10%;">
                    <?php $typeprest= $prestation['type_prestations_id'];
                    echo PrestationsController::TypePrestationById($typeprest);  ?>
                </td>
                <td style="width:20%">{{$prestation->price}}</td>

            </tr>
        @endforeach
        </tbody>
    </table>

    </div>

            <div id="tab03" class="tab-pane fade   " style="padding-top:30px">

                <button style="float:right;margin-top:10px;margin-bottom: 15px;margin-right: 20px" id="addev" class="btn btn-md btn-success"   data-toggle="modal" data-target="#createeval"><b><i class="fas fa-plus"></i> Ajouter une Priorité</b></button>

                <table class="table table-striped" id="mytable2" style="width:100%">
                    <thead>
                    <tr id="headtable">
                        <th style="text-align: center;width:35%">Gouvernorat</th>
                        <th style="text-align: center;width:25%">Type</th>
                        <th style="text-align: center;width:10%">Priorité</th>
                        <th style="text-align: center;width:10%">Disponibilité</th>
                        <th style="text-align: center;width:20%">Evaluation</th>
                    </tr>

                    </thead>
                    <tbody>
                @foreach($evaluations as $eval)
                    <tr>
                        <td style="text-align: center"> <?php echo PrestationsController::GouvById( $eval['gouv']) ;?> </td>
                        <td style="text-align: center"> <?php echo PrestationsController::TypePrestationById( $eval['type_prest']) ;?></td>
                        <td style="text-align: center"> {{$eval->priorite}} </td>
                        <td style="text-align: center"> {{$eval->disponibilite}} </td>
                        <td style="text-align: center"> {{$eval->evaluation}} </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>

            </div>

    </section>




    <!-- Modal Evaluation -->
    <div class="modal fade" id="createeval" tabindex="-1" role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal2">Ajouter une Evaluation </h5>

                </div>
                <div class="modal-body">
                    <div class="card-body">

                        <div class="form-group">
                            {{ csrf_field() }}

                            <form id="addevalform" novalidate="novalidate">

                                <input id="prestataire_id" name="prestataire" type="hidden" value="{{ $prestataire->id}}">
                                <div class="form-group " >
                                    <label>Type de prestations</label>
                                    <div class=" row  ">
                                        <select class=" form-control col-lg-12  " style="width:400px" name=""    id="typeprestation">
                                            <option></option>
                                            @foreach($typesprestations as $aKey)
                                                <option     value="<?php echo $aKey->id;?>"> <?php echo $aKey->name;?></option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label>Gouvernorat de couverture</label>
                                    <div class="row">
                                        <select class="form-control  col-lg-12 "  style="width:400px" name="gouv"    id="gouvpr">
                                            <option></option>
                                            @foreach($gouvernorats as $aKeyG)
                                                <option      value="<?php echo $aKeyG->id;?>"> <?php echo $aKeyG->name;?></option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label>Priorité</label>
                                    <div class="row">
                                        <input type="number" step="1" id="prior" max="10" min="0" value="1" />
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label>Disponibilité</label>
                                    <div class="row">
                                        <input type="number" step="1" max="10" min="0" id="disp" value="0"  />
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label>Evaluation</label>
                                    <div class="row">
                                        <input type="number" step="1" max="10" min="0" id="note" value="0"  />
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



    <!-- Modal Email-->
    <div class="modal fade" id="createemail" tabindex="-1" role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal2">Ajouter un Email </h5>

                </div>
                <div class="modal-body">
                    <div class="card-body">


                        <div class="form-group">

                            <form id="addemailform" novalidate="novalidate">
                                {{ csrf_field() }}

                                <input id="parent" name="parent" type="hidden" value="{{ $prestataire->id}}">
                                <div class="form-group " >
                                    <label for="emaildoss">Email</label>
                                    <div class=" row  ">
                                        <input class="form-control" type="email" required id="emaildoss"/>

                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="DescrEmail">Nom</label>
                                    <div class="row">
                                        <input type="text" class="form-control"  id="DescrEmail" />

                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="DescrEmail">Qualité</label>
                                    <div class="row">
                                        <input type="text" class="form-control"  id="qualite" />

                                    </div>
                                </div>

                                <div class="form-group ">
                                    <label for="DescrEmail">Tel</label>
                                    <div class="row">
                                        <input type="text" class="form-control"  id="telmail" />

                                    </div>
                                </div>
                            </form>
                        </div>


                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="button" id="emailadd" class="btn btn-primary">Ajouter</button>
                </div>
            </div>
        </div>
    </div>

@endsection

<script src="https://cdn.jsdelivr.net/npm/places.js@1.16.4"></script>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>


<script>


    function hideinfos() {
        $('#tab01').css('display','none');
    }
    function hideinfos2() {
        $('#tab02').css('display','none');
    }
    function showinfos() {
        $('#tab01').css('display','block');
    }

    function showinfos2() {
        $('#tab02').css('display','block');
    }
        function changing(elm) {
        var champ=elm.id;

        var val =document.getElementById(champ).value;
        //  var type = $('#type').val();
        var prestataire = $('#idpres').val();
         //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('prestataires.updating') }}",
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
         var prestataire = $('#idpres').val();
        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('prestataires.updating') }}",
            method: "POST",
            data: {prestataire: prestataire , champ:champ ,val:val, _token: _token},
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



/*

    function removeprest(elm) {

        var id= elm.id;
        var typeprest= id.slice(5);
        var prestataire = $('#idpres').val();

        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{-- route('prestataires.removetypeprest') --}}",
            method: "POST",
            data: {prestataire: prestataire , typeprest:typeprest ,  _token: _token},
            success: function (data) {
                $('#type'+typeprest).hide( "slow", function() {
                    // Animation complete.
                });


            }
        });

    }


    function createtypeprest(id) {

         var typeprest= id.slice(3);


        var prestataire = $('#idpres').val();


        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{-- route('prestataires.createtypeprest') --}}",
            method: "POST",
            data: {prestataire: prestataire , typeprest:typeprest ,  _token: _token},
            success: function (data) {

                location.reload();


            }
        });


    }
    */

    $(function () {
        $('.itemName').select2({
            filter: true,
        language: {
            noResults: function () {
                return 'Pas de résultats';
            }
        }

        });


        var $topo = $('.itemName');

        var valArray = ($topo.val()) ? $topo.val() : [];

        $topo.change(function() {
            var val = $(this).val(),
                numVals = (val) ? val.length : 0,
                changes;
            if (numVals != valArray.length) {
                var longerSet, shortSet;
                (numVals > valArray.length) ? longerSet = val : longerSet = valArray;
                (numVals > valArray.length) ? shortSet = valArray : shortSet = val;
                //create array of values that changed - either added or removed
                changes = $.grep(longerSet, function(n) {
                    return $.inArray(n, shortSet) == -1;
                });

                Updating(changes, (numVals > valArray.length) ? 'selected' : 'removed');

            }else{
                // if change event occurs and previous array length same as new value array : items are removed and added at same time
                Updating( valArray, 'removed');
                Updating( val, 'selected');
            }
            valArray = (val) ? val : [];
        });



        function Updating(array, type) {
            $.each(array, function(i, item) {

                if (type=="selected"){


                    var prestataire = $('#idpres').val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('prestataires.createtypeprest') }}",
                        method: "POST",
                        data: {prestataire: prestataire , typeprest:item ,  _token: _token},
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

                     var prestataire = $('#idpres').val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('prestataires.removetypeprest') }}",
                        method: "POST",
                        data: {prestataire: prestataire , typeprest:item ,  _token: _token},
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


        $('#gouvcouv').select2({
            filter: true,
            language: {
                noResults: function () {
                    return 'Pas de résultats';
                }
            }

        });


        var $gouv = $('#gouvcouv');

        var valArray = ($gouv.val()) ? $gouv.val() : [];

        $gouv.change(function() {
            var val = $(this).val(),
                numVals = (val) ? val.length : 0,
                changes;
            if (numVals != valArray.length) {
                var longerSet, shortSet;
                (numVals > valArray.length) ? longerSet = val : longerSet = valArray;
                (numVals > valArray.length) ? shortSet = valArray : shortSet = val;
                //create array of values that changed - either added or removed
                changes = $.grep(longerSet, function(n) {
                    return $.inArray(n, shortSet) == -1;
                });

                UpdatingG(changes, (numVals > valArray.length) ? 'selected' : 'removed');

            }else{
                // if change event occurs and previous array length same as new value array : items are removed and added at same time
                UpdatingG( valArray, 'removed');
                UpdatingG( val, 'selected');
            }
            valArray = (val) ? val : [];
        });


        function UpdatingG(array, type) {
            $.each(array, function(i, item) {

                if (type=="selected"){


                    var prestataire = $('#idpres').val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('prestataires.createcitieprest') }}",
                        method: "POST",
                        data: {prestataire: prestataire , citie:item ,  _token: _token},
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

                    var prestataire = $('#idpres').val();
                    var _token = $('input[name="_token"]').val();

                    $.ajax({
                        url: "{{ route('prestataires.removecitieprest') }}",
                        method: "POST",
                        data: {prestataire: prestataire , citie:item ,  _token: _token},
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


        $('#evaladd').click(function(){
            var prestataire = $('#prestataire_id').val();
            var type_prest = $('#typeprestation').val();
            var gouvernorat = $('#gouvpr').val();
            var priorite = $('#prior').val();
            var disponibilite = $('#disp').val();
            var evaluation = $('#note').val();
            if ((type_prest != '') &&(gouvernorat != '') &&(priorite != '') )
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('prestataires.addeval') }}",
                    method:"POST",
                    data:{prestataire:prestataire,type_prest:type_prest,gouvernorat:gouvernorat,priorite:priorite,disponibilite:disponibilite,evaluation:evaluation, _token:_token},
                    success:function(data){

                        //   alert('Added successfully');
                        window.location =data;


                    }
                });
            }else{
                // alert('ERROR');
            }
        });



        $('#emailadd').click(function(){
            var parent = $('#parent').val();
            var champ = $('#emaildoss').val();
            var nom = $('#DescrEmail').val();
            var tel = $('#telmail').val();
            var qualite = $('#qualite').val();
            if ((champ != '') )
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('prestataires.addemail') }}",
                    method:"POST",
                    data:{parent:parent,champ:champ,nom:nom,tel:tel,qualite:qualite, _token:_token},
                    success:function(data){

                        //   alert('Added successfully');
                        window.location =data;


                    }
                });
            }else{
                // alert('ERROR');
            }
        });




    });





/*
    $(function () {
        $('.itemName').select2({
            filter: true,

            ajax: {
                url: "{{-- route('emails.fetch') --}}",
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (attachements) {
                            return {
                                text: attachements.nom,
                                id: attachements.id
                            }
                        })
                    };
                },
                cache: true
            }
        });
    });


*/







</script>
