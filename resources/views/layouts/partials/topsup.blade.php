<header class="header">
        <?php
		        $seance =  DB::table('seance')
            ->where('id','=', 1 )->first();
		?>
        <div class="collapse bg-grey" id="navbarHeader">
             @include('layouts.partials._top_menu')

        </div>

    <div class="navbar "  style="background-color:#4FC1E9 !important">
      <div class="row">
        <div class="col-sm-1 col-md-1 col-lg-1" style="margin-right:60px">
           <a href="{{ route('supervision')}}" class="logo" style="background-color: transparent!important;border:none!important;">
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


		  
          <div  class="col-sm-1 col-md-1 col-lg-1 " style="padding-top:10px;padding-left:0px!important;margin-left:15px">
              <a href="{{ route('home') }}" class="btn btn-default btn-lg btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Demander pause de ton superviseur" style="margin-bottom: 28px!important;">
                  <i class="far fa-user"></i>
                  <br>
                  Agent
              </a>
          </div>


		  


        <div class="col-sm-3 col-md-2 col-lg-2" style=" height: 40px!important;padding-top:27px;padding-left:0px ">
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
          <a id="ajoutcompter" href="#" class="btn btn-primary btn-lg btn-responsive phone" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Lancer / Recevoir des appels téléphoniques" style="margin-bottom: 28px!important;padding-top: 15px;padding-bottom: 15px; ">
              <span class="fa fa-fw fa-phone fa-2x"></span>
          </a> 
        </div>
        <div class="col-sm-1 col-md-1 col-lg-1" style="padding-top:10px;">
		 <?php

        $disp=$seance->dispatcheur ;

        $iduser=Auth::id();
        if ($iduser==$disp) {
            ?>
			  <a href="{{ route('entrees.dispatching') }}" class="btn btn-danger btn-lg btn-responsive boite" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Boîte d'emails" style="margin-bottom: 28px!important;padding-top: 15px;padding-bottom: 15px;">
              <span class="  fa-fw    fas    fa-map-signs fa-2x"></span>
          </a>
		<?php } else {?>
          <a href="{{ route('entrees.index') }}" class="btn btn-danger btn-lg btn-responsive boite" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Boîte d'emails" style="margin-bottom: 28px!important;padding-top: 15px;padding-bottom: 15px;">
              <span class="fa fa-fw fa-envelope fa-2x"></span>
          </a> 
	 <?php }  ?>


        </div>

        <div class="col-sm-1 col-md-1 col-lg-1">
          <ul class="nav navbar-nav" style="">
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
                    //alert(myStr[i]);

                    if(qy[0].toUpperCase()==myStr[i].toUpperCase())
                    {

                        if(!dejaEn)
                        {

                            ancien= myContents ;
                            kol=true;
                            // an_i=i;
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
                                            //alert ("special");

                                            if(caracSp.includes(myStr[i]))
                                            {
                                                //alert ("special");

                                                i++;

                                                if(qy[j].toUpperCase()==myStr[i].toUpperCase())
                                                {
                                                    myContents += '<span class="single-char2 char-' + (i-1) + '">' + myStr[i-1] + '</span>';
                                                    myContents += '<span class="single-char char-' + i + '">' + myStr[i] + '</span>';

                                                    noniden+= '<span class="single-char2 char-' + (i-1) + '">' + myStr[i-1] + '</span>';
                                                    noniden+= '<span class="single-char2 char-' + i + '">' + myStr[i] + '</span>';

                                                    i++;
                                                    //b=true;
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
                                        //i=an_i;
                                        //i++;
                                        kol=false;
                                        ancien= myContents;

                                    }

                                }
                                else
                                {
                                    myContents=ancien;
                                    j=len2;
                                    // i=an_i;
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

                                // i=an_i;
                                // i--;
                                dejaEn=false;

                                // myContents=ancien;
                                // myContents+= noniden;

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

                //alert($(this).html(myContents).text());
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


                        //alert(data);

                        $("#kkk").fadeIn();
                        $("#kkk").html(data);


                        var myStringType=$('.resAutocompRech');
                        // alert( myStringType.html());
                        colorerSeq(myStringType,qy);
                        // alert(myStringType);

                        /* $(document).ready(function() {
                         var myStringType = $('.resAutocompTyoeAct').text();
                         arrayMe(myStringType);
                         });*/



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

        // alert("bonjour");

        $("#search-bar").val($(this).text());
        $("#kkk").fadeOut();


    });


    $('#search-bar').blur(function() {
        $("#kkk").fadeOut();
    });



</script>

<script>

    $('#dpause').click(function() {

        $('#modalconfirm').modal({show: true});

    });


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




    // Ajout Compte Rendu
    $('#ajoutcompter').click(function() {
        $('#modalconfirm').modal('hide');

        var _token = $('input[name="_token"]').val();
        var dossier = document.getElementById('dossierid').value;
        var contenu = document.getElementById('contenucr').value;

        $.ajax({
            url: "{{ route('entrees.ajoutcompter') }}",
            method: "POST",
            data: { dossier:dossier,contenu:contenu,  _token: _token},

            success: function (data) {
                alert('Ajouté avec succès');
            }
        });


    }); //end click



</script>