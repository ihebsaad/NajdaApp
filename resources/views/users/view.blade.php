@extends('layouts.adminlayout')

@section('content')


    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


    <div class=" " style="padding:8px 8px 8px 8px">
        <div class="portlet box grey">
            <h3>   Utilisateur</h3>
        </div>
    <form class="form-horizontal" method="POST"  action="{{action('UsersController@update', $id)}}" >
        {{ csrf_field() }}


        <?php use \App\Http\Controllers\UsersController;     ?>


        <input type="hidden" id="iduser" value="{{$id}}" ></input>
        <table class="table">

        <tbody>

        <tr>
            <td class="text-primary">Prénom </td>
            <td>
                <input id="name" onchange="changing(this)" type="text" class="form-control" name="name"  value="{{ $user->name }}" />
                </td>
        </tr>
        <tr>
            <td class="text-primary">Nom </td>
            <td>
                <input id="lastname" onchange="changing(this)" type="text" class="form-control" name="lastname"  value="{{ $user->lastname }}" />
            </td>
        </tr>
        <tr>
            <td class="text-primary">Identifiant</td>
            <td> <input   id="username" autocomplete="off"  onchange="changing(this)" type="text" class="form-control" name="username" value="{{ $user->username }}" />          </td>
        </tr>
        <tr>
            <td class="text-primary">Mot de passe</td>
            <td> <input autocomplete="off"   onchange="changing(this)"  type="password" class="form-control" name="password"  id="password"   />          </td>
        </tr>
        <tr>
            <td class="text-primary">Email Boite</td>
            <td> <input id="boite" autocomplete="off" onchange="changing(this)"  type="email" class="form-control" name="boite" id="boite" value="{{ $user->boite }}" />                  </td>
        </tr>
        <tr>
            <td class="text-primary">Mote de passe boite</td>
            <td> <input id="passboite" autocomplete="off" onchange="changing(this)"   type="password" class="form-control" name="passboite"  id="passboite" value="" />
            </td>
        </tr>
            <tr>
                <td class="text-primary">Tel</td>
                <td>    <input id="phone" onchange="changing(this);"  type="text" class="form-control" name="phone"  id="phone" value="{{ $user->phone }}" />
<a style="margin-left:30px;cursor:pointer" data-toggle="modal"  data-target="#sendsms" ><i class="fas    fa-sms"></i>  Envoyer un SMS </a>
 <a  style="margin-left:30px;cursor:pointer"  data-toggle="modal"  data-target="#listesms" ><i class="fa fa-list"></i>  Liste des SMS </a>
                </td>
            </tr>
        <tr>
            <td class="text-primary">Observation</td>
            <td>    <textarea style="height:80px" id="observation" onchange="changing(this);"  type="text" class="form-control" name="observation"  id="observation"  ><?php echo  $user->observation ; ?></textarea>
            </td>
        </tr>
       <?php if($user->user_type!='admin') { ?>
        <tr>
            <td class="text-primary">Qualification</td>
            <td>
                <select  name="user_type"  id="user_type" onchange="changing(this);"  >
                    <option></option>
                     <option value="user"  <?php if($user->user_type=='user') {echo'selected="selected"';}?> >Agent </option>
                     <option value="autonome"  <?php if($user->user_type=='autonome') {echo'selected="selected"';}?> >Agent autonome </option>
                    <option  value="superviseur"  <?php if($user->user_type=='superviseur') {echo'selected="selected"';}?>  >Superviseur</option>
                    <option  value="financier"  <?php if($user->user_type=='financier') {echo'selected="selected"';}?>  >Financier</option>
                    <option  value="bureau"  <?php if($user->user_type=='bureau') {echo'selected="selected"';}?>  >Bureau d'ordre</option>
                </select>
            </td>
        </tr>
<?php } ?>

        <tr>
            <td class="text-primary">Signature</td>
            <td>    <textarea style="height:60px" id="signature" onchange="changing(this);"  type="text" class="form-control" name="signature"  ><?php echo  $user->signature ; ?></textarea>
            </td>

        </tr>
 <tr>
            <td class="text-primary"> <label style="padding-top:10px">Actif</label></td>
            <td>   
 <label for="actif" style="margin-left:15px;" class="">
                                            <div class="radio" id="uniform-actif"><span class="checked">
                                                <input  onclick="changing(this)" type="radio" name="actif" id="actif" value="1"   <?php if ($user->actif ==1){echo 'checked';} ?>></span></div> Oui
                                        </label>
            

   <label for="nonactif" class="" style="margin-left:100px;">
                                            <div class="radio" id="uniform-nonactif"><span>
                                                <input onclick="disabling('actif')" type="radio" name="actif" id="nonactif" value="0"  <?php if ($user->actif==0){echo 'checked';} ?>></span></div> Non
                                        </label> </td>

        </tr>

        </tbody>
        </table>

        <?php


        $missions=UsersController::countmissions($user->id);
        $actions=UsersController::countactions($user->id);
        $actives=UsersController::countactionsactives($user->id);
        $dossiers=UsersController::countaffectes($user->id);
        $notifications=UsersController::countnotifs($user->id);

        ?>
        <table id="tabstats" style="background-color: #a6e1ec;margin-left:80px ;margin-top:40px ;width:300px;font-weight: 600;">
            <tr><td><span>Notifications </span></td><td><b><?php echo $notifications;?></b></td></tr>
            <tr><td><span>Dossiers Affectés </span></td><td><b><?php echo $dossiers;?></b></td></tr>
            <tr><td><span>Missions en cours </span></td><td><b><?php echo $missions;?></b></td></tr>
            <tr><td><span>Total des Actions </span></td><td><b><?php echo $actions;?></b></tr>
            <tr><td><span>Actions Actives </span></td><td><b><?php echo $actives;?></b></td></tr>
        </table>

		<a href="{{action('UsersController@stats', $user['id'])}}" ><span class="btn btn-success" style='color:white;margin-left:80px ;margin-top:60px ;width:250px;font-weight: 600;font-size:20px;'>Statistiques détaillés </span></a>
		
      <!--  <div class="form-group">
            <div class="col-md-6 col-md-offset-4 ">
                <button type="submit" class="btn btn-primary">
                    Enregistrer
                </button>
            </div>
        </div>-->
    </form>

<?php
use App\Dossier ;
use App\Envoye ;
$dossiers = Dossier::get();
if ($user->phone!=''){
    $envoyes = Envoye::orderBy('id', 'desc')->where('type','sms' )->where('destinataire', $user->phone)->paginate(5);
}else{

}

?>


<!-- Modal SMS -->
<div class="modal fade" id="sendsms" tabindex="-1" role="dialog" aria-labelledby="sendingsms" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModal7">Envoyer un SMS </h5>

            </div>
            <form method="post" action="{{action('EmailController@sendsmsxml')}}" >
<input type="hidden"  name="smsuser" id="smsuser" class="form-control"  value="{{ $user->id }}">
            <div class="modal-body">
                <div class="card-body">


                        <div class="form-group">
                            {{ csrf_field() }}
                            <label for="description">Dossier:</label>

                            <div class="form-group">
                                 <select id ="dossier"  class="form-control " style="width: 120px">
                                    <option></option>
                                    <?php foreach($dossiers as $ds)

                                    {
                                        echo '<option value="'.$ds->reference_medic.'"> '.$ds->reference_medic.' </option>';}     ?>
                                </select>
                             </div>


                        </div>


                        <div class="form-group">
                            {{ csrf_field() }}

                            <label for="description">Description:</label>
                            <input id="description" type="text" class="form-control" name="description"     />
                        </div>

                        <div class="form-group">

                            <label for="destinataire">Destinataire:</label>
                            <input id="destinataire" type="number" class="form-control" name="destinataire"   value="{{ $user->phone }}"   />
                        </div>

                        <div class="form-group">
                            <label for="contenu">Message:</label>
                            <textarea  type="text" class="form-control" name="message"></textarea>
                        </div>
                        {{--  {!! NoCaptcha::renderJs() !!}     --}}
                      <!--  <script src="https://www.google.com/recaptcha/api.js" async defer></script>-->




                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button  type="submit"  class="btn btn-md  btn-primary btn_margin_top"><i class="fa fa-paper-plane" aria-hidden="true"></i> Envoyer</button>
            </div>
            </form>

        </div>
    </div>
</div>
<!-- Modal Liste sms -->
<div class="modal fade" id="listesms" tabindex="-1" role="dialog" aria-labelledby="liste" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModal7">SMS Envoyés </h5>

            </div>

                <div class="modal-body">
                    <div class="card-body" style="min-height: 100px">


    <div class="uper">
    <?php if (isset($envoyes)) { ?>
        @foreach($envoyes as $envoye)
            <div class="email">
                <div class="fav-box">
                   <!-- <a href="{{action('EnvoyesController@destroy', $envoye['id'])}}" class="btn btn-sm btn-danger btn-responsive" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom"  data-original-title="Supprimer">
                        <i class="fa fa-lg fa-fw fa-trash-alt"></i>

                    </a>-->
                </div>
                <div class="media-body pl-3">
                    <div class="subject">
                         <i class="fa fa-lg fa-sms"></i>
                        <?php echo $envoye->description; ?>
                    </div>

                <div class="stats">
                        <div class="row" style="margin-left:15px;margin-bottom:20px;margin-right:15px ;">
                            <p><?php echo $envoye->contenu; ?></p>
                             </div>

                    <div class="row">
                        <span><i class="fa fa-fw fa-clock-o"></i><?php echo  date('d/m/Y H:i', strtotime($envoye->created_at)) ; ?></span>
                    </div>

                    </div>
                </div>
            </div>
    </div>
    @endforeach


    <?php  $envoyes->links(); } ?>

             </div>

                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
             </div>

        </div>
    </div>
</div>
	<style>
        #tabstats {font-size: 15px;padding:30px 30px 30px 30px;}
        #tabstats td{border-left:1px solid white;border-bottom:1px solid white;min-width:50px;min-height: 25px;;text-align: center;}
        #tabstats tr{margin-bottom:15px;text-align: center;height: 40px;}
        </style>
@endsection



<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>


<script>

$(function () {
$('.itemName').select2({
filter: true,
language: {
noResults: function () {
return 'Pas de résultats';
}
}

});




});


    function changing(elm) {
        var champ = elm.id;

        var val = document.getElementById(champ).value;

        var user = $('#iduser').val();
        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('users.updating') }}",
            method: "POST",
            data: {user: user, champ: champ, val: val, _token: _token},
            success: function (data) {
                $('#' + champ).animate({
                    opacity: '0.3',
                });
                $('#' + champ).animate({
                    opacity: '1',
                });

            }
        });
        // } else {

        // }
    }
 function disabling(elm) {
        var champ=elm;

        var val =0;
        var user = $('#iduser').val();
        //if ( (val != '')) {
        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('users.updating') }}",
            method: "POST",
            data: {user: user , champ:champ ,val:val, _token: _token},
            success: function (data) {
                if (elm=='actif'){
                $('#nonactif').animate({
                    opacity: '0.3',
                });
                $('#nonactif').animate({
                    opacity: '1',
                });
                }


            }
        });
        // } else {

        // }
    }

</script>
