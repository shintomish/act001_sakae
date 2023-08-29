{{-- @extends('layouts.app') --}}
{{-- @extends('layouts.common_index') --}}
@extends('layouts.api_index')


@section('content')
    {{-- <h2>スケジュール一覧</h2> --}}
    <div class="text-right">
        {{-- <a class="btn btn-success btn-sm mr-auto" href="{{route('schedule.create')}}">新規登録</a> --}}
    </div>

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
        {{-- <table class="table table-striped table-borderd table_sticky"> --}}
        {{-- table-responsive text-nowrap add scope=row 2022/11/09--}}
        <table class="table table-responsive text-nowrap table-striped table-borderd table_sticky">
            <thead>
                <tr>
                    <th scope="row"  class ="fixed01" >ID</th>
                    <th scope="row"  class ="fixed01" >@sortablelink('business_name', '顧客名')</th>
                    <th scope="row"  class ="fixed02" >業務名</th>
                    <th class ="fixed02" scope="row" >01月</th>
                    <th class ="fixed02" scope="row" >02月</th>
                    <th class ="fixed02" scope="row" >03月</th>
                    <th class ="fixed02" scope="row" >04月</th>
                    <th class ="fixed02" scope="row" >05月</th>
                    <th class ="fixed02" scope="row" >06月</th>
                    <th class ="fixed02" scope="row" >07月</th>
                    <th class ="fixed02" scope="row" >08月</th>
                    <th class ="fixed02" scope="row" >09月</th>
                    <th class ="fixed02" scope="row" >10月</th>
                    <th class ="fixed02" scope="row" >11月</th>
                    <th class ="fixed02" scope="row" >12月</th>
                    {{-- <th scope="col" >操作</th> --}}
                </tr>
            </thead>

            <tbody>
                @if($progrechecks->count())
                    @foreach($progrechecks as $progrecheck)
                    <tr>
                        {{-- ID --}}
                        <th  class ="fixed02">{{ $progrecheck->id }}</th>

                        {{-- 社名/氏名 --}}
                        @foreach ($customers as $customers2)
                            @if ($customers2->id==$progrecheck->custm_id)
                                <th  class ="fixed02">{{ $customers2->business_name }}</th>
                            @endif
                        @endforeach

                        {{-- 業務名 --}}
                        <td>{{ $progrecheck->businm_no }}</td>
{{-- debug --}}
{{-- @php
    $nowmonth = 11;
@endphp --}}
                        {{-- 01 --}}
                        @switch ($progrecheck->check_01)
                            @case (1)   {{-- × --}}
                                @if($nowmonth >= 1)
                                    {{-- <td class ="text-danger text-center">〇</td> --}}
                                    {{-- <td class ="text-danger text-center">●</td> --}}
                                    <td class ="text-danger text-center">×</td>
                                @endif
                                @break;
                            @case (2)   {{-- △ --}}
                                @if($nowmonth >= 1)
                                    {{-- <td class ="text-danger text-center">〇</td> --}}
                                    {{-- <td class ="text-danger text-center">●</td> --}}
                                    <td class ="text-danger text-center">△</td>
                                @endif
                                @break;
                            @case (3)   {{-- 〇 --}}
                                {{-- <td class ="text-center" >●</td> --}}
                                <td class ="text-center" >〇</td>
                                @break;
                            @default:
                                @break;
                        @endswitch

                        {{-- 02 --}}
                        @switch ($progrecheck->check_02)
                            @case (1)   {{-- × --}}
                                @if($nowmonth >= 2 )
                                    <td class ="text-danger text-center">×</td>
                                @else
                                    <td class ="text-primary text-center">×</td>
                                @endif
                                @break;
                            @case (2)   {{-- △ --}}
                                @if($nowmonth >= 2 )
                                    <td class ="text-danger text-center">△</td>
                                @else
                                    <td class ="text-primary text-center">△</td>
                                @endif
                                @break;
                            @case (3)   {{-- 〇 --}}
                                <td class ="text-center" >〇</td>
                                @break;
                            @default:
                                @break;
                        @endswitch

                        {{-- 03 --}}
                        @switch ($progrecheck->check_03)
                            @case (1)   {{-- × --}}
                                @if($nowmonth >= 3)
                                    <td class ="text-danger text-center">×</td>
                                @else
                                    <td class ="text-primary text-center">×</td>
                                @endif
                                @break;
                            @case (2)   {{-- △ --}}
                                @if($nowmonth >= 3)
                                    <td class ="text-danger text-center">△</td>
                                @else
                                    <td class ="text-primary text-center">△</td>
                                @endif
                                @break;
                            @case (3)   {{-- 〇 --}}
                                <td class ="text-center" >〇</td>
                                @break;
                            @default:
                                @break;
                        @endswitch

                        {{-- 04 --}}
                        @switch ($progrecheck->check_04)
                            @case (1)   {{-- × --}}
                                @if($nowmonth >= 4 )
                                    <td class ="text-danger text-center">×</td>
                                @else
                                    <td class ="text-primary text-center">×</td>
                                @endif
                                @break;
                            @case (2)   {{-- △ --}}
                                @if($nowmonth >= 4 )
                                    <td class ="text-danger text-center">△</td>
                                @else
                                    <td class ="text-primary text-center">△</td>
                                @endif
                                @break;
                            @case (3)   {{-- 〇 --}}
                                <td class ="text-center" >〇</td>
                                @break;
                            @default:
                                @break;
                        @endswitch

                        {{-- 05 --}}
                        @switch ($progrecheck->check_05)
                            @case (1)   {{-- × --}}
                                @if($nowmonth >= 5)
                                    <td class ="text-danger text-center">×</td>
                                @else
                                    <td class ="text-primary text-center">×</td>
                                @endif
                                @break;
                            @case (2)   {{-- △ --}}
                                @if($nowmonth >= 5 )
                                    <td class ="text-danger text-center">△</td>
                                @else
                                    <td class ="text-primary text-center">△</td>
                                @endif
                                @break;
                            @case (3)   {{-- 〇 --}}
                                <td class ="text-center" >〇</td>
                                @break;
                            @default:
                                @break;
                        @endswitch

                        {{-- 06 --}}
                        @switch ($progrecheck->check_06)
                            @case (1)   {{-- × --}}
                                @if($nowmonth >= 6 )
                                    <td class ="text-danger text-center">×</td>
                                @else
                                    <td class ="text-primary text-center">×</td>
                                @endif
                                @break;
                            @case (2)   {{-- △ --}}
                                @if($nowmonth >= 6 )
                                    <td class ="text-danger text-center">△</td>
                                @else
                                    <td class ="text-primary text-center">△</td>
                                @endif
                                @break;
                            @case (3)   {{-- 〇 --}}
                                <td class ="text-center" >〇</td>
                                @break;
                            @default:
                                @break;
                        @endswitch

                        {{-- 07 --}}
                        @switch ($progrecheck->check_07)
                            @case (1)   {{-- × --}}
                                @if($nowmonth >= 7 )
                                    <td class ="text-danger text-center">×</td>
                                @else
                                    <td class ="text-primary text-center">×</td>
                                @endif
                                @break;
                            @case (2)   {{-- △ --}}
                                @if($nowmonth >= 7 )
                                    <td class ="text-danger text-center">△</td>
                                @else
                                    <td class ="text-primary text-center">△</td>
                                @endif
                                @break;
                            @case (3)   {{-- 〇 --}}
                                <td class ="text-center" >〇</td>
                                @break;
                            @default:
                                @break;
                        @endswitch

                        {{-- 08 --}}
                        @switch ($progrecheck->check_08)
                            @case (1)   {{-- × --}}
                                @if($nowmonth >= 8 )
                                    <td class ="text-danger text-center">×</td>
                                @else
                                    <td class ="text-primary text-center">×</td>
                                @endif
                                @break;
                            @case (2)   {{-- △ --}}
                                @if($nowmonth >= 8 )
                                    <td class ="text-danger text-center">△</td>
                                @else
                                    <td class ="text-primary text-center">△</td>
                                @endif
                                @break;
                            @case (3)   {{-- 〇 --}}
                                <td class ="text-center" >〇</td>
                                @break;
                            @default:
                                @break;
                        @endswitch

                        {{-- 09 --}}
                        @switch ($progrecheck->check_09)
                            @case (1)   {{-- × --}}
                                @if($nowmonth >= 9)
                                    <td class ="text-danger text-center">×</td>
                                @else
                                    <td class ="text-primary text-center">×</td>
                                @endif
                                @break;
                            @case (2)   {{-- △ --}}
                                @if($nowmonth >= 9)
                                    <td class ="text-danger text-center">△</td>
                                @else
                                    <td class ="text-primary text-center">△</td>
                                @endif
                                @break;
                            @case (3)   {{-- 〇 --}}
                                <td class ="text-center" >〇</td>
                                @break;
                            @default:
                                @break;
                        @endswitch

                        {{-- 10 --}}
                        @switch ($progrecheck->check_10)
                            @case (1)   {{-- × --}}
                                @if($nowmonth >= 10)
                                    <td class ="text-danger text-center">×</td>
                                @else
                                    <td class ="text-primary text-center">×</td>
                                @endif
                                @break;
                            @case (2)   {{-- △ --}}
                                @if($nowmonth >= 10)
                                    <td class ="text-danger text-center">△</td>
                                @else
                                    <td class ="text-primary text-center">△</td>
                                @endif
                                @break;
                            @case (3)   {{-- 〇 --}}
                                <td class ="text-center" >〇</td>
                                @break;
                            @default:
                                @break;
                        @endswitch

                        {{-- 11 --}}
                        @switch ($progrecheck->check_11)
                            @case (1)   {{-- × --}}
                                @if($nowmonth >= 11 )
                                    <td class ="text-danger text-center">×</td>
                                @else
                                    <td class ="text-primary text-center">×</td>
                                @endif
                                @break;
                            @case (2)   {{-- △ --}}
                                @if($nowmonth >= 11 )
                                    <td class ="text-danger text-center">△</td>
                                @else
                                    <td class ="text-primary text-center">△</td>
                                @endif
                                @break;
                            @case (3)   {{-- 〇 --}}
                                <td class ="text-center" >〇</td>
                                @break;
                            @default:
                                @break;
                        @endswitch

                        {{-- 12 --}}
                        @switch ($progrecheck->check_12)
                            @case (1)   {{-- × --}}
                                @if($nowmonth >= 12)
                                    <td class ="text-danger text-center">×</td>
                                @else
                                    <td class ="text-primary text-center">×</td>
                                @endif
                                @break;
                            @case (2)   {{-- △ --}}
                                @if($nowmonth >= 12)
                                    <td class ="text-danger text-center">△</td>
                                @else
                                    <td class ="text-primary text-center">△</td>
                                @endif
                                @break;
                            @case (3)   {{-- 〇 --}}
                                <td class ="text-center" >〇</td>
                                @break;
                            @default:
                                @break;
                        @endswitch

                       {{-- <td>
                            <div class="btn-toolbar">
                                <div class="btn-group me-2 mb-0">
                                <a class="btn btn-primary btn-sm" href="{{ route('schedule.edit',$schedule->id)}}">編集</a>
                                </div>
                                <div class="btn-group me-2 mb-0">
                                    <form action="{{ route('schedule.destroy', $schedule->id)}}" method="POST">
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

            </tbody>
        </table>

    </div>

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
