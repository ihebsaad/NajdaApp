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
<script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/1.0.5/push.js"></script>


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
$urlapp=env('APP_URL');

if (App::environment('local')) {
    // The environment is local
    $urlapp='http://localhost/najdaapp';
}?>
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

</script>

<script>

    /*var jsnt = '{"data":{"entree":{"sujet":"blabla", "type":"email", "id":"identree"}},"id":"idnotif","type": "typenotif"}';
     var parsed = JSON.parse(jsnt);
     alert(parsed['data']['entree']['type']);
     console.log(parsed);*/

    // var userId = $('meta[name="userId"]').attr('content')
    Echo.private('App.User.{{Auth::id()}}').notification(  (notification) => {


        // extraction du contenu de la notification en format json
        var jsnt = JSON.stringify(notification);
    var parsed = JSON.parse(jsnt);
    //alert("l'ID: "+parsed['data']['entree']['id']+" le sujet: "+parsed['data']['entree']['sujet']);
    //attribuer type selon type recu
    var typee = "tremail";var img="";
    if ((typeof parsed['data']['entree']['type'] !== "undefined") && (parsed['data']['entree']['type'] !== null) && (parsed['data']['entree']['type'] !== ''))
    {
        switch (parsed['data']['entree']['type']) {
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
            case "phone":
                typee = "trsms";
                img = '<?php echo $urlapp; ?>/public/img/tel.png';

                break;

            default:
                typee = "tremail";
                img = '<?php echo $urlapp; ?>/public/img/email.png';


        }
    }
    // Vérifier le nom de la vue
    <?php if (($view_name!='entrees-dispatching')  && ($view_name!='entrees-showdisp') ) { ?>

    // verifier si la notification est dispatche
    if ((typeof parsed['data']['entree']['dossier'] !== "undefined") && (parsed['data']['entree']['dossier'] !== null))
    {
        // verifier si le dossier exist dans la liste des notifications
        if( $("#prt_"+parsed['data']['entree']['dossier']).length )
        {
            // ajout nouvelle notification sous son dossier
            $('#jstree').jstree().create_node("#prt_"+parsed['data']['entree']['dossier'] ,  { "id" : parsed['data']['entree']['id'], "text" :parsed['data']['entree']['sujet'] , "type" : typee, "a_attr":{"href":"{{ asset('entrees/show/') }}"+"/"+parsed['data']['entree']['id']}}, "inside", function(){
                // animation de nouvelle notification
                setInterval(function(){
                    $("#"+parsed['data']['entree']['id']).toggleClass("newnotif");
                },400);
                // scroll vers lemplacement de la notification
                $('#notificationstab').scrollTop(
                    $("#"+parsed['data']['entree']['id']).offset().top - $('#notificationstab').offset().top + $('#notificationstab').scrollTop()
                );
            });
            $('#jstree').bind("select_node.jstree", function (e, data) {
                var href = data.node.a_attr.href;
                document.location.href = href;
            });

        }
        else
        {
            // cas dossier nest pas dans la liste des notifications
            // ajout node dossier
            $('#jstree').jstree().create_node("#" ,  { "id" : "prt_"+parsed['data']['entree']['dossier'], "text" :parsed['data']['entree']['dossier'] , "type" : "default"}, "first", function(){
            });
            // ajout nouvelle notification sous son dossier
            $('#jstree').jstree().create_node("#prt_"+parsed['data']['entree']['dossier'] ,  { "id" : parsed['data']['entree']['id'], "text" :parsed['data']['entree']['sujet'] , "type" : typee, "a_attr":{"href":"{{ asset('entrees/show/') }}"+"/"+parsed['data']['entree']['id']}}, "inside", function(){
                //ouvrir node dossier
                $('#jstree').jstree().open_node("#prt_"+parsed['data']['entree']['dossier']);
                // animation de nouvelle notification
                setInterval(function(){
                    $("#"+parsed['data']['entree']['id']).toggleClass("newnotif");
                },400);
                // scroll vers lemplacement de la notification
                $('#notificationstab').scrollTop(
                    $("#"+parsed['data']['entree']['id']).offset().top - $('#notificationstab').offset().top + $('#notificationstab').scrollTop()
                );
            });
            $('#jstree').bind("select_node.jstree", function (e, data) {
                var href = data.node.a_attr.href;
                document.location.href = href;
            });

        }
    }
    else {
        <?php  if (Session::get('disp') != 0) { ?>

        // ajout de la nouvelle node (notification non dispatche)
        $('#jstree').jstree().create_node("#", {
            "id": parsed['data']['entree']['id'],
            "text": parsed['data']['entree']['sujet'],
            "type": typee,
            "a_attr": {"href": "{{ asset('entrees/show/') }}" + "/" + parsed['data']['entree']['id']}
        }, "first", function () {
            // animation de nouvelle notification
            setInterval(function () {
                $("#" + parsed['data']['entree']['id']).toggleClass("newnotif");
            }, 400);
            // scroll vers lemplacement de la notification
            $('#notificationstab').scrollTop(
                $("#" + parsed['data']['entree']['id']).offset().top - $('#notificationstab').offset().top + $('#notificationstab').scrollTop()
            );
        });
        $('#jstree').bind("select_node.jstree", function (e, data) {
            var href = data.node.a_attr.href;
            document.location.href = href;
        });

        <?php } ?>

    }

        <?php }else{ ?>
         $("#notifdisp").fadeOut('slow');
        $("#notifdisp").prepend("<li class='overme' style='color:white;padding-left:6px;margin-bottom:15px;background-color:#fd9883;color:white;font-weight:800;'><img width='15' src='" + img + "' /> <a  style=';font-weight:800;font-size:14px;color:white' href='<?php echo $urlapp; ?>/entrees/showdisp/" + parsed['data']['entree']['id'] + "'   ><small style='font-size:12px;color:white;'> " + parsed['data']['entree']['sujet'] + "</small></a><br>                  <label style='font-size:12px'><a style='color:white' href='<?php echo $urlapp; ?>/entrees/showdisp/" + parsed['data']['entree']['id'] + "'  >" + parsed['data']['entree']['emetteur'] + "</a></label><br><label style='font-size:12px'> " + parsed['data']['entree']['date'] + "</label></li>");
        $("#notifdisp").fadeIn('slow');

        $('#idEntreeMissionOnclik').val(parsed['data']['entree']['id']);
        alert(parsed['data']['entree']['id']);

        <?php } ?>



        // php if interface = dispatching




        // notification desktop

        Push.config({
            serviceWorker: "{{asset('public/js/serviceWorker.min.js') }}", // Sets a                      custom service worker script
            fallback: function(payload) {
                // Code that executes on browsers with no notification support
                // "payload" is an object containing the
                // title, body, tag, and icon of the notification
            }
        });

        Push.create("Nouvelle "+parsed['data']['entree']['type'], {

            body: parsed['data']['entree']['sujet'],
            icon: "{{ asset('public/img/najda.png') }}",
            timeout: 5000,

            onClick: function(){
                // window.focus();
                // this.close();
                window.location ='<?php echo $urlapp; ?>/entrees/show/'+parsed['data']['entree']['id'];

            }

        });

    });


</script>

<script>

    $('#dpause').click(function() {

        $('#modalconfirm').modal({show: true});

    });


    $('#oui').click(function() {
        $('#modalconfirm').modal('hide');

        var _token = $('input[name="_token"]').val();
        $.ajax({
            url: "{{ route('home.demandepause') }}",
            method: "POST",
            data: {   _token: _token},

            success: function (data) {
                alert('Demande envoyée');

            }
        });


    }); //end click


</script>