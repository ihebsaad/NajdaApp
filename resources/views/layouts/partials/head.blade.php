 <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="eSolutions">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="userId" content="{{ Auth::check() ? Auth::user()->id : '' }}">
 <link rel="shortcut icon" href="{{ asset('public/img/favicon.ico') }}" type="image/x-icon">
 <link rel="icon" href="{{ asset('public/img/favicon.ico') }}" type="image/x-icon">
    <meta charset="UTF-8">
    <title>
        @section('title')
            | Najda Assistance
        @show
    </title>
 <style>
     #swal2-content{font-size:15px}
     </style>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
      <!-- CSRF Token -->
 {{ csrf_field() }}

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

 <link href="{{ URL::asset('public/css/datepicker.css') }}" rel="stylesheet">

 <!-- end of global css -->
 <link rel="stylesheet" href="{{ URL::asset('resources/assets/css/styles.css') }}" />
 <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

 <!--page level css-->
    @yield('header_styles')

    <!-- include alertify css -->

<link rel="stylesheet" href="{{ URL::asset('resources/assets/css/alertify.css') }}">
<link rel="stylesheet" href="{{ URL::asset('resources/assets/css/alertify-bootstrap.css') }}">

<!-- include alertify script -->
<script src="{{ URL::asset('resources/assets/js/alertify.js') }}"></script>

<!--<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>-->
<script src="{{  URL::asset('public/js/jquery-1.11.1.min.js') }}" type="text/javascript"></script>
<script src="{{  URL::asset('public/js/app.js') }}" type="text/javascript"></script>

 <script src="https://cdnjs.cloudflare.com/ajax/libs/push.js/1.0.5/push.js"></script>

 <link href="{{ asset('public/js/select2/css/select2.css') }}" rel="stylesheet" type="text/css"/>
 <link href="{{ asset('public/js/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>


 <?php
$urlapp="http://$_SERVER[HTTP_HOST]/najdaapp";
$urlnotif=$urlapp.'/entrees/show/' ;
//$urlnotif = preg_replace('/\s+/', '', $urlnotif);
?>
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@8/dist/sweetalert2.min.css" />
<script>

    $(document).ready(function(){

        $('#countnotif').html('500');

        $('#enpause').click(function() {

            $("#dpause").css("display", "block");
            $("#enpause").css("display", "none");
            var _token = $('input[name="_token"]').val();
            // back statut en ligne
            $.ajax({
                url:"{{ route('users.changestatut') }}",
                method:"POST",
                data:{ _token:_token},
                success:function(data){

                }
            });
        });


        $('#search-bar').keyup(function(){
            var query = $(this).val();
            if(query != '')
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('home.fetch') }}",
                    method:"POST",
                    data:{query:query, _token:_token},
                    success:function(data){
                        $('#countryList').fadeIn();
                        $('#countryList').html(data);
                    }
                });
            }else{
                $('#countryList').fadeOut();
            }
        });

        $('#search-bar').change(function() {
            var query = $(this).val();
            if(query != '')
            {                $('#countryList').fadeOut();
            }
        });
        $(document).on('click', 'li .search', function(){
            $('#search-bar').val($(this).text());
            $('#countryList').fadeOut();
        });


        function checksms(){
            $.ajax({
                type: "get",
                url: "<?php echo $urlapp; ?>/emails/checksms",
                success:function(data)
                {
                    //console.log the response
                    console.log('check boite SMS : '+data);
                    //Send another request in n seconds.
                    setTimeout(function(){
                        checksms();
                    }, 30000);  //30 secds
                }
            });
        }

        function checkemails(){
            $.ajax({
                type: "get",
                url: "<?php echo $urlapp; ?>/emails/check",
                success:function(data)
                {
                    //console.log the response
                    console.log('check boite 1 :'+data);

                    if(parseInt(data)>1)
                    {
                   /*    Push.create("Nouvelle Notification", {

                            body: 'Nouvelle Notification',
                            icon: "{{-- asset('public/img/najda.png') --}}",
                            timeout: 5000,

                            onClick: function(){
                                // window.focus();
                                // this.close();
                               // window.location ='<?php // echo $urlapp; ?>/entrees/show/'+parsed['data']['entree']['id'];

                            }

                        });
                        */
                    }
                    //Send another request in n seconds.
                    setTimeout(function(){
                        checkemails();
                    }, 30000);  //30 secds
                }
            });


        }

        function checkemails2(){
            $.ajax({
                type: "get",
                url: "<?php echo $urlapp; ?>/emails/checkboite2",
                success:function(data)
                {
                    //console.log the response
                    console.log('check boite 2 : '+data);
                    //Send another request in n seconds.
                    setTimeout(function(){
                        checkemails2();
                    }, 30000);  //30 secds
                }
            });
        }

        function checkemails3(){
            $.ajax({
                type: "get",
                url: "<?php echo $urlapp; ?>/emails/checkboite2",
                success:function(data)
                {
                    //console.log the response
                    console.log('check boite 2 : '+data);
                    //Send another request in n seconds.
                    setTimeout(function(){
                        checkemails3();
                    }, 30000);  //30 secds
                }
            });
        }


        function checkemails4(){
            $.ajax({
                type: "get",
                url: "<?php echo $urlapp; ?>/emails/checkboite2",
                success:function(data)
                {
                    //console.log the response
                    console.log('check boite 2 : '+data);
                    //Send another request in n seconds.
                    setTimeout(function(){
                        checkemails3();
                    }, 30000);  //30 secds
                }
            });
        }


        function checkfax(){
            $.ajax({
                type: "get",
                url: "<?php echo $urlapp; ?>/emails/checkfax",
                success:function(data)
                {
                    //console.log the response
                    console.log('check boite fax : '+data);
                    //Send another request in n seconds.
                    setTimeout(function(){
                        checkfax();
                    }, 60000);  //60 secds
                }
            });
        }


        function checkboite(){
            $.ajax({
                type: "get",
                url: "<?php echo $urlapp; ?>/emails/checkboiteperso",
                success:function(data)
                {
                    //console.log the response
                    console.log('check boite perso : '+data);
                    //Send another request in n seconds.
                    setTimeout(function(){
                        checkboite();
                    }, 1200000);  //2 mins
                }
            });
        }

        function checkdemandes(){
            $.ajax({
                type: "get",
              //  data:{nom:nom, _token:_token},
                url: "<?php echo $urlapp; ?>/checkdemandes",
                success:function(data)
                {
                    //console.log the response
                    console.log('check demandes : '+data);
                    //Send another request in n seconds.

                    if(data !='')
                    {
                        var obj = JSON.parse(data);

                        var id=obj.id ;
                        var par=obj.par ;
                        var role=obj.role ;
                         var vers=obj.vers ;
                        var emetteur=obj.emetteur ;
                        var type=obj.type ;


                        if (type=='role')
                        {
                            var message= emetteur+' a demandé de prendre le rôle : '+role;
                         const swalWithBootstrapButtons = Swal.mixin({
                            customClass: {
                                confirmButton: 'btn btn-success',
                                cancelButton: 'btn btn-danger'
                            },
                            buttonsStyling: false,
                        })

                        swalWithBootstrapButtons.fire({
                            title: 'changement de rôle',
                            text: message,
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Ok !',
                            cancelButtonText: 'Non',
                         //   reverseButtons: true
                        }).then((result) => {
                            if (result.value) {


                           // affectation du role ok =true
                            var _token = $('input[name="_token"]').val();

                             $.ajax({

                                url: "{{ route('home.affecterrole') }}",
                                method: "POST",
                                data: {id:id ,ok:1, _token: _token},
                                success: function (data) {


                                }
                            });

                        swalWithBootstrapButtons.fire(
                            'Attribué!',
                            'le rôle '+role+' n\'est plus parmi vos permissions ',
                            'success'
                        );

                    } else if (
                        // Read more about handling dismissals
                    result.dismiss === Swal.DismissReason.cancel
                    ) {
                            var _token = $('input[name="_token"]').val();

                             // ok =false
                            $.ajax({
                                url: "{{ route('home.affecterrole') }}",
                                method: "POST",
                                data: {id:id ,ok:0, _token: _token},
                                success: function (data) {


                                }
                            });


                        swalWithBootstrapButtons.fire(
                            'Non attribué',
                            'le rôle '+role+' reste parmi vos permissions ',
                            'error'
                        )

                    }
                    })
                        } // end type=role


                        if(type=='pause')
                        {
                            var duree=obj.duree ;

                            var message= emetteur+' a demandé une pause de '+duree+' minutes';
                            const swalWithBootstrapButtons = Swal.mixin({
                                customClass: {
                                    confirmButton: 'btn btn-success',
                                    cancelButton: 'btn btn-danger'
                                },
                                buttonsStyling: false,
                            })

                            swalWithBootstrapButtons.fire({
                                title: 'Demande de pause',
                                text: message,
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Ok !',
                                cancelButtonText: 'Non',
                                //   reverseButtons: true
                            }).then((result) => {
                                if (result.value) {


                            // affectation du role ok =true
                            var _token = $('input[name="_token"]').val();

                            $.ajax({

                                url: "{{ route('home.reponsepause') }}",
                                method: "POST",
                                data: {id:id ,ok:1, _token: _token},
                                success: function (data) {


                                }
                            });

                            swalWithBootstrapButtons.fire(
                                'Accepté!',
                                'Pause Acceptée ! ',
                                'success'
                            )

                        } else if (
                            // Read more about handling dismissals
                        result.dismiss === Swal.DismissReason.cancel
                        ) {
                            var _token = $('input[name="_token"]').val();

                            // ok =false
                            $.ajax({
                                url: "{{ route('home.reponsepause') }}",
                                method: "POST",
                                data: {id:id ,ok:0, _token: _token},
                                success: function (data) {


                                }
                            });


                            swalWithBootstrapButtons.fire(
                                'Réfusée',
                                'Pause réfusée ! ',
                                'error'
                            )

                        }
                        })

                        } // end type pause



                        if(type=='reponsedemande')
                        {
                          var rep=0;
                            if(role=='oui')
                            {
                                rep=1;
                                Swal.fire(
                                    'Pause accepté!',
                                    'Votre demande de pause a été acceptée',
                                    'success'
                                )
                                // here

                                $("#dpause").css("display", "none");
                                $("#enpause").css("display", "block");
                                setTimeout(function(){
                                    window.location = '{{route('pause')}}';
                                }, 3000);
                            }
                            if(role=='non')
                            {rep=0;
                                Swal.fire(
                                    'Pause réfusée!',
                                    'Votre demande de pause a été réfusée',
                                    'error'
                                )
                            }

                            // affectation du role ok =true
                            var _token = $('input[name="_token"]').val();

                            $.ajax({

                                url: "{{ route('home.removereponsepause') }}",
                                method: "POST",
                                data: {id:id ,  _token: _token},
                                success: function (data) {


                                }
                            });




                        } // end type reponsedemande






                        //////////////////////
                    }

                    setTimeout(function(){
                        checkdemandes();
                    }, 30000);  //30 secds
                }
            });
        }

<?php

?>


        function checkNotifs(){
            $.ajax({
                type: "get",
                url: "<?php echo $urlapp; ?>/notifications/checknotifs",
                success:function(data)
                {
                    //console.log the response
                    console.log('check new notif   :'+data);
               //  Data=   JSON.stringify(data);

                    if(data )
                   {

                        showNotif(data);



                    }
                    //Send another request in n seconds.
                    setTimeout(function(){
                        checkNotifs();
                    }, 30000);  //20 secds
                }
            });

        }


        checkNotifs();
        checkemails();
        checkemails2();
        checksms();
        checkboite();
        checkfax();
        checkdemandes();
    });
</script>