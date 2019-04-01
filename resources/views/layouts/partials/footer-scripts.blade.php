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
   <!--<script src="{{  URL::asset('public/js/nestable-list/jquery.nestable.js') }}"></script>-->
   <!--<script src="{{ asset('assets/js/custom_js/nestable.js') }}" type="text/javascript"></script>-->
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