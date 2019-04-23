@extends('layouts.mainlayout')

@section('content')

    <div class="portlet box grey">
        <div class="modal-header">Prestations</div>
    </div>
    <div class="form-group">
     {{ csrf_field() }}

<div class="modal-body">
    <form id="updateform">

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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Type prestation *</label>

                                <select onchange="changing(this)" id="typepres" name="typepres[]" multiple="multiple" class="form-control select2-offscreen" tabindex="-1" value={{ $prestataire->typepres }}>
                                    </select>
                            </div>
                        </div>

                    </div>

                    <div class="row">


                        <div class="col-md-10">
                            <div class="form-group">
                                <label>Ville du siège social</label><br>

                                <?php if ($prestataire->ville_id >0)
                                    {
                                        $villeid=$prestataire->ville_id ;

                                        echo '<label style="font-weight:bold">'. $villes[$villeid]->name .'</label>';

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
                                    <label for="actif" class="">
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

  </div>

@endsection

<script src="https://cdn.jsdelivr.net/npm/places.js@1.16.4"></script>


<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>


<script>

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

</script>
