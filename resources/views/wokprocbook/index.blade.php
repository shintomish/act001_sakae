{{-- @extends('layouts.app') --}}
@extends('layouts.api_index')

@section('content')
    {{-- <h2>税理士業務処理簿一覧</h2> --}}

    <!-- 検索エリア -->
    {{-- <form  class="form-inline" action="" method="GET">
        @csrf
        @method('get')
        <div style="display:inline-flex">
            <div class="row">
                <div class="col-sm-2">
                    <a class="btn btn-success btn-sm mr-auto" href="{{route('wokprocbook.create')}}">新規登録</a>
                </div>

                <div class="col-sm-4">
                    <input type="date" class="form-control" id="frdate" name="frdate" value="{{$frdate}}">
                </div>

                <div class="col-sm-4">
                    <input type="date" class="form-control" id="todate"  name="todate" value="{{$todate}}">
                </div>

                <div class="col-sm-2">
                    <a class="btn btn-secondary btn-sm mr-auto" href="{{route('wokprocbookexport')}}">CSV出力</a>
                </div>

            </div>
        </div>
    </form> --}}
        <!-- 検索エリア -->
        <form  class="my-2 my-lg-0 ml-2" action="{{route('wokprocbookexport')}}" method="GET">
            @csrf
            @method('get')
            <table>
                <div style="display:inline-flex">
                    <div class="row">
                        <div class="col-sm-2">
                            <a class="btn btn-success btn-sm mr-auto" href="{{route('wokprocbook.create')}}">新規登録</a>
                        </div>

                        <div class="col-sm-4">
                            <input type="date" class="form-control" id="frdate" name="frdate" value="{{$frdate}}">
                        </div>

                        <div class="col-sm-4">
                            <input type="date" class="form-control" id="todate"  name="todate" value="{{$todate}}">
                        </div>

                        <div class="col-sm-2">
                            <button type="submit" class="btn btn-secondary btn-sm mr-auto">CSV出力</button>
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
        <!-- 検索エリア -->
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
    </style>

    {{-- <div class="table-responsive"> --}}

        {{-- <table class="table table-striped table-borderd"> --}}
        <table class="table table-striped table-borderd table_sticky">
            <thead>
                <tr>
                    {{-- <th class="text-end"scope="col">ID</th> --}}
                    <th scope="col" class ="fixed01 bg-info text-right">@sortablelink('refnumber',      '整理番号')</th>
                    <th scope="col" class ="fixed02 bg-info text-right">@sortablelink('business_name',  '顧客名')</th>
                    <th scope="col" class ="fixed02 bg-info text-right">@sortablelink('busi_class',     '業務区分')</th>
                    <th scope="col" class ="fixed02 bg-info text-right">@sortablelink('contents_class', '内容')</th>
                    <th scope="col" class ="fixed02 bg-info text-right">@sortablelink('facts_class',    '顛末')</th>
                    <th scope="col" class ="fixed02 bg-info text-right">@sortablelink('proc_date',      '処理日')</th>
                    <th scope="col" class ="fixed02 bg-info text-right">@sortablelink('attach_doc',     '添付')</th>
                    {{-- 2022/08/25 --}}
                    {{-- <th scope="col" class ="fixed02 bg-info text-right">@sortablelink('filing_date',         '提出日')</th> --}}
                    <th scope="col" class ="fixed02 bg-info text-right">@sortablelink('filing_date',    '税務代理権限書提出日')</th>
                    <th scope="col" class ="fixed02 bg-info text-right">@sortablelink('name',           '担当')</th>
                    <th scope="col" class ="fixed02 bg-secondary  text-right">操作</th>
                </tr>
            </thead>

            <tbody>
                @if($wokprocbooks->count())
                    @foreach($wokprocbooks as $wokprocbook)
                    <tr>
                        {{-- <td class="text-end">{{ number_format($wokprocbook->id) }}</td> --}}
                        {{-- 整理番号 --}}
                        <td>{{ $wokprocbook->refnumber }}</td>

                        {{-- 社名/氏名 --}}
                        @foreach ($customers as $customers2)
                            @if ($customers2->id==$wokprocbook->custm_id)
                                <td>{{ $customers2->business_name }}</td>
                            @endif
                        @endforeach

                        {{-- /'業務区分 1:代理 2:相談'--}}
                        @foreach ($loop_busi_class as $loop_busi_class2)
                            @if ($loop_busi_class2['no']==$wokprocbook->busi_class)
                                <td>{{ $loop_busi_class2['name'] }}</td>
                            @endif
                        @endforeach

                        {{-- 住所 --}}
                        {{-- @foreach ($customers as $customers2)
                            @if ($customers2->id==$wokprocbook->custm_id)
                                <td>{{ $customers2->business_address }}</td>
                            @endif
                        @endforeach --}}

                        {{-- //内容（税目等）1～ --}}
                        @foreach ($loop_contents_class as $loop_contents_class2)
                            @if ($loop_contents_class2['no']==$wokprocbook->contents_class)
                                <td>{{ $loop_contents_class2['name'] }}</td>
                            @endif
                        @endforeach

                        {{-- //顛末 --}}
                        @foreach ($loop_facts_class as $loop_facts_class2)
                            @if ($loop_facts_class2['no']==$wokprocbook->facts_class)
                                <td>{{ $loop_facts_class2['name'] }}</td>
                            @endif
                        @endforeach

                        {{-- //処理年月日 --}}
                        @php
                            $str = "";
                            if (isset($wokprocbook->proc_date)) {
                                $str = ( new DateTime($wokprocbook->proc_date))->format('Y-m-d');
                            }
                        @endphp
                        <td>{{ $str }}</td>
                        {{-- <td><input type="date" class="form-control" name="proc_date" value="{{$str}}"></td> --}}

                        {{-- //添付書面 1:無 2:有 --}}
                        @foreach ($loop_attach_doc as $loop_attach_doc2)
                            @if ($loop_attach_doc2['no']==$wokprocbook->attach_doc)
                                <td>{{ $loop_attach_doc2['name'] }}</td>
                            @endif
                        @endforeach

                        {{-- <select class="custom-select d-block w-100" id="attach_doc" name="attach_doc">
                            @foreach ($loop_attach_doc as $loop_attach_doc2)
                                @if ($loop_attach_doc2['no']==$wokprocbook->attach_doc)
                            <option selected="selected" value={{$wokprocbook->attach_doc}}>{{ $loop_attach_doc2['name'] }}</option>
                                @else
                                    @if ($loop_attach_doc2['no']==0)
                                    <option disabled value="{{ $loop_attach_doc2['no'] }}">{{ $loop_attach_doc2['name'] }}</option>
                                    @else
                                    <option value="{{ $loop_attach_doc2['no'] }}">{{ $loop_attach_doc2['name'] }}</option>
                                    @endif
                                @endif
                            @endforeach
                        </select> --}}


                        {{-- 提出日 -> 税務代理権限書提出日 2022/08/25--}}
                        @php
                            $str = "";
                            if (isset($wokprocbook->filing_date)) {
                                $str = ( new DateTime($wokprocbook->filing_date))->format('Y-m-d');
                            }
                        @endphp
                        <td>{{ $str }}</td>

                        {{-- 所属 --}}
                        {{-- @foreach ($users as $user2)
                            @if ($user2->id==$wokprocbook->staff_no)
                                @foreach ($loop_login_flg as $loop_login_flg2)
                                    @if ($loop_login_flg2['no']==$user2->login_flg)
                                        <td>{{ $loop_login_flg2['name'] }}</td>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach --}}

                        {{-- 担当税理士 --}}
                        @foreach ($users as $user2)
                            @if ($user2->id==$wokprocbook->staff_no)
                                <td>{{ $user2->name }}</td>
                            @endif
                        @endforeach

                        {{-- 備考 --}}
                        {{-- <td>{{ $wokprocbook->remarks }}</td> --}}

                        <td>
                            <div class="btn-toolbar">

                                <div class="btn-group me-2 mb-0">
                                    {{-- <a class="btn btn-primary btn-sm" href="{{ route('wokprocbook.edit',$wokprocbook->id)}}">編集</a> --}}

                    <button type="button" class="btn_edit btn btn-block btn-primary btn-sm" wokid="{{ $wokprocbook->id }}">編集</button>

                                </div>

                                <div class="btn-group me-2 mb-0">
                                    <form action="{{ route('wokprocbook.destroy', $wokprocbook->id)}}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input type="submit" class="btn_del btn btn-block btn-danger btn-sm" value="削除">
                                            {{-- onclick='return confirm("削除しますか？");'> --}}
                                    </form>
                                </div>

                            </div>

                        </td>
                    </tr>
                    @endforeach

                    <script>

                        //---------------------------------------------------------------
                        //-- 削除ボタン押下イベントハンドラ
                        //---------------------------------------------------------------
                        $('.btn_del').on('click', function(event) {
                            if( !confirm('削除しますか？') ){
                                /* キャンセルの時の処理 */
                                return false;
                            }
                            else{
                                /* OKの時の処理 */
                                return true;
                            }
                        });

                        //---------------------------------------------------------------
                        //-- 編集ボタン押下イベントハンドラ
                        //---------------------------------------------------------------
                        $('.btn_edit').click( function(e){

                            var id = $(this).attr('wokid');

                            // index情報を元にinput画面に遷移する
                            // 画面遷移をサーバに要求
                            var action = "{{ route('wokprocbook.input') }}";
                            var method = 'GET';

                            var requestData = {};
                            requestData.id  = $(this).attr('wokid');

                            var requestText = JSON.stringify(requestData);
                            // console.log(requestText);
                            // DynamicSubmit.submit(action, method, requestText);

                            $.ajax({
                                type: "GET",
                                url: "{{ route('wokprocbook.input') }}",
                                requestData: JSON.stringify(requestData),
                                contentType: "application/json",

                            }).done(function (requestData) { //ajaxが成功したときの処理;
                                $('.loading').addClass('display-none'); //通信中のぐるぐるを消す
                                console.log(requestText);
                                DynamicSubmit.submit(action, method, requestText);
                            }).fail(function () {       //ajax通信がエラーのときの処理
                                alert('er編集ボタンClick')
                                console.log('どんまい！');

                            });
                            // alert('er編集ボタンClick');
                            // DynamicSubmit.submit(action, method, requestText);


                        });

                    </script>
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
                    </tr>
                @endif

            </tbody>
        </table>
    {{-- </div> --}}

     {{-- ページネーション / pagination）の表示 --}}
     <ul class="pagination justify-content-center">
        {{ $wokprocbooks->appends(request()->query())->render() }}
     </ul>

@endsection

@section('part_javascript')
ChangeSideBar("nav-item-system-user");

    <script type="text/javascript">
    </script>
@endsection
