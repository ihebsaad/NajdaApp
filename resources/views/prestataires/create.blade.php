@extends('layouts.mainlayout')

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<link href="{{ asset('public/js/select2/css/select2.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('public/js/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@section('content')
 
                <form id="updateform"      action="{{route('dossiers.save')}}" >
                    {{ csrf_field() }}
                    <input type="hidden" id="dossier" value="<?php echo $folder;?>"/>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Civilité</label>
                                <select     class="form-control input" name="civilite" id="civilite"  >
                                    <option  ></option>
                                    <option value="Mr">Mr</option>
                                    <option  value="Mme">Mme</option>
                                    <option value="Mlle">Mlle</option>
                                    <option  value="Dr">Dr</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Nom *</label>
                                <input   type="text" class="form-control input" name="name" id="name"   >
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Prénom *</label>
                                <input   type="text" class="form-control input" name="prenom" id="prenom"  >
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <label for="form_control_1">Observation  <span class="required"> * </span></label>
                                <textarea   rows="2" class="form-control" name="observation_prestataire" id="observation_prestataire">  </textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Ville du siège social</label><br>


                                <input   type="text" class="form-control input" name="ville" id="ville"  >

                                <script>
                                    var placesAutocomplete = places({
                                        appId: 'plCFMZRCP0KR',
                                        apiKey: 'aafa6174d8fa956cd4789056c04735e1',
                                        container: document.querySelector('#ville')
                                    });
                                </script>

                            </div>
                        </div>


                    </div>


                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Adresse </label>
                                <input   type="text" class="form-control input" name="adresse" id="adresse"   >
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Mobile 1</label>
                                <input   type="text" id="phone_cell" class="form-control" name="phone_cell"   >
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Mobile 2 </label>
                                <input   type="text" id="phone_cell2" class="form-control" name="phone_cell2"   >
                            </div>
                        </div>
                     </div>



                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                   <label for="inputError" class="control-label">Email </label>
                                 <input    type="text" id="mail" class="form-control" name="mail" placeholder="Email"   > <br>

                                 <input    type="text" id="mail2" name="mail2" class="form-control" placeholder="Email2"   ><br>


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
                                                <input  type="radio" name="annule" id="annule" value="0"    ></span></div> Oui
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="nonactif" class="">
                                            <div class="radio" id="uniform-nonactif"><span>
                                                <input  type="radio" name="annule" id="nonactif" value="1"   ></span></div> Non
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="row" style="margin-bottom:30px">


                        <div class="form-actions pull-right  col-md-4">
                            <a href="{{route('prestataires')}}" type="button" id="annuler" class="btn btn-sm btn-danger">Annuler</a>
                        </div>

                        <div class="form-actions pull-right col-md-6">
                            <input type="submit" value="Enregistrer" id="editDos" class="btn btn-sm btn-info"></input>
                        </div>
                    </div>

                </form>


@endsection




<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


<script src="https://cdn.jsdelivr.net/npm/places.js@1.16.4"></script>


<script>


    $(function () {


        var url = document.location.toString();
        if (url.match('#')) {
            $('.nav-item a[href="#' + url.split('#')[1] + '"]').tab('show');
        }

// Change hash for page-reload
        $('.nav-item a').on('shown.bs.tab', function (e) {
            window.location.hash = e.target.hash;
        })




    });






</script>
