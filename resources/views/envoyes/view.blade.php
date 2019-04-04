@extends('layouts.mainlayout')

@section('content')

     <div class="row">
        <div class="col-sm-3 col-md-3">
            <?php use \App\Http\Controllers\EnvoyesController;     ?>
            <div class="panel">
                <div class="panel-body pan">
                    <ul class="nav nav-pills nav-stacked">
                        <li >
                            <a  href="{{ route('emails.sending') }}">
                                <span class="badge pull-right"></span>
                                <i class="fa fa-inbox fa-fw mrs"></i>
                                Rédiger un email
                            </a>
                        </li>
                        <li class=" ">
                            <a   href="{{ route('boite') }}">
                                <span class="badge pull-right"></span>
                                <i class="fa fa-envelope-square fa-fw mrs"></i>
                                Boîte de réception
                            </a>
                        </li>
                        <li class="active">
                            <a   href="{{ route('envoyes') }}">
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
                            <input id="destinataire" type="email" class="form-control" name="destinataire" required value={{ $envoye->destinataire }} />
                        </div>
                        <div class="col-md-2">
                            <i id="emailso" onclick="visibilite('autres')" class="fa fa-lg fa-arrow-circle-down" style="margin-right:10px"></i>

                        </div>
                    </div>
                </div>
                <div class="form-group" style="margin-top:10px;">
                    <div id="autres" class="row"   >
                        <div class="col-md-1">
                            <label for="cc">CC:</label>
                        </div>
                        <div class="col-md-4">
                            <input id="cc" type="text" class="form-control" name="cc" value={{ $envoye->cc }}  />
                        </div>
                        <div class="col-md-1">
                            <label for="cci">CCI:</label>
                        </div>
                        <div class="col-md-4">
                            <input id="cci" type="text" class="form-control" name="cci" value={{ $envoye->cci }}  />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="sujet">sujet :</label>
                    <input id="sujet" type="text" class="form-control" name="sujet" required value={{ $envoye->sujet }}/>
                </div>
                <div class="form-group ">
                    <label for="contenu">contenu:</label>
                         <textarea   style="min-height: 280px;"   type="text"  class=" form-control" placeholder="Contenu de l'email ici" name="contenu" required >
                            {{ $envoye->contenu }}
                        </textarea>
                 </div>

            <!--    <div class="form-group form-group-default">
                    <label>Attachements</label>
                    <input class="btn btn-danger fileinput-button" id="file" type="file" name="files[]"   multiple>
                </div>
-->
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



        });
    </script>


@endsection