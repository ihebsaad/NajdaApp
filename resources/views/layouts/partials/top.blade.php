<header class="header">
        
        <div class="collapse bg-grey" id="navbarHeader">
             @include('layouts.partials._top_menu')

        </div>

    <div class="navbar ">
      <div class="row">
        <div class="col-sm-2 col-md-2 col-lg-2" style="">
           <a href="{{ route('home')}}" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                <img src="{{  URL::asset('public/img/logo.png') }}" alt="logo" />
            </a>
        </div>
        <div class="col-sm-1 col-md-1 col-lg-1" style="">
          <span  id="ndate" class="date" data-month="" data-year="" style="line-height: 1; padding-top: 13px;">
                    <p id="njour" style="font-size: 10px; margin-bottom: 0px!important;"></p><span id="numj">13</span>
           </span>
        </div>

        <div style="min-width:100px!important;padding-top:15px;padding-left:0px!important" class="col-sm-1 col-md-1 col-lg-1">
          <div class="time">
        time</div>
        </div>

        

        <div class="col-sm-1 col-md-1 col-lg-1 " style="padding-top:10px;padding-left:0px!important">
          <a href="#" class="btn btn-default btn-lg btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Demander pause de ton superviseur" style="margin-bottom: 28px!important;"> 
                                                <span class="fa fa-fw fa-pause"></span>
                                                <br>
                                                Pause
          </a> 
        </div>
        <div class="col-sm-3 col-md-3 col-lg-3" style=" height: 40px!important;padding-top:27px;padding-left:0px ">
          <form class="search-container">
            <input type="text" id="search-bar" placeholder="Recherche">
            <a href="#"><img class="search-icon" src="{{ URL::asset('public/img/search-icon.png') }}"></a>
                 <div id="countryList">
                </div>

           {{ csrf_field() }}
          </form>
        </div>
        <div class="col-sm-1 col-md-1 col-lg-1" style="padding-top:10px;">
          <a href="#" class="btn btn-primary btn-lg btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Lancer / Recevoir des appels téléphoniques" style="margin-bottom: 28px!important;padding-top: 15px;padding-bottom: 15px;"> 
              <span class="fa fa-fw fa-phone fa-2x"></span>
          </a> 
        </div>
        <div class="col-sm-1 col-md-1 col-lg-1" style="padding-top:10px;">
          <a href="{{ route('boite') }}" class="btn btn-danger btn-lg btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Boîte d'emails" style="margin-bottom: 28px!important;padding-top: 15px;padding-bottom: 15px;">
              <span class="fa fa-fw fa-envelope fa-2x"></span>
          </a> 
        </div>
        <div class="col-sm-1 col-md-1 col-lg-1">
          <ul class="nav navbar-nav" style="float: right!important;">


                    {{-- User Account --}}
                    @include('layouts.partials._user_menu')

                </ul>
        </div>

        <div class="col-sm-1 col-md-1 col-lg-1" class="navbar-toggler" data-toggle="collapse" data-target="#navbarHeader">
         <!-- <img class="menu-trigger" src="{{ URL::asset('resources/assets/img/menu-black.png') }}" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation"/>-->
          <div class="menu-icon menu-trigger" class="navbar-toggler" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation" alt="Menu de l'application"  style="zoom:60%;float: right!important; padding-top: 28px">
            <div class="line-1 no-animation"></div>
            <div class="line-2 no-animation"></div>
            <div class="line-3 no-animation"></div>
          </div>
        </div>
             
      </div><!--------------- row -->
    </div>

    </header>