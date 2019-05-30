<header class="header">
        
        <div class="collapse bg-grey" id="navbarHeader">
             @include('layouts.partials._top_menu')

        </div>

    <div class="navbar ">
      <div class="row">
        <div class="col-sm-2 col-md-2 col-lg-2" style="">
           <a href="{{ route('home')}}" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                <img src="{{  URL::asset('public/img/logo.png') }}" alt="logo" />
            </a>
        </div>
        <div class="col-sm-1 col-md-1 col-lg-1" style="">
          <span  id="ndate" class="date" data-month="" data-year="" style="line-height: 1; padding-top: 13px;">
                    <p id="njour" style="font-size: 10px; margin-bottom: 0px!important;"></p><span id="numj">13</span>
           </span>
        </div>

        <div style="min-width:100px!important;padding-top:15px;padding-left:0px!important" class="col-sm-1 col-md-1 col-lg-1">
          <div class="time">
        time</div>
        </div>

        

        <div id="dpause" class="col-sm-1 col-md-1 col-lg-1 " style="padding-top:10px;padding-left:0px!important">
          <a href="#" class="btn btn-default btn-lg btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Demander pause de ton superviseur" style="margin-bottom: 28px!important;"> 
                                                <span class="fa fa-fw fa-pause"></span>
                                                <br>
                                                Pause
          </a> 
        </div>
        <div class="col-sm-3 col-md-3 col-lg-3" style=" height: 40px!important;padding-top:27px;padding-left:0px ">
          <form class="search-container" action="{{route('RechercheMulti.test')}}" id="testRecheche" method="POST">
            <input type="text" id="search-bar"  placeholder="Recherche" autocomplete="off" name="qy">
            <a href="#" onclick='document.getElementById("testRecheche").submit()'><img class="search-icon" src="{{ URL::asset('public/img/search-icon.png') }}"></a>
                 <!--<div id="countryList">
                </div>-->


                <div id="kkk" class="dropdown" style="top:0px; left:-50px; "></div>

           {{ csrf_field() }}
          </form>
        </div>

   <style>

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




        <div class="col-sm-1 col-md-1 col-lg-1" style="padding-top:10px;">
          <a href="#" class="btn btn-primary btn-lg btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Lancer / Recevoir des appels téléphoniques" style="margin-bottom: 28px!important;padding-top: 15px;padding-bottom: 15px;"> 
              <span class="fa fa-fw fa-phone fa-2x"></span>
          </a> 
        </div>
        <div class="col-sm-1 col-md-1 col-lg-1" style="padding-top:10px;">
          <a href="{{ route('boite') }}" class="btn btn-danger btn-lg btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Boîte d'emails" style="margin-bottom: 28px!important;padding-top: 15px;padding-bottom: 15px;">
              <span class="fa fa-fw fa-envelope fa-2x"></span>
          </a> 
        </div>
        <div class="col-sm-1 col-md-1 col-lg-1">
          <ul class="nav navbar-nav" style="float: right!important;">


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

    <script>
      /* function colorerSeq(string,qy) {
           


           if(qy!='')
           {
          // For all matching elements
             $(string).each(function() {

          // Get contents of string
                  var myStr = $(this).text();
                  //alert(myStr);
                  // Split myStr into an array of characters
                  myStr = myStr.split("");
                  var dejaEn=false;
                  // Build an html string of characters wrapped in  tags with classes
                  var myContents = "";
                  for (var i = 0, len = myStr.length; i < len; i++) {
                    
                    if(qy[0].toUpperCase()==myStr[i].toUpperCase())
                    {

                      if(!dejaEn)
                      {

                      for(var j=0, len2 = qy.length; j<len2; j++)
                      {
                        if(i<len)
                        {
                          if(qy[j].toUpperCase()==myStr[i].toUpperCase())
                          {
                           myContents += '<span class="single-char char-' + i + '">' + myStr[i] + '</span>';
                           i++;
                           //b=true;
                          }
                          else
                          {
                            myContents += '<span class="single-char2 char-' + i + '">' + myStr[i] + '</span>';
                            j=len2;
                            i++;
                          }
                          
                        }
                       

                      }
                    
                      
                      i--;
                      dejaEn=true;
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

                  // Replace original string with constructed html string
                  $(this).html(myContents);
                });
           }
              
              }*/
    </script>
