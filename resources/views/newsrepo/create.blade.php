@extends('layouts.customer')

@section('content')
<div class="content-wrapper">
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

    <!-- ---------------------------------------------------------------------- -->
    <!-- Content Header (Page header)                                           -->
    <!-- ---------------------------------------------------------------------- -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    {{-- <h1 id="page-title" class="m-0 text-dark">システム・メンテナンス</h1> --}}
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- ---------------------------------------------------------------------- -->
    <!-- Main content                                                           -->
    <!-- ---------------------------------------------------------------------- -->
    <section class="content">
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
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- card content News新規追加 -->
                    <div id="card_newsrepo_list_edit" class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">News・メール配信作成</h3>
                        </div>
                        <!-- /.card-header -->

                        <span style="margin-left:20px;" class="text-success">送信 ( {{ $count }} 件)</span>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('newsrepo.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="col-8">
                                    <div class="col-6">
                                        <label style="margin-right:5px;" for="individual_mail">法人／個人</label>
                                        <select style="margin-bottom:5px;" class="custom-select" id="individual_mail" name="individual_mail">
                                            @foreach ($loop_individual_mail as $loop_individual_mail2)

                                            @if ($loop_individual_mail2['no']!='0')
                                                @if ($loop_individual_mail2['no']==$individual)
                                                <option selected="selected" value="{{ $loop_individual_mail2['no'] }}">{{ $loop_individual_mail2['name'] }}</option>
                                                @else
                                                <option value="{{ $loop_individual_mail2['no'] }}">{{ $loop_individual_mail2['name'] }}</option>
                                                @endif
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-8">
                                    <div class="col-6">
                                        <label  style="margin-right:6px;" for="interim_mail">選　択　月</label>
                                        <select style="margin-bottom:5px;" class="custom-select" id="interim_mail" name="interim_mail">
                                            @foreach ($loop_interim_mail as $loop_interim_mail2)
                                                @if ($loop_interim_mail2['no']!='0')
                                                @if ($loop_interim_mail2['no']==$interim_mail)
                                                <option selected="selected" value="{{ $loop_interim_mail2['no'] }}">{{ $loop_interim_mail2['name'] }}</option>
                                                @else
                                                <option value="{{ $loop_interim_mail2['no'] }}">{{ $loop_interim_mail2['name'] }}</option>
                                                @endif
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-8">
                                    <div class="col-6">
                                        <label  style="margin-right:6px;" for="announce_month">告　知　月</label>
                                        <select style="margin-bottom:5px;" class="custom-select" id="announce_month" name="announce_month">
                                        {{-- <select style="margin-bottom:5px;" class="custom-select" id="announce_month" name="announce_month" onchange="changeColor(this)"> --}}
                                            @foreach ($loop_announce_month as $loop_announce_month2)
                                                @if ($loop_announce_month2['no']!='0')
                                                @if ($loop_announce_month2['no']==$announce_month)
                                                <option selected="selected" value="{{ $loop_announce_month2['no'] }}">{{ $loop_announce_month2['name'] }}</option>
                                                @else
                                                <option value="{{ $loop_announce_month2['no'] }}">{{ $loop_announce_month2['name'] }}</option>
                                                @endif
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- 2022/11/15 Mail添付 下記を追記 --}}
                                    {{-- <div class="file"> --}}
                                        {{-- <label style="margin-bottom:5px;">添付ファイル</label> --}}
                                    {{-- <input style="margin-left:7px;" type="file" id="file" name="file" class="form-control"> --}}
                                        {{-- <input type="hidden" name="organization_id" value="{{ $organization_id }}"> --}}
                                        {{-- <input type="hidden" name="user_id" value="{{ $user_id }}"> --}}
                                    {{-- </div> --}}
                                    {{-- 2022/11/15 Mail添付 上記を追記 --}}
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-2">
                                            <label for="comment">コメント</label>
        <div class="col-2">
        <button  name="submit_temp" value="submit_temp" type="submit" class="btn btn-primary btn-sn btn-block mt-1" formaction="{!! route('newstemp') !!}"> Template </button>
        </div>
        {{-- <button  name="submit_non" value="submit_non" type="submit" class="btn btn-primary btn-sn btn-block mt-2" formaction="{!! route('newsnon') !!}">会計未処理</button> --}}

                                        </div>
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
                                        $textarea = replaceUrl($comment_out);
                                        @endphp
{{-- <input type="textarea" class="row-5" name="comment" value="{{ old('comment')}}">{!! nl2br(htmlspecialchars($textarea)) !!} --}}
{{-- <textarea class="row-5" id="comment" name="comment" value="{{ old($textarea)}}">{!! (htmlspecialchars(old('textarea') ?? '')) !!}</textarea> --}}
{{-- {{ $textarea }} --}}
<textarea class="row-5" id="comment" name="comment">{!! (htmlspecialchars($textarea ?? '')) !!}</textarea>
                                        <!-- ５行にしたいテキストエリア -->
                                        <style>
                                            /** ５行ピッタシに調整 6行*/
                                            .row-5 {
                                                height: calc( 1.4em * 6 );
                                                line-height: 1.3;
                                                /* max-width: 550px; */
                                                width: 550px;
                                                margin-top:5px;
                                                margin-bottom:5px;
                                            }
                                        </style>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
<button  id="start2" name="submit_new" value="submit_new" type="submit" class="btn btn-block btn-primary" formaction="{!! route('newsmail') !!}">メール送信</button>
<button name="submit" value="submit" type="submit" class="btn btn-block btn-primary">登録</button>
<a style="top: 5;" class="btn btn-block btn-primary" href="{{route('newsrepo.index')}}">戻る</a>
                                    </div>
                                </div>
                                <p>送信状況：
                                <div class="progress">
                                    <div id="pgss2" class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
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
                                <th scope="row" class ="fixed01">@sortablelink('email',  'E-Mail')</th>
                                {{-- <th scope="col" class ="fixed01">@sortablelink('notificationl_flg',  'News通知')</th> --}}
                            </tr>
                        </thead>

                        <tbody>
                            @if($count)
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

                                    {{-- //E-mail --}}
                                    <td>{{ $customer->email }}</td>

                                    {{-- //契約/解除 --}}
                                    {{-- @foreach ($loop_active_cancel as $loop_active_cancel2)
                                        @if ($loop_active_cancel2['no']==$customer->active_cancel)
                                            <td>{{ $loop_active_cancel2['name'] }}</td>
                                        @endif
                                    @endforeach --}}

                                    {{-- //通知しない(1):通知する(2) --}}
                                    {{-- @foreach ($loop_notificationl_flg as $loop_notificationl_flg2)
                                        @if ($loop_notificationl_flg2['no']==$customer->notificationl_flg)
                                            <td>{{ $loop_notificationl_flg2['name'] }}</td>
                                        @endif
                                    @endforeach --}}

                                    {{-- <td>
                                        <div class="btn-toolbar">
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
                                    {{-- <td><p> </p></td> --}}
                                    {{-- <td><p> </p></td> hidden --}}
                                </tr>
                            @endif

                        </tbody>
                    </table>
                    <hr class="mb-4">  {{-- // line --}}
                </div>
            </div>
        </div>

        <script type="text/javascript">
            //---------------------------------------------------------------
            //--法人／個人プルダウンイベントハンドラ
            //---------------------------------------------------------------
            $('select[name^="individual_mail"]').change( function(e){
                // alert('法人／個人Click');
                // var wok_id           = $(this).attr("name").replace('announce_month', '');
                var this_id          = $(this).attr("id");
                var individual_mail  = $("#"+this_id + " option:selected").val();
                change_from_mail(         this_id           // 対象コントロール
                                        , individual_mail   // 法人／個人
                                        , interim_mail      // 選択月
                                        , announce_month    // 告知月
                                );
            });

            //---------------------------------------------------------------
            //--選択月プルダウンイベントハンドラ
            //---------------------------------------------------------------
            $('select[name^="interim_mail"]').change( function(e){
                // alert('選択月Click');
                // var wok_id           = $(this).attr("name").replace('announce_month', '');
                var this_id          = $(this).attr("id");
                var interim_mail     = $("#"+this_id + " option:selected").val();
                change_from_mail(         this_id           // 対象コントロール
                                        , individual_mail   // 法人／個人
                                        , interim_mail      // 選択月
                                        , announce_month    // 告知月
                                );
            });

            //---------------------------------------------------------------
            //--告知月プルダウンイベントハンドラ
            //---------------------------------------------------------------
            $('select[name^="announce_month"]').change( function(e){
                // alert('告知月Click');
                // var wok_id           = $(this).attr("name").replace('announce_month', '');
                var this_id          = $(this).attr("id");
                var announce_month   = $("#"+this_id + " option:selected").val();
                change_from_mail(         this_id           // 対象コントロール
                                        , individual_mail   // 法人／個人
                                        , interim_mail      // 選択月
                                        , announce_month    // 告知月
                                );
            });

            // function changeColor(announce_month){
            //                         alert('change60');
            //             if( announce_month.value == 6 ){
            //                 individual_mail.style.color = 'red';
            //                 document.getElementById("individual_mail").value = 3;
            //             }else{
            //                 individual_mail.style.color = '';
            //             }
            //         }
            /**
            * this_id         : 対象コントロール
            * individual_mail : 法人／個人
            * interim_mail    : 選択月
            * announce_month  : 告知月
            *
            */
            function change_from_mail(    this_id
                                        , individual_mail   // 法人／個人
                                        , interim_mail      // 選択月
                                        , announce_month    // 告知月
                                    ){

                if(announce_month == 6) {
                    // alert('change60');
                    document.getElementById("individual_mail").value = 3;
                    document.getElementById("interim_mail").value = 13;
                }
                if((individual_mail == 1 ||individual_mail == 2) &&
                    document.getElementById("announce_month").value == 6) {
                    // alert('change20');
                    // document.getElementById("announce_month").value = 1;
                    document.getElementById("interim_mail").value = 13;
                }
                if(individual_mail == 2) {
                    // alert('change20');
                    // document.getElementById("announce_month").value = 1;
                    // document.getElementById("interim_mail").value = 13;
                }
                if(interim_mail != 13 && document.getElementById("announce_month").value == 6) {
                    // alert('change30');
                    // document.getElementById("announce_month").value = 1;
                    document.getElementById("interim_mail").value = 13;
                }
                document.getElementById("comment").value = "";

                };

        </script>

        {{-- 進捗バー --}}
        <script>
            $(function () {
                var count = 0;
                $(document).on('click','#start2',function(){
                    progress(count);
                });

                function progress(count){
                    setTimeout(function(){
                        $("#pgss2").css({'width':count+'%'});
                        $("#pgss2").prop('aria-valuenow', count)
                        $("#pgss2").text(count + '%');
                        count++;
                        if(count == 100) return;
                        progress(count);
                    },150);
                }
            })

        </script>

    </section>
@endsection

@section('part_javascript')
{{-- ChangeSideBar("nav-item-system-newsrepo"); --}}

@endsection
