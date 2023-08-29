{{-- @extends('layouts.app') --}}
@extends('layouts.api_index')

@section('content')
    {{-- <h2>進捗チェック一覧</h2> --}}
    {{-- <div class="text-right"> --}}
        {{-- <a class="btn btn-success btn-sm mr-auto" href="{{route('progrecheck.create')}}">新規登録</a> --}}
    {{-- </div> --}}

    <div class="row">
        <!-- 検索エリア -->
        <!-- 検索エリア -->
    </div>

    {{-- Line --}}
    {{-- <hr class="mb-4"> --}}
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
                height: 100%;
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
    </style>

    {{-- <div class="table-responsive"> --}}

        {{-- <table class="table table-striped table-borderd table-scroll"> --}}
        <table class="table table-striped table-borderd table_sticky">
            <thead>
                <tr>
                    <th scope="col" class ="fixed01" >ID</th>
                    <th scope="col" class ="fixed01" >@sortablelink('business_name', '顧客名')</th>
                    <th scope="col" class ="fixed02" >業務名</th>
                    <th scope="col" class ="fixed02" >01月</th>
                    <th scope="col" class ="fixed02" >02月</th>
                    <th scope="col" class ="fixed02" >03月</th>
                    <th scope="col" class ="fixed02" >04月</th>
                    <th scope="col" class ="fixed02" >05月</th>
                    <th scope="col" class ="fixed02" >06月</th>
                    <th scope="col" class ="fixed02" >07月</th>
                    <th scope="col" class ="fixed02" >08月</th>
                    <th scope="col" class ="fixed02" >09月</th>
                    <th scope="col" class ="fixed02" >10月</th>
                    <th scope="col" class ="fixed02" >11月</th>
                    <th scope="col" class ="fixed02" >12月</th>
                    <th scope="col" >操作</th>
                </tr>
            </thead>

            <tbody>
                @if($progrechecks->count())
                    @foreach($progrechecks as $progrecheck)
                    <tr>
                        {{-- ID --}}
                        <th class ="fixed02" >{{ $progrecheck->id }}</th>

                        {{-- 社名/氏名 --}}
                        @foreach ($customers as $customers2)
                            @if ($customers2->id==$progrecheck->custm_id)
                                <th class ="fixed02" >{{ $customers2->business_name }}</th>
                            @endif
                        @endforeach

                        {{-- 業務名 --}}
                        <td>{{ $progrecheck->businm_no }}</td>
{{-- debug --}}
{{-- @php
    $nowmonth = 12;
@endphp --}}
                        <td>
                            {{-- 01 --}}
                            @if($nowmonth >= 1)
                                <select class="custom-select" id="check_01_{{$progrecheck->id}}" name="check_01_{{$progrecheck->id}}">

                            @else
                            <select disabled class="custom-select" id="check_01_{{$progrecheck->id}}" name="check_01_{{$progrecheck->id}}">
                            @endif
                                <option value="1" {{ $progrecheck->check_01 == 1 ? 'selected' : '' }}>×</option>
                                <option value="2" {{ $progrecheck->check_01 == 2 ? 'selected' : '' }}>△</option>
                                <option value="3" {{ $progrecheck->check_01 == 3 ? 'selected' : '' }}>〇</option>
                            </select>

                        </td>

                        <td>
                        {{-- 02 --}}
                            @if($nowmonth >= 2)
                            <select class="custom-select" id="check_02_{{$progrecheck->id}}" name="check_02_{{$progrecheck->id}}">

                            @else
                            <select disabled class="custom-select" id="check_02_{{$progrecheck->id}}" name="check_02_{{$progrecheck->id}}">
                            @endif
                                <option value="1" {{ $progrecheck->check_02 == 1 ? 'selected' : '' }}>×</option>
                                <option value="2" {{ $progrecheck->check_02 == 2 ? 'selected' : '' }}>△</option>
                                <option value="3" {{ $progrecheck->check_02 == 3 ? 'selected' : '' }}>〇</option>
                            </select>
                        </td>

                        <td>
                        {{-- 03 --}}
                            @if($nowmonth >= 3)
                            <select class="custom-select" id="check_03_{{$progrecheck->id}}" name="check_03_{{$progrecheck->id}}">

                            @else
                            <select disabled class="custom-select" id="check_03_{{$progrecheck->id}}" name="check_03_{{$progrecheck->id}}">
                            @endif
                                <option value="1" {{ $progrecheck->check_03 == 1 ? 'selected' : '' }}>×</option>
                                <option value="2" {{ $progrecheck->check_03 == 2 ? 'selected' : '' }}>△</option>
                                <option value="3" {{ $progrecheck->check_03 == 3 ? 'selected' : '' }}>〇</option>
                            </select>
                        </td>

                        <td>
                        {{-- 04 --}}
                            @if($nowmonth >= 4)
                            <select class="custom-select" id="check_04_{{$progrecheck->id}}" name="check_04_{{$progrecheck->id}}">
                            @else
                            <select disabled class="custom-select" id="check_04_{{$progrecheck->id}}" name="check_04_{{$progrecheck->id}}">
                            @endif
                                <option value="1" {{ $progrecheck->check_04 == 1 ? 'selected' : '' }}>×</option>
                                <option value="2" {{ $progrecheck->check_04 == 2 ? 'selected' : '' }}>△</option>
                                <option value="3" {{ $progrecheck->check_04 == 3 ? 'selected' : '' }}>〇</option>
                            </select>
                        </td>

                        <td>
                        {{-- 05 --}}
                            @if($nowmonth >= 5)
                            <select class="custom-select" id="check_05_{{$progrecheck->id}}" name="check_05_{{$progrecheck->id}}">
                            @else
                            <select disabled class="custom-select" id="check_05_{{$progrecheck->id}}" name="check_05_{{$progrecheck->id}}">
                            @endif
                                <option value="1" {{ $progrecheck->check_05 == 1 ? 'selected' : '' }}>×</option>
                                <option value="2" {{ $progrecheck->check_05 == 2 ? 'selected' : '' }}>△</option>
                                <option value="3" {{ $progrecheck->check_05 == 3 ? 'selected' : '' }}>〇</option>
                            </select>
                        </td>

                        <td>
                        {{-- 06 --}}
                            @if($nowmonth >= 6)
                            <select class="custom-select" id="check_06_{{$progrecheck->id}}" name="check_06_{{$progrecheck->id}}">
                            @else
                            <select disabled class="custom-select" id="check_06_{{$progrecheck->id}}" name="check_06_{{$progrecheck->id}}">
                            @endif
                                <option value="1" {{ $progrecheck->check_06 == 1 ? 'selected' : '' }}>×</option>
                                <option value="2" {{ $progrecheck->check_06 == 2 ? 'selected' : '' }}>△</option>
                                <option value="3" {{ $progrecheck->check_06 == 3 ? 'selected' : '' }}>〇</option>
                            </select>
                        </td>

                        <td>
                        {{-- 07 --}}
                            @if($nowmonth >= 7)
                            <select class="custom-select" id="check_07_{{$progrecheck->id}}" name="check_07_{{$progrecheck->id}}">
                            @else
                            <select disabled class="custom-select" id="check_07_{{$progrecheck->id}}" name="check_07_{{$progrecheck->id}}">
                            @endif
                                <option value="1" {{ $progrecheck->check_07 == 1 ? 'selected' : '' }}>×</option>
                                <option value="2" {{ $progrecheck->check_07 == 2 ? 'selected' : '' }}>△</option>
                                <option value="3" {{ $progrecheck->check_07 == 3 ? 'selected' : '' }}>〇</option>
                            </select>
                        </td>

                        <td>
                        {{-- 08 --}}
                            @if($nowmonth >= 8)
                            <select class="custom-select" id="check_08_{{$progrecheck->id}}" name="check_08_{{$progrecheck->id}}">
                            @else
                            <select disabled class="custom-select" id="check_08_{{$progrecheck->id}}" name="check_08_{{$progrecheck->id}}">
                            @endif
                                <option value="1" {{ $progrecheck->check_08 == 1 ? 'selected' : '' }}>×</option>
                                <option value="2" {{ $progrecheck->check_08 == 2 ? 'selected' : '' }}>△</option>
                                <option value="3" {{ $progrecheck->check_08 == 3 ? 'selected' : '' }}>〇</option>
                            </select>
                        </td>

                        <td>
                        {{-- 09 --}}
                            @if($nowmonth >= 9)
                            <select class="custom-select" id="check_09_{{$progrecheck->id}}" name="check_09_{{$progrecheck->id}}">
                            @else
                            <select disabled class="custom-select" id="check_09_{{$progrecheck->id}}" name="check_09_{{$progrecheck->id}}">
                            @endif
                                <option value="1" {{ $progrecheck->check_09 == 1 ? 'selected' : '' }}>×</option>
                                <option value="2" {{ $progrecheck->check_09 == 2 ? 'selected' : '' }}>△</option>
                                <option value="3" {{ $progrecheck->check_09 == 3 ? 'selected' : '' }}>〇</option>
                            </select>
                        </td>

                        <td>
                        {{-- 10 --}}
                            @if($nowmonth >= 10)
                            <select class="custom-select" id="check_10_{{$progrecheck->id}}" name="check_10_{{$progrecheck->id}}">
                            @else
                            <select disabled class="custom-select" id="check_10_{{$progrecheck->id}}" name="check_10_{{$progrecheck->id}}">
                            @endif
                                <option value="1" {{ $progrecheck->check_10 == 1 ? 'selected' : '' }}>×</option>
                                <option value="2" {{ $progrecheck->check_10 == 2 ? 'selected' : '' }}>△</option>
                                <option value="3" {{ $progrecheck->check_10 == 3 ? 'selected' : '' }}>〇</option>
                            </select>
                        </td>

                        <td>
                        {{-- 11 --}}
                            @if($nowmonth >= 11)
                            <select class="custom-select" id="check_11_{{$progrecheck->id}}" name="check_11_{{$progrecheck->id}}">
                            @else
                            <select disabled class="custom-select" id="check_11_{{$progrecheck->id}}" name="check_11_{{$progrecheck->id}}">
                            @endif
                                <option value="1" {{ $progrecheck->check_11 == 1 ? 'selected' : '' }}>×</option>
                                <option value="2" {{ $progrecheck->check_11 == 2 ? 'selected' : '' }}>△</option>
                                <option value="3" {{ $progrecheck->check_11 == 3 ? 'selected' : '' }}>〇</option>
                            </select>
                        </td>

                        <td>
                        {{-- 12 --}}
                            @if($nowmonth >= 12)
                            <select class="custom-select" id="check_12_{{$progrecheck->id}}" name="check_12_{{$progrecheck->id}}">
                            @else
                            <select disabled class="custom-select" id="check_12_{{$progrecheck->id}}" name="check_12_{{$progrecheck->id}}">
                            @endif
                                <option value="1" {{ $progrecheck->check_12 == 1 ? 'selected' : '' }}>×</option>
                                <option value="2" {{ $progrecheck->check_12 == 2 ? 'selected' : '' }}>△</option>
                                <option value="3" {{ $progrecheck->check_12 == 3 ? 'selected' : '' }}>〇</option>
                            </select>
                        </td>

                        <td>
                            <div class="btn-toolbar">
                                <div class="btn-group me-2 mb-0">
                                <a class="btn btn-primary btn-sm" href="{{ route('progrecheck.edit',$progrecheck->id)}}">編集</a>
                                </div>
                                <div class="btn-group me-2 mb-0">
                                    <form action="{{ route('progrecheck.destroy', $progrecheck->id)}}" method="POST">
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
                    //-- 01 プルダウンイベントハンドラ
                    //---------------------------------------------------------------
                    $('select[name^="check_01_"]').change( function(e){
                        // alert('check_01Click');
                        var wok_id           = $(this).attr("name").replace('check_01_', '');
                        var this_id          = $(this).attr("id");
                        var check_01         = $("#"+this_id + " option:selected").val();
                        change_progre_info(       this_id          // 対象コントロール
                                                , wok_id           // wokprocbookテーブルのID
                                                , check_01         // 01月
                                                , null             // 02月
                                                , null             // 03月
                                                , null             // 04月
                                                , null             // 05月
                                                , null             // 06月
                                                , null             // 07月
                                                , null             // 08月
                                                , null             // 09月
                                                , null             // 10月
                                                , null             // 11月
                                                , null             // 12月
                                            );
                    });
                    //---------------------------------------------------------------
                    //-- 02 プルダウンイベントハンドラ
                    //---------------------------------------------------------------
                    $('select[name^="check_02_"]').change( function(e){
                        // alert('check_02Click');
                        var wok_id           = $(this).attr("name").replace('check_02_', '');
                        var this_id          = $(this).attr("id");
                        var check_02         = $("#"+this_id + " option:selected").val();
                        change_progre_info(       this_id          // 対象コントロール
                                                , wok_id           // wokprocbookテーブルのID
                                                , null             // 01月
                                                , check_02         // 02月
                                                , null             // 03月
                                                , null             // 04月
                                                , null             // 05月
                                                , null             // 06月
                                                , null             // 07月
                                                , null             // 08月
                                                , null             // 09月
                                                , null             // 10月
                                                , null             // 11月
                                                , null             // 12月
                                            );
                    });
                    //---------------------------------------------------------------
                    //-- 03 プルダウンイベントハンドラ
                    //---------------------------------------------------------------
                    $('select[name^="check_03_"]').change( function(e){
                        // alert('check_03Click');
                        var wok_id           = $(this).attr("name").replace('check_03_', '');
                        var this_id          = $(this).attr("id");
                        var check_03         = $("#"+this_id + " option:selected").val();
                        change_progre_info(       this_id          // 対象コントロール
                                                , wok_id           // wokprocbookテーブルのID
                                                , null             // 01月
                                                , null             // 02月
                                                , check_03         // 03月
                                                , null             // 04月
                                                , null             // 05月
                                                , null             // 06月
                                                , null             // 07月
                                                , null             // 08月
                                                , null             // 09月
                                                , null             // 10月
                                                , null             // 11月
                                                , null             // 12月
                                            );
                    });
                    //---------------------------------------------------------------
                    //-- 04 プルダウンイベントハンドラ
                    //---------------------------------------------------------------
                    $('select[name^="check_04_"]').change( function(e){
                        // alert('check_04Click');
                        var wok_id           = $(this).attr("name").replace('check_04_', '');
                        var this_id          = $(this).attr("id");
                        var check_04         = $("#"+this_id + " option:selected").val();
                        change_progre_info(       this_id          // 対象コントロール
                                                , wok_id           // wokprocbookテーブルのID
                                                , null             // 01月
                                                , null             // 02月
                                                , null             // 03月
                                                , check_04         // 04月
                                                , null             // 05月
                                                , null             // 06月
                                                , null             // 07月
                                                , null             // 08月
                                                , null             // 09月
                                                , null             // 10月
                                                , null             // 11月
                                                , null             // 12月
                                            );
                    });
                    //---------------------------------------------------------------
                    //-- 05 プルダウンイベントハンドラ
                    //---------------------------------------------------------------
                    $('select[name^="check_05_"]').change( function(e){
                        // alert('check_05Click');
                        var wok_id           = $(this).attr("name").replace('check_05_', '');
                        var this_id          = $(this).attr("id");
                        var check_05         = $("#"+this_id + " option:selected").val();
                        change_progre_info(       this_id          // 対象コントロール
                                                , wok_id           // wokprocbookテーブルのID
                                                , null             // 01月
                                                , null             // 02月
                                                , null             // 03月
                                                , null             // 04月
                                                , check_05         // 05月
                                                , null             // 06月
                                                , null             // 07月
                                                , null             // 08月
                                                , null             // 09月
                                                , null             // 10月
                                                , null             // 11月
                                                , null             // 12月
                                            );
                    });
                    //---------------------------------------------------------------
                    //-- 06 プルダウンイベントハンドラ
                    //---------------------------------------------------------------
                    $('select[name^="check_06_"]').change( function(e){
                        // alert('check_06Click');
                        var wok_id           = $(this).attr("name").replace('check_06_', '');
                        var this_id          = $(this).attr("id");
                        var check_06         = $("#"+this_id + " option:selected").val();
                        change_progre_info(       this_id          // 対象コントロール
                                                , wok_id           // wokprocbookテーブルのID
                                                , null             // 01月
                                                , null             // 02月
                                                , null             // 03月
                                                , null             // 04月
                                                , null             // 05月
                                                , check_06         // 06月
                                                , null             // 07月
                                                , null             // 08月
                                                , null             // 09月
                                                , null             // 10月
                                                , null             // 11月
                                                , null             // 12月
                                            );
                    });
                    //---------------------------------------------------------------
                    //-- 07 プルダウンイベントハンドラ
                    //---------------------------------------------------------------
                    $('select[name^="check_07_"]').change( function(e){
                        // alert('check_07Click');
                        var wok_id           = $(this).attr("name").replace('check_07_', '');
                        var this_id          = $(this).attr("id");
                        var check_07         = $("#"+this_id + " option:selected").val();
                        change_progre_info(       this_id          // 対象コントロール
                                                , wok_id           // wokprocbookテーブルのID
                                                , null             // 01月
                                                , null             // 02月
                                                , null             // 03月
                                                , null             // 04月
                                                , null             // 05月
                                                , null             // 06月
                                                , check_07         // 07月
                                                , null             // 08月
                                                , null             // 09月
                                                , null             // 10月
                                                , null             // 11月
                                                , null             // 12月
                                            );
                    });
                    //---------------------------------------------------------------
                    //-- 08 プルダウンイベントハンドラ
                    //---------------------------------------------------------------
                    $('select[name^="check_08_"]').change( function(e){
                        // alert('check_08Click');
                        var wok_id           = $(this).attr("name").replace('check_08_', '');
                        var this_id          = $(this).attr("id");
                        var check_08         = $("#"+this_id + " option:selected").val();
                        change_progre_info(       this_id          // 対象コントロール
                                                , wok_id           // wokprocbookテーブルのID
                                                , null             // 01月
                                                , null             // 02月
                                                , null             // 03月
                                                , null             // 04月
                                                , null             // 05月
                                                , null             // 06月
                                                , null             // 07月
                                                , check_08         // 08月
                                                , null             // 09月
                                                , null             // 10月
                                                , null             // 11月
                                                , null             // 12月
                                            );
                    });
                    //---------------------------------------------------------------
                    //-- 09 プルダウンイベントハンドラ
                    //---------------------------------------------------------------
                    $('select[name^="check_09_"]').change( function(e){
                        // alert('check_09Click');
                        var wok_id           = $(this).attr("name").replace('check_09_', '');
                        var this_id          = $(this).attr("id");
                        var check_09         = $("#"+this_id + " option:selected").val();
                        change_progre_info(       this_id          // 対象コントロール
                                                , wok_id           // wokprocbookテーブルのID
                                                , null             // 01月
                                                , null             // 02月
                                                , null             // 03月
                                                , null             // 04月
                                                , null             // 05月
                                                , null             // 06月
                                                , null             // 07月
                                                , null             // 08月
                                                , check_09         // 09月
                                                , null             // 10月
                                                , null             // 11月
                                                , null             // 12月
                                            );
                    });
                    //---------------------------------------------------------------
                    //-- 10 プルダウンイベントハンドラ
                    //---------------------------------------------------------------
                    $('select[name^="check_10_"]').change( function(e){
                        // alert('check_10Click');
                        var wok_id           = $(this).attr("name").replace('check_10_', '');
                        var this_id          = $(this).attr("id");
                        var check_10         = $("#"+this_id + " option:selected").val();
                        change_progre_info(       this_id          // 対象コントロール
                                                , wok_id           // wokprocbookテーブルのID
                                                , null            // 01月
                                                , null             // 02月
                                                , null             // 03月
                                                , null             // 04月
                                                , null             // 05月
                                                , null             // 06月
                                                , null             // 07月
                                                , null             // 08月
                                                , null             // 09月
                                                , check_10         // 10月
                                                , null             // 11月
                                                , null             // 12月
                                            );
                    });
                    //---------------------------------------------------------------
                    //-- 11 プルダウンイベントハンドラ
                    //---------------------------------------------------------------
                    $('select[name^="check_11_"]').change( function(e){
                        // alert('check_11Click');
                        var wok_id           = $(this).attr("name").replace('check_11_', '');
                        var this_id          = $(this).attr("id");
                        var check_11         = $("#"+this_id + " option:selected").val();
                        change_progre_info(       this_id          // 対象コントロール
                                                , wok_id           // wokprocbookテーブルのID
                                                , null             // 01月
                                                , null             // 02月
                                                , null             // 03月
                                                , null             // 04月
                                                , null             // 05月
                                                , null             // 06月
                                                , null             // 07月
                                                , null             // 08月
                                                , null             // 09月
                                                , null             // 10月
                                                , check_11         // 11月
                                                , null             // 12月
                                            );
                    });
                    //---------------------------------------------------------------
                    //-- 12 プルダウンイベントハンドラ
                    //---------------------------------------------------------------
                    $('select[name^="check_12_"]').change( function(e){
                        // alert('check_12Click');
                        var wok_id           = $(this).attr("name").replace('check_12_', '');
                        var this_id          = $(this).attr("id");
                        var check_12         = $("#"+this_id + " option:selected").val();
                        change_progre_info(       this_id          // 対象コントロール
                                                , wok_id           // wokprocbookテーブルのID
                                                , null             // 01月
                                                , null             // 02月
                                                , null             // 03月
                                                , null             // 04月
                                                , null             // 05月
                                                , null             // 06月
                                                , null             // 07月
                                                , null             // 08月
                                                , null             // 09月
                                                , null             // 10月
                                                , null             // 11月
                                                , check_12         // 12月
                                            );
                    });

                    /**
                    * this_id         : 対象コントロール
                    * wok_id          : progrecheckテーブルのID
                    * check_01-12     : 01月 - 12月
                    *
                    */
                    function change_progre_info(     this_id
                                                    , wok_id
                                                    , check_01
                                                    , check_02
                                                    , check_03
                                                    , check_04
                                                    , check_05
                                                    , check_06
                                                    , check_07
                                                    , check_08
                                                    , check_09
                                                    , check_10
                                                    , check_11
                                                    , check_12
                                                            ){
                            var reqData = new FormData();
                                                    reqData.append( "id"       , wok_id   );
                            if( null != check_01 ) reqData.append( "check_01" , check_01 );
                            if( null != check_02 ) reqData.append( "check_02" , check_02 );
                            if( null != check_03 ) reqData.append( "check_03" , check_03 );
                            if( null != check_04 ) reqData.append( "check_04" , check_04 );
                            if( null != check_05 ) reqData.append( "check_05" , check_05 );
                            if( null != check_06 ) reqData.append( "check_06" , check_06 );
                            if( null != check_07 ) reqData.append( "check_07" , check_07 );
                            if( null != check_08 ) reqData.append( "check_08" , check_08 );
                            if( null != check_09 ) reqData.append( "check_09" , check_09 );
                            if( null != check_10 ) reqData.append( "check_10" , check_10 );
                            if( null != check_11 ) reqData.append( "check_11" , check_11 );
                            if( null != check_12 ) reqData.append( "check_12" , check_12 );

                            // console.log(wok_id);
                            // console.log(check_09);

                            // Ajax通信呼出(データファイルのアップロード)
                            AjaxAPI.callAjax(
                                "{{ route('progrecheck.update_api') }}",
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
        {{ $progrechecks->appends(request()->query())->render() }}
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
