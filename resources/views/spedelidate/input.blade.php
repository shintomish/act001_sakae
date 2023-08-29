{{-- @extends('layouts.app') --}}
@extends('layouts.api_index')

@section('content')
    {{-- <h2>納期特例一覧</h2> --}}
    <div class="text-right">
        <a class="btn btn-success btn-sm mr-auto" href="{{route('spedelidate.create')}}">新規登録</a>
    </div>

    <div class="row">
        <!-- 検索エリア -->
        {{-- <!--- form  class="my-2 my-lg-0 ml-2" action="{{route('spedelidateserchyear')}}" method="GET"> --}}
            {{-- @csrf --}}
            {{-- @method('get') --}}
            {{-- <table>
                <tr>
                    <style>
                        .exright{
                            text-align: right;
                        }
                    </style>
                    <div class="exright">
                        <select class="custom-select" id="year" name="year"> --}}
                            {{-- @foreach ($loop_year_flg as $loop_year_flg2) --}}
                                {{-- 2021年のみ --}}
                                {{-- @if ($loop_year_flg2['no']==$nowyear)
                                <option value="{{ $loop_year_flg2['no'] }}">{{ $loop_year_flg2['name'] }}</option>
                                @else
                                <option disabled value="{{ $loop_year_flg2['no'] }}">{{ $loop_year_flg2['name'] }}</option>
                                @endif --}}
                                {{-- @if ($loop_year_flg2['no']==0) --}}
                                    {{-- <option disabled value="{{ $loop_year_flg2['no'] }}">{{ $loop_year_flg2['name'] }}</option> --}}
                                {{-- @else --}}
                                    {{-- @if ($loop_year_flg2['no']==$nowyear) --}}
                                        {{-- <option selected value="{{ $nowyear }}">{{ $loop_year_flg2['name'] }}</option> --}}
                                    {{-- @else --}}
                                        {{-- <option value="{{ $loop_year_flg2['no'] }}">{{ $loop_year_flg2['name'] }}</option> --}}
                                    {{-- @endif --}}
                                {{-- @endif --}}
                            {{-- @endforeach --}}
                        {{-- </select>
                        <button type="submit" class="btn btn-secondary btn_sm">検索</button>
                    </div>
                </tr>
            </table>
        </form --> --}}
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
            z-index: 1;
        }
        .fixed03{
            position: sticky;
            top: 0;
            left: 0;
            color: rgb(0, 55, 255);
            background: rgb(0, 0, 0);
            z-index: 2;
        }
    </style>

    {{-- <div class="table-responsive"> --}}

        {{-- <table class="table table-striped table-borderd table_sticky"> --}}
        {{-- table-responsive text-nowrap add scope=row 2022/11/09--}}
        <table class="table table-responsive text-nowrap table-striped table-borderd table_sticky">
            <thead>
                <tr>
                    <th scope="row" class ="fixed01 bg-info">ID</th>
                    {{-- <th scope="col" class ="bg-info text-right">@sortablelink('business_code', '事業者コード')</th> --}}
                    <th scope="row" class ="fixed01 bg-info text-right">@sortablelink('business_name', '顧客名')</th>
                    {{-- <th scope="col" class ="bg-info text-right">代表者</th> --}}
                    <th scope="row" class ="fixed02 bg-info text-right">@sortablelink('closing_month', '決算月')</th>
                    <th scope="row" class ="fixed02 bg-info text-right">役員報酬</th>
                    <th scope="row" class ="fixed02 bg-info text-right">従業員</th>
                    <th scope="row" class ="fixed02 bg-info text-right">納付種別</th>
                    <th scope="row" class ="fixed02 bg-success text-right">達人入力</th>
                    <th scope="row" class ="fixed02 bg-success text-right">納付作成</th>
                    <th scope="row" class ="fixed02 bg-success text-right">納付申告</th>
                    <th scope="row" class ="fixed02 bg-warning text-right">支払日注意</th>
                    {{-- <div class="opacit-40">rgba(255, 255, 0, 0.4)黄色 --}}
                    {{-- <th scope="col" class ="text-right" style="background-color: rgba(255, 255, 0, 0.4); ">確認事項</th> --}}

                    <th scope="row" class ="fixed02 bg-success text-right">報酬変更</th>
                    {{-- <th scope="col" class ="bg-success text-right">変更後</th> --}}
                    {{-- <th scope="col" class ="bg-success text-right">変更時期</th> --}}
                    {{-- <th scope="col" class ="bg-success text-right">納付書データ</th> --}}
                    <th scope="row" class ="fixed02 bg-secondary text-right">操作</th>
                </tr>
            </thead>

            <tbody>
                @if($spedelidates->count())
                    @foreach($spedelidates as $spedelidate)
                    <tr>
                        {{-- ID --}}
                        <th class="fixed03">{{ $spedelidate->id }}</th>

                        {{-- 事業者コード --}}
                        {{-- @foreach ($customers as $customers2)
                            @if ($customers2->id==$spedelidate->custm_id)
                                <td>{{ $customers2->business_code }}</td>
                            @endif
                        @endforeach --}}

                        {{-- 社名/氏名 --}}
                        @foreach ($customers as $customers2)
                            @if ($customers2->id==$spedelidate->custm_id)
                                {{-- <td>{{ $customers2->business_name }}</td> --}}
                                <th class="fixed03">{{ $customers2->business_name }}</th>
                            @endif
                        @endforeach

                        {{-- 代表者 --}}
                        {{-- @foreach ($customers as $customers2)
                            @if ($customers2->id==$spedelidate->custm_id)
                                <td>{{ $customers2->represent_name }}</td>
                            @endif
                        @endforeach --}}

                        {{-- 決算月 --}}
                        @foreach ($customers as $customers2)
                            @if ($customers2->id==$spedelidate->custm_id)
                                @foreach ($loop_closing_month as $loop_closing_month2)
                                    @if ($loop_closing_month2['no']==$customers2->closing_month)
                                        <td>{{ $loop_closing_month2['name'] }}</td>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                        {{-- 役員報酬 --}}
                        {{-- <td>{{ number_format($spedelidate->officecompe) }}</td> --}}
                        @php
                            $str = sprintf("%s", $spedelidate->officecompe);
                        @endphp
                        <td>
<input type="text" class="form-control"  id="officecompe_{{$spedelidate->id}}" name="officecompe_{{$spedelidate->id}}" value="{{$str}}">
                        </td>

                        {{-- 従業員 --}}
                        {{-- <td>{{ number_format($spedelidate->employee) }}</td> --}}
                        @php
                            $str = sprintf("%s", $spedelidate->employee);
                        @endphp
                        <td>
<input type="text" class="form-control"  id="employee_{{$spedelidate->id}}" name="employee_{{$spedelidate->id}}" value="{{$str}}">
                        </td>

                        {{-- 納付種別 --}}
                        {{-- <td>{{ $spedelidate->paymenttype }}</td> --}}
                        @php
                            $str = sprintf("%s", $spedelidate->paymenttype);
                        @endphp
                        <td>
<input type="text" class="form-control"  id="paymenttype_{{$spedelidate->id}}" name="paymenttype_{{$spedelidate->id}}" value="{{$str}}" placeholder="0円納付">
                        </td>

                        {{-- 達人入力 1:― 2:○', --}}
                        {{-- @foreach ($loop_circle_cross as $loop_circle_cross2)
                            @if ($loop_circle_cross2['no']==$spedelidate->adept_flg)
                                <td>{{ $loop_circle_cross2['name'] }}</td>
                            @endif
                        @endforeach --}}
                        <td>
                            <select class="custom-select" id="adept_flg_{{$spedelidate->id}}" name="adept_flg_{{$spedelidate->id}}">
                                <option value="1" {{ $spedelidate->adept_flg == 1 ? 'selected' : '' }}>―</option>
                                <option value="2" {{ $spedelidate->adept_flg == 2 ? 'selected' : '' }}>○</option>
                            </select>
                        </td>

                        {{-- 納付書作成 1:― 2:○', --}}
                        {{-- @foreach ($loop_payslip_flg as $loop_payslip_flg2)
                            @if ($loop_payslip_flg2['no']==$spedelidate->payslip_flg)
                                <td>{{ $loop_payslip_flg2['name'] }}</td>
                            @endif
                        @endforeach --}}
                        <td>
                            <select class="custom-select" id="payslip_flg_{{$spedelidate->id}}" name="payslip_flg_{{$spedelidate->id}}">
                                <option value="1" {{ $spedelidate->payslip_flg == 1 ? 'selected' : '' }}>―</option>
                                <option value="2" {{ $spedelidate->payslip_flg == 2 ? 'selected' : '' }}>○</option>
                            </select>
                        </td>

                        {{-- 0円納付申告 1:― 2:○', --}}
                        {{-- @foreach ($loop_declaration_flg as $loop_declaration_flg2)
                            @if ($loop_declaration_flg2['no']==$spedelidate->declaration_flg)
                                <td>{{ $loop_declaration_flg2['name'] }}</td>
                            @endif
                        @endforeach --}}
                        <td>
                        <select class="custom-select" id="declaration_flg_{{$spedelidate->id}}" name="declaration_flg_{{$spedelidate->id}}">
                                <option value="1" {{ $spedelidate->declaration_flg == 1 ? 'selected' : '' }}>―</option>
                                <option value="2" {{ $spedelidate->declaration_flg == 2 ? 'selected' : '' }}>○</option>
                            </select>
                        </td>

                        {{-- 支払日注意 --}}
                        {{-- <td>{{ $spedelidate->paydate_att }}</td> --}}
                        @php
                            $str = sprintf("%s", $spedelidate->paydate_att);
                        @endphp
                        <td>
<input type="text" class="form-control"  id="paydate_att_{{$spedelidate->id}}" name="paydate_att_{{$spedelidate->id}}" value="{{$str}}" placeholder="末締/当月末払い">
                        </td>

                        {{-- 確認事項 --}}
                        {{-- <td>{{ $spedelidate->checklist }}</td> --}}
                        {{-- @php
                            $str = sprintf("%s", $spedelidate->checklist);
                        @endphp
                        <td>
<input type="text" class="form-control"  id="checklist_{{$spedelidate->id}}" name="checklist_{{$spedelidate->id}}" value="{{$str}}">
                        </td> --}}

                        {{-- 役員報酬変更 --}}
                        {{-- @foreach ($loop_chaneg_flg as $loop_chaneg_flg2)
                            @if ($loop_chaneg_flg2['no']==$spedelidate->chaneg_flg)
                                <td>{{ $loop_chaneg_flg2['name'] }}</td>
                            @endif
                        @endforeach --}}
                        <td>
                        <select class="custom-select" id="chaneg_flg_{{$spedelidate->id}}" name="chaneg_flg_{{$spedelidate->id}}">
                                    <option value="1" {{ $spedelidate->chaneg_flg == 1 ? 'selected' : '' }}>―</option>
                                    <option value="2" {{ $spedelidate->chaneg_flg == 2 ? 'selected' : '' }}>○</option>
                                </select>
                        </td>

                        {{-- 変更後 --}}
                        {{-- <td>{{ $spedelidate->after_change }}</td> --}}
                        {{-- @php
                            $str = sprintf("%s", $spedelidate->after_change);
                        @endphp
                        <td>
<input type="text" class="form-control"  id="after_change_{{$spedelidate->id}}" name="after_change_{{$spedelidate->id}}" value="{{$str}}">
                        </td> --}}

                        {{-- 変更時期 --}}
                        {{-- <td>{{ $spedelidate->change_time }}</td> --}}
                        {{-- @php
                            $str = sprintf("%s", $spedelidate->change_time);
                        @endphp
                        <td>
<input type="text" class="form-control"  id="change_time_{{$spedelidate->id}}" name="change_time_{{$spedelidate->id}}" value="{{$str}}">
                        </td> --}}

                        {{-- 納付書データの連携 --}}
                        {{-- <td>{{ $spedelidate->linkage_pay }}</td> --}}
                        {{-- @php
                            $str = sprintf("%s", $spedelidate->linkage_pay);
                        @endphp
                        <td>
<input type="text" class="form-control"  id="linkage_pay_{{$spedelidate->id}}" name="linkage_pay_{{$spedelidate->id}}" value="{{$str}}">
                        </td> --}}

                       <td>
                            <div class="btn-toolbar">
                                {{-- <div class="btn-group me-2 mb-0"> --}}
                                <a class="btn btn-primary btn-sm" href="{{ route('spedelidate.edit',$spedelidate->id)}}">編集</a>
                                {{-- </div> --}}
                                {{-- <div class="btn-group me-2 mb-0"> --}}
                                    <form action="{{ route('spedelidate.destroy', $spedelidate->id)}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <input class="btn btn-danger btn-sm" type="submit" value="削除" id="btn_del"
                                            onclick='return confirm("削除しますか？");'>
                                    </form>
                                {{-- </div> --}}
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td><p>0件です。</p></td>
                        {{-- <td><p> </p></td> --}}
                        {{-- <td><p> </p></td> --}}
                        {{-- <td><p> </p></td> --}}
                        {{-- <td><p> </p></td> --}}
                        {{-- <td><p> </p></td> --}}
                        {{-- <td><p> </p></td> --}}
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
                    //--役員報酬テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="officecompe_"]').change( function(e){
                        // alert('役員報酬Click');
                        var wok_id           = $(this).attr("name").replace('officecompe_', '');
                        var this_id          = $(this).attr("id");
                        var officecompe      = $(this).val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // spedelidateテーブルのID
                                                , officecompe   // 役員報酬
                                                , null          // 従業員
                                                , null          // 納付種別
                                                , null          // 達人フラグ
                                                , null          // 納付書作成
                                                , null          // 0円納付申告
                                                , null          // 支払日注意
                                                , null          // 確認事項
                                                , null          // 役員報酬変更なしあり
                                                , null          // 変更後
                                                , null          // 変更時期
                                                , null          // 納付書データの連携
                                            );
                    });

                    //---------------------------------------------------------------
                    //--従業員テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="employee_"]').change( function(e){
                        // alert('従業員テキストClick');
                        var wok_id           = $(this).attr("name").replace('employee_', '');
                        var this_id          = $(this).attr("id");
                        var employee         = $(this).val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // spedelidateテーブルのID
                                                , null          // 役員報酬
                                                , employee      // 従業員
                                                , null          // 納付種別
                                                , null          // 達人フラグ
                                                , null          // 納付書作成
                                                , null          // 0円納付申告
                                                , null          // 支払日注意
                                                , null          // 確認事項
                                                , null          // 役員報酬変更なしあり
                                                , null          // 変更後
                                                , null          // 変更時期
                                                , null          // 納付書データの連携
                                            );
                    });

                    //---------------------------------------------------------------
                    //--納付種別テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="paymenttype_"]').change( function(e){
                        // alert('納付種別テキストClick');
                        var wok_id           = $(this).attr("name").replace('paymenttype_', '');
                        var this_id          = $(this).attr("id");
                        var paymenttype      = $(this).val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // spedelidateテーブルのID
                                                , null          // 役員報酬
                                                , null          // 従業員
                                                , paymenttype   // 納付種別
                                                , null          // 達人フラグ
                                                , null          // 納付書作成
                                                , null          // 0円納付申告
                                                , null          // 支払日注意
                                                , null          // 確認事項
                                                , null          // 役員報酬変更なしあり
                                                , null          // 変更後
                                                , null          // 変更時期
                                                , null          // 納付書データの連携
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
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // spedelidateテーブルのID
                                                , null          // 役員報酬
                                                , null          // 従業員
                                                , null          // 納付種別
                                                , adept_flg     // 達人フラグ
                                                , null          // 納付書作成
                                                , null          // 0円納付申告
                                                , null          // 支払日注意
                                                , null          // 確認事項
                                                , null          // 役員報酬変更なしあり
                                                , null          // 変更後
                                                , null          // 変更時期
                                                , null          // 納付書データの連携
                                            );
                    });

                    //---------------------------------------------------------------
                    //--納付書作成プルダウンイベントハンドラ
                    //---------------------------------------------------------------
                    $('select[name^="payslip_flg_"]').change( function(e){
                        // alert('納付書作成Click');
                        var wok_id           = $(this).attr("name").replace('payslip_flg_', '');
                        var this_id          = $(this).attr("id");
                        var payslip_flg      = $("#"+this_id + " option:selected").val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // spedelidateテーブルのID
                                                , null          // 役員報酬
                                                , null          // 従業員
                                                , null          // 納付種別
                                                , null          // 達人フラグ
                                                , payslip_flg   // 納付書作成
                                                , null          // 0円納付申告
                                                , null          // 支払日注意
                                                , null          // 確認事項
                                                , null          // 役員報酬変更なしあり
                                                , null          // 変更後
                                                , null          // 変更時期
                                                , null          // 納付書データの連携
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
                                                , wok_id        // spedelidateテーブルのID
                                                , null          // 役員報酬
                                                , null          // 従業員
                                                , null          // 納付種別
                                                , null          // 達人フラグ
                                                , null          // 納付書作成
                                                , declaration_flg          // 0円納付申告
                                                , null          // 支払日注意
                                                , null          // 確認事項
                                                , null          // 役員報酬変更なしあり
                                                , null          // 変更後
                                                , null          // 変更時期
                                                , null          // 納付書データの連携
                                            );
                    });

                    //---------------------------------------------------------------
                    //--支払日注意テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="paydate_att_"]').change( function(e){
                        // alert('支払日注意テキストClick');
                        var wok_id           = $(this).attr("name").replace('paydate_att_', '');
                        var this_id          = $(this).attr("id");
                        var paydate_att      = $(this).val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // spedelidateテーブルのID
                                                , null          // 役員報酬
                                                , null          // 従業員
                                                , null          // 納付種別
                                                , null          // 達人フラグ
                                                , null          // 納付書作成
                                                , null          // 0円納付申告
                                                , paydate_att   // 支払日注意
                                                , null          // 確認事項
                                                , null          // 役員報酬変更なしあり
                                                , null          // 変更後
                                                , null          // 変更時期
                                                , null          // 納付書データの連携
                                            );
                    });

                    //---------------------------------------------------------------
                    //--確認事項テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="checklist_"]').change( function(e){
                        // alert('確認事項テキストClick');
                        var wok_id           = $(this).attr("name").replace('checklist_', '');
                        var this_id          = $(this).attr("id");
                        var checklist        = $(this).val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // spedelidateテーブルのID
                                                , null          // 役員報酬
                                                , null          // 従業員
                                                , null          // 納付種別
                                                , null          // 達人フラグ
                                                , null          // 納付書作成
                                                , null          // 0円納付申告
                                                , null          // 支払日注意
                                                , checklist     // 確認事項
                                                , null          // 役員報酬変更なしあり
                                                , null          // 変更後
                                                , null          // 変更時期
                                                , null          // 納付書データの連携
                                            );
                    });

                    //---------------------------------------------------------------
                    //--役員報酬変更なしありプルダウンイベントハンドラ
                    //---------------------------------------------------------------
                    $('select[name^="chaneg_flg_"]').change( function(e){
                        // alert('役員報酬変更なしありClick');
                        var wok_id           = $(this).attr("name").replace('chaneg_flg_', '');
                        var this_id          = $(this).attr("id");
                        var chaneg_flg       = $("#"+this_id + " option:selected").val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // spedelidateテーブルのID
                                                , null          // 役員報酬
                                                , null          // 従業員
                                                , null          // 納付種別
                                                , null          // 達人フラグ
                                                , null          // 納付書作成
                                                , null          // 0円納付申告
                                                , null          // 支払日注意
                                                , null          // 確認事項
                                                , chaneg_flg    // 役員報酬変更なしあり
                                                , null          // 変更後
                                                , null          // 変更時期
                                                , null          // 納付書データの連携
                                            );
                    });

                    //---------------------------------------------------------------
                    //--変更後テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="after_change_"]').change( function(e){
                        // alert('変更後テキストClick');
                        var wok_id           = $(this).attr("name").replace('after_change_', '');
                        var this_id          = $(this).attr("id");
                        var after_change     = $(this).val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // spedelidateテーブルのID
                                                , null          // 役員報酬
                                                , null          // 従業員
                                                , null          // 納付種別
                                                , null          // 達人フラグ
                                                , null          // 納付書作成
                                                , null          // 0円納付申告
                                                , null          // 支払日注意
                                                , null          // 確認事項
                                                , null          // 役員報酬変更なしあり
                                                , after_change  // 変更後
                                                , null          // 変更時期
                                                , null          // 納付書データの連携
                                            );
                    });

                    //---------------------------------------------------------------
                    //--変更時期テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="change_time_"]').change( function(e){
                        // alert('変更時期テキストClick');
                        var wok_id           = $(this).attr("name").replace('change_time_', '');
                        var this_id          = $(this).attr("id");
                        var change_time      = $(this).val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // spedelidateテーブルのID
                                                , null          // 役員報酬
                                                , null          // 従業員
                                                , null          // 納付種別
                                                , null          // 達人フラグ
                                                , null          // 納付書作成
                                                , null          // 0円納付申告
                                                , null          // 支払日注意
                                                , null          // 確認事項
                                                , null          // 役員報酬変更なしあり
                                                , null          // 変更後
                                                , change_time   // 変更時期
                                                , null          // 納付書データの連携
                                            );
                    });

                    //---------------------------------------------------------------
                    //--納付書データの連携テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="linkage_pay_"]').change( function(e){
                        // alert('納付書データの連携テキストClick');
                        var wok_id           = $(this).attr("name").replace('linkage_pay_', '');
                        var this_id          = $(this).attr("id");
                        var linkage_pay      = $(this).val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // spedelidateテーブルのID
                                                , null          // 役員報酬
                                                , null          // 従業員
                                                , null          // 納付種別
                                                , null          // 達人フラグ
                                                , null          // 納付書作成
                                                , null          // 0円納付申告
                                                , null          // 支払日注意
                                                , null          // 確認事項
                                                , null          // 役員報酬変更なしあり
                                                , null          // 変更後
                                                , null          // 変更時期
                                                , linkage_pay   // 納付書データの連携
                                            );
                    });

                    /**
                    * this_id         : 対象コントロール
                    * wok_id          : spedelidateテーブルのID
                    * proc_date       : 処理年月日
                    * filing_date     : 提出日
                    * attach_doc      : 添付書面
                    *
                    */
                    function change_wokproc_info(  this_id
                                                , wok_id               // wok_id  spedelidateテーブルのID
                                                , officecompe          // 役員報酬
                                                , employee             // 従業員
                                                , paymenttype          // 納付種別
                                                , adept_flg            // 達人フラグ
                                                , payslip_flg          // 納付書作成
                                                , declaration_flg      // 0円納付申告
                                                , paydate_att          // 支払日注意
                                                , checklist            // 確認事項
                                                , chaneg_flg           // 役員報酬変更なしあり
                                                , after_change         // 変更後
                                                , change_time          // 変更時期
                                                , linkage_pay          // 納付書データの連携
                                            ){
                            var reqData = new FormData();
                                                          reqData.append( "id"                 , wok_id      );
                            if( null != officecompe       ) reqData.append( "officecompe"      , officecompe );
                            if( null != employee          ) reqData.append( "employee"         , employee   );
                            if( null != paymenttype       ) reqData.append( "paymenttype"      , paymenttype  );
                            if( null != adept_flg         ) reqData.append( "adept_flg"        , adept_flg );
                            if( null != payslip_flg       ) reqData.append( "payslip_flg"      , payslip_flg   );
                            if( null != declaration_flg   ) reqData.append( "declaration_flg"  , declaration_flg  );
                            if( null != paydate_att       ) reqData.append( "paydate_att"      , paydate_att );
                            if( null != checklist         ) reqData.append( "checklist"        , checklist  );
                            if( null != chaneg_flg        ) reqData.append( "chaneg_flg"       , chaneg_flg  );
                            if( null != after_change      ) reqData.append( "after_change"     , after_change  );
                            if( null != change_time       ) reqData.append( "change_time"      , change_time  );
                            if( null != linkage_pay       ) reqData.append( "linkage_pay"      , linkage_pay  );

                            // console.log(proc_date);
                            // console.log(filing_date);

                            // Ajax通信呼出(データファイルのアップロード)
                            AjaxAPI.callAjax(
                                "{{ route('spedelidate.update_api') }}",
                                reqData,
                                function (res) {
                                    $('#'+this_id).effect("pulsate", { times:2 }, 500);

                                }
                            )
                    };

            </script>

            </tbody>
        </table>

    {{-- </div> --}}

     {{-- ページネーション / pagination）の表示 --}}
     <ul class="pagination justify-content-center">
        {{-- {{ $spedelidates->appends(request()->query())->render() }} --}}
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
