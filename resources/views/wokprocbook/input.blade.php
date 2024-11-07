{{-- @extends('layouts.app') --}}
@extends('layouts.api_index')

@section('content')
    @if (session('message'))
        @if (session('message') == 'CSV出力が完了しました。')
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @else
            <div class="alert alert-danger">
                {{ session('message') }}
            </div>
        @endif
    @endif
    {{-- <h2>税理士業務処理簿一覧</h2> --}}
    <div class="text-right">
        {{-- <a class="btn btn-success btn-sm mr-auto" href="{{route('wokprocbook.create')}}">新規登録</a> --}}
    </div>

    <form  class="my-2 my-lg-0 ml-2" action="{{route('wokprocbookexport')}}" method="GET">
        @csrf
        @method('get')
        <table>
            <div style="display:inline-flex">
                <div class="row">
                    <div class="col-sm-2">
                        <a class="btn btn-success" href="{{route('wokprocbook.create')}}">新規登録</a>
                    </div>

                    <div class="col-sm-4">
                        <input type="date" class="form-control" id="frdate" name="frdate" value="{{$frdate}}">
                    </div>

                    <div class="col-sm-4">
                        <input type="date" class="form-control" id="todate"  name="todate" value="{{$todate}}">
                    </div>

                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-secondary">ＣＳＶ出力</button>
                    </div>

                </div>
            </div>
            <tr>
                {{-- <style>
                    .exright{
                        text-align: right;
                    }
                </style> --}}
                {{-- <div class="exright">
                    <select class="custom-select" id="year" name="year">
                        @foreach ($loop_year_flg as $loop_year_flg2)
                            {{-- 2021年のみ --}}
                            {{-- @if ($loop_year_flg2['no']==$nowyear) --}}
                            {{-- <option value="{{ $loop_year_flg2['no'] }}">{{ $loop_year_flg2['name'] }}</option>
                            @else
                            <option disabled value="{{ $loop_year_flg2['no'] }}">{{ $loop_year_flg2['name'] }}</option>
                            @endif
                        @endforeach --}}
                    {{-- </select> --}}
                    {{-- <button type="submit" class="btn btn-secondary btn_sm">検索</button> --}}
                {{-- </div> --}}
            </tr>
        </table>
    </form>
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
        <form method="GET" action="{{ route('wokprocbook.input') }}">
            @csrf
            @method('get')
        {{-- <table class="table table-striped table-borderd"> --}}
        {{-- table-responsive text-nowrap add scope=row 2022/11/09--}}
        <table class="table table-responsive text-nowrap table-striped table-borderd table_sticky">
            <thead>
                <tr>
                    <th scope="row" class ="fixed01 bg-info text-right">@sortablelink('refnumber',      '整理番号')</th>
                    <th scope="row" class ="fixed02 bg-info p-4">@sortablelink('business_name',  '顧客名')</th>
                    <th scope="row" class ="fixed02 bg-info text-right">@sortablelink('busi_class',     '業務区分')</th>
                    <th scope="row" class ="fixed02 bg-info text-right">@sortablelink('contents_class', '内容')</th>
                    <th scope="row" class ="fixed02 bg-info text-right">@sortablelink('facts_class',    '顛末')</th>
                    <th scope="row" class ="fixed02 bg-info text-right">@sortablelink('proc_date',      '処理日')</th>
                    <th scope="row" class ="fixed02 bg-info text-right">@sortablelink('attach_doc',     '添付')</th>
                    {{-- 2022/08/25 --}}
                    {{-- <th scope="col" class ="fixed02 bg-info text-right">@sortablelink('filing_date',         '提出日')</th> --}}
                    <th scope="row" class ="fixed02 bg-info text-right">@sortablelink('filing_date',    '税務代理権限書提出日')</th>
                    <th scope="row" class ="fixed02 bg-info p-3">@sortablelink('name',           '担当')</th>
                    <th scope="row" class ="fixed02 bg-secondary  text-right">操作</th>
                </tr>
            </thead>

            <tbody>
                @if($wokprocbooks->count())
                    @foreach($wokprocbooks as $wokprocbook)
                    <tr>
                        {{-- 整理番号 --}}
                        @php
                            $str = sprintf("%s", $wokprocbook->refnumber);
                        @endphp
                        <th class="fixed03">{{$wokprocbook->refnumber}}</th>
                        {{-- <td>
<input type="text" class="form-control"  id="refnumber_{{$wokprocbook->id}}" name="refnumber_{{$wokprocbook->id}}" value="{{$str}}">
                        </td> --}}

                        {{-- 社名/氏名 --}}
                        @foreach ($customers as $customers2)
                            @if ($customers2->id==$wokprocbook->custm_id)
                                <th class="fixed03">{{ $customers2->business_name }}</th>
                            @endif
                        @endforeach

                        <td>
                            {{-- /'業務区分 1:代理 2:相談'--}}
                            <select class="custom-select" id="busi_class_{{$wokprocbook->id}}" name="busi_class_{{$wokprocbook->id}}">
                                <option value="1" {{ $wokprocbook->busi_class == 1 ? 'selected' : '' }}>代理</option>
                                <option value="2" {{ $wokprocbook->busi_class == 2 ? 'selected' : '' }}>相談</option>
                            </select>
                        </td>

                        {{-- //内容（税目等）1～ --}}
                        <td>
                            <select class="custom-select" id="contents_class_{{$wokprocbook->id}}" name="contents_class_{{$wokprocbook->id}}">
        <option value="1" {{ $wokprocbook->contents_class == 1 ? 'selected' : '' }}>一般的な税務・経営の相談</option>
        <option value="2" {{ $wokprocbook->contents_class == 2 ? 'selected' : '' }}>異動届（本店・代表者住所変更</option>
        <option value="3" {{ $wokprocbook->contents_class == 3 ? 'selected' : '' }}>異動届（本店住所変更）</option>
        <option value="4" {{ $wokprocbook->contents_class == 4 ? 'selected' : '' }}>確定申告の勉強会</option>
        <option value="5" {{ $wokprocbook->contents_class == 5 ? 'selected' : '' }}>帰化申請の為の数字を教示</option>
        <option value="6" {{ $wokprocbook->contents_class == 6 ? 'selected' : '' }}>源泉所得税（0円納付）</option>
        <option value="7" {{ $wokprocbook->contents_class == 7 ? 'selected' : '' }}>設立届・青色・給与支払・納期の特例承認申請書</option>
        <option value="8" {{ $wokprocbook->contents_class == 8 ? 'selected' : '' }}>法人設立・設置届出書（支店設置）</option>
        <option value="9" {{ $wokprocbook->contents_class == 9 ? 'selected' : '' }}>法定調書・給与支払報告書</option>
        <option value="10" {{ $wokprocbook->contents_class == 10 ? 'selected' : '' }}>役員報酬相談</option>
        <option value="11" {{ $wokprocbook->contents_class == 11 ? 'selected' : '' }}>法人税・消費税確定申告</option>
        <option value="12" {{ $wokprocbook->contents_class == 12 ? 'selected' : '' }}>法人税確定申告</option>
        <option value="13" {{ $wokprocbook->contents_class == 13 ? 'selected' : '' }}>消費税申告</option>
        <option value="14" {{ $wokprocbook->contents_class == 14 ? 'selected' : '' }}>確定申告書</option>
        <option value="15" {{ $wokprocbook->contents_class == 15 ? 'selected' : '' }}>確定申告書（訂正申告）</option>
        <option value="16" {{ $wokprocbook->contents_class == 16 ? 'selected' : '' }}>確定申告書・消費税申告書</option>
        <option value="17" {{ $wokprocbook->contents_class == 17 ? 'selected' : '' }}>給与支払・納期の特例承認申請書</option>
        <option value="18" {{ $wokprocbook->contents_class == 18 ? 'selected' : '' }}>年末調整過納額還付請求</option>
        {{-- // 2022/08/25 Add --}}
        <option value="19" {{ $wokprocbook->contents_class == 19 ? 'selected' : '' }}>会計処理</option>
        <option value="20" {{ $wokprocbook->contents_class == 29 ? 'selected' : '' }}>その他</option>
                        </select>
                        </td>

                        {{-- //顛末 --}}
                        <td>
                    <select class="custom-select" id="facts_class_{{$wokprocbook->id}}" name="facts_class_{{$wokprocbook->id}}">
                    <option value="1" {{ $wokprocbook->facts_class == 1 ? 'selected' : '' }}>申告</option>
                    <option value="2" {{ $wokprocbook->facts_class == 2 ? 'selected' : '' }}>相談</option>
                    <option value="3" {{ $wokprocbook->facts_class == 3 ? 'selected' : '' }}>勉強会</option>
                    <option value="4" {{ $wokprocbook->facts_class == 4 ? 'selected' : '' }}>確定申告書提出</option>
                    <option value="5" {{ $wokprocbook->facts_class == 5 ? 'selected' : '' }}>還付請求書提出</option>
                    <option value="6" {{ $wokprocbook->facts_class == 6 ? 'selected' : '' }}>届出書・報告書提出</option>
                    <option value="7" {{ $wokprocbook->facts_class == 7 ? 'selected' : '' }}>届出書提出</option>
                    <option value="8" {{ $wokprocbook->facts_class == 8 ? 'selected' : '' }}>数字の教示</option>
        {{-- // 2022/08/25 Add --}}
                    <option value="9" {{ $wokprocbook->facts_class == 9 ? 'selected' : '' }}>会計処理</option>
                    <option value="10" {{ $wokprocbook->facts_class == 10 ? 'selected' : '' }}>その他</option>
                    </select>
                        </td>

                        {{-- //処理年月日 --}}
                        @php
                            $str = "";
                            if (isset($wokprocbook->proc_date)) {
                                $str = ( new DateTime($wokprocbook->proc_date))->format('Y-m-d');
                            }
                        @endphp
                        {{-- <td>{{ $str }}</td> --}}
    <td><input type="date" class="form-control"  id="proc_date_{{$wokprocbook->id}}" name="proc_date_{{$wokprocbook->id}}" value="{{$str}}"></td>

                        {{-- //添付書面 1:無 2:有 --}}
                        <td>
                        <select class="custom-select" id="attach_doc_{{$wokprocbook->id}}" name="attach_doc_{{$wokprocbook->id}}">
                            <option value="1" {{ $wokprocbook->attach_doc == 1 ? 'selected' : '' }}>無</option>
                            <option value="2" {{ $wokprocbook->attach_doc == 2 ? 'selected' : '' }}>有</option>
                        </select>
                        </td>

                        {{-- 提出日 -> 税務代理権限書提出日 2022/08/25--}}
                        @php
                            $str = "";
                            if (isset($wokprocbook->filing_date)) {
                                $str = ( new DateTime($wokprocbook->filing_date))->format('Y-m-d');
                            }
                        @endphp
                        {{-- <td>{{ $str }}</td> --}}
<td><input type="date" class="form-control"  id="filing_date_{{$wokprocbook->id}}" name="filing_date_{{$wokprocbook->id}}" value="{{$str}}"></td>

                        {{-- 担当税理士 --}}
                        @foreach ($users as $user2)
                            @if ($user2->id==$wokprocbook->staff_no)
                                <td>{{ $user2->name }}</td>
                            @endif
                        @endforeach

                        <td>
                            <div class="btn-toolbar">
                                <div class="btn-group me-2 mb-0">
                                <a class="btn btn-primary btn-sm" href="{{ route('wokprocbook.edit',$wokprocbook->id)}}">編集</a>
                                </div>
                                <div class="btn-group me-2 mb-0">
                                    <form action="{{ route('wokprocbook.destroy', $wokprocbook->id)}}" method="POST">
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
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        {{-- <td><p> </p></td> --}}
                    </tr>
                @endif
                <script type="text/javascript">

                    //---------------------------------------------------------------
                    //--整理番号テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="refnumber_"]').change( function(e){
                        // alert('整理番号Click');
                        var wok_id           = $(this).attr("name").replace('refnumber_', '');
                        var refnumber        = $(this).val();
                        var this_id          = $(this).attr("id");
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // wokprocbookテーブルのID
                                                , refnumber     // 整理番号
                                                , null          // 業務区分
                                                , null          // 内容（税目等）
                                                , null          // 顛末
                                                , null          // 処理年月日
                                                , null          // 添付書面
                                                , null          // 提出日
                                                , null          // 担当税理士
                                            );
                    });

                    //---------------------------------------------------------------
                    //--業務区分プルダウンイベントハンドラ
                    //---------------------------------------------------------------
                    $('select[name^="busi_class_"]').change( function(e){
                        // alert('業務区分Click');
                        var wok_id           = $(this).attr("name").replace('busi_class_', '');
                        var this_id          = $(this).attr("id");
                        var busi_class       = $("#"+this_id + " option:selected").val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // wokprocbookテーブルのID
                                                , null          // 整理番号
                                                , busi_class    // 業務区分
                                                , null          // 内容（税目等）
                                                , null          // 顛末
                                                , null          // 処理年月日
                                                , null          // 添付書面
                                                , null          // 提出日
                                                , null          // 担当税理士
                                            );
                    });

                    //---------------------------------------------------------------
                    //--内容（税目等）プルダウンイベントハンドラ
                    //---------------------------------------------------------------
                    $('select[name^="contents_class_"]').change( function(e){
                        // alert('内容（税目等）Click');
                        var wok_id           = $(this).attr("name").replace('contents_class_', '');
                        var this_id          = $(this).attr("id");
                        var contents_class   = $("#"+this_id + " option:selected").val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // wokprocbookテーブルのID
                                                , null          // 整理番号
                                                , null          // 業務区分
                                                , contents_class   // 内容（税目等）
                                                , null          // 顛末
                                                , null          // 処理年月日
                                                , null          // 添付書面
                                                , null          // 提出日
                                                , null          // 担当税理士
                                            );
                    });

                    //---------------------------------------------------------------
                    //--顛末プルダウンイベントハンドラ
                    //---------------------------------------------------------------
                    $('select[name^="facts_class_"]').change( function(e){
                        // alert('顛末Click');
                        var wok_id           = $(this).attr("name").replace('facts_class_', '');
                        var this_id          = $(this).attr("id");
                        var facts_class      = $("#"+this_id + " option:selected").val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // wokprocbookテーブルのID
                                                , null          // 整理番号
                                                , null          // 業務区分
                                                , null          // 内容（税目等）
                                                , facts_class   // 顛末
                                                , null          // 処理年月日
                                                , null          // 添付書面
                                                , null          // 提出日
                                                , null          // 担当税理士
                                            );
                    });

                    //---------------------------------------------------------------
                    //--処理年月日テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="proc_date_"]').change( function(e){
                        // alert('処理年月日Click');
                        var wok_id           = $(this).attr("name").replace('proc_date_', '');
                        var proc_date        = $(this).val();
                        var this_id          = $(this).attr("id");
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // wokprocbookテーブルのID
                                                , null          // 整理番号
                                                , null          // 業務区分
                                                , null          // 内容（税目等）
                                                , null          // 顛末
                                                , proc_date     // 処理年月日
                                                , null          // 添付書面
                                                , null          // 提出日
                                                , null          // 担当税理士
                                            );
                    });

                    //---------------------------------------------------------------
                    //--添付書面プルダウンイベントハンドラ
                    //---------------------------------------------------------------
                    $('select[name^="attach_doc_"]').change( function(e){
                        // alert('添付書面Click');
                        var wok_id           = $(this).attr("name").replace('attach_doc_', '');
                        var this_id          = $(this).attr("id");
                        var attach_doc       = $("#"+this_id + " option:selected").val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // wokprocbookテーブルのID
                                                , null          // 整理番号
                                                , null          // 業務区分
                                                , null          // 内容（税目等）
                                                , null          // 顛末
                                                , null          // 処理年月日
                                                , attach_doc    // 添付書面
                                                , null          // 提出日
                                                , null          // 担当税理士
                                            );
                    });

                    //---------------------------------------------------------------
                    //--提出日テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="filing_date_"]').change( function(e){
                        // alert('提出日Click');
                        var wok_id           = $(this).attr("name").replace('filing_date_', '');
                        var filing_date      = $(this).val();
                        var this_id          = $(this).attr("id");
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // wokprocbookテーブルのID
                                                , null          // 整理番号
                                                , null          // 業務区分
                                                , null          // 内容（税目等）
                                                , null          // 顛末
                                                , null          // 処理年月日
                                                , null          // 添付書面
                                                , filing_date   // 提出日
                                                , null          // 担当税理士
                                            );
                    });

                    //---------------------------------------------------------------
                    //--担当税理士プルダウンイベントハンドラ
                    //---------------------------------------------------------------
                    $('select[name^="staff_no_"]').change( function(e){
                        // alert('担当税理士Click');
                        var wok_id           = $(this).attr("name").replace('staff_no_', '');
                        var this_id          = $(this).attr("id");
                        var staff_no         = $("#"+this_id + " option:selected").val();
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // wokprocbookテーブルのID
                                                , null          // 整理番号
                                                , null          // 業務区分
                                                , null          // 内容（税目等）
                                                , null          // 顛末
                                                , null          // 処理年月日
                                                , null          // 添付書面
                                                , null          // 提出日
                                                , staff_no      // 担当税理士
                                            );
                    });

                    /**
                    * this_id         : 対象コントロール
                    * wok_id          : wokprocbookテーブルのID
                    * proc_date       : 処理年月日
                    * filing_date     : 提出日
                    * attach_doc      : 添付書面
                    *
                    */
                    function change_wokproc_info(  this_id
                                                , wok_id           // wok_id  wokprocbookテーブルのID
                                                , refnumber        // 整理番号
                                                , busi_class       // 業務区分
                                                , contents_class   // 内容（税目等）
                                                , facts_class      // 顛末
                                                , proc_date        // 処理年月日
                                                , attach_doc       // 添付書面
                                                , filing_date      // 提出日
                                                , staff_no         // 担当税理士
                                                            ){
                            var reqData = new FormData();
                                                          reqData.append( "id"             , wok_id      );
                            if( null != refnumber       ) reqData.append( "refnumber"      , refnumber );
                            if( null != busi_class      ) reqData.append( "busi_class"     , busi_class   );
                            if( null != contents_class  ) reqData.append( "contents_class" , contents_class  );
                            if( null != facts_class     ) reqData.append( "facts_class"    , facts_class );
                            if( null != proc_date       ) reqData.append( "proc_date"      , proc_date   );
                            if( null != attach_doc      ) reqData.append( "attach_doc"     , attach_doc  );
                            if( null != filing_date     ) reqData.append( "filing_date"    , filing_date );
                            if( null != staff_no        ) reqData.append( "staff_no"       , staff_no  );
                            // console.log(proc_date);
                            // console.log(filing_date);

                            // Ajax通信呼出(データファイルのアップロード)
                            AjaxAPI.callAjax(
                                "{{ route('wokprocbook.update_api') }}",
                                reqData,
                                function (res) {
                                    $('#'+this_id).effect("pulsate", { times:2 }, 500);

                                }
                            )
                    };


            </script>

            </tbody>
        </table>
        </form>
    {{-- </div> --}}

     {{-- ページネーション / pagination）の表示 --}}
     <ul class="pagination justify-content-center">
        {{ $wokprocbooks->appends(request()->query())->render() }}
     </ul>

@endsection

@section('part_javascript')
{{-- ChangeSideBar("nav-item-system-user"); --}}
    <script type="text/javascript">

    </script>
@endsection
