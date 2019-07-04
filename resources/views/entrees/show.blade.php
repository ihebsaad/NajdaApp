@extends('layouts.mainlayout')
{{-- Page title --}}
@section('title')
    @parent
@stop
<?php
use App\Tag ;
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


<div class="panel panel-default panelciel " style="">
 @if(session()->has('AffectNouveauDossier'))
    <div class="alert alert-success">
       <center> <h4>{{ session()->get('AffectNouveauDossier') }}</h4></center>
    </div>
  @endif
        <div class="panel-heading" style="">
                    <div class="row">
                        <div  style=" padding-left: 0px;color:black;font-weight: bold ;margin-bottom:15px;">
                            <h4 class="panel-title  "> <label for="sujet" style=" ;font-size: 15px;">Sujet :</label>  <?php $sujet=$entree['sujet']; echo ($sujet); ?></h4>
                        </div>
                    </div>
                        <div class="row" style="padding-right: 10px;">
                            <div class="pull-right" style="margin-top: 0px;">
                                @if (!empty($entree->dossier))
                                    <button class="btn btn-sm btn-default"><b>REF: {{ $entree['dossier']   }}</b></button>
                                @endif
                                @if (empty($entree->dossier))
                                        <button id="addfolder" class="btn btn-md btn-success"   data-toggle="modal" data-target="#createfolder"><b><i class="fas fa-folder-plus"></i> Créer un Dossier</b></button>
                                        <button id="afffolder" class="btn   " style="background-color: #c5d6eb;color:#333333;"  data-toggle="modal" data-target="#affectfolder"><b><i class="fas fa-folder"></i> Dispatcher</b></button>
                                 @endif
                               @if(Gate::check('isAdmin') || Gate::check('isSupervisor') || Gate::check('isDisp') )

                                    <a  href="{{action('EntreesController@archiver', $entree['id'])}}" class="btn btn-warning btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Archiver" >
                                  <span class="fa fa-fw fa-archive"></span> Archiver
                                </a>
                                    <a  href="{{action('EntreesController@traiter', $entree['id'])}}" class="btn btn-info btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Marquer comme traité" >
                                        <span class="fa fa-fw fa-check"></span> Traité
                                    </a>

                                    <a  href="{{action('EntreesController@destroy', $entree['id'])}}" class="btn btn-danger btn-sm btn-responsive " role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Supprimer" >
                                        <span class="fa fa-fw fa-trash-alt"></span> Supprimer
                                    </a>
                                    @if (!empty($entree->dossier))
                                    <button  id="accuse"   data-toggle="modal" data-target="#sendaccuse" class="btn btn-info btn-sm btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Envoyer l'accusé de reception" >
                                        <i class="fas fa-mail-bulk"></i> Accusé
                                    </button>
                                    @endif
                                  @endif

                            </div>
                        </div>


                 </a>
        </div>
        <div id="emailhead" class="panel-collapse collapse in" aria-expanded="true" style="">
            <div class="panel-body">
                <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-6"style=" padding-left: 0px; ">
                            <span><b>Emetteur: </b>{{ $entree['emetteur']  }}</span>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6 " style="padding-right: 0px;">
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
        <div class="panel-heading" data-parent="#accordion-cat-1" href="#emailcontent" class="">
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
        <div id="emailcontent" class="panel-collapse collapse in" aria-expanded="true" style="">
            <div class="panel-body" id="emailnpj">
                <div class="row">
                   <ul class="nav nav-pills">
                        <li class="active" >
                            <?php if ( $entree['type']=='fax') {}else {?> <a href="#mailcorps" data-toggle="tab" aria-expanded="true">Corps du mail</a><?php }?>
                        </li>
                        @if ( $entree['nb_attach']   > 0)
                            @for ($i = 1; $i <= $entree['nb_attach'] ; $i++)
                                <li>
                                    <a href="#pj<?php echo $i; ?>" data-toggle="tab" aria-expanded="false">PJ<?php echo $i; ?></a>
                                </li>
                            @endfor
                        @endif
                    </ul>
                    <div id="myTabContent" class="tab-content" style="padding:10px;padding-top:20px;background: #ffffff">
                       <?php if ( $entree['type']!='fax') { ?>
                           <div class="tab-pane fade active in" id="mailcorps" style="">
                                            <p class="overme" id="mailtext" style=" line-height: 25px;"><?php  $content= $entree['contenu'] ; ?>
                                            <?php  $search= array('facture','invoice','facturation','invoicing','plafond','max','maximum'); ?>
                                            <?php  $replace=  array('<B class="invoice">facture</B>','<B class="invoice">invoice</B>','<B class="invoice">facturation</B>','<B class="invoice">invoicing</B>','<B class="invoice">plafond</B>','<B class="invoice">max</B>','<B class="invoice">maximum</B>'); ?>

                                            <?php  $cont=  str_replace($search,$replace, $content); ?>
                                            <?php // $cont=  str_replace("invoice","<b>invoice</b>", $content); ?>
                                            <?php  echo $cont; ?></p>
                                        </div><?php } ?>

                         @if ($entree['nb_attach']  > 0)
                                          <?php
                                            // get attachements info from DB
                                            $attachs = Attachement::get()->where('parent', '=', $entree['id'] );
                                            
                                          ?>
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



<!-- Modal -->
<div class="modal fade" id="affectfolder" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

<!-- Modal -->
<div class="modal fade" id="createfolder" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form method="post" action="{{route('affectation.dossier') }}">
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
                         <input type="hidden" value="nouveau" name="statdoss">
                         <input type="hidden" value="nouveau" name="statdoss">

                         <input type="hidden" value="<?php echo $entree['id'];?>" name="entree_id" >

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
</form>
</div>


<!-- Modal Accusé -->
<div class="modal fade" id="sendaccuse" tabindex="-1" role="dialog" aria-labelledby="label" aria-hidden="true">
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
                    <div  style=" width: 540px; height: 450px;" id="message" contenteditable="true" ><?php echo $message   ;?></div>
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
$urlapp=env('APP_URL');

if (App::environment('local')) {
// The environment is local
$urlapp='http://localhost/najdaapp';
}
?>
<script>

    $( document ).ready(function() {

        $('#add').click(function(){
            var type_dossier = $('#type_dossier').val();
            var entree = $('#entree_id').val();
             var type_affectation = $('#type_affectation').val();
            var affecte = $('#affecte').val();
            if ((type_dossier != '')&&(type_affectation != '')&&(affecte != ''))
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('dossiers.saving') }}",
                    method:"POST",
                    data:{entree:entree,type_dossier:type_dossier,type_affectation:type_affectation,affecte:affecte, _token:_token},
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


</script>

<style>
  #message {

border: 1px solid #ccc;
padding: 5px;
}
    </style>



@endsection
