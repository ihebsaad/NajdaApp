<!DOCTYPE html>
<html lang="fr">
<head>

    <link rel="shortcut icon" href="{{ asset('public/img/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('public/img/favicon.ico') }}" type="image/x-icon">
    <meta charset="UTF-8">
    <title>Najda Assistances - Supervision</title>
     @include('layouts.partials.head')
</head>
<body>
@include('layouts.partials.topsup')

<div class="content row">
 
                 @yield('content')

 
</div><!---->
@include('layouts.partials.footer')
@include('layouts.partials.footer-scripts')
</body>
</html>

