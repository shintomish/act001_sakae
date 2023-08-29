@extends('layouts.customer_index')

@section('content')
    {{-- <h2>News一覧</h2> --}}

    {{-- <div class="table-responsive"> --}}
        @if (session('message'))
            {{-- @if (session('message') == 'メール送信完了')
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @else
                <div class="alert alert-danger">
                    {{ session('message') }}
                </div>
            @endif --}}
        @endif
        <div class="btn-toolbar">
            <div class="btn-group  me-5 mb-0">
                {{-- <a class="btn btn-success btn-sm" href="{{route('newsrepo.create')}}">新規登録</a> --}}
            </div>
            {{-- <form action="{{ route('newssend' ) }}" method="POST"> --}}
                {{-- @csrf --}}
                {{-- <input type="submit" class="btn btn-primary btn-sm" value="メール送信"> --}}
            {{-- </form> --}}

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
                width: 400px;   /* 200->400 */
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

        {{-- <table class="table table-striped table-borderd"> --}}
        <table class="table table-responsive text-nowrap table-striped table-borderd table_sticky">
            <thead>
                <tr>
                    <th scope="row" class ="fixed01">ID</th>
                    <th scope="row" class ="fixed01">操作日</th>
                    <th scope="row" class ="fixed01">ユーザー名</th>
                    <th scope="row" class ="fixed01">route</th>
                    <th scope="row" class ="fixed01">url</th>
                    <th scope="row" class ="fixed01">method</th>
                    <th scope="row" class ="fixed01">status</th>
                    <th scope="row" class ="fixed01">data</th>
                    <th scope="row" class ="fixed01">remote_addr</th>
                    <th scope="row" class ="fixed01">user_agent</th>
                </tr>
            </thead>

            <tbody>
                @if($actlog->count())
                    @foreach($actlog as $actlog2)
                    <tr>
                        <td>{{ $actlog2->id }}</td>
                        <td>{{ $actlog2->created_at }}</td>
                        <td>
                            @foreach ($users as $users2)
                                @if ($users2->id==$actlog2->user_id)
                                    {{$users2->name}}
                                @endif
                            @endforeach
                        </td>
                        <td>{{ $actlog2->route }}</td>
                        <td>{{ $actlog2->url }}</td>
                        <td>{{ $actlog2->method }}</td>
                        <td>{{ $actlog2->status }}</td>
                        <td>{{ $actlog2->data }}</td>
                        <td>{{ $actlog2->remote_addr }}</td>
                        <td>{{ $actlog2->user_agent }}</td>
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
                    </tr>
                @endif
            </tbody>
        </table>
    {{-- </div> --}}
    {{-- ページネーション / pagination）の表示 --}}
    <ul class="pagination justify-content-center">
        {{ $actlog->appends(request()->query())->render() }}
    </ul>

@endsection

@section('part_javascript')
    <script>
        // ChangeSideBar("nav-item-system-organization");

        // $('.btn_del').click(function(){
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
