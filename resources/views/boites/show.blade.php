@extends('layouts.mainlayout')
{{-- Page title --}}
@section('title')
    @parent
@stop
<?php
use App\Tag ;
 use App\Notification ;

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

        <div class="panel-heading" style="">
                    <div class="row">
                        <div class="col-sm-4 col-md-4 col-lg-6"style=" padding-left: 0px;color:black;font-weight: bold ">
                            <h4 class="panel-title"> <label for="sujet" style="font-size: 15px;">Sujet :</label>  {{ $boite['sujet'] }}</h4>
                        </div>
                        <div class="col-sm-8 col-md-8 col-lg-6" style="padding-right: 0px;">
                            <div class="pull-right" style="margin-top: 0px;">
                            </div>
                        </div>

                    </div>
                    
                 </a>
        </div>
        <div id="emailhead" class="panel-collapse collapse in" aria-expanded="true" style="">
            <div class="panel-body">
                <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-6"style=" padding-left: 0px; ">
                            <span><b>Emetteur: </b>{{ $boite['emetteur']  }}</span>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6 " style="padding-right: 0px;">
                            <span class="pull-right"><b>Date: </b> <?php   echo date('d/m/Y H:i', strtotime( $boite['reception']  )) ;  ?></span>

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
                            <h4 class="panel-title"> <label for="sujet" style="font-size: 15px;"> Contenu</label></h4>
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
                            <a href="#mailcorps" data-toggle="tab" aria-expanded="true">Corps du mail</a>
                        </li>
                        @if ( $boite['nb_attach']   > 0)
                            @for ($i = 1; $i <= $boite['nb_attach'] ; $i++)
                                <li>
                                    <a href="#pj<?php echo $i; ?>" data-toggle="tab" aria-expanded="false">PJ<?php echo $i; ?></a>
                                </li>
                            @endfor
                        @endif
                    </ul>
                    <div id="myTabContent" class="tab-content" style="padding:10px;padding-top:20px;background: #ffffff">
                                        <div class="tab-pane fade active in" id="mailcorps" style="min-height: 350px;">
                                            <p id="mailtext" style="line-height: 25px;"><?php  $content= $boite['contenu'] ; ?>
                                                 <?php  echo utf8_decode($content); ?></p>
                                        </div>
                                        @if ($boite['nb_attach']  > 0)
                                          <?php
                                            // get attachements info from DB
                                            $attachs = Attachement::get()->where('parent', '=', $boite['id'] );
                                            
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
$users=UsersController::ListeUsers();

 $CurrentUser = auth()->user();

 $iduser=$CurrentUser->id;

?>



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
