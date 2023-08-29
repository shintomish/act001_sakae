<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <script>
            history.pushState(null, null, location.href);
            window.addEventListener('popstate', (e) => {
                history.go(1);
            });
        </script>

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
        {{-- <script src="{{asset('js/back/common.js')}}"></script> --}}

        {{-- <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css"> --}}

        <!-- flash_message -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
        <script src = "https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        {{-- プラグイン(pace.min.js center-atom.css) loading-bar.css center-circle.css--}}
        {{-- <script type="text/javascript" src="{{ asset('js/back/pace.min.js') }}"></script> --}}
        {{-- <link href="{{ asset('css/back/center-circle_d.css') }}" rel="stylesheet"> --}}

        <!-- Place your kit's code here -->
        {{-- <script src="https://kit.fontawesome.com/376cff10ff.js" crossorigin="anonymous"></script> --}}
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">

        {{-- <script src="https://unpkg.com/vue@3.2.31/dist/vue.global.js"></script> --}}

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
        <div id=’app’></div>
        <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
            <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="{{ route('topclient') }}">{{ config('app.name', 'Laravel') }}</a>
            <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="form-control bg-dark w-100" type="text" placeholder="" aria-label="Search"></div>
            <ul class="navbar-nav px-3">
                <li class="nav-item text-nowrap">
                    <a class="nav-link" href="{{ route('logout') }}" onclick="return logout(event);">
                        {{-- <span class="text-danger"> --}}
                            {{-- <i class="fa fa-sign-out-alt"></i> ログアウト --}}
                        {{-- </span> --}}
                        {{-- 2022/10/17 --}}
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
                                <a class="nav-link">
                                    <a class="nav-link" href="{{route('topclient')}}">
                                    {{-- <a class="nav-link" href="{{route('media-library')}}"> --}}
                                    <i class="fas fa-laptop-house"></i>
                                    ホーム
                                </a>
                            </li>
                            {{-- <li class="nav-item"> --}}
                                {{-- <a class="nav-link"> --}}
                                    {{-- <a class="nav-link" href="{{route('camera_capture')}}"> --}}
                                    {{-- <i class="fas fa-file-upload"></i> --}}
                                    {{-- カメラアップロード --}}
                                {{-- </a> --}}
                            {{-- </li> --}}

                            {{-- 2022/10/25 --}}
                            {{-- <li class="nav-item">
                                <a class="nav-link" href="{{route('chatclientin')}}">
                                    <i class="fas fa-wifi"></i>
                                    チャット
                                </a>
                            </li> --}}
                        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                            <span>業務管理</span>
                            <a class="link-secondary" href="#" aria-label="Add a new report">
                                <span data-feather="plus-circle"></span>
                            </a>
                        </h6>

                        <ul class="nav flex-column mb-2">
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('transhistory')}}">
                                {{-- <a class="nav-link" href=""> --}}
                                    {{-- <span data-feather="file"></span> --}}
                                    <i class="fas fa-file-upload"></i>
                                    送信ファイル一覧
                                </a>
                            </li>
                        </ul>

                        <ul class="nav flex-column mb-2">
                            <li class="nav-item">
                                <a class="nav-link" target="_blank" href="{{route('topclinewpdf')}}">
                                {{-- <a class="nav-link" href=""> --}}
                                    {{-- <span data-feather="file"></span> --}}
                                    <i class="fas fa-download"></i>
                                    <?php echo $latestinfodate; ?>
                                </a>
                            </li>
                        </ul>

                        <ul class="nav flex-column mb-2">
                            <li class="nav-item">
                                <a class="nav-link" target="_blank" href="{{route('topclientpdf')}}">
                                {{-- <a class="nav-link" href=""> --}}
                                    {{-- <span data-feather="file"></span> --}}
                                    <i class="fas fa-download"></i>
                                    マニュアル
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>

                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h1 class="h2">
                            <!--button class="btn btn-secondary btn-lg dropdown-toggle" type="button" id="dropdownMenuButtonLG" data-bs-toggle="dropdown" aria-expanded="false">
                            ALLUSER
                            </button-->
                        </h1>

                        <div class="btn-toolbar mb-2 mb-md-0">
                            <div class="btn-group me-2">
                                <!--button type="button" class="w-100 btn btn-lg btn-primary" >Download</button-->
                            </div>
                            <div class="btn-group me-2">
                                <!--button type="button" class="w-100 btn btn-lg btn-primary" >Delete</button-->
                            </div>
                            {{-- <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle"> --}}
                                {{-- <span data-feather="calendar"></span> --}}
                                {{-- This week --}}
                            {{-- </button> --}}

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
                    {{-- @include('components.toastr') --}}

                </main>
            </div>
        </div>
        <script src="{{ asset('js/app.js')}}" defer></script>

    </body>

</html>
