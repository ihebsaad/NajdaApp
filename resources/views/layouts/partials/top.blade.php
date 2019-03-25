<div class="collapse bg-grey" id="navbarHeader">
         <div class="container">
               <div class="row">
                Menu ici
                </div>
    </div></div><div class="navbar "><div class="row"><div class="col-sm-2 col-md-2 col-lg-2" style="margin-top:-10px;line-height:0.9em;text-align:center;padding-left:15px;font-weight:bold;"><span style="font-size:12px;margin-bottom:5px;"><?php echo date("l"); ?></span><br><span class="dot" style="font-size:18px"><?php echo date("j"); ?></span><br><span style="font-size:12px"><?php echo date("F"); ?></span></div><div style="float:left;" class="col-sm-2 col-md-2 col-lg-2"><span id="myclock">time</span></div><div class="col-sm-4 col-md-4 col-lg-4"><!--<div class="d-flex justify-content-center h-100">-->
       <div class="searchbar">
               <input type="text" name="country_name" id="country_name" class="search_input " placeholder="Rechercher .." /><a href="##" class="search_icon"><i class="fa fa-search"></i></a></div>
                <!--</div>-->
           <div id="countryList">
                </div>

           {{ csrf_field() }}
        </div><div class="col-sm-2 col-md-2 col-lg-2 "><span id="test"><a href="#">pause</a></span></div><div class="col-sm-1 col-md-1 col-lg-1"></div><div class="col-sm-1 col-md-1 col-lg-1"><img class="menu-trigger" src="{{ URL::asset('resources/assets/img/menu-black.png') }}" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation"/></div>     
      </div></div>

<style>

    #countryList ul li{
        min-width:450px;
    }

    .searchbar{
        color:white;
        margin-bottom: auto;
        margin-top: auto;
        height: 60px;
        background-color: #353b48;
        border-radius: 30px;
        padding: 10px;
        cursor: pointer;
        max-width:500px;
    }

    .search_input{
        color: white;
        border: 0;
        outline: 0;
        background: none;
        /*width: 0;*/
        caret-color:transparent;
        line-height: 40px;
        /*transition: width 0.4s linear;*/
        cursor: pointer;
        padding-left:8px;

    }

    .searchbar:hover > .search_input{
       /* padding: 0 10px;*/
        width: 400px;
        caret-color:white;
        transition: width 0.4s linear;
    }

    .searchbar:hover > .search_icon{
        background: white;
        color: #e74c3c;
    }

    .search_icon{
        height: 40px;
        width: 40px;
        float: right;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 50%;
        color:white;
    }

</style>

<!-- include alertify css -->

<link rel="stylesheet" href="{{ URL::asset('resources/assets/css/alertify.css') }}">
<link rel="stylesheet" href="{{ URL::asset('resources/assets/css/alertify-bootstrap.css') }}">

<!-- include alertify script -->
<script src="{{ URL::asset('resources/assets/js/alertify.js') }}"></script>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>


<?php $urlapp=env('APP_URL');
$urlnotif=$urlapp.'/entrees/show/' ;
$urlnotif = preg_replace('/\s+/', '', $urlnotif);
?>
<script>

    $(document).ready(function(){


        $('#country_name').keyup(function(){
            var query = $(this).val();
            if(query != '')
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('demo.fetch') }}",
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

        $('#country_name').change(function() {
            var query = $(this).val();
            if(query != '')
            {                $('#countryList').fadeOut();
            }
        });
        $(document).on('click', 'li .search', function(){
            $('#country_name').val($(this).text());
            $('#countryList').fadeOut();
        });

        /**** TIME ****/
        var start = new Date;
        setInterval(function() {
            $('.Timer').text((new Date - start) / 1000 + " Seconds");
        }, 1000);


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
                         window.showAlert = function(){
                             //       alertify.alert('<a href="<?php // print $urlnotif;?>'+id+'">Voir notification</a>');
                                    alertify.alert('<a href="http://localhost/najdaapp/entrees/show/'+id+'">Voir notification</a>');

                        }

//works with modeless too
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

        checkemails();
        dispatch();


    });
</script>

<script language="javascript">

    var clock = 0;
    var interval_msec = 1000;

    // ready

    $(function() {
        // set timer
        clock = setTimeout("UpdateClock()", interval_msec);
    });

    // UpdateClock
    function UpdateClock(){

        // clear timer
        clearTimeout(clock);

        var dt_now = new Date();
        var hh	= dt_now.getHours();
        var mm	= dt_now.getMinutes();
        var ss	= dt_now.getSeconds();

        if(hh < 10){
            hh = "0" + hh;
        }
        if(mm < 10){
            mm = "0" + mm;
        }
        if(ss < 10){
            ss = "0" + ss;
        }
        $("#myclock").html( hh + ":" + mm );

        // set timer
        clock = setTimeout("UpdateClock()", interval_msec);

    }
</script>

<style type="text/css">
    #myclock{
        font-size:30px;
        color:#ffffff;
        background-color: black;
        font-weight: 700;
        letter-spacing: 2px;
        border:1px solid #ffffff;
        padding:6px 12px 6px 12px;
    }
</style>

