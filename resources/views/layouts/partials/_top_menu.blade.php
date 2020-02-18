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
        <!-- <div class="col-md-3">
              <a href="#" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
               <span class="fa fa-lg  fa-fw fa-phone"></span>
               <br>
                    Mes enregistrements
              </a>
          </div>-->
          <div class="col-sm-2">
              <a href="{{ route('prestataires') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
               <span class="fa fa-lg fa-fw fa-user-md"></span>
               <br>
                    Les Intervenants
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

        <?php      if( ($seance->superviseurmedic==$iduser)  || ($seance->superviseurtech==$iduser) ||($user->user_type=='admin')) { ?>

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
<?php }
?>    </div>

@can('isSupervisor')


      @endcan


    <div class="row">
        <div class="col-sm-2">
            <a href="{{ route('boites') }}" class="btn btn-default btn-md btn-responsive menu-item" role="button">
                <span class="fa fa-lg fa-fw fa-inbox"></span>
                <br>
                Emails Personnels
            </a>
        </div>

        <div class="col-sm-2">
            <a href="{{ route('transport') }}" class="btn btn-default btn-md btn-responsive menu-item" role="button">
                <span class="fas fa-lg  fa-calendar-alt"></span>
                <br>
                Missions Transport
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

       <?php if($user_type=='financier')
        {?>

        <div class="col-sm-2">
            <a href="{{ route('parametres') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
                <span class="fas fa-lg  fa-sliders-h"></span>
                <br>
                Paramètres
            </a>
        </div>

    <?php  } ?>



    </div>
    @can('isAdmin')
<!--
    <div class="col-sm-2">
        <a href="{{ route('users') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
            <span class="fa fa-lg fa-fw fa-users"></span>
            <br>
        Utilisateurs
        </a>
    </div>

    <div class="col-sm-2">
        <a href="{{ route('logs') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
            <span class="fa fa-lg fa-fw fa-history"></span>
            <br>
        Historique
        </a>
    </div>

    <div class="col-sm-2">
                <a href="{{ route('actualites') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
                    <span class="fa fa-lg fa-fw fa-bullhorn"></span>
                    <br>
                    Actualités
                </a>
    </div>
-->
    @endcan



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


    <div class="row">

        @can('isAdmin')
<!--
            <div class="col-sm-2">
                <a href="{{ route('clients') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
                    <span class="fas fa-lg   fa-hotel"></span>
                    <br>
                    Clients
                </a>
            </div>

            <div class="col-sm-2">
                <a href="{{ route('clientgroupes') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
                    <span class=" fas fa-lg   fa-industry "></span>
                    <br>
                    Groupes
                </a>
            </div>

            <div class="col-sm-2">
                <a href="{{ route('docs') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
                    <span class="   fas fa-lg fa-fw   fa-file-signature"></span>
                    <br>
                    Documents à signer
                </a>
            </div>

            <div class="col-sm-2">
                <a href="{{ route('cities') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
                    <span class="   fas fa-lg fa-fw fa-city"></span>
                    <br>
                Gouvernorats
                </a>
            </div>

	    -->

        @endcan
    </div>

    @can('isAdmin')
<!--
    <div class="row">


    <div class="col-sm-2">
        <a href="{{ route('typeprestations') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
            <span class="fas fa-lg   fa-clinic-medical"></span>
            <br>
            Types de prestations
        </a>
    </div>

        <div class="col-sm-2">
            <a href="{{ route('specialites') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
                <span class="fas fa-lg   fa-clinic-medical"></span>
                <br>
                Spécialités
            </a>
        </div>
    <div class="col-sm-2">
        <a href="{{ route('prestations') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
            <span class="fas fa-lg  fa-notes-medical"></span>
            <br>
            Prestations
        </a>
    </div>
        <div class="col-sm-2">
            <a href="{{ route('parametres') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
                <span class="fas fa-lg  fa-sliders-h"></span>
                <br>
                Paramètres
            </a>
        </div>
    </div>
    -->
    @endcan



        @can('isAdmin')
            <div class="row">

            <!--
            <div class="col-sm-2">
                <a href="{{ route('personnes') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
                    <span class="fas fa-lg  fa-user-nurse"></span>
                    <br>
                    Personnels
                </a>
            </div>

            <div class="col-sm-2">
                <a href="{{ route('voitures') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
                    <span class="fa fa-lg fa-fw fa-ambulance"></span>
                    <br>
                    Véhicules
                </a>
            </div>

            <div class="col-sm-2">
                <a href="{{ route('equipements') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
                    <span class="fas fa-lg fa-fw   fa-medkit"></span>
                    <br>
                    Equipements
                </a>
            </div>
-->
    </div>

@endcan


    <style>
    .menu-item{background-color: #4fc1e9!important;color:white!important;min-width:165px!important;margin-bottom:5px;margin-top:5px;}
</style>
