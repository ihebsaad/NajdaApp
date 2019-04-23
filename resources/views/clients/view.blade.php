@extends('layouts.mainlayout')

@section('content')
<div class="form-group">
     {{ csrf_field() }}

<div class="modal-body">

    <form id="addclientform">
        <div class="portlet box grey">
            <div class="modal-header">Ajouter Client</div>
        </div>
        <div class="modal-body">

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Nom *</label>
                        <input onchange="changing(this)"  type="text" class="form-control input" name="name" id="name"   value="{{ $client->name }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Actif</label>
                        <div class="radio-list">
                            <div class="col-md-3">
                                <label for="annule" class="">
                                    <div class="radio" id="uniform-actif"><span  >
                                                <input  onclick="changing(this)" type="radio" name="annule" id="annule" value="0"   <?php if ($client->annule ==0){echo 'checked';} ?>></span></div> Oui
                                </label>
                            </div>
                            <div class="col-md-3">
                                <label for="nonactif" class="">
                                    <div class="radio" id="uniform-nonactif"><span>
                                                <input onclick="disabling('annule')" type="radio" name="annule" id="nonactif" value="1"  <?php if ($client->annule ==1){echo 'checked';} ?>></span></div> Non
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Groupe</label>
                        <select class="form-control" name="groupe" id="groupe" onchange="changing(this)"   value="{{ $client->groupe }}">
                            <option value="0"></option>
                        @foreach($groupes as $gr  )
                                <option
                                        @if($client->groupe==$gr->id)selected="selected"@endif

                                        value="{{$gr->id}}">{{$gr->label}}</option>

                            @endforeach

                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Pays</label>
                        <input class="form-control" type="text" name="pays" onchange="changing(this)" id="pays"  value="{{ $client->pays }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Langue 1</label>
                        <select onchange="changing(this)"  class="form-control" name="langue1" id="langue1"  value="{{ $client->langue1 }}">
                            <option <?php if ($client->langue1 =='0'){echo 'selected="selected"';} ?> value="0"></option>
                            <option <?php if ($client->langue1 =='francais'){echo 'selected="selected"';} ?>value="francais">Français</option>
                            <option <?php if ($client->langue1 =='anglais'){echo 'selected="selected"';} ?>  value="anglais">Anglais</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Langue 2</label>
                        <select onchange="changing(this)"  class="form-control" name="langue2" id="langue2"  value="{{ $client->langue2 }}">
                            <option  <?php if ($client->langue2 ==''){echo 'selected="selected"';} ?> value="0"></option>
                            <option  <?php if ($client->langue2 =='francais'){echo 'selected="selected"';} ?> value="francais">Français</option>
                            <option <?php if ($client->langue2 =='anglais'){echo 'selected="selected"';} ?> value="anglais">Anglais</option>
                            <option <?php if ($client->langue2 =='allemand'){echo 'selected="selected"';} ?> value="allemand">Allemand</option>
                            <option <?php if ($client->langue2 =='portugais'){echo 'selected="selected"';} ?> value="portugais">Portugais</option>
                            <option <?php if ($client->langue2 =='espagnole'){echo 'selected="selected"';} ?> value="espagnole">Espagnole</option>
                            <option <?php if ($client->langue2 =='italien'){echo 'selected="selected"';} ?> value="italien">Italien</option>
                            <option <?php if ($client->langue2 =='arabe'){echo 'selected="selected"';} ?> value="arabe">Arabe</option>
                            <option <?php if ($client->langue2 =='neerlandais'){echo 'selected="selected"';} ?> value="neerlandais">Néerlandais</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Nature</label>
                        <div class="select2-container select2-container-multi form-control" id="s2id_nature"><ul class="select2-choices">  <li class="select2-search-field">    <label for="s2id_autogen1" class="select2-offscreen"></label>    <input type="text" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" class="select2-input" id="s2id_autogen1" style="width: 20px;" placeholder="">  </li></ul><div class="select2-drop select2-drop-multi select2-display-none">   <ul class="select2-results">   <li class="select2-no-results">No matches found</li><li class="select2-no-results">No matches found</li></ul></div></div>
                        <select class="form-control select2-offscreen" name="nature[]" id="nature" multiple="" tabindex="-1">
                            <option value="1">Assistance / Assurance</option>
                            <option value="2">Avionneur</option>
                            <option value="3">Pétrolier / apparenté</option>
                            <option value="4">Clinique</option>
                            <option value="5">Agence de voyage / Hôtel</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Téléphone </label> <input onchange="changing(this)"  type="text" class="form-control input" name="tel" id="tel" value="{{ $client->tel }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Téléphone 2</label> <input onchange="changing(this)" type="text" id="tel2" class="form-control" name="tel2"  value="{{ $client->tel2 }}">
                    </div>
                </div>

            </div>


            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Email 1</label> <input onchange="changing(this)" type="text" id="mail" class="form-control" name="mail"  value="{{ $client->mail }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Email 2</label> <input onchange="changing(this)" type="text" id="mail2" class="form-control" name="email2" value="{{ $client->mail2 }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Email 3</label> <input onchange="changing(this)" type="text" id="mail3" class="form-control" name="email3" value="{{ $client->mail3 }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Email 4</label> <input onchange="changing(this)"  type="text" id="mail4" class="form-control" name="email4" value="{{ $client->mail4 }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Email 5</label> <input onchange="changing(this)" type="text" id="mail5" class="form-control" name="email5" value="{{ $client->mail5 }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Email 6</label> <input onchange="changing(this)" type="text" id="mail6" class="form-control" name="email6" value="{{ $client->mail6 }}">
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Email 7</label> <input onchange="changing(this)" type="text" id="mail7" class="form-control" name="email7" value="{{ $client->mail7 }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Email 8</label> <input onchange="changing(this)"  type="text" id="mail8" class="form-control" name="email8" value="{{ $client->mail8 }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Email 9</label> <input onchange="changing(this)" type="text" id="mail9" class="form-control" name="email9" value="{{ $client->mail9 }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Email 10</label> <input onchange="changing(this)"  type="text" id="email10" class="form-control" name="email10"  value="{{ $client->mail10 }}">

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Fax 1</label> <input onchange="changing(this)" type="text" id="fax" class="form-control" name="fax" value="{{ $client->fax }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Fax 2</label> <input onchange="changing(this)"  type="text" id="fax2" class="form-control" name="fax2" value="{{ $client->fax2 }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Fax 3</label> <input onchange="changing(this)"  type="text" id="fax3" class="form-control" name="fax3" value="{{ $client->fax3 }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Fax 4</label> <input onchange="changing(this)" type="text" id="fax4" class="form-control" name="fax4" value="{{ $client->fax4 }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputError" class="control-label">Fax 5</label> <input onchange="changing(this)" type="text" id="fax5" class="form-control" name="fax5" value="{{ $client->fax5 }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse">
                                    Coordonnées Back Office - Gestion</a>
                            </h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mail 1</label>
                                        <input onchange="changing(this)" type="text" class="form-control" name="gestion_mail1" id="gestion_mail1" value="{{ $client->gestion_mail1 }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mail 2</label>
                                        <input onchange="changing(this)" type="text" class="form-control" name="gestion_mail2" id="gestion_mail2"  value="{{ $client->gestion_mail2 }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tel 1</label>
                                        <input onchange="changing(this)" type="text" class="form-control" name="gestion_tel1" id="gestion_tel1"  value="{{ $client->gestion_tel1 }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tel 2</label>
                                        <input onchange="changing(this)" type="text" class="form-control" name="gestion_tel2" id="gestion_tel2"  value="{{ $client->gestion_tel2 }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Fax </label>
                                        <input onchange="changing(this)" type="text" class="form-control" name="gestion_fax" id="gestion_fax"  value="{{ $client->gestion_fax }}">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse">
                                    Coordonnées Back Office - Réclamation / Qualité</a>
                            </h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mail 1</label>
                                        <input onchange="changing(this)" type="text" class="form-control" name="qualite_mail1" id="qualite_mail1" value="{{ $client->qualite_mail1 }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mail 2</label>
                                        <input onchange="changing(this)"  type="text" class="form-control" name="qualite_mail2" id="qualite_mail2" value="{{ $client->qualite_mail2 }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tel 1</label>
                                        <input onchange="changing(this)"  type="text" class="form-control" name="qualite_tel1" id="qualite_tel1" value="{{ $client->qualite_tel1 }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tel 2</label>
                                        <input onchange="changing(this)"  type="text" class="form-control" name="qualite_tel2" id="qualite_tel2" value="{{ $client->qualite_tel2 }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Fax </label>
                                        <input onchange="changing(this)"  type="text" class="form-control" name="qualite_fax" id="qualite_fax" value="{{ $client->qualite_fax }}">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a class="accordion-toggle" data-toggle="collapse">
                                    Coordonnées Back Office - Réseau</a>
                            </h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mail 1</label>
                                        <input onchange="changing(this)"  type="text" class="form-control" name="reseau_mail1" id="reseau_mail1" value="{{ $client->reseau_mail1 }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mail 2</label>
                                        <input onchange="changing(this)"  type="text" class="form-control" name="reseau_mail2" id="reseau_mail2" value="{{ $client->reseau_mail2 }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tel 1</label>
                                        <input onchange="changing(this)"  type="text" class="form-control" name="reseau_tel1" id="reseau_tel1" value="{{ $client->reseau_tel1 }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Tel 2</label>
                                        <input onchange="changing(this)"  type="text" class="form-control" name="reseau_tel2" id="reseau_tel2" value="{{ $client->reseau_tel2 }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Fax </label>
                                        <input onchange="changing(this)"  type="text" class="form-control" name="reseau_fax" id="reseau_fax" value="{{ $client->reseau_fax }}">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </form>



    <input type="hidden" id="idcl" class="form-control"   value={{ $client->id }}>
    </form>
  </div>

  </div>

@endsection

<script src="https://cdn.jsdelivr.net/npm/places.js@1.16.4"></script>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>


<script>

    function changing(elm) {
        var champ=elm.id;

        var val =document.getElementById(champ).value;
        //  var type = $('#type').val();
        var client = $('#idcl').val();
         //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('clients.updating') }}",
            method: "POST",
            data: {client: client , champ:champ ,val:val, _token: _token},
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
         var client = $('#idcl').val();
        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('clients.updating') }}",
            method: "POST",
            data: {client: client , champ:champ ,val:val, _token: _token},
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
