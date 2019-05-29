@extends('layouts.mainlayout')

@section('content')

     <div class="row">
        <div class="col-sm-3 col-md-3">
            <?php use \App\Http\Controllers\EnvoyesController;     ?>
            <?php use \App\Http\Controllers\EntreesController;     ?>            <div class="panel">
                <div class="panel-body pan">
                    <ul class="nav nav-pills nav-stacked">

                        <li class="">
                            <a   href="{{ route('boite') }}">
                                <span class="badge pull-right"></span>
                                <i class="fa fa-envelope-square fa-fw mrs"></i>
                                Boîte de réception
                            </a>
                        </li>
                        <li class="active">
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
<?php $type= $envoye['type'];
            if ($type=='email') { echo ' <H3 style="margin-left:20px;margin-bottom:10px">  <i class="fa fa-lg fa-envelope"></i> Email</H3>'; }
            if ($type=='sms') { echo ' <H3 style="margin-left:20px;margin-bottom:10px"> <i class="fas fa-lg  fa-sms"></i> SMS</H3>'; }
            if ($type=='fax') { echo ' <H3 style="margin-left:20px;margin-bottom:10px"> <i class="fa fa-lg fa-fax"></i> FAX</H3>'; }

    ?>
            <form method="post" action="{{action('EmailController@send')}}"  enctype="multipart/form-data">
                <div class="form-group">
                    {{ csrf_field() }}
                    <label for="destinataire">destinataire:</label>
                    <div class="row">
                        <div class="col-md-10">
                            <input id="destinataire" type="text" class="form-control" name="destinataire" required value="{{ $envoye->destinataire }}" />
                        </div>
                        <div class="col-md-2">
                            <i id="emailso" onclick="visibilite('autres')" class="fa fa-lg fa-arrow-circle-down" style="margin-right:10px"></i>

                        </div>
                    </div>
                </div>
                <?php if ($type=='email') {?>
                <div class="form-group" style="margin-top:10px;">
                    <div id="autres" class="row"   >
                        <div class="col-md-1">
                            <label for="cc">CC:</label>
                        </div>
                        <div class="col-md-4">
                            <input id="cc" type="text" class="form-control" name="cc" value="{{ $envoye->cc }}"  />
                        </div>
                        <div class="col-md-1">
                            <label for="cci">CCI:</label>
                        </div>
                        <div class="col-md-4">
                            <input id="cci" type="text" class="form-control" name="cci" value="{{ $envoye->cci }}"  />
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="sujet">sujet :</label>
                    <input id="sujet" type="text" class="form-control" name="sujet" required value="{{ $envoye->sujet }}"/>
                </div>
                <?php }?>

                <div class="form-group">
                    <label for="description">Description :</label>
                    <input id="description" type="text" class="form-control" name="description" required value="{{ $envoye->description }}" />
                </div>
                <div class="form-group ">
                    <label for="contenu">contenu:</label>
                <div class="form-control" style="overflow:scroll;min-height:200px">
                  <?php $contenu= $envoye['contenu'];
                  echo $contenu;?>

                </div>

                </div>


            <?php use App\Attachement ;?>


                     <?php if ($envoye['nb_attach']>0){
                    echo '<br>Attachements :<br>';

                    $attachs = Attachement::get()->where('parent', '=', $envoye['id'] )->where('boite', '=', 1 );
                   // echo json_encode($attachs);
                   } ?>


                @if (!empty($attachs) )
                    <?php $i=1; ?>
                    @foreach ($attachs as $att)
                        <div class="tab-pane fade in" id="pj<?php echo $i; ?>">

                            <h4><b style="font-size: 13px;">{{ $att->nom }}</b> (<a target="_self" style="font-size: 13px;" href="{{ URL::asset('storage'.$att->path) }}" download>Télécharger</a>)</h4>

                        </div>

                            <iframe src="{{ URL::asset('storage'.$att->path) }}" frameborder="0" style="width:100%;min-height:640px;"></iframe>

                        @endforeach
                @endif




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
