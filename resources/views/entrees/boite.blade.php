
@extends('layouts.mainlayout')
{{-- Page title --}}
@section('title')
    Boîte de réception | Najda Assistance
@stop
<?php
use App\Dossier ;
$dossiers = Dossier::get();
?>
{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('public/css/custom_css/layout_responsive.css') }}">
@stop
@section('content')
    <style>
        .uper {
            margin-top: 40px;
        }
        .email {background:#ececec; position:relative; margin-bottom:15px; padding:5px; border-style: solid!important; border-color: #cccccc; border-width: 1px!important;}
        .email-body{border:1px solid #bcbcbc; border-left:0; height:136px;}
        .email .subject{float:left; width:100%; font-size:15px;font-weight:600; color:#00a7e2;}
        .email .subject small{display:block; font-size:14px; color:#232323;}
        .email .stats{float:left; width:100%; margin-top:10px;}
        .email .stats span{float:left; margin-right:10px; font-size:14px;}
        .email .stats span i{margin-right:7px; color:#7ecce7;}
        .email .fav-box{position:absolute; right:10px; font-size:15px; top:4px; color:#E74C3C;}
        .nav > li > a {font-size:14px!important;}

    </style>
    <div class="row">
        <div class="col-sm-3 col-md-3">
            <?php use \App\Http\Controllers\EnvoyesController;     ?>
            <div class="panel">
                <div class="panel-body pan">
                    <ul class="nav nav-pills nav-stacked">
                        <li >
                            <a  href="{{ route('emails.sending') }}">
                                <span class="badge pull-right"></span>
                                <i class="fa fa-inbox fa-fw mrs"></i>
                                Rédiger un email
                            </a>
                        </li>
                        <li class="active">
                            <a   href="{{ route('boite') }}">
                                <span class="badge pull-right"></span>
                                <i class="fa fa-envelope-square fa-fw mrs"></i>
                                Boîte de réception
                            </a>
                        </li>
                        <li class="">
                            <a   href="{{ route('envoyes') }}">
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
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-9 ">
            <div class="row">
                <div class="col-md-9"><H2> Boîte de reception</H2></div>
                <div class="col-md-3"><a data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Envoyer un email" style="float:right;margin-right:20px;margin-bottom:25px;padding:3px 3px 3px 3px;border:1px solid #4fc1e9;" href="{{action('EmailController@sending')}}"><span role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Envoyer un email"  class="fa fa-fw fa-envelope fa-2x"></span></a><br>
                </div>
            </div>
   <a data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Envoyer un email"  style="float:right;margin-right:20px;margin-bottom:25px;padding:3px 3px 3px 3px;border:1px solid #4fc1e9;" href="{{action('EmailController@sending')}}"><span role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Envoyer un email"  class="fa fa-fw fa-envelope fa-2x"></span></a><br>
    <div class="uper">
        @if(session()->get('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div><br />
        @endif

            @foreach($entrees as $entree)
                <div class="email">
                    <div class="fav-box">
                        <a href="{{action('EntreesController@show', $entree['id'])}}" class="btn btn-sm btn-primary btn-responsive">
                            <i class="fa fa-lg fa-fw fa-eye"></i>
                            Ouvrir
                        </a>
                    </div>
                      <div class="media-body pl-3">
                        <div class="subject"><a  href="{{action('EntreesController@show', $entree['id'])}}" >{{$entree->sujet}}</a><small style="margin-top:10px;">{{$entree->emetteur}}</small></div>
                        <div class="stats">
                            <div class="row">
                                <div class="col-sm-8 col-md-8 col-lg-8">
                                    <span><i class="fa fa-fw fa-clock-o"></i><?php echo  date('d/m/Y H:i', strtotime($entree->reception)) ; ?></span>
                                    <span><i class="fa fa-fw fa-paperclip"></i><b>({{$entree->nb_attach}})</b> Attachements</span>
                                </div>
                                <div class="col-sm-4 col-md-4 col-lg-4">
                                    @if (!empty($entree->dossier))
                                    <button class="btn btn-sm btn-default"><b>REF: {{ $entree->dossier }}</b></button>
                                    @endif
                                </div>
                            </div>
                        </div>
                      </div>
                </div>
            @endforeach
        
                
        {{ $entrees->links() }}
    </div>
        </div>
    </div>

@endsection