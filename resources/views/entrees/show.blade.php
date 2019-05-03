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
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('resources/assets/css/tags.css') }}">
@stop
@section('content')

<div class="panel panel-default panelciel " style="">

        <div class="panel-heading" style="">
                    <div class="row">
                        <div class="col-sm-4 col-md-4 col-lg-4"style=" padding-left: 0px;color:black;font-weight: bold ">
                            <h4 class="panel-title"> <label for="sujet" style="font-size: 15px;">Sujet :</label>  {{ $entree['sujet'] }}</h4>
                        </div>
                        <div class="col-sm-8 col-md-8 col-lg-8" style="padding-right: 0px;">
                            <div class="pull-right" style="margin-top: 0px;">
                                @if (!empty($entree->dossier))
                                    <button class="btn btn-sm btn-default"><b>REF: {{ $entree['dossier']   }}</b></button>
                                @endif
                                @if (empty($entree->dossier))
                                        <button id="addfolder" class="btn btn-md btn-success"   data-toggle="modal" data-target="#createfolder"><b><i class="fas fa-folder-plus"></i> Créer un Dossier</b></button>
                                 @endif
                                <a href="#" class="btn btn-primary btn-sm btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Enregistrer les commentaires et TAGS" > 
                                  <span class="fa fa-fw fa-save"></span> Sauvegarder
                                </a>
                                <button class="btn btn-sm btn-default"><i class="fa fa-fw fa-square"></i></button>
                                <button class="btn btn-sm btn-default"><i class="fa fa-fw fa-times removepanel clickable"></i></button>
                            </div>
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
                            <h4 class="panel-title"> <label for="sujet" style="font-size: 15px;">Contenu</h4>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6" style="padding-right: 0px;">
                            <div class="pull-right" style="margin-top: 0px;z-index: 10">
                                <a id="commenttag" href="#" class="btn btn-info btn-sm btn-responsive" role="button" onclick="comment();"> 
                                  <span class="fa fa-fw fa-tags"></span> Commentaire & TAG
                                </a>
                                <a id="emailcontent" href="#" class="btn btn-primary btn-sm btn-responsive" style="display:none" role="button" onclick="comment();"> 
                                  <span class="fa fa-fw fa-arrow-left"></span> Retour
                                </a>
                            </div>
                        </div>
                    </div>        
                 </a>
        </div>
        <div id="emailcontent" class="panel-collapse collapse in" aria-expanded="true" style="">
            <div class="panel-body" id="emailnpj">
                <div class="row">
                   <ul class="nav nav-pills">
                        <li class="active" >
                            <a href="#mailcorps" data-toggle="tab" aria-expanded="true">Corps du mail</a>
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
                                        <div class="tab-pane fade active in" id="mailcorps" style="min-height: 350px;">
                                            <p id="mailtext" style="line-height: 25px;"><?php  $content= $entree['contenu'] ; ?>
                                            <?php  $search= array('facture','invoice','facturation','invoicing','plafond','max','maximum'); ?>
                                            <?php  $replace=  array('<B class="invoice">facture</B>','<B class="invoice">invoice</B>','<B class="invoice">facturation</B>','<B class="invoice">invoicing</B>','<B class="invoice">plafond</B>','<B class="invoice">max</B>','<B class="invoice">maximum</B>'); ?>

                                            <?php  $cont=  str_replace($search,$replace, $content); ?>
                                            <?php // $cont=  str_replace("invoice","<b>invoice</b>", $content); ?>
                                            <?php  echo $cont; ?></p>
                                        </div>
                                        @if ($entree['nb_attach']  > 0)
                                          <?php
                                            // get attachements info from DB
                                            $attachs = Attachement::get()->where('parent', '=', $entree['id'] );
                                            
                                          ?>
                                            @if (!empty($attachs) )
                                            <?php $i=1; ?>
                                            @foreach ($attachs as $att)
                                                <div class="tab-pane fade in" id="pj<?php echo $i; ?>">

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
            <div class="panel-body" id="emailcomment" style="display: none">
                <div class="row"style="padding:10px;padding-top:20px;background: #eee">
                    <div class="form-group">
                        <form method="post">
                                            <div class="col-md-10">
                                                <textarea id="message" name="message" rows="7" class="form-control resize_vertical" placeholder="Entrez votre commentaire"></textarea>
                                            </br><label style="padding-bottom: 10px;color: #8a8a8a;">Les TAGS:</label></br>
                                                <div id="taglist" class="form-token-tags">
                                                  <ul class="tag-editor">
                                                   <?php 
                                                        // affichage des tags relié au notification courante
                                                        $tags=TagsController::entreetags($entree['id']);
                                                        if (! empty($tags))
                                                        {   
                                                            $abbrevtag = "";
                                                            $listtag="";
                                                            foreach ($tags as $tag) {
                                                                switch ($tag['titre']) {
                                                                        case "Garantie de paiement":
                                                                            $abbrevtag = "GOP";
                                                                            $listtag .=",GOP";
                                                                            break;
                                                                        case "Franchise":
                                                                            $abbrevtag = "FR";
                                                                            $listtag .=",FR";
                                                                            break;
                                                                        case "tag de test":
                                                                            $abbrevtag = "TT";
                                                                            $listtag .=",TT";
                                                                            break;
                                                                    }
                                                                echo '<li data-id="'.$abbrevtag.'"><div class="tag-editor-spacer">&nbsp;</div><div class="tag-editor-tag">'.$tag["titre"].'</div><div class="tag-editor-delete"><i></i></div></li>';
                                                                                                           
                                                            }
                                                        }
                                                                    
                                                           ?>
                                                    {{ csrf_field() }}
                                                    <input id="entreeid" type="hidden" name="entree" value="<?php echo $entree['id']; ?>" />
                                                    <input id="urladdtag" type="hidden" name="urladdtag" value="{{ route('tags.addnew') }}" />
                                                  </ul>
                                                  <input type="hidden" name="country-blacklist" value="<?php echo $listtag; ?>" data-allowed-tags="[{&quot;id&quot;:&quot;GOP&quot;,&quot;name&quot;:&quot;Garantie de paiement&quot;},{&quot;id&quot;:&quot;FR&quot;,&quot;name&quot;:&quot;Franchise&quot;},{&quot;id&quot;:&quot;TT&quot;,&quot;name&quot;:&quot;tag de test&quot;}]">
                                                </div>
                                            </div>
                                        </form>
                    </div>
                </div>
            </div>
        </div>
</div>

<style>
    .invoice{background-color: #fad9da;padding:1px;}
    label{font-weight:bold;}
</style>
 <script>
      function comment()
      {
        if ($("#emailcomment").is(":hidden")) 
            {
                $("#emailnpj").hide();
                $("#emailcomment").show();

                $("#commenttag").hide();
                $("#emailcontent").show();

            }
        else
        {
            
            $("#emailcomment").hide();
            $("#emailnpj").show();

            $("#commenttag").show();
            $("#emailcontent").hide();
        }
      }
    </script>

<style>
.pdfnotice{color:red;font-weight: 600;margin-top:10px;margin-bottom:10px;}
    </style>

<?php use \App\Http\Controllers\UsersController;
$users=UsersController::ListeUsers();

 $CurrentUser = auth()->user();

 $iduser=$CurrentUser->id;

?>


<!-- Modal -->
<div class="modal fade" id="createfolder" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Créer un nouveau dossier</h5>

            </div>
            <div class="modal-body">
                     <div class="card-body">

                        <form method="post" >
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
                                <label for="type">Affecté à :</label>
                            <select id="type_affectation" name="type_affectation" class="form-control js-example-placeholder-single" readonly="readonly">
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
                                <label for="affecte">Agent:</label>
                                <select   id="affecte" name="affecte"   class="form-control js-example-placeholder-single">
                                @foreach($users as $user  )
                                    <option
                                     @if($user->id==$iduser)selected="selected"@endif

                                    value="{{$user->id}}">{{$user->name}}</option>

                                @endforeach
                                </select>
                            </div>
                         </form>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" id="add" class="btn btn-primary">Ajouter</button>
            </div>
        </div>
    </div>
</div>





<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script src="{{ URL::asset('resources/assets/js/spectrum.js') }}"></script>
<script src="{{ URL::asset('resources/assets/js/jquery.marker.js') }}"></script>
<script src="{{ URL::asset('resources/assets/js/tags.js') }}"></script>

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
             var type_affectation = $('#type_affectation').val();
            var affecte = $('#affecte').val();
            if ((type_dossier != '')&&(type_affectation != '')&&(affecte != ''))
            {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url:"{{ route('dossiers.saving') }}",
                    method:"POST",
                    data:{type_dossier:type_dossier,type_affectation:type_affectation,affecte:affecte, _token:_token},
                    success:function(data){

                     //   alert('Added successfully');
                        window.location =data;


                    }
                });
            }else{
               // alert('ERROR');
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

</script>




@endsection
