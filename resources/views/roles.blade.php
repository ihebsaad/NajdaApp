 <?php

    use \App\Http\Controllers\UsersController;
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

?>
 <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<html>
<body>
<div class="row">
<div class="col-md-3">
</div>
<div class="col-md-6" style="padding:100px 50px 100px 50px">
    <h1>Bienvenue <?php echo  $name .' '. $lastname; ?></h1><br>
<h2>Sélectionnez votre/vos rôle(s) pendant cette séance :</h2><br>

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
            ?>
        <span class="checkmark"></span>
        <?php
        }
        else
        {
            $nomagent = app('App\Http\Controllers\UsersController')->ChampById('name',$seance->dispatcheur);
            echo '<div><div style="height:18px;width:18px;top:22px;background-color:lightgrey;display:inline-block;"></div><label style="display:inline-block;padding-left:5px;font-size:18px">Dispatcheur <b>( '.$nomagent.' )</b></label></div>';
        }
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
            if (session()->has('supmedic'))
            {
                if (Session::get('supmedic') == 0)
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
        ?>
        <span class="checkmark"></span>
        <?php
        }
        else
        {
            $nomagent = app('App\Http\Controllers\UsersController')->ChampById('name',$seance->superviseurmedic);
            echo '<div><div style="height:18px;width:18px;top:22px;background-color:lightgrey;display:inline-block;"></div><label style="display:inline-block;padding-left:5px;font-size:18px">Superviseur Médic <b>( '.$nomagent.' )</b></label></div>';
        }
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
        ?>
        <span class="checkmark"></span>
        <?php
        }
        else
        {
            $nomagent = app('App\Http\Controllers\UsersController')->ChampById('name',$seance->superviseurtech);
            echo '<div><div style="height:18px;width:18px;top:22px;background-color:lightgrey;display:inline-block;"></div><label style="display:inline-block;padding-left:5px;font-size:18px">Superviseur Technique <b>( '.$nomagent.' )</b></label></div>';
        }
        }



        ?>
    </label>
<?php 
   // if (UsersController::CheckRoleUser(Auth::id(),5) > 0)
   //   {
        if (empty($seance->chargetransport) || ($seance->chargetransport === Auth::id()))
        { 
?>
    <label class="check ">Chargé Transport
        <?php 
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
        ?>
        <span class="checkmark"></span>
        <?php
        }
        else
        {
            $nomagent = app('App\Http\Controllers\UsersController')->ChampById('name',$seance->chargetransport);
            echo '<div><div style="height:18px;width:18px;top:22px;background-color:lightgrey;display:inline-block;"></div><label style="display:inline-block;padding-left:5px;font-size:18px">Chargé Transport <b>( '.$nomagent.' )</b></label></div>';
        }
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
        ?>
        <span class="checkmark"></span>
        <?php
        }
        else
        {
            $nomagent = app('App\Http\Controllers\UsersController')->ChampById('name',$seance->dispatcheurtel);
            echo '<div><div style="height:18px;width:18px;top:22px;background-color:lightgrey;display:inline-block;"></div><label style="display:inline-block;padding-left:5px;font-size:18px">Dispatcheur Téléphonique <b>( '.$nomagent.' )</b></label></div>';
        }
   //     }
        ?>
    </label>



    <?php
    // $date_actu =time();
    $date_actu =date("H:i");
  /*  echo ' date_actu: '.$date_actu.'<br>';
    echo 'Debut: '.$debut.'<br>';
    echo 'Fin : '.$fin .'<br>';
    echo 'date_actu > debut : '.($date_actu > $debut).'<br>';
    echo 'date_actu > fin : '.($date_actu > $fin).'<br>';
    echo 'date_actu < debut : '.($date_actu < $debut).'<br>';
    echo 'date_actu < fin : '.($date_actu < $fin).'<br>';*/

    // verif date actuelle par rapport seance
   if ( $date_actu < $debut || ($date_actu > $fin) )
     {

    if (empty($seance->veilleur) || ($seance->veilleur === Auth::id())  )
    {
    ?>
    <label class="check ">Veilleur de nuit
        <?php
        if (session()->has('veilleur'))
        {
            if (Session::get('veilleur') == 0)
            {
                echo '<input type="checkbox" name="veilleur">';
            }
            else
            {
                echo '<input type="checkbox" name="veilleur" checked>';
            }
        }
        else
        {
            echo '<input type="checkbox" name="veilleur">';
        }

        ?>
        <span class="checkmark"></span>
        <?php
        }
        else
        {
            $nomagent = app('App\Http\Controllers\UsersController')->ChampById('name',$seance->veilleur);
            echo '<div><div style="height:18px;width:18px;top:22px;background-color:lightgrey;display:inline-block;"></div><label style="display:inline-block;padding-left:5px;font-size:18px">Dispatcheur Téléphonique <b>( '.$nomagent.' )</b></label></div>';
        }


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
 <button onclick="redirect()" class="btn cust-btn " type="button" id="btn-primary" style="font-size: 20PX;letter-spacing: 1px;width:150px">Entrer</button>
</div>

<div class="col-md-3">

</div>  
  
</div>
</body>
</html>


 <script>
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

       //  var type="agent";
         //alert(disp +" | "+ dispmedic +" | "+ disptech +" | "+ chrgtr +" | "+disptel+" | "+type);
            $.ajax({
                url:"{{ route('users.sessionroles') }}",
                method:"POST",
                data:{disp:disp,supmedic:supmedic,suptech:suptech,chrgtr:chrgtr,disptel:disptel, _token:_token},
                success:function(data){

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
                }
            });
     }
 </script>
<style>
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

/*}
else
{
    redirect()->to('home')->send();
}
 
*/