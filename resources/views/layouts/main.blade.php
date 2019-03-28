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
    <link href="{{ URL::asset('public/css/custom_css/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('public/css/custom_css/alertmessage.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('public/js/jstree/themes/default/style.min.css') }}" rel="stylesheet">
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
          <a href="{{ route('boite') }}" class="btn btn-danger btn-lg btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Ma boîte d'emails" style="margin-bottom: 28px!important;padding-top: 15px;padding-bottom: 15px;"> 
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
    <div class="row" style="margin-top:110px;">
        <div class="carousel-wrap">
          <div class="owl-carousel">
            @foreach ($dossiers as $i) 
            <div class="item">
                <a class="dossieritem" href="#" id="{{ $i->id }}"" >
                    <div class="dossiercr well well-gc well-sm" >
                        <h3 class="cutlongtext">{{ $i->ref }}</h3>
                        <p class="cutlongtext">{!!$i->abonnee!!}</p>
                    </div>
                </a>
            </div>
            @endforeach
          </div>
        </div>
        
    </div>

    <div class="content row">
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
                                            <div class="row" style="width: 99%">
                                               <div class="col-xs-9 col-md-9 align-left"> 
                                                    <div class="select">
                                                      <select>
                                                        <option>Trier par</option>
                                                        <option>Temps</option>
                                                        <option>Dossier</option>
                                                      </select>
                                                      <div class="select__arrow"></div>
                                                    </div>
                                                </div>
                                                <div class="col-xs-1 col-md-1 pull-right"> 
                                                    <a href="#" class="btn btn-default btn-sm btn-responsive" role="button"> </a>
                                                </div>
                                                <div class="col-xs-1 col-md-1 pull-right"> 
                                                    <a href="#" class="btn btn-success btn-sm btn-responsive" role="button"> </a>
                                                </div>
                                                <div class="col-xs-1 col-md-1 pull-right"> 
                                                    <a href="#" class="btn btn-danger btn-sm btn-responsive" role="button"> </a>
                                                </div>
                                            </div>
                                            <!-- treeview of notifications -->
                                            <div id="jstree">
                                                <!-- in this example the tree is populated from inline HTML -->
                                                <!--<ul>
                                                  <li >Root node 1
                                                    <ul>
                                                      <li id="child_node_1" type="demo">Child node 1</li>
                                                      <li id="D123" type="foldernotifs">Child node 2</li>
                                                    </ul>
                                                  </li>
                                                  <li>Root node 2</li>
                                                </ul>

                                                <button id="btntree">demo button</button>-->
                                              </div>
                                              <div id="jstreefld2">
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
    
    <!--<script src="{{  URL::asset('public/js/jquery-ui/jquery.ui.min.js') }}" type="text/javascript"></script>-->

    <script src="{{  URL::asset('public/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{  URL::asset('public/js/custom_js/app.js') }}" type="text/javascript"></script>
    <script src="{{  URL::asset('public/js/custom_js/metisMenu.js') }}" type="text/javascript"></script>
    <script src="{{  URL::asset('public/js/jquery-slimscroll/js/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
    <script src="{{  URL::asset('public/js/custom_js/rightside_bar.js') }}" type="text/javascript"></script>
    <script src="{{  URL::asset('public/js/custom_js/fixed_layout.js') }}" type="text/javascript"></script>
    <script src="{{  URL::asset('public/js/custom_js/owl.carousel.min.js') }}" type="text/javascript"></script>
    <script src="{{  URL::asset('public/js/custom_js/datetime.js') }}" type="text/javascript"></script>
    <script src="{{  URL::asset('public/js/jstree/jstree.min.js') }}" type="text/javascript"></script>

    <script type="text/javascript">
        $('.menu-icon').bind('click', function() {
          $(this).toggleClass('active');
          $(this).find('div').removeClass('no-animation');
        });
    </script>
    <!-- end of page level js -->
    <!-- begin page level js -->
    @yield('footer_scripts')
   <!--<script src="{{  URL::asset('public/js/nestable-list/jquery.nestable.js') }}"></script>-->
   <!--<script src="{{ asset('assets/js/custom_js/nestable.js') }}" type="text/javascript"></script>-->
   <script type="text/javascript">$(function() {
        /*$('#nestable_list_3').nestable();*/
        $('.owl-carousel').owlCarousel({
          loop: true,
          margin: 20,
          nav: true,
          navText: [
            "<i class='fa fa-caret-left'></i>",
            "<i class='fa fa-caret-right'></i>"
          ],
          autoplay: true,
          autoplayHoverPause: true,
          responsive: {
            0: {
              items: 1
            },
            1440: {
              items: 7
            },
            1700: {
              items: 10
            }
          }
        })

        $.jstree.defaults.core.themes.variant = "large";
         // 6 create an instance when the DOM is ready
        /*$("#jstree").jstree({
            "types" : {
              "default" : {
                "icon" : "glyphicon glyphicon-flash"
              },
              "foldernotifs" : {
                "icon" : "glyphicon glyphicon-ok"
              }
            },
            "plugins" : [ "types" ]
          });

        // 7 bind to events triggered on the tree
        $('#jstree').on("changed.jstree", function (e, data) {
          console.log(data.selected);
        });

        // 8 interact with the tree - either way is OK
        $('#btntree').on('click', function () {
          $('#jstree').jstree(true).select_node('child_node_1');
          $('#jstree').jstree('select_node', 'child_node_1');
          $.jstree.reference('#jstree').select_node('child_node_1');
        });*/
        $('#jstree').jstree({ 
            'core' : {
                'check_callback' : true,
                'data' : [
                   { "id" : "id-a1", "parent" : "#", "text" : "<b>19N00082</b> (BOUFALGA Kameleddine)", "type" : "default" },
                   { "id" : "id-aa2", "parent" : "id-a1", "text" : "<span class='cutlongtext'><b>EMAIL</b> V/Réf(Y/Ref): PE281 - N/Réf(O/Ref): 19N00082</span>", "type" : "tremail" },
                   { "id" : "id-aa3", "parent" : "id-a1", "text" : "Node A3", "type" : "trfax"}
                ]
            },
            "types" : {
                "default" : { "icon" : "glyphicon glyphicon-folder-open" },
                "tremail" : { "icon" : "menu-icon fa fa-fw fa-envelope" },
                "trfax" : { "icon" : "glyphicon glyphicon-print" }
            },
            "plugins" : ["dnd", "types"]
        });

        $('#jstreefld2').jstree({ 
            'core' : {
                'check_callback' : true,
                'data' : [
                   { "id" : "id-f2a1", "parent" : "#", "text" : "<b>19N00276</b> (TISSIER Marc)", "type" : "default" },
                   { "id" : "id-f2aa2", "parent" : "id-f2a1", "text" : "<b>EMAIL</b> V/Réf(Y/Ref): PE281 - N/Réf(O/Ref): 19N00082", "type" : "tremail" },
                   { "id" : "id-f2aa3", "parent" : "id-f2a1", "text" : "<b>EMAIL</b> V/Réf(Y/Ref): PE281 - N/Réf(O/Ref): 19N00082", "type" : "tremail"}
                ]
            },
            "types" : {
                "default" : { "icon" : "glyphicon glyphicon-folder-open" },
                "tremail" : { "icon" : "menu-icon fa fa-fw fa-envelope" },
                "trfax" : { "icon" : "glyphicon glyphicon-print" }
            },
            "plugins" : ["dnd", "types"]
        });
        
    });</script>
    <!-- end page level js -->
</body>
</html>