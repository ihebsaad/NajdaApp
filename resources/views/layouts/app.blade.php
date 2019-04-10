<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>Najda Assistance</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- Styles -->

    <!-- global level css -->
    <link href="{{ URL::asset('public/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/vendors/animate/animate.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="{{ asset('public/img/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('public/img/favicon.ico') }}" type="image/x-icon">


</head>
<body>
<div id="app">


    @yield('content')
</div>

<!-- Scripts -->
<script src="{{ asset('/public/js/app.js') }}"></script>
</body>
</html>

<style>
.panel-heading{background-color: #639BE4!important;color:white!important;}
    body {
        width: 100wh;
        height: 90vh;
      /*  color: #fff;*/
        background: linear-gradient(-45deg, #EE7752, #E73C7E, #23A6D5, #23D5AB);
        background-size: 400% 400%;
        -webkit-animation: Gradient 15s ease infinite;
        -moz-animation: Gradient 15s ease infinite;
        animation: Gradient 15s ease infinite;
    }

    @-webkit-keyframes Gradient {
        0% {
            background-position: 0% 50%
        }
        50% {
            background-position: 100% 50%
        }
        100% {
            background-position: 0% 50%
        }
    }

    @-moz-keyframes Gradient {
        0% {
            background-position: 0% 50%
        }
        50% {
            background-position: 100% 50%
        }
        100% {
            background-position: 0% 50%
        }
    }

    @keyframes Gradient {
        0% {
            background-position: 0% 50%
        }
        50% {
            background-position: 100% 50%
        }
        100% {
            background-position: 0% 50%
        }
    }

    h1,
    h6 {
        font-family: 'Open Sans';
        font-weight: 300;
        text-align: center;
        position: absolute;
        top: 45%;
        right: 0;
        left: 0;
    }

</style>