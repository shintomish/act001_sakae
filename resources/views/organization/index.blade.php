@extends('layouts.customer')

@section('content')
    {{-- システム管理者の時 --}}
    @if (0 == $organization->id)
        <h2>登録組織の一覧</h2>
    @else
        <h2>登録組織情報</h2>
    @endif
    <div class="table-responsive">
        <div class="btn-toolbar">
            <div class="btn-group ml-auto">
                    {{-- システム管理者の時 --}}
                    @if (0 == $organization->id)
                    <a class="btn btn-success btn-sm ms-auto" href="{{route('organization.create')}}">新規登録</a>
                    @else
                        <h2></h2>
                    @endif
            </div>
        </div>
        <table class="table table-striped table-borderd">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">組織名</th>
                    <th scope="col">住所</th>
                    <th scope="col">電話番号</th>
                    <th scope="col">E-メール</th>
                    <th scope="col">コメント</th>
                    <th scope="col">操作</th>
                </tr>
            </thead>

            <tbody>
                @foreach($organizations as $organization2)
                <tr>
                    <td>{{ $organization2->id }}</td>
                    <td>{{ $organization2->name }}</td>
                    <td>
                        {{ $organization2->prefecture }}
                        {{ $organization2->city }}
                        {{ $organization2->address }}
                        {{ $organization2->other }}
                    </td>
                    <td>{{ $organization2->phone }}</td>
                    <td>{{ $organization2->email }}</td>
                    <td>{{ $organization2->comment }}</td>
                    <td>
                        <div class="btn-toolbar">
                            <div class="btn-group me-2 mb-0">
                                <a class="btn btn-primary btn-sm" href="{{ route('organization.edit',$organization2->id)}}">編集</a>
                            </div>
                            <div class="btn-group me-2 mb-0">
                                {{-- システム管理者の時 --}}
                                @if (0 == $organization->id)
                                    <form action="{{ route('organization.destroy', $organization->id)}}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                    <input type="submit" value="削除" class="btn btn-danger  btn-sm" onclick='return confirm("削除しますか？");'>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{-- ページネーション / pagination）の表示 --}}
    <ul class="pagination justify-content-center">
       {{ $organizations->render() }}
    </ul>

@endsection

@section('part_javascript')
    <script>
        ChangeSideBar("nav-item-system-organization");

        $('.btn_del').click(function(){
            if( !confirm('本当に削除しますか？') ){
                /* キャンセルの時の処理 */
                return false;
            }
            else{
                /*　OKの時の処理 */
                return true;
            }
        });
    </script>
@endsection
