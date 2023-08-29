@extends('layouts.customer_index')

@section('content')
    {{-- <h2>News一覧</h2> --}}

    {{-- <div class="table-responsive"> --}}
        @if (session('message'))
            @if (session('message') == 'メール送信完了')
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @else
                <div class="alert alert-danger">
                    {{ session('message') }}
                </div>
            @endif
        @endif
        <div class="btn-toolbar">
            <div class="btn-group  me-5 mb-0">
                <a class="btn btn-success btn-sm" href="{{route('newsrepo.create')}}">新規登録</a>
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
                    /* height: 100%; 2023/06/12 sortablelink対応 */
                    height: 10%;
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
        <table class="table table-striped table-borderd table_sticky">
            <thead>
                <tr>
                    <th scope="col" class ="fixed01">ID</th>
                    <th scope="col" class ="fixed01">@sortablelink('created_at', '作成日')</th>
                    <th scope="col" class ="fixed01">@sortablelink('mail_flg',   'MAIL/登録')</th>
                    <th scope="col" class ="fixed01">@sortablelink('individual_mail', '法人/個人')</th>
                    <th scope="col" class ="fixed01">@sortablelink('interim_mail', '選択月')</th>
                    <th scope="col" class ="fixed01">@sortablelink('announce_month', '告知月')</th>
                    <th scope="col" class ="fixed01">コメント</th>
                    <th scope="col" class ="fixed01">操作</th>
                </tr>
            </thead>

            <tbody>
                @if($newsrepos->count())
                    @foreach($newsrepos as $newsrepos2)
                    <tr>
                        <td>{{ $newsrepos2->id }}</td>
                        <td>{{ $newsrepos2->created_at }}</td>
                        <td>
                            @foreach ($loop_mail_flg as $loop_mail_flg2)
                            @if ($loop_mail_flg2['no']==$newsrepos2->mail_flg)
                            {{ $loop_mail_flg2['name'] }}
                            @endif
                            @endforeach
                        </td>
                        <td>
                            @foreach ($loop_individual_mail as $loop_individual_mail2)
                            @if ($loop_individual_mail2['no']==$newsrepos2->individual_mail)
                            {{ $loop_individual_mail2['name'] }}
                            @endif
                            @endforeach
                        </td>
                        <td>
                            @foreach ($loop_interim_mail as $loop_interim_mail2)
                            @if ($loop_interim_mail2['no']==$newsrepos2->interim_mail)
                            {{ $loop_interim_mail2['name'] }}
                            @endif
                            @endforeach
                        </td>
                        <td>
                            @foreach ($loop_announce_month as $loop_announce_month2)
                            @if ($loop_announce_month2['no']==$newsrepos2->announce_month)
                            {{ $loop_announce_month2['name'] }}
                            @endif
                            @endforeach
                        </td>
                        @php
                        if (!function_exists('replaceUrl')) {
                            function replaceUrl($text)
                            {
                                $texts = explode(PHP_EOL, $text);
                                //PHP_EOLは,改行コードをあらわす.改行があれば分割する
                                // $pattern = '/^https?:\/\/[^\s 　\\\|`^"\'(){}<>\[\]]*$/'; //正規表現パターン
                                // $pattern = '/^https?:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+/';
                                $pattern = '|https*?\://[-_.!~*a-zA-Z0-9;/?:@&=+$,%#]+|';
                                $replacedTexts = array(); //空の配列を用意

                                foreach ($texts as $value) {
                                    $replace = preg_replace_callback($pattern, function ($matches) {
                                    //textが１行ごとに正規表現にmatchするか確認する
                                        if (isset($matches[1])) {
                                            return $matches[0]; //$matches[0] がマッチした全体を表す
                                        }
                                    //既にリンク化してあれば置換は必要ないので、配列に代入
                                        return '<a href="' . $matches[0] . '" target="_blank" rel="noopener">' . $matches[0] . '</a>';
                                    }, $value);
                                    $replacedTexts[] = $replace;
                                    //リンク化したコードを配列に代入
                                }
                                return implode(PHP_EOL, $replacedTexts);
                                //配列にしたtextを文字列にする
                            }
                        }
                        $textarea = replaceUrl($newsrepos2->comment);

                    @endphp
                        {{-- <td>{{ $newsrepos2->comment }}</td> --}}
                        <td>{!! nl2br($textarea) !!}</td>
                        <td>
                            <div class="btn-toolbar">
                                <div class="btn-group me-2 mb-0">
                                    <a class="btn btn-primary btn-sm" href="{{ route('newsrepo.edit',$newsrepos2->id)}}">編集</a>
                                </div>
                                    <form action="{{ route('newsrepo.destroy', $newsrepos2->id)}}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                    <input type="submit" value="削除" class="btn btn-danger  btn-sm" onclick='return confirm("削除しますか？");'>
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
                    </tr>
                @endif
            </tbody>
        </table>
    {{-- </div> --}}
    {{-- ページネーション / pagination）の表示 --}}
    <ul class="pagination justify-content-center">
        {{ $newsrepos->appends(request()->query())->render() }}
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
