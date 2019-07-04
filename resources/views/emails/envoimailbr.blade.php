@extends('layouts.mainlayout')

@section('content')


    <link href="{{ asset('public/css/summernote.css') }}" rel="stylesheet" media="screen" />

    <div class="row">
        <div class="col-sm-3 col-md-3">
            <?php use \App\Http\Controllers\EnvoyesController;     ?>
            <?php use \App\Http\Controllers\EntreesController;     ?>
                <div class="panel">
                <div class="panel-body pan">
                    <ul class="nav nav-pills nav-stacked">
                        <li class="active">
                                 <span class="badge pull-right"></span>
                                <i class="fa fa-inbox fa-fw mrs"></i>
                                Rédiger un email
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

<form method="post" action="{{action('EmailController@send')}}"  enctype="multipart/form-data"   >
    <input id="envoye" type="hidden" class="form-control" name="envoye"  value="{{$envoye['id']}}" />
    <input id="dossier" type="hidden" class="form-control" name="dossier"  value="{{$doss}}" />

    <div class="form-group">
        {{ csrf_field() }}
        <label for="destinataire">destinataire:</label>
        <div class="row">
        <div class="col-md-10">
            <input id="destinataire" type="email" class="form-control" name="destinataire" required  value="{{$envoye['destinataire']}}"/>
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
                <input id="cc" type="text" class="form-control" name="cc"  value="{{$envoye['cc']}}" />
            </div>
         <div class="col-md-1">
            <label for="cci">CCI:</label>
        </div>
        <div class="col-md-4">
            <input id="cci" type="text" class="form-control" name="cci"  value="{{$envoye['cci']}}"  />
        </div>
    </div>
    </div>

    <div class="form-group">
        <label for="sujet">sujet :</label>
        <input id="sujet" type="text" class="form-control"   name="sujet" required   value="{{$envoye['sujet']}}"/>
    </div>
    <div class="form-group">
        <label for="description">Description :</label>
        <input id="description" type="text" class="form-control" name="description" required   value="{{$envoye['description']}}"/>
    </div>
    <div class="form-group ">
        <label for="contenu">contenu:</label>
       <div class="editor" >
        <textarea style="min-height: 280px;" id="contenu" type="text"  class="textarea tex-com" placeholder="Contenu de l'email ici" name="contenu" required onchange=""><?php $contenu=$envoye['contenu']; echo $contenu; ?></textarea>
       </div>
    </div>

    <div class="form-group form-group-default">
        <label>Attachements de dossier</label>
        <div class="row">
            <div class="col-md-10">
        <select id="attachs"  class="itemName form-control col-lg-12" style="" name="attachs[]"  multiple  value="$('#attachs').val()">
            <option></option>
            @foreach($attachements as $attach)
                <option value="<?php echo $attach->id;?>"> <?php echo $attach->nom;?></option>
            @endforeach
        </select>
            </div>
        </div>
     </div>

    {!! NoCaptcha::display() !!}

     <script src="https://www.google.com/recaptcha/api.js" async defer></script>

     <div class="form-group form-group-default">
        <label>Attachements Externes</label>
        <input class="btn btn-danger fileinput-button" id="file" type="file" name="files[]"   multiple>
    </div>

    <button  type="submit"  class="btn btn-md  btn-primary btn_margin_top"><i class="fa fa-paper-plane" aria-hidden="true"></i> Envoyer</button>

 </form>

        </div>
    </div>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

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






        // ajax save as draft
        $('#description').change(function(){
            var destinataire = $('#destinataire').val();
            var cc = $('#cc').val();
            var cci = $('#cci').val();
            var sujet = $('#sujet').val();
            var contenu = $('#contenu').val();
            var description = $('#description').val();
            var brsaved = $('#brsaved').val();

            if ( (brsaved==0) )
            {// alert('create br');
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('envoyes.savingbr') }}",
                    method:"POST",
                    data:{description:description,destinataire:destinataire,sujet:sujet,contenu:contenu,cc:cc,cci:cci, _token:_token},
                    success:function(data){
                   //     alert('Brouillon enregistré ');

                        document.getElementById('envoye').value=data;
                        document.getElementById('brsaved').value=1;

                    }
                });
            }else{

                if ( description!='' )
                {             var envoye = $('#envoye').val();

                //    alert('update br');
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('envoyes.updatingbr') }}",
                        method:"POST",
                        data:{envoye:envoye,description:description,destinataire:destinataire,contenu:contenu,cc:cc,cci:cci, _token:_token},
                        success:function(data){
                        //    alert('Brouillon enregistré ');

                            document.getElementById('envoye').value=data;
                            document.getElementById('brsaved').value=1;

                        }
                    });

                }

            }
        });

        objTextBox = document.getElementById("contenu");
        oldValue = objTextBox.value;
        var somethingChanged = false;

        function track_change()
        {
            if(objTextBox.value != oldValue)
            {
                oldValue = objTextBox.value;
                somethingChanged = true;
                changeeditor();
            };

            setTimeout(function() { track_change()}, 15000);

        }


        // setTimeout(function() { track_change()}, 15000);
        track_change();
        function setcontenu(myValue) {
            $('#contenu').val(myValue)
                .trigger('change');
        }

        function changeeditor()
        {    var destinataire = $('#destinataire').val();
            var cc = $('#cc').val();
            var cci = $('#cci').val();
            var sujet = $('#sujet').val();
            var contenu = $('#contenu').val();
            var description = $('#description').val();
            var brsaved = $('#brsaved').val();

            if ((brsaved == 0)) {
              //  alert('content changed');
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('envoyes.savingbr') }}",
                    method: "POST",
                    data: {
                        description: description,
                        destinataire: destinataire,
                        sujet: sujet,
                        contenu: contenu,
                        cc: cc,
                        cci: cci,
                        _token: _token
                    },
                    success: function (data) {
                    //    alert('Brouillon enregistré ');

                        document.getElementById('envoye').value = data;
                        document.getElementById('brsaved').value = 1;

                    }
                });
            } else {

                //if ( description!='' )
                //{
                var envoye = $('#envoye').val();

                alert('updating br');
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('envoyes.updatingbr') }}",
                    method: "POST",
                    data: {
                        description: description,
                        envoye: envoye,
                        destinataire: destinataire,
                        contenu: contenu,
                        cc: cc,
                        cci: cci,
                        _token: _token
                    },
                    success: function (data) {
                   //     alert('Brouillon enregistré ');
                        document.getElementById('envoye').value = data;
                        document.getElementById('brsaved').value = 1;
                    }
                });

                //  }

            }

        }


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

