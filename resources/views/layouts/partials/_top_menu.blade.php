<div class="container">
    <div class="row">
          <div class="col-md-3">
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
          <div class="col-md-3">
              <a href="{{ route('prestataires') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
               <span class="fa fa-lg fa-fw fa-user-md"></span>
               <br>
                    Les prestataires 
              </a>
          </div>
         <div class="col-md-3">
              <a href="{{ route('boites') }}" class="btn btn-default btn-md btn-responsive menu-item" role="button">
               <span class="fa fa-lg fa-fw fa-inbox"></span>
               <br>
                    Ma boîte de réception
              </a>
          </div>

    </div>

    <div class="row">

    @if(Gate::check('isAdmin') || Gate::check('isSupervisor'))


    @endif

    @can('isAdmin')

    <div class="col-md-3">
        <a href="{{ route('users') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
            <span class="fa fa-lg fa-fw fa-users"></span>
            <br>
        Utilisateurs
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('logs') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
            <span class="fa fa-lg fa-fw fa-history"></span>
            <br>
        Historique
        </a>
    </div>

    <div class="col-md-3">
                <a href="{{ route('actualites') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
                    <span class="fa fa-lg fa-fw fa-bullhorn"></span>
                    <br>
                    Actualités
                </a>
    </div>

    @endcan
    </div>



    <div class="row">

        @can('isAdmin')

            <div class="col-md-3">
                <a href="{{ route('clients') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
                    <span class="fas fa-lg   fa-hotel"></span>
                    <br>
                    Clients
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('clientgroupes') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
                    <span class=" fas fa-industry "></span>
                    <br>
                    Groupes
                </a>
            </div>


            <div class="col-md-3">
                <a href="{{ route('cities') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
                    <span class="   fas fa-lg fa-fw fa-city"></span>
                    <br>
                Gouvernorats
                </a>
            </div>
<!--
            <div class="col-md-3">
                <a href="#" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
                    <span class="fa fa-lg fa-fw fa-history"></span>
                    <br>

                </a>
            </div>
-->

        @endcan
    </div>

    @can('isAdmin')

    <div class="row">


    <div class="col-md-3">
        <a href="{{ route('typeprestations') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
            <span class="fas fa-lg   fa-clinic-medical"></span>
            <br>
            Types de prestations
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('prestations') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
            <span class="fas fa-notes-medical"></span>
            <br>
            Prestations
        </a>
    </div>

    </div>
    @endcan


    <div class="row">

        @can('isAdmin')

            <div class="col-md-3">
                <a href="{{ route('personnes') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
                    <span class="fas fa-user-nurse"></span>
                    <br>
                    Personnels
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('voitures') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
                    <span class="fa fa-lg fa-fw fa-ambulance"></span>
                    <br>
                    Véhicules
                </a>
            </div>

            <div class="col-md-3">
                <a href="{{ route('equipements') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
                    <span class="fas fa-lg fa-fw   fa-medkit"></span>
                    <br>
                    Equipements
                </a>
            </div>

        @endcan
    </div>

</div>

    <style>
    .menu-item{background-color: #4fc1e9!important;color:white!important;min-width:165px!important;margin-bottom:5px;margin-top:5px;}
</style>
