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
                <td>    <input id="tel" onchange="changing(this);"  type="text" class="form-control" name="tel"  id="tel" value="{{ $user->phone }}" />
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
            <td>    <textarea style="height:60px" id="signature" onchange="changing(this);"  type="text" class="form-control" name="observation"  id="observation"  ><?php echo  $user->signature ; ?></textarea>
            </td>
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

      <!--  <div class="form-group">
            <div class="col-md-6 col-md-offset-4 ">
                <button type="submit" class="btn btn-primary">
                    Enregistrer
                </button>
            </div>
        </div>-->
    </form>


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

</script>