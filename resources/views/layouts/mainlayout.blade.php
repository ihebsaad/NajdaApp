<!DOCTYPE html>
<html lang="fr">
<head>
    @include('layouts.partials.head')
</head>
<body>
@include('layouts.partials.top')
@include('layouts.partials.folders')
<div class="content row">
<div class="left column col-lg-2" >
@include('layouts.partials.left')
</div><div class="operations column col-lg-8" >
@yield('content')
</div><div class="right column col-lg-2">
@include('layouts.partials.right')
</div></div><!---->
@include('layouts.partials.footer')
@include('layouts.partials.footer-scripts')
</body>
</html>