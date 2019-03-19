<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>
        @section('title')
            | Najda Assistance
        @show
    </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <!-- global css -->
    <link href="{{ URL::asset('public/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <!--<link href="{{ asset('assets/css/calendardate.css') }}" rel="stylesheet" type="text/css"/>-->
    <link href="{{ URL::asset('public/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('public/css/custom_css/chandra.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('public/css/custom_css/metisMenu.css') }}"  rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('public/css/custom_css/panel.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('public/css/custom_css/fixed_layout.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('public/css/custom_css/additional.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('public/css/custom_css/nestable_list.css') }}" rel="stylesheet">

    <!-- end of global css -->
    <!--page level css-->
    @yield('header_styles')
    <!--end of page level css-->
</head>
<body class="skin-chandra fixed_menu">
    <!-- header logo: style can be found in header-->
    <header class="header">
        
        <div class="collapse bg-grey" id="navbarHeader">
            @include('layouts._top_menu')
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
          </form>
        </div>
        <div class="col-sm-1 col-md-1 col-lg-1" style="padding-top:10px;">
          <a href="#" class="btn btn-primary btn-lg btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Lancer / Recevoir des appels téléphoniques" style="margin-bottom: 28px!important;padding-top: 15px;padding-bottom: 15px;"> 
              <span class="fa fa-fw fa-phone fa-2x"></span>
          </a> 
        </div>
        <div class="col-sm-1 col-md-1 col-lg-1" style="padding-top:10px;">
          <a href="#" class="btn btn-danger btn-lg btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Ma boîte d'emails" style="margin-bottom: 28px!important;padding-top: 15px;padding-bottom: 15px;"> 
              <span class="fa fa-fw fa-envelope fa-2x"></span>
          </a> 
        </div>
        <div class="col-sm-1 col-md-1 col-lg-1">
          <ul class="nav navbar-nav" style="float: right!important;">


                    {{-- User Account --}}
                    @include('layouts._user_menu')

                </ul>
        </div>

        <div class="col-sm-1 col-md-1 col-lg-1">
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
    
    <div class="content row" style="margin-top:120px;">
        <!-- Left side column. contains the logo and sidebar -->
        <div class="column col-lg-3"> 
           
            <div class="panel panel-default"  id="notificationspanel">
                                <div class="panel-heading" id="headernotifs">
                                    <h4 class="panel-title">Notifications</h4>
                                    <span class="pull-right">
                                       <i class="fa fa-fw clickable fa-chevron-up"></i>
                                        
                                    </span>
                                </div>
                                <div class="panel-body" style="display: block;">
                                    <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                                        <li class="active">
                                            <a href="#notificationstab" data-toggle="tab">Notifs</a>
                                        </li>
                                        <li>
                                            <a href="#notestab" data-toggle="tab">Notes</a>
                                        </li>
                                    </ul>
                                    <div id="NotificationsTabContent" class="tab-content">
                                        <div class="tab-pane fade active in  scrollable-panel" id="notificationstab">
                                            <div class="dd" id="nestable_list_3">
                                            <ol class="dd-list">
                                                <li class="dd-item dd3-item" data-id="13">
                                                    <div class="dd-handle dd3-handle"></div>
                                                    <div class="dd3-content">Item 13</div>
                                                </li><li class="dd-item dd3-item" data-id="14"><button data-action="collapse" type="button">Collapse</button><button data-action="expand" type="button" style="display: none;">Expand</button>
                                                    <div class="dd-handle dd3-handle"></div>
                                                    <div class="dd3-content">Item 14</div>
                                                <ol class="dd-list"><li class="dd-item dd3-item" data-id="15"><button data-action="collapse" type="button">Collapse</button><button data-action="expand" type="button" style="display: none;">Expand</button>
                                                    <div class="dd-handle dd3-handle"></div>
                                                    <div class="dd3-content">Item 15</div>
                                                    <ol class="dd-list">
                                                        <li class="dd-item dd3-item" data-id="16">
                                                            <div class="dd-handle dd3-handle"></div>
                                                            <div class="dd3-content">Item 16</div>
                                                        </li>
                                                        <li class="dd-item dd3-item" data-id="17">
                                                            <div class="dd-handle dd3-handle"></div>
                                                            <div class="dd3-content">Item 17</div>
                                                        </li>
                                                        <li class="dd-item dd3-item" data-id="18">
                                                            <div class="dd-handle dd3-handle"></div>
                                                            <div class="dd3-content">Item 18</div>
                                                        </li>
                                                    </ol>
                                                </li></ol></li>
                                                
                                                
                                            </ol>
                                            </div>

                                        </div>
                                        <div class="tab-pane fade  scrollable-panel" id="notestab">
                                        </div>
                                    </div>

                                </div>
            </div>
        </div>
        <div class="column col-lg-6">


            <!-- Content -->
            <div class="panel panel-primary">
              <div class="panel-heading">
                                    <h4 class="panel-title">Bienvenue </h4>
                                    <span class="pull-right">
                                       <i class="fa fa-fw clickable fa-chevron-up"></i>
                                        
                                    </span>
                                </div>
                <div class="panel-body" style="display: block;">                
                @yield('content')
                </div>
            </div>
            <!-- /.content -->
        </div>
        <div class="column col-lg-3">


            <!-- Content -->
            <div class="panel panel-danger">
                                <div class="panel-heading">
                                    <h4 class="panel-title">Actions</h4>
                                    <span class="pull-right">
                                       <i class="fa fa-fw clickable fa-chevron-up"></i>
                                        
                                    </span>
                                </div>
                                <div class="panel-body scrollable-panel" style="display: block;">
                                    actions here

                                </div>
            </div>

            <!-- /.content -->
        </div>
        <!-- /.right-side -->
    </div>
    <!-- /.right-side -->
    <!-- ./wrapper -->
    <!-- global js -->
    <script src="{{  URL::asset('public/js/jquery-1.11.1.min.js') }}" type="text/javascript"></script>
    
    <script src="{{  URL::asset('public/js/jquery-ui/jquery.ui.min.js') }}" type="text/javascript"></script>

    <script src="{{  URL::asset('public/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{  URL::asset('public/js/custom_js/app.js') }}" type="text/javascript"></script>
    <script src="{{  URL::asset('public/js/custom_js/metisMenu.js') }}" type="text/javascript"></script>
    <script src="{{  URL::asset('public/js/jquery-slimscroll/js/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
    <script src="{{  URL::asset('public/js/custom_js/rightside_bar.js') }}" type="text/javascript"></script>
    <script src="{{  URL::asset('public/js/custom_js/fixed_layout.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        var language = window.navigator.language;
        if (language.length > 2) {
            language = language.split('-');
            language = language[0];
        }

        //language = "fr"; // manually set language

        if (language === "en") {
            var weekday = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
            var month = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        } else if (language === "cz") {
            var weekday = ["Neděle", "Pondělí", "Úterý", "Středa", "Čtvrtek", "Pátek", "Sobota"];
            var month = ["Leden", "Únor", "Březen", "Duben", "Květen", "Červen", "Červenec", "Srpen", "Září", "Říjen", "Listopad", "Prosinec"];
        } else if (language === "it") {
            var weekday = ['Domenica', 'Luned&#236', 'Marted&#236', 'Mercoled&#236', 'Gioved&#236', 'Venerd&#236', 'Sabato'];
            var month = ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"];
        } else if (language === "sp") {
            var weekday = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];
            var month = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        } else if (language === "de") {
            var weekday = ["Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag"];
            var month = ["Januar", "Februar", "März", "April", "Mai", "Juni", "Ju li", "August", "September", "Oktober", "November", "Dez ember"];
        } else if (language === "fr") {
            var weekday = ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];
            var month = ["Janvie", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];

        } else if (language === "zh") {
            var weekday = ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'];
            var month = ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'];
        } else {
            var weekday = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
            var month = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        }
        myDate = new Date();
        mnth = myDate.getMonth();
        dat = myDate.getDate();
        day = myDate.getDay();
        oday = (dat < 10 ? "0" : "") + dat;
        year = myDate.getFullYear();
        
        $('#njour').text(weekday[day]);
        $('#numj').text(oday);
        $('#ndate').attr('data-month',month[mnth]);
        $('#ndate').attr('data-year',year);
        setInterval(function(){
        myDate = new Date();
        //+myDate.getDate()
        $('.time').html(myDate.getHours() + ":" + myDate.getMinutes() + "<b>" + myDate.getSeconds()+"</b>");
        }, 1000);

        $('.menu-icon').bind('click', function() {
          $(this).toggleClass('active');
          $(this).find('div').removeClass('no-animation');
        });
    </script>
    <!-- end of page level js -->
    <!-- begin page level js -->
    @yield('footer_scripts')
   <script src="{{  URL::asset('public/js/nestable-list/jquery.nestable.js') }}"></script>
   <!--<script src="{{ asset('assets/js/custom_js/nestable.js') }}" type="text/javascript"></script>-->
   <script type="text/javascript">$(function() {
        $('#nestable_list_3').nestable();
    });</script>
    <!-- end page level js -->
</body>
</html>