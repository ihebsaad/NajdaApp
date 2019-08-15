<!DOCTYPE html>
<html lang="fr">
<head>
    @include('layouts.partials.head')
</head>
<body>
@include('layouts.partials.top')

<div class="content row">
 
                 @yield('content')

 
</div><!---->
@include('layouts.partials.footer')
@include('layouts.partials.footer-scripts')
</body>
</html>

