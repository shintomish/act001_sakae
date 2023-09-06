{{-- @extends('layouts.app') --}}
@extends('layouts.common_index')

@section('content')
    {{-- <h2>利用者一覧</h2> --}}
    <div class="text-right">
        {{-- <a class="btn btn-success btn-sm mr-auto" href="{{route('user.create')}}">新規登録</a> --}}
    </div>

    <form  class="my-2 my-lg-0 ml-2" action="{{route('operationperiodsearch')}}" method="GET">
        @csrf
        @method('get')
        <table>
            <div style="display:inline-flex">
                <div class="row">
                    {{-- <div class="col-sm-2">
                    </div> --}}
                    <label for="notlogin">ログインしていない事業主　　　 以前　　以降</label>

                    <div class="col-sm-4">
                        <input type="date" class="form-control" id="frdate" name="frdate" value="{{$frdate}}">
                    </div>

                    <div class="col-sm-4">
                        <input type="date" class="form-control" id="todate"  name="todate" value="{{$todate}}">
                    </div>

                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-sm mr-auto btn-secondary">検索</button>
                    </div>

                </div>
            </div>
            <tr>
            </tr>
        </table>
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
            width: 250px;
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
                    {{-- <th scope="col" class ="fixed01">@sortablelink('id', 'ID')</th> --}}
                    <th scope="col" class ="fixed02">ID</th>
                    <th scope="col" class ="fixed01">@sortablelink('name', 'ユーザー名')</th>
                    <th scope="col" class ="fixed01">@sortablelink('business_name', '顧客名')</th>
                    <th scope="col" class ="fixed01">@sortablelink('status_flg', '状態')</th>
                    <th scope="col" class ="fixed01">@sortablelink('login_verified_at', 'ログイン日時')</th>
                    {{-- <th scope="col" class ="fixed01">@sortablelink('logout_verified_at', 'ログアウト日時')</th> --}}
                </tr>
            </thead>

            <tbody>
                @if($operations->count())
                    @foreach($operations as $operation)
                    <tr>
                        <td>{{ $operation->id }}</td>
                        <td>{{ $operation->name }}</td>
                        <td>
                            @foreach ($customers as $customers2)
                                @if ($customers2->id==$operation->user_id)
                                    {{$customers2->business_name}}
                                @endif
                            @endforeach
                        </td>
                            @php
                                if ($operation->status_flg == 1) {
                                    $str = "ログイン中";
                                }
                                if ($operation->status_flg == 2) {
                                    $str = "ログアウト";
                                }
                                if ($operation->status_flg == 3) {
                                    $str = "ログインなし";
                                }
                            @endphp

                        <td>{{ $str }}</td>
                        {{-- <select class="custom-select" id="attach_doc_{{$operation->id}}" name="attach_doc_{{$operation->id}}">
                            <option value="1" {{ $operation->status_flg == 1 ? 'selected' : '' }}>ログイン中</option>
                            <option value="2" {{ $operation->status_flg == 2 ? 'selected' : '' }}>ログアウト</option>
                            <option value="3" {{ $operation->status_flg == 3 ? 'selected' : '' }}>ログインなし</option>
                        </select> --}}
                        <td>{{ $operation->login_verified_at }}</td>
                        {{-- <td>{{ $operation->logout_verified_at }}</td> --}}

                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td><p>0件です。</p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        {{-- <td><p> </p></td> --}}
                    </tr>
                @endif

            </tbody>
        </table>
    {{-- </div> --}}

     {{-- ページネーション / pagination）の表示 --}}
    <ul class="pagination justify-content-center">
       {{ $operations->appends(request()->query())->render() }}
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
