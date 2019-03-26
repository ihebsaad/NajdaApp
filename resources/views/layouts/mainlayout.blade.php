<!DOCTYPE html>
<html lang="fr">
<head>
    @include('layouts.partials.head')
</head>
<body>
@include('layouts.partials.top')

@include('layouts.partials.folders')

<div class="content row">
<div class="left column col-lg-3" >
@include('layouts.partials.left')
</div>
         <div class="column col-lg-6">


            <!-- Content -->
            <div class="panel panel-primary">
              <div class="panel-heading">
                                    <h4 class="panel-title">Bienvenue </h4>
                                    <span class="pull-right">
                                       <i class="fa fa-fw clickable fa-chevron-up"></i>
                                        
                                    </span>
                                </div>
                <div class="panel-body" style="display: block;">                
                @yield('content')
                </div>
            </div>
            <!-- /.content -->
        </div>

<div class="right column col-lg-3">
@include('layouts.partials.right')
</div></div><!---->
@include('layouts.partials.footer')
@include('layouts.partials.footer-scripts')
</body>
</html>