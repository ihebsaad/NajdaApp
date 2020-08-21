<?php
$user = auth()->user();
 $iduser=$user->id;
$user_type=$user->user_type;

$seance =  DB::table('seance')
    ->where('id','=', 1 )->first();

?>
<div class="container">
    <div class="row">
          <div class="col-sm-2">
              <a href="{{ route('dossiers') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
               <span class="fa fa-lg fa-fw fa-folder"></span>
               <br>
                    Liste des dossiers
              </a>
          </div>
        <?php      if( ($seance->superviseurmedic==$iduser)  || ($seance->superviseurtech==$iduser) ||($user->user_type=='admin')) { ?>

		   <div class="col-sm-2">
                <a href="{{ route('missions.calendriermissions') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
                    <span class="fas fa-lg fa-clipboard-list"></span>
                    <br>
                   Missions plateau
                </a>
            </div>
		<?php } ?>	
			<div class="col-sm-2">
            <a href="{{ route('transporth') }}" class="btn btn-default btn-md btn-responsive menu-item" role="button">
                <span class="fas fa-lg  fa-calendar-alt"></span>
                <br>
                Missions Transport
            </a>
        </div>
		 

    </div>


	

        <?php      if( ($seance->superviseurmedic==$iduser)  || ($seance->superviseurtech==$iduser) ||($user->user_type=='admin')) { ?>
	    <div class="row">

            <div class="col-sm-2">
            <a href="{{ route('supervision') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
                <span class="fas fa-lg  fa-users-cog"></span>
                <br>
                Supervision
            </a>
        </div>

            <div class="col-sm-2">
                <a href="{{ route('affectation') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
                    <span class="fas fa-lg  fa-user-tag"></span>
                    <br>
                    Affectations
                </a>
            </div>

            <div class="col-sm-2">
                <a href="{{ route('dossiers.affectclassique') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
                    <span class="fas fa-lg  fa-user-tag"></span>
                    <br>
                    Affectation Classique
                </a>
            </div>
     </div>

<?php }
?> 


<div class="row">

       <div class="col-sm-2">
              <a href="{{ route('prestataires') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
               <span class="fa fa-lg fa-fw fa-user-md"></span>
               <br>
                   Intervenants
              </a>
          </div>


        <div class="col-sm-2">
            <a href="{{ route('entrees.index') }}" class="btn btn-default btn-md btn-responsive menu-item" role="button">
                <span class="fas fa-lg  fa-envelope"></span>
                <br>
                Boite Principale
            </a>
        </div>

        <div class="col-sm-2">
            <a href="{{ route('boite') }}" class="btn btn-default btn-md btn-responsive menu-item" role="button">
                <span class="fas fa-lg  fa-envelope"></span>
                <br>
                Boites et archives
            </a>
        </div>
		
</div>

  

    <div class="row">
	
	    <div class="col-sm-2">
            <a href="{{ route('prestataires.mails') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
                <span class="   fas fa-mail-bulk"></span>
                <br>
                Emails Interventants
            </a>
        </div>
		
	<div class="col-sm-2">
                <a href="{{ route('clients.mails') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
                    <span class="   fas fa-mail-bulk"></span>
                    <br>
                Emails Clients
                </a>
     </div>
	 
 

        <div class="col-sm-2">
            <a href="{{ route('inactifs') }}" class="btn btn-default btn-md btn-responsive menu-item" role="button">
                <span class="fas fa-lg  fa-warning"></span>
                <br><?php $count=  \App\Dossier::where('sub_status', 'immobile')
                    ->where('current_status', 'inactif')
                     ->count();
                  ?>
                Dossiers Immobiles <span   class="label label-warning" style="color:black"><?php echo $count  ;?></span>
            </a>
        </div>
		
    </div>

  
	<div class="row">
	
       <div class="col-sm-2">
            <a href="{{ route('boites') }}" class="btn btn-default btn-md btn-responsive menu-item" role="button">
                <span class="fa fa-lg fa-fw fa-inbox"></span>
                <br>
                Boite mail perso 
            </a>
        </div>
		  <?php if($user_type=='financier' || $user_type=='bureau'  )
        {?>
        <div class="col-sm-2">
            <a href="{{ route('parametres') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
                <span class="fas fa-lg  fa-sliders-h"></span>
                <br>
                Paramètres
            </a>
        </div>

        <div class="col-sm-2">
            <a href="{{ route('factures') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
                <span  class="fas fa-lg fa-file-invoice"></span>
                <br>
                Finances
            </a>
        </div>
    <?php  } ?>

    </div>

 

   <div class="row"  style="padding-left:50px">


    <?php
    $parametres =  DB::table('parametres')
        ->where('id','=', 1 )->first();
    $dollarA=$parametres->dollar_achat ;
    $dollarV=$parametres->dollar_vente ;
    $euroA=$parametres->euro_achat ;
    $euroV=$parametres->euro_vente ;
    ?>
    <div class="col-md-4 btn bg-success  btn-md btn-responsive    " style="cursor:default;max-width:230px;margin-left:10px;margin-top:20px;margin-bottom:15px;"  >
        <span style="color:white;font-size:15px; ">1 Dollar ($) <small>Achat</small>  = <?php echo $dollarA; ?> <small>dt</small></span><br>
        <span style="color:white;font-size:15px; ">1 Euro (€) <small>Achat</small> =  <?php echo $euroA; ?> <small>dt</small></span>
    </div>

    <div class="col-md-4 btn bg-info  btn-md btn-responsive    " style="cursor:default;max-width:230px;margin-left:10px;margin-top:20px;margin-bottom:15px;"  >
        <span style="color:white;font-size:15px; ">1 Dollar ($) <small>Vente</small>  = <?php echo $dollarV; ?> <small>dt</small></span><br>
        <span style="color:white;font-size:15px; ">1 Euro (€) <small>Vente</small>  =  <?php echo $euroV; ?> <small>dt</small></span>
    </div>
    </div>

  
</div><!--- container---->

 


    <style>
    .menu-item{background-color: #4fc1e9!important;color:white!important;min-width:165px!important;margin-bottom:5px;margin-top:5px;}
</style>
