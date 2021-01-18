<script src="{{ asset('public/najda_app/najdaapp/webphone/webphone_api.js') }}"></script>
<header class="header">
        
        <div class="collapse bg-grey" id="navbarHeader">
             @include('layouts.partials._top_menu')

        </div>

    <div class="navbar "  style="background-color:#636b6f !important">
      <div class="row">
        <div class="col-sm-2 col-md-2 col-lg-2" style="">
            <a href="{{ route('entrees.dispatching')}}" class="logo" style="background-color: transparent!important;border:none!important;">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                <img style="margin-left:-60px;" src="{{  URL::asset('public/img/logo.png') }}" alt="logo" />
            </a>
        </div>

        <div style="min-width:100px!important;padding-top:15px;padding-left:0px!important" class="col-sm-1 col-md-1 col-lg-1">
          <p id="njour" style="font-size: 25px; margin-bottom: 0px!important;color: white"></p>
            <div  >


                <?php
                // Récupère l'heure du serveur

                $localtime = localtime();

                $seconde =  $localtime[0];
                $minute =  $localtime[1];
                $heure =  $localtime[2];

                ?>
                <script>
                    bcle=0;

                    function clock()
                    {
                        if (bcle == 0)
                        {
                            heure = <?php echo $heure ?>;
                            min = <?php echo $minute ?>;
                            sec = <?php echo $seconde ?>;
                        }
                        else
                        {
                            sec ++;
                            if (sec == 60)
                            {
                                sec=0;
                                min++;
                                if (min == 60)
                                {
                                    min=0;
                                    heure++;
                                };
                            };
                        };
                        txt="";
                        if(heure < 10)
                        {
                            txt += "0";
                        }
                        txt += heure+ ":";
                        if(min < 10)
                        {
                            txt += "0"
                        }
                        txt += min ;//+ ":";
                        /* if(sec < 10)
                         {
                         txt += "0"
                         }
                         txt += sec ;*/
                        timer = setTimeout("clock()",1000);
                        bcle ++;
                        document.clock.date.value = txt ;
                    }
                </script>

                <body onload="clock();">
                <style type="text/css">
                    form{
                        display:inline;
                    }
                    .style {border-width: 0;background-color:transparent;color: #f3fdfd;font-weight:bold;font-size:20px;margin-left:10px;}
                </style>

                <form name="clock" onSubmit="0"  >
                    <input type="text" name="date" size="5" readonly="true" class="style">
                </form>
                </body>
            </div>
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
<?php
    use App\Entree;
    $seance =  DB::table('seance')
        ->where('id','=', 1 )->first();
    $user = auth()->user();
    $iduser=$user->id;

    ?>
           <?php
          if( ($seance->superviseurmedic==$iduser)  || ($seance->superviseurtech==$iduser) ||($user->user_type=='admin'))
          { ?>
              <div  class="col-sm-1 col-md-1 col-lg-1 " style="padding-top:10px;padding-left:0px!important">
                  <a href="{{ route('supervision') }}" class="btn btn-default btn-lg btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Demander pause de ton superviseur" style="margin-bottom: 28px!important;">
                      <i class="fas fa-users-cog"></i>
                      <br>
                      Superv
                  </a>
              </div>

         <?php } ?>

      @cannot('isSupervisor')
          @cannot('isAdmin')
                  <div class="col-sm-1 col-md-1 col-lg-1" style="padding-top:10px;margin-left:15px">
                      <a href="{{ route('home') }}" class="btn btn-default btn-lg btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="basculer en mode agent" style="margin-bottom: 28px!important;padding-top: 15px;padding-bottom: 15px;">
                          <i class="far fa-user"></i> Agent
                      </a>
                  </div>

          @endcannot
      @endcannot
              <!--<div id="dpause" class="col-sm-1 col-md-1 col-lg-1 " style="padding-top:10px;padding-left:0px!important">
          <a href="#" class="btn btn-default btn-lg btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Demander pause de ton superviseur" style="margin-bottom: 28px!important;"> 
                                                <span class="fa fa-fw fa-pause"></span>
                                                <br>
                                                Pause
          </a> 
        </div>-->
        <div class="col-sm-2 col-md-2 col-lg-2" style=" height: 40px!important;padding-top:27px;padding-left:0px ">
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
          <a data-toggle="modal" data-target="#appelinterfacerecep" class="btn btn-primary btn-lg btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Lancer / Recevoir des appels téléphoniques" style=";margin-left: 20px;margin-bottom: 28px!important;padding-top: 15px;padding-bottom: 15px;;margin-left:-5px">
              <span class="fas fa-fw fas fa-phone fa-2x"></span>
          </a> 
        </div>
          <!--
          @can('isAdmin')
        <div class="col-sm-1 col-md-1 col-lg-1" style="padding-top:10px;">
          <a href="{{ route('home') }}" class="btn btn-danger btn-lg btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="basculer en mode agent" style="margin-bottom: 28px!important;padding-top: 15px;padding-bottom: 15px;">
            Supervision
          </a>
        </div>
          @endcan
              @cannot('isAdmin')
              <div class="col-sm-1 col-md-1 col-lg-1" style="padding-top:10px;">
                  <a href="{{ route('home') }}" class="btn btn-danger btn-lg btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="basculer en mode agent" style="margin-bottom: 28px!important;padding-top: 15px;padding-bottom: 15px;">
                      Mode Agent
                  </a>
              </div>
              @endcannot
-->
          <div  class="col-sm-1 col-md-1 col-lg-1 " style="padding-top:10px;padding-left:0px!important; ">
              <a href="{{ route('home') }}" class="btn btn-default btn-lg btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Demander pause de ton superviseur" style="margin-bottom: 28px!important;">
                  <i class="far fa-user"></i>
                  <br>
                  Agent
              </a>
          </div>
        <div class="col-sm-1 col-md-1 col-lg-1">
          <ul class="nav navbar-nav" style=" ">


                    {{-- User Account --}}
                    @include('layouts.partials._user_menu')

                </ul>
        </div>

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

<?php
  $param= App\Parametre::find(1);$env=$param->env;
$urlapp="http://$_SERVER[HTTP_HOST]/".$env;
?>


<div class="modal  " id="crendu" >
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="text-align:center"  id="modalalert0"><center>Compte Rendu </center> </h5>
            </div>
            <div class="modal-body">
                <div class="card-body">


                    <div class="form-group">
                        <label for="sujet">Dossier :</label>
                        <input style="overflow:scroll;" id="dossierid" type="text" class="form-control" name="dossierid"     />

                    </div>

                    <div class="form-group">
                        <label for="sujet">Contenu :</label>
                        <textarea style="overflow:scroll;" id="contenucr"   class="form-control" name="contenucr"    ></textarea>

                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <a id="ajoutcompter"   class="btn btn  "   style="background-color:#5D9CEC; width:100px;color:#ffffff"   >Ajouter</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width:100px">Annuler</button>
            </div>
        </div>
    </div>
</div>
 <!--Modal Tel-->

    <div class="modal fade" style="z-index:10000!important;left: 20px;" id="numatransfer"    role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
        <div class="modal-dialog" role="numatransfer">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModal2">Saisir le numéro</h5>

                </div>
                <div class="modal-body">
                    <div class="card-body" sytle="height:300px">

                        <div class="form-group">
                            {{ csrf_field() }}

                            <form id="numatransfer" novalidate="novalidate">

                                <input id="numatrans" name="numatrans" type="text" value="" />
                                   
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
<?php

?>

                    <button type="button"  class="btn btn-primary"  onclick="transfer();">Transférer</button>
   
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>

                </div>
            </div>

        </div>

    </div>
<!--Modal Tel 2-->

    <div class="modal fade" id="appelinterfacerecep"    role="dialog" aria-labelledby="basicModal" aria-hidden="true">
 <div class="modal-dialog modal-lg" role="telrecep"  sytle="width:200px;height:30px">
            <div class="modal-content">
                <div class="modal-header">
                  <h3 class="modal-title" style="text-align:center"  id=" "><center>Recevoir un appel </center> </h3>
</div>
                <div class="modal-body">
                    <div class="card-body" >


                        <div class="form-group">
                            {{ csrf_field() }}

                            <form id="appelinterfacerecep" novalidate="novalidate">
  <div id="call_duration">&nbsp;</div>
            <div id="status_call">&nbsp;</div>
<input id="nomencoursrecep" name="nomencours" type="text" readonly value="" style="font-size: 30px;text-align: center;border: none;margin-left:50px;">
 <input id="numencoursrecep" name="numencours" type="text" readonly value="" style="font-size: 30px;text-align: center;border: none;margin-left:50px;">

                            </form>

                        </div>
                    </div>

                </div>

                <div class="modal-footer">


<button type="button"  class="btn btn-primary"  onclick="accept();"><i class="fas fa-phone-volume"></i> Répondre</button>              
 <button type="button"  class="btn btn-primary"  onclick="Hangup();"><i class="fas fa-phone-slash"></i> Raccrocher</button>
 <div id="mettreenattente" style="display :inline-block;"><button type="button"  class="btn btn-primary" onclick="hold(true);" ><i class="fas fa-pause"></i> Mettre en attente</button></div>
 <div id="reprendreappel" style="display :none;"><button type="button"  class="btn btn-primary"  onclick="hold(false);"><i class="fas fa-phone"></i> Reprendre</button></div>
 <div id="couperson" style="display :inline-block;"><button type="button"  class="btn btn-primary" onclick="mute(true,0);" ><i class="fas fa-microphone-slash"></i> Couper le son</button></div>
 <div id="reactiveson" style="display :none;"><button type="button"  class="btn btn-primary"  onclick="mute(false,0);"><i class="fas fa-microphone"></i> Réactiver son</button></div>
 <button type="button"  class="btn btn-primary" data-toggle="modal" data-target="#numatransfer"><i class="fas fa-reply-all"></i> Transférer</button>
<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
              <!--<button type="button"  class="btn btn-primary"  onclick="transfer();">Transférer</button>    
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>!-->

                </div>
            </div>

        </div>

    </div>




<style>
    @media  (max-width: 1280px)  /*** 150 % ***/  {
        #search-bar{width:250px;}
        .search-icon{display:none;}
    }
    @media (max-width: 1024px)    {
        #search-bar{width:220px;}
        .boite,  .date,  .search-icon{display:none;}
    }
    @media (max-width: 1100px) /*** 175 % ***/  {
        #search-bar{width:200px;}
        .boite,   .date,   .search-icon{display:none;}
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
        left: -50 px;


    }
.modal-lg
    {

        max-width:60%;


    }
</style>

<script>


    function colorerSeq(string,qy) {

        if(qy!='')
        {
            var caracSp = ['-', '_', '(',')',' '];
            //alert(string);
            // For all matching elements
            $(string).each(function() {
                var hrefString=$(this).html();
                //  alert(hrefString);
                var a1=hrefString.indexOf("\"");
                var b1=hrefString.lastIndexOf("\"");
                hrefString=hrefString.substring(a1+1,b1);

                // Get contents of string
                var myStr = $(this).text();
                //alert(myStr);
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
                                            //b=true;
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
                        // alert( myStringType.html());
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


<script>

      function Hangup()
        {
            webphone_api.hangup();
            
        }
function accept()
        {
            webphone_api.accept();
            
        }
    function transfer()
        {
numtrans=$('#numatrans').val();;
//alert(numtrans);
            webphone_api.Transfer(numtrans);
        }
  function hold(state)
        {
if(state===true)

         {   webphone_api.hold(state);
document.getElementById('mettreenattente').style.display = 'none';
document.getElementById('reprendreappel').style.display = 'inline-block';}
if(state===false)

         {   webphone_api.hold(state);
document.getElementById('reprendreappel').style.display = 'none';
document.getElementById('mettreenattente').style.display = 'inline-block';}

        }
function mute(state,direction)
        {
if(state===true)

         {   webphone_api.mute(state,direction);
document.getElementById('couperson').style.display = 'none';
document.getElementById('reactiveson').style.display = 'inline-block';}
if(state===false)

         {   webphone_api.mute(state,direction);
document.getElementById('reactiveson').style.display = 'none';
document.getElementById('couperson').style.display = 'inline-block';}

        }

</script>
