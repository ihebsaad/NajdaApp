@extends('layouts.mainlayout')

@section('content')


    <link href="{{ asset('public/css/summernote.css') }}" rel="stylesheet" media="screen" />

    <?php use \App\Http\Controllers\EnvoyesController;     ?>
    <?php use \App\Http\Controllers\ClientsController;
    use \App\Http\Controllers\PrestatairesController ;
    ?>
    <h2> Envoyer un Fax </h2><br>
    <div class="row">
        <?php if($type=='client' )
        { ?>
        <a class="pull-right" href="#"  data-toggle="modal" data-target="#listeemails" > <i class="fa fa-lg fa-user"></i> Liste des Responsables </a>
        <?php  }  ?>

        </div>
        <div class="col-lg-11 ">

<form method="post" action="{{action('EmailController@sendfax')}}"  enctype="multipart/form-data"   >
    <input id="dossier" type="hidden" class="form-control" name="dossier"  value="{{$doss}}" />

    
    <div class="form-group">
         <label for="destinataire">Destinataire:</label>
        <div class="row">
            <div class="col-md-10">
              <!--  <input id="destinataire" type="text" class="form-control" name="nom" required />-->

                <?php if($type=='prestataire') {?>
                <select class="form-control" id="prest" required name="nom" >
                    <option ></option>
                    @foreach($prestataires as $prestat)
                        <option  <?php  if($prest==$prestat){ echo 'selected="selected" ';}  ?> value="<?php echo $prestat ;?>"> <?php   echo PrestatairesController::ChampById('name',$prestat); ;?></option>
                    @endforeach
                </select>
                <?php }                 ?>
                <?php if($type=='client') {?>

                <input id="nom" required type="text" name="nom" readonly class="form-control" value="<?php  echo ClientsController::ClientChampById('name',$refdem) ;?> " />
                <?php }                 ?>

                <?php if($type=='libre') {?>

               <input id="nom" required type="text" class="form-control" name="nom" required />
                <?php }                 ?>

            </div>


        </div>
    </div>

    <div class="form-group">
         <label for="numero">Numéro:</label>
        <div class="row">
            <div class="col-md-10">
             <?php  if($type=='libre'){ ?>
                  <input id="numero"  class="form-control" name="numero" required />
              <?php  }  else { ?>

                 <select   id="numero"  class="form-control" required name="numero" >
                @foreach($faxs as $fax)

                    <option   value="<?php echo $fax['champ']; ?>"> <?php   echo  $fax['champ'] ;?> (<?php   echo  $fax['type'] ;?> , <?php   echo  $fax['remarque'] ;?>)</option>
                @endforeach
                 </select>
                <?php } ?>
            </div>

        </div>
    </div>
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
        {{ csrf_field() }}
        <label for="description">Description:</label>
        <div class="row">
            <div class="col-md-10">
                <input id="commentaire" type="text" class="form-control" name="commentaire"   />
            </div>
 
        </div>

    </div>


    <div class="form-group form-group-default">
        <label>Attachements </label>
        <div class="row">
            <div class="col-md-10">
        <select id="attachs" class="itemName form-control col-lg-6" style="" name="attachs[]"  multiple    value="$('#attachs').val()">
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

   {{-- {!! NoCaptcha::display() !!}--}}

     <script src="https://www.google.com/recaptcha/api.js" async defer></script>




    <button  type="submit"  class="btn btn-md  btn-primary btn_margin_top"><i class="fa fa-paper-plane" aria-hidden="true"></i> Envoyer</button>

 </form>

        </div>




    <!-- Modal Liste emails -->
    <div class="modal fade" id="listeemails"   role="dialog" aria-labelledby="exampleModal001" aria-hidden="true"    >
        <div class="modal-dialog" role="document">
            <div class="modal-content"  style="width:800px">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal001">Liste des Faxs du client </h5>

                </div>
                <?php
                $qualites =   App\Adresse::where('nature', 'qualite')
                ->where('parent',$cl)
                ->get();

                $reseaux =   App\Adresse::where('nature', 'reseau')
                ->where('parent',$cl)
                ->get();

                $gestions =   App\Adresse::where('nature', 'gestion')
                ->where('parent',$cl)
                ->get();

                ?>
                <div class="modal-body">
                    <div class="card-body">
                        Responsables Gestion

                        <table class="table table-striped"  style="width:100%;margin-top:25px;font-size:16px;">
                            <thead>
                            <tr class="headtable">
                                <th style="width:15%">Nom </th>
                                <th style="width:20%">Fonction</th>
                                <!--   <th style="width:15%">Tel</th>-->
                                   <th style="width:15%">Fax</th>

                                <th style="width:10%">Observation</th>

                            </tr>

                            </thead>
                            <tbody>
                            @foreach($gestions as $gestion)
                                <tr>
                                    <td style="width:15%;">  <?php echo $gestion->nom; ?>   <?php echo $gestion->prenom; ?> </td>
                                    <td style="width:20%;"> <?php echo $gestion->fonction; ?> </td>
                                    <td style="width:21%;" onclick="addItem('<?php echo $gestion->fax; ?>')"><a href="#">  <?php echo $gestion->fax; ?> </a></td>
                                    <td style="width:10%;">  <?php echo $gestion->remarque; ?>  </td>

                                </tr>
                            @endforeach

                            </tbody>
                        </table>

                        Responsables Qualité

                        <table class="table table-striped"  style="width:100%;margin-top:25px;font-size:16px;">
                            <thead>
                            <tr class="headtable">
                                <th style="width:15%">Nom</th>
                                <th style="width:20%">Fonction</th>
                                     <th style="width:15%">Fax</th>
                                 <th style="width:10%">Observation</th>
                            </tr>

                            </thead>
                            <tbody>
                            @foreach($qualites as $qualite)
                                <tr>
                                    <td style="width:15%;"> <?php echo $qualite->nom; ?>   <?php echo $qualite->prenom; ?> </td>
                                    <td style="width:20%;"> <?php echo $qualite->fonction; ?> </td>
                                <!--   <td style="width:15%;"> <?php echo $qualite->tel; ?> </td>
                                    <td style="width:15%;">  <?php echo $qualite->fax; ?> </td>-->
                                    <td style="width:21%;"  onclick="addItem('<?php echo $qualite->fax; ?>')"> <a href="#"> <?php echo $qualite->fax; ?> </a></td>
                                    <td style="width:10%;">  <?php echo $qualite->remarque; ?> </td>

                                </tr>
                            @endforeach

                            </tbody>
                        </table>

                        Responsables Réseau

                        <table class="table table-striped"  style="width:100%;margin-top:25px;font-size:16px;">
                            <thead>
                            <tr class="headtable">
                                <th style="width:15%">Nom</th>
                                <th style="width:20%">Fonction</th>
                                <!--   <th style="width:15%">Tel</th>-->
                                   <th style="width:15%">Fax</th>
                                 <th style="width:10%">Observation</th>
                            </tr>

                            </thead>
                            <tbody>
                            @foreach($reseaux as $reseau)
                                <tr>
                                    <td style="width:15%;"> <?php echo $reseau->nom; ?>   <?php echo $qualite->prenom; ?> </td>
                                    <td style="width:20%;"> <?php echo $reseau->fonction; ?> </td>
                                <!--    <td style="width:15%;"> <?php // echo $reseau->tel; ?> </td>-->
                                    <td style="width:21%;" onclick="addItem('<?php echo $reseau->fax; ?>')"> <a href="#"> <?php echo $reseau->fax; ?> </a></td>
                                    <td style="width:10%;">  <?php echo $reseau->remarque; ?> </td>

                                </tr>
                            @endforeach

                            </tbody>
                        </table>



                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>



    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script type="text/javascript">

    function addItem(item){
        select = document.getElementById('numero');
        var opt = document.createElement('option');
        opt.value = item;
        opt.innerHTML = item;
        select.appendChild(opt);
        $('#listeemails').modal('hide');

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



        $('.itemName').select2({
            filter: true,
            language: {
                noResults: function () {
                    return 'Pas de résultats';
                }
            }
        });


        <?php  
		$param= App\Parametre::find(1);$env=$param->env;
$urlapp="http://$_SERVER[HTTP_HOST]/".$env;
		?>

        $("#prest").change(function(){
            //  prest = $(this).val();
            var  prest =document.getElementById('prest').value;

            if (prest>0)
            {

                window.location = '<?php echo $urlapp; ?>/emails/envoifax/<?php echo $doss; ?>/prestataire/'+prest;
            }else{
                window.location = "<?php echo $urlapp; ?>/emails/envoifax/<?php echo $doss; ?>/prestataire/0";

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

