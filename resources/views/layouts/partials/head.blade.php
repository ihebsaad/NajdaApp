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
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
      <!-- CSRF Token -->
    
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
    <?php
    $urlapp=env('APP_URL');

    if (App::environment('local')) {
        // The environment is local
        $urlapp='http://localhost/najdaapp';
    }
$urlnotif=$urlapp.'/entrees/show/' ;
//$urlnotif = preg_replace('/\s+/', '', $urlnotif);
?>

<script>

    $(document).ready(function(){


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

        /**** TIME ****/
    /*    var start = new Date;
        setInterval(function() {
            $('.Timer').text((new Date - start) / 1000 + " Seconds");
        }, 1000);
*/

        function checkemails(){
            $.ajax({
                type: "get",
                url: "<?php echo $urlapp; ?>/emails/check",
                success:function(data)
                {
                    //console.log the response
                    console.log(data);
                    var id=parseInt((data));
                    if (id>0)
                    {
                  //    $('#jstree').jstree().create_node("<?php //  print $urlnotif;?>"+id+"" ,  { "id" : "ajson5", "text" : ""+id+" (new)" }, "last", function(){
                       // $(this).css("background-color", "red");
                    // });

                        window.showAlert = function(){
                                   alertify.alert('<a href="<?php  print $urlnotif;?>'+id+'">Voir notification</a>');
                          //  alertify.alert('<a href="http://localhost/najdaapp/entrees/show/'+id+'">Voir notification</a>');

                        }

                        alertify.alert().setting('modal', false).set({'label': 'voir ultérieurement!'}).setHeader('<em> Nouvelle Notification</em> '); ; ;
                        window.showAlert();


                    }
                    //Send another request in 10 seconds.
                    setTimeout(function(){
                        checkemails();
                    }, 30000);  //30 secds
                }
            });
        }

        function dispatch(){
            $.ajax({
                type: "get",
                url: "<?php echo $urlapp; ?>/emails/disp",
                success:function(data)
                {
                    //console.log the response
                    console.log(data);
                    //Send another request in 10 seconds.
                    setTimeout(function(){
                        dispatch();
                    }, 60000);
                }
            });
        }

                function dispatchnow(){
            $.ajax({
                type: "get",
                url: "<?php echo $urlapp; ?>/emails/disp",
                success:function(data)
                {
                    //console.log the response
                    console.log(data);
            
                }
            });
        }

        checkemails();
       // dispatch();


    });
</script>