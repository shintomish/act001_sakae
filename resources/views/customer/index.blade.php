{{-- @extends('layouts.app') --}}
@extends('layouts.common_index')

@section('content')
    {{-- <h2>顧客一覧</h2> --}}
    {{-- <div class="text-right">
        <a class="btn btn-success btn-sm mr-auto" href="{{route('customer.create')}}">新規登録</a>
    </div> --}}

    {{-- @if(Session::has('message'))
    メッセージ：{{ session('message') }}
    @endif --}}

    @if (session('message'))
        @if (session('message') == 'CSV登録が完了しました。')
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @else
            <div class="alert alert-danger">
                {{ session('message') }}
            </div>
        @endif
    @endif

    {{-- @if ($errors)
        <div class="alert alert-danger">
        <ul>
            @foreach ($errors as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        </div>
    @endif --}}
    @if (is_array($errors))
        <div class="flushComment">
            ・CSVインポートエラーが発生しました。以下の内容を確認してください。<br>
            @if (count($errors['registration_errors']) > 0)
                [対象のデータ：新規登録]
                <ul>
                @foreach ($errors['registration_errors'] as $line => $columns)
                    @foreach ($columns as $error)
                    <li>{{ $line }}行目：{{ $error }}</li>
                    @endforeach
                @endforeach
                </ul>
            @endif
            @if (count($errors['update_errors']) > 0)
                [対象のデータ：編集登録]<br>
                <ul>
                @foreach ($errors['update_errors'] as $line => $columns)
                    @foreach ($columns as $error)
                    <li>{{ $line }}行目：{{ $error }}</li>
                    @endforeach
                @endforeach
                </ul>
            @endif
        </div>
    @endif

    <!-- 検索エリア -->
    <form  class="form-inline" enctype="multipart/form-data" id="csvUpload" action="{{route('csvimprt.store')}}" method="POST">
        @csrf
        {{-- @method('get') --}}
        <div style="display:inline-flex">
            <div class="row">
                {{-- <div class="col-sm-2"> --}}
                    {{-- <a class="btn btn-success btn-sm btn-block" href="{{route('wokprocbook.create')}}">新規登録</a> --}}
                {{-- </div> --}}


                <div class="col-sm-30">
                    <a class="btn btn-success btn-sm btn-block mb-2" href="{{route('customer.create')}}">新規登録</a>

                    <div class="custom-file">
                        <input type="file" name="csv_file" class="custom-file-input" id="csv_file">
                        <label class="custom-file-label" for="csv_file" data-browse="参照"></label>
                <button type="submit" class="btn btn-secondary btn-sm btn-block">CSV登録</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

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
        {{-- <table class="table table-striped table-borderd table_sticky"> --}}
        {{-- table-responsive text-nowrap add scope=row 2022/11/09--}}
        <table class="table table-responsive text-nowrap table-striped table-borderd table_sticky">
            <thead>
                <tr>
                    <th class ="fixed01" scope="row">顧客ID</th>
                    <th scope="row" class ="fixed01">@sortablelink('business_code',      '事業者コード')</th>
                    <th scope="row" class ="fixed01">@sortablelink('business_name',      '顧客名')</th>
                    <th scope="row" class ="fixed01">@sortablelink('individual_class',   '法人/個人')</th>
                    <th scope="row" class ="fixed01">@sortablelink('closing_month',      '決算月')</th>
                    <th scope="row" class ="fixed01">@sortablelink('represent_name',     '代表者名')</th>
                    {{-- <th scope="col" class ="fixed01">@sortablelink('active_cancel',      '契約/解除')</th> --}}
                    <th scope="row" class ="fixed01">契約/解除</th>
                    <th scope="row" class ="fixed01">@sortablelink('notificationl_flg',  'News通知')</th>
                    <th scope="row" class ="fixed01">操作</th>
                </tr>
            </thead>

            <tbody>
                @if($customers->count())
                    @foreach($customers as $customer)
                    <tr>
                        @php
                            $str  = sprintf("%04d", $customer->id);
                        @endphp
                        {{-- <td class="text-end">{{ number_format($customer->id) }}</td> --}}
                        <td class="">{{ $str }}</td>
                        <td>{{ $customer->business_code }}</td>
                        <td>{{ $customer->business_name }}</td>

                        {{-- //法人・個人 App/Providers/AppServiceProviderのboot--}}
                        @foreach ($loop_individual_class as $loop_individual_class2)
                            @if ($loop_individual_class2['no']==$customer->individual_class)
                                <td>{{ $loop_individual_class2['name'] }}</td>
                            @endif
                        @endforeach

                        {{-- //決算月 --}}
                        @foreach ($loop_closing_month as $loop_closing_month2)
                            @if ($loop_closing_month2['no']==$customer->closing_month)
                                <td>{{ $loop_closing_month2['name'] }}</td>
                            @endif
                        @endforeach

                        {{-- //代表者名 --}}
                        <td>{{ $customer->represent_name }}</td>

                        {{-- //契約/解除 --}}
                        @foreach ($loop_active_cancel as $loop_active_cancel2)
                            @if ($loop_active_cancel2['no']==$customer->active_cancel)
                                <td>{{ $loop_active_cancel2['name'] }}</td>
                            @endif
                        @endforeach

                        {{-- //通知しない(1):通知する(2) --}}
                        @foreach ($loop_notificationl_flg as $loop_notificationl_flg2)
                            @if ($loop_notificationl_flg2['no']==$customer->notificationl_flg)
                                <td>{{ $loop_notificationl_flg2['name'] }}</td>
                            @endif
                        @endforeach

                        <td>
                            <div class="btn-toolbar">
                                <div class="btn-group me-2 mb-0">
                                    <a class="btn btn-primary btn-sm" href="{{ route('customer.edit',$customer->id)}}">編集</a>
                                </div>
                                <div class="btn-group me-2 mb-0">
                                    <form action="{{ route('customer.destroy', $customer->id)}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            {{-- <input class="btn btn-danger btn-sm" type="submit" value="削除" id="btn_del"
                                            onclick='return confirm("削除しますか？");'> --}}
                                            <input class="btn btn-danger btn-sm" type="hidden" value="削除" id="btn_del"
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
                        {{-- <td><p> </p></td> hidden --}}
                    </tr>
                @endif

            </tbody>
        </table>
    {{-- </div> --}}

     {{-- ページネーション / pagination）の表示 --}}
     <ul class="pagination justify-content-center">
        {{ $customers->appends(request()->query())->render() }}
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
