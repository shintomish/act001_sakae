<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <script>
            history.pushState(null, null, location.href);
            window.addEventListener('popstate', (e) => {
                history.go(1);
            });
        </script>

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
                                    <i class="fas fa-user-alt"></i>
                                    組織
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('user.index')}}">
                                    <i class="fas fa-user-friends"></i>
                                    利用者管理
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('customer.index')}}">
                                    <i class="fas fa-users"></i>
                                    顧客管理
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('ctluserindex')}}">
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

                            <li class="nav-item">
                                <a class="nav-link" href="{{route('operationindex')}}">
                                    <i class="fas fa-clipboard"></i>
                                    顧客ログイン状態
                                </a>
                            </li>

                            {{-- 2023/09/04 1=shintomi.sh@gmail.com 9=dummy09@gmail.com --}}
                            {{-- 2022/11/05 actlogindex --}}
                            @if($userid == 1 || $userid == 9 )
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('actlogindex')}}">
                                        <i class="fas fa-clipboard"></i>
                                        操作履歴
                                    </a>
                                </li>
                            @endif

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
                                    <i class="fas fa-file-upload"></i>
                                    アップロードユーザー
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('newsrepo.index')}}">
                                    <i class="fas fa-edit"></i>
                                    News・メール配信作成
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('spedelidate.input')}}">
                                    <i class="fas fa-clipboard"></i>
                                    納期特例
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('yrendadjust.input')}}">
                                    <i class="fas fa-wallet"></i>
                                    年末調整
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('notaccount.index')}}">
                                    <i class="fas fa-wallet"></i>
                                    会計未処理事業者
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('advisorsfee.input')}}">
                                    <i class="fas fa-wallet"></i>
                                    顧問料金編集
                                </a>
                            </li>
                            {{-- 2023/09/26 --}}
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('invoiceconfirm.index')}}">
                                    <i class="fas fa-wallet"></i>
                                    請求書データ送信確認ページ
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('invoice.index')}}">
                                    <i class="fas fa-file-upload"></i>
                                    請求書データファイルアップロード
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('wokprocbook.input')}}">
                                    <i class="fas fa-address-book"></i>
                                    税理士業務処理簿
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('businesname.index')}}">
                                    <i class="fas fa-file-alt"></i>
                                    業務名管理
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('progrecheck.input')}}">
                                    <i class="fas fa-pen-square"></i>
                                    進捗チェック
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('schedule.index')}}">
                                    <i class="fas fa-calendar-check"></i>
                                    スケジュール
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>

                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">

                        <!-- 検索エリア -->
                        @switch ($common_no)
                            @case ('00_ope')
                                <!-- タイトル -->
                                <h3>顧客ログイン状態</h3>
                                <form  class="form-inline my-2 my-lg-0 ml-2" action="{{route('operationserch')}}" method="GET">
                                @break;
                            @case ('00_1')
                                <!-- タイトル -->
                                <h3>利用者管理</h3>
                                <form  class="form-inline my-2 my-lg-0 ml-2" action="{{route('userserch')}}" method="GET">
                                @break;
                            @case ('00_2')
                                <!-- タイトル -->
                                <h3>顧客管理</h3>
                                <form  class="form-inline my-2 my-lg-0 ml-2" action="{{route('customerserch')}}" method="GET">
                                @break;
                            @case ('00_3')
                                <!-- タイトル -->
                                <!-- TOP -->
                                @break;
                            @case ('00_4')
                                <!-- タイトル -->
                                <h3>複数法人</h3>
                                <form  class="form-inline my-2 my-lg-0 ml-2" action="{{route('ctluserserch')}}" method="GET">
                                @break;
                            @case ('01')
                                <!-- layouts.upload 検索が日付でdatapickを使用-->
                                {{-- <h3>アップロードユーザー一覧</h3> --}}
                                @break;
                            @case ('02')
                                <!-- layouts.costomer-->
                                {{-- <h3>NEWS作成</h3> --}}
                                @break;
                            <!-- 03以降 顧客名検索 -->
                            @case ('03')
                                <!-- タイトル -->
                                <h3>納期特例</h3>
                                <form  class="form-inline my-2 my-lg-0 ml-2" action="{{route('spedelidate_custom')}}" method="GET">
                                @break;
                            @case ('04')
                                <!-- タイトル -->
                                <h3>年末調整</h3>
                                <form  class="form-inline my-2 my-lg-0 ml-2" action="{{route('yrendadjust_custom')}}" method="GET">
                                @break;
                            @case ('05')
                                <!-- タイトル -->
                                <h3>会計未処理事業者</h3>
                                <form  class="form-inline my-2 my-lg-0 ml-2" action="{{route('notaccounth_custom')}}" method="GET">
                                @break;
                            @case ('06')
                                <!-- タイトル -->
                                <h3>顧問料金編集</h3>
                                <form  class="form-inline my-2 my-lg-0 ml-2" action="{{route('advisorsfee_custom')}}" method="GET">
                                @break;
                            @case ('06_1')
                                <!-- タイトル -->
                                <h3>請求書データ送信確認ページ</h3>
                                <form  class="form-inline my-2 my-lg-0 ml-2" action="{{route('invoiceconfirm_custom')}}" method="GET">
                                @break;
                            @case ('06_2')
                                <!-- タイトル -->
                                <h3>請求書データファイルアップロード</h3>
                                <form  class="form-inline my-2 my-lg-0 ml-2" action="{{route('invoice_custom')}}" method="GET">
                                @break;
                            @case ('07')
                                <!-- タイトル -->
                                <h3>税理士業務処理簿</h3>
                                <form  class="form-inline my-2 my-lg-0 ml-2" action="{{route('wokprocbookserch')}}" method="GET">
                                @break;
                            @case ('07_2')
                                <!-- タイトル -->
                                <h3>税理士業務処理簿</h3>
                                <form  class="form-inline my-2 my-lg-0 ml-2" action="{{route('wokprocbook_custom')}}" method="GET">
                                @break;
                            @case ('08')
                                <!-- タイトル -->
                                <h3>業務名管理</h3>
                                <form  class="form-inline my-2 my-lg-0 ml-2" action="{{route('businesname_custom')}}" method="GET">
                                @break;

                            @case ('09')
                                <!-- タイトル -->
                                <h3>進捗チェック</h3>
                                <form  class="form-inline my-2 my-lg-0 ml-2" action="{{route('progreserch_custom')}}" method="GET">
                                @break;

                            @case ('09_1')
                                <!-- タイトル input -->
                                <h3>進捗チェック</h3>
                                <form  class="form-inline my-2 my-lg-0 ml-2" action="{{route('progreserch_input')}}" method="GET">
                                @break;

                            @case ('10')
                                <!-- タイトル -->
                                <h3>スケジュール</h3>
                                <form  class="form-inline my-2 my-lg-0 ml-2" action="{{route('schedule_custom')}}" method="GET">
                                @break;

                            @case ('11')
                                <!-- タイトル -->
                                <h3>会社申請・設立</h3>

                                @break;

                            @default:
                                @break;
                        @endswitch

                        {{-- <form  class="form-inline my-2 my-lg-0 ml-2" action="{{route('wokprocbookserch')}}" method="GET"> --}}
                            @csrf
                            @method('get')
                            <div class='btn-toolbar' role="toolbar">
                                <div class="input-group">
                                    {{-- 進捗チェック・スケジュール --}}
                                    <!-- 年あり 顧客名あり -->
                                    <!-- 納期特例 03 -->
                                    <!-- 年末調整 04 -->
                                    <!-- 顧問料金 06 -->
                                    <!-- 税理士業務処理簿 07 -->
                                    <!-- 業務名管理 08 -->
                                    <!-- 進捗チェック 09 -->
                                    <!-- スケジュール 10 -->
                                    @if( $common_no == '07'   || 
                                         $common_no == '07_2' || 
                                         $common_no == '03'   || 
                                         $common_no == '04'   || 
                                         $common_no == '06'
                                        )

                                    <select style="margin-right:5px;" class="custom-select" id="year" name="year">
                                        @foreach ($loop_year_flg as $loop_year_flg2)
                                            @if ($loop_year_flg2['no']==0)
                                                <option disabled value="{{ $loop_year_flg2['no'] }}">{{ $loop_year_flg2['name'] }}</option>
                                            @else
                                                @if ($loop_year_flg2['no']==$nowyear)
                                                    <option selected value="{{ $loop_year_flg2['no'] }}">{{ $loop_year_flg2['name'] }}</option>
                                                @else
                                                    <option value="{{ $loop_year_flg2['no'] }}">{{ $loop_year_flg2['name'] }}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </select>
                                    @endif
                                    @if( $common_no == '09' || $common_no == '09_1' || $common_no == '10' || $common_no == '11')
                                        <select style="margin-right:5px;" class="custom-select" id="year" name="year">
                                            @foreach ($loop_year_flg as $loop_year_flg2)
                                                @if ($loop_year_flg2['no']==0)
                                                    <option disabled value="{{ $loop_year_flg2['no'] }}">{{ $loop_year_flg2['name'] }}</option>
                                                @else
                                                    @if ($loop_year_flg2['no']==$nowyear)
                                                        <option selected value="{{ $loop_year_flg2['no'] }}">{{ $loop_year_flg2['name'] }}</option>
                                                    @else
                                                        <option value="{{ $loop_year_flg2['no'] }}">{{ $loop_year_flg2['name'] }}</option>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </select>
                                        <select style="margin-right:5px;" class="custom-select" id="sel_custom" name="sel_custom" >
                                            @foreach ($customselects as $customselects2)
                                                {{-- ALL(9999999) --}}
                                                @if ($customselects2->custm_id==$int_custom)
                                                    <option selected="selected" value={{$customselects2->custm_id}}>{{ $customselects2->business_name }}</option>
                                                @else
                                                    <option value={{$customselects2->custm_id}}>{{ $customselects2->business_name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-secondary btn_sm">検索</button>
                                    @else
                                        @if( $common_no == '00_3' )
                                        @else
                                            <input  style="margin-right:5px;" type="text" value="{{$keyword2}}" name="keyword" class="form-control" placeholder="顧客名検索">
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-secondary btn_sm">検索</button>
                                            </div>
                                        @endif

                                    @endif

                                </div>
                            </div>
                        </form>
                        <!-- 検索エリア -->
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
