@extends('layouts.mainlayout')

@section('content')


    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


    <div class=" " style="padding:8px 8px 8px 8px">
        <div class="portlet box grey">
            <div class="modal-header">Utilisateur</div>
        </div>
    <form class="form-horizontal" method="POST"  action="{{action('UsersController@update', $id)}}" >
        {{ csrf_field() }}

    <div class="form-group">
        <label for="ID">Numéro:</label>
        <input id="id" type="text" class="form-control col-lg-6" name="id"  readonly value={{ $user->id }} />
    </div>
    <div class="form-group">
         <label for="name">Nom:</label>
        <input id="name" type="text" class="form-control col-lg-6" name="name"  value={{ $user->name }} />
    </div>
<div class="form-group">
    <label for="type">Email :</label>
    <input id="type" type="text" class="form-control col-lg-6" name="email"  value={{ $user->email }} />
</div>
<div class="form-group">
    <label for="user_type">Type :</label>
     <select  name="user_type"   >
        <option value="user"  <?php if($user->user_type=='user') {echo'selected';}?> >Simple</option>
        <option  value="admin"  <?php if($user->user_type=='admin') {echo'selected';}?>  >Admin</option>
    </select>
</div>
        <?php use \App\Http\Controllers\UsersController;     ?>
        <div class="form-group  ">
            <label>Rôles</label>
                  <select class="itemName form-control col-lg-6" style="" name="roles"  multiple  id="roles" value="{{$user->roles}}">
                    <option></option>
                    <?php $c=0;if ( count($rolesusers) > 0 ) {
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

                    <?php }  ?>

                </select>
         </div>

      <!--  <div class="form-group">
            <div class="col-md-6 col-md-offset-4 ">
                <button type="submit" class="btn btn-primary">
                    Enregistrer
                </button>
            </div>
        </div>-->
    </form>


</div>
	
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


var user = $('#id').val();
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

var user = $('#id').val();
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

</script>