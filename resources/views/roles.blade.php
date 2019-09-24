<head>
    <link rel="shortcut icon" href="{{ asset('public/img/favicon.ico') }}" type="image/x-icon">
<link rel="icon" href="{{ asset('public/img/favicon.ico') }}" type="image/x-icon">
<meta charset="UTF-8">
<title>Najda Assistances - Rôles</title>
</head>
<style>

 .demande{color:darkorange;font-weight:bold;margin-left:20px;}

    body {font-family: "Open Sans", serif !important;}
    /* The radio */
    .radio {

        display: block;
        position: relative;
        padding-left: 30px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 20px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none
    }

    /* Hide the browser's default radio button */
    .radio input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    /* Create a custom radio button */
    .checkround {

        position: absolute;
        top: 6px;
        left: 0;
        height: 20px;
        width: 20px;
        background-color: #fff ;
        border-color:#5D9CEC;
        border-style:solid;
        border-width:2px;
        border-radius: 50%;
    }


    /* When the radio button is checked, add a blue background */
    .radio input:checked ~ .checkround {
        background-color: #fff;
    }

    /* Create the indicator (the dot/circle - hidden when not checked) */
    .checkround:after {
        content: "";
        position: absolute;
        display: none;
    }

    /* Show the indicator (dot/circle) when checked */
    .radio input:checked ~ .checkround:after {
        display: block;
    }

    /* Style the indicator (dot/circle) */
    .radio .checkround:after {
        left: 2px;
        top: 2px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background:#20a5ff;


    }

    /* The check */
    .check {
        display: block;
        position: relative;
        padding-left: 25px;
        margin-bottom: 12px;
        padding-right: 15px;
        cursor: pointer;
        font-size: 18px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /* Hide the browser's default checkbox */
    .check input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    /* Create a custom checkbox */
    .checkmark {
        position: absolute;
        top: 3px;
        left: 0;
        height: 18px;
        width: 18px;
        background-color: #fff ;
        border-color:#5D9CEC;
        border-style:solid;
        border-width:2px;
    }



    /* When the checkbox is checked, add a blue background */
    .check input:checked ~ .checkmark {
        background-color: #fff  ;
    }

    /* Create the checkmark/indicator (hidden when not checked) */
    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    /* Show the checkmark when checked */
    .check input:checked ~ .checkmark:after {
        display: block;
    }

    /* Style the checkmark/indicator */
    .check .checkmark:after {
        left: 5px;
        top: 1px;
        width: 5px;
        height: 10px;
        border: solid ;
        border-color:#5D9CEC;
        border-width: 0 3px 3px 0;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
    }
    .btn{border-radius: 0!important;}
    .cust-btn{
        margin-bottom: 10px;
        background-color: #5D9CEC;
        border-width: 2px;
        border-color: #5D9CEC;
        color: #fff;
    }
    .cust-btn:hover{

        border-color: #5D9CEC;
        background-color: #fff;
        color: #5D9CEC;

    }


</style>

 <?php

 use App\Demande;
 use     \App\Http\Controllers\UsersController;
    $haveroles =   DB::table('roles_users')
            ->where(['user_id' => Auth::id()])
            ->count();
 //   if ($haveroles > 0)
  ///  {
    // use DB;
     $seance = DB::table('seance')->first();

 $user = auth()->user();
 $name=$user->name;
 $lastname=$user->lastname;
 $iduser=$user->id;
 $typeuser=$user->user_type;

 $debut=$seance->debut;
 $fin=$seance->fin;
 $date=date('l d/m/Y');

?>
 <script src="{{  URL::asset('public/js/jquery-1.11.1.min.js') }}" type="text/javascript"></script>


 <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script><!--
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>-->

<!------ Include the above in your HEAD tag ---------->

<html>
<body>
<div class="row">
<div class="col-md-3">
</div>
<div class="col-md-6" style="padding:80px 50px 80px 50px">
    <h1>Bienvenue <B style="color:#5D9CEC"><?php echo  $name .' '. $lastname; ?></B></h1>
	<h4>Nous sommes le <b><?php echo $date; ?></b></h4>

<h5>Sélectionnez votre/vos rôle(s) pendant cette séance :</h5>
    <small> (Cliquez sur un rôle attribué pour le demander)</small><br><br><br>
    <input type="hidden" id="par" value="<?php echo $iduser; ?>" >
{{ csrf_field() }}


<!--<label class="radio">Agent
        <input type="radio" checked name="is_company">
        <span class="checkround"></span>
    </label>-->


<?php 
    // verifier si user a le role
   // if (UsersController::CheckRoleUser(Auth::id(),2) > 0)
    //  {
        // verifier si le role est vide dans la seance et quil na pas deja le role
        if (empty($seance->dispatcheur) || ($seance->dispatcheur === Auth::id()))
        { 
?>
    <label class="check ">Dispatcheur
        <?php
        if ($seance->dispatcheur === Auth::id()) {  echo '<input type="checkbox" name="disp" checked>';} else {

            if (session()->has('disp'))
            {
                if (Session::get('disp') == 0)
                {
                    echo '<input type="checkbox" name="disp">';
                }
                else
                {
                    echo '<input type="checkbox" name="disp" checked>';
                }
            }
            else
            {
                echo '<input type="checkbox" name="disp">';
            }


          }  ?>
        <span class="checkmark"></span>
        <?php
        }
        else
        {
         $nomagent = app('App\Http\Controllers\UsersController')->ChampById('name',$seance->dispatcheur).' '.app('App\Http\Controllers\UsersController')->ChampById('lastname',$seance->dispatcheur); ?>

        <div><div style="height:18px;width:18px;top:22px;background-color:lightgrey;display:inline-block;"></div><label title="demander ce rôle"  onclick="demande('Dispatcheur Emails','<?php echo $seance->dispatcheur; ?>')"  style="display:inline-block;padding-left:5px;font-size:18px;cursor:pointer">Dispatcheur <b>( <?php echo $nomagent; ?>  )</b></label><label class="demande" id="labeldispatcheur"></label></div>
      <?php  }
      //  }
        ?>
    </label>
<?php 
     if( ($typeuser=='superviseur') || ($typeuser=='admin'))
      {
        if (empty($seance->superviseurmedic) || ($seance->superviseurmedic === Auth::id()))
        { 
?>
    <label class="check ">Superviseur Médical
        <?php

        if ($seance->superviseurmedic === Auth::id()) {  echo '<input type="checkbox" name="supmedic" checked>';} else {

            if (session()->has('supmedic')   )
            {
                if ((Session::get('supmedic') == 0)   )
                {
                    echo '<input type="checkbox" name="supmedic">';
                }
                else
                {
                    echo '<input type="checkbox" name="supmedic" checked>';
                }
            }
            else
            {
                echo '<input type="checkbox" name="supmedic">';
            }

     }

        ?>
        <span class="checkmark"></span>
        <?php
        }
        else
        {
         $nomagent = app('App\Http\Controllers\UsersController')->ChampById('name',$seance->superviseurmedic).' '.app('App\Http\Controllers\UsersController')->ChampById('lastname',$seance->superviseurmedic); ?>

        <div><div style="height:18px;width:18px;top:22px;background-color:lightgrey;display:inline-block;"></div><label title="demander ce rôle"  onclick="demande('Superviseur Médical','<?php echo $seance->superviseurmedic; ?>')"  style="display:inline-block;padding-left:5px;font-size:18px;cursor:pointer">Superviseur Médical <b>( <?php echo $nomagent; ?>  )</b></label><label  class="demande" id="labelsuperviseurmedic"></label></div>
     <?php   }
       }
        ?>
    </label>
<?php
    if( ($typeuser=='superviseur') || ($typeuser=='admin'))
    {
        if (empty($seance->superviseurtech) || ($seance->superviseurtech === Auth::id()))
        { 
?>
    <label class="check ">Superviseur Technique
        <?php

        if ($seance->superviseurtech === Auth::id()) {  echo '<input type="checkbox" name="suptech" checked>';} else {

        if (session()->has('suptech'))
            {
                if (Session::get('suptech') == 0)
                {
                    echo '<input type="checkbox" name="suptech">';
                }
                else
                {
                    echo '<input type="checkbox" name="suptech" checked>';
                }
            }
            else
            {
                echo '<input type="checkbox" name="suptech">';
            }

            }
        ?>
        <span class="checkmark"></span>
        <?php
        }
        else
        {
         $nomagent = app('App\Http\Controllers\UsersController')->ChampById('name',$seance->superviseurtech).' '.app('App\Http\Controllers\UsersController')->ChampById('lastname',$seance->superviseurtech); ?>

        <div><div style="height:18px;width:18px;top:22px;background-color:lightgrey;display:inline-block;"></div><label title="demander ce rôle"  onclick="demande('Superviseur Technique','<?php echo $seance->superviseurtech; ?>')" style="display:inline-block;padding-left:5px;font-size:18px;cursor:pointer">Superviseur Technique <b>( <?php echo $nomagent; ?>  )</b></label><label  class="demande" id="labelsuperviseurtech"></label></div>
      <?php  }
        }



        ?>
    </label>
<?php 
   // if (UsersController::CheckRoleUser(Auth::id(),5) > 0)
   //   {
        if (empty($seance->chargetransport) || ($seance->chargetransport === Auth::id()))
        { 
?>
    <label class="check ">Chargé de Transport
        <?php

        if ($seance->chargetransport === Auth::id()) {  echo '<input type="checkbox" name="chrgtr" checked>';} else {

        if (session()->has('chrgtr'))
            {
                if (Session::get('chrgtr') == 0)
                {
                    echo '<input type="checkbox" name="chrgtr">';
                }
                else
                {
                    echo '<input type="checkbox" name="chrgtr" checked>';
                }
            }
            else
            {
                echo '<input type="checkbox" name="chrgtr">';
            }

            }
        ?>
        <span class="checkmark"></span>
        <?php
        }
        else
        {
         $nomagent = app('App\Http\Controllers\UsersController')->ChampById('name',$seance->chargetransport).' '.app('App\Http\Controllers\UsersController')->ChampById('lastname',$seance->chargetransport); ?>

        <div><div style="height:18px;width:18px;top:22px;background-color:lightgrey;display:inline-block;"></div><label  title="demander ce rôle"  onclick="demande('Chargé de Transport','<?php echo $seance->chargetransport; ?>')" style="display:inline-block;padding-left:5px;font-size:18px;cursor:pointer">Chargé de Transport <b>( <?php echo $nomagent; ?>  )</b></label><label  class="demande" id="labelchargetransport"></label></div>
        <?php }
    //    }
        ?>
    </label>
<?php 
   // if (UsersController::CheckRoleUser(Auth::id(),6) > 0)
    //  {
        if (empty($seance->dispatcheurtel) || ($seance->dispatcheurtel === Auth::id()))
        { 
?>
    <label class="check ">Dispatcheur Téléphonique
        <?php

        if ($seance->dispatcheurtel === Auth::id()) {  echo '<input type="checkbox" name="disptel" checked>';} else {

        if (session()->has('disptel'))
            {
                if (Session::get('disptel') == 0)
                {
                    echo '<input type="checkbox" name="disptel">';
                }
                else
                {
                    echo '<input type="checkbox" name="disptel" checked>';
                }
            }
            else
            {
                echo '<input type="checkbox" name="disptel">';
            }

            }
        ?>
        <span class="checkmark"></span>
        <?php
        }
        else
        {
        $nomagent = app('App\Http\Controllers\UsersController')->ChampById('name',$seance->dispatcheurtel).' '.app('App\Http\Controllers\UsersController')->ChampById('lastname',$seance->dispatcheurtel); ?>

        <div><div style="height:18px;width:18px;top:22px;background-color:lightgrey;display:inline-block;"></div><label title="demander ce rôle"  onclick="demande('Dispatcheur Téléphonique','<?php echo $seance->dispatcheurtel; ?>')" style="display:inline-block;padding-left:5px;font-size:18px;cursor:pointer">Dispatcheur Téléphonique <b>( <?php echo $nomagent; ?>  )</b></label><label  class="demande" id="labeldispatcheurtel"></label></div>
        <?php }
   //     }
        ?>
    </label>



    <?php
    // $date_actu =time();
    $date_actu =date("H:i");

    // verif date actuelle par rapport seance
   if ( $date_actu < $debut || ($date_actu > $fin) )
     {

    if (empty($seance->veilleur) || ($seance->veilleur === Auth::id())  )
    {
    ?>
    <label class="check ">Veilleur de nuit
        <?php
        if ($seance->veilleur === Auth::id()) {  echo '<input type="checkbox" name="veilleur" checked>';} else {

        if (session()->has('veilleur'))
        {
            if (Session::get('veilleur') == 0)
            {
                echo '<input type="checkbox" name="veilleur">';
            }


        }
        else
        {
            echo '<input type="checkbox" name="veilleur">';
        }
   }
        ?>
        <span class="checkmark"></span>
        <?php
        }
        else
        {
            $nomagent = app('App\Http\Controllers\UsersController')->ChampById('name',$seance->veilleur).' '.app('App\Http\Controllers\UsersController')->ChampById('lastname',$seance->veilleur); ?>

            <div><div style="height:18px;width:18px;top:22px;background-color:lightgrey;display:inline-block;"></div><label title="demander ce rôle"  onclick="demande('Veilleur de Nuit','<?php echo $seance->veilleur; ?>')" style="display:inline-block;padding-left:5px;font-size:18px;cursor:pointer">Veilleur de nuit <b>( <?php echo $nomagent; ?> )</b></label><label  class="demande" id="labelveilleur"></label></div>
      <?php  }


           } // verif date actuelle par rapport seance
            else{
                echo '<input type="hidden" name="veilleur">';

            }
        ?>
    </label>


    <?php
    if(  ($typeuser=='admin'))
    {


    ?>
    <label class="check ">Administrateur
        <?php


        echo '<input type="checkbox" name="admin">';


        ?>
        <span class="checkmark"></span>
        <?php

        } else{

            echo '<input type="hidden" name="admin">';

        }
        ?>
    </label>


    {{ csrf_field() }}
<br>
 <button onclick="redirect()" class="btn cust-btn " type="button" id="btn-primary" style="margin-top:50px;font-size: 20PX;letter-spacing: 1px;width:150px">Entrer</button>
</div>

<div class="col-md-3">

</div>  
  
</div>
</body>


</html>

<!-- Modal Document-->
<div class="modal  " id="modalconfirm" >
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" style="text-align:center"  id="modalalert0"><center>Demande de rôle  </center> </h3>

            </div>
            <div class="modal-body">
                <div class="card-body">

                    <div style="text-align:center" class="row" >
                        <div style="text-align:center" class="     show" role="alert">

                            <h5> <center> Vous voulez demander ce rôle ?</center></h5>
                        </div>
                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <a id="oui"   class="btn btn  "   style="background-color:#5D9CEC; width:100px;color:#ffffff"   >Oui</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width:100px">Non</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@8/dist/sweetalert2.min.css" />


 <script>

     function removereponse(role)
     {
         var _token = $('input[name="_token"]').val();

         $.ajax({
             url:"{{ route('home.removereponse') }}",
             method:"POST",
             data:{role:role , _token:_token},
             success:function(data){

             }
         });
     }

     function redirect()
     {
         var _token = $('input[name="_token"]').val();
         var disp = 0;
         if ($('input[name="disp"]').is(':checked'))
         {disp=1;}
         var supmedic = 0;
         if ($('input[name="supmedic"]').is(':checked'))
         {supmedic=1;}
         var suptech = 0;
         if ($('input[name="suptech"]').is(':checked'))
         {suptech=1;}
         var chrgtr = 0;
         if ($('input[name="chrgtr"]').is(':checked'))
         {chrgtr=1;}
         var disptel = 0;
         if ($('input[name="disptel"]').is(':checked'))
         {disptel=1;}
         var admin=0;
         if ($('input[name="admin"]').is(':checked'))
         {admin=1;
          }
         veilleur=0;
         if ($('input[name="veilleur"]').is(':checked'))
         {veilleur=1;
         }

       //  var type="agent";
         //alert(disp +" | "+ dispmedic +" | "+ disptech +" | "+ chrgtr +" | "+disptel+" | "+type);
            $.ajax({
                url:"{{ route('users.sessionroles') }}",
                method:"POST",
                data:{disp:disp,supmedic:supmedic,suptech:suptech,chrgtr:chrgtr,disptel:disptel,veilleur:veilleur, _token:_token},
                success:function(data){

                    if((supmedic==1)||(suptech==1)){
                        window.location = '{{route('supervision')}}';
                    }else{

                    if(admin==1){
                        window.location = '{{route('parametres')}}';
                    }
                    else{

                        if(disp==1) {
                          window.location = '{{route('entrees.dispatching')}}';
                        }
                        else{
                            window.location = '{{route('home')}}';
                        }
                    }

                    //

                    }
                }
            });
     }


 function demande(role,vers) {

     $('#modalconfirm').modal({show:true});


     var par = $('#par').val();

     $('#oui').click(function() {
         $('#modalconfirm').modal('hide');

         if (role == 'Dispatcheur Emails') {
             nomrole = 'dispatcheur';
             //  $request->session()->put('disp',0);

         }

         if (role == 'Dispatcheur Téléphonique') {
             nomrole = 'dispatcheurtel';
             //   $request->session()->put('disptel',0) ;
         }

         if (role == 'Superviseur Médical') {
             nomrole = 'superviseurmedic';
             //   $request->session()->put('supmedic',0) ;
         }

         if (role == 'Superviseur Technique') {
             nomrole = 'superviseurtech';
             //   $request->session()->put('suptech',0) ;
         }

         if (role == 'Chargé de Transport') {
             nomrole = 'chargetransport';
             //    $request->session()->put('chrgtr',0)  ;
         }

         if (role == 'Veilleur de Nuit') {
             nomrole = 'veilleur';
             //   $request->session()->put('veilleur',0) ;
         }
         if ((par != '')) {
             var _token = $('input[name="_token"]').val();
             $.ajax({
                 url: "{{ route('home.demande') }}",
                 method: "POST",
                 data: {role: role, vers: vers, par: par, _token: _token},
                 success: function (data) {

                    // alert('Demande envoyée');
                     Swal.fire({
                         type: 'success',
                         title: 'Envoyée...',
                         text: "Demande envoyée"
                     });

                     $('#label' + nomrole).text(' (Demande Envoyée) ');

                     $('#label' + nomrole).css("color", "grey");


                 }
             });
         } else {
             // alert('ERROR');
         }

     }); //end click
 }
     <?php
     $urlapp=env('APP_URL');$urlapp=config('app.url');

     if (App::environment('local')) {
         // The environment is local
         $urlapp='http://localhost/najdaapp';
     }
       ?>

      checkreponses();

     function checkreponses(){
         console.log('checkreponses');
         $.ajax({
             type: "GET",
             url: "<?php echo $urlapp; ?>/checkreponses",
             success:function(data)
             {


                 if(data !='')
                 {
                     var obj = JSON.parse(data);

                     var id=obj.id ;
                     var par=obj.par ;
                     var role=obj.role ;
                     var vers=obj.vers ;
                     var emetteur=obj.emetteur ;
                     var statut=obj.statut ;
                     var type=obj.type ;


                     if (type=='reponserole') {

                         // role accepté
                         if(statut==0)
                       {
                         if (role== 'Dispatcheur Emails')
                         { nomrole = 'dispatcheur';
                           //  $request->session()->put('disp',0);
                          //  alert('Rôle '+role+' alloué');
                             Swal.fire({
                                 type: 'success',
                                 title: 'Alloué...',
                                 text: 'Rôle '+role+' alloué'
                             });
                          //   affecter(nomrole);
                            removereponse(role);

                         }

                         if (role== 'Dispatcheur Téléphonique')
                         { nomrole = 'dispatcheurtel';
                          //   $request->session()->put('disptel',0) ;
                          //   alert('Rôle '+role+' alloué');
                             Swal.fire({
                                 type: 'success',
                                 title: 'Alloué...',
                                 text: 'Rôle '+role+' alloué'
                             });
                             removereponse(role);

                         }

                         if (role== 'Superviseur Médical')
                         { nomrole = 'superviseurmedic';
                          //   $request->session()->put('supmedic',0) ;
                          //   alert('Rôle '+role+' alloué');
                             Swal.fire({
                                 type: 'success',
                                 title: 'Alloué...',
                                 text: 'Rôle '+role+' alloué'
                             });
                             removereponse(role);

                         }

                         if (role== 'Superviseur Technique')
                         { nomrole = 'superviseurtech';
                          //   $request->session()->put('suptech',0) ;
                            // alert('Rôle '+role+' alloué');
                             Swal.fire({
                                 type: 'success',
                                 title: 'Alloué...',
                                 text: 'Rôle '+role+' alloué'
                             });
                             removereponse(role);

                         }

                         if (role== 'Chargé de Transport')
                         { nomrole = 'chargetransport';
                         //    $request->session()->put('chrgtr',0)  ;
                           //  alert('Rôle '+role+' alloué');
                             Swal.fire({
                                 type: 'success',
                                 title: 'Alloué...',
                                 text: 'Rôle '+role+' alloué'
                             });
                             removereponse(role);

                         }

                         if (role== 'Veilleur de Nuit')
                         { nomrole = 'veilleur';
                          //   $request->session()->put('veilleur',0) ;
                            // alert('Rôle '+role+' alloué');
                             Swal.fire({
                                 type: 'success',
                                 title: 'Alloué...',
                                 text: 'Rôle '+role+' alloué'
                             });
                             removereponse(role);

                         }

                         $('#label'+nomrole).css("color", "green");
                         $('#label'+nomrole).text(' (Demande Acceptée) ');



                         // window.location = '{{route('roles')}}';

                         // supprimer les demandes


                         setTimeout(function(){
                             window.location = '{{route('roles')}}';
                         }, 3000);
                     }

                         // role refusé
                         if(statut == -1)
                         {
                             if (role== 'Dispatcheur Emails')
                             { nomrole = 'dispatcheur';
                                 //  $request->session()->put('disp',0);

                                 Swal.fire({
                                     type: 'error',
                                     title: 'Refusé...',
                                     text: 'Rôle '+role+' non alloué'
                                 });
                                 //   affecter(nomrole);
                                 removereponse(role);

                             }

                             if (role== 'Dispatcheur Téléphonique')
                             { nomrole = 'dispatcheurtel';
                                 //   $request->session()->put('disptel',0) ;

                                 Swal.fire({
                                     type: 'error',
                                     title: 'Refusé...',
                                     text: 'Rôle '+role+' non alloué'
                                 });
                                 removereponse(role);

                             }

                             if (role== 'Superviseur Médical')
                             { nomrole = 'superviseurmedic';
                                 //   $request->session()->put('supmedic',0) ;

                                 Swal.fire({
                                     type: 'error',
                                     title: 'Refusé...',
                                     text: 'Rôle '+role+' non alloué'
                                 });
                                 removereponse(role);

                             }

                             if (role== 'Superviseur Technique')
                             { nomrole = 'superviseurtech';
                                 //   $request->session()->put('suptech',0) ;

                                 Swal.fire({
                                     type: 'error',
                                     title: 'Refusé...',
                                     text: 'Rôle '+role+' non alloué'
                                 });
                                 removereponse(role);

                             }

                             if (role== 'Chargé de Transport')
                             { nomrole = 'chargetransport';
                                 //    $request->session()->put('chrgtr',0)  ;

                                 Swal.fire({
                                     type: 'error',
                                     title: 'Refusé...',
                                     text: 'Rôle '+role+' non alloué'
                                 });
                                 removereponse(role);

                             }

                             if (role== 'Veilleur de Nuit')
                             { nomrole = 'veilleur';
                                 //   $request->session()->put('veilleur',0) ;

                                 Swal.fire({
                                     type: 'error',
                                     title: 'Refusé...',
                                     text: 'Rôle '+role+' non alloué'
                                 });
                                 removereponse(role);

                             }

                             $('#label'+nomrole).css("color", "red");
                             $('#label'+nomrole).text(' (Demande Refusée) ');

                         /*    setTimeout(function(){
                                 window.location = '{{route('roles')}}';
                             }, 3000);*/
                         }

                     }


                     }

                 setTimeout(function(){
                     checkreponses();
                 }, 10000);  //10 secds

             }
         });
     }

  </script>
