@extends('layouts.mainlayout')
{{-- Page title --}}
@section('title')
    @parent
@stop
<?php
use App\Http\Controllers\DossiersController;use App\Tag ;
use App\Dossier ;
use App\Notification ;
$dossiers = Dossier::get();

use App\Attachement ;
use App\Http\Controllers\AttachementsController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\TagsController;
?>
{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('public/css/custom_css/layout_responsive.css') }}">

@stop
@section('content')

    <?php
$urlapp="http://$_SERVER[HTTP_HOST]/najdaapp";?>

<div class="panel panel-default panelciel " style="">
 @if(session()->has('AffectNouveauDossier'))
    <div class="alert alert-success">
       <center> <h4>{{ session()->get('AffectNouveauDossier') }}</h4></center>
    </div>
  @endif
        <div class="panel-heading" style=""   >
                     <div class="row">
                        <div  style=" padding-left: 0px;color:black;font-weight: bold ;">
                            <h4 class="panel-title  " > <label for="sujet" style=" ;font-size: 15px;">Sujet :</label>  <?php $sujet=$entree['sujet']; echo ($sujet); ?><span id="hiding" class="pull-right">
         <i style="color:grey;margin-top:10px"class="fa fa-2x fa-fw clickable fa-chevron-down"></i>
            </span></h4>
                        </div>
                    </div>
                        <div class="row" style="padding-right: 10px;margin-top:10px" id="emailbuttons">
                            <div class="pull-right" style="margin-top: 0px;"><?php $iddossier=$entree['dossierid'] ; ?>
                                @if (!empty($entree->dossier))
                                    <button class="btn btn-sm btn-default"><b><a style="color:black" href="<?php echo $urlapp.'/dossiers/fiche/'.$iddossier;?>">REF: {{ $entree['dossier']   }} - <?php echo  DossiersController::FullnameAbnDossierById($iddossier); ?></a></b></button>
                                @endif
                                @if (empty($entree->dossier))
                                        <button id="addfolder" class="btn btn-md btn-success"   data-toggle="modal" data-target="#createfolder"><b><i class="fas fa-folder-plus"></i> Créer un Dossier</b></button>
                                 @endif

                                    <button id="afffolder" class="btn   " style="width:180px;background-color: #c5d6eb;color:#333333;"  data-toggle="modal" data-target="#affectfolder"><b><i class="fas fa-folder"></i>  Re-Dispatcher</b></button>

                                <?php    $seance =  DB::table('seance')
                                    ->where('id','=', 1 )->first();
                                    $disp=$seance->dispatcheur ;

                                    $iduser=Auth::id();
                                  //  if ($iduser==$disp) { ?>
                                    <a  href="{{action('EntreesController@archiver', $entree['id'])}}" style="color:black" class="btn btn-warning btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Archiver" >
                                  <span class="fa fa-fw fa-archive"></span> Archiver
                                </a>
                                   <?php if ($entree['type'] != 'tel'){ ?>
                                    <a  href="{{action('EntreesController@spam', $entree['id'])}}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Marquer comme SPAM" >
                                        <span class="fas fa-exclamation-triangle"></span> SPAM
                                    </a>
                                      <?php //} ?>

                                    <?php } ?>
                                <?php if ($entree['notif']!=1 ) { ?>
                                    <a onclick="checkComment()"  class="btn btn-info btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Marquer comme traité" >
                                        <span class="fa fa-fw fa-check"></span> Traité
                                    </a>
                                <?php } ?>

                                    @can('isAdmin')
                                    <a  href="{{action('EntreesController@destroy', $entree['id'])}}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer" >
                                        <span class="fa fa-fw fa-trash-alt"></span> Supprimer
                                    </a>
                                    @endcan

                            </div>
                        </div>


                 </a>
        </div>
        <div id="emailhead" class="panel-collapse collapse in" aria-expanded="true" style="">
            <div class="panel-body">
                <div class="row" style="font-size:12px;">
                        <div class="col-sm-4 col-md-4 col-lg-4"style=" padding-top: 4px; ">
                            <span><b>Emetteur: </b>{{ $entree['emetteur']  }}</span>
                        </div>
                    <div class="col-sm-4 col-md-4 col-lg-4" style=" padding-left: 0px; ">
                        <span><b>À : </b>{{ $entree['destinataire']  }}</span>
                    </div>
                        <div class="col-sm-4 col-md-4 col-lg-4 " style="padding-right: 0px;">
                            <span class="pull-right"><b>Date: </b><?php if ($entree['type']=='email'){echo  date('d/m/Y H:i', strtotime( $entree['reception']  )) ; }else {echo  date('d/m/Y H:i', strtotime( $entree['created_at']  )) ; }?></span>
                            <?php 
                                // verifier si l'entree possede de notification et la marque comme lu
                                $identr=$entree['id'];
                                $havenotif=NotificationsController::havenotification($identr);
                                if ($havenotif)
                                {
                                    // marker comme lu avec la date courante
                                    $date = date('Y-m-d g:i:s');
                                    Notification::where('id', $havenotif)->update(array('read_at' => $date));
                                }
                            ?>
                        </div>
                </div>
            </div>
        </div>

</div>
<div class="panel panel-default panelciel " >
        <!--<div class="panel-heading" style="cursor:pointer" data-toggle="collapse" data-parent="#accordion-cat-1" href="#emailcontent" class="" aria-expanded="true">-->
        <div class="panel-heading" data-parent="#accordion-cat-1" href="#emailcontent" >
                <a >
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-6"style=" padding-left: 0px; ">
                            <h4 class="panel-title"> <label for="sujet" style="font-size: 15px;"><?php if ( $entree['type']=='fax') {echo 'F A X';}else {?>Contenu<?php }?></label></h4>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6" style="padding-right: 0px;">
                        </div>
                    </div>        
                 </a>
        </div>
                                  <?php
                                            // get attachements info from DB
    $attachs = Attachement::get()->where('parent', '=', $entree['id'] )->where('boite','0');
    $nbattachs = Attachement::where('parent', '=', $entree['id'] )->where('boite','0')->count();

                                            
                                          ?>
        <div id="emailcontent" class="panel-collapse collapse in" aria-expanded="true" style="min-height:250px">
            <div class="panel-body" id="emailnpj">
                <div class="row">
                   <ul class="nav nav-pills">
                        <li class="active" >
                           <?php if ( $entree['type']=='fax') {}else {  if ( $entree['type']!='tel') { ?><a href="#mailcorps" data-toggle="tab" aria-expanded="true">Corps du mail</a><?php } }?>
                       </li>
                       <?php /* if ( $entree['type']!='fax') { ?>
                       <li class=" " >
                              <a href="#txtcorps" data-toggle="tab" aria-expanded="true"> Texte Brut</a>
                       </li>
                       <?php } */?>
                        @if ( $nbattachs   > 0)
                            @for ($i = 1; $i <= $nbattachs ; $i++)
                                <li>
                                    <a class=" " href="#pj<?php echo $i; ?>" data-toggle="tab" aria-expanded="false">PJ<?php echo $i; ?></a>
                                </li>
                            @endfor
                        @endif
                    </ul>
                    
                    <div id="myTabContent" class="tab-content" style="background: #ffffff">
                       <?php if ( $entree['type']!='fax') { ?>
                           <div class="tab-pane fade active in" id="mailcorps" style="">
                                          <p  id="mailtext" style=" line-height: 25px;"><?php  $content= $entree['contenu'] ; ?>
                                            <?php  $search= array('facture','invoice','facturation','invoicing','plafond','max','maximum'); ?>
                                            <?php  $replace=  array('<B class="invoice">facture</B>','<B class="invoice">invoice</B>','<B class="invoice">facturation</B>','<B class="invoice">invoicing</B>','<B class="invoice">plafond</B>','<B class="invoice">max</B>','<B class="invoice">maximum</B>'); ?>

                                            <?php  $cont=  str_replace($search,$replace, $content); ?>
                                            <?php // $cont=  str_replace("invoice","<b>invoice</b>", $content); ?>
                                              <?php  echo $cont; ?></p>
                                        </div><?php } ?>

                           <?php /* if ( $entree['type']!='fax') { ?>
                           <div class="tab-pane fade   in" id="txtcorps" style="">
                               <p  id="mailtext2" style=" line-height: 25px;"><?php  $contenttxt= $entree['contenutxt'] ; ?>
                                   <?php  $search= array('facture','invoice','facturation','invoicing','plafond','max','maximum'); ?>
                                   <?php  $replace=  array('<B class="invoice">facture</B>','<B class="invoice">invoice</B>','<B class="invoice">facturation</B>','<B class="invoice">invoicing</B>','<B class="invoice">plafond</B>','<B class="invoice">max</B>','<B class="invoice">maximum</B>'); ?>

                                   <?php  $cont2=  str_replace($search,$replace, $contenttxt); ?>
                                   <?php // $cont=  str_replace("invoice","<b>invoice</b>", $content); ?>
                                   <?php  echo $cont2; ?></p>
                           </div><?php } */ ?>

                         @if ($nbattachs > 0)
                
                                            @if (!empty($attachs) )
                                            <?php $i=1; ?>
                                            @foreach ($attachs as $att)
                                                <div class="tab-pane fade in <?php  if ( ($entree['type']=='fax')&&($i==1)) {echo 'active';}?>" id="pj<?php echo $i; ?>">

                                                    <h4><b style="font-size: 13px;">{{ $att->nom }}</b> (<a style="font-size: 13px;" href="{{ URL::asset('storage'.$att->path) }}" download>Télécharger</a>)</h4>

                                                    
                                                    
                                                    @switch($att->type)
                                                        @case('docx')
                                                        @case('doc')
                                                        @case('dot')
                                                        @case('dotx')
                                                        @case('docm')
                                                        @case('odt')
                                                        @case('pot')
                                                        @case('potm')
                                                        @case('pps')
                                                        @case('ppsm')
                                                        @case('ppt')
                                                        @case('pptm')
                                                        @case('pptx')
                                                        @case('ppsx')
                                                        @case('odp')
                                                        @case('xls')
                                                        @case('xlsx')
                                                        @case('xlsm')
                                                        @case('xlsb')
                                                        @case('ods')
                                                            <iframe src="https://view.officeapps.live.com/op/view.aspx?src={{ URL::asset('storage'.$att->path) }}" frameborder="0" style="width:100%;min-height:640px;"></iframe>
                                                            @break

                                                        @case('pdf')
                                                    <?php

                                                      $fact=$att->facturation;
                                                    if ($fact!='')
                                                    {
                                                        echo '<span class="pdfnotice"> Ce document contient le(s) mots important(s) suivant(s) : <b>'.$fact.'</b></span>';
                                                    }

                                                    ?>

                                                            <iframe src="{{ URL::asset('storage'.$att->path) }}" frameborder="0" style="width:100%;min-height:640px;"></iframe>
                                                            @break

                                                        @case('jpg')
                                                        @case('jpeg')
                                                        @case('gif')
                                                        @case('png')
                                                        @case('bmp')
                                                            <img src="{{ URL::asset('storage'.$att->path) }}" class="mx-auto d-block" style="max-width: 100%!important;"> 
                                                            @break
                                                               
                                                        @default
                                                            <span>Type de fichier non reconnu ... </span>
                                                    @endswitch
                                                    
                                                </div>
                                                <?php $i++; ?>
                                            @endforeach

                                            @endif

                                        @endif
                    </div>
                </div>
            </div>
                                        <form method="post">
                                                    {{ csrf_field() }}
                                                    <input id="entreeid" type="hidden" name="entree" value="<?php echo $entree['id']; ?>" />
                                                    <input id="urladdtag" type="hidden" name="urladdtag" value="{{ route('tags.addnew') }}" />
                                                    <input id="urldeletetag" type="hidden" name="urldeletetag" value="{{ route('tags.deletetag') }}" />
                                        </form>
        </div>

        <center> <button style="margin-bottom:15px" type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#annulerAttenteReponse"> Annuler attente de réponse</button></center>
</div>

<style>
    .invoice{background-color: #fad9da;padding:1px;}
    label{font-weight:bold;}
</style>

<style>
.pdfnotice{color:red;font-weight: 600;margin-top:10px;margin-bottom:10px;}
    </style>

<?php use \App\Http\Controllers\UsersController;
use \App\Http\Controllers\EntreesController;
$users=UsersController::ListeUsers();

 $CurrentUser = auth()->user();

 $iduser=$CurrentUser->id;

?>

 <?php use \App\Http\Controllers\ActionController;
    if (($dossier)) {
             
             $actionsReouRap=ActionController::ListeActionsRepOuRap($dossier->id);

         
          
       /*echo($actionsReouRap);*/
 ?>


<style>
td {border: 1px #DDD solid; padding: 5px; cursor: pointer;}

.selected {
    background-color: brown;
    color: #FFF;
}
</style>
  <!-- modal annuler attente de réponse -->
<div class="modal fade" id="annulerAttenteReponse" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><b>Annuler l'attente de réponse</b></h4>
        </div>
        <div class="modal-body">
          <p>
            
            @if(count($actionsReouRap)!=0)
            <h5> <b>Veuillez sélectionner une action avant de cliquer le bouton "Annuler Attente Réponse"</b></h5>
            <br>
            <table id="tabkkk">
                <tr> <th></th> <th> Action  </th> <th> Mission  </th><th> Dossier   </th> </tr>


                   @foreach ( $actionsReouRap as $rr)
                    <tr> <td style="color: white; font-size: 0px;">{{$rr->id}}</td> <td>{{$rr->titre}}</td> <td>{{ $rr->Mission->typeMission->nom_type_Mission}}</td> <td>{{$rr->Mission->dossier->reference_medic}} - {{$rr->Mission->dossier->subscriber_name }} {{$rr->Mission->dossier->subscriber_lastname}}</td>  </tr>
                   
                  @endforeach

              
            </table>
              @else

                 <div> les attentes de réponse pour ce dossier n'existent pas </div>

            @endif
                        
            <!--<input type="button" id="tst" value="OK" onclick="fnselect()" />-->


          </p>
        </div>
        <div class="modal-footer">
          @if(count($actionsReouRap)!=0) <button type="button" id="tst" class="btn btn-default" data-dismiss="modal">Annuler Attente Réponse </button>  @endif
          <button type="button" class="btn btn-default" data-dismiss="modal">Quitter</button>
        </div>
      </div>
  


</div>
      </div>
    <?php }?>

       <script type="text/javascript">
        
      $("#tabkkk tr").click(function(){
         $(this).addClass('selected').siblings().removeClass('selected');    
         var value=$(this).find('td:first').html();
        //alert(value);    
      });

      $('#tst').on('click', function(e){
          var arrid=-1;
          arrid =$("#tabkkk tr.selected td:first").html();
        
     
           $.ajax({
       
       url : '{{ url('/') }}'+'/annulerAttenteReponseAction/'+arrid,
       type : 'GET',
       dataType : 'html', // On désire recevoir du HTML
       success : function(data){ // code_html contient le HTML renvoyé
           //alert (data);

           if(data)
           {

           alert(data);

           if(String(data).indexOf("Erreur")== -1)
           {
           location.reload();
           }

            
           }
       }
    });
   
  

      });


      </script>


<!-- Modal -->
<div class="modal fade" id="affectfolder"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 style="text-align:center" class="modal-title" id="exampleModalLabel">Re-Dispatcher</h3>

            </div>
            <div class="modal-body">
                <div class="card-body">

                    <form method="post" >
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="type">Dossier :</label>
                         <select id ="affdoss"  class="form-control select2" style="width: 100%">
                             <option></option>
                         <?php foreach($dossiers as $ds)

                               {
                               echo '<option value="'.$ds->reference_medic.'"> '.$ds->reference_medic.' | '.$ds->subscriber_name.' - '.$ds->subscriber_lastname.' </option>';}     ?>
                         </select>
                            <br><br><br>
                        </div>


                    </form>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" id="updatefolder" onclick="document.getElementById('updatefolder').disabled=true" class="btn btn-primary">Dispatcher</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script src="{{ URL::asset('resources/assets/js/spectrum.js') }}"></script>
<script src="{{ URL::asset('resources/assets/js/jquery.marker.js') }}"></script>

<link rel="stylesheet" href="{{ URL::asset('resources/assets/css/spectrum.css') }}">
<?php
$urlapp="http://$_SERVER[HTTP_HOST]/najdaapp";
?>
<script>

    $("#affdoss").select2();

    $( document ).ready(function() {



        $('#sending').click(function(){
            var entree = $('#entreeid').val();
            var message = $('#message').html();


                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('emails.accuse') }}",
                    method:"POST",
                    data:{entree:entree,message:message, _token:_token},
                    success:function(data){

                        alert('Envoyé !');


                    }
                });

        });

        $('#updatefolder').click(function(){
            var entree = $('#entreeid').val();
            var dossier = $('#affdoss').val();


            var _token = $('input[name="_token"]').val();
            $.ajax({
                url:"{{ route('entrees.dispatchf') }}",
                method:"POST",
                data:{entree:entree,dossier:dossier, _token:_token},
                success:function(data){

                    window.location =data;



                }
            });

        });



        /****** Hilight Text on mail content ********/

        var target = $('#myTabContent');

        target.marker({
            //overlap:true,
            data : function(e, data) {
               // console.log(JSON.stringify(data))
            },
            debug : function(e, data) {
                    //console.log(JSON.stringify(data))
            }
        });

         //  var data= target.marker(data);


    });

    $('#data').on('click',   function() {

        target.marker('data');


    });

    $('#hiding').on('click',   function() {

        var   div=document.getElementById('emailhead');
        var   div2=document.getElementById('emailbuttons');
        if(div.style.display==='none')
        {
            div.style.display='block';
            div2.style.display='block';
        }
        else
        {
            div.style.display='none';
            div2.style.display='none';
        }

    });

    $('#checkaccuse').on('click',   function() {

        var   div=document.getElementById('formaccuse');
         if(div.style.display==='none')
        {
            div.style.display='block';
         }
        else
        {
            div.style.display='none';
         }

    });

</script>

<style>
  #message {

border: 1px solid #ccc;
padding: 5px;
}
    </style>

<script>

    function checkComment()
    {
        if (document.getElementById('commentuser').value == '') {
            alert('Ajouter un commentaire avant de marquer comme traité !');
               $('#actiontabs a[href="#infostab"]').trigger('click');
                $('#btn-cmttag').trigger('click');
                $('#editbtn').trigger('click');
            $("#commentuser").css("border", "2px solid red ");



        }else{
           location.href="{{action('EntreesController@traiter', $entree['id'])}}";
        }
    }


</script>


@endsection
