@extends('layouts.mainlayout')
<?php
$param= App\Parametre::find(1);$env=$param->env;
$urlapp="http://$_SERVER[HTTP_HOST]/".$env;

?>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

<link href="{{ asset('public/js/select2/css/select2.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('public/js/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@section('content')
 <h2> Créer une nouvel Intervenant </h2>
                <form id="updateform"   method="post"   action="{{route('prestataires.saving')}}" >
                    {{ csrf_field() }}
                    <input type="hidden" id="dossier" name="dossier" value="<?php echo $folder;?>"/>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="inputError" class="control-label">Civilité</label>
                                <select     class="form-control " name="civilite" id="civilite"  >
                                    <option value="" ></option>
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
                                <input required onchange="checkexiste()"  type="text" class="form-control input" name="name" id="name"   >
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
                                <label for="inputError" class="control-label">Adresse </label>
                                <input   type="text" class="form-control input" name="adresse" id="adresse"   >
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Ville du siège social</label><br>

<input list="villes" name="ville" id="ville" style="width:290px">

<datalist id="villes">
     <option value="Select">Selectionner</option>
                                         
                                         @foreach($villes as $pres)

                                             <option   value="<?php echo $pres->ville;?>"> <?php echo $pres->ville;?></option>
                                         @endforeach
</datalist>

                            </div>
                        </div>

                    </div>

                    <div class="row">

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


                    <div class="row" style="margin-bottom:30px;margin-top:50px;">


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
 $(document).ready(function() {
   
 $("#ville").select2();
});
    function checkexiste( ) {

        var val =document.getElementById('name').value;
        //  var type = $('#type').val();

        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('prestataires.checkexisteprname') }}",
            method: "POST",
            data: {   val:val, _token: _token},
            success: function (data) {
                if(data!==""){
 parsed = JSON.parse(data);
                    string='Existe deja ! ';
                    if(parsed['name']!=null){string+='Nom : '+parsed['name']+ ' - '; }
 if(parsed['prenom']!=null){string+='Prénom : '+parsed['prenom']+ ' - '; }
                    string+='<br>   lien : <a href="<?php echo $urlapp.'/prestataires/view/'; ?>'+parsed['id']+'" target="_blank" >Ouvrir Fiche Prestataire</a>';
 

                    Swal.fire({
                        type: 'error',
                        title: 'Existant...',
                        html: string
                    });
                    document.getElementById('name').style.background='#FD9883';
                    document.getElementById('name').style.color='white';
                } else{
                    document.getElementById('name').style.background='white';
                    document.getElementById('name').style.color='black';
                }


            }
        });
        // } else {

        // }
    }


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
