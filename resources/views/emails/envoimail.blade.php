@extends('layouts.mainlayout')

@section('content')
    <link href="{{ asset('public/css/summernote.css') }}" rel="stylesheet" media="screen" />

    <div class="row">
        <div class="col-sm-3 col-md-3">
            <?php use \App\Http\Controllers\EnvoyesController;     ?>
            <?php use \App\Http\Controllers\EntreesController;     ?>            <div class="panel">
                <div class="panel-body pan">
                    <ul class="nav nav-pills nav-stacked">
                        <li class="active">
                            <a  href="{{ route('emails.sending') }}">
                                <span class="badge pull-right"></span>
                                <i class="fa fa-inbox fa-fw mrs"></i>
                                Rédiger un email
                            </a>
                        </li>
                        <li >
                            <a   href="{{ route('boite') }}">
                                <span class="badge pull-right"></span>
                                <i class="fa fa-envelope-square fa-fw mrs"></i>
                                Boîte de réception
                            </a>
                        </li>
                        <li class="">
                            <a   href="{{ route('envoyes') }}">
                                <span class="badge pull-right"><?php  echo EnvoyesController::countenvoyes(); ?></span>
                                <i class="fa fa-paper-plane fa-fw mrs"></i>
                                Envoyées
                            </a>
                        </li>
                        <li class="">
                            <a   href="{{ route('envoyes.brouillons') }}">
                                <span class="badge badge-orange pull-right"><?php echo EnvoyesController::countbrouillons(); ?></span>
                                <i class="fa fa-edit fa-fw mrs"></i>
                                Brouillons
                            </a>
                        </li>
                        <li class="">
                            <a   href="{{ route('entrees.archive') }}">
                                <span class="badge badge-orange pull-right"><?php echo EntreesController::countarchives(); ?></span>
                                <i class="fa fa-archive fa-fw mrs"></i>
                                Archive
                            </a>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
        <div class="col-lg-9 ">

<form method="post" action="{{action('EmailController@send')}}"  enctype="multipart/form-data">
    <div class="form-group">
        {{ csrf_field() }}
        <label for="destinataire">destinataire:</label>
        <div class="row">
        <div class="col-md-10">
            <input id="destinataire" type="email" class="form-control" name="destinataire" required />
        </div>
            <div class="col-md-2">
                <i id="emailso" onclick="visibilite('autres')" class="fa fa-lg fa-arrow-circle-down" style="margin-right:10px"></i>

            </div>
        </div>
    </div>
    <div class="form-group" style="margin-top:10px;">
        <div id="autres" class="row"  style="display:none " >
            <div class="col-md-1">
                <label for="cc">CC:</label>
            </div>
            <div class="col-md-4">
                <input id="cc" type="text" class="form-control" name="cc"  />
            </div>
         <div class="col-md-1">
            <label for="cci">CCI:</label>
        </div>
        <div class="col-md-4">
            <input id="cci" type="text" class="form-control" name="cci"  />
        </div>
    </div>
    </div>

    <div class="form-group">
        <label for="sujet">sujet :</label>
        <input id="sujet" type="text" class="form-control" name="sujet" required/>
    </div>
    <div class="form-group ">
        <label for="contenu">contenu:</label>
       <div class="editor" >
        <textarea id="summernote" style="min-height: 280px;" id="contenu" type="text"  class="textarea tex-com" placeholder="Contenu de l'email ici" name="contenu" required ></textarea>
       </div>
    </div>

    <div class="form-group form-group-default">
        <label>Attachements de dossier</label>
        <form class="search-container">
            <input type="text" id="search-bar" placeholder="Recherche">
            <a href="#"><img class="search-icon" src="{{ URL::asset('public/img/search-icon.png') }}"></a>
            <div id="countryList">
            </div>

            {{ csrf_field() }}
        </form>
     </div>

    <div class="form-group form-group-default">
        <label>Attachements Externes</label>
        <input class="btn btn-danger fileinput-button" id="file" type="file" name="files[]"   multiple>
    </div>
    {!! NoCaptcha::display() !!}

    <button  type="submit"  class="btn btn-md  btn-primary btn_margin_top"><i class="fa fa-paper-plane" aria-hidden="true"></i> Envoyer</button>
    <a id="broullion"   disabled class="btn btn-md btn-success btn_margin_top"><i class="fa fa-archive" aria-hidden="true"></i> Brouillon</a>
 </form>

        </div>
    </div>

<script type="text/javascript">

    function visibilite(divId)
    {
        //divPrecedent.style.display='none';
        divPrecedent=document.getElementById(divId);
        if(divPrecedent.style.display==='none')
        {divPrecedent.style.display='block';	 }
        else
        {divPrecedent.style.display='none';     }
    }

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

        // activation bouton Brouillon
        $('#destinataire').change(function() {
            var destinataire = $('#destinataire').val();

            if ( destinataire != '') {
                 $('#broullion').removeAttr('disabled');
            }
            else {
                 $('#broullion').attr("disabled","disabled");

            }
            });
        $('#sujet').change(function() {
            var sujet = $('#sujet').val();

            if ( sujet != '') {
                $('#broullion').removeAttr('disabled');
            }
            else {
                $('#broullion').attr("disabled","disabled");

            }
        });
            // ajax save as draft
        $('#broullion').click(function(){
            var destinataire = $('#destinataire').val();
            var cc = $('#cc').val();
            var cci = $('#cci').val();
            var sujet = $('#sujet').val();
            var contenu = $('#contenu').val();
             if ( (contenu != ''))
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('envoyes.savingBR') }}",
                    method:"POST",
                    data:{destinataire:destinataire,sujet:sujet,contenu:contenu,cc:cc,cci:cci, _token:_token},
                    success:function(data){
                   ////     alert('Brouillon enregistré ');

                    }
                });
            }else{
                alert('ERROR');
            }
        });


       /****  Recherche des attachements */

        $('#search').keyup(function(){
            var query = $(this).val();
            if(query != '')
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('home.fetch') }}",
                    method:"POST",
                    data:{query:query, _token:_token},
                    success:function(data){
                        $('#List').fadeIn();
                        $('#List').html(data);
                    }
                });
            }else{
                $('#List').fadeOut();
            }
        });

        $('#search').change(function() {
            var query = $(this).val();
            if(query != '')
            {
                $('#List').fadeOut();
            }
        });
        $(document).on('click', 'li .search', function(){
            $('#search').val($(this).text());
            $('#List').fadeOut();
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
