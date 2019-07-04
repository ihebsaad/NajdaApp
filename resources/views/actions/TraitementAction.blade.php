
@extends('layouts.mainlayout')

@section('content')
   
<!--<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#home">Home</a></li>
  <li><a data-toggle="tab" href="#menu1">Menu 1</a></li>
  <li><a data-toggle="tab" href="#menu2">Menu 2</a></li>
</ul>

<div class="tab-content">
  <div id="home" class="tab-pane fade in active">
    <h3>HOME</h3>
    <p>Some content.</p>
  </div>
  <div id="menu1" class="tab-pane fade">
    <h3>Menu 1</h3>
    <p>Some content in menu 1.</p>
  </div>
  <div id="menu2" class="tab-pane fade">
    <h3>Menu 2</h3>
    <p>Some content in menu 2.</p>
  </div>
</div>-->


<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#home">Instructions</a></li>
  <!--<li><a data-toggle="tab" href="#menu1">Email</a></li>
  <li><a data-toggle="tab" href="#menu2">SMS</a></li>
  <li><a data-toggle="tab" href="#menu3">Téléphone</a></li>
  <li><a data-toggle="tab" href="#menu4">Fax</a></li>
  <li><a data-toggle="tab" href="#menu5">Créer Prestation</a></li>
  <li><a data-toggle="tab" href="#menu6">Créer OM</a></li>
  <li><a data-toggle="tab" href="#menu7">Créer Doc</a></li>-->
  
</ul>

<div class="tab-content">
<br>
 
  <!-- début onglet instructions------------------------------------------------------------------ -->
  <div id="home" class="tab-pane fade in active">
     @if(session()->has('messagekbsSucc'))
    <div class="alert alert-success">
       <center> <h4>{{ session()->get('messagekbsSucc') }}</h4></center>
    </div>
  @endif

    @if(session()->has('messagekbsFail'))
    <div class="alert alert-danger">
       <center> <h4>{{ session()->get('messagekbsFail') }}</h4></center>
    </div>
  @endif
    

   <!--<form action="{{ url('dossier/Mission/TraitercommentAction/'.$Action->Mission->dossier->id.'/'.$Action->Mission->id.'/'.$Action->id)}}">-->

    <br>
     <div class="row">
     
     <div class="col-sm-2">


      <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-envelope"></i> Email <i class="fa fa-angle-down"></i>
                </button>
                <ul class="dropdown-menu pull-right">
                    <li>
                        <a href="{{url('dossiers/view/'.$dossier->id )}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                            Au client </a>
                    </li>
                    <li>
                        <a href="{{url('dossiers/view/'.$dossier->id )}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                            Au Prestataire </a>
                    </li>
                    <li>
                        <a href="{{url('dossiers/view/'.$dossier->id )}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                            A l'assuré </a>
                    </li>

                </ul>
     </div>&nbsp
     </div>
    <div class="col-sm-1">
      <!--<button class="btn btn-default" >sms</button>&nbsp-->
      <button type="button" class="btn btn-default"  >
                    <a style="color:black" href="{{url('dossiers/view/'.$dossier->id )}}"> SMS</a>
                </button>
     </div>
     <div class="col-sm-2">
      <!--<button class="btn btn-default">téléphone </button>&nbsp;-->
       <button type="button" class="btn btn-default" >
              
                    Téléphone

                </button>
     </div>
     <div class="col-sm-1">
      <!--<button class="btn btn-default">fax</button>&nbsp-->
      <button type="button" class="btn btn-default" >
          <a style="color:black" href="{{url('dossiers/view/'.$dossier->id )}}"> Fax</a>
      </button>
     </div>
     <div class="col-sm-2">
     <button type="button" class="btn btn-default"  >
                    <a style="color:black" href="{{url('dossiers/view/'.$dossier->id )}}"> Prestation</a>
                </button>
     </div>
     <div class="col-sm-2">
      <button type="button" class="btn btn-default"  >
                    <a style="color:black"  href="{{url('dossiers/view/'.$dossier->id )}}">Créer DOC</a>
                </button>
     </div>
     <div class="col-sm-2">
      <button type="button" class="btn btn-default"  >
                    <a style="color:black"  href="{{url('dossiers/view/'.$dossier->id )}}"> Créer ORM</a>
                </button>
     </div>



    </div>

    <!--<div class="row">
       

   <div class="col-md-3">
    <button class="btn " style="float:right;margin-right:5px;" title="creer un Rappel" data-toggle="modal" data-target="#ReporterA" >Reporter action<br>
     <i style="margin-top:8px" class="fa fa-2x fa-clock"> </i> </button>
   </div>


    

    <div class="col-md-3">
    <button id ="rappelerk"class="btn " style="float:right;margin-right:5px;" title="Reporter la sous-action courante à une date ultérieure" data-toggle="modal" data-target="#RappelerA" >Attente de réponse<br>
     <i style="margin-top:8px" class="fa fa-2x fa-history"> </i></button>
   </div>
     </div>-->
    <br>
    <h3>Étapes à réaliser:</h3>
    <br>
    <p><textarea readonly style="padding:10px 10px 10px 10px ;border:1px solid #00aced;WIDTH: 100%; height:170px;  font-size: 18px;">{{$Action->descrip}}</textarea>
    </p>
     <br>
     <div>

       <form id="kbsoptionform" action="{{ url('/traitementsBoutonsActions/'.$Action->Mission->dossier->id.'/'.$Action->Mission->id.'/'.$Action->id.'/1')}}" method="GET">


      <h4 id="comenttitle">Vous pouvez ajouter un ou plusieurs commentaires :</h4>
      <br>

      <!--<form  id="formcoments" action="{{ url('dossier/Mission/TraitercommentAction/'.$Action->Mission->dossier->id.'/'.$Action->Mission->id.'/'.$Action->id)}}">-->

         {{ csrf_field() }}
       <div class="com_wrapper">
        <!-- bloc pour commentaires existants-->
        <?php $nbcomm=0;?>
        
             @if($Action->comment1)
             <?php $nbcomm++;?>
            <div>
            <input autocomplete="off"  type="text" style="width:80%"  size="70" onkeyup="changing(this)"  id="comment1" name="comment1" value="<?php echo $Action->comment1 ?>"/>
            <a href="javascript:void(0);" class="com_button" title="Ajouter commentaire"></a>
            </div>
            <br>
             @endif
             @if($Action->comment2)
             <?php $nbcomm++;?>
            <div>
            <input autocomplete="off"  type="text"  style="width:80%"  size="70" onkeyup="changing(this)"  id="comment2" name="comment2" value="<?php echo $Action->comment2 ?>"/>
            <a href="javascript:void(0);" class="com_button" title="Ajouter commentaire"></a>
            </div>
             <br>
             @endif
             @if($Action->comment3)
             <?php $nbcomm++;?>
              <div>
            <input autocomplete="off" type="text" style="width:80%"  size="70" onkeyup="changing(this)" id ="comment3" name="comment3" value="<?php echo $Action->comment3 ?>"/>
            <a href="javascript:void(0);" class="com_button" title="Ajouter commentaire"></a>
            </div>
             <br>
             @endif

        
      <?php if($nbcomm<3){?>
       <div>
            <input autocomplete="off"  type="text" onkeyup="changing(this)" size="70" style="width:80%" id="field_name[]" name="field_name[]" value=""/>
            <a href="javascript:void(0);" class="com_button" title="Ajouter commentaire"><?php if($nbcomm<=1){ ?><img width="26" height="26" src="{{ asset('public/img/plus.png') }}"/> <?php } ?></a>
      </div> 
    <?php } ?>
    </div>

    
           <!-- <center><button  id="Enrcommentaire" type="submit" >Enregistrer Commentaires</button></center>-->


    </div>
    <br><br>
    <!-- postion des otions et les boutons radios -->
    
    <div class="row">

      <!--<div class="col-md-4">
       <h4>option 1  &nbsp<input type="radio" name="gender" value="male"></h4>
      </div>
     

      <div class="col-md-4">
       <h4>option 2  &nbsp<input type="radio" name="gender" value="male"></h4>
      </div>

      <div class="col-md-4">
      <h4>option 3  &nbsp<input type="radio" name="gender" value="male"></h4>
      </div>-->

      @if($Action->nb_opt != 0)
      <h4> Vous devez sélectionner une option: <select name="optionAction">
     <option value='0'></option>
     <?php for($k=1; $k<=$Action->nb_opt;$k++) {?>
     <option value='<?php echo $k ?>'>option<?php echo $k?></option>   
     <?php } ?>
     </select></h4>
     @endif
    </div>
     <br><br>
     <div class="row">
      <center><button id="BouRapOuRepAct" class="btn btn-default" type="button"> Créer Attente de réponse / reporter action  </button></center>
     </div>

     <br><br>
     <div id="DivRapOuRepAct" class="row" style="border-style:solid; border-color:black;"> <!--début onglet reporter/attente document-->

    <ul class="nav nav-tabs nav-justified">
    <li class="active"><a data-toggle="tab" href="#OngRapAct">Attente de réponse <i class="fa fa-2x fa-history"> </i> </a></li>
    <li ><a data-toggle="tab" href="#OngRepAct">Reporter action <i class="fa fa-2x fa-clock"> </i></a></li>
    
     </ul>
<style>



</style>
<script>

$(document).on('click','.kbstab',function(){

    $(".kbstab").removeClass("kbsactive");
    // $(".tab").addClass("active"); // instead of this do the below 
    $(this).addClass("kbsactive");   
});

</script>

  <div class="tab-content">
    <div id="OngRapAct" class="tab-pane fade in active" >
       <br>
      <h5>saisissez la date de rappel de l'<u><b>attente de réponse</b></u> :</h5>
      <br>
      <p> 
      <div class="row">
        <?php $da = (new \DateTime())->modify('-1 Hour')->format('Y-m-d\TH:i'); ?>
       <input id="daterappel" type="datetime-local" value="<?php echo $da ?>" class="form-control" style="width:50%;  text-align: left;" name="daterappel"/>
      </div>
      <br><br>
      <div class="row">
        <button id="BouRapAct" type="submit" style="flow : left; margin-left: 120px"> Attente de réponse</button>
      </div>

      </p>
    </div>
    <div id="OngRepAct" class="tab-pane fade" >
      <br>
      <h5 style="float: right ; margin-right: 20px;">Saisissez la date de <u><b>report de l'action</b></u> : &nbsp  &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp</h5>
      <br>
      <br>
      <p align="right" style="margin-right: 5px;">
          <div class="row">
        <?php $da = (new \DateTime())->modify('-1 Hour')->format('Y-m-d\TH:i'); ?>
       <input id="datereport" type="datetime-local" value="<?php echo $da ?>" class="form-control" style="width:50%;  text-align: right; float: right !important; margin-right: 20px;"  name="datereport"/>
      </div>
      <br><br>
      <div class="row">
        <button id="BouRepAct" type="submit" style="float: right; margin-right: 130px;"> Reporter Action</button> &nbsp  &nbsp &nbsp
      </div>
    
      </p>
    </div>
    </div>




     </div><!--fin  onglet reporter/attente document-->




       <br><br>

    <!--position des boutons -->
    <div class="row">

      
     <!-- <div class="col-md-6">
          <a href="{{ url('dossier/Mission/EnregistrerEtAllerSuivante/'.$Action->Mission->dossier->id.'/'.$Action->Mission->id.'/'.$Action->id)}}" class="btn btn-primary"><i class="fa fa-check"></i>  Marquer la sous-action comme faite et <i class="fa fa-arrow-right"></i> aller à la suivante</a>

      </div>-->
      <!--<div class="col-md-4">
          <a href="{{ url('dossier/Mission/EnregistrerEtAllerSuivante/'.$Action->Mission->dossier->id.'/'.$Action->Mission->id.'/'.$Action->id)}}" class="btn btn-primary"><i class="fa fa-check"></i>  Faite </a>-->

         <div class="col-md-3">

          <button id="BouFaiAct" type="submit" class="btn btn-success" style="width: 200px;"> Fait </button>


      </div>

    

      <!--<div class="col-md-6">
      <a href="{{ url('dossier/Mission/FinaliserMission/'.$Action->Mission->dossier->id.'/'.$Action->Mission->id.'/'.$Action->id)}}" class="btn btn-primary"><i class="fa fa-check-circle"></i>Marquer la sous-action comme faite </a>

      </div>-->

      



       <div class="col-md-1">
     

      </div>
      
      <!--<div class="col-md-5">
        
         <a class="btn btn-danger" href="{{ url('dossier/Mission/AnnulerEtAllerSuivante/'.$Action->Mission->dossier->id.'/'.$Action->Mission->id.'/'.$Action->id)}}" ><i class="fa fa-close"></i> Annuler la sous-action et <i class="fa fa-arrow-right"></i> aller à la suivante</a>

     </div>-->

     <div class="col-md-3">


         @if($Action->igno_ou_non != 0)
         <button id="BouIgnAct" class="btn btn-danger" type="submit" href=""  style="width: 200px;" > <i class="fa fa-close"></i> Ignorer</button>
         @else
          <button id="BouIgnAct2" class="btn btn-danger disabled" type="button" style="width: 200px;" ><i class="fa fa-close"></i> Ignorer</button>

         @endif

     </div>
      <div class="col-md-1">
     

      </div>

     <div class="col-md-3">
        
         <a class="btn btn-danger"  style="width: 200px;" href="{{ url('dossier/Mission/AnnulerMissionCourante/'.$Action->Mission->dossier->id.'/'.$Action->Mission->id.'/'.$Action->id)}}" ><i class="fa fa-close"></i> Annuler la mission</a>
     </div>

   </div>
   <br><br>
    <div class="panel panel-default">
  <div class="panel-heading">Délégation</div>
  <div class="panel-body">


    <div class="row" style=" padding-top: 50px;">
        
      <div class="col-md-5">
         <!--<button id="BouDeleAction" type="button"class="btn btn-primary disabled" style="width: 250px;"> Déléguer l'action</button>-->
      </div>

      <div class="col-md-2">
      </div>

   <div class="col-md-5">
        <!--<button id="BouDeleMiss" type="button" class="btn btn-primary disabled" style="width: 250px; "> Déléguer la mission
        </button>-->

        <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#modalDelegMiss">Déléguer la mission courante</button>

        
  </div>
      

   </div>
<!-- les div de délégation-->

          <style>
            table tr:not(:first-child){
                cursor: pointer;transition: all .25s ease-in-out;
            }
            table tr:not(:first-child):hover{background-color: #ddd;}
          </style>



    <div class="row" style="padding-left: 20px; padding-top: 10px">
        
      <div class="col-md-5">
         <div id="DivDeleAction" type="button" style="width: 400px;"> 
          <h5><b> Cliquer 2 fois sur le nom de personne <br>à laquelle vous voulez déléguer l'action </b></h5>
           <br>
          <table id="tabDelAct" border="1">
           
            <tr> <th> </th><th>Nom </th> <th>Prénom</th> <th>rôle</th>  </tr>
            <tr> <td>1</td> <td>Foulen 1</td>  <td> ben Foulen 1</td> <td>agent</td>   </tr>
            <tr> <td>2</td> <td>Foulen 2</td>  <td> ben Foulen 2</td> <td>Superviseur</td>  </tr>
            <tr> <td>3</td> <td>Foulen 3</td>  <td> ben Foulen 3</td> <td>agent</td>  </tr>                    
            
        </table>

        <br>

         <p style="color: red" id="PetatDelAct"></p>


         </div>
      </div>

      <div class="col-md-2">
      </div>

   <div class="col-md-5">
        <div id="DivDeleMiss" type="button"  style="width: 400px; "> 
          <h5> <b>Cliquer 2 fois sur le nom de personne <br> à laquelle vous voulez déléguer la mission </b></h5>
          <br>
          <table id="tabDelMiss" border="1">
           
            <tr> <th> </th><th>Nom </th> <th>Prénom</th> <th>rôle</th>  </tr>
            <tr> <td>1</td> <td>Foulen 1</td>  <td> ben Foulen 1</td> <td>Agent</td>   </tr>
            <tr> <td>2</td> <td>Foulen 2</td>  <td> ben Foulen 2</td> <td>Superviseur</td>  </tr>
            <tr> <td>3</td> <td>Foulen 3</td>  <td> ben Foulen 3</td> <td>Agent</td>  </tr>                    
            
        </table>

        <br>

        <p style="color: red" id="PetatDelMiss"></p>
        </div>
  </div>

 <script>
    
                var tabDelAct = document.getElementById('tabDelAct');
                
                for(var i = 1; i <  tabDelAct.rows.length; i++)
                {
                    tabDelAct.rows[i].ondblclick = function()
                    {
                         //rIndex = this.rowIndex;
                         //document.getElementById("fname").value = this.cells[0].innerHTML;
                         //document.getElementById("lname").value = this.cells[1].innerHTML;
                         //document.getElementById("age").value = this.cells[2].innerHTML;


                             var txt;
                               var r = confirm("Vous voulez déléguer cette action à : "+this.cells[1].innerHTML+" "
                                +this.cells[2].innerHTML+" ( "+this.cells[3].innerHTML+" )");
                                if (r == true) {
                                  txt = "Action déléguée";
                                } else {
                                  txt = "Action non déléguée";
                                }
                                document.getElementById("PetatDelAct").innerHTML = txt;
                         //alert(this.cells[0].innerHTML);
                    };
                }

                     var tabDelMiss = document.getElementById('tabDelMiss');
                
                for(var i = 1; i < tabDelMiss.rows.length; i++)
                {
                    tabDelMiss.rows[i].ondblclick = function()
                    {
                         //rIndex = this.rowIndex;
                         //document.getElementById("fname").value = this.cells[0].innerHTML;
                         //document.getElementById("lname").value = this.cells[1].innerHTML;
                         //document.getElementById("age").value = this.cells[2].innerHTML;
                         
                               var txt;
                               var r = confirm("Vous voulez déléguer cette mission à : "+this.cells[1].innerHTML+" "
                                +this.cells[2].innerHTML+" ( "+this.cells[3].innerHTML+" )");
                                if (r == true) {
                                  txt = "Mission déléguée";
                                } else {
                                  txt = "Mission non déléguée";
                                }
                                document.getElementById("PetatDelMiss").innerHTML = txt;

                         //alert(this.cells[0].innerHTML);
                    };
                }
    
  </script>



  <script>

  $('#BouDeleAction').click(function(e) { 
  $('#DivDeleAction').fadeToggle("slow");
 

 });
 $('#BouDeleMiss').click(function(e) { 
  $('#DivDeleMiss').fadeToggle("slow");


 });
$('#DivDeleMiss').hide();
$('#DivDeleAction').hide();


  </script>
      

   </div>





</div>
</div>

<div>
<!--<input type=text list=browsers >
<datalist id=browsers >
   <option> Google
   <option> IE9
</datalist>-->

<!--<select>
  <optgroup label="Swedish Cars">
    <option value="volvo">Volvo</option>
    <option value="saab">Saab</option>
  </optgroup>
  <optgroup label="German Cars">
    <option value="mercedes">Mercedes</option>
    <option value="audi">Audi</option>
  </optgroup>
</select>-->


<!--<table id="my-table-id" border=1>
 <tbody style="cursor:pointer">
  <tr><td>row 1 col 1</td> <td>row 1 col 2</td>  <td>row 1 col 3</td></tr>
  <tr><td>row 2 col 1</td> <td>row 2 col 2</td>  <td>row 2 col 3</td></tr>
  <tr><td>row 3 col 1</td> <td>row 3 col 2</td>  <td>row 3 col 3</td></tr>
 </tbody>
</table>-->

        
   

</div>



<input type="hidden" id="NumBouton" name="numerobouton" value=""/>



    {{ csrf_field() }}
     </form>

       <br>
     <div class="row">

     <!-- <div class="col-md-6">
        <?php  if(isset($Action)) { if($Action->ordre > 1){ ?>
         <a class="btn btn-primary" href="{{ url('dossier/Mission/EnregistrerEtAllerPrecedente/'.$Action->Mission->dossier->id.'/'.$Action->Mission->id.'/'.$Action->id)}}"> <i class="fa fa-arrow-left"></i>  Aller à la sous-action précédente</a>
       <?php }}?>
      </div>-->
      <div class="col-md-1">
      </div>

     <!-- <div class="col-md-5">

        <a class="btn btn-danger" href="{{ url('dossier/Mission/AnnulerMissionCourante/'.$Action->Mission->dossier->id.'/'.$Action->Mission->id.'/'.$Action->id)}}" ><i class="fa fa-close"></i>  Annuler la Mission courante</a>
      </div>-->
     
     </div>

    <!--</form>-->
  </div>
  <script type="text/javascript">
     $(document).ready(function(){
    var maxField = <?php echo (3-$nbcomm) ?>; //Input fields increment limitation
    var comButton = $('.com_button'); //Add button selector
    var comwrapper = $('.com_wrapper'); //Input field wrapper
    var comfieldHTML = '<div><br><input autocomplete="off"  style="width:80%" size="70" type="text" onkeyup="changing(this)" id="field_name[]"  name="field_name[]" value=""/><a href="javascript:void(0);" class="comremove_button"> <img width="26" height="26" src="{{ asset('public/img/moin.png') }}"/></a><br></div> '; //New input field html
    var x = 1; //Initial field counter is 1
    
    //Once add button is clicked
    $(comButton).click(function(){
        //Check maximum number of input fields
        if(x < maxField){ 
            x++; //Increment field counter
            $(comwrapper).append(comfieldHTML); //Add field html
        }
    });
    
    //Once remove button is clicked
    $(comwrapper).on('click', '.comremove_button', function(e){
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });
});
</script>
<!-- fin traitement partie instructions ------------------------------------------------------------------ -->
  <div id="menu1" class="tab-pane fade" style="height: 500px;">
    <h3>Mail</h3>
    <p>

    <!-- début traitement email------------------------------------------------------------------ -->
<div class="row">
  <div class="col-md-6">
  </div>
    <div class="col-md-6">
    <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-envelope"></i> Email <i class="fa fa-angle-down"></i>
                </button>
                <ul class="dropdown-menu pull-right">
                    <li>
                        <a href="{{route('emails.envoimail',['id'=>$dossier->id,'type'=> 'client','prest'=> 0])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                            Au client </a>
                    </li>
                    <li>
                        <a href="{{route('emails.envoimail',['id'=>$dossier->id,'type'=> 'prestataire','prest'=> 0])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                            Au Prestataire </a>
                    </li>
                    <li>
                        <a href="{{route('emails.envoimail',['id'=>$dossier->id,'type'=> 'assure','prest'=> 0])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                            A l'assuré </a>
                    </li>

                </ul>
     </div>
    </div>
  </div>
     
      <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#annulerAttenteReponse"> Annuler attente de réponse</button>
<style>
td {border: 1px #DDD solid; padding: 5px; cursor: pointer;}

.selected {
    background-color: brown;
    color: #FFF;
}
</style>

<!-- Modal -->
  <div class="modal fade" id="annulerAttenteReponse" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Annuler Attente Réponse</h4>
        </div>
        <div class="modal-body">
          <p>
            

            <table id="tabkkk">

                    <tr>  <th> Action  </th> <th> Mission  </th><th> Dossier   </th> </tr>

                    <tr> <td>Action 1</td> <td>mission 1</td> <td>dossier 1</td>  </tr>
                     <tr> <td>Action 2</td> <td>mission 2</td> <td>dossier 2</td>  </tr>
                      <tr> <td>Action 3</td> <td>mission 3</td> <td>dossier 3</td>  </tr>
                   
                        
            </table>
            <!--<input type="button" id="tst" value="OK" onclick="fnselect()" />-->



      




          </p>
        </div>
        <div class="modal-footer">
          <button type="button" id="tst" class="btn btn-default" data-dismiss="modal">Annuler Attente Réponse </button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Quitter</button>
        </div>
      </div>


      <script type="text/javascript">
        
      $("#tabkkk tr").click(function(){
         $(this).addClass('selected').siblings().removeClass('selected');    
         var value=$(this).find('td:first').html();
        //alert(value);    
      });

      $('#tst').on('click', function(e){
          alert($("#tabkkk tr.selected td:first").html());
      });





      </script>


     {{--@include('emails.envoimailAction');--}}





      
    </div>
  </div>


    <!--fin traitement email--------------------------------------------------------------------------------------->
 
    </p>
  </div>
  <div id="menu2" class="tab-pane fade">
    <h3> SMS </h3>
    <p>
      <!-- Envoyer un SMS  Whatsapp ---------------------------------------------------------------------------------->
      
   
 @include('emails.smsAction');

    </p>
    <!-- Fin Envoyer un SMS  Whatsapp ---------------------------------------------------------------------------------->
  </div>

  <div id="menu3" class="tab-pane fade">
    <h3> Téléphonique</h3>
    <p>gestion téléphonique</p>
  </div>

  <div id="menu4" class="tab-pane fade">
   <!-- <h2>Envoi de Fax</h2>-->
  
     @include('emails.envoifaxAction');
  </div>

  <div id="menu5" class="tab-pane fade">
    <h3>Créer Prestation</h3>
    <p>gestion de prestation</p>
  </div>
  <div id="menu6" class="tab-pane fade">
    <h3>Créer OM </h3>
    <p>OM</p>
  </div>
   <div id="menu7" class="tab-pane fade">
    <h3>Creer Doc</h3>
    <p>Documents</p>
  </div>
   

</div>

<!--  les modals -->

<!--  début  modal repoter Action (et donc toute la Mission)  -->
<style>
.modal-dialog.kbs { margin-top: 18%; } 
</style>
  

  <!-- model pour les report-->

  <div id="ReporterA" class="modal fade" role="dialog">
  <div class="modal-dialog kbs">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Reporter l action courante à une date ultérieure </h4>
      </div>
      <form   action ="{{ url('/traitementsBoutonsActions/'.$Action->Mission->dossier->id.'/'.$Action->Mission->id.'/'.$Action->id.'/3')}}">
      <div class="modal-body">
        <p>
      <div class="form-group">
        <?php $da = (new \DateTime())->modify('-1 Hour')->format('Y-m-d\TH:i'); ?>

      <label for="datereport" style="display: inline-block;  text-align: left; width:200px;">Saisissez la date de report:</label>
      <input id="datereport" type="datetime-local" value="<?php echo $da ?>" class="form-control" style="width:95%;  text-align: right;" name="datereport"/>
      </div>

      <div class="form-group">
        

      <!--<label for="objetrappel" style="display: inline-block;  text-align: left; width:200px;">Saisissez l'objet de rappel :</label>
      <input id="objetrappel" type="text" value="" class="form-control" style="width:95%;  text-align: right;" name="objetrappel"/>-->
      </div>


        </p>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-default" >Reporter</button>

      </form>
        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
      </div>
    </div>

  </div>

 </div>

     <!-- model pour les report-->

 <div id="RappelerA" class="modal fade" role="dialog">
  <div class="modal-dialog kbs">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Créer Rappel pour l'attente de réponse</h4>
      </div>
      <form   action ="{{ url('/traitementsBoutonsActions/'.$Action->Mission->dossier->id.'/'.$Action->Mission->id.'/'.$Action->id.'/4')}}">
      <div class="modal-body">
        <p>
      <div class="form-group">
        <?php $da = (new \DateTime())->modify('-1 Hour')->format('Y-m-d\TH:i'); ?>

      <label for="daterappel" style="display: inline-block;  text-align: left; width:200px;">Saisissez la date de report:</label>
      <input id="daterappel" type="datetime-local" value="<?php echo $da ?>" class="form-control" style="width:95%;  text-align: right;" name="daterappel"/>
      </div>

      <div class="form-group">
        

     <!-- <label for="objetrappel" style="display: inline-block;  text-align: left; width:200px;">Saisissez l'objet de rappel :</label>
      <input id="objetrappel" type="text" value="" class="form-control" style="width:95%;  text-align: right;" name="objetrappel"/>-->
      </div>


        </p>
      </div>
      <div class="modal-footer">
        <button id="idrappelerk" type="submit" class="btn btn-default" >Rappeler</button>

      </form>
        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
      </div>
    </div>

  </div>
 </div>

<script>

  /*$('#idrappelerk').on('click',function() {
    $(this).prop("disabled",true);
   });

 $('#idrappeler').click(function(e) { 
  $('#kbsoptionform').submit();
});*/

</script>



<!-- fin tous modals-->




<!--  scrpts de confirmation avavnt de quitter la page --> 

<script>
/*$(function() {
  $('a').click(function(e) {
     e.preventDefault();
     var url = $(this).attr('href');
     var urllocal=window.location;
     //alert(urllocal);
     if (url.indexOf('#') == -1)
      {

        if( urllocal != url)
        {


           if (!confirm("Enregister votre travail avant de quitter (Si vous voulez rester sur la meme page et enregistrer votre traveil cliquer sur OK)") ){ 
            
          window.location.replace(url);
          }
            
         
        }
     
      }
    
  });
});*/


/*$(function() {
  $('a').click(function(e) {
     e.preventDefault();
     var url = $(this).attr('href');
     $.ajax(....)
        .done(function() {
           window.location = url;
        });
        .fail(function(r) {
           alert('unable to save data');
        });
  });
});*/

 </script>

<!-- script pour mettre à jour le titre de panel en milieu-->

   <script>
  
   $("#kbspaneltitle").append('<?php if($Action) {  echo 'Mission : '.$Action->Mission->titre.' | Action : '.$Action->titre ; }  ?>');
  </script>
<!-- enregistrer commentaires sans bouton -->
<!-- <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>-->
<script>
    var timeoutId; 
    function changing(elm) {

      if (timeoutId) clearTimeout(timeoutId);

        timeoutId = setTimeout(function(){ 
      //alert("changement");
        var champ=elm.id;
       // alert(champ);
        var val =document.getElementById(champ).value;
       // alert(val);
        //  var type = $('#type').val();
        //var donnees = $("#formcoments").serialize();
        var donnees = $("#kbsoptionform").serialize();
        
        $.ajax({
            url: "{{ url('dossier/Mission/TraitercommentActionAjax/'.$Action->Mission->dossier->id.'/'.$Action->Mission->id.'/'.$Action->id)}}",
            method: "GET",
            data : donnees,
            success: function (data) {
                $('#comenttitle').animate({
                    opacity: '0.3',
                });
                $('#comenttitle').animate({
                    opacity: '1',
                });

               // location.reload();

            }
        });

         }, 3000);

    }

  </script>



  <script>
/*
var idAction;
var dataAction;
var iddropdownA;
var hrefidAcheverA;

    setInterval(function(){ //alert("Hello"); 
     
    $.ajax({
       url : '{{ url('/') }}'+'/getActionAjaxModal',
       type : 'GET',
       dataType : 'html', // On désire recevoir du HTML
       success : function(data){ // code_html contient le HTML renvoyé
           //alert (data);

           if(data)
           {

             // alert ("des nouvelles notes sont activées");
              //$("#contenuNotes").prepend(data);
              var sound = document.getElementById("audiokbs");
              sound.setAttribute('src', "{{URL::asset('public/media/point.mp3')}}");
              sound.play();

             // alertify.alert("Note","Une nouvelle note est activée").show();

             $("#contenuActionsModal").empty().append(data);
             iddropdown=jQuery(data).find('.dropdown').attr("id");
             $("#"+iddropdown).hide();
             $("#hiddenreporterAct").hide();
            
              
             $("#myActionModalReporter1").modal('show');          
             idAction=jQuery(data).find('.rowkbs').attr("id");
             dataAction=jQuery(data).find('#'+idAction).html();
             hrefidAcheverA=jQuery(data).find('#idAchever').attr("href");

            
           }
       }
    });
   


}, 10000);

    $(document).ready(function() {
    
    $(document).on("click","#actionOngletaj",function() {
      //alert(datakbs);
      $("#contenuActions").prepend(dataAction);
      $("#"+iddropdown).show();
    

        $('#actiontabs a[href="#Missionstab"]').trigger('click');


    });

  

     $(document).on("click","#reporterHideA",function() {
       
       $("#hiddenreporterA").toggle();  


    });

      });

*/
  </script>

<div class="modal fade" id="myActionModalReporter1" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content" id="contenuActionsModal">
       
      </div>
      
    </div>
</div>


<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <p  id="conff"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="myModalk" class="modal fade" role="dialog"></div>


<!-- model pour délégation des missions-->

<div class="modal fade" id="modalDelegMiss" role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <form  method="post" action="{{ route('Deleguer.Mission') }}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModal2">Déléguer la mission courante</h5>

            </div>
            <div class="modal-body">
                <div class="card-body">

                    <div class="form-group">
                        
                        
                            {{ csrf_field() }}
                            <input id="MissDeldossid" name="MissDeldossid" type="hidden" value="{{$Action->Mission->dossier->id}}">
                            <input id="delegMissid" name="delegMissid" type="hidden" value="{{$Action->Mission->id}}">

                            <input id="affecteurmiss" name="affecteurmiss" type="hidden" value="{{ Auth::user()->id}}">
                            <input id="statdoss" name="statdoss" type="hidden" value="existant">

                            <div class="form-group " >
                                <div class=" row  ">
                                    <div class="form-group mar-20">
                                        <label for="agent" class="control-label" style="padding-right: 20px">Agent</label>
                                        <select id="agent" name="agent" class="form-control select2" style="width: 230px">
                                            <option value="Select">Selectionner</option>
                                            <?php $agents = App\User::get(); ?>
                                           
                                                @foreach ($agents as $agt)
                                                <?php if (!empty ($agentname)) { ?>
                                                @if ($agentname["id"] == $agt["id"])
                                                    <option value={{ $agt["id"] }} selected >{{ $agt["name"] }}</option>
                                                @else
                                                    <option value={{ $agt["id"] }} >{{ $agt["name"] }}</option>
                                                @endif
                                                
                                                <?php }
                                                else
                                                      {  echo '<option value='.$agt["id"] .' >'.$agt["name"].'</option>';}
                                                ?>
                                                @endforeach    
                                        </select>
                                    </div>
                                </div>
                            </div>
                      

                    </div>


                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="submit" id="attribdoss" class="btn btn-primary">Déleguer Mission</button>
            </div>
        </div>
          </form>
    </div>
</div>


  



<script>

  /*var i=0;

  setInterval(function(){


         //$("#conff").html(i);
    // More code using $ as alias to jQuery
    $("#myModalk").modal('show').append("i");
   
  
 //alert("Hello"); 

 
     
   
   
i++;

}, 5000);*/

  </script>
  <script>

  $('#BouFaiAct').on('click',function() {
   $('#NumBouton').val("1");
   });
  $('#BouIgnAct').on('click',function() {
   $('#NumBouton').val("2");
   });

  $('#BouRepAct').on('click',function() {
   $('#NumBouton').val("3");
   });

 $('#BouRapAct').click(function(e) { 
  $('#NumBouton').val("4");
});

 $('#BouRapOuRepAct').click(function(e) { 
  $('#DivRapOuRepAct').fadeToggle("slow");

 });

  $('#DivRapOuRepAct').hide();


/* bouton et div de délégation*/





</script>



 

@endsection










