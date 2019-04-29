@extends('layouts.mainlayout')

@section('content')

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
                    <a class="nav-link" href="#tab02" data-toggle="tab"  onclick="hideinfos();">
                        <i class="fas fa-lg fa-ambulance"></i>  Prestations
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
                        <style>.tags{font-size: 13px;}</style>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Type prestation *</label>
                           <div class="row form-group">
                               @foreach($relations as $prest  )
                                   @foreach($typesprestations as $aKey  )
                                         @if($prest->type_prestation_id==$aKey->id)
                                           <?php echo '<span class="tags" id="type'.$aKey->id.'" >'.$aKey->name.' <a onclick="removeprest(this)" id="prest'.$aKey->id.'" href="javascript:"> <i class="fas fa-times-circle "></i> </a></span><br>' ; ?>
                                       @endif
                                   @endforeach
                               @endforeach

                           </div>
                                <select   id="typepres" name="typepres[]" multiple="multiple" class="form-control select2-offscreen" tabindex="-1" value={{ $prestataire->typepres }}>

                                    @foreach($relations as $prest  )
                                        @foreach($typesprestations as $aKey  )
                                             <option   onclick="createtypeprest('tpr<?php echo $aKey->id; ?>')"            value="{{$aKey->id}}" @if($prest->type_prestation_id==$aKey->id)selected="selected"@endif     >{{$aKey->name}}</option>
                                         @endforeach
                                    @endforeach

                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row">


                        <div class="col-md-10">
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
                                <input onchange="changing(this)"  type="text" id="mail" class="form-control" name="mail" placeholder="Email"  value={{ $prestataire->mail }}>
                                <br>
                                <span id="email01">
                                    <input onchange="changing(this)"  type="text" id="mail2" name="mail2" class="form-control" placeholder="Email2"  value={{ $prestataire->mail2 }}>
                                    <br>
                                    <span id="email02">
                                        <input onchange="changing(this)" type="text" id="mail3"  name="mail3" class="form-control" placeholder="Email3"  value={{ $prestataire->mail3 }}>
                                        <br>
                                        <span id="email03">
                                            <input onchange="changing(this)" type="text" id="mail4" name="mail4" class="form-control" placeholder="Email4"  value={{ $prestataire->mail4 }}>
                                            <br>
                                            <span id="email04">
                                                <input onchange="changing(this)" type="text" id="mail5"  name="email5" class="form-control" placeholder="Email5"  value={{ $prestataire->mail5 }}>
                                            </span>
                                        </span>
                                    </span>
                                </span>

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
                    <input type="hidden" id="idpres" class="form-control"   value={{ $prestataire->id }}>
    </form>



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
        <tr>
            <th style="width:35%">Dossier</th>
            <th style="width:25%">Prestataire</th>
            <th   style="width:10%">Type</th>
            <th style="width:20%">Prix</th>
        </tr>
        </thead>
        <tbody>
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

        </div>
 @endsection

<script src="https://cdn.jsdelivr.net/npm/places.js@1.16.4"></script>


<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>


<script>


    function hideinfos() {
        $('#tab01').css('display','none');
    }

    function showinfos() {
        $('#tab01').css('display','block');
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





    function removeprest(elm) {

        var id= elm.id;
        var typeprest= id.slice(5);
        var prestataire = $('#idpres').val();

        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('prestataires.removetypeprest') }}",
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
            url: "{{ route('prestataires.createtypeprest') }}",
            method: "POST",
            data: {prestataire: prestataire , typeprest:typeprest ,  _token: _token},
            success: function (data) {

                location.reload();


            }
        });


    }





</script>
