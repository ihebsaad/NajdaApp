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
<script src="{{ asset('public/js/push.min.js') }}"></script>
<script src="{{ asset('public/js/serviceWorker.min.js') }}"></script>

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
                "trfax" : { "icon" : "glyphicon glyphicon-print" },
                "trtel" : { "icon" : "menu-icon fa fa-fw fa-phone" },
                "trsms" : { "icon" : "menu-icon fas fa-sms" },
                "trwp" : { "icon" : "menu-icon fab fa-whatsapp" },
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
    /*$( "#dpause" ).click(function() {
      $('#jstree').jstree().create_node("#prt_19N00276" ,  { "id" : "ajson5", "text" : "notif (new)" , "type" : "tremail"}, "last", function(){
        $("#ajson5").css("background-color", "red");
      });
    });*/
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
/*var jsnt = '{"data":{"entree":{"sujet":"blabla", "type":"email", "id":"identree"}},"id":"idnotif","type": "typenotif"}';
var parsed = JSON.parse(jsnt);
alert(parsed['data']['entree']['type']);
console.log(parsed);*/

   // var userId = $('meta[name="userId"]').attr('content')
   Echo.private('App.User.{{Auth::id()}}').notification(  (notification) => {
        
        // notification desktop

            Push.config({
            serviceWorker: "{{ asset('public/js/serviceWorker.min.js') }}", // Sets a                      custom service worker script
              fallback: function(payload) {
            // Code that executes on browsers with no notification support
             // "payload" is an object containing the 
            // title, body, tag, and icon of the notification 
             }
           });

         Push.create("Notification Nejda", {

          body: "Nouvelle Notification",
          icon: "{{ asset('public/img/nejda.jpg') }}",
          timeout: 5000,
       
          onClick: function(){
          window.focus();
          this.close();
         }
         
        });

        // extraction du contenu de la notification en format json
        var jsnt = JSON.stringify(notification);
        var parsed = JSON.parse(jsnt);
        //alert("l'ID: "+parsed['data']['entree']['id']+" le sujet: "+parsed['data']['entree']['sujet']);
        // verifier si la notification est dispatche
        if ((typeof parsed['data']['entree']['dossier'] !== "undefined") && (parsed['data']['entree']['dossier'] !== null))
        {
          // verifier si le dossier exist dans la liste des notifications
          if( $("#prt_"+parsed['data']['entree']['dossier']).length ) 
          {
            // ajout nouvelle notification sous son dossier
            $('#jstree').jstree().create_node("#prt_"+parsed['data']['entree']['dossier'] ,  { "id" : parsed['data']['entree']['id'], "text" :parsed['data']['entree']['type'] +" || "+parsed['data']['entree']['sujet'] , "type" : parsed['data']['entree']['type']}, "inside", function(){
            });

          }
        }
        else
        {  
          // ajout de la nouvelle node (notification non dispatche)
          $('#jstree').jstree().create_node("#" ,  { "id" : parsed['data']['entree']['id'], "text" :parsed['data']['entree']['type'] +" || "+parsed['data']['entree']['sujet'] , "type" : parsed['data']['entree']['type']}, "first", function(){
            //$("#"+parsed['data']['entree']['id']).css("background-color", "red");
            $( "#"+parsed['data']['entree']['id'] ).animate({
              opacity: 0.25,
              left: "+=50",
              height: "toggle"
            }, 5000, function() {
              // Animation complete.
            });
            //$('#jstree').jstree('select_node', parsed['data']['entree']['id']);
          });
        }

        });

        
  </script>
