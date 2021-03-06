
@extends('layouts.mainlayout')

@section('content')
   



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
     {{--@if(session()->has('messagekbsSucc'))
    <div class="alert alert-success">
       <center> <h4>{{ session()->get('messagekbsSucc') }}</h4></center>
    </div>
  @endif--}}

    @if(session()->has('messagekbsFail'))
    <div class="alert alert-danger" style="background-color:red">
       <center> <h4>{{ session()->get('messagekbsFail') }}</h4></center>
    </div>
  @endif
    


    <br>
     <div class="row">
     
     <div class="col-sm-2">


      <div class="btn-group">
          
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-envelope"></i> Email <i class="fa fa-angle-down"></i>
                </button>
                <ul class="dropdown-menu pull-right">
                    <li>
                  <a href="{{route('emails.envoimail',['id'=>$dossier->id,'type'=> 'client','prest'=> 0])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;"> Au Client </a>
                    </li>
                    <li>
                        <a href="{{route('emails.envoimail',['id'=>$dossier->id,'type'=> 'prestataire','prest'=> 0])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">Au Prestataire </a>
                    </li>
                    <li>
                <a href="{{route('emails.envoimail',['id'=>$dossier->id,'type'=> 'assure','prest'=> 0])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">A l Assuré </a>
                    </li>

                </ul>
     </div>&nbsp
     </div>
    <div class="col-sm-1">
      <!--<button class="btn btn-default" >sms</button>&nbsp-->
      <button type="button" class="btn btn-default"  >
                    <a style="color:black" href="{{action('EmailController@sms',$dossier->id)}}"> SMS</a>
                </button>
     </div>
     <div class="col-sm-2">
      <!--<button class="btn btn-default">téléphone </button>&nbsp;-->
       <button type="button" class="btn btn-default" >
              
                    Téléphone

                </button>
     </div>
     <div class="col-sm-2">
      <!--<button class="btn btn-default">fax</button>&nbsp-->
     <div class="btn-group">
       <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
         <i class="fa fa-envelope"></i>  Fax <i class="fa fa-angle-down"></i>
      </button>

         <ul class="dropdown-menu pull-right">
                    <li>
                        <a href="{{route('emails.envoifax',['id'=>$dossier->id,'type'=> 'client','prest'=> 0])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                            Au client </a>
                    </li>
                    <li>
                        <a href="{{route('emails.envoifax',['id'=>$dossier->id,'type'=> 'prestataire','prest'=> 0])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                            À l'intervenant </a>
                    </li>
                    <li>
                        <a href="{{route('emails.envoifax',['id'=>$dossier->id,'type'=> 'libre','prest'=> 0])}}" class="sendMail" data-dest="client" style="font-size:17px;height:30px;margin-bottom:5px;">
                            Libre </a>
                    </li>

                </ul>
    </div>
     </div>
     <div class="col-sm-1">
     <button type="button" class="btn btn-default" style="margin-right: 1px ;" >
                    <a style="color:black" href="{{url('dossiers/view/'.$dossier->id )}}"> Prest</a>&nbsp;
                </button>&nbsp;
     </div>
     <div class="col-sm-2">
      <button type="button" class="btn btn-default"  >
                    <a style="color:black"  href="{{url('dossiers/view/CreerDoc/'.$dossier->id.'/'.$Action->Mission->id )}}">DOC</a>
                </button>
     </div>
     <div class="col-sm-2">
      <button type="button" class="btn btn-default"  >
                    <a style="color:black"  href="{{url('dossiers/view/CreerOM/'.$dossier->id.'/'.$Action->Mission->id )}}"> OM</a>
                </button>
     </div>

    </div>
    <form id="kbsoptionform" action="{{ url('/traitementsBoutonsActions/'.$Action->Mission->dossier->id.'/'.$Action->Mission->id.'/'.$Action->id.'/1')}}" onSubmit="return confirm('Confirmez-vous votre action ?');"  method="post">
    
    <br><br>
      <div class="row">
     <div class="col-md-3">

          <button id="BouFaiAct" type="submit" class="btn btn-success" style="width: 200px;"> Fait </button>

         </div>

       <div class="col-md-1">
     
      </div>     
    
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
        
         <a class="btn btn-danger annulerMission" id="a{{$Action->Mission->id}}" style="width: 200px;" href=" javascript:void(0)" ><i class="fa fa-close"></i> Annuler la mission</a>
     </div>

   </div>
   <br>

     <div class="row">
      @if($Action->Mission->type_Mission==7) {{--ambulance--}} 
        @if($Action->ordre <=7 )
       <span style="color:red"> <h3> * <u><b>Date(s) spécifique(s) :</b></u></h3> </span>  <div style="padding-left: 10px;color:red"><h4><b> Vous devrez saisir les dates suivantes dans l'interface de description de mission dès qu'elles seront connues : <br> -  la date "début mission" (date départ base) (2ème partie OM Ambulance) <br> - la date/heure "arrivée à destination" (date/heure disponibilité prévisible) (2ème partie OM Ambulance) </b></h4></div>
       @endif 
     @endif
      @if($Action->Mission->type_Mission==6) {{--Taxi--}} 
        @if($Action->ordre <=5 )
       <span style="color:red"> <h3> * <u><b>Date(s) spécifique(s) :</b></u></h3> </span>  <div style="padding-left: 10px;color:red"><h4><b> Vous devrez saisir les dates suivantes dans l'interface de description de mission dès qu'elles seront connues : <br> -  la date "début mission" (date départ base) (2ème partie OM Taxi) <br> - la date/heure "arrivée à destination" (date/heure disponibilité prévisible) (2ème partie OM Taxi) </b></h4></div>
       @endif 
     @endif
       @if($Action->Mission->type_Mission==30) {{--rapatriement véhicule sur Cargo--}} 
       @if($Action->ordre <=25 )
       <span style="color:red"> <h3> *<u><b> Date(s) spécifique(s) :</b></u></h3> </span> <div style="padding-left: 10px;color:red"><h4><b> Vous devrez saisir les dates suivantes dans l'interface de description de mission dès qu'elles seront connues :<br> - la date prévue d'arrivée de remorqueur au port (date souhaitée arrivée) (dans 2ème partie OM remorquage)<br> - la date/heure de départ sur Cargo (2ème partie OM remorquage)</b></h4> </div> 
       @endif
      @endif
       @if($Action->Mission->type_Mission==26) {{--Ecsorte intern. fournie par MI--}} 
       @if($Action->ordre <=15 )
       <span style="color:red"> <h3>*<u><b> Date(s) spécifique(s) :</b></u></h3> </span> <div style="padding-left: 10px;color:red"><h4><b>Vous devrez saisir la  date suivante dans l'interface de description de mission dès qu'elle sera connue :<br> - la date/heure de décollage d'avion (Date/heure décollage vol)(OM Ambulance)</b></h4> </div> 
       @endif
      @endif
       @if($Action->Mission->type_Mission==27) {{--Rapatriement véhicule avec chauffeur accompagnateur--}} 
       @if($Action->ordre <=10 )
       <span style="color:red"> <h3> *<u><b> Date(s) spécifique(s) :</b></u></h3> </span> <div style="padding-left: 10px;color:red"><h4><b>Vous devrez saisir la date suivante dans l'interface de description de mission dès qu'elle sera connue :<br>- date/heure prévue d'arrivée au port (Date/heure RDV au port) (OM remorquage)</b></h4> </div> 
       @endif
      @endif
       @if($Action->Mission->type_Mission==12) {{--Dédouanement de pièces--}} 
        @if($Action->ordre <=6 )
       <span style="color:red"> <h3> *<u><b> Date(s) spécifique(s) :</b></u></h3> </span> <div style="padding-left: 10px;color:red"><h4><b> Vous devrez saisir la date suivante dans l'interface de description de mission dès qu'elle sera connue :<br>- date/heure du RDV prévu</b></h4> </div> 
      @endif
      @endif
       @if($Action->Mission->type_Mission==11) {{--consultation médicale--}} 
       @if($Action->ordre <=5 )
       <span style="color:red">  <h3> *<u><b> Date(s) spécifique(s) :</b></u></h3> </span> <div style="padding-left: 10px;color:red"><h4><b> Vous devrez saisir la date suivante dans l'interface de description de mission dès qu'elle sera connue:<br>- date/heure du RDV avec le médecin </b></h4> </div> 
      @endif
      @endif
       @if($Action->Mission->type_Mission==16) {{--Devis transport international sous assistance--}} 
       @if($Action->ordre <=8 )
       <span style="color:red"> <h3> *<u><b> Date(s) spécifique(s) :</b></u></h3> </span> <div style="padding-left: 10px;color:red"><h4><b> Vous devrez saisir la date suivante dans l'interface de description de mission dès qu'elle sera connue:<br>- date/heure de décollage (OM Ambulance / PEC Devis Transport sous assistance)</b></h4> </div>
       @endif 
      @endif
       @if($Action->Mission->type_Mission==18) {{--Demande d’evasan internationale--}} 
        @if($Action->ordre <= 7 )
       <span style="color:red">  <h3> *<u><b> Date(s) spécifique(s) :</b></u></h3> </span> <div style="padding-left: 10px;color:red"><h4><b> Vous devrez saisir la date suivante dans l'interface de description de mission dès qu'elle sera connue :<br>- date/heure prévue d’arrivée (date/heure atterrissage destination) (OM ambulance)</b></h4> </div> 
       @endif
      @endif
       @if($Action->Mission->type_Mission==19) {{--Demande d’evasan nationale--}} 
       @if($Action->ordre <= 7)
       <span style="color:red">  <h3> *<u><b> Date(s) spécifique(s) :</b></u></h3> </span> <div style="padding-left: 10px;color:red"><h4><b> Vous devrez saisir la date suivante dans l'interface de description de mission dès qu'elle sera connue :<br>- date/heure prévue d’arrivée (date/heure atterrissage destination) (OM ambulance / PEC Evasan Armée )</b></h4> </div> 
       @endif
      @endif
       @if($Action->Mission->type_Mission==22) {{--escorte de l étranger--}} 
        @if($Action->ordre <= 4)
       <span style="color:red">  <h3> *<u><b> Date(s) spécifique(s) :</b></u></h3> </span> <div style="padding-left: 10px;color:red"><h4><b> Vous devrez saisir la date suivante dans l'interface de description de mission dès qu'elle sera connue :<br>- date/heure d'arrivée du vol (OM Taxi)</b></h4> </div> @endif
      @endif
        @if($Action->Mission->type_Mission==32) {{--réservation hotel--}} 
        @if($Action->ordre <= 5)
       <span style="color:red"> <h3> *<u> <b>Date(s) spécifique(s) :</b></u></h3> </span> <div style="padding-left: 10px;color:red"><h4><b> Vous devrez saisir la date suivante dans l'interface de description de mission dès qu'elle sera connue :<br>- date/heure fin de séjour (PEC hôtel)</b></h4> </div> 
      @endif
      @endif
      @if($Action->Mission->type_Mission==35) {{--organisation visite médicale--}} 
      @if($Action->ordre <= 5)
       <span style="color:red"><h3> *<u><b> Date(s) spécifique(s) :</b></u></h3> </span> <div style="padding-left: 10px;color:red"><h4><b> Vous devrez saisir la date suivante dans l'interface de description de mission dès qu'elle sera connue :<br>- date/heure du RDV </b></h4> </div> 
     @endif
      @endif
       @if($Action->Mission->type_Mission==39) {{--Expertise--}} 
       @if($Action->ordre <= 5)
       <span style="color:red"> <h3> *<u><b> Date(s) spécifique(s) :</b></u></h3> </span> <div style="padding-left: 10px;color:red"><h4><b> Vous devrez saisir la date suivante dans l'interface de description de mission dès qu'elle sera connue :<br>- date/heure du RDV (PEC expertise)</b></h4> </div> 
      @endif
      @endif
      @if($Action->Mission->type_Mission==43) {{--rapatriement de véhicule sur ferry--}} 
      @if($Action->ordre <= 10)
       <span style="color:red"> <h3> *<u><b> Date(s) spécifique(s) :</b></u></h3> </span> <div style="padding-left: 10px;color:red"><h4><b> Vous devrez saisir la date suivante dans l'interface de description de mission dès qu'elle sera connue :<br>- date/heure prévue de départ du bâteau (OM remorquage)</b></h4> </div> 
      @endif
      @endif
         @if($Action->Mission->type_Mission==45) {{--réparation véhicule--}}
         @if($Action->ordre <= 7) 
       <span style="color:red"><h3> *<u><b> Date(s) spécifique(s) :</b></u></h3> </span> <div style="padding-left: 10px;color:red"><h4><b> Vous devrez saisir la date suivante dans l'interface de description de mission dès qu'elle sera connue :<br>- date/heure du RDV (de passage assuré)</b> </h4> </div> 
      @endif
      @endif
       @if($Action->Mission->type_Mission==44) {{--remorquage--}} 
       @if($Action->ordre <= 9) 
       <span style="color:red"><h3>*<u><b> Date(s) spécifique(s) :</b></u></h3> </span> <div style="padding-left: 10px;color:red"><h4><b> Vous devrez saisir les dates suivantes dans l'interface de description de mission dès qu'elles seront connues :<br>- date/heure départ pour mission (Date/Heure départ base)  (OM remorquage (2ème partie)) <br> - date/heure fin de mission (Date/Heure dispo prévisible) (OM remorquage (2ème partie))</b></h4> </div> 
      @endif 
      @endif
       @if($Action->Mission->type_Mission==46) {{--location voiture--}} 
        @if($Action->ordre <= 5) 
       <span style="color:red"> <h3>*<u><b> Date(s) spécifique(s) :</b></u></h3> </span> <div style="padding-left: 10px;color:red"><h4><b> Vous devrez saisir la date suivante dans l'interface de description de mission dès qu'elle sera connue :<br>- date/heure de fin de location (PEC location) </b></h4> </div> 
      @endif
      @endif
      
     </div>

  
    <br>
    <h3><u>Étapes à réaliser:</u></h3>
    <br>
    <p><textarea readonly style="padding:10px 10px 10px 10px ;border:1px solid #00aced;WIDTH: 100%; height:170px;  font-size: 18px;">{{$Action->descrip}}</textarea>
    </p>
     <br>
     <div>

   
      <h4 id="comenttitle">Vous pouvez ajouter un ou plusieurs commentaires :</h4>
      <br>

      

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

  
    </div>
    <br><br>
    <!-- postion des otions et les boutons radios -->
    
    <div class="row">


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
    <li class="active"><a data-toggle="tab" href="#OngRapAct">Mise en attente (Rappel) <i class="fa fa-2x fa-history"> </i> </a></li>
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
        <?php $da = (new \DateTime())->format('Y-m-d\TH:i'); ?>
       <input id="daterappel" type="datetime-local" value="<?php echo $da ?>" class="form-control" style="width:50%;  text-align: left;" name="daterappel"/>
      </div>
      <br><br>
      <div class="row">
        <button id="BouRapAct"  class="btn btn-success" type="submit" style="flow : left; margin-left: 120px"> Mise en attente</button>
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
        <?php $da = (new \DateTime())->format('Y-m-d\TH:i'); ?>
       <input id="datereport" type="datetime-local" value="<?php echo $da ?>" class="form-control" style="width:50%;  text-align: right; float: right !important; margin-right: 20px;"  name="datereport"/>
      </div>
      <br><br>
      <div class="row">
        <button id="BouRepAct"  class="btn btn-success" type="submit" style="float: right; margin-right: 130px;"> Reporter Action</button> &nbsp  &nbsp &nbsp
      </div>
    
      </p>
    </div>
    </div>




 </div><!--fin  onglet reporter/mise en attente-->
       <br><br>


       <!-- gestion des dates spécifiques-->

    

    <!-- fin gestion des dates spécifiques-->

       <br><br>

    <!--position des boutons -->
  
   <br><br>
    <div class="panel panel-default">
  <div class="panel-heading">Délégation</div>
  <div class="panel-body">


    <div class="row" style=" padding-top: 50px;">
        
      <div class="col-md-5">
         <!--<button id="BouDeleAction" type="button"class="btn btn-primary disabled" style="width: 250px;"> Déléguer l'action</button>-->
         <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#modalDelegAct">Déléguer l'action courante</button>
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

<br> <br>
    <div class="row">
    
        <!--<button  type="button" style="float : right;" class="btn btn-info btn-lg" data-toggle="modal" data-target="#ModalWorkLies">Créer une sous mission</button>-->
    </div>

<div>
        
   

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
      <form  id="formTA" action ="{{ url('/traitementsBoutonsActions/'.$Action->Mission->dossier->id.'/'.$Action->Mission->id.'/'.$Action->id.'/3')}}" onsubmit="return false">
      <div class="modal-body">
        <p>
      <div class="form-group">
        <?php $da = (new \DateTime())->format('Y-m-d\TH:i'); ?>

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
        <?php $da = (new \DateTime())->format('Y-m-d\TH:i'); ?>

      <label for="daterappel" style="display: inline-block;  text-align: left; width:200px;">Saisissez la date de report:</label>
      <input id="daterappel" type="datetime-local" value="<?php echo $da ?>" class="form-control" style="width:95%;  text-align: right;" name="daterappel"/>
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


<!-- fin tous modals-->




<!--  scrpts de confirmation avavnt de quitter la page --> 


<!-- script pour mettre à jour le titre de panel en milieu-->

   <script>
  
   $("#kbspaneltitle").append('<?php if($Action) {  echo 'Action: '.$Action->titre.' | Mission : '.$Action->type_Mission.'<br> (Extrait de mission  :  '.$Action->Mission->titre.'  ) |<a  href="'.action('DossiersController@view',$dossier->id).'" > <u>Dossier : '.$Action->Mission->dossier->reference_medic.' - '.$Action->Mission->dossier->subscriber_name.' '.$Action->Mission->dossier->subscriber_lastname.'</u></a><br><div style ="overflow: hidden ; width: 100%;"></div>' ; }  ?>');
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

         }, 2000);

    }

  </script>

  <!-- gestion des dates spécifiques-->

  <script>

$('#MajDateSpec').click(function(){
        var idmissionDateSpec = $('#idmissionDateSpec').val();
        var NomTypeDateSpec = $('#NomTypeDateSpec').val();
        var dateSpec = $('#dateSpec').val();
      
            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('Action.dateSpecifique') }}",
                method:"POST",
                data:{idmissionDateSpec:idmissionDateSpec,NomTypeDateSpec:NomTypeDateSpec,dateSpec:dateSpec,_token:_token},
                success:function(data){

                    //  idspandateAssNonAss
                   // window.location =data;

                   if(data=='date spécifique invalide')
                   {
                       alert(data);
                   }
                   if(data=='date affectée')
                   {
                       alert(data);
                 
                         $('#idspandateAssNonAss').css('color','green');
                                              
                         $('#idspandateAssNonAss').text('Oui,date affectée');
                          //$('#idspandateAssNonAss').css('text-decoration','blink'); 

                   }

                   

                }
            });
       
    });
     

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
                                                @if($agt->isOnline())
                                                <?php if (!empty ($agentname)) { ?>
                                                @if ($agentname["id"] == $agt["id"])
                                                    <option value={{ $agt["id"] }} selected >{{ $agt["name"] }} {{ $agt["lastname"] }}</option>
                                                @else
                                                    <option value={{ $agt["id"] }} >{{ $agt["name"] }} {{ $agt["lastname"] }}</option>
                                                @endif
                                                
                                                <?php }
                                                else
                                                      {  echo '<option value='.$agt["id"] .' >'.$agt["name"].' '.$agt["lastname"].'</option>';}
                                                ?>
                                                @endif
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


<!-- model pour délégation des actions-->

<div class="modal fade" id="modalDelegAct" role="dialog" aria-labelledby="exampleModal2" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <form  method="post" action="{{ route('Deleguer.Action') }}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModal2">Déléguer l'action courante</h5>

            </div>
            <div class="modal-body">
                <div class="card-body">

                    <div class="form-group">
                        
                        
                            {{ csrf_field() }}
                            <input id="MissDeldossid" name="MissDeldossid" type="hidden" value="{{$Action->Mission->dossier->id}}">
                            <input id="delegMissid" name="delegMissid" type="hidden" value="{{$Action->Mission->id}}">
                            <input id="delegActid" name="delegActid" type="hidden" value="{{$Action->id}}">

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
                                                @if($agt->isOnline())
                                                <?php if (!empty ($agentname)) { ?>
                                                @if ($agentname["id"] == $agt["id"])
                                                    <option value={{ $agt["id"] }} selected >{{ $agt["name"] }} {{ $agt["lastname"] }}</option>
                                                @else
                                                    <option value={{ $agt["id"] }} >{{ $agt["name"] }} {{ $agt["lastname"] }}</option>
                                                @endif
                                                
                                                <?php }
                                                else
                                                      {  echo '<option value='.$agt["id"] .' >'.$agt["name"].' '.$agt["lastname"].'</option>';}
                                                ?>
                                                @endif
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
                <button type="submit" id="attribdoss" class="btn btn-primary">Déleguer l'action</button>
            </div>
        </div>
          </form>
    </div>
</div>

<!--modal pour créer les missions liées-->
<div id="ModalWorkLies" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->

     <form  id="idFormCreationMissionLie" method="POST" action="#" style="padding-top:30px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Créer une sous mission </h4>
      </div>
      <div class="modal-body"><!-- début création mission liée-->

                   
                                      <div class="form-group">
                                           {{ csrf_field() }}

                                          <input id="idMissionMere" type="hidden" value="{{$Action->Mission->id}}" class="form-control" style="width:95%;  text-align: left;" name="idMissionMere"/> 

                                        <div class="row">
                                             <div class="col-md-3" style="padding-top:5px"> <label  style=" ;  text-align: left; width: 55px;">Extrait:</label></div>
                                             <div class="col-md-9"><input id="titreml" type="text" class="form-control" style="width:95%;  text-align: left !important;" name="titreml"/></div>
                                       </div>
                                       <br>
                                        <!-- input pour l'autocomplete type Mission -->
                                          <div class="form-group">

                                               <div class="row">
                                                <div class="col-md-3" style="padding-top:5px">  <label for="typeactauto" style="display: inline-block;  text-align: left; width: 55px;">Type:</label></div>
                                                <div class="col-md-9" style=" margin-left: -1px ; "> 
                                           


                                          <select id="typeMissLieauto" name="typeMissLieauto" class="form-control select2" style="width:95%; border: 1px solid #ccc; height: 32px">
                                            <option value="">Sélectionner</option>
                                         @foreach( $typesMissions2 as $c) 

                                                <option value="{{$c->nom_type_Mission}}">{{$c->nom_type_Mission}} </option>

                                         @endforeach


                                         </select>

                                                 <script> 
                                                  $(document).ready(function(){

                                                  $(document).on("change","#typeMissLieauto",function() {

                                                if ($(this).val()=="Transports terrestres effectué par entité-sœur MMS" || $(this).val()=="Transport terrestre effectué par prestataire externe")
                                                {
                                                  //alert($(this).val());
                                                  $("#idDateSpecifique").empty().append("<span style='color:red'> la date ci dessous indique la date de départ d'avion <span>");
                                                }
                                                else
                                                {

                                                  $("#idDateSpecifiqueml").empty();

                                                };                                                  ;

                                                });
                                               
                                                     $("#typeMissLieauto").select2();
                                                                                                   
                                                 });
                                                 

                                                  </script>

                                                </div>
                                            </div>

                                          </div>
                                          <div class="form-group">
                                           <div class="row"> 
                                            <div class="col-md-3">
                                            </div>
                                            <div class="col-md-9">
                                            <div id="idDateSpecifiqueml"> </div>
                                            </div>
                                          </div>

                                        </div>
                                         <div class="form-group">
                                            <?php  $da = (new \DateTime())->format('Y-m-d\TH:i');// $da= date('Y-m-d\TH:m'); ?>

                                                <div class="row">
                                                    <div class="col-md-3" style="padding-top:5px">  <label for="datedeb" style="display: inline-block;  text-align: left; width: 55px;">Date:</label></div>
                                                    <div class="col-md-9"> <input id="datedebml" type="datetime-local" value="<?php echo $da ?>" class="form-control" style="width:95%;  text-align: left;" name="datedebml"/></div>
                                                </div>
                                         </div>
                                  
                                        </br>
                                       <div class="row">
                                             <div class="col-md-3" style="padding-top:5px"> <label  style=" ;  text-align: left; width: 55px;">Commentaire:</label></div>
                                             <div class="col-md-9"><textarea id="commentaireml" class="form-control" style="width:95%;  text-align: left !important;" name="commentaireml"></textarea></div>
                                       </div>
                                      </div>

                                           


                                      <div class="form-group">

                                           <?php if(isset($dossier)) {  ?>
                                          
                                        
                                          <input id="dossierIDml" type="hidden" class="form-control" value="{{$dossier->id}}" name="dossierIDml"/>
                                          <input id="hreftopwindowml" type="hidden" class="form-control" value="" name="hreftopwindowml"/>
                                            

                                    

                                          <?php } else {  ?>
                                               <div class="row">

                                               <div class="col-md-3" style="padding-top:5px">     <label for="typeact" style="display: inline-block;  text-align: right; width: 40px;">Réf dossier</label></div>
                                                  <div class="col-md-9"> <input id="dossier" type="text" class="form-control" value="" name="dossier"/></div>
                                               </div>

                                           <?php } ?>

                                      </div>
                                       <!--<button  type="submit"  class="btn btn-success">Ajouter</button>-->
                                        <br><br>
                                       
                                        <br><br><br>
                                       <!-- <button  id="idFinAjoutMiss" type="button"  class="btn btn-danger">Fin ajout de missions</button>-->

                                     <!-- <button id="add"  class="btn btn-primary">Ajax Add</button>-->
                                 
       
      </div><!-- fin creation mission liée -->
      <div class="modal-footer">
       <button  id="idAjoutMissLie" type="button"  class="btn btn-success">Ajouter la mission</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
      </div>
    </div>
  </form>

  </div>
</div>


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

  $('#DivRapOuRepAct').show();

 

  $(document).on("submit","#formTA",function( event ) {
  // if ( $( "input:first" ).val() === "correct" ) {
  //   $( "span" ).text( "Validated..." ).show();
  //   return;
  // }
 alert ("not allowed");
  //$( "span" ).text( "Not valid!" ).show().fadeOut( 1000 );
  event.preventDefault();
});


</script>

 <script>

  $("#idAjoutMissLie").click(function(e){ // On sélectionne le formulaire par son identifiant
   // e.preventDefault(); // Le navigateur ne peut pas envoyer le formulaire
     //alert('ok');

     $("#idAjoutMissLie").prop('disabled', true);

     var en=true;

     if(!$('#idFormCreationMissionLie #titreml').val())
     {

      alert('vous devez remplir le champs extrait');
      en=false;

     }

     if(!$('#idFormCreationMissionLie #typeMissLieauto').val())
     {

      alert('vous devez sélectionner le type de mission');
      en=false;

     }
  

    if(!$('#idFormCreationMissionLie #dossierIDml').val())
     {

      alert('vous devez sélectionner un dossier pour créer une mission ou créer une mission à partir d\'un email');
      en=false;

     }
     
 

   if(en==true)
   {
    var donnees = $('#idFormCreationMissionLie').serialize(); // On crée une variable content le formulaire sérialisé
    if(donnees)
    {
    alert(donnees);
    }
    var _token = $('input[name="_token"]').val();
    $.ajax({

           url:"{{ route('Mission.StoreMissionLieByAjax') }}",
           method:"POST",
           data : donnees,
           success:function(data){

         
                alert(data);
                 $('#idFormCreationMissionLie #typeMissLieauto').val('');
                 //$('#idFormCreationMission #typeMissauto option:eq(1)').prop('selected', true);
                //$('#idFormCreationMission #typeMissauto').text('Sélectionner');
                $('#idFormCreationMissionLie #titreml').val('');
                   

                },
            error: function(jqXHR, textStatus, errorThrown) {

              alert('erreur lors de création de la mission');


            }

   
    });
  }

   $("#idAjoutMissLie").prop('disabled', false);

});

</script>

@endsection










