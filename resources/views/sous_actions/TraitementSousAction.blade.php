
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
  <li><a data-toggle="tab" href="#menu1">Email</a></li>
  <li><a data-toggle="tab" href="#menu2">SMS</a></li>
  <li><a data-toggle="tab" href="#menu3">Appel Téléphonique</a></li>
  <li><a data-toggle="tab" href="#menu4">Fax</a></li>
  <li><a data-toggle="tab" href="#menu5">Prestation</a></li>
  <li><a data-toggle="tab" href="#menu6">Créer document</a></li>
  
</ul>

<div class="tab-content">
  <!-- début onglet instructions------------------------------------------------------------------ -->
  <div id="home" class="tab-pane fade in active">

   <!--<form action="{{ url('dossier/action/Traitercommentsousaction/'.$sousaction->action->dossier->id.'/'.$sousaction->action->id.'/'.$sousaction->id)}}">-->

    <br>
    <div class="row">
      <div class="col-md-10">
    <h3 style="margin-top:15px;">Étapes à réaliser:</h3>
      </div>

    <div class="col-md-2">
    <button class="btn " style="float:right;margin-right:5px;" title="Reporter la sous-action courante à une date ultérieure" data-toggle="modal" data-target="#ReporterSA"  >Reporter<br>
     <i style="margin-top:8px" class="fa fa-2x fa-history"> </i></button>
   </div>
     </div>
    <br>
    <p><textarea readonly style="padding:10px 10px 10px 10px ;border:1px solid #00aced;WIDTH: 100%; height:100px;  font-size: 18px;">{{$sousaction->descrip}}</textarea>
    </p>
     <br>
     <div>
      <h4 id="comenttitle">Vous pouvez ajouter un ou plusieurs commentaires :</h4>
      <br>

      <form  id="formcoments" action="{{ url('dossier/action/Traitercommentsousaction/'.$sousaction->action->dossier->id.'/'.$sousaction->action->id.'/'.$sousaction->id)}}">

         {{ csrf_field() }}
       <div class="com_wrapper">
        <!-- bloc pour commentaires existants-->
        <?php $nbcomm=0;?>
        
             @if($sousaction->comment1)
             <?php $nbcomm++;?>
            <div>
            <input autocomplete="off"  type="text" style="width:80%"  size="70" onchange="changing(this)"  id="comment1" name="comment1" value="<?php echo $sousaction->comment1 ?>"/>
            <a href="javascript:void(0);" class="com_button" title="Ajouter commentaire"></a>
            </div>
            <br>
             @endif
             @if($sousaction->comment2)
             <?php $nbcomm++;?>
            <div>
            <input autocomplete="off"  type="text"  style="width:80%"  size="70" onchange="changing(this)"  id="comment2" name="comment2" value="<?php echo $sousaction->comment2 ?>"/>
            <a href="javascript:void(0);" class="com_button" title="Ajouter commentaire"></a>
            </div>
             <br>
             @endif
             @if($sousaction->comment3)
             <?php $nbcomm++;?>
              <div>
            <input autocomplete="off" type="text" style="width:80%"  size="70" onchange="changing(this)" id ="comment3" name="comment3" value="<?php echo $sousaction->comment3 ?>"/>
            <a href="javascript:void(0);" class="com_button" title="Ajouter commentaire"></a>
            </div>
             <br>
             @endif

        
      <?php if($nbcomm<3){?>
       <div>
            <input autocomplete="off"  type="text" onchange="changing(this)" size="70" style="width:80%" id="field_name[]" name="field_name[]" value=""/>
            <a href="javascript:void(0);" class="com_button" title="Ajouter commentaire"><?php if($nbcomm<=1){ ?><img width="26" height="26" src="{{ asset('public/img/plus.png') }}"/> <?php } ?></a>
      </div> 
    <?php } ?>
    </div>

    
           <!-- <center><button  id="Enrcommentaire" type="submit" >Enregistrer Commentaires</button></center>-->


    </div>
    <br><br>
    <div class="row">

      <?php  if(isset($sousaction)) { if($sousaction->ordre < $sousaction->action->sousactions->count()){ ?>
      <div class="col-md-6">
          <a href="{{ url('dossier/action/EnregistrerEtAllerSuivante/'.$sousaction->action->dossier->id.'/'.$sousaction->action->id.'/'.$sousaction->id)}}" class="btn btn-primary"><i class="fa fa-check"></i>  Marquer la sous-action comme faite et <i class="fa fa-arrow-right"></i> aller à la suivante</a>

      </div>

    <?php } else {?>

      <div class="col-md-6">
      <a href="{{ url('dossier/action/FinaliserAction/'.$sousaction->action->dossier->id.'/'.$sousaction->action->id.'/'.$sousaction->id)}}" class="btn btn-primary"><i class="fa fa-check-circle"></i>   Marquer la sous-action comme faite </a>

      </div>

    <?php }} ?>


       <div class="col-md-1">
     

      </div>
      
      <div class="col-md-5">
        
         <a class="btn btn-danger" href="{{ url('dossier/action/AnnulerEtAllerSuivante/'.$sousaction->action->dossier->id.'/'.$sousaction->action->id.'/'.$sousaction->id)}}" ><i class="fa fa-close"></i> Annuler la sous-action et <i class="fa fa-arrow-right"></i> aller à la suivante</a>

     </div>

   </div>
       <br>
     <div class="row">

      <div class="col-md-6">
        <?php  if(isset($sousaction)) { if($sousaction->ordre > 1){ ?>
         <a class="btn btn-primary" href="{{ url('dossier/action/EnregistrerEtAllerPrecedente/'.$sousaction->action->dossier->id.'/'.$sousaction->action->id.'/'.$sousaction->id)}}"> <i class="fa fa-arrow-left"></i>  Aller à la sous-action précédente</a>
       <?php }}?>
      </div>
      <div class="col-md-1">
      </div>

      <div class="col-md-5">

        <a class="btn btn-danger" href="{{ url('dossier/action/AnnulerActionCourante/'.$sousaction->action->dossier->id.'/'.$sousaction->action->id.'/'.$sousaction->id)}}" ><i class="fa fa-close"></i>  Annuler l'action courante</a>
      </div>
     
     </div>

    </form>
  </div>
  <script type="text/javascript">
     $(document).ready(function(){
    var maxField = <?php echo (3-$nbcomm) ?>; //Input fields increment limitation
    var comButton = $('.com_button'); //Add button selector
    var comwrapper = $('.com_wrapper'); //Input field wrapper
    var comfieldHTML = '<div><br><input autocomplete="off"  style="width:80%" size="70" type="text" onchange="changing(this)" id="field_name[]"  name="field_name[]" value=""/><a href="javascript:void(0);" class="comremove_button"> <img width="26" height="26" src="{{ asset('public/img/moin.png') }}"/></a><br></div> '; //New input field html
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
  <div id="menu1" class="tab-pane fade">
    <h3>Email</h3>
    <p><!-- début traitement email------------------------------------------------------------------ -->

  <link href="{{ asset('public/css/summernote.css') }}" rel="stylesheet" media="screen" />

    <div class="row">
        <div class="col-sm-3 col-md-3">
            <?php use \App\Http\Controllers\EnvoyesController;     ?>
            <?php use \App\Http\Controllers\EntreesController;     ?>            <div class="panel">
                <div class="panel-body pan">
                    <ul class="nav nav-pills nav-stacked">
                        <li class="active">
                            <a  href="{{ route('emails.sending') }}">
                                <span class="badge pull-right"></span>
                                <i class="fa fa-inbox fa-fw mrs"></i>
                                Rédiger un email
                            </a>
                        </li>
                        <li >
                            <a   href="{{ route('boite') }}">
                                <span class="badge pull-right"></span>
                                <i class="fa fa-envelope-square fa-fw mrs"></i>
                                Boîte de réception
                            </a>
                        </li>
                        <li class="">
                            <a   href="{{ route('envoyes') }}">
                                <span class="badge pull-right"><?php  echo EnvoyesController::countenvoyes(); ?></span>
                                <i class="fa fa-paper-plane fa-fw mrs"></i>
                                Envoyées
                            </a>
                        </li>
                        <li class="">
                            <a   href="{{ route('envoyes.brouillons') }}">
                                <span class="badge badge-orange pull-right"><?php echo EnvoyesController::countbrouillons(); ?></span>
                                <i class="fa fa-edit fa-fw mrs"></i>
                                Brouillons
                            </a>
                        </li>
                        <li class="">
                            <a   href="{{ route('entrees.archive') }}">
                                <span class="badge badge-orange pull-right"><?php echo EntreesController::countarchives(); ?></span>
                                <i class="fa fa-archive fa-fw mrs"></i>
                                Archive
                            </a>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
        <div class="col-lg-9 ">

<form method="post" action="{{action('EmailController@send')}}"  enctype="multipart/form-data">
    <div class="form-group">
        {{ csrf_field() }}
        <label for="destinataire">destinataire:</label>
        <div class="row">
        <div class="col-md-10">
            <input id="destinataire" type="email" class="form-control" name="destinataire" required />
        </div>
            <div class="col-md-2">
                <i id="emailso" onclick="visibilite('autres')" class="fa fa-lg fa-arrow-circle-down" style="margin-right:10px"></i>

            </div>
        </div>
    </div>
    <div class="form-group" style="margin-top:10px;">
        <div id="autres" class="row"  style="display:none " >
            <div class="col-md-1">
                <label for="cc">CC:</label>
            </div>
            <div class="col-md-4">
                <input id="cc" type="text" class="form-control" name="cc"  />
            </div>
         <div class="col-md-1">
            <label for="cci">CCI:</label>
        </div>
        <div class="col-md-4">
            <input id="cci" type="text" class="form-control" name="cci"  />
        </div>
    </div>
    </div>

    <div class="form-group">
        <label for="sujet">sujet :</label>
        <input id="sujet" type="text" class="form-control" name="sujet" required/>
    </div>
    <div class="form-group ">
        <label for="contenu">contenu:</label>
       <div class="editor" >
        <textarea id="summernote" style="min-height: 280px;" id="contenu" type="text"  class="textarea tex-com" placeholder="Contenu de l'email ici" name="contenu" required ></textarea>
       </div>
    </div>

    <div class="form-group form-group-default">
        <label>Attachements</label>
        <input class="btn btn-danger fileinput-button" id="file" type="file" name="files[]"   multiple>
    </div>
    {!! NoCaptcha::display() !!}

    <button  type="submit"  class="btn btn-md  btn-primary btn_margin_top"><i class="fa fa-paper-plane" aria-hidden="true"></i> Envoyer</button>
    <a id="broullion"   disabled class="btn btn-md btn-success btn_margin_top"><i class="fa fa-archive" aria-hidden="true"></i> Brouillon</a>
 </form>

        </div>
    </div>

    <script type="text/javascript">

    function visibilite(divId)
    {
        //divPrecedent.style.display='none';
        divPrecedent=document.getElementById(divId);
        if(divPrecedent.style.display==='none')
        {divPrecedent.style.display='block';   }
        else
        {divPrecedent.style.display='none';     }
    }

    $(document).ready(function(){



        $('#file').change(function(){
            var fp = $("#file");
            var lg = fp[0].files.length; // get length
            var items = fp[0].files;
            var fileSize = 0;

            if (lg > 0) {
                for (var i = 0; i < lg; i++) {
                    fileSize = fileSize+items[i].size; // get file size
                }
                if(fileSize > 12000000 ) {
                    alert('La taille des fichiers ne doit pas dépasser 12 MB');
                    $('#file').val('');
                }
            }
        });

        // activation bouton Brouillon
        $('#destinataire').change(function() {
            var destinataire = $('#destinataire').val();

            if ( destinataire != '') {
                 $('#broullion').removeAttr('disabled');
            }
            else {
                 $('#broullion').attr("disabled","disabled");

            }
            });
        $('#sujet').change(function() {
            var sujet = $('#sujet').val();

            if ( sujet != '') {
                $('#broullion').removeAttr('disabled');
            }
            else {
                $('#broullion').attr("disabled","disabled");

            }
        });
            // ajax save as draft
        $('#broullion').click(function(){
            var destinataire = $('#destinataire').val();
            var cc = $('#cc').val();
            var cci = $('#cci').val();
            var sujet = $('#sujet').val();
            var contenu = $('#contenu').val();
             if ( (contenu != ''))
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('envoyes.savingBR') }}",
                    method:"POST",
                    data:{destinataire:destinataire,sujet:sujet,contenu:contenu,cc:cc,cci:cci, _token:_token},
                    success:function(data){
                   ////     alert('Brouillon enregistré ');

                    }
                });
            }else{
                alert('ERROR');
            }
        });


     });
</script>

    <script type="text/javascript">
        var onloadCallback = function() {
            console.log("grecaptcha is ready!");
        };
    </script>

     <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"
            async defer>
    </script>



    </p><!--fin traitement email--------------------------------------------------------------------------------------->
  </div>
  <div id="menu2" class="tab-pane fade">
    <h3>Envoyer un SMS </h3>
    <p><!-- Envoyer un SMS  Whatsapp ---------------------------------------------------------------------------------->
      
    <form method="post" action="{{action('EmailController@sendwhatsapp')}}" >
    <div class="form-group">
        {{ csrf_field() }}
        <label for="destinataire">Destinataire:</label>
        <input id="destinataire" type="text" class="form-control" name="destinataire"    />
    </div>

    <div class="form-group">
        <label for="contenu">Message:</label>
        <textarea  type="text" class="form-control" name="message"></textarea>
        {!! NoCaptcha::renderJs() !!}

    </div>
        <div class="form-group">
        <button  type="submit"  class="btn btn-md  btn-primary btn_margin_top"><i class="fa fa-paper-plane" aria-hidden="true"></i> Envoyer</button>
        </div>

    </form>


    </p>
    <!-- Fin Envoyer un SMS  Whatsapp ---------------------------------------------------------------------------------->
  </div>

  <div id="menu3" class="tab-pane fade">
    <h3>Enregistrement Téléphonique</h3>
    <p>Some content in menu 2.</p>
  </div>
  <div id="menu4" class="tab-pane fade">
    <h3>Prestataire</h3>
    <p>Some content in menu 2.</p>
  </div>
  <div id="menu5" class="tab-pane fade">
    <h3>intervenant</h3>
    <p>Some content in menu 2.</p>
  </div>
   <div id="menu6" class="tab-pane fade">
    <h3>Ordre de mission</h3>
    <p>Some content in menu 2.</p>
  </div>
   <div id="menu7" class="tab-pane fade">
    <h3>Fax</h3>
    <p>Some content in menu 2.</p>
  </div>

</div>

<!--  les modals -->

<!--  début  modal repoter sousaction (et donc toute l'action)  -->
<style>
.modal-dialog.kbs { margin-top: 18%; } 
</style>
  

  <div id="ReporterSA" class="modal fade" role="dialog">
  <div class="modal-dialog kbs">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Reporter la sous action courante à une date ultérieure (Report de toute l'action)</h4>
      </div>
      <form   action ="{{ url('dossier/action/Reportersousaction/'.$sousaction->action->dossier->id.'/'.$sousaction->action->id.'/'.$sousaction->id)}}">
      <div class="modal-body">
        <p>
      <div class="form-group">
        <?php $da= date('Y-m-d\TH:m'); ?>

      <label for="datereport" style="display: inline-block;  text-align: left; width:200px;">Saisissez la date de report:</label>
      <input id="datereport" type="datetime-local" value="<?php echo $da ?>" class="form-control" style="width:95%;  text-align: right;" name="datereport"/>
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
  $("#kbspaneltitle").append('<?php if($sousaction) {  echo 'Action : '.$sousaction->action->titre.' | Sous-action : '.$sousaction->titre.' --- ( '. $sousaction->ordre . ' / '. $sousaction->action->sousactions->count() .')'; }  ?>');
  </script>
<!-- enregistrer commentaires sans bouton -->
<!-- <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>-->
<script>

    function changing(elm) {
        var champ=elm.id;
       // alert(champ);
        var val =document.getElementById(champ).value;
       // alert(val);
        //  var type = $('#type').val();
        var donnees = $("#formcoments").serialize();
        
        $.ajax({
            url: "{{ url('dossier/action/TraitercommentsousactionAjax/'.$sousaction->action->dossier->id.'/'.$sousaction->action->id.'/'.$sousaction->id)}}",
            method: "POST",
            data : donnees,
            success: function (data) {
                $('#comenttitle').animate({
                    opacity: '0.3',
                });
                $('#comenttitle').animate({
                    opacity: '1',
                });

                location.reload();

            }
        });

    }

  </script>



 

@endsection










