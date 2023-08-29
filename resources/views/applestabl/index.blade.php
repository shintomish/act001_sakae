{{-- @extends('layouts.app') --}}
@extends('layouts.common_index')

@section('content')
    {{-- <h2>顧問料一覧</h2> --}}
    <div class="text-right">
        <a class="btn btn-success btn-sm mr-auto" href="{{route('applestabl.create')}}">新規登録</a>
    </div>

    <div class="row">
        <!-- 検索エリア -->

        <!-- 検索エリア -->
    </div>

    {{-- Line --}}
    <hr class="mb-4">

    <div class="table-responsive">

        <table class="table table-striped table-borderd table-scroll">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">年</th>
                    <th scope="col">社名</th>
                    <th scope="col">申請・設立内容</th>
                    <th scope="col">納期</th>
                    <th scope="col">申請・郵送</th>
                    <th scope="col">操作</th>
                </tr>
            </thead>

            <tbody>
                @if($applestabls->count())
                {{-- 今月の申請・設立 --}}
                    @foreach($applestabls as $applestabls2)
                        <tr>
                            <td>{{$applestabls2->id}}</td>
                            <td>{{$applestabls2->year}}</td>
                            <td>{{$applestabls2->companyname}}</td>
                            <td>{{$applestabls2->estadetails}}</td>
                                @php
                                    $str = "-";
                                    if (isset($applestabls2->delivery_at)) {
                                        $str = ( new DateTime($applestabls2->delivery_at))->format('Y-m-d');
                                    }
                                @endphp
                            <td>{{ $str }}</td>
                            <td>
                                @foreach ($loop_mail_flg as $loop_mail_flg2)
                                    @if ($loop_mail_flg2['no']==$applestabls2->mail_flg)
                                        {{ $loop_mail_flg2['name'] }}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                <div class="btn-toolbar">
                                    <div class="btn-group me-2 mb-0">
                                    <a class="btn btn-primary btn-sm" href="{{ route('applestabl.edit',$applestabls2->id)}}">編集</a>
                                    </div>
                                    <div class="btn-group me-2 mb-0">
                                        <form action="{{ route('applestabl.destroy', $applestabls2->id)}}" method="POST">
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
                    </tr>
                @endif

            </tbody>
        </table>

    </div>

     {{-- ページネーション / pagination）の表示 --}}
     <ul class="pagination justify-content-center">
        {{ $applestabls->appends(request()->query())->render() }}
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
