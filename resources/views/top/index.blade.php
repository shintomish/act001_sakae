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
            width: 2600px;
        }
        th,td{
            width: 360px;   /* 200->280->360 */
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
        /* 申告未完了：社名を赤字＋ゆっくり点滅 */
        .blink-danger {
            color: red !important;
            font-weight: bold;
            animation: blinkFade 2s infinite;
        }

        @keyframes blinkFade {
            0%   { opacity: 1; }
            50%  { opacity: 0.3; }
            100% { opacity: 1; }
        }

    </style>

    <div class="row">
        <div class="col-md-10 order-md-2 mb-4">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-success">今月の申告 ( {{ $count2 }} 社)</span>
                <button type="button" id="btn-reset-thismonth" class="btn btn-warning btn-sm">一括リセット（〇→ー）</button>
            </h4>
        {{-- table-responsive text-nowrap add scope=row 2022/11/09--}}
        <table id="table-thismonth" class="table table-responsive text-nowrap table-striped table-borderd table_sticky">
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
                        <th scope="row" class ="fixed02" >消費税申告期間</th>
                    </tr>
                </thead>

                <tbody>

                    @if($customers2->count())
                        @foreach($customers2 as $customer)
                        <tr>
                            {{-- <td>{{ $customer->business_name }}</td> --}}
                            <td>
                                {{-- 点滅追加 --}}
                                <a href="{{ route('customer.edit',$customer->id)}}"
                                class="{{ $customer->report_flg == 1 ? 'blink-danger' : '' }}">
                                    {{ $customer->business_name }}
                                </a>
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
        {{-- 2025/11/15 --}}
        {{-- 消費税申告期間 --}}
        {{-- // `loop_consumption_tax_filing_period` int(11) DEFAULT 3 COMMENT '消費税申告期間 1:１年 2:３か月ごと 3:毎月', --}}
                            <td>
                                <select class="custom-select d-block w-100" id="tax_filing_period_{{$customer->id}}" name="tax_filing_period_{{$customer->id}}">

                                    @foreach ($loop_consumption_tax_filing_period as $tax_filing_period2)
                                        @if ($tax_filing_period2['no']==$customer->consumption_tax_filing_period)
    <option selected="selected" value={{$tax_filing_period2['no']}}>{{ $tax_filing_period2['name'] }}</option>
                                        @else
                                            @if ($tax_filing_period2['no']==0)
    <option  disabled value={{$tax_filing_period2['no']}}>{{ $tax_filing_period2['name'] }}</option>
                                            @else
    <option value={{$tax_filing_period2['no']}}>{{ $tax_filing_period2['name'] }}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                        </tr>
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
                            {{-- //2025/11/15 --}}
                            <td><p> </p></td>
                        </tr>
                    @endif
{{-- {{-- //2025/11/16 -- 移動}} --}}
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
                                                    , null              // 消費税申告期間フラグ
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
                                                    , null              // 消費税申告期間フラグ
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
                                                    , null              // 消費税申告期間フラグ
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
                                                    , null              // 消費税申告期間フラグ
                                                );
                            // ★ 社名の点滅切替
                            // ▼ 社名リンク取得
                            var nameLink = $(this).closest('tr').find('td:first a');
                            
                            // ▼ report_flg == 1 のとき赤色点滅
                            if (report_flg == 1) {
                                nameLink.addClass('blink-danger');
                            } else {
                                nameLink.removeClass('blink-danger');
                            }
                        });
                        //2022/05/20
                        //---------------------------------------------------------------
                        //--消費税フラグプルダウンイベントハンドラ　filing_period
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
                                                    , null              // 消費税申告期間フラグ
                                                );
                            changeColor(this);
                            change_custom_Color(      this_id            // 対象コントロール
                                                    , wok_id            // customerテーブルのID
                                                    , consumption_tax   // 消費税フラグ
                                                );
                        });
                        //2025/11/15
                        //---------------------------------------------------------------
                        //--消費税申告期間フラグプルダウンイベントハンドラ
                        //---------------------------------------------------------------
                        $('select[name^="tax_filing_period_"]').change( function(e){
                            //　alert('消費税申告期間Click');
                            var wok_id              = $(this).attr("name").replace('tax_filing_period_', '');
                            var this_id             = $(this).attr("id");
                            var tax_filing_period   = $("#"+this_id + " option:selected").val();
                            change_custom_info(      this_id            // 対象コントロール
                                                    , wok_id            // customerテーブルのID
                                                    , null              // 会計フラグ
                                                    , null              // 達人フラグ
                                                    , null              // 税理士確認フラグ
                                                    , null              // 申告フラグ
                                                    , null              // 消費税フラグ
                                                    , tax_filing_period   // 消費税申告期間フラグ
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
                        * tax_filing_period     : 消費税申告期間フラグ
                        */
                        function change_custom_info(     this_id
                                                        , wok_id
                                                        , bill_flg
                                                        , adept_flg
                                                        , confirmation_flg
                                                        , report_flg
                                                        , consumption_tax
                                                        , tax_filing_period
                                                                ){
                                var reqData = new FormData();
                                                                    reqData.append( "id"                , wok_id            );
                                if( null != bill_flg )              reqData.append( "bill_flg"          , bill_flg          );
                                if( null != adept_flg   )           reqData.append( "adept_flg"         , adept_flg         );
                                if( null != confirmation_flg  )     reqData.append( "confirmation_flg"  , confirmation_flg  );
                                if( null != report_flg  )           reqData.append( "report_flg"        , report_flg        );
                                //2022/05/20
                                if( null != consumption_tax  )      reqData.append( "consumption_tax"   , consumption_tax   );
                                //2025/11/15
                                if( null != tax_filing_period  )    reqData.append( "tax_filing_period" , tax_filing_period );
                                // console.log(bill_flg);
                                // console.log(adept_flg);
                                // console.log(confirmation_flg);
                                // console.log(report_flg);
                                console.log(wok_id);

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
                        function changeColor(select) {
                            // まず全 option の色をリセット
                            for (let i = 0; i < select.options.length; i++) {
                                select.options[i].style.color = '';
                            }

                            // 選択中の値だけ判定
                            if (select.value == "2") {
                                select.options[select.selectedIndex].style.color = 'red';
                            }
                        };
                        function change_custom_Color(this_id, wok_id, consumption_tax) {
                            var el = document.getElementById(this_id);

                            // 色リセット
                            el.style.color = '';
                            el.style.backgroundColor = '';

                            if (consumption_tax == 2) {
                                // select 自体の背景色を変える（optionには影響しない）
                                el.style.backgroundColor = '#ffe5e5'; // 薄い赤（お好みで変更可）
                            }
                        };
                    </script>
{{-- {{-- //2025/11/16 -- 移動}} --}}
                    <script type="text/javascript">
                        //---------------------------------------------------------------
                        //-- 一括リセット（〇→ー）ボタン
                        //---------------------------------------------------------------
                        $('#btn-reset-thismonth').click(function () {
                            if (!confirm('今月の申告の〇をすべてーにリセットしますか？')) return;

                            // 顧客IDを収集（bill_flg_ セレクトは今月テーブルのみに存在）
                            var ids = [];
                            $('select[name^="bill_flg_"]').each(function () {
                                ids.push($(this).attr('name').replace('bill_flg_', ''));
                            });

                            if (ids.length === 0) return;

                            var reqData = new FormData();
                            $.each(ids, function (i, id) { reqData.append('ids[]', id); });

                            AjaxAPI.callAjax(
                                "{{ route('top.reset_api') }}",
                                reqData,
                                function (res) {
                                    // UI を ー(1) に更新
                                    $.each(ids, function (i, id) {
                                        $('#bill_flg_'         + id).val(1);
                                        $('#adept_flg_'        + id).val(1);
                                        $('#confirmation_flg_' + id).val(1);
                                        $('#report_flg_'       + id).val(1);
                                    });
                                    // 社名の点滅をすべて解除
                                    $('a.blink-danger').removeClass('blink-danger');
                                }
                            );
                        });
                    </script>

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
        {{-- table-responsive text-nowrap add scope=row 2022/11/09--}}
        <table class="table table-responsive text-nowrap table-striped table-borderd table_sticky">
                <thead>
                    <tr>
	                    <th scope="row" class ="fixed01">社名</th>
	                    <th scope="row" class ="fixed01">法人</th>
	                    <th scope="row" class ="fixed01">決算</th>
                        <th scope="row" class="fixed01">消費税申告の期間</th> {{-- ★追加 --}}
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
                            {{-- 2026/01/21 追加 --}}
                            {{-- ★ 消費税申告の期間 --}}
                            <td>
                                @foreach ($loop_consumption_tax_filing_period as $tax_filing_period2)
                                    @if ($tax_filing_period2['no'] == $customer->consumption_tax_filing_period)
                                        {{ $tax_filing_period2['name'] }}
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
