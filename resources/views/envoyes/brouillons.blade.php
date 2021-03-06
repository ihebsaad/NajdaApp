
@extends('layouts.mainlayout')
{{-- Page title --}}
@section('title')
    Boîte d'envoi | Najda Assistance
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
            <?php use \App\Http\Controllers\EntreesController;     ?>            <div class="panel">
                <div class="panel-body pan">
                    <ul class="nav nav-pills nav-stacked">

                        <li class="">
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
                                Mes Envoyées
                            </a>
                        </li>
                        <li class="">
                            <a   href="{{ route('envoyes.tous') }}" style="cursor:default">
                            <span class="badge pull-right"></span>
                                <i class="fa fa-paper-plane fa-fw mrs"></i>
                               Tous Envoyées
                            </a>
                         </li>							
                        <li class="active">
                            <a   href="#" style="cursor:default">

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
    <div class="row">
        <div class="col-md-6"><H2> Brouillons</H2></div>
       </div>


    <div class="uper">
 

        @foreach($envoyes as $envoye)
            <div class="email">
                <div class="fav-box">
                    <a href="{{action('EnvoyesController@destroy', $envoye['id'])}}" class="btn btn-sm btn-danger btn-responsive" data-toggle="tooltip" data-tooltip="tooltip" data-placement="bottom"  data-original-title="Supprimer">
                        <i class="fa fa-lg fa-fw fa-trash-alt"></i>

                    </a>
                </div>
<a  href=" {{ route('emails.envoimailbr', $envoye['id']) }}" >
                <div class="media-body pl-3">
                    <div class="subject">{{$envoye->description}}<small style="margin-top:10px;">{{$envoye->destinataire}}</small></div>
                    <div class="stats">
                        <div class="row">
                            <div class="col-sm-8 col-md-8 col-lg-8">
                                <span><i class="fa fa-fw fa-clock-o"></i><?php echo  date('d/m/Y H:i', strtotime($envoye->created_at)) ; ?></span>
                             </div>


                        </div>
                    </div>
                </div>
            </div>
</a>
        @endforeach


        {{ $envoyes->links() }}
    </div>

        </div>
    </div>
@endsection
