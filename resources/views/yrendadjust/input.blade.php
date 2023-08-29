{{-- @extends('layouts.app') --}}
@extends('layouts.api_index')

@section('content')
    {{-- <h2>年末調整一覧</h2> --}}
    <div class="text-right">
        <a class="btn btn-success btn-sm mr-auto" href="{{route('yrendadjust.create')}}">新規登録</a>
    </div>

    <div class="row">
        <!-- 検索エリア -->
        <!-- 検索エリア -->
    </div>

    {{-- Line --}}
    <hr class="mb-4">
    <style>
        /* スクロールバーの実装 */
        .table_sticky {
            display: block;
            overflow-y: scroll;
            /* height: calc(100vh/2); */
            height: 600px;
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
                /* height: 100%; 2023/06/12 sortablelink対応 */
                height: 10%;
                border: 1px solid #ccc;
            }
        }

        table{
            width: 1800px;
        }
        th,td{
            width: 200px;
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
            z-index: 2;
        }
        .fixed03{
            position: sticky;
            top: 0;
            left: 0px;
            color: rgb(0, 55, 255);
            background: rgb(249, 247, 247);
            z-index: 2;
        }
    </style>

    {{-- <div class="table-responsive"> --}}
    {{-- <div class="alt-table-responsive"> 狭くすると崩れる--}}

        {{-- <table class="table table-striped table-borderd table-scroll"> --}}
        {{-- table-responsive text-nowrap add scope=row 2022/11/09--}}
        <table class="table table-responsive text-nowrap table-striped table-borderd table_sticky">
            <thead>
                <tr>
                    <th scope="row" class ="fixed01 bg-info">ID</th>
                    {{-- <th scope="col" class ="fixed01 bg-info text-right">@sortablelink('business_code', '事業者コード')</th> --}}
                    {{-- <th scope="row" class ="fixed03 bg-info p-4">@sortablelink('business_code', '顧客名')</th> --}}
                    <th scope="row" class ="fixed02 bg-info">@sortablelink('business_code', '顧客名')</th>
                    {{-- <th scope="col" class ="col-xs-3 col-md-2  bg-info text-right">代表者</th> --}}
                    <th scope="row" class ="fixed02   bg-info text-right">年調有無</th>
                    <th scope="row" class ="fixed02   bg-info text-right">伝達手段</th>
                    {{-- <th scope="col" class ="  bg-info text-right">受託人数</th> --}}
                    {{-- 'announce_at',	// アナウンス --}}
                    {{-- 'docinfor_at',	// 書類案内日 --}}
                    {{-- 'doccolle_at',	// 資料回収日 --}}
                    {{-- 'rrequest_at',	// 資料再請求日 --}}
                    {{-- 'matecret_at',	// 資料作成日 --}}

                    <th scope="row" class ="fixed02   bg-info text-right">@sortablelink('announce_at', '通知日')</th>
                    <th scope="row" class ="fixed02   bg-info text-right">@sortablelink('docinfor_at', '案内日')</th>
                    <th scope="row" class ="fixed02   bg-info text-right">@sortablelink('doccolle_at', '回収日')</th>
                    <th scope="row" class ="fixed02   bg-info text-right">@sortablelink('rrequest_at', '再請求')</th>
                    <th scope="row" class ="fixed02   bg-warning text-right">@sortablelink('matecret_at', '作成日')</th>
                    <th scope="row" class ="fixed02   bg-warning text-right">給与</th>
                    {{-- <th scope="col" class ="  bg-info text-right">備考01</th> --}}
                    {{-- <th scope="col" class ="  bg-info text-right">備考02</th> --}}
                    {{-- <th scope="col" class ="  bg-success text-right">納期特例</th> --}}
                    <th scope="row" class ="fixed02   bg-success text-right">申請還付</th>
                    <th scope="row" class ="fixed02   bg-success text-right">納付申告</th>
                    <th scope="row" class ="fixed02   bg-success text-right">年調申告</th>
                    <th scope="row" class ="fixed02   bg-success text-right">源泉徴収</th>
                    <th scope="row" class ="fixed02   bg-success text-right">請求確認</th>
                    <th scope="row" class ="fixed02   bg-success text-right">入金確認</th>
                    <th scope="row" class ="fixed02   bg-secondary text-right">操作</th>
                </tr>
            </thead>

            <tbody>
                @if($yrendadjusts->count())
                    @foreach($yrendadjusts as $yrendadjust)
                    <tr>
                        {{-- ID --}}
                        <th class="fixed03">{{ $yrendadjust->id }}</th>

                        {{-- 事業者コード --}}
                        {{-- @foreach ($customers as $customers2)
                            @if ($customers2->id==$yrendadjust->custm_id)
                                <th class="fixed02">{{ $customers2->business_code }}</th>
                            @endif
                        @endforeach --}}

                        {{-- 社名/氏名 --}}
                        @foreach ($customers as $customers2)
                            @if ($customers2->id==$yrendadjust->custm_id)
                                {{-- <td>{{ $customers2->business_name }}</td> --}}
                                <th class="fixed03">{{ $customers2->business_name }}</th>
                            @endif
                        @endforeach

                        {{-- 代表者 --}}
                        {{-- @foreach ($customers as $customers2)
                            @if ($customers2->id==$yrendadjust->custm_id)
                                <td>{{ $customers2->represent_name }}</td>
                            @endif
                        @endforeach --}}

                        {{-- /'年調の有無 1:無 2:有'--}}
                        {{-- @foreach ($loop_absence_flg as $loop_absence_flg2)
                            @if ($loop_absence_flg2['no']==$yrendadjust->absence_flg)
                                <td>{{ $loop_absence_flg2['name'] }}</td>
                            @endif
                        @endforeach --}}
                        <td>
                        <select class="custom-select" id="absence_flg_{{$yrendadjust->id}}" name="absence_flg_{{$yrendadjust->id}}">
                            <option value="1" {{ $yrendadjust->absence_flg == 1 ? 'selected' : '' }}>無</option>
                            <option value="2" {{ $yrendadjust->absence_flg == 2 ? 'selected' : '' }}>有</option>
                        </select>
                        </td>

                        {{-- 受託人数 --}}
                        {{-- <td>{{ $yrendadjust->trustees_no }}</td> --}}
                        {{-- @php
                            $str = sprintf("%s", $yrendadjust->trustees_no);
                        @endphp
                        <td>
<input type="text" class="form-control"  id="trustees_no_{{$yrendadjust->id}}" name="trustees_no_{{$yrendadjust->id}}" value="{{$str}}">
                        </td> --}}

                        {{-- 伝達手段 1:CHAT 2:LINE 3:MAIL 4:TELL' --}}
                        {{-- @foreach ($loop_communica_flg as $loop_communica_flg2)
                            @if ($loop_communica_flg2['no']==$yrendadjust->communica_flg)
                                <td>{{ $loop_communica_flg2['name'] }}</td>
                            @endif
                        @endforeach --}}
                        <td>
                        <select class="custom-select" id="communica_flg_{{$yrendadjust->id}}" name="communica_flg_{{$yrendadjust->id}}">
                        <option value="1" {{ $yrendadjust->communica_flg == 1 ? 'selected' : '' }}>CHAT</option>
                        <option value="2" {{ $yrendadjust->communica_flg == 2 ? 'selected' : '' }}>LINE</option>
                        <option value="3" {{ $yrendadjust->communica_flg == 3 ? 'selected' : '' }}>MAIL</option>
                        <option value="4" {{ $yrendadjust->communica_flg == 4 ? 'selected' : '' }}>TELL</option>
                        </select>
                        </td>

                        {{-- /アナウンス日 --}}
                        @php
                            $str = "";
                            if (isset($yrendadjust->announce_at)) {
                                $str = ( new DateTime($yrendadjust->announce_at))->format('Y-m-d');
                            }
                        @endphp
                        {{-- <td>{{ $str }}</td> --}}
                        <td><input type="date" class="form-control"  id="announce_at_{{$yrendadjust->id}}" name="announce_at_{{$yrendadjust->id}}" value="{{$str}}"></td>

                        {{-- /書類の案内日 --}}
                        @php
                            $str = "";
                            if (isset($yrendadjust->docinfor_at)) {
                                $str = ( new DateTime($yrendadjust->docinfor_at))->format('Y-m-d');
                            }
                        @endphp
                        {{-- <td>{{ $str }}</td> --}}
                        <td><input type="date" class="form-control"  id="docinfor_at_{{$yrendadjust->id}}" name="docinfor_at_{{$yrendadjust->id}}" value="{{$str}}"></td>

                        {{-- /資料回収日 --}}
                        @php
                            $str = "";
                            if (isset($yrendadjust->doccolle_at)) {
                                $str = ( new DateTime($yrendadjust->doccolle_at))->format('Y-m-d');
                            }
                        @endphp
                        {{-- <td>{{ $str }}</td> --}}
                        <td><input type="date" class="form-control"  id="doccolle_at_{{$yrendadjust->id}}" name="doccolle_at_{{$yrendadjust->id}}" value="{{$str}}"></td>

                        {{-- /資料再請求日 --}}
                        @php
                            $str = "";
                            if (isset($yrendadjust->rrequest_at)) {
                                $str = ( new DateTime($yrendadjust->rrequest_at))->format('Y-m-d');
                            }
                        @endphp
                        {{-- <td>{{ $str }}</td> --}}
                        <td><input type="date" class="form-control"  id="rrequest_at_{{$yrendadjust->id}}" name="rrequest_at_{{$yrendadjust->id}}" value="{{$str}}"></td>

                        {{-- /資料作成日 --}}
                        @php
                            $str = "";
                            if (isset($yrendadjust->matecret_at)) {
                                $str = ( new DateTime($yrendadjust->matecret_at))->format('Y-m-d');
                            }
                        @endphp
                        {{-- <td>{{ $str }}</td> --}}
                        <td><input type="date" class="form-control"  id="matecret_at_{{$yrendadjust->id}}" name="matecret_at_{{$yrendadjust->id}}" value="{{$str}}"></td>

                        {{-- 給与情報 1:未 2:済' --}}
                        {{-- @foreach ($loop_salary_flg as $loop_salary_flg2) --}}
                        {{-- @if ($loop_salary_flg2['no']==$yrendadjust->salary_flg) --}}
                            {{-- @if ($yrendadjust->salary_flg==2) --}}
                                {{-- <div class="opacit-40">rgba(255, 255, 0, 0.4)黄色 --}}
                                {{-- <td class ="text-right" style="background-color: rgba(255, 255, 0, 0.4); ">{{ $str }}</td> --}}
                                {{-- <td class ="text-right" style="background-color: rgba(255, 255, 0, 0.4); ">{{ $loop_salary_flg2['name'] }}</td> --}}
                            {{-- @else --}}
                                {{-- <td>{{ $str }}</td> --}}
                                {{-- <td >{{ $loop_salary_flg2['name'] }}</td> --}}
                            {{-- @endif --}}
                        {{-- @endif --}}
                        {{-- @endforeach --}}

                        <td>
                            <select class="custom-select" id="salary_flg_{{$yrendadjust->id}}" name="salary_flg_{{$yrendadjust->id}}">
                                <option value="1" {{ $yrendadjust->salary_flg == 1 ? 'selected' : '' }}>未</option>
                                <option value="2" {{ $yrendadjust->salary_flg == 2 ? 'selected' : '' }}>済</option>
                            </select>
                        </td>

                        {{-- 備考1 --}}
                        {{-- <td>{{ $yrendadjust->remark_1 }}</td> --}}
                        {{-- @php
                            $str = sprintf("%s", $yrendadjust->remark_1);
                        @endphp
                        <td>
<input type="text" class="form-control"  id="remark_1_{{$yrendadjust->id}}" name="remark_1_{{$yrendadjust->id}}" value="{{$str}}">
                        </td> --}}

                        {{-- 備考2 --}}
                        {{-- <td>{{ $yrendadjust->remark_2 }}</td> --}}
                        {{-- @php
                            $str = sprintf("%s", $yrendadjust->remark_2);
                        @endphp
                        <td>
<input type="text" class="form-control"  id="remark_2_{{$yrendadjust->id}}" name="remark_2_{{$yrendadjust->id}}" value="{{$str}}">
                        </td> --}}

                        {{-- 納特納付書の連携 --}}
                        {{-- <td>{{ $yrendadjust->cooperat }}</td> --}}
                        {{-- @php
                            $str = sprintf("%s", $yrendadjust->cooperat);
                        @endphp
                        <td>
<input type="text" class="form-control"  id="cooperat_{{$yrendadjust->id}}" name="cooperat_{{$yrendadjust->id}}" value="{{$str}}">
                        </td> --}}

                        {{-- 申請すれば還付あり 1:― 2:○' --}}
                        {{-- @foreach ($loop_refund_flg as $loop_refund_flg2)
                            @if ($loop_refund_flg2['no']==$yrendadjust->refund_flg)
                                <td>{{ $loop_refund_flg2['name'] }}</td>
                            @endif
                        @endforeach --}}
                        <td>
                            <select class="custom-select" id="refund_flg_{{$yrendadjust->id}}" name="refund_flg_{{$yrendadjust->id}}">
                                <option value="1" {{ $yrendadjust->refund_flg == 1 ? 'selected' : '' }}>―</option>
                                <option value="2" {{ $yrendadjust->refund_flg == 2 ? 'selected' : '' }}>○</option>
                            </select>
                        </td>

                        {{-- 0円納付申告 1:― 2:○', --}}
                        {{-- @foreach ($loop_declaration_flg as $loop_declaration_flg2)
                            @if ($loop_declaration_flg2['no']==$yrendadjust->declaration_flg)
                                <td>{{ $loop_declaration_flg2['name'] }}</td>
                            @endif
                        @endforeach --}}
                        <td>
                            <select class="custom-select" id="declaration_flg_{{$yrendadjust->id}}" name="declaration_flg_{{$yrendadjust->id}}">
                                <option value="1" {{ $yrendadjust->declaration_flg == 1 ? 'selected' : '' }}>―</option>
                                <option value="2" {{ $yrendadjust->declaration_flg == 2 ? 'selected' : '' }}>○</option>
                            </select>
                        </td>

                        {{-- 年調申告 1:― 2:○', --}}
                        {{-- @foreach ($loop_annual_flg as $loop_annual_flg2)
                            @if ($loop_annual_flg2['no']==$yrendadjust->annual_flg)
                                <td>{{ $loop_annual_flg2['name'] }}</td>
                            @endif
                        @endforeach --}}
                        <td>
                            <select class="custom-select" id="annual_flg_{{$yrendadjust->id}}" name="annual_flg_{{$yrendadjust->id}}">
                                <option value="1" {{ $yrendadjust->annual_flg == 1 ? 'selected' : '' }}>―</option>
                                <option value="2" {{ $yrendadjust->annual_flg == 2 ? 'selected' : '' }}>○</option>
                            </select>
                        </td>

                        {{-- 源泉徴収票 1:― 2:○', --}}
                        {{-- @foreach ($loop_withhold_flg as $loop_loop_withhold_flg2)
                            @if ($loop_loop_withhold_flg2['no']==$yrendadjust->withhold_flg)
                                <td>{{ $loop_loop_withhold_flg2['name'] }}</td>
                            @endif
                        @endforeach --}}
                        <td>
                            <select class="custom-select" id="withhold_flg_{{$yrendadjust->id}}" name="withhold_flg_{{$yrendadjust->id}}">
                                <option value="1" {{ $yrendadjust->withhold_flg == 1 ? 'selected' : '' }}>―</option>
                                <option value="2" {{ $yrendadjust->withhold_flg == 2 ? 'selected' : '' }}>○</option>
                            </select>
                        </td>

                        {{-- 請求フラグ 1:― 2:○', --}}
                        {{-- @foreach ($loop_claim_flg as $loop_claim_flg2)
                            @if ($loop_claim_flg2['no']==$yrendadjust->claim_flg)
                                <td>{{ $loop_claim_flg2['name'] }}</td>
                            @endif
                        @endforeach --}}
                        <td>
                            <select class="custom-select" id="claim_flg_{{$yrendadjust->id}}" name="claim_flg_{{$yrendadjust->id}}">
                                <option value="1" {{ $yrendadjust->claim_flg == 1 ? 'selected' : '' }}>―</option>
                                <option value="2" {{ $yrendadjust->claim_flg == 2 ? 'selected' : '' }}>○</option>
                            </select>
                        </td>

                        {{-- 入金確認フラグ 1:― 2:○', --}}
                        {{-- @foreach ($loop_payment_flg as $loop_payment_flg2)
                            @if ($loop_payment_flg2['no']==$yrendadjust->payment_flg)
                                <td>{{ $loop_payment_flg2['name'] }}</td>
                            @endif
                        @endforeach --}}
                        <td>
                            <select class="custom-select" id="payment_flg_{{$yrendadjust->id}}" name="payment_flg_{{$yrendadjust->id}}">
                                <option value="1" {{ $yrendadjust->payment_flg == 1 ? 'selected' : '' }}>―</option>
                                <option value="2" {{ $yrendadjust->payment_flg == 2 ? 'selected' : '' }}>○</option>
                            </select>
                        </td>

                       <td>
                            <div class="btn-toolbar">
                                <div class="btn-group me-2 mb-0">
                                <a class="btn btn-primary btn-sm" href="{{ route('yrendadjust.edit',$yrendadjust->id)}}">編集</a>
                                </div>
                                <div class="btn-group me-2 mb-0">
                                    <form action="{{ route('yrendadjust.destroy', $yrendadjust->id)}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <input class="btn btn-danger btn-sm" type="submit" value="削除" id="btn_del"
                                            onclick='return confirm("削除しますか？");'>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td><p>0件です。</p></td>
                        {{-- <td><p> </p></td>
                        <td><p> </p></td> --}}
                        {{-- <td><p> </p></td>
                        <td><p> </p></td> --}}
                        {{-- <td><p> </p></td>
                        <td><p> </p></td> --}}
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>

                    </tr>
                @endif

                <script type="text/javascript">
                    //---------------------------------------------------------------
                    //--年調の有無プルダウンイベントハンドラ
                    //---------------------------------------------------------------
                    $('select[name^="absence_flg_"]').change( function(e){
                        // alert('年調の有無Click');
                        var wok_id           = $(this).attr("name").replace('absence_flg_', '');
                        var this_id          = $(this).attr("id");
                        var absence_flg      = $("#"+this_id + " option:selected").val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // yrendadjustテーブルのID
                                                , absence_flg   // 年調の有無 1:無 2:有
                                                , null          // 受託人数
                                                , null          // 伝達手段
                                                , null          // アナウンス日
                                                , null          // 書類の案内日
                                                , null          // 資料回収日
                                                , null          // 資料再請求日
                                                , null          // 資料作成日
                                                , null          // 給与情報 1:未 2:済
                                                , null          // 備考1
                                                , null          // 備考2
                                                , null          // 納特納付書の連携
                                                , null          // 申請すれば還付あり 1:× 2:○
                                                , null          // 0円納付申告 1:× 2:○
                                                , null          // 年調申告 1:× 2:○
                                                , null          // 源泉徴収票 1:× 2:○
                                                , null          // 請求フラグ 1:× 2:○
                                                , null          // 入金確認フラグ 1:× 2:○
                                            );
                    });

                    //---------------------------------------------------------------
                    //--受託人数テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="trustees_no_"]').change( function(e){
                        // alert('受託人数Click');
                        var wok_id           = $(this).attr("name").replace('trustees_no_', '');
                        var trustees_no      = $(this).val();
                        var this_id          = $(this).attr("id");
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // yrendadjustテーブルのID
                                                , null          // 年調の有無 1:無 2:有
                                                , trustees_no   // 受託人数
                                                , null          // 伝達手段
                                                , null          // アナウンス日
                                                , null          // 書類の案内日
                                                , null          // 資料回収日
                                                , null          // 資料再請求日
                                                , null          // 資料作成日
                                                , null          // 給与情報 1:未 2:済
                                                , null          // 備考1
                                                , null          // 備考2
                                                , null          // 納特納付書の連携
                                                , null          // 申請すれば還付あり 1:× 2:○
                                                , null          // 0円納付申告 1:× 2:○
                                                , null          // 年調申告 1:× 2:○
                                                , null          // 源泉徴収票 1:× 2:○
                                                , null          // 請求フラグ 1:× 2:○
                                                , null          // 入金確認フラグ 1:× 2:○
                                            );
                    });

                    //---------------------------------------------------------------
                    //--伝達手段プルダウンイベントハンドラ
                    //---------------------------------------------------------------
                    $('select[name^="communica_flg_"]').change( function(e){
                        // alert('伝達手段Click');
                        var wok_id           = $(this).attr("name").replace('communica_flg_', '');
                        var this_id          = $(this).attr("id");
                        var communica_flg   = $("#"+this_id + " option:selected").val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // yrendadjustテーブルのID
                                                , null          // 年調の有無 1:無 2:有
                                                , null          // 受託人数
                                                , communica_flg          // 伝達手段
                                                , null          // アナウンス日
                                                , null          // 書類の案内日
                                                , null          // 資料回収日
                                                , null          // 資料再請求日
                                                , null          // 資料作成日
                                                , null          // 給与情報 1:未 2:済
                                                , null          // 備考1
                                                , null          // 備考2
                                                , null          // 納特納付書の連携
                                                , null          // 申請すれば還付あり 1:× 2:○
                                                , null          // 0円納付申告 1:× 2:○
                                                , null          // 年調申告 1:× 2:○
                                                , null          // 源泉徴収票 1:× 2:○
                                                , null          // 請求フラグ 1:× 2:○
                                                , null          // 入金確認フラグ 1:× 2:○
                                            );
                    });

                    //---------------------------------------------------------------
                    //--アナウンス日テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="announce_at_"]').change( function(e){
                        // alert('アナウンス日Click');
                        var wok_id           = $(this).attr("name").replace('announce_at_', '');
                        var this_id          = $(this).attr("id");
                        var announce_at      = $(this).val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // yrendadjustテーブルのID
                                                , null          // 年調の有無 1:無 2:有
                                                , null          // 受託人数
                                                , null          // 伝達手段
                                                , announce_at          // アナウンス日
                                                , null          // 書類の案内日
                                                , null          // 資料回収日
                                                , null          // 資料再請求日
                                                , null          // 資料作成日
                                                , null          // 給与情報 1:未 2:済
                                                , null          // 備考1
                                                , null          // 備考2
                                                , null          // 納特納付書の連携
                                                , null          // 申請すれば還付あり 1:× 2:○
                                                , null          // 0円納付申告 1:× 2:○
                                                , null          // 年調申告 1:× 2:○
                                                , null          // 源泉徴収票 1:× 2:○
                                                , null          // 請求フラグ 1:× 2:○
                                                , null          // 入金確認フラグ 1:× 2:○
                                            );
                    });

                    //---------------------------------------------------------------
                    //--書類の案内日テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="docinfor_at_"]').change( function(e){
                        // alert('書類の案内日Click');
                        var wok_id           = $(this).attr("name").replace('docinfor_at_', '');
                        var this_id          = $(this).attr("id");
                        var docinfor_at      = $(this).val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // yrendadjustテーブルのID
                                                , null          // 年調の有無 1:無 2:有
                                                , null          // 受託人数
                                                , null          // 伝達手段
                                                , null          // アナウンス日
                                                , docinfor_at          // 書類の案内日
                                                , null          // 資料回収日
                                                , null          // 資料再請求日
                                                , null          // 資料作成日
                                                , null          // 給与情報 1:未 2:済
                                                , null          // 備考1
                                                , null          // 備考2
                                                , null          // 納特納付書の連携
                                                , null          // 申請すれば還付あり 1:× 2:○
                                                , null          // 0円納付申告 1:× 2:○
                                                , null          // 年調申告 1:× 2:○
                                                , null          // 源泉徴収票 1:× 2:○
                                                , null          // 請求フラグ 1:× 2:○
                                                , null          // 入金確認フラグ 1:× 2:○
                                            );
                    });

                    //---------------------------------------------------------------
                    //--資料回収日テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="doccolle_at_"]').change( function(e){
                        // alert('資料回収日Click');
                        var wok_id           = $(this).attr("name").replace('doccolle_at_', '');
                        var this_id          = $(this).attr("id");
                        var doccolle_at      = $(this).val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // yrendadjustテーブルのID
                                                , null          // 年調の有無 1:無 2:有
                                                , null          // 受託人数
                                                , null          // 伝達手段
                                                , null          // アナウンス日
                                                , null          // 書類の案内日
                                                , doccolle_at          // 資料回収日
                                                , null          // 資料再請求日
                                                , null          // 資料作成日
                                                , null          // 給与情報 1:未 2:済
                                                , null          // 備考1
                                                , null          // 備考2
                                                , null          // 納特納付書の連携
                                                , null          // 申請すれば還付あり 1:× 2:○
                                                , null          // 0円納付申告 1:× 2:○
                                                , null          // 年調申告 1:× 2:○
                                                , null          // 源泉徴収票 1:× 2:○
                                                , null          // 請求フラグ 1:× 2:○
                                                , null          // 入金確認フラグ 1:× 2:○
                                            );
                    });

                    //---------------------------------------------------------------
                    //--資料再請求日テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="rrequest_at_"]').change( function(e){
                        // alert('資料再請求日Click');
                        var wok_id           = $(this).attr("name").replace('rrequest_at_', '');
                        var this_id          = $(this).attr("id");
                        var rrequest_at      = $(this).val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // yrendadjustテーブルのID
                                                , null          // 年調の有無 1:無 2:有
                                                , null          // 受託人数
                                                , null          // 伝達手段
                                                , null          // アナウンス日
                                                , null          // 書類の案内日
                                                , null          // 資料回収日
                                                , rrequest_at          // 資料再請求日
                                                , null          // 資料作成日
                                                , null          // 給与情報 1:未 2:済
                                                , null          // 備考1
                                                , null          // 備考2
                                                , null          // 納特納付書の連携
                                                , null          // 申請すれば還付あり 1:× 2:○
                                                , null          // 0円納付申告 1:× 2:○
                                                , null          // 年調申告 1:× 2:○
                                                , null          // 源泉徴収票 1:× 2:○
                                                , null          // 請求フラグ 1:× 2:○
                                                , null          // 入金確認フラグ 1:× 2:○
                                            );
                    });

                    //---------------------------------------------------------------
                    //--資料作成日テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="matecret_at_"]').change( function(e){
                        // alert('資料作成日Click');
                        var wok_id           = $(this).attr("name").replace('matecret_at_', '');
                        var this_id          = $(this).attr("id");
                        var matecret_at      = $(this).val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // yrendadjustテーブルのID
                                                , null          // 年調の有無 1:無 2:有
                                                , null          // 受託人数
                                                , null          // 伝達手段
                                                , null          // アナウンス日
                                                , null          // 書類の案内日
                                                , null          // 資料回収日
                                                , null          // 資料再請求日
                                                , matecret_at          // 資料作成日
                                                , null          // 給与情報 1:未 2:済
                                                , null          // 備考1
                                                , null          // 備考2
                                                , null          // 納特納付書の連携
                                                , null          // 申請すれば還付あり 1:× 2:○
                                                , null          // 0円納付申告 1:× 2:○
                                                , null          // 年調申告 1:× 2:○
                                                , null          // 源泉徴収票 1:× 2:○
                                                , null          // 請求フラグ 1:× 2:○
                                                , null          // 入金確認フラグ 1:× 2:○
                                            );
                    });

                    //---------------------------------------------------------------
                    //--給与情報プルダウンイベントハンドラ
                    //---------------------------------------------------------------
                    $('select[name^="salary_flg_"]').change( function(e){
                        // alert('給与情報Click');
                        var wok_id           = $(this).attr("name").replace('salary_flg_', '');
                        var this_id          = $(this).attr("id");
                        var salary_flg       = $("#"+this_id + " option:selected").val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // yrendadjustテーブルのID
                                                , null          // 年調の有無 1:無 2:有
                                                , null          // 受託人数
                                                , null          // 伝達手段
                                                , null          // アナウンス日
                                                , null          // 書類の案内日
                                                , null          // 資料回収日
                                                , null          // 資料再請求日
                                                , null          // 資料作成日
                                                , salary_flg          // 給与情報 1:未 2:済
                                                , null          // 備考1
                                                , null          // 備考2
                                                , null          // 納特納付書の連携
                                                , null          // 申請すれば還付あり 1:× 2:○
                                                , null          // 0円納付申告 1:× 2:○
                                                , null          // 年調申告 1:× 2:○
                                                , null          // 源泉徴収票 1:× 2:○
                                                , null          // 請求フラグ 1:× 2:○
                                                , null          // 入金確認フラグ 1:× 2:○
                                            );
                    });

                    //---------------------------------------------------------------
                    //--備考1テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="remark_1_"]').change( function(e){
                        // alert('備考1Click');
                        var wok_id           = $(this).attr("name").replace('remark_1_', '');
                        var this_id          = $(this).attr("id");
                        var remark_1         = $(this).val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // yrendadjustテーブルのID
                                                , null          // 年調の有無 1:無 2:有
                                                , null          // 受託人数
                                                , null          // 伝達手段
                                                , null          // アナウンス日
                                                , null          // 書類の案内日
                                                , null          // 資料回収日
                                                , null          // 資料再請求日
                                                , null          // 資料作成日
                                                , null          // 給与情報 1:未 2:済
                                                , remark_1          // 備考1
                                                , null          // 備考2
                                                , null          // 納特納付書の連携
                                                , null          // 申請すれば還付あり 1:× 2:○
                                                , null          // 0円納付申告 1:× 2:○
                                                , null          // 年調申告 1:× 2:○
                                                , null          // 源泉徴収票 1:× 2:○
                                                , null          // 請求フラグ 1:× 2:○
                                                , null          // 入金確認フラグ 1:× 2:○
                                            );
                    });

                    //---------------------------------------------------------------
                    //--備考2テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="remark_2_"]').change( function(e){
                        // alert('備考2Click');
                        var wok_id           = $(this).attr("name").replace('remark_2_', '');
                        var this_id          = $(this).attr("id");
                        var remark_2         = $(this).val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // yrendadjustテーブルのID
                                                , null          // 年調の有無 1:無 2:有
                                                , null          // 受託人数
                                                , null          // 伝達手段
                                                , null          // アナウンス日
                                                , null          // 書類の案内日
                                                , null          // 資料回収日
                                                , null          // 資料再請求日
                                                , null          // 資料作成日
                                                , null          // 給与情報 1:未 2:済
                                                , null          // 備考1
                                                , remark_2          // 備考2
                                                , null          // 納特納付書の連携
                                                , null          // 申請すれば還付あり 1:× 2:○
                                                , null          // 0円納付申告 1:× 2:○
                                                , null          // 年調申告 1:× 2:○
                                                , null          // 源泉徴収票 1:× 2:○
                                                , null          // 請求フラグ 1:× 2:○
                                                , null          // 入金確認フラグ 1:× 2:○
                                            );
                    });

                    //---------------------------------------------------------------
                    //--納特納付書の連携テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="cooperat_"]').change( function(e){
                        // alert('納特納付書の連携Click');
                        var wok_id           = $(this).attr("name").replace('cooperat_', '');
                        var this_id          = $(this).attr("id");
                        var cooperat         = $(this).val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // yrendadjustテーブルのID
                                                , null          // 年調の有無 1:無 2:有
                                                , null          // 受託人数
                                                , null          // 伝達手段
                                                , null          // アナウンス日
                                                , null          // 書類の案内日
                                                , null          // 資料回収日
                                                , null          // 資料再請求日
                                                , null          // 資料作成日
                                                , null          // 給与情報 1:未 2:済
                                                , null          // 備考1
                                                , null          // 備考2
                                                , cooperat          // 納特納付書の連携
                                                , null          // 申請すれば還付あり 1:× 2:○
                                                , null          // 0円納付申告 1:× 2:○
                                                , null          // 年調申告 1:× 2:○
                                                , null          // 源泉徴収票 1:× 2:○
                                                , null          // 請求フラグ 1:× 2:○
                                                , null          // 入金確認フラグ 1:× 2:○
                                            );
                    });

                    //---------------------------------------------------------------
                    //--申請すれば還付ありプルダウンイベントハンドラ
                    //---------------------------------------------------------------
                    $('select[name^="refund_flg_"]').change( function(e){
                        // alert('申請すれば還付ありClick');
                        var wok_id           = $(this).attr("name").replace('refund_flg_', '');
                        var this_id          = $(this).attr("id");
                        var refund_flg       = $("#"+this_id + " option:selected").val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // yrendadjustテーブルのID
                                                , null          // 年調の有無 1:無 2:有
                                                , null          // 受託人数
                                                , null          // 伝達手段
                                                , null          // アナウンス日
                                                , null          // 書類の案内日
                                                , null          // 資料回収日
                                                , null          // 資料再請求日
                                                , null          // 資料作成日
                                                , null          // 給与情報 1:未 2:済
                                                , null          // 備考1
                                                , null          // 備考2
                                                , null          // 納特納付書の連携
                                                , refund_flg          // 申請すれば還付あり 1:× 2:○
                                                , null          // 0円納付申告 1:× 2:○
                                                , null          // 年調申告 1:× 2:○
                                                , null          // 源泉徴収票 1:× 2:○
                                                , null          // 請求フラグ 1:× 2:○
                                                , null          // 入金確認フラグ 1:× 2:○
                                            );
                    });

                    //---------------------------------------------------------------
                    //--0円納付申告プルダウンイベントハンドラ
                    //---------------------------------------------------------------
                    $('select[name^="declaration_flg_"]').change( function(e){
                        // alert('0円納付申告Click');
                        var wok_id           = $(this).attr("name").replace('declaration_flg_', '');
                        var this_id          = $(this).attr("id");
                        var declaration_flg  = $("#"+this_id + " option:selected").val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // yrendadjustテーブルのID
                                                , null          // 年調の有無 1:無 2:有
                                                , null          // 受託人数
                                                , null          // 伝達手段
                                                , null          // アナウンス日
                                                , null          // 書類の案内日
                                                , null          // 資料回収日
                                                , null          // 資料再請求日
                                                , null          // 資料作成日
                                                , null          // 給与情報 1:未 2:済
                                                , null          // 備考1
                                                , null          // 備考2
                                                , null          // 納特納付書の連携
                                                , null          // 申請すれば還付あり 1:× 2:○
                                                , declaration_flg          // 0円納付申告 1:× 2:○
                                                , null          // 年調申告 1:× 2:○
                                                , null          // 源泉徴収票 1:× 2:○
                                                , null          // 請求フラグ 1:× 2:○
                                                , null          // 入金確認フラグ 1:× 2:○
                                            );
                    });

                    //---------------------------------------------------------------
                    //--年調申告プルダウンイベントハンドラ
                    //---------------------------------------------------------------
                    $('select[name^="annual_flg_"]').change( function(e){
                        // alert('年調申告Click');
                        var wok_id           = $(this).attr("name").replace('annual_flg_', '');
                        var this_id          = $(this).attr("id");
                        var annual_flg       = $("#"+this_id + " option:selected").val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // yrendadjustテーブルのID
                                                , null          // 年調の有無 1:無 2:有
                                                , null          // 受託人数
                                                , null          // 伝達手段
                                                , null          // アナウンス日
                                                , null          // 書類の案内日
                                                , null          // 資料回収日
                                                , null          // 資料再請求日
                                                , null          // 資料作成日
                                                , null          // 給与情報 1:未 2:済
                                                , null          // 備考1
                                                , null          // 備考2
                                                , null          // 納特納付書の連携
                                                , null          // 申請すれば還付あり 1:× 2:○
                                                , null          // 0円納付申告 1:× 2:○
                                                , annual_flg          // 年調申告 1:× 2:○
                                                , null          // 源泉徴収票 1:× 2:○
                                                , null          // 請求フラグ 1:× 2:○
                                                , null          // 入金確認フラグ 1:× 2:○
                                            );
                    });

                    //---------------------------------------------------------------
                    //--源泉徴収票プルダウンイベントハンドラ
                    //---------------------------------------------------------------
                    $('select[name^="withhold_flg_"]').change( function(e){
                        // alert('源泉徴収票Click');
                        var wok_id           = $(this).attr("name").replace('withhold_flg_', '');
                        var this_id          = $(this).attr("id");
                        var withhold_flg     = $("#"+this_id + " option:selected").val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // yrendadjustテーブルのID
                                                , null          // 年調の有無 1:無 2:有
                                                , null          // 受託人数
                                                , null          // 伝達手段
                                                , null          // アナウンス日
                                                , null          // 書類の案内日
                                                , null          // 資料回収日
                                                , null          // 資料再請求日
                                                , null          // 資料作成日
                                                , null          // 給与情報 1:未 2:済
                                                , null          // 備考1
                                                , null          // 備考2
                                                , null          // 納特納付書の連携
                                                , null          // 申請すれば還付あり 1:× 2:○
                                                , null          // 0円納付申告 1:× 2:○
                                                , null          // 年調申告 1:× 2:○
                                                , withhold_flg          // 源泉徴収票 1:× 2:○
                                                , null          // 請求フラグ 1:× 2:○
                                                , null          // 入金確認フラグ 1:× 2:○
                                            );
                    });

                    //---------------------------------------------------------------
                    //--請求フラグプルダウンイベントハンドラ
                    //---------------------------------------------------------------
                    $('select[name^="claim_flg_"]').change( function(e){
                        // alert('請求フラグClick');
                        var wok_id           = $(this).attr("name").replace('claim_flg_', '');
                        var this_id          = $(this).attr("id");
                        var claim_flg        = $("#"+this_id + " option:selected").val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // yrendadjustテーブルのID
                                                , null          // 年調の有無 1:無 2:有
                                                , null          // 受託人数
                                                , null          // 伝達手段
                                                , null          // アナウンス日
                                                , null          // 書類の案内日
                                                , null          // 資料回収日
                                                , null          // 資料再請求日
                                                , null          // 資料作成日
                                                , null          // 給与情報 1:未 2:済
                                                , null          // 備考1
                                                , null          // 備考2
                                                , null          // 納特納付書の連携
                                                , null          // 申請すれば還付あり 1:× 2:○
                                                , null          // 0円納付申告 1:× 2:○
                                                , null          // 年調申告 1:× 2:○
                                                , null          // 源泉徴収票 1:× 2:○
                                                , claim_flg          // 請求フラグ 1:× 2:○
                                                , null          // 入金確認フラグ 1:× 2:○
                                            );
                    });

                    //---------------------------------------------------------------
                    //--入金確認フラグプルダウンイベントハンドラ
                    //---------------------------------------------------------------
                    $('select[name^="payment_flg_"]').change( function(e){
                        // alert('入金確認フラグClick');
                        var wok_id           = $(this).attr("name").replace('payment_flg_', '');
                        var this_id          = $(this).attr("id");
                        var payment_flg      = $("#"+this_id + " option:selected").val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // yrendadjustテーブルのID
                                                , null          // 年調の有無 1:無 2:有
                                                , null          // 受託人数
                                                , null          // 伝達手段
                                                , null          // アナウンス日
                                                , null          // 書類の案内日
                                                , null          // 資料回収日
                                                , null          // 資料再請求日
                                                , null          // 資料作成日
                                                , null          // 給与情報 1:未 2:済
                                                , null          // 備考1
                                                , null          // 備考2
                                                , null          // 納特納付書の連携
                                                , null          // 申請すれば還付あり 1:× 2:○
                                                , null          // 0円納付申告 1:× 2:○
                                                , null          // 年調申告 1:× 2:○
                                                , null          // 源泉徴収票 1:× 2:○
                                                , null          // 請求フラグ 1:× 2:○
                                                , payment_flg          // 入金確認フラグ 1:× 2:○
                                            );
                    });

                    /**
                    * this_id         : 対象コントロール
                    * wok_id          : yrendadjustテーブルのID
                    * absence_flg     : 年調の有無
                    * trustees_no     : 受託人数
                    * communica_flg   : 伝達手段
                    *
                    */
                    function change_wokproc_info(  this_id
                                                , wok_id              // wok_id  yrendadjustテーブルのID
                                                , absence_flg         // 年調の有無 1:無 2:有
                                                , trustees_no         // 受託人数
                                                , communica_flg       // 伝達手段
                                                , announce_at         // アナウンス日
                                                , docinfor_at         // 書類の案内日
                                                , doccolle_at         // 資料回収日
                                                , rrequest_at         // 資料再請求日
                                                , matecret_at         // 資料作成日
                                                , salary_flg          // 給与情報 1:未 2:済
                                                , remark_1            // 備考1
                                                , remark_2            // 備考2
                                                , cooperat            // 納特納付書の連携
                                                , refund_flg          // 申請すれば還付あり 1:× 2:○
                                                , declaration_flg     // 0円納付申告 1:× 2:○
                                                , annual_flg          // 年調申告 1:× 2:○
                                                , withhold_flg        // 源泉徴収票 1:× 2:○
                                                , claim_flg           // 請求フラグ 1:× 2:○
                                                , payment_flg         // 入金確認フラグ 1:× 2:○
                                              ){
                            var reqData = new FormData();
                                                           reqData.append( "id"              , wok_id          );
                            if( null != absence_flg      ) reqData.append( "absence_flg"     , absence_flg     );
                            if( null != trustees_no      ) reqData.append( "trustees_no"     , trustees_no     );
                            if( null != communica_flg    ) reqData.append( "communica_flg"   , communica_flg   );
                            if( null != announce_at      ) reqData.append( "announce_at"     , announce_at     );
                            if( null != docinfor_at      ) reqData.append( "docinfor_at"     , docinfor_at     );
                            if( null != doccolle_at      ) reqData.append( "doccolle_at"     , doccolle_at     );
                            if( null != rrequest_at      ) reqData.append( "rrequest_at"     , rrequest_at     );
                            if( null != matecret_at      ) reqData.append( "matecret_at"     , matecret_at     );
                            if( null != salary_flg       ) reqData.append( "salary_flg"      , salary_flg      );
                            if( null != remark_1         ) reqData.append( "remark_1"        , remark_1        );
                            if( null != remark_2         ) reqData.append( "remark_2"        , remark_2        );
                            if( null != cooperat         ) reqData.append( "cooperat"        , cooperat        );
                            if( null != refund_flg       ) reqData.append( "refund_flg"      , refund_flg      );
                            if( null != declaration_flg  ) reqData.append( "declaration_flg" , declaration_flg );
                            if( null != annual_flg       ) reqData.append( "annual_flg"      , annual_flg      );
                            if( null != withhold_flg     ) reqData.append( "withhold_flg"    , withhold_flg    );
                            if( null != claim_flg        ) reqData.append( "claim_flg"       , claim_flg       );
                            if( null != payment_flg      ) reqData.append( "payment_flg"     , payment_flg     );

                            console.log(absence_flg);
                            // console.log(filing_date);

                            // Ajax通信呼出(データファイルのアップロード)
                            AjaxAPI.callAjax(
                                "{{ route('yrendadjust.update_api') }}",
                                reqData,
                                function (res) {
                                    $('#'+this_id).effect("pulsate", { times:2 }, 500);

                                }
                            )
                    };

            </script>

            </tbody>
        </table>

    {{-- ページネーション / pagination）の表示 --}}
    <ul class="pagination justify-content-center">
        {{-- {{ $yrendadjusts->appends(request()->query())->render() }} --}}
    </ul>

@endsection

@section('part_javascript')
{{-- ChangeSideBar("nav-item-system-user"); --}}
    <script type="text/javascript">
            // $('.btn_del').click(function()
            //     if( !confirm('本当に削除しますか？') ){
            //         /* キャンセルの時の処理 */
            //         return false;
            //     }
            //     else{
            //         /*　OKの時の処理 */
            //         return true;
            //     }
            // });
    </script>
@endsection
