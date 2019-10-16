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
                    Les Prestataires
              </a>
          </div>


        <?php
        $seance =  DB::table('seance')
            ->where('id','=', 1 )->first();
        $disp=$seance->dispatcheur ;

        $iduser=Auth::id();
        if ($iduser==$disp) {
            ?>
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


        <?php } else{?>

            <div class="col-sm-2">
            <a href="{{ route('entrees.dispatching') }}" class="btn btn-default btn-md btn-responsive menu-item" role="button">
                <span class="fas fa-lg  fa-map-signs"></span>
                <br>
            Dispatching
            </a>
        </div>

        <div class="col-sm-2">
            <a href="{{ route('boite') }}" class="btn btn-default btn-md btn-responsive menu-item" role="button">
                <span class="fas fa-lg  fa-envelope"></span>
                <br>
                Boites et archives
            </a>
        </div>

       <?php }

        ?>

    </div>

    <div class="row">

    @if(Gate::check('isAdmin') || Gate::check('isSupervisor'))

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
    @endif
    </div>

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
                <br><?php $count= \App\Http\Controllers\DossiersController::DossiersInactifs();?>
                Dossiers Inactifs <span   class="label label-warning" style="color:black"><?php echo $count  ;?></span>
            </a>
        </div>

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
    $dollar=$parametres->dollar ;
    $euro=$parametres->euro ;
    ?>
    <div class="col-md-2 btn bg-success  btn-md btn-responsive    " style="cursor:default;max-width:180px;margin-left:10px;margin-top:20px;margin-bottom:15px;"  >
        <span style="color:white;font-size:15px; ">1 Dollar ($) = <?php echo $dollar; ?> <small>dt</small></span><br>
        <span style="color:white;font-size:15px; ">1 Euro (€) =  <?php echo $euro; ?> <small>dt</small></span>
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
