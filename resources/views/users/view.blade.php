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
            <td class="text-primary">Login</td>
            <td> <input readonly id="email" autocomplete="off" onchange="changing(this)" type="email" class="form-control" name="email"  id="email" value="{{ $user->email }}" />          </td>
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
        <tr>
            <td class="text-primary">Type</td>
            <td>
                <select  name="user_type"  id="user_type" onchange="changing(this);"  >
                    <option value="admin"  <?php if($user->user_type=='admin') {echo'selected="selected"';}?> >Administrateur</option>
                    <option value="user"  <?php if($user->user_type=='user') {echo'selected="selected"';}?> >Agent Simple</option>
                    <option  value="superviseur"  <?php if($user->user_type=='superviseur') {echo'selected="selected"';}?>  >Superviseur</option>
                    <option  value="dispatcheur"  <?php if($user->user_type=='dispatcheur') {echo'selected="selected"';}?>  >Dispatcheur</option>
                </select>
            </td>
        </tr>

     <!--   <tr>
            <td class="text-primary">Rôles</td>
            <td>
                <select class="itemName form-control col-lg-10" style="" name="roles"  multiple  id="roles" value="{{$user->roles}}">
                    <option></option>
                    <?php /* $c=0;if ( count($rolesusers) > 0 ) {
                    foreach($roles as $aKey){
                    ?>
                    <option  <?php if(UsersController::CheckRoleUser($user['id'],$aKey->id)==1){echo 'selected="selected" ';} ?>   value="<?php echo $aKey->id;?>" > <?php echo  $aKey->nom; ?>
                    </option>
                    <?php

                    }

                    } else{ ?>

                    @foreach($roles as $aKey)
                        <option       value="<?php echo $aKey->id;?>"> <?php echo $aKey->nom;?></option>
                    @endforeach

                    <?php } */ ?>

                </select>
            </td>
        </tr>--->
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


var $topo = $('.itemName');

var valArray = ($topo.val()) ? $topo.val() : [];

$topo.change(function() {
var val = $(this).val(),
numVals = (val) ? val.length : 0,
changes;
if (numVals != valArray.length) {
var longerSet, shortSet;
(numVals > valArray.length) ? longerSet = val : longerSet = valArray;
(numVals > valArray.length) ? shortSet = valArray : shortSet = val;
//create array of values that changed - either added or removed
changes = $.grep(longerSet, function(n) {
return $.inArray(n, shortSet) == -1;
});

Updating(changes, (numVals > valArray.length) ? 'selected' : 'removed');

}else{
// if change event occurs and previous array length same as new value array : items are removed and added at same time
Updating( valArray, 'removed');
Updating( val, 'selected');
}
valArray = (val) ? val : [];
});



function Updating(array, type) {
$.each(array, function(i, item) {

if (type=="selected"){


var user = $('#iduser').val();
var _token = $('input[name="_token"]').val();

$.ajax({
url: "{{ route('users.createuserrole') }}",
method: "POST",
data: {user: user , role:item ,  _token: _token},
success: function () {
$('.select2-selection').animate({
opacity: '0.3',
});
$('.select2-selection').animate({
opacity: '1',
});

}
});

}

if (type=="removed"){

var user = $('#iduser').val();
var _token = $('input[name="_token"]').val();

$.ajax({
url: "{{ route('users.removeuserrole') }}",
method: "POST",
data: {user: user , role:item ,  _token: _token},
success: function () {
$( ".select2-selection--multiple" ).hide( "slow", function() {
// Animation complete.
});
$( ".select2-selection--multiple" ).show( "slow", function() {
// Animation complete.
});
}
});

}

});
} // updating











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