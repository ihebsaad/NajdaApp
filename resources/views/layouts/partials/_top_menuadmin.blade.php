<div class="container">
 
 

    <div class="row">

    @if(Gate::check('isAdmin') || Gate::check('isSupervisor'))


    @endif

    @can('isAdmin')

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

    @endcan
    </div>



    <div class="row">

        @can('isAdmin')

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

	    

        @endcan
    </div>


    <div class="row">

        @can('isAdmin')

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
            <a href="{{ route('parametres') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
                <span class="fas fa-lg  fa-sliders-h"></span>
                <br>
                Paramètres
            </a>
        </div>
        @endcan

            @cannot('isAdmin')

        <div class="col-sm-2">
            <a href="{{ route('prestations') }}" class="btn btn-default btn-md btn-responsive  menu-item" role="button">
                <span class="fas fa-lg  fa-notes-medical"></span>
                <br>
                Prestations
            </a>
        </div>
            @endcannot

    </div>


    <div class="row">

        @can('isAdmin')

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

        @endcan
    </div>

</div>

    <style>
    .menu-item{background-color: #4fc1e9!important;color:white!important;min-width:165px!important;margin-bottom:5px;margin-top:5px;}
</style>