<div class="container">
          <div class="col-lg-2">
              <a href="{{ route('dossiers') }}" class="btn btn-default btn-md btn-responsive" role="button">
               <span class="fa fa-fw fa-folder"></span>
               <br>
                    Liste des dossiers
              </a>
          </div>
          <div class="col-lg-2">
              <a href="#" class="btn btn-default btn-md btn-responsive" role="button">
               <span class="fa fa-fw fa-phone"></span>
               <br>
                    Mes enregistrements
              </a>
          </div>
          <div class="col-lg-2">
              <a href="#" class="btn btn-default btn-md btn-responsive" role="button">
               <span class="fa fa-fw fa-users"></span>
               <br>
                    Les prestataires 
              </a>
          </div>
          <div class="col-lg-2">
              <a href="#" class="btn btn-default btn-md btn-responsive" role="button">
               <span class="fa fa-fw fa-inbox"></span>
               <br>
                    Ma boîte de réception
              </a>
          </div>


    @if(Gate::check('isAdmin') || Gate::check('isSupervisor'))
    <div class="col-lg-1">

        test supe 1
    </div>

    <div class="col-lg-1">

        test sup 2
    </div>
    @endif

    @can('isAdmin')

    <div class="col-lg-1">

         test admin 1
    </div>

    <div class="col-lg-1">

        test admin 2
    </div>
    @endcan

</div>