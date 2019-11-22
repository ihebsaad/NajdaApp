@extends('layouts.mainlayout')

@section('content')
    <?php
    use App\Http\Controllers\ClientsController;
    use App\Http\Controllers\PrestatairesController;
    ?>
    <h2>Envoyer un SMS</h2>

    <form method="post" action="{{action('EmailController@sendsms')}}" >
       <input id="dossier" type="hidden" class="form-control" name="dossier"  value="{{$doss}}" />

        <div class="form-group">
            {{ csrf_field() }}
            <label for="description">Description:</label>
            <input id="description" type="text" class="form-control" name="description"     />
     </div>

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
                            <option  <?php  if($prest==$prestat){ echo 'selected="selected" ';}  ?> value="<?php echo $prestat ;?>"> <?php   echo PrestatairesController::ChampById('name',$prestat); ;?></option>
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
            <?php foreach($tels as $tel){ ?>
            <option value="<?php echo $tel;?>"><?php echo $tel;?></option>
            <?php } ?>
        </select>
        <?php  }else{?>
      <input id="destinataire" type="number" class="form-control" name="destinataire"     />

    <?php } ?>


    </div>

    <div class="form-group">
        <label for="contenu">Message:</label>
        <textarea  type="text" class="form-control" name="message"></textarea>
    </div>
      {{--  {!! NoCaptcha::renderJs() !!}     --}}
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>

        <div class="form-group">
        <button  type="submit"  class="btn btn-md  btn-primary btn_margin_top"><i class="fa fa-paper-plane" aria-hidden="true"></i> Envoyer</button>
        </div>

    </form>
<?php
$urlapp="http://$_SERVER[HTTP_HOST]/najdaapp";

?>
    <script>


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