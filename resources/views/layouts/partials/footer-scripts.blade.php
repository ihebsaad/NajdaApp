<!-- Bootstrap core JavaScript

================================================= -->

<!-- Placed at the end of the document so the pages load faster -->


<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>-->

    
    <!--<script src="{{  URL::asset('public/js/jquery-ui/jquery.ui.min.js') }}" type="text/javascript"></script>-->

    <script src="{{  URL::asset('public/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{  URL::asset('public/js/custom_js/metisMenu.js') }}" type="text/javascript"></script>
    <script src="{{  URL::asset('public/js/jquery-slimscroll/js/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
    <script src="{{  URL::asset('public/js/custom_js/owl.carousel.min.js') }}" type="text/javascript"></script>
    <script src="{{  URL::asset('public/js/custom_js/datetime.js') }}" type="text/javascript"></script>
    <script src="{{  URL::asset('public/js/jstree/jstree.min.js') }}" type="text/javascript"></script>

<script src="{{  URL::asset('public/js/custom_js/rightside_bar.js') }}" type="text/javascript"></script>
<!--<script src="{{  URL::asset('public/js/custom_js/fixed_layout.js') }}" type="text/javascript"></script>-->

<script  src="{{ asset('public/js/summernote.min.js') }}"  type="text/javascript"></script>
<script src="{{  URL::asset('public/js/custom_js/compose.js') }}" type="text/javascript"></script>

    <script type="text/javascript">
        $('.menu-icon').bind('click', function() {
          $(this).toggleClass('active');
          $(this).find('div').removeClass('no-animation');
        });
    </script>
    <!-- end of page level js -->
    <!-- begin page level js -->
    @yield('footer_scripts')
   <script type="text/javascript">
       $(function() {
        /*$('#nestable_list_3').nestable();*/

        $('.owl-carousel').owlCarousel({
          loop: false,
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
        });
        $.jstree.defaults.core.themes.variant = "large";

        // 8 interact with the tree - either way is OK
        /*$('#btntree').on('click', function () {
          $('#jstree').jstree(true).select_node('child_node_1');
          $('#jstree').jstree('select_node', 'child_node_1');
          $.jstree.reference('#jstree').select_node('child_node_1');
        });*/
        $('#jstree').jstree({ 
            'core' : {
                'check_callback' : function (op, node, par, pos, more) {
                      if(more && more.dnd) {
                          return more.pos !== "i" && par.id == node.parent;
                      }
                      return true;
                  },
            },
            "types" : {
                "default" : { "icon" : "glyphicon glyphicon-folder-open" },
                "tremail" : { "icon" : "menu-icon fa fa-fw fa-envelope" },
                "trfax" : { "icon" : "glyphicon glyphicon-print" }
            },
            "plugins" : ["dnd", "types"]
        }).bind("select_node.jstree", function (e, data) {
          var href = data.node.a_attr.href;
          var parentId = data.node.a_attr.parent_id;
          if(href == '#')
          return '';

          window.open(href,'_self');
        });
        
    });</script>
    <!-- end page level js -->

<script>

    $( "#open" ).click(function() {
        $(".folders").css("display", "none");
     /*   $( ".folders" ).hide( "slow", function() {
            // Animation complete.
        });*/
        $( ".left" ).hide( "slow", function() {
            // Animation complete.
        });
        $( ".right" ).hide( "slow", function() {
            // Animation complete.
        });

        $( "#mainc" ).addClass( "col-lg-12" );

    });

    $( "#close" ).click(function() {
        $(".folders").css("display", "inline");

        /*
        $( ".folders" ).show( "slow", function() {
            // Animation complete.
        });*/

        $( ".left" ).show( "slow", function() {
            // Animation complete.
        });
        $( ".right" ).show( "slow", function() {
            // Animation complete.
        });
        $( "#mainc" ).removeClass( "col-lg-12" );

    });

</script>

<script>


 
   // var userId = $('meta[name="userId"]').attr('content')
   Echo.private('App.User.{{Auth::id()}}').notification((notification) => {
         // Echo.private('kbs').listen('CorrespondanceCreated',(e) => { 
            // Echo.private('kbs.{{Auth::id()}}').listen('CorrespondanceCreated',(e) => { 
        alert(JSON.stringify(notification));
            //alert ("bonjour");

           // console.log(e);
           //this.notifications = notification.data.correspondance.dossier;
          // this.notifications.push(notification.data.correspondance.dossier);

          // alert (this.notifications);
        });
  </script>
