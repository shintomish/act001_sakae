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

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Tytle -->
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>

        <!-- Flow Scripts -->
        <script src="{{ asset('js/flow.js') }}" type="text/javascript"></script>

        <!-- upload Scripts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/resumable.js/1.1.0/resumable.min.js"></script>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">

        {{-- @yield('styles') --}}

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" ></script>

        <!-- Custom styles for this template -->
        <link href="{{ asset('css/back/dashboard.css') }}" rel="stylesheet">

        <!-- flash_message -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        <!-- Place your kit's code here -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">

        {{-- 2021/11/21  --}}
        <script type="text/javascript" src="{{ asset('js/back/pace.min.js') }}"></script>
        <link href="{{ asset('css/back/center-circle_d.css') }}" rel="stylesheet">
        {{-- 2021/11/21  --}}
        {{-- <link href="{{ asset('css/back/loading-circle.css') }}" rel="stylesheet"> --}}

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

    </head>

    <body>
        @csrf
        <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
            <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="{{ route('topclient') }}">{{ config('app.name', 'Laravel') }}</a>
            <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="form-control bg-dark w-100" type="text" placeholder="" aria-label="Search"></div>
            <ul class="navbar-nav px-3">
                <li class="nav-item text-nowrap">
                    <a class="nav-link" href="{{ route('logout') }}" onclick="return logout(event);">
                        <h5 >
                            <span class="text-danger">
                                <i class="fa fa-sign-out-alt"></i> ログアウト
                            </span>
                        </h5>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>

                    <script type="text/javascript">
                        function logout(event){
                                event.preventDefault();
                                var check = confirm("ログアウトしますか？");
                                if(check){
                                    document.getElementById('logout-form').submit();
                                }
                        }
                    </script>
                </li>
            </ul>
        </header>

        <div class="container-fluid">
            <div class="row">
                <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                    <div class="position-sticky pt-3">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link active">
                                    <i class="fas fa-address-card"></i>
                                    {{-- {{ $user->name }} --}}
                                    <?php $user = Auth::user(); ?>{{ $user->name }}
                                </a>
                            </li>
                            <li class="nav-item">

                            </li>


                        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                            {{-- <span>業務管理</span> --}}
                            <a class="link-secondary" href="#" aria-label="Add a new report">
                                <span data-feather="plus-circle"></span>
                            </a>
                        </h6>

                        <ul class="nav flex-column mb-2">

                        </ul>

                        <ul class="nav flex-column mb-2">

                        </ul>

                        <ul class="nav flex-column mb-2">

                        </ul>
                    </div>
                </nav>

                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">

                        </h1>

                        <div class="btn-toolbar mb-2 mb-md-0">
                            <div class="btn-group me-2">
                                <!--button type="button" class="w-100 btn btn-lg btn-primary" >Download</button-->
                            </div>
                            <div class="btn-group me-2">
                                <!--button type="button" class="w-100 btn btn-lg btn-primary" >Delete</button-->
                            </div>


                        </div>
                    </div>

                    <div id="page">
                        <div id="contents">
                            @yield('content')
                        </div><!-- / #contents -->
                    </div><!-- #page -->
                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" ></script>
                    @yield('scripts')

                    <!-- フラッシュメッセージ -->
                    @include('components.toastr')

                </main>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>

    </body>

</html>
