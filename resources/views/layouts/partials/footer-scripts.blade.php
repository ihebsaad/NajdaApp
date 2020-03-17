<!-- Bootstrap core JavaScript

================================================= -->

<!-- Placed at the end of the document so the pages load faster -->


<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>-->


<!--<script src="{{--  URL::asset('public/js/jquery-ui/jquery.ui.min.js') --}}" type="text/javascript"></script>-->
{{ csrf_field() }}

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

<!----- Datepicker ------->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!----- Push ------->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/1.0.5/push.js"></script>-->


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
            autoplay: false,
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
      //  $.jstree.defaults.core.themes.variant = "large";

        //  $('#jstree').jstree();
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

        $('#jstree').on('ready.jstree', function() {
            $("#jstree").jstree("open_all");
         });

/*
        $('#jstree').jstree({
            'core' : {
                'data' : [
                    'Simple root node',
                    {
                        'id' : 'node_2',
                        'text' : 'Root node with options',
                        'state' : { 'opened' : true, 'selected' : true },
                        'children' : [ { 'text' : 'Child 1' }, 'Child 2']
                    }
                ]
            }
        });
*/

        $( ".datepicker-default" ).datepicker({

            altField: "#datepicker",
            closeText: 'Fermer',
            prevText: 'Précédent',
            nextText: 'Suivant',
            currentText: 'Aujourd\'hui',
            monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
            dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
            dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
            dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
            weekHeader: 'Sem.',
            buttonImage: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABEAAAATCAYAAAB2pebxAAABGUlEQVQ4jc2UP06EQBjFfyCN3ZR2yxHwBGBCYUIhN1hqGrWj03KsiM3Y7p7AI8CeQI/ATbBgiE+gMlvsS8jM+97jy5s/mQCFszFQAQN1c2AJZzMgA3rqpgcYx5FQDAb4Ah6AFmdfNxp0QAp0OJvMUii2BDDUzS3w7s2KOcGd5+UsRDhbAo+AWfyU4GwnPAYG4XucTYOPt1PkG2SsYTbq2iT2X3ZFkVeeTChyA9wDN5uNi/x62TzaMD5t1DTdy7rsbPfnJNan0i24ejOcHUPOgLM0CSTuyY+pzAH2wFG46jugupw9mZczSORl/BZ4Fq56ArTzPYn5vUA6h/XNVX03DZe0J59Maxsk7iCeBPgWrroB4sA/LiX/R/8DOHhi5y8Apx4AAAAASUVORK5CYII=",

            firstDay: 1,
            dateFormat: "dd/mm/yy"

        });




    });</script>
<!-- end page level js -->
<?php
  $param= App\Parametre::find(1);$env=$param->env;
$urlapp="http://$_SERVER[HTTP_HOST]/".$env;

?>


<script>



$( "#open" ).click(function() {
        $(".folders").css("display", "none");
        $(".news").css("display", "none");
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
        $(".news").css("display", "inline");

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


     // var userId = $('meta[name="userId"]').attr('content')
   ////////////// Echo.private('App.User.{{Auth::id()}}').notification(  (notification) => {
//alert("tessty");
  function showNotif(notification) {
         // extraction du contenu de la notification en format json
       //  var jsnt = JSON.stringify(notification);
         var jsnt = notification;
 
      ////   var parsed = JSON.parse(jsnt);

        //// var parseddata = JSON.parse(parsed['data']);
      console.log(notification);

         //alert("l'ID: "+parseddata['Entree']['id']+" le sujet: "+parseddata['Entree']['sujet']);
         //attribuer type selon type recu
         var typee = "tremail";
         var img = "";
         if ((typeof jsnt.type !== "undefined") && (jsnt.type !== null) && (jsnt.type !== '')) {
             switch ( jsnt.type) {
                 case "email":
                     typee = "tremail";
                     img = '<?php echo $urlapp; ?>/public/img/email.png';

                     break;
                 case "fax":
                     typee = "trfax";
                     img = '<?php echo $urlapp; ?>/public/img/faxx.png';

                     break;
                 case "sms":
                     typee = "trsms";
                     img = '<?php echo $urlapp; ?>/public/img/smss.png';

                     break;
                 case "tel":
                     typee = "trtel";
                     img = '<?php echo $urlapp; ?>/public/img/tel.png';

                     break;

                 default:
                     typee = "tremail";
                     img = '<?php echo $urlapp; ?>/public/img/email.png';


             }
         }
         // Vérifier le nom de la vue
         <?php if (($view_name != 'entrees-dispatching') && ($view_name != 'entrees-showdisp') ) { ?>

         <?php  if (($view_name != 'supervision') && ($view_name != 'affectation') && ($view_name != 'notifs') && ($view_name != 'missions')  && ($view_name != 'transport')  && ($view_name != 'transportsemaine')  && ($view_name != 'dossiers-create')   ) { ?>


         // verifier si la notification est dispatche
         if ((typeof jsnt.dossierid !== "undefined") && (jsnt.dossierid!== null)&& (jsnt.dossierid !== '')) {
             // verifier si le dossier exist dans la liste des notifications
              if ($("#prt_" + jsnt.dossierid).length) {

                 var reception= jsnt.reception;
                 var emetteur= jsnt.emetteur ;
                var heure=reception.toString().slice(11,16);
                 // ajout nouvelle notification sous son dossier
                 $('#jstree').jstree().create_node("#prt_" +  jsnt.dossierid, {
                     "id":   'entree_'+jsnt.entree,
                     "text": heure+' '+emetteur+' '+ jsnt.sujet,
                     "type": typee,
                     "a_attr": {"href": "{{ asset('entrees/show/') }}" + "/" +  jsnt.entree}
                 }, "inside", function () {
                     // animation de nouvelle notification
                     setInterval(function () {
                         $("#" +  'entree_'+jsnt.entree).toggleClass("newnotif");
                     }, 400);


                 });
                 $('#jstree').bind("select_node.jstree", function (e, data) {
                     var href = data.node.a_attr.href;
                     document.location.href = href;
                 });

                  $('#jstree').jstree("move_node", "#prt_"+jsnt.dossierid, "#notifcs", 0);

                  /********
                 Push.create("Nouvelle Notification", {

                     body:  jsnt.sujet,
                     icon: "{{ asset('public/img/najda.png') }}",
                     timeout: 8000,

                     onClick: function(){
                         // window.focus();
                         // this.close();
                         window.location ='<?php   echo $urlapp; ?>/entrees/show/'+jsnt.entree;

                     }

                 });
*/
             }
             else {
                 // cas dossier nest pas dans la liste des notifications
                 // ajout node dossier
                $('#jstree').jstree().create_node("#", {
              //    $('#jstree').jstree().create_node("#prt_" +  jsnt.dossierid, {
                     "id": "prt_" + jsnt.dossierid,
                     "text": jsnt.refdossier +' | '+jsnt.nomassure,
                     "type": "default",
                     "a_attr": {"href": "{{ asset('dossiers/view/') }}" + "/" +jsnt.dossierid}
                 }, "first", function () {
                 });
                   var reception= jsnt.reception;
                  var emetteur= jsnt.emetteur ;
                  var heure=reception.toString().slice(11,16);

                 // ajout nouvelle notification sous son dossier
                  $('#jstree').jstree().create_node("#prt_" +  jsnt.dossierid, {
                      "id":  'entree_'+jsnt.entree,
                      "text": heure+' '+emetteur+' '+ jsnt.sujet,
                      "type": typee,
                      "a_attr": {"href": "{{ asset('entrees/show/') }}" + "/" +  jsnt.entree}
                  }, "inside", function () {
                     //ouvrir node dossier
                     $('#jstree').jstree().open_node("#prt_" + jsnt.dossierid);
                     // animation de nouvelle notification
                     setInterval(function () {
                         $("#" +  'entree_'+jsnt.entree).toggleClass("newnotif");
                     }, 400);


                 });
                 $('#jstree').bind("select_node.jstree", function (e, data) {
                     var href = data.node.a_attr.href;
                     document.location.href = href;
                 });

                  $('#jstree').jstree("move_node", "#prt_"+jsnt.dossierid, "#notifcs", 0);


                  /*****       Push.create("New Notification", {

                     body:  jsnt.emetteur +' '+ jsnt.sujet,
                     icon: "{{ asset('public/img/najda.png') }}",
                     timeout: 8000,

                     onClick: function(){
                         // window.focus();
                         // this.close();
                         window.location ='<?php   echo $urlapp; ?>/entrees/show/'+ jsnt.entree;

                     }

                 });

*/
             }
         }
         else {
             <?php  if (Session::get('disp') != 0) {   ?>

             var reception= jsnt.reception;
              var heure=reception.toString().slice(11,16);

             // ajout de la nouvelle node (notification non dispatche)
         /*    $('#jstree').jstree().create_node("#", {
                 "id": parseddata['Entree']['id'],
                 "text":heure+' '+parseddata['Entree']['sujet'],
                 "type": typee,
                 "a_attr": {"href": "{{-- asset('entrees/showdisp/') --}}" + "/" + parseddata['Entree']['id']}
             }, "first", function () {
                 // animation de nouvelle notification
                 setInterval(function () {
                     $("#" + parseddata['Entree']['id']).toggleClass("newnotif");
                 }, 400);
                 // scroll vers lemplacement de la notification
                 $('#notificationstab').scrollTop(
                     $("#" + parseddata['Entree']['id']).offset().top - $('#notificationstab').offset().top + $('#notificationstab').scrollTop()
                 );
             });
             $('#jstree').bind("select_node.jstree", function (e, data) {
                 var href = data.node.a_attr.href;
                 document.location.href = href;
             });

*/

             /****
           
              Push.create("Nouvelle Notification", {

              body: jsnt.emetteur+' '+jsnt.sujet,
              icon: "{{ asset('public/img/najda.png') }}",
              timeout: 15000,

              onClick: function(){

              window.location ='<?php   echo $urlapp; ?>/entrees/showdisp/'+jsnt.entree;

              }

              });
 */


             <?php   } ?>

         }

         <?php } // end supervision and admin views  ?>
         <?php }else{ ?>
          $("#notifdisp").fadeOut('slow');
         $("#notifdisp").prepend("<li class='overme' style='color:white;padding-left:6px;margin-bottom:15px;background-color:#fd9883;color:white;font-weight:800;'><img width='15' src='" + img + "' /> <a  style=';font-weight:800;font-size:14px;color:white' href='<?php echo $urlapp; ?>/entrees/showdisp/" + jsnt.entree + "'   ><small style='font-size:12px;color:white;'> " +jsnt.sujet + "</small></a><br>                  <label style='font-size:12px'><a style='color:white' href='<?php echo $urlapp; ?>/entrees/showdisp/" + jsnt.entree + "'  >" + jsnt.emetteur+ "</a></label><br><label style='font-size:12px'> " + ' Maintenant ' + "</label></li>");
         $("#notifdisp").fadeIn('slow');

         $('#idEntreeMissionOnclik').val(jsnt.entree);


         <?php } ?>



         // php if interface = dispatching


         // notification desktop

         Push.config({
             serviceWorker: "{{asset('public/js/serviceWorker.min.js') }}", // Sets a                      custom service worker script
             fallback: function (payload) {
                 // Code that executes on browsers with no notification support
                 // "payload" is an object containing the
                 // title, body, tag, and icon of the notification
             }
         });



         /////// });


     }

</script>

