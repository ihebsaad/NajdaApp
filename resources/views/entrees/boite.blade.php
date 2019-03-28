
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
    </style>
    <a style="float:right;margin-right:20px;margin-bottom:25px;padding:3px 3px 3px 3px;border:1px solid #4fc1e9;" href="{{action('EmailController@sending')}}"><span role="button" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom" data-original-title="Envoyer un email"  class="fa fa-fw fa-envelope fa-2x"></span></a><br>
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
                        <div class="subject"><a  href="{{action('EntreesController@show', $entree['id'])}}" >{{$entree->sujet}}</a><small>{{$entree->emetteur}}</small></div>
                        <div class="stats">
                            <span><i class="fa fa-lg fa-fw fa-clock-o"></i><?php echo  date('d/m/Y H:i', strtotime($entree->reception)) ; ?></span>
                            <span><i class="fa fa-lg fa-fw fa-paperclip"></i><b>({{$entree->nb_attach}})</b> Attachements</span>
                        </div>
                      </div>
                </div>
            @endforeach
        
                
        {{ $entrees->links() }}
    </div>
@endsection