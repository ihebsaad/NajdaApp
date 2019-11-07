<header class="header">
<?php
    use App\Entree;
    $seance =  DB::table('seance')
        ->where('id','=', 1 )->first();
    $user = auth()->user();
    $iduser=$user->id;

    ?>
        <div class="collapse bg-grey" id="navbarHeader">
             @include('layouts.partials._top_menu')

        </div>

    <div class="navbar ">
      <div class="row">
        <div class="col-sm-1 col-md-1 col-lg-1" style="margin-right:60px">
           <a href="{{ route('home')}}" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                <img style="margin-left:-60px;" src="{{  URL::asset('public/img/logo.png') }}" alt="logo" />
            </a>
        </div>
          <div style="min-width:100px!important;padding-top:15px;padding-left:0px!important" class="col-sm-1 col-md-1 col-lg-1">
              <p id="njour" style="font-size: 25px; margin-bottom: 0px!important;color: white"></p>
              <div class="time">
                  time</div>
          </div>
        <div class="col-sm-1 col-md-1 col-lg-1" style="padding-top: 5px;">
          <span  id="ndate" class="date" data-month="" data-year="" style="width:70px;height:60px;line-height: 1; padding-top: 15px;">
                    <span id="numj">13</span>
           </span>
        </div>



      @can('isAdmin')

          <div  class="col-sm-1 col-md-1 col-lg-1 " style="padding-top:10px;padding-left:0px!important">
          <a href="{{ route('parametres') }}" class="btn btn-default btn-lg btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Demander pause de ton superviseur" style="margin-bottom: 28px!important;">
                                                <i class="fas fa-user-tie"></i>
                                                <br>
                                                Admin
          </a> 
        </div>
          @endcan


          @cannot('isAdmin')
              <?php
              $statut=$user->statut;
              if( ($seance->superviseurmedic!=$iduser)  && ($seance->superviseurtech!=$iduser))
              {
              if($statut==2) { ?>
              <div  id="pause" class="col-sm-1 col-md-1 col-lg-1 " style="padding-top:10px;padding-left:0px!important">


                  <a href="#"    id="enpause" class="btn btn-danger btn-lg btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Demander pause de ton superviseur" style="margin-bottom: 28px!important;">
                      <span class="fas fa-mug-hot"></span>
                      <br>
                      En Pause
                  </a>

                  <a href="#"  style="display:none" id="dpause" class="btn btn-default btn-lg btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Demander pause de ton superviseur" style="margin-bottom: 28px!important;">
                      <span class="fa fa-fw fa-pause"></span>
                      <br>
                      Pause
                  </a>
              </div>

                  <?php }else{  ?>
                  <div  id="pause" class="col-sm-1 col-md-1 col-lg-1 " style="padding-top:10px;padding-left:0px!important">

                  <a href="#"  id="dpause" class="btn btn-default btn-lg btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Demander pause de ton superviseur" style="margin-bottom: 28px!important;">
                      <span class="fa fa-fw fa-pause"></span>
                      <br>
                      Pause
                  </a>

                  <a href="#"    style="display:none"  id="enpause" class="btn btn-danger btn-lg btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Demander pause de ton superviseur" style="margin-bottom: 28px!important;">
                      <span class="fas fa-mug-hot"></span>
                      <br>
                      En Pause
                  </a>

                  </div>

                  <?php }
                  } // sup
                  ?>
          @endcannot

          <?php
          if( ($seance->superviseurmedic==$iduser)  || ($seance->superviseurtech==$iduser) ||($user->user_type=='admin'))
          { ?>
          <div class="col-sm-1 col-md-1 col-lg-1" style="padding-top:10px;margin-left:15px">
              <a  href="{{ route('supervision') }}" class="btn btn-default btn-lg btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Demander pause de ton superviseur" style="margin-bottom: 28px!important;">
                  <span class="fas fa-fw fa-users-cog"></span>
                  <br>
                  Supervision
              </a>
          </div>
        <?php } ?>

          <div class="col-sm-1 col-md-1 col-lg-2" style=" height: 40px!important;padding-top:27px;padding-left:0px ">
          <form class="search-container" action="{{route('RechercheMulti.test')}}" id="testRecheche" method="POST">
            <input type="text" id="search-bar"  placeholder="Recherche" autocomplete="off" name="qy">
            <a href="#" onclick='document.getElementById("testRecheche").submit()'><img class="search-icon" src="{{ URL::asset('public/img/search-icon.png') }}"></a>
                 <!--<div id="countryList">
                </div>-->

                <div id="kkk" class="dropdown" style="top:0px; left:-50px; "></div>

           {{ csrf_field() }}
          </form>
        </div>
        <div class="col-sm-1 col-md-1 col-lg-1" style="padding-top:10px;">
          <a href="#" id="phonebtn" class="btn btn-primary btn-lg btn-responsive phone" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Lancer / Recevoir des appels téléphoniques" style="margin-bottom: 28px!important;padding-top: 15px;padding-bottom: 15px; ">
              <span class="fas fa-fw fas fa-phone fa-2x"></span>
          </a> 
        </div>

		 <?php

        $disp=$seance->dispatcheur ;

        $iduser=Auth::id();
        if($iduser==$disp){$icon='fa-map-signs';}else{$icon='fa-envelope';}

          $count=Entree::where('dossier','')
              ->count();
            if($count==0){$color='btn-success';}
            else{$color='btn-danger';
            }
          ?>
          <div class="col-sm-1 col-md-1 col-lg-1" style="padding-top:10px;">

          <a href="{{ route('entrees.dispatching') }}" class="btn <?php echo $color; ?> btn-lg btn-responsive boite" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Boîte d'emails" style="margin-bottom: 28px!important;padding-top: 15px;padding-bottom: 15px;">
              <span class="  fa-fw fas <?php echo $icon ; ?> fa-2x"></span><?php  if($count > 0 ){ ?><span id="countnotific" class="label label-warning" style="color:black"><?php echo $count;?></span><?php } else{ ?><span id="countnotific" class="label " style="color:black"><?php echo $count;?></span> <?php } ?>
          </a>
          </div>



        <div class="col-sm-1 col-md-1 col-lg-1">
          <ul class="nav navbar-nav" style=" ">
                    {{-- User Account --}}
                    @include('layouts.partials._user_menu')

                </ul>

        </div>

          @cannot('isAdmin')

          <div class="col-sm-1 col-md-1 col-lg-1" class="overme">
              <?php
              $user = auth()->user();
              $name=$user->name;
              $lastname=$user->lastname;

              ?>

              <b style="font-size: 12px;color:white;font-weight:600">   <?php echo $name .' '. $lastname; ?></b>

              <?php $disp=$seance->dispatcheur ;
              $supmedic=$seance->superviseurmedic ;
              $suptech=$seance->superviseurtech ;
              $charge=$seance->chargetransport ;
              ?>
               <div class=" overme" style="font-size:12px;color:white;text-align:left">
                  <?php
                  $iduser=Auth::id();
                  if ($iduser==$disp) { ?>
                  <span>Dispatcheur</span><br>
                  <?php }    if ($iduser==$supmedic) { ?>
                  <span>Sup Medical</span><br>
                  <?php }
                  if ($iduser==$suptech) { ?>
                      <span>Sup Technique</span><br>
                  <?php }    if ($iduser==$charge) { ?>
                  <span>Chargé Trans</span><br>
                  <?php } ?>

              </div>

          </div>
        @endcannot
          <div class="col-sm-1 col-md-1 col-lg-1" class="navbar-toggler" data-toggle="collapse" data-target="#navbarHeader">
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

<div class="modal  " id="modalconfirm" >
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="text-align:center"  id="modalalert0"><center>Demande de Pause  </center> </h5>

            </div>
            <div class="modal-body">
                <div class="card-body">

                    <div style="text-align:center" class="row" >
                        <div style="text-align:center" class="     show" role="alert">

                            <h3 style="font-weight:bold;"> <center> Vous voulez demander une pause ?</center></h3>
                            <div class="row"><label>Durée: </label>    <center><input type="number" step="1" value="15" class="form-control" style="width:60px;margin:10px 10px 10px 10px" name="duree" id="dureep" /> Minutes</center>
                            </div>
                        </div>
                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <a id="oui"   class="btn btn  "   style="background-color:#5D9CEC; width:100px;color:#ffffff"   >Oui</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width:100px">Non</button>
            </div>
        </div>
    </div>
</div>




<style>
    @media  (max-width: 1280px)  /*** 150 % ***/  {
        #search-bar input{width:200px;}
        .search-icon{display:none;}
    }
    @media (max-width: 1024px)    {
        #search-bar input{width:170px;}
       .phone , .boite,  .date,  .search-icon{display:none;}
    }
    @media (max-width: 1100px) /*** 175 % ***/  {
        #search-bar input{width:170px;}
        .phone , .boite,   .date,   .search-icon{display:none;}
    }

   .user-menu .boite , .phone{
    margin-left: 30px;
    }

    .single-char {
        color:red;
        cursor:pointer;
    }

    .single-char2 {
        color:black;
        cursor:pointer;
    }


    .kbsdropdowns
    {

        display:block ;
        position:relative ;
        top:-65px;
        left: -50px;


    }
</style>

<script>


    function colorerSeq(string,qy) {
        if(qy!='')
        {
            var caracSp = ['-', '_', '(',')',' '];
            // For all matching elements
            $(string).each(function() {

                var hrefString=$(this).html();
                var a1=hrefString.indexOf("\"");
                var b1=hrefString.lastIndexOf("\"");
                hrefString=hrefString.substring(a1+1,b1);
                // Get contents of string
                var myStr = $(this).text();
                // Split myStr into an array of characters
                myStr = myStr.split("");
                var dejaEn=false;
                // Build an html string of characters wrapped in  tags with classes
                var myContents = "";
                var noniden="";
                var ancien;
                var kol=false;
                for (var i = 0, len = myStr.length; i < len; i++) {
                    if(qy[0].toUpperCase()==myStr[i].toUpperCase())
                    {
                        if(!dejaEn)
                        {
                            ancien= myContents ;
                            kol=true;
                            noniden="";
                            for(var j=0, len2 = qy.length; j<len2; j++)
                            {
                                if(i<len)
                                {
                                    if(qy[j].toUpperCase()==myStr[i].toUpperCase() || caracSp.includes(myStr[i]) )
                                    {
                                        if(qy[j].toUpperCase()==myStr[i].toUpperCase())
                                        {
                                            // alert ("bonjour");
                                            myContents += '<span class="single-char char-' + i + '">' + myStr[i] + '</span>';
                                            noniden+= '<span class="single-char2 char-' + i + '">' + myStr[i] + '</span>';
                                            i++;
                                        }
                                        else
                                        {
                                            if(caracSp.includes(myStr[i]))
                                            {
                                                i++;
                                                if(qy[j].toUpperCase()==myStr[i].toUpperCase())
                                                {
                                                    myContents += '<span class="single-char2 char-' + (i-1) + '">' + myStr[i-1] + '</span>';
                                                    myContents += '<span class="single-char char-' + i + '">' + myStr[i] + '</span>';
                                                    noniden+= '<span class="single-char2 char-' + (i-1) + '">' + myStr[i-1] + '</span>';
                                                    noniden+= '<span class="single-char2 char-' + i + '">' + myStr[i] + '</span>';
                                                    i++;
                                                }
                                                else
                                                {
                                                    myContents += '<span class="single-char2 char-' + (i-1) + '">' + myStr[i-1] + '</span>';
                                                    noniden+= '<span class="single-char2 char-' + (i-1) + '">' + myStr[i-1] + '</span>';
                                                }
                                            }
                                        }
                                    }
                                    else
                                    {
                                        myContents=ancien;
                                        myContents += noniden;
                                        myContents += '<span class="single-char2 char-' + i + '">' + myStr[i] + '</span>';
                                        j=len2;
                                        kol=false;
                                        ancien= myContents;
                                    }
                                }
                                else
                                {
                                    myContents=ancien;
                                    j=len2;
                                    kol=false;
                                }
                            }
                            if(kol)
                            {
                                i--;
                                dejaEn=true;
                            }
                            else
                            {
                                 dejaEn=false;
                            }
                        }
                        else
                        {
                            myContents += '<span class="single-char2 char-' + i + '">' + myStr[i] + '</span>';
                        }
                    }
                    else
                    {
                        myContents += '<span class="single-char2 char-' + i + '">' + myStr[i] + '</span>';
                    }
                }
                myContents='<a href="'+hrefString+'">'+myContents+'</a>';
                // Replace original string with constructed html string
                $(this).html(myContents);
            });
        }
    }

    $(document).ready(function(){

        $("#search-bar").keyup(function(){
            var qy=$(this).val();
            //alert(qy);

            if(qy != ''){
                var _token=$('input[name="_token"]').val();
                $.ajax({

                    url:"{{ route('RechercheMulti.autocomplete')}}",
                    method:"POST",
                    data:{qy:qy, _token:_token},
                    success:function(data)
                    {
                        $("#kkk").fadeIn();
                        $("#kkk").html(data);
                        var myStringType=$('.resAutocompRech');
                        colorerSeq(myStringType,qy);
                    }
                });
            }
            else
            {
                $("#kkk").fadeOut();
            }
        });
    });


</script>

<script>
    $(document).on('click','.resAutocompTyoeAct',function(){

        $("#search-bar").val($(this).text());
        $("#kkk").fadeOut();

    });


    $('#search-bar').blur(function() {
        $("#kkk").fadeOut();
    });



</script>
<?php
$urlapp="http://$_SERVER[HTTP_HOST]/najdaapp";?>


<?php
$seance =  DB::table('seance')
    ->where('id','=', 1 )->first();
$user = auth()->user();
$iduser=$user->id; ?>
<!--select css-->
<link href="{{ asset('public/js/select2/css/select2.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('public/js/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


<script>

    $('#dpause').click(function() {

        $('#modalconfirm').modal({show: true});

    });
    /*
    $("#dossierid").select2();

    $('#phoneicon').click(function() {

        $('#crendu').modal({show: true});

    });

    // Ajout Compte Rendu
    $('#ajoutcompter').click(function() {

        var _token = $('input[name="_token"]').val();
        var dossier = document.getElementById('dossierid').value;
        var contenu = document.getElementById('contenucr').value;

        $.ajax({
            url: "{{ route('entrees.ajoutcompter') }}",
            method: "POST",
            data: { dossier:dossier,contenu:contenu,  _token: _token},

            success: function (data) {
                alert('Ajouté avec succès');
                $('#crendu').modal('hide');
                //     $('#crendu').modal({show: false});

            }
        });


    }); //end click
*/


    $('#oui').click(function() {
        $('#modalconfirm').modal('hide');

        var _token = $('input[name="_token"]').val();
        var duree = document.getElementById('dureep').value;

        $.ajax({
            url: "{{ route('home.demandepause') }}",
            method: "POST",
            data: { duree:duree,  _token: _token},

            success: function (data) {
                alert('Demande envoyée');

            }
        });


    }); //end click





    function countNotifs() {

        <?php  if (($view_name != 'supervision') && ($view_name != 'affectation') && ($view_name != 'notifs') && ($view_name != 'missions') && ($view_name != 'transport') && ($view_name != 'transportsemaine') && ($view_name != 'dossiers-create') && ($view_name != 'entrees-dispatching') && ($view_name != 'entrees-showdisp') ) { ?>
        <?php // if($iduser == $seance->dispatcheur)
        //{  ?>
         // count notif dispatcheur
        console.log('count notif dispatcheur: ');
        $.ajax({
            type: "get",
            url: "<?php echo $urlapp; ?>/entrees/countnotifs",
            success: function (countdata1) {
               // console.log('count notif : ' + countdata1);
              //  alert( 'count Notifs disp'+countdata1);

                // var count=parseInt(data);
                //  if(count>0 )
                //  {
              //  document.getElementById('countnotif').innerHTML =   countdata1;
              document.getElementById('countnotific').innerHTML =   ''+countdata1;
           //     $('#countnotif').html('500') ;
                // document.getElementById('countnotif').innerHTML='500';
                // }
            }
        });

        <?php  // }

          if ( ($iduser==$seance->superviseurmedic) || ($iduser== $seance->superviseurtech) ) {
          // count notif superviseur
                            ?>
console.log('count notif Orange: ');
        document.getElementById('totnotifs').style.background = 'white';

        $.ajax({
            type: "get",
            url: "<?php echo $urlapp; ?>/entrees/countnotifsorange",
            success: function (countdata2) {
                console.log('count notif orange: ' + countdata2);

                // var count = parseInt(data);
                //    if (count > 0) {
                document.getElementById('notiforange').innerHTML = '' + countdata2;
                document.getElementById('totnotifs').style.background = '#FFCE54';

                // }
            }
        });


        $.ajax({
            type: "get",
            url: "<?php echo $urlapp; ?>/entrees/countnotifsrouge",
            success: function (countdata3) {
                console.log('count notif rouge: ' + countdata3);
                //   var count = parseInt(data);
                //    if (count > 0) {

                document.getElementById('notifrouge').innerHTML = '' + countdata3;
                document.getElementById('totnotifs').style.background = '#fc6e51';

                //   }

            }
        });


        <?php
        } // superviseur
?>
        setTimeout(function(){
            countNotifs();
        }, 30000);  //30 secds


<?php
        } // viewname
        ?>

    }    //function

    countNotifs();




</script>

<script>

    $('#phonebtn').on('click', function(event) {
        event.preventDefault();
         var url      = 'http://192.168.1.249/najdaapp/public/ctxSip/phone/index.php',
            features = 'menubar=no,location=no,resizable=no,scrollbars=no,status=no,addressbar=no,width=320,height=480,';
        var session=null;
        // This is set when the phone is open and removed on close
        if (!localStorage.getItem('ctxPhone')) {
            window.open(url, 'ctxPhone', features);

            return false;
        } else {
            window.alert('Phone already open.');

        }
        alert(document.getElementById('numtel').options[document.getElementById('numtel').selectedIndex].value);

    });


</script>