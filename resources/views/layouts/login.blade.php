<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="generator" content="">

        <!-- favicon.ico -->
        <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">

        <!-- Saite Seal -->
        {{-- <img alt="gif" src="{{ asset('css/back/RapidSSL-SEAL.gif') }}" width="90" height="50"> --}}

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Tytle -->
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Bootstrap core CSS -->
        {{-- <link href="{{ asset('css/back/bootstrap.min.css') }}" rel="stylesheet"> --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

        <!-- Scripts -->
        {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
        {{-- <script src="{{ asset('js/jquery-3.6.0.min.js') }}" defer></script> --}}

        <!-- Fonts -->
        {{-- <link rel="dns-prefetch" href="//fonts.gstatic.com"> --}}
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="{{ asset('css/back/dashboard.css') }}" rel="stylesheet">

        <!-- jQuery -->
        <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script src = "https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

        <!-- My js -->
        <script src="{{asset('js/back/common.js')}}"></script>

        {{-- <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css"> --}}

        <!-- flash_message -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
        <script src = "https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        {{-- プラグイン(pace.min.js center-atom.css) loading-bar.css center-circle.css--}}
        <script type="text/javascript" src="{{ asset('js/back/pace.min.js') }}"></script>
        <link href="{{ asset('css/back/center-circle_d.css') }}" rel="stylesheet">

        <!-- Place your kit's code here -->
        {{-- <script src="https://kit.fontawesome.com/376cff10ff.js" crossorigin="anonymous"></script> --}}
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
        
        <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
            font-size: 3.5rem;
            }
        }
        </style>

        <!-- Custom styles for this template -->
        <link href="{{ asset('css/back/signin.css') }}" rel="stylesheet">
    </head>

    <body class="text-center">

        <main class="form-signin">
            <!-- フラッシュメッセージ -->
            @include('components.toastr')
            <div class="container">
                @yield('content')
            </div>
            {{-- @yield('content') --}}
        </main>

    </body>

</html>
