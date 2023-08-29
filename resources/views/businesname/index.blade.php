{{-- @extends('layouts.app') --}}
@extends('layouts.common_index')

@section('content')
    {{-- <h2>業務名一覧</h2> --}}
    <div class="text-right">
        <a class="btn btn-success btn-sm mr-auto" href="{{route('businesname.create')}}">新規登録</a>
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
                    <th scope="row" class ="fixed01">ID</th>
                    <th scope="row" class ="fixed01">@sortablelink('business_name', '顧客名')</th>
                    <th scope="row" class ="fixed02" >01</th>
                    <th scope="row" class ="fixed02" >02</th>
                    <th scope="row" class ="fixed02" >03</th>
                    <th scope="row" class ="fixed02" >04</th>
                    <th scope="row" class ="fixed02" >05</th>
                    <th scope="row" class ="fixed02" >06</th>
                    <th scope="row" class ="fixed02" >07</th>
                    <th scope="row" class ="fixed02" >08</th>
                    <th scope="row" class ="fixed02" >09</th>
                    <th scope="row" class ="fixed02" >10</th>
                    <th scope="row" class ="fixed02" >操作</th>
                </tr>
            </thead>

            <tbody>
                @if($businesnames->count())
                    @foreach($businesnames as $businesname)
                    <tr>
                        {{-- ID --}}
                        <th class ="fixed02">{{ $businesname->id }}</th>

                        {{-- 社名/氏名 --}}
                        @foreach ($customers as $customers2)
                            @if ($customers2->id==$businesname->custm_id)
                                <th  class ="fixed02">{{ $customers2->business_name }}</th>
                            @endif
                        @endforeach

                        {{-- 業務名01 --}}
                        <td>{{ $businesname->businm_01 }}</td>

                        {{-- 業務名02 --}}
                        <td>{{ $businesname->businm_02 }}</td>

                        {{-- 業務名03 --}}
                        <td>{{ $businesname->businm_03 }}</td>

                        {{-- 業務名04 --}}
                        <td>{{ $businesname->businm_04 }}</td>

                        {{-- 業務名05 --}}
                        <td>{{ $businesname->businm_05 }}</td>

                        {{-- 業務名06 --}}
                        <td>{{ $businesname->businm_06 }}</td>

                        {{-- 業務名07 --}}
                        <td>{{ $businesname->businm_07 }}</td>

                        {{-- 業務名08 --}}
                        <td>{{ $businesname->businm_08 }}</td>

                        {{-- 業務名09 --}}
                        <td>{{ $businesname->businm_09 }}</td>

                        {{-- 業務名10 --}}
                        <td>{{ $businesname->businm_10 }}</td>

                       <td>
                            <div class="btn-toolbar">
                                <div class="btn-group me-2 mb-0">
                                <a class="btn btn-primary btn-sm" href="{{ route('businesname.edit',$businesname->id)}}">編集</a>
                                </div>
                                <div class="btn-group me-2 mb-0">
                                    <form action="{{ route('businesname.destroy', $businesname->id)}}" method="POST">
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
                    </tr>
                @endif

            </tbody>
        </table>

    {{-- </div> --}}

     {{-- ページネーション / pagination）の表示 --}}
     <ul class="pagination justify-content-center">
        {{ $businesnames->appends(request()->query())->render() }}
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
