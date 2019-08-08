<!DOCTYPE html>
<html lang="fr">
<head>
    @include('layouts.partials.head')
</head>
<body>
@include('layouts.partials.top')

@include('layouts.partials.news')
@include('layouts.partials.folders')

<div class="content row">
<div class="left column col-lg-3" >
@include('layouts.partials.left')
</div>
         <div id="mainc" class="column col-lg-6">
         @if ($errors->any())
             <div class="alert alert-danger">
                 <ul>
                     @foreach ($errors->all() as $error)
                         <li>{{ $error }}</li>
                     @endforeach
                 </ul>
             </div><br />
         @endif

    @if (!empty( Session::get('success') ))
        <div class="alert alert-success">

        {{ Session::get('success') }}
        </div>

    @endif

            <!-- Content -->
            <div class="panel panel-primary"  style="overflow:hidden">
              <div class="panel-heading">
                                    <h4 id="kbspaneltitle" class="panel-title">  </h4>
                                    <span class="pull-right">
                                         <i id="open" class="fa fa-lg fa-window-maximize"></i>
                                         <i style="margin-left:20px" id="close" class="fa fa-lg   fa-window-restore"></i>
                                    </span>
                                </div>
                <div class="panel-body" style="display: block;min-height:450px;">
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

