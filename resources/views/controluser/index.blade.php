{{-- @extends('layouts.app') --}}
@extends('layouts.common_index')

@section('content')
    {{-- <h2>利用者一覧</h2> --}}
    <div class="text-right">
        <a class="btn btn-success btn-sm mr-auto" href="{{route('ctluser.create')}}">新規登録</a>
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
            width: 250px;   /* 200->250 */
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
                    <th scope="row" class ="fixed01">所属組織</th>
                    <th scope="row" class ="fixed01">@sortablelink('users_name', 'ユーザー名')</th>
                    <th scope="row" class ="fixed01">@sortablelink('business_name', '顧客名')</th>
                    <th scope="row" class ="fixed01">操作</th>
                </tr>
            </thead>

            <tbody>
                @if($controlusers->count())
                    @foreach($controlusers as $controluser)
                    <tr>
                        <td>{{ $controluser->id }}</td>
                        <td>
                            @foreach ($organizations as $organizations2)
                                @if ($organizations2->id==$controluser->organization_id)
                                    {{$organizations2->name}}
                                @endif
                            @endforeach
                        </td>
                        <td>
                            @foreach ($users as $users2)
                                @if ($users2->id==$controluser->user_id)
                                    {{$users2->name}}
                                @endif
                            @endforeach
                        </td>
                        <td>
                            @foreach ($customers as $customers2)
                                @if ($customers2->id==$controluser->customer_id)
                                    {{$customers2->business_name}}
                                @endif
                            @endforeach
                        </td>

                        <td>
                            <div class="btn-toolbar">
                                <div class="btn-group me-2 mb-0">
                                    <a class="btn btn-primary btn-sm" href="{{ route('ctluser.edit',$controluser->id)}}">編集</a>
                                </div>
                                <div class="btn-group me-2 mb-0">
                                    <form action="{{ route('ctluser.destroy', $controluser->id)}}" method="POST">
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
                        {{-- <td><p> </p></td>hidden --}}
                    </tr>
                @endif

            </tbody>
        </table>
    {{-- </div> --}}

     {{-- ページネーション / pagination）の表示 --}}
    <ul class="pagination justify-content-center">
       {{ $controlusers->appends(request()->query())->render() }}
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
            //         /* OKの時の処理 */
            //         return true;
            //     }
            // });
    </script>
@endsection
