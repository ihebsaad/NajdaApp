@extends('layouts.dispatchlayout')
{{-- Page title --}}
@section('title')
    @parent
@stop
<?php
use App\Tag ;
use App\Dossier ;
use App\Notification ;
//$dossiers = Dossier::get();
Use App\Common;


use App\Attachement ;
use App\Http\Controllers\AttachementsController;
use App\Http\Controllers\NotificationsController;


?>
{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('public/css/custom_css/layout_responsive.css') }}">

@stop
@section('content')

    <style>   span.select2-selection , .select2-selection--single ,.select2-selection ,.select2-selection--single, .select2-selection__rendered,span.select2-selection__rendered{color:black!important; }
    </style>
<div class="panel panel-default panelciel " style="">
 @if(session()->has('AffectNouveauDossier'))
    <div class="alert alert-success">
       <center> <h4>{{ session()->get('AffectNouveauDossier') }}</h4></center>
    </div>
  @endif
        <div class="panel-heading" style="">
                    <div class="row">
                        <div  style=" padding-left: 0px;color:black;font-weight: bold ;">
                            <h4 class="panel-title  " > <label for="sujet" style=" ;font-size: 15px;">Sujet :</label>  <?php $sujet=$entree['sujet'];

                            if(Common::SstartsWith($sujet,"=?utf") || Common::SstartsWith($sujet,"=?windows") ||Common::SstartsWith($sujet,"=?UTF") || Common::SstartsWith($sujet,"=?WIND")   ) {
                                    $sujet=  iconv_mime_decode( nl2br(strval(utf8_encode($sujet)) )  );
                                }

                            echo ($sujet); ?><span id="hiding" class="pull-right">
         <i style="color:grey;margin-top:10px"class="fa fa-2x fa-fw clickable fa-chevron-down"></i>
            </span></h4>                        </div>
                    </div>
                 <div class="row" style="padding-right: 10px;margin-top:10px;" id="emailbuttons">
                            <div class="pull-right" style="margin-top: 0px;">
                                @if (!empty($entree->dossier))
                                    <button class="btn btn-sm btn-default"><b>REF: {{ $entree['dossier']   }}</b></button>
                                @endif
                                @if (empty($entree->dossier))
                                        <a   class="btn btn-md btn-success"   href="{{route('dossiers.create',['identree'=> $entree['id']]) }}"  > <i class="fas fa-folder-plus"></i> Créer un Dossier</a>
                                     <!--   <button class="btn   " id="showdispl" style="background-color: #c5d6eb;color:#333333;"  ><b><i class="fas fa-folder"></i> Dispatcher</b></button>-->
                                 @endif


                                <?php    $seance =  DB::table('seance')
                                    ->where('id','=', 1 )->first();
                                    $disp=$seance->dispatcheur ;

                                    $iduser=Auth::id();
                                  //  if ($iduser==$disp) { ?>
                                    <a  href="{{action('EntreesController@archiver', $entree['id'])}}" class="btn btn-warning btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Archiver" >
                                  <span class="fa fa-fw fa-archive"></span> Archiver
                                </a>


                                    <!--<a  href="{{action('EntreesController@spam', $entree['id'])}}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Marquer comme traité" >
                                        <span class="fas fa-exclamation-triangle"></span> SPAM
                                    </a>-->

                                        <a onclick="return confirm('Êtes-vous sûrs ?')"  href="{{action('EntreesController@destroy2', $entree['id'])}}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer" >
                                            <span class="fa fa-fw fa-trash-alt"></span> Supprimer
                                        </a>
                            </div>
                        </div>


                 </a>
        </div>
        <div id="emailhead" class="panel-collapse collapse in" aria-expanded="true" style="">
            <div class="panel-body">
                <div class="row" id="displine" style="padding-left:30px;padding-bottom:15px;padding-top:15px;background-color: #F9F9F8 ">
                  <div class="col-md-3">
                      <B> Dossier : </B>
                      <section><select id ="affdoss"  class="form-control " style="width: 150px;color:black!important;">
                        <option></option>
                        <?php foreach($dossiers as $ds)

                        {
                            echo '<option style="color:black!important" title="'.$ds->id.'" value="'.$ds->reference_medic.'"> '.$ds->reference_medic.' </option>';}     ?>
                          </select></section>
                  </div>
                    <div class="col-md-3">

                    <select id ="affdoss2"  class="form-control " style="margin-left:100px;width: 150px;color:black!important;">
                        <option></option>
                        <?php foreach($dossiers as $ds)

                        {
                            echo '<option style="color:black!important" title="'.$ds->id.'" value="'.$ds->reference_medic.'"> '.$ds->reference_medic.' </option>';}     ?>
                    </select>
                    </div>
                    <div class="col-md-3">
                    <button style="margin-left:35px" type="button" id="updatefolder" onclick="document.getElementById('updatefolder').disabled=true" class="btn btn-primary">Dispatcher</button>
                    </div>
                </div>
                <div class="row" style="font-size:12px;">
                    <div class="col-sm-5 col-md-5 col-lg-5" style=" padding-top: 4px; ">
                        <span><b>Emetteur: </b>{{ $entree['emetteur']  }}</span>
                    </div>
                    <div class="col-sm-4 col-md-4 col-lg-4" style=" padding-left: 0px; ">
                        <span><b>À: </b>{{ $entree['destinataire']  }}</span>
                    </div>
                        <div class="col-sm-3 col-md-3 col-lg-3 " style="padding-right: 0px;">
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
                    <li class="<?php if($entree['contenu']!=null){echo 'active ';} ?>" >
                        <?php if ( $entree['type']=='fax') {}else {  if ( $entree['type']!='tel') { ?><a href="#mailcorps" data-toggle="tab" aria-expanded="true">Corps HTML du mail</a><?php } }?>
                    </li>
                    <li class="<?php if($entree['contenu']==null){echo 'active ';} ?> " >
                        <?php if ( $entree['type']=='fax') {}else {  if ( $entree['type']!='tel') { ?><a href="#mailcorps2" data-toggle="tab" aria-expanded="true">Texte Brute</a><?php } }?>
                    </li>
                    <?php /* if ( $entree['type']!='fax') { ?>
                       <li class=" " >
                              <a href="#txtcorps" data-toggle="tab" aria-expanded="true"> Texte Brut</a>
                       </li>
                       <?php } */
                    if ( $entree['type']!='tel') { ?>
                    @if ( $nbattachs   > 0)
                        @for ($i = 1; $i <= $nbattachs ; $i++)
                            <li>
                                <a class=" " href="#pj<?php echo $i; ?>" data-toggle="tab" aria-expanded="false">PJ<?php echo $i; ?></a>
                            </li>
                        @endfor
                    @endif
                    <?php } ?>
                </ul>

                <div id="myTabContent" class="tab-content" style="background: #ffffff">
                    <?php if ( $entree['type']!='fax') { ?>
                    <div class="tab-pane fade <?php if($entree['contenu']!=null){echo 'active in';} ?> " id="mailcorps" style="">
                       <section> <p  id="mailtext" style=" line-height: 25px;"><?php
                            if($entree['contenu']!= null)
                            {$content= nl2br($entree['contenu']) ;

                            ?>
                            <?php  $search= array('facture','invoice','facturation','invoicing','plafond','max','maximum'); ?>
                            <?php  $replace=  array('<B class="invoice">facture</B>','<B class="invoice">invoice</B>','<B class="invoice">facturation</B>','<B class="invoice">invoicing</B>','<B class="invoice">plafond</B>','<B class="invoice">max</B>','<B class="invoice">maximum</B>'); ?>

                            <?php  $cont=  str_replace($search,$replace, $content); ?>
                            <?php  echo $cont; ?></p>
                       </section>
                        <?php } ?>

                    </div>

                    <div class="tab-pane fade  <?php if($entree['contenu']==null){echo 'active in';} ?>" id="mailcorps2" style="">
                       <section> <p  id="mailtext2" style=" line-height: 25px;"><?php
                            if($entree['contenutxt']!= null)
                            {$content2= nl2br($entree['contenutxt']) ;

                            ?>
                            <?php  $search= array('facture','invoice','facturation','invoicing','plafond','max','maximum'); ?>
                            <?php  $replace=  array('<B class="invoice">facture</B>','<B class="invoice">invoice</B>','<B class="invoice">facturation</B>','<B class="invoice">invoicing</B>','<B class="invoice">plafond</B>','<B class="invoice">max</B>','<B class="invoice">maximum</B>'); ?>

                            <?php  $cont2=  str_replace($search,$replace, $content2); ?>
                            <?php  echo $cont2; ?></p>
                       </section>
                        <?php }; ?>
                    </div><?php } ?>


                    <?php // } ?>
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

<style>
td {border: 1px #DDD solid; padding: 5px; cursor: pointer;}

.selected {
    background-color: brown;
    color: #FFF;
}
</style>



       <script type="text/javascript">
        
      $("#tabkkk tr").click(function(){
         $(this).addClass('selected').siblings().removeClass('selected');    
         var value=$(this).find('td:first').html();
        //alert(value);    
      });

      $('#tst').on('click', function(e){
          var arrid =$("#tabkkk tr.selected td:first").html();


           $.ajax({
       url : '{{ url('/') }}'+'/annulerAttenteReponseAction/'+arrid,
       type : 'GET',
       dataType : 'html', // On désire recevoir du HTML
       success : function(data){ // code_html contient le HTML renvoyé
           //alert (data);

           if(data)
           {

          

           alert(data);
           location.reload();

            
           }
       }
    });
   








      });


      </script>


<!-- Modal
<div class="modal fade" id="affectfolder"    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 style="text-align:center" class="modal-title" id="exampleModalLabel">Dispatcher</h3>

            </div>
            <div class="modal-body">
                <div class="card-body">

                    <form method="post" >
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="type">Dossier :</label>
                         <select id ="affdoss"  class="form-control " style="width: 120px">
                             <option></option>
                         <?php foreach($dossiers as $ds)

                               {
                               echo '<option value="'.$ds->reference_medic.'"> '.$ds->reference_medic.' </option>';}     ?>
                         </select>
                            <br><br><br>
                        </div>


                    </form>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" id="updatefolder" class="btn btn-primary">Dispatcher</button>
            </div>
        </div>
    </div>
</div>
-->
<!-- Modal -->
<div class="modal fade" id="createfolder"    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Créer un nouveau dossier</h5>

            </div>
            <div class="modal-body">
                     <div class="card-body">

                        <!--<form method="post" >-->
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label for="type">Type :</label>
                                <select   id="type_dossier" name="type_dossier" class="form-control js-example-placeholder-single">
                                    <option   value="Medical">Medical</option>
                                    <option   value="Technique">Technique</option>
                                    <option   value="Mixte">Mixte</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="type">Type d'affectation :</label>
                            <select id="type_affectation" name="type_affectation" class="form-control js-example-placeholder-single"  >
                                <option  value="Najda">Najda</option>
                                <option   value="VAT">VAT</option>
                                <option  value="MEDIC">MEDIC</option>
                                <option   value="Transport MEDIC">Transport MEDIC</option>
                                <option   value="Transport VAT">Transport VAT</option>
                                <option  value="Medic International">Medic International</option>
                                <option   value="Najda TPA">Najda TPA</option>
                                <option   value="Transport Najda">Transport Najda</option>
                            </select>
                            </div>


                         <div class="form-group">
                             <label for="type">Prénom de l'abonné :</label>
                             <input type="text" id="subscriber_name" class="form-control">
                         </div>
                         <div class="form-group">
                             <label for="type">Nom de l'abonné :</label>
                             <input type="text" id="subscriber_lastname" class="form-control">
                         </div>

                            <div class="form-group">
                                <label for="affecte">Agent Affecté :</label>
                                <select   id="affecte" name="affecte"   class="form-control js-example-placeholder-single">
                                @foreach($users as $user  )
                                    <option
                                     @if($user->id==$iduser)
                                     selected="selected"
                                     @endif

                                    value="{{$user->id}}">{{$user->name}}</option>

                                @endforeach
                                </select>
                            </div>


                         <input style="margin-top:8px;" type="checkbox" id="checkaccuse"><label for="checkaccuse" style="margin-left:10px;cursor:pointer">Envoyer un accusé de réception au client</label></input>
                         <div id="formaccuse" style="display:none">
                             {{ csrf_field() }}
                             <?php $message= EntreesController::GetParametre($entree['id']);
                             //  echo json_encode($message);?>
                             <div  style=" width: 540px; height: 450px;" id="message" contenteditable="true" ><?php echo $message   ;?></div>
                         </div>

                         <input type="hidden" value="nouveau" name="statdoss">


                         <input type="hidden" value="<?php echo $entree['id'];?>" name="entree_id"  id="entree_id" >

                            <input type="hidden" value="{{Auth::user()->id}}" name="affecteur">
                        <!-- </form>-->
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="submit" id="add" class="btn btn-primary">Ajouter</button>
            </div>
        </div>
    </div>

</div>


<!-- Modal Accusé -->
<div class="modal fade" id="sendaccuse"    role="dialog" aria-labelledby="label" aria-hidden="true">
    <div class="modal-dialog" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="label">Accusé</h5>

            </div>
            <div class="modal-body">
                <div class="card-body" style="text-align:center">
                    <h3>Envoyer un accusé de réception au client</h3><br><br>

                    <form method="post" >
                        {{ csrf_field() }}
                        <?php $message= EntreesController::GetParametre($entree['id']);
                      //  echo json_encode($message);?>
                    <div  style=" width: 540px; height: 450px;" id="message00" contenteditable="true" ><?php echo $message   ;?></div>
                    </form>
                    <button style="margin-top:20px;text-align:center" type="button"  id="sending" class="btn btn-lg  btn-primary btn_margin_top"><i class="fa fa-paper-plane" aria-hidden="true"></i> Envoyer</button>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>

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

<!--select css-->
<link href="{{ asset('public/js/select2/css/select2.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('public/js/select2/css/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>

<script src="{{ asset('public/js/select2/js/select2.js') }}"></script>

<script>

    $( document ).ready(function() {
        $("#affdoss").select2();

        $('#add').click(function(){
            var type_dossier = $('#type_dossier').val();
            var entree = $('#entree_id').val();
             var type_affectation = $('#type_affectation').val();
             var lastname = $('#subscriber_lastname').val();
             var name = $('#subscriber_name').val();
            var affecte = $('#affecte').val();
             var message = $('#message').html();
            var send=false;
             if (document.getElementById('checkaccuse').checked == true){send=true;}else{send=false;}

            if ((type_dossier != '')&&(type_affectation != '')&&(affecte != ''))
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('dossiers.saving') }}",
                    method:"POST",
                    data:{lastname:lastname,name:name ,message:message,send:send,entree:entree,type_dossier:type_dossier,type_affectation:type_affectation,affecte:affecte, _token:_token},
                    success:function(data){

                     //   alert('Added successfully');
                        window.location =data;


                    }
                });
            }else{
               // alert('ERROR');
            }
        });


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
            var dossier2 = $('#affdoss2').val();
            var iddossier = $('#affdoss').find("option:selected").attr("title");
            var iddossier2 = $('#affdoss').find("option:selected").attr("title");

            var _token = $('input[name="_token"]').val();
            if(dossier!='')
            {
                $.ajax({
                url:"{{ route('entrees.dispatchf2') }}",
                method:"POST",
                data:{entree:entree,dossier:dossier,iddossier:iddossier, _token:_token},
                success:function(data){
                    window.location =data;

                }
            });

            }else{

                if(dossier2!='')
                {
                    $.ajax({
                        url:"{{ route('entrees.dispatchf2') }}",
                        method:"POST",
                        data:{entree:entree,dossier:dossier2,iddossier:iddossier2, _token:_token},
                        success:function(data){
                            window.location =data;

                        }
                    });
                }
                else{
                    alert('Sélectionnez un dossier');
                    document.getElementById('updatefolder').disabled=false;

                }


            }

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


    $('#showdispl').click(function() {

        var   div=document.getElementById('displine');
        if(div.style.display==='none')
        {
            div.style.display='block';
        }
        else
        {div.style.display='none';     }


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




@endsection
