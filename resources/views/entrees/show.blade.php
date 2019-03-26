@extends('layouts.mainlayout')
{{-- Page title --}}
@section('title')
    @parent
@stop
<?php
use App\Dossier ;
$dossiers = Dossier::get();
?>
{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('public/css/custom_css/layout_responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('public/css/custom_css/bootstrap-pdf-viewer.css') }}">
@stop
@section('content')

<div class="panel panel-default panelciel " style="">
        <div class="panel-heading" style="">
                <a data-toggle="collapse" data-parent="#accordion-cat-1" href="#emailhead" class="" aria-expanded="true">
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-6"style=" padding-left: 0px; ">
                            <h4 class="panel-title"> <label for="sujet" style="font-size: 15px;">Sujet :</label> {{ $entree->sujet }}</h4>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6" style="padding-right: 0px;">
                            <div class="pull-right" style="margin-top: 0px;">
                                <a href="#" class="btn btn-primary btn-sm btn-responsive" role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Enregistrer les commentaires et TAGS" > 
                                  <span class="fa fa-fw fa-save"></span> Sauvegarder
                                </a>
                                <button class="btn btn-sm btn-default"><i class="fa fa-fw fa-square-o"></i></button>
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
                            <span><b>Emetteur: </b>{{$entree->emetteur}}</span>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6 " style="padding-right: 0px;">
                            <span class="pull-right"><b>Date: </b><?php echo  date('d/m/Y H:i', strtotime($entree->reception)) ; ?></span>
                        </div>
                </div>
            </div>
        </div>
</div>
<div class="panel panel-default panelciel " style="">
        <div class="panel-heading" style="">
                <a data-toggle="collapse" data-parent="#accordion-cat-1" href="#emailcontent" class="" aria-expanded="true">
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-6"style=" padding-left: 0px; ">
                            <h4 class="panel-title"> <label for="sujet" style="font-size: 15px;">Contenu</h4>
                        </div>
                        <div class="col-sm-6 col-md-6 col-lg-6" style="padding-right: 0px;">
                            <div class="pull-right" style="margin-top: 0px;">
                                <a href="#" class="btn btn-info btn-sm btn-responsive" role="button" onclick="comment();"> 
                                  <span class="fa fa-fw fa-tags"></span> Commentaire & TAG
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
                        @if ($entree->nb_attach > 0)
                            @for ($i = 1; $i <= $entree->nb_attach; $i++)
                                <li>
                                    <a href="#pj<?php echo $i; ?>" data-toggle="tab" aria-expanded="false">PJ<?php echo $i; ?></a>
                                </li>
                            @endfor
                        @endif
                    </ul>
                    <div id="myTabContent" class="tab-content" style="padding:10px;padding-top:20px;background: #eee">
                                        <div class="tab-pane fade active in" id="mailcorps">
                                            <p style="line-height: 25px"><?php  $content= $entree->contenu; ?>
                                            <?php  $search= array('facture','invoice','facturation','invoicing','plafond','max','maximum'); ?>
                                            <?php  $replace=  array('<B class="invoice">facture</B>','<B class="invoice">invoice</B>','<B class="invoice">facturation</B>','<B class="invoice">invoicing</B>','<B class="invoice">plafond</B>','<B class="invoice">max</B>','<B class="invoice">maximum</B>'); ?>

                                            <?php  $cont=  str_replace($search,$replace, $content); ?>
                                            <?php // $cont=  str_replace("invoice","<b>invoice</b>", $content); ?>
                                            <?php  echo $cont; ?></p>
                                        </div>
                                        @if ($entree->nb_attach > 0)
                                            @for ($i = 1; $i <= $entree->nb_attach; $i++)
                                                <div class="tab-pane fade in" id="pj<?php echo $i; ?>">
                                                    <p>
                                                        It is pj<?php echo $i; ?>.
                                                    </p>
                                                    <div id="viewer" class="pdf-viewer" data-url="{{ URL::asset('public/img/sample.pdf') }}"></div>
                                                </div>
                                            @endfor
                                        @endif
                    </div>
                </div>
            </div>
            <div class="panel-body" id="emailcomment" style="display: none">
                <div class="row"style="padding:10px;padding-top:20px;background: #eee">
                    <div class="form-group">
                                            <div class="col-md-6">
                                                <textarea id="message" name="message" rows="7" class="form-control resize_vertical" placeholder="Entrez votre commentaire"></textarea>
                                            </div>
                    </div>
                </div>
            </div>
        </div>
</div>

<style>
    .invoice{background-color: khaki;padding:1px;}
    label{font-weight:bold;}
</style>
<script src="{{  URL::asset('public/js/pdf/pdf.js') }}" type="text/javascript"></script>
<script src="{{  URL::asset('public/js/pdf/bootstrap-pdf-viewer.js') }}" type="text/javascript"></script>
 <script>
      var viewer;

      document.addEventListener('DOMContentLoaded', function() {
        viewer = new PDFViewer($('#viewer'));
      });
      function comment()
      {
        if ($("#emailcomment").is(":hidden")) 
            {
                $("#emailnpj").hide();
                $("#emailcomment").show();

            }
        else
        {
            
            $("#emailcomment").hide();
            $("#emailnpj").show();
        }
      }
    </script>
@endsection