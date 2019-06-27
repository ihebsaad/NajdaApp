@extends('layouts.mainlayout')

@section('content')


    <link href="{{ asset('public/css/summernote.css') }}" rel="stylesheet" media="screen" />

    <?php use \App\Http\Controllers\EnvoyesController;     ?>
    <?php use \App\Http\Controllers\EntreesController;     ?>
    <h2> Envoyer un Fax </h2>
    <div class="row">

        </div>
        <div class="col-lg-11 ">

<form method="post" action="{{action('EmailController@sendfax')}}"  enctype="multipart/form-data"   >
    <input id="dossier" type="hidden" class="form-control" name="dossier"  value="{{$doss}}" />

    <div class="form-group">
        {{ csrf_field() }}
        <label for="description">Objet:</label>
        <div class="row">
            <div class="col-md-10">
                <input id="description" type="text" class="form-control" name="description" required />
            </div>


        </div>
    </div>
    <div class="form-group">
         <label for="destinataire">Destinataire:</label>
        <div class="row">
            <div class="col-md-10">
                <input id="destinataire" type="text" class="form-control" name="nom" required />
            </div>


        </div>
    </div>

    <div class="form-group">
         <label for="destinataire">Numéro:</label>
        <div class="row">
            <div class="col-md-10">
                <input id="destinataire"  class="form-control" name="numero" required />
            </div>


        </div>
    </div>



    <div class="form-group form-group-default">
        <label>Attachements </label>
        <div class="row">
            <div class="col-md-10">
        <select id="attachs" class="itemName form-control col-lg-6" style="" name="attachs[]"  multiple  value="$('#attachs').val()">
            <option></option>
            @foreach($attachements as $attach)
                  @if($attach->type=='pdf')
                <option value="<?php echo $attach->id;?>"> <?php echo $attach->nom;?></option>
                @endif
            <?php ?>
            @endforeach
        </select>
            </div>
        </div>
     </div>
{{--
    {!! NoCaptcha::display() !!}
    --}}
     <script src="https://www.google.com/recaptcha/api.js" async defer></script>




    <button  type="submit"  class="btn btn-md  btn-primary btn_margin_top"><i class="fa fa-paper-plane" aria-hidden="true"></i> Envoyer</button>

 </form>

        </div>



    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script type="text/javascript">




    $(document).ready(function(){


        $('#file').change(function(){
            var fp = $("#file");
            var lg = fp[0].files.length; // get length
            var items = fp[0].files;
            var fileSize = 0;

            if (lg > 0) {
                for (var i = 0; i < lg; i++) {
                    fileSize = fileSize+items[i].size; // get file size
                }
                if(fileSize > 12000000 ) {
                    alert('La taille des fichiers ne doit pas dépasser 12 MB');
                    $('#file').val('');
                }
            }
        });



        $('.itemName').select2({
            filter: true,
            language: {
                noResults: function () {
                    return 'Pas de résultats';
                }
            }
        });




     });
</script>
    <script type="text/javascript">
        var onloadCallback = function() {
            console.log("grecaptcha is ready!");
        };
    </script>

    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
            async defer>
    </script>

@endsection

