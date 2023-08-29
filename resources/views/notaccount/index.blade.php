{{-- @extends('layouts.app') --}}
@extends('layouts.common_index')

@section('content')
    {{-- <h2>会計未処理一覧</h2> --}}
    <div class="text-right">
        {{-- <a class="btn btn-success btn-sm mr-auto" href="{{route('notaccount.create')}}">新規登録</a> --}}
        <h5 class="text-secondary">会計未処理 ( {{ $count3 }} 社)</h5>
    </div>
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
                    <th scope="row" class ="fixed01">ID</th>
                    <th scope="row" class ="fixed01">@sortablelink('business_code',    '事業者コード')</th>
                    <th scope="row" class ="fixed01">@sortablelink('business_name',    '顧客名')</th>
                    <th scope="row" class ="fixed01">@sortablelink('individual_class', '法人/個人')</th>
                    <th scope="row" class ="fixed01">@sortablelink('closing_month',    '決算月')</th>
                    <th scope="row" class ="fixed01">@sortablelink('final_accounting_at',    '会計処理日')</th>
                    {{-- <th scope="col">操作</th> --}}
                </tr>
            </thead>

            <tbody>
                @if($customers->count())
                    @foreach($customers as $customer)
                    <tr>
                        {{-- <td class="text-end">{{ number_format($customer->id) }}</td> --}}
                        <th>{{ $customer->id }}</th>
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

                        @php
                            $str = "";
                            if (isset($customer->final_accounting_at)) {
                                $str = ( new DateTime($customer->final_accounting_at))->format('Y-m-d');
                            }
                        @endphp
                        <td>{{ $str }}</td>

                        {{-- <td>
                            <div class="btn-toolbar">
                                <div class="btn-group me-2 mb-0">
                                    <a class="btn btn-primary btn-sm" href="{{ route('notaccount.edit',$customer->id)}}">編集</a>
                                </div>
                                <div class="btn-group me-2 mb-0">
                                    <form action="{{ route('customer.destroy', $customer->id)}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <input class="btn btn-danger btn-sm" type="submit" value="削除" id="btn_del"
                                            onclick='return confirm("削除しますか？");'>
                                    </form>
                                </div>
                            </div>
                        </td> --}}
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
