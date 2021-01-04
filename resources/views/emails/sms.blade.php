@extends('layouts.mainlayout')

@section('content')
    <?php
    use App\Http\Controllers\ClientsController;
    use App\Http\Controllers\PrestatairesController;
    ?>
    <h2>Envoyer un SMS</h2>
    <?php if($type=='client' )
    { ?>
    <a class="pull-right" href="#"  data-toggle="modal" data-target="#listeemails" > <i class="fa fa-lg fa-user"></i>   Liste des Responsables </a><br>
    <?php  }  ?>

    <form method="post" action="{{action('EmailController@sendsmsxml')}}" >
       <input id="dossier" type="hidden" class="form-control" name="dossier"  value="{{$doss}}" />

        <div class="form-group">
            <?php if($type!='libre') {?>

            <label for="destinataire">Destinataire:</label>
            <div class="row">
                <div class="col-md-10">
                    <!--  <input id="destinataire" type="text" class="form-control" name="nom" required />-->

                    <?php if($type=='prestataire') {?>
                    <select class="form-control" id="prest" required name="nom" >
                        <option ></option>
                        @foreach($prestataires as $prestat)
                            <option  <?php  if($prest==$prestat){ echo 'selected="selected" ';}  ?> value="<?php echo $prestat ;?>"> <?php   echo PrestatairesController::ChampById('name',$prestat).' '.  PrestatairesController::ChampById('prenom',$prestat) ; ;?></option>
                        @endforeach
                    </select>
                    <?php }                 ?>
                    <?php if($type=='client') {?>

                    <input id="nom" required type="text" name="nom" readonly class="form-control" value="<?php  echo ClientsController::ClientChampById('name',$refdem) ;?> " />
                    <?php }                 ?>

                    <?php if($type=='assure') {?>

                    <input id="nom" required type="text" class="form-control" name="nom" value="<?php echo $nomabn ?>"/>
                    <?php }                 ?>

                </div>


            </div>
                <?php } ?>

        </div>

    <div class="form-group">

        <label for="destinataire">Numéro:</label>
      <!--  <input id="destinataire" type="number" class="form-control" name="destinataire"     />-->
      <?php if($type!='libre') {?>
        <select id="destinataire" class="form-control" name="destinataire" >
        <option value=""></option>
            <?php foreach($tels as $tel){   ?>
            <option value="<?php echo $tel->champ;?>"><?php echo $tel->champ .'( '.$tel->type.' , '.$tel->remarque.' )';?></option>
            <?php } ?>
        </select>
        <?php  }else{?>
      <input id="destinataire" type="number" class="form-control" name="destinataire"     />

    <?php } ?>


    </div>

        <div class="form-group">
            {{ csrf_field() }}
            <label for="description">Description:</label>
            <input id="description" type="text" class="form-control" name="description"     />
     </div>

<?php
$ref="";
if (isset($doss)){
$dossier= \App\Dossier::where('id',$doss)->first();
$ref  =  $dossier->reference_medic ;
}
?>
    <div class="form-group">
        <label for="contenu">Message:</label>
        <textarea  type="text" class="form-control" name="message" style="height:150px"><?php if ($ref!=""){echo $ref; ?> (ref à inclure dans votre réponse SVP)<?php }?></textarea>
    </div>
      {{--  {!! NoCaptcha::renderJs() !!}     --}}
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>

        <div class="form-group">
        <button  type="submit"  class="btn btn-md  btn-primary btn_margin_top"><i class="fa fa-paper-plane" aria-hidden="true"></i> Envoyer</button>
        </div>

    </form>






    <!-- Modal Liste emails -->
    <div class="modal fade" id="listeemails"   role="dialog" aria-labelledby="exampleModal001" aria-hidden="true"    >
        <div class="modal-dialog" role="document">
            <div class="modal-content"  style="width:800px">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal001">Liste des Tels du client </h5>

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
                                  <th style="width:15%">Tel</th>
                                <!--    <th style="width:15%">Fax</th>
                                <th style="width:21%">Email</th>-->
                                <th style="width:10%">Observation</th>

                            </tr>

                            </thead>
                            <tbody>
                            @foreach($gestions as $gestion)
                                <tr>
                                    <td style="width:15%;">  <?php echo $gestion->nom; ?>   <?php echo $gestion->prenom; ?> </td>
                                    <td style="width:20%;"> <?php echo $gestion->fonction; ?> </td>
                                   <td style="width:15%;" onclick="addItem('<?php echo $gestion->tel; ?>')"><a href="#"> <?php echo $gestion->tel; ?>  </a>  </td>
                                <!-- <td style="width:15%;">   <?php echo $gestion->fax; ?> </td>
                                    <td style="width:21%;" ><a href="#">  <?php echo $gestion->mail; ?> </a></td>-->
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
                                   <th style="width:15%">Tel</th>
                                <!--     <th style="width:15%">Fax</th>
                                <th style="width:21%">Email</th>-->
                                <th style="width:10%">Observation</th>
                            </tr>

                            </thead>
                            <tbody>
                            @foreach($qualites as $qualite)
                                <tr>
                                    <td style="width:15%;"> <?php echo $qualite->nom; ?>   <?php echo $qualite->prenom; ?> </td>
                                    <td style="width:20%;"> <?php echo $qualite->fonction; ?> </td>
                                    <td style="width:15%;" onclick="addItem('<?php echo $qualite->tel; ?>')"><a href="#"> <?php echo $qualite->tel; ?> </a></td>
                                <!--    <td style="width:15%;">  <?php echo $qualite->fax; ?> </td>
                                    <td style="width:21%;"  >  <?php echo $qualite->mail; ?> </td>  -->
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
                                  <th style="width:15%">Tel</th>
                                <!--    <th style="width:15%">Fax</th>
                                   <th style="width:21%">Email</th>-->
                                <th style="width:10%">Observation</th>
                            </tr>

                            </thead>
                            <tbody>
                            @foreach($reseaux as $reseau)
                                <tr>
                                    <td style="width:15%;"> <?php echo $reseau->nom; ?>   <?php echo $qualite->prenom; ?> </td>
                                    <td style="width:20%;"> <?php echo $reseau->fonction; ?> </td>
                                    <td style="width:15%;"  onclick="addItem('<?php echo $reseau->tel; ?>')"><a href="#"> <?php   echo $reseau->tel; ?></a> </td>
                                <!--     <td style="width:15%;">  <?php // echo $reseau->fax; ?> </td>
                                    <td style="width:21%;" >  <?php echo $reseau->mail; ?> </td>-->
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



    <?php
  $param= App\Parametre::find(1);$env=$param->env;
$urlapp="http://$_SERVER[HTTP_HOST]/".$env;
?>
    <script>

        function addItem(item){
            select = document.getElementById('destinataire');
            var opt = document.createElement('option');
            opt.value = item;
            opt.innerHTML = item;
            select.appendChild(opt);
            $('#listeemails').modal('hide');

        }


        $(document).ready(function(){

            $("#prest").change(function(){
                //  prest = $(this).val();
                var  prest =document.getElementById('prest').value;

                if (prest>0)
                {

                    window.location = '<?php echo $urlapp; ?>/emails/sms/<?php echo $doss; ?>/prestataire/'+prest;
                }else{
                    window.location = "<?php echo $urlapp; ?>/emails/sms/<?php echo $doss; ?>/prestataire/0";

                }


            });


            });
    </script>
@endsection
