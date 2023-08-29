@extends('layouts.api_index')

@section('content')
    <h2></h2>
    <div class="text-right">
        {{-- <a class="btn btn-success btn-sm mr-auto" href="{{route('customer.create')}}">新規登録</a> --}}
    </div>
    @if ($errors->any())
        <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        </div>
    @endif
    <style>
        /* スクロールバーの実装 */
        .table_sticky {
            display: block;
            overflow-y: scroll;
            /* height: calc(100vh/2); */
            height: 450px;
            border:1px solid;
            border-collapse: collapse;
        }
        .table_sticky thead th {
            position: -webkit-sticky;
            position: sticky;
            top: 0;
            left: 0;
            color: #fff;
            background: rgb(180, 226, 11);
            &:before{
                content: "";
                position: absolute;
                top: -1px;
                left: -1px;
                width: 100%;
                height: 100%;
                border: 1px solid #ccc;
            }
        }

        table{
            width: 1800px;
        }
        th,td{
            width: 280px;   /* 200->280 */
            height: 10px;
            vertical-align: middle;
            padding: 0 15px;
            border: 1px solid #ccc;
        }
        .fixed01,
        .fixed02{
            /* position: -webkit-sticky; */
            position: sticky;
            top: 0;
            left: 0;
            color: rgb(8, 8, 8);
            background: #333;
            &:before{
                content: "";
                position: absolute;
                top: -1px;
                left: -1px;
                width: 100%;
                height: 100%;
                border: 1px solid #ccc;
            }
        }
        .fixed01{
            z-index: 2;
        }
        .fixed02{
            z-index: 1;
        }
    </style>

    <div class="row">
        <div class="col-md-10 order-md-2 mb-4">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-success">今月の申告 ( {{ $count2 }} 社)</span>
                {{-- <span class="badge badge-secondary badge-pill">3</span> --}}
            </h4>
            {{-- <table class="table table-striped table-borderd"> --}}
            {{-- <table class="table table-striped table-borderd table_sticky"> --}}
        {{-- table-responsive text-nowrap add scope=row 2022/11/09--}}
        <table class="table table-responsive text-nowrap table-striped table-borderd table_sticky">
                <form method="GET" action="{{ route('top.index') }}">
                    @csrf
                    @method('get')
                <thead>
                    <tr>
                        <th scope="row" class ="fixed01" >社名</th>
                        <th scope="row" class ="fixed02" >法人</th>
                        <th scope="row" class ="fixed02" >決算</th>
                        <th scope="row" class ="fixed02" >会計</th>
                        <th scope="row" class ="fixed02" >達人</th>
                        <th scope="row" class ="fixed02" >税理士確認</th>
                        <th scope="row" class ="fixed02" >申告</th>
                        <th scope="row" class ="fixed02" >消費税</th>
                    </tr>
                </thead>

                <tbody>

                    @if($customers2->count())
                        @foreach($customers2 as $customer)
                        <tr>
                            {{-- <td>{{ $customer->business_name }}</td> --}}
                            <td>
                            <a href="{{ route('customer.edit',$customer->id)}}">{{ $customer->business_name }}</a>
                            </td>
                            <td>
                                {{-- //法人・個人 App/Providers/AppServiceProviderのboot--}}
                                @foreach ($loop_individual_class as $loop_individual_class2)
                                    @if ($loop_individual_class2['no']==$customer->individual_class)
                                        {{ $loop_individual_class2['name'] }}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach ($loop_closing_month as $loop_closing_month2)
                                    @if ($loop_closing_month2['no']==$customer->closing_month)
                                        {{$loop_closing_month2['name']}}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                <select class="custom-select d-block w-100" id="bill_flg_{{$customer->id}}" name="bill_flg_{{$customer->id}}">
                                @foreach ($loop_circle_cross as $loop_circle_cross2)
                                    @if ($loop_circle_cross2['no']==$customer->bill_flg)
                    <option selected="selected" value={{$loop_circle_cross2['no']}}>{{ $loop_circle_cross2['name'] }}</option>
                                    @else
                                        @if ($loop_circle_cross2['no']==0)
                                        <option  disabled value={{$loop_circle_cross2['no']}}>{{ $loop_circle_cross2['name'] }}</option>
                                        @else
                                        <option value={{$loop_circle_cross2['no']}}>{{ $loop_circle_cross2['name'] }}</option>
                                        @endif
                                    @endif
                                @endforeach
                                </select>
                            </td>
                            <td>
                                <select class="custom-select d-block w-100" id="adept_flg_{{$customer->id}}" name="adept_flg_{{$customer->id}}">
                                @foreach ($loop_circle_cross as $loop_circle_cross2)
                                    @if ($loop_circle_cross2['no']==$customer->adept_flg)
                    <option selected="selected" value={{$loop_circle_cross2['no']}}>{{ $loop_circle_cross2['name'] }}</option>
                                    @else
                                        @if ($loop_circle_cross2['no']==0)
                                        <option  disabled value={{$loop_circle_cross2['no']}}>{{ $loop_circle_cross2['name'] }}</option>
                                        @else
                                        <option value={{$loop_circle_cross2['no']}}>{{ $loop_circle_cross2['name'] }}</option>
                                        @endif
                                    @endif
                                @endforeach
                                </select>
                            </td>
                            <td>
                        <select class="custom-select d-block w-100" id="confirmation_flg_{{$customer->id}}" name="confirmation_flg_{{$customer->id}}">
                                    @foreach ($loop_circle_cross as $loop_circle_cross2)
                                        @if ($loop_circle_cross2['no']==$customer->confirmation_flg)
                                        <option selected="selected" value={{$loop_circle_cross2['no']}}>{{ $loop_circle_cross2['name'] }}</option>
                                        @else
                                            @if ($loop_circle_cross2['no']==0)
                                            <option  disabled value={{$loop_circle_cross2['no']}}>{{ $loop_circle_cross2['name'] }}</option>
                                            @else
                                            <option value={{$loop_circle_cross2['no']}}>{{ $loop_circle_cross2['name'] }}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                            <td>
                            <select class="custom-select d-block w-100" id="report_flg_{{$customer->id}}" name="report_flg_{{$customer->id}}">

                                    @foreach ($loop_circle_cross as $loop_circle_cross2)
                                        @if ($loop_circle_cross2['no']==$customer->report_flg)
                        <option selected="selected" value={{$loop_circle_cross2['no']}}>{{ $loop_circle_cross2['name'] }}</option>
                                        @else
                                            @if ($loop_circle_cross2['no']==0)
                                            <option  disabled value={{$loop_circle_cross2['no']}}>{{ $loop_circle_cross2['name'] }}</option>
                                            @else
                                            <option value={{$loop_circle_cross2['no']}}>{{ $loop_circle_cross2['name'] }}</option>

                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                {{-- //2022/05/20 --}}
                                {{-- // `consumption_tax` int(11) DEFAULT 1 COMMENT '消費税 1:簡易 2:本則 3:免税', --}}
                                <select class="custom-select d-block w-100" id="consumption_tax_{{$customer->id}}" name="consumption_tax_{{$customer->id}}" onchange="changeColor(this)">

                                    @foreach ($loop_consumption_tax_flg as $loop_consumption_tax_flg2)
                                        @if ($loop_consumption_tax_flg2['no']==$customer->consumption_tax)
    <option selected="selected" value={{$loop_consumption_tax_flg2['no']}}>{{ $loop_consumption_tax_flg2['name'] }}</option>
                                        @else
                                            @if ($loop_consumption_tax_flg2['no']==0)
    <option  disabled value={{$loop_consumption_tax_flg2['no']}}>{{ $loop_consumption_tax_flg2['name'] }}</option>
                                            @else
    <option value={{$loop_consumption_tax_flg2['no']}}>{{ $loop_consumption_tax_flg2['name'] }}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <script type="text/javascript">
                            //---------------------------------------------------------------
                            //--会計フラグプルダウンイベントハンドラ
                            //---------------------------------------------------------------
                            $('select[name^="bill_flg_"]').change( function(e){
                                // alert('会計フラグClick');
                                var wok_id           = $(this).attr("name").replace('bill_flg_', '');
                                var this_id          = $(this).attr("id");
                                var bill_flg         = $("#"+this_id + " option:selected").val();
                                change_custom_info(      this_id            // 対象コントロール
                                                        , wok_id            // customerテーブルのID
                                                        , bill_flg          // 会計フラグ
                                                        , null              // 達人フラグ
                                                        , null              // 税理士確認フラグ
                                                        , null              // 申告フラグ
                                                        , null              // 消費税フラグ
                                                    );
                            });
                            //---------------------------------------------------------------
                            //--達人フラグプルダウンイベントハンドラ
                            //---------------------------------------------------------------
                            $('select[name^="adept_flg_"]').change( function(e){
                                // alert('達人フラグClick');
                                var wok_id           = $(this).attr("name").replace('adept_flg_', '');
                                var this_id          = $(this).attr("id");
                                var adept_flg        = $("#"+this_id + " option:selected").val();
                                change_custom_info(      this_id            // 対象コントロール
                                                        , wok_id            // customerテーブルのID
                                                        , null              // 会計フラグ
                                                        , adept_flg         // 達人フラグ
                                                        , null              // 税理士確認フラグ
                                                        , null              // 申告フラグ
                                                        , null              // 消費税フラグ
                                                    );
                            });
                            //---------------------------------------------------------------
                            //--税理士確認フラグプルダウンイベントハンドラ
                            //---------------------------------------------------------------
                            $('select[name^="confirmation_flg_"]').change( function(e){
                                // alert('税理士確認フラグClick');
                                var wok_id           = $(this).attr("name").replace('confirmation_flg_', '');
                                var this_id          = $(this).attr("id");
                                var confirmation_flg = $("#"+this_id + " option:selected").val();
                                change_custom_info(      this_id            // 対象コントロール
                                                        , wok_id            // customerテーブルのID
                                                        , null              // 会計フラグ
                                                        , null              // 達人フラグ
                                                        , confirmation_flg  // 税理士確認フラグ
                                                        , null              // 申告フラグ
                                                        , null              // 消費税フラグ
                                                    );
                            });
                            //---------------------------------------------------------------
                            //--申告フラグプルダウンイベントハンドラ
                            //---------------------------------------------------------------
                            $('select[name^="report_flg_"]').change( function(e){
                                // alert('申告フラグClick');
                                var wok_id           = $(this).attr("name").replace('report_flg_', '');
                                var this_id          = $(this).attr("id");
                                var report_flg       = $("#"+this_id + " option:selected").val();
                                change_custom_info(      this_id            // 対象コントロール
                                                        , wok_id            // customerテーブルのID
                                                        , null              // 会計フラグ
                                                        , null              // 達人フラグ
                                                        , null              // 税理士確認フラグ
                                                        , report_flg        // 申告フラグ
                                                        , null              // 消費税フラグ
                                                    );
                            });
                            //2022/05/20
                            //---------------------------------------------------------------
                            //--消費税フラグプルダウンイベントハンドラ
                            //---------------------------------------------------------------
                            $('select[name^="consumption_tax_"]').change( function(e){
                                // alert('消費税フラグClick');
                                var wok_id           = $(this).attr("name").replace('consumption_tax_', '');
                                var this_id          = $(this).attr("id");
                                var consumption_tax  = $("#"+this_id + " option:selected").val();
                                change_custom_info(      this_id            // 対象コントロール
                                                        , wok_id            // customerテーブルのID
                                                        , null              // 会計フラグ
                                                        , null              // 達人フラグ
                                                        , null              // 税理士確認フラグ
                                                        , null              // 申告フラグ
                                                        , consumption_tax   // 消費税フラグ
                                                    );
                            });

                            /**
                            * this_id               : 対象コントロール
                            * wok_id                : customerテーブルのID
                            * bill_flg              : 会計フラグ
                            * adept_flg             : 達人フラグ
                            * confirmation_flg      : 税理士確認フラグ
                            * report_flg            : 申告フラグ
                            * consumption_tax       : 消費税フラグ
                            */
                            function change_custom_info(     this_id
                                                            , wok_id
                                                            , bill_flg
                                                            , adept_flg
                                                            , confirmation_flg
                                                            , report_flg
                                                            , consumption_tax
                                                                    ){
                                    var reqData = new FormData();
                                                                        reqData.append( "id"                , wok_id            );
                                    if( null != bill_flg )              reqData.append( "bill_flg"          , bill_flg          );
                                    if( null != adept_flg   )           reqData.append( "adept_flg"         , adept_flg         );
                                    if( null != confirmation_flg  )     reqData.append( "confirmation_flg"  , confirmation_flg  );
                                    if( null != report_flg  )           reqData.append( "report_flg"        , report_flg        );
                                    //2022/05/20
                                    if( null != consumption_tax  )      reqData.append( "consumption_tax"   , consumption_tax   );
                                    // console.log(bill_flg);
                                    // console.log(adept_flg);
                                    // console.log(confirmation_flg);
                                    // console.log(report_flg);

                                        // Ajax通信呼出(データファイルのアップロード)
                                        AjaxAPI.callAjax(
                                            "{{ route('top.update_api') }}",
                                            reqData,
                                            function (res) {
                                                $('#'+this_id).effect("pulsate", { times:2 }, 500);

                                            }
                                        )
                                    };
                        </script>
                        <script>
                            function changeColor(consumption_tax_{{$customer->id}}){
                                if( consumption_tax_{{$customer->id}}.value == 2 ){
                                    consumption_tax_{{$customer->id}}.style.color = 'red';
                                }else{
                                    consumption_tax_{{$customer->id}}.style.color = '';
                                }
                            }
                        </script>
                    @endforeach
                    @else
                        <tr>
                            <td><p>今月の申告はありません。</p></td>
                            <td><p> </p></td>
                            <td><p> </p></td>
                            <td><p> </p></td>
                            <td><p> </p></td>
                            <td><p> </p></td>
                            <td><p> </p></td>
                            {{-- //2022/05/20 --}}
                            <td><p> </p></td>
                        </tr>
                    @endif
                </tbody>
                </form>
            </table>

            {{-- ページネーション / pagination）の表示 --}}
            <ul class="pagination justify-content-center">
                {{-- //2022/05/20 --}}
                {{ $customers2->appends(request()->query())->render() }}
            </ul>
            <hr class="mb-4">  {{-- // line --}}

            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-success">来月の申告 ( {{ $count3 }} 社)</span>
                {{-- <span class="badge badge-secondary badge-pill">3</span> --}}
            </h4>
            {{-- <table class="table table-striped table-borderd"> --}}
            {{-- <table class="table table-striped table-borderd table_sticky"> --}}
        {{-- table-responsive text-nowrap add scope=row 2022/11/09--}}
        <table class="table table-responsive text-nowrap table-striped table-borderd table_sticky">
                <thead>
                    <tr>
	                    <th scope="row" class ="fixed01">社名</th>
	                    <th scope="row" class ="fixed01">法人</th>
	                    <th scope="row" class ="fixed01">決算</th>
	                    <th scope="row" class ="fixed01">最終会計処理日</th>
                    </tr>
                </thead>

                <tbody>
                    @if($customers3->count())
                        @foreach($customers3 as $customer)
                        <tr>
                            {{-- <td>{{ $customer->business_name }}</td> --}}
                            <td>
                                <a href="{{ route('customer.edit',$customer->id)}}">{{ $customer->business_name }}</a>
                            </td>
                            <td>
                                {{-- //法人・個人 App/Providers/AppServiceProviderのboot--}}
                            @foreach ($loop_individual_class as $loop_individual_class2)
                                @if ($loop_individual_class2['no']==$customer->individual_class)
                                    {{ $loop_individual_class2['name'] }}
                                @endif
                            @endforeach
                        </td>
                        <td>
                            @foreach ($loop_closing_month as $loop_closing_month2)
                                @if ($loop_closing_month2['no']==$customer->closing_month)
                                    {{$loop_closing_month2['name']}}
                                @endif
                            @endforeach
                        </td>
                            @php
                            $str = "-";
                                if (isset($customer->final_accounting_at)) {
                                    $str = ( new DateTime($customer->final_accounting_at))->format('Y-m-d');
                                }
                            @endphp
                            <td>{{ $str }}</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td><p>来月の申告はありません。</p></td>
                            <td><p> </p></td>
                            <td><p> </p></td>
                            <td><p> </p></td>
                        </tr>
                    @endif
                </tbody>
            </table>
            {{-- ページネーション / pagination）の表示 --}}
            <ul class="pagination justify-content-center">
                {{-- //2022/05/20 --}}
                {{ $customers3->appends(request()->query())->render() }}
            </ul>

            {{-- <hr class="mb-4">  // line --}}

            {{-- //2022/05/20 --}}
            {{-- >今月の申請・設立は不要 --}}
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                {{-- <span class="text-secondary">今月の申請・設立</span> --}}
            </h4>
            <div class="text-right">
                {{-- <a class="btn btn-success btn-sm mr-auto" href="">新規登録</a> --}}
            </div>
            <table class="table table-striped table-borderd">
                <thead>
                    {{-- <tr>
                        <th scope="col">年</th>
	                    <th scope="col">社名</th>
	                    <th scope="col">申請・設立内容</th>
	                    <th scope="col">納期</th>
	                    <th scope="col">申請・郵送</th>
                    </tr> --}}
                </thead>
                <tbody>
                    {{-- 今月の申請・設立 --}}
                    {{-- @foreach($applestabls as $applestabls2)
                    <tr>
                        <td>{{$applestabls2->year}}</td>
                        <td>{{$applestabls2->companyname}}</td>
                        <td>{{$applestabls2->estadetails}}</td>
                            @php
                                $str = "-";
                                if (isset($applestabls2->delivery_at)) {
                                    $str = ( new DateTime($applestabls2->delivery_at))->format('Y-m-d');
                                }
                            @endphp
                        <td>{{ $str }}</td>
                        <td>
                            @foreach ($loop_mail_flg as $loop_mail_flg2)
                                @if ($loop_mail_flg2['no']==$applestabls2->mail_flg)
                                    {{ $loop_mail_flg2['name'] }}
                                @endif
                            @endforeach
                        </td>
                    </tr>
                    @endforeach --}}
                </tbody>
            </table>

            {{-- ページネーション / pagination）の表示 --}}
            <ul class="pagination justify-content-center">
                {{-- //2022/05/20 --}}
                {{-- {{ $applestabls->appends(request()->query())->render() }} --}}
            </ul>
            <hr class="mb-4">  {{-- // line --}}

        </div>

    </div>

@endsection

@section('part_javascript')
{{-- ChangeSideBar("nav-item-system-user"); --}}
    <script type="text/javascript">
        $('.btn_del').click(function() {
            if( !confirm('本当に削除しますか？') ){
                /* キャンセルの時の処理 */
                return false;
            }
            else{
                /*　OKの時の処理 */
                return true;
            }
        });
    </script>
@endsection
