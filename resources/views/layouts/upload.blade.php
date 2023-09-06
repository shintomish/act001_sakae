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
        {{-- <script src="{{ asset('js/jquery-3.6.0.min.js') }}" defer></script> --}}

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <!-- Bootstrap core CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

        {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script> --}}

        <!-- datetimepicker CSS -->
        {{-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" />

        <!-- Custom styles for this template -->
        <link href="{{ asset('css/back/dashboard.css') }}" rel="stylesheet">

        <!-- flash_message -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

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

    </head>

    <body>
        <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
            <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="{{ route('top') }}">{{ config('app.name', 'Laravel') }}</a>
            <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="form-control bg-dark w-100" type="text" placeholder="" aria-label="Search"></div>
            <ul class="navbar-nav px-3">
                {{-- 2021/11/19 変更 --}}
                {{-- <li class="nav-item text-nowrap">
                    <a class="nav-link" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">{{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li> --}}
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
                                    <a class="nav-link" href="{{route('top')}}">
                                    <i class="fas fa-laptop-house"></i>
                                    ホーム
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('organization.index')}}">
                                    {{-- <span data-feather="users"></span> --}}
                                    <i class="fas fa-user-alt"></i>
                                    組織
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('user.index')}}">
                                    {{-- <span data-feather="users"></span> --}}
                                    <i class="fas fa-user-friends"></i>
                                    利用者管理
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('customer.index')}}">
                                    {{-- <span data-feather="users"></span> --}}
                                    <i class="fas fa-users"></i>
                                    顧客管理
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('ctluserindex')}}">
                                    {{-- <span data-feather="users"></span> --}}
                                    <i class="fas fa-users"></i>
                                    複数法人
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('annualupdateedit')}}">
                                    <i class="fas fa-wrench"></i>
                                    年度更新
                                </a>
                            </li>
                            {{-- 2023/09/04 以下「顧客ログイン状態」追加--}}
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('operationindex')}}">
                                    <i class="fas fa-clipboard"></i>
                                    顧客ログイン状態
                                </a>
                            </li>
                        </ul>

                        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                            <span>業務管理</span>
                            <a class="link-secondary" href="#" aria-label="Add a new report">
                              <span data-feather="plus-circle"></span>
                            </a>
                        </h6>

                        <ul class="nav flex-column mb-2">
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('uploaduser')}}">
                                    {{-- <span data-feather="file"></span> --}}
                                    <i class="fas fa-file-upload"></i>
                                    アップロードユーザー
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('newsrepo.index')}}">
                                    {{-- <span data-feather="file-text"></span> --}}
                                    <i class="fas fa-edit"></i>
                                    News・メール配信作成
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('spedelidate.input')}}">
                                    {{-- <span data-feather="file-text"></span> --}}
                                    <i class="fas fa-clipboard"></i>
                                    納期特例
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('yrendadjust.input')}}">
                                    {{-- <span data-feather="file-text"></span> --}}
                                    <i class="fas fa-wallet"></i>
                                    年末調整
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('notaccount.index')}}">
                                    {{-- <span data-feather="file-text"></span> --}}
                                    <i class="fas fa-wallet"></i>
                                    会計未処理事業者
                                </a>
                            </li>
                            {{-- 2022/05/20 --}}
                            {{-- 顧問料金 不要 --}}
                            {{-- <li class="nav-item">
                                <a class="nav-link" href="{{route('advisorsfee.index')}}">
                                    <i class="fas fa-wallet"></i>
                                    顧問料金
                                </a>
                            </li> --}}
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('wokprocbookindex')}}">
                                    {{-- <span data-feather="file"></span> --}}
                                    <i class="fas fa-address-book"></i>
                                    税理士業務処理簿
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('businesname.index')}}">
                                    {{-- <span data-feather="file-text"></span> --}}
                                    <i class="fas fa-file-alt"></i>
                                    業務名管理
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('progrecheck.input')}}">
                                    {{-- <span data-feather="file-text"></span> --}}
                                    <i class="fas fa-pen-square"></i>
                                    進捗チェック
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('schedule.index')}}">
                                    {{-- <span data-feather="file-text"></span> --}}
                                    <i class="fas fa-tasks"></i>
                                    スケジュール
                                </a>
                            </li>
                            {{-- 2022/05/20 --}}
                            {{-- 会社申請・設立 不要 --}}
                            {{-- <li class="nav-item">
                                <a class="nav-link" href="{{route('applestabl.index')}}">
                                    <i class="fas fa-edit"></i>
                                    会社申請・設立
                                </a>
                            </li> --}}
                    </div>
                </nav>

                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">

                        <h3>アップロードユーザー一覧</h3>

                        <!-- 検索エリア -->
                        <form  class="form-inline my-2 my-lg-0 ml-2" action="{{route('uploadserch')}}" method="GET">
                            @csrf
                            @method('get')
                            <div class='btn-toolbar' role="toolbar">
                                <div class='col-sm-10'>
                                    <div class="btn-group me-2">
                                        <div class='input-group date' id='datetimepicker'>
                                            <input type='text' class="form-control" name="keyword" placeholder="日付を入力" aria-label="検索.."/>
                                        <span class="input-group-addon">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                                <input type="submit" value="検索" class="btn btn-sm btn-info">
                            </div>
                        </form>
                        <!-- 検索エリア -->
                    </div>

                    <style>
                        /* カレンダーアイコン */
                        .input-group-addon{
                            color:#3c763d;
                            background-color:#dff0d8;
                            border-color:#3c763d;
                            padding:12px 12px;
                            font-size:14px;font-weight:400;line-height:1;
                            border:1px solid #ccc;border-radius:4px
                        }
                        .datepicker-days th.dow:first-child,
                        .datepicker-days td:first-child {
                            color: #f00;
                        }
                        .datepicker-days th.dow:last-child,
                        .datepicker-days td:last-child {
                            color: #00f;
                        }
                    </style>

                    <!-- フラッシュメッセージ 使用しない -->
                    {{-- @include('components.toastr') --}}

                    <div class="container">
                        @yield('content')
                    </div>

                </main>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>

    </body>

    {{-- datetimepicker --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment-with-locales.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

    <script type="text/javascript">
        // $(function() {
        //    $('#datetimepicker').datetimepicker();
        // });
        $(function() {
            $('#datetimepicker').datetimepicker({
                dayViewHeaderFormat: 'YYYY年 MMMM',
                extraFormats:false,
                // step size
                stepping: 1,
                // min/max dates
                minDate:false,
                maxDate:false,
                // uses current date/time
                useCurrent:true,

                tooltips: {
                    close: '閉じる',
                    selectMonth: '月を選択',
                    prevMonth: '前月',
                    nextMonth: '次月',
                    selectYear: '年を選択',
                    prevYear: '前年',
                    nextYear: '次年',
                    selectTime: '時間を選択',
                    selectDate: '日付を選択',
                    prevDecade: '前期間',
                    nextDecade: '次期間',
                    selectDecade: '期間を選択',
                    prevCentury: '前世紀',
                    nextCentury: '次世紀',
                    time:'fa fa-clock-o'
                },
                format: 'YYYY/MM/DD',
                locale: 'ja',
                // uses strict
                useStrict:false,
                // displays side by side
                sideBySide:false,
                // disabled days of the week
                daysOfWeekDisabled:false,
                // shows the week of the year
                calendarWeeks:false,
                // 'decades','years','months','days'
                viewMode:'days',
                // toolbar placement
                toolbarPlacement:'default',

                icons: {
                    time: 'far fa-clock',
                    date: 'far fa-calendar-alt',
                    up: 'fas fa-arrow-up',
                    down: 'fas fa-arrow-down',
                    previous:'fa fa-chevron-left',
                    next:'fa fa-chevron-right',
                    today:'fa fa-calendar-check-o',
                    clear:'fa fa-delete',
                    close:'fa fa-times'
                }
            });
        });

    </script>

</html>
