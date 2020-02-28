@extends('layouts.mainlayout')
<?php // use DB; ?>
@section('content')

     <div class="row">

        <div class="col-lg-12 ">
         <?php if(isset($envoye['dossier'])){
           // $dossierid=App\Http\Controllers\DossiersController::IdDossierByRef(trim($envoye['dossier']));
            $dosss=    DB::table('dossiers')->where('reference_medic','like','%'.trim($envoye['dossier'].'%'))->first();
            $dossierid= $dosss->id;

             ?>   <span style="font-weight:bold;"><a  href="{{action('DossiersController@view',$dossierid)}}" ><?php  echo   $envoye['dossier'].' - '.    \App\Http\Controllers\DossiersController::FullnameAbnDossierById($dossierid );?> </a></span>

             <div class="btn-group pull-right">
                 <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                     <i class="fas fa-share"></i> Transférer <i class="fa fa-angle-down"></i>
                 </button>
                 <ul class="dropdown-menu pull-right">
                     <li>
                         <a href="{{route('emails.envoimailenreg',[$dossierid,'type'=> 'client','prest'=> 0,'entreeid'=>0,'envoyeid'=>$envoye['id'] ])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                             Au client </a>
                     </li>
                     <li>
                         <a href="{{route('emails.envoimailenreg',[$dossierid,'type'=> 'prestataire','prest'=> 0 ,'entreeid'=>0,'envoyeid'=>$envoye['id'] ])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                             À l'intervenant </a>
                     </li>
                     <li>
                         <a href="{{route('emails.envoimailenreg',[$dossierid,'type'=> 'assure','prest'=> 0 ,'entreeid'=>0,'envoyeid'=>$envoye['id']  ] )}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                             À l'assuré </a>
                     </li>

                 </ul>
             </div><br><?php } ?>

        <?php $type= $envoye['type'];
            if ($type=='email') { echo ' <H3 style="margin-left:20px;margin-bottom:10px">  <i class="fa fa-lg fa-envelope"></i> Email envoyé</H3>'; }
            if ($type=='sms') { echo ' <H3 style="margin-left:20px;margin-bottom:10px"> <i class="fas fa-lg  fa-sms"></i> SMS envoyé</H3>'; }
            if ($type=='fax') { echo ' <H3 style="margin-left:20px;margin-bottom:10px"> <i class="fa fa-lg fa-fax"></i> FAX envoyé</H3>'; }

    ?>

                <div class="form-group">
                    {{ csrf_field() }}
                    <label for="destinataire">destinataire:</label>
                    <div class="row">
                        <div class="col-md-10">
                   {{ $envoye->destinataire }}
                        </div>
                      <!--  <div class="col-md-2">
                            <i id="emailso" onclick="visibilite('autres')" class="fa fa-lg fa-arrow-circle-down" style="margin-right:10px"></i>

                        </div>-->
                    </div>
                </div>
                <?php if ($type=='email') {?>
                <div class="form-group" style="margin-top:10px;">
                    <div id="autres" class="row"   >
                     <?php if($envoye->cc !='') {?>
                        <div class="col-md-1">
                            <label for="cc">CC:</label>
                        </div>
                        <div class="col-md-5">
                             {{ $envoye->cc }}
                        </div>
                         <?php } ?>
                         <?php if($envoye->cci !='') {?>

                         <div class="col-md-1">
                            <label for="cci">CCI:</label>
                        </div>
                        <div class="col-md-5">
                            {{ $envoye->cci }}
                        </div>
                         <?php } ?>

                    </div>
                </div>

                <div class="form-group">
                    <label for="sujet">Sujet :</label>
                    <input id="sujet" type="text" class="form-control" name="sujet" required value="{{ $envoye->sujet }}"/>
                </div>
                <?php }?>

                <div class="form-group">
                    <label for="description">Description :</label>
                    <input id="description" type="text" class="form-control" name="description" required value="{{ $envoye->description }}" />
                </div>
                <?php if ( $envoye->type != 'fax' ) {?>
                <div class="form-group ">
                    <label for="contenu">contenu:</label>
                <div class="form-control" style="overflow:scroll;min-height:200px">
                  <?php $contenu= $envoye['contenu'];
                  echo $contenu;?>

                </div>

                </div>

                <?php } ?>

                <button class="btn btn-success " id="genererpdf"> Générer le PDF de l'email </button>

            <?php use App\Attachement ;?>


                     <?php // if ($envoye['nb_attach']>0){
                    echo '<br>Attachements :<br>';

                 //   $attachs = Attachement::where('parent',  $envoye['id'] )->where('boite', '=', 1 )->get();

                $envid=$envoye['id'];
                    $attachs = Attachement::where(function ($query)  use ($envid) {
                        $query->where('envoye_id',$envid )
                      ->where('boite',  1 );
                     })->orWhere(function ($query) use ($envid )   {
                        $query->where('parent', $envid )
                       ->where('boite',  1 );
                     })->get();




                 //  }
                ?>


                @if (!empty($attachs) )
                    <?php $i=1; ?>
                    @foreach ($attachs as $att)
                        <div class="tab-pane fade in" id="pj<?php echo $i; ?>">

                            <h4><b style="font-size: 13px;">{{ $att->nom }}</b> (<a target="_self" style="font-size: 13px;" href="{{ URL::asset('storage'.$att->path) }}" download>Télécharger</a>)</h4>

                        </div>

                            <iframe src="{{ URL::asset('storage'.$att->path) }}" frameborder="0" style="width:100%;min-height:440px;border:1px solid grey"></iframe>

                        @endforeach
                @endif






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

            $('#genererpdf').click(function() {


                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('emails.createpdf') }}",
                    method: "POST",
                    data: {   envoye:<?php echo $envoye['id']; ?>, _token: _token},
                    success: function (data) {

                        location.reload();

                    }
                });


            });


        });
    </script>


@endsection
