{{-- @extends('layouts.app') --}}
@extends('layouts.client')
<?php
    function formatBytes($bytes, $precision = 2, array $units = null)
    {
        if ( abs($bytes) < 1024 ){
            $precision = 0;
        }

        if ( is_array($units) === false ){
            $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        }

        if ( $bytes < 0 ){
            $sign = '-';
            $bytes = abs($bytes);
        }else{
            $sign = '';
        }
        $exp   = floor(log($bytes) / log(1024));
        $unit  = $units[$exp];
        $bytes = $bytes / pow(1024, floor($exp));
        $bytes = sprintf('%.'.$precision.'f', $bytes);
        return $sign.$bytes.' '.$unit;
    }
?>

@section('content')
    @if (session('message'))
        @if (session('message') == '送信処理を正常終了しました。')
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @else
            <div class="alert alert-danger">
                {{ session('message') }}
            </div>
        @endif
    @endif
    <div class="row">

        <!-- 検索エリア -->
        <form  class="my-2 my-lg-0 ml-2" action="{{route('topclientserch')}}" method="GET">
            {{-- <form  class="my-2 my-lg-0 ml-2" action="{{route('transserch_custom')}}" method="GET"> --}}
            @csrf
            @method('get')
            <style>
                .exright{
                    text-align: right;
                }
            </style>
            <div class="exright">
                <select style="margin-right:5px;" class="custom-select" id="customer_id" name="customer_id">
                    @foreach ($customer_findrec as $customer_findrec2)
                        @if ($customer_findrec2['id']==$customer_id)
                    <option selected="selected" value="{{ $customer_findrec2['id'] }}">{{ $customer_findrec2['business_name'] }}</option>
                        @else
                            <option value="{{ $customer_findrec2['id'] }}">{{ $customer_findrec2['business_name'] }}</option>
                        @endif

                    @endforeach
                </select>
                <button type="submit" class="btn btn-secondary btn_sm">送信元</button>
            </div>

        </form -->
        <!-- 検索エリア -->
    </div>

    <div class="table-responsive">

        {{-- 通知あり : 2  --}}
        @if ($notice_0 == 2)
            <table class="table table-striped table-borderd">
                <thead>
                    <tr>
                        <th scope="col"><h6>作成日</h6></th>
                        <th scope="col"><h6>事務所からの通知</h6></th>
                    </tr>

                    <tbody>
                        @foreach($newsrepos as $newsrepos2)
                            <tr>
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

                                    $str = "";
                                    if (isset($newsrepos2->created_at)) {
                                        $str = ( new DateTime($newsrepos2->created_at))->format('Y-m-d');
                                    }
                                    // 2022/12/29
                                    // 至急フラグ(1):通常 (2):至急
                                    if($newsrepos2->urgent_flg == 2) {
                                            $strvalue = "詳細を表示する";
                                            $clsvalue = "btn btn-danger btn-lg";
                                            $strstyle = "color:red";
                                            $strnews  = "NEW";
                                            $clslight = "light_box";    //点滅
                                        } else {
                                            $strvalue = "詳細を表示する";
                                            $clsvalue = "btn btn-secondary btn-lg";
                                            $strstyle = "";
                                            $strnews  = "";
                                            $clslight = "";
                                    }
                                @endphp
                                <td>
                                    {{$str}}
                                    <h6 >
                                        <div name="shine" class="{{$clslight}}" ><label style="margin-top:10px;">{{$strnews}}</label>
                                        </div>
                                    </h6>
                                </td>
                                <td>
                                    {{-- 2022/11/30 --}}
                        {{-- <a  disabled  cols="120"  rows="3"  name="comment">{!! nl2br($textarea) !!}</a> --}}
                {{-- <textarea  disabled  cols="120"  rows="3"  name="comment">{!! nl2br($textarea) !!}</textarea> --}}
                                    {{-- {!! nl2br($textarea) !!} --}}
<textarea class="row-5" id="comment_{{$newsrepos2->id}}" name="comment_{{$newsrepos2->id}}">{{$newsrepos2->comment}}</textarea>
                                </td>
                                    {{-- 2022/12/29 --}}
                                    {{-- 至急フラグ(1):通常 (2):至急 --}}
                                <td>
<input class="{{$clsvalue}}" type="submit" id="btn_del_{{$newsrepos2->id}}" name="btn_del_{{$newsrepos2->id}}" value="{{$strvalue}}" >
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                    <!-- ５行にしたいテキストエリア -->
                    <style>
                        /** ５行ピッタシに調整 6行*/
                        .row-5 {
                            height: calc( 1.4em * 4 );
                            line-height: 1.3;
                            /* max-width: 550px; */
                            width: 800px;
                            margin-top:5px;
                            margin-bottom:5px;
                        }
                        /* 点滅 */
                        .light_box{
                            width: 40px;
                            height: 40px;
                            margin: 5px auto;
                            opacity: 0;
                            background-color:rgb(255, 0, 0);
                            border-radius: 3.0rem;
                            animation: flash 1.5s infinite linear;
                            color:rgb(254, 254, 254);
                        }
                        @keyframes flash {
                            50% {
                            opacity: 1;
                            }
                        }
                    </style>
                    {{-- 2022/12/30 --}}
                    <script type="text/javascript">
                        $(function() {
                            $('input[name^="btn_del_"]').click( function(e){
                                // alert('詳細Click');
                                var wok_id   = $(this).attr("name").replace('btn_del_', '');
                                var this_id  = $(this).attr("id");
                                var url      = "/topclient/alert/" + wok_id;
                                $('#temp_form').method = 'POST';
                                $('#temp_form').submit();
    var popup = window.open(url,"preview","width=800, height=500, top=100, left=300 scrollbars=yes");

                            });

                        });
                        // $(".shine").text("NEW");
                    </script>
                </thead>
            </table>

            {{-- ページネーション / pagination）の表示 --}}
            <ul class="pagination justify-content-center">
                {{ $newsrepos->appends(request()->query())->render() }}
            </ul>
            <table class="table table-striped table-borderd">
                <thead>
                    <tr>
                        <th scope="col"><h6>お知らせ</h6></th>
                    </tr>
                </thead>
            </table>

            <table class="table table-striped table-borderd">
                <thead>
                    <tbody>
                        @if ($notice_1 == 2)
                        {{-- <td>決算月１カ月前です</td> --}}
                            {{-- 2022/11/30 --}}
                            @if ($indiv_class == 1)
                                <h5 >
                                    <span style="color:red">決算月１カ月前</span>
                                    <span style="color:blue"> です</span>
                                </h5>
                            @else
                                <h5 >
                                    <span style="color:red">年度末の１カ月前</span>
                                    <span style="color:blue"> です</span>
                                </h5>
                            @endif
                        @endif
                    </tbody>
                </thead>

                <thead>
                    <tbody>
                        @if ($notice_7 == 2)
                        {{-- <td>今月が決算月です。申告相談の連絡を待ってます</td> --}}
                            {{-- 2022/11/30 --}}
                            @if ($indiv_class == 1)
                                <h5 >
                                    <span style="color:red">今月が決算月</span>
                                    <span style="color:blue"> です。申告相談の連絡を待ってます</span>
                                </h5>
                            @else
                                <h5 >
                                    <span style="color:red">今月が年度末</span>
                                    <span style="color:blue"> です。申告相談の連絡を待ってます</span>
                                </h5>
                            @endif
                        @endif
                    </tbody>
                </thead>

                <thead>
                    <tbody>
                        @if ($notice_3 == 2)
                            {{-- <td>来月、所得税の納付があります</td> --}}
                            <h5 >
                                <span style="color:red">来月、所得税の納付</span>
                                <span style="color:blue"> があります</span>
                            </h5>
                        @endif
                    </tbody>
                </thead>

                <thead>
                    <tbody>
                        @if ($notice_6 == 2)
                            {{-- <td>来月が申告月です。納税まで忘れずに行いましょう</td> --}}
                            <h5 >
                                <span style="color:red">来月が申告月</span>
                                <span style="color:blue"> です。納税まで忘れずに行いましょう</span>
                            </h5>
                        @endif
                    </tbody>
                </thead>

                <thead>
                    <tbody>
                        @if ($notice_5 == 2)
                            {{-- <td>今月が申告月です。納税まで忘れずに行いましょう</td> --}}
                            <h5 >
                                <span style="color:red">今月が申告月</span>
                                <span style="color:blue"> です。納税まで忘れずに行いましょう</span>
                            </h5>
                        @endif
                    </tbody>
                </thead>
{{-- 2022/09/11 --}}
                <thead>
                    <tbody>
                        @if ($notice_8 == 2)
                        {{-- <td>11月：「来月、年末調整を行います。期限までに必要資料をアップロードしてください」</td> --}}
                            <h5 >
                                <span style="color:red">来月、年末調整</span>
                                <span style="color:blue"> を行います。期限までに必要資料をアップロードしてください</span>
                            </h5>
                        @endif
                    </tbody>
                </thead>
                <thead>
                    <tbody>
                        @if ($notice_9 == 2)
                        {{-- <td>12月：「年末調整の時期です。早急にアップロードをしてください」」</td> --}}
                            <h5 >
                                <span style="color:red">年末調整の時期</span>
                                <span style="color:blue"> です。早急にアップロードをしてください</span>
                            </h5>
                        @endif
                    </tbody>
                </thead>
                <thead>
                    <tbody>
                        @if ($notice_11 == 2)
{{--  $notice_11 = 1;   １月：「今月、住民税の支払い（４回目）があります」 --}}
{{--                       ：「2月16日より確定申告開始です。3月15日までに申告と納税が必要です」 --}}
                        <h5 >
                            <span style="color:red">今月、住民税の支払い</span>
                            <span style="color:blue"> （４回目）があります</span>
                        </h5>
                        <h5 >
                            <span style="color:red">2月16日より確定申告開始</span>
                            <span style="color:blue"> です。3月15日までに申告と納税が必要です</span>
                        </h5>
                        @endif
                    </tbody>
                </thead>
                <thead>
                    <tbody>
                        @if ($notice_12 == 2)
{{--  $notice_12 = 1; ２月：「2月16日より確定申告開始です。3月15日までに申告と納税が必要です」 --}}
                        <h5 >
                            <span style="color:red">2月16日より確定申告開始</span>
                            <span style="color:blue"> です。3月15日までに申告と納税が必要です</span>
                        </h5>
                        @endif
                    </tbody>
                </thead>
                <thead>
                    <tbody>
                        @if ($notice_13 == 2)
{{--  $notice_13 = 1; ３月：「3月15日までに申告と納税が必要です」 --}}
                        <h5 >
                            <span style="color:red">3月15日まで</span>
                            <span style="color:blue"> に申告と納税が必要です</span>
                        </h5>
                        @endif
                    </tbody>
                </thead>
                <thead>
                    <tbody>
                        @if ($notice_15 == 2)
{{--  $notice_15 = 1; ５月：「住民税の納付書が届く頃です。確認をお願いします」 --}}
                        <h5 >
                            <span style="color:red">住民税の納付書</span>
                            <span style="color:blue"> が届く頃です。確認をお願いします</span>
                        </h5>
                        @endif
                    </tbody>
                </thead>
                <thead>
                    <tbody>
                        @if ($notice_16 == 2)
{{--  $notice_16 = 1; ６月：「予定納税の納付書が届く頃です。」 --}}
{{-- //                  //        対象は下記の方です。 --}}
{{-- //                  //        ①所得税 「前年の所得税が15万円以上の方」 --}}
{{-- //                  //        ②消費税 「前年の消費税が60万円以上の方」 --}}
{{-- //                  //        確認をお願いします」 --}}
{{-- //                  //       「今月、住民税の支払い（１回目）があります」 --}}
                        <h5 >
                            <span style="color:red">予定納税の納付書</span>
                            <span style="color:blue"> が届く頃です。確認をお願いします</span>
                        </h5>
                        <h5 >
                            <span style="color:blue"> 対象は下記の方です。</span>
                        </h5>
                        <h5 >
                            <span style="color:blue"> ①所得税 「前年の所得税が15万円以上の方」</span>
                        </h5>
                        <h5 >
                            <span style="color:blue"> ②消費税 「前年の消費税が60万円以上の方」</span>
                        </h5>
                        <h5 >
                            <span style="color:blue"> 確認をお願いします</span>
                        </h5>
                        <h5 >
                            <span style="color:red">今月、住民税の支払い（１回目）</span>
                            <span style="color:blue"> があります</span>
                        </h5>
                        @endif
                    </tbody>
                </thead>
                <thead>
                    <tbody>
                        @if ($notice_17 == 2)
{{--  $notice_17 = 1; ７月：「今月、予定納税の納付書が届く頃です。 --}}
{{-- //                  //        対象は下記の方です。 --}}
{{-- //                  //        所得税 「前年の所得税が290万円以上の方」 --}}
{{-- //                  //        確認をお願いします」 --}}
{{-- //                  // ：    「今月、個人事業税の支払い（所得税：１回目）があります」 --}}
                        <h5 >
                            <span style="color:red">今月、予定納税の納付書</span>
                            <span style="color:blue"> が届く頃です。確認をお願いします</span>
                        </h5>
                        <h5 >
                            <span style="color:blue"> 対象は下記の方です。</span>
                        </h5>
                        <h5 >
                            <span style="color:blue"> 所得税 「前年の所得税が290万円以上の方」</span>
                        </h5>
                        <h5 >
                            <span style="color:blue"> 確認をお願いします</span>
                        </h5>
                        <h5 >
                            <span style="color:red">今月、個人事業税の支払い（所得税：１回目）</span>
                            <span style="color:blue"> があります</span>
                        </h5>
                        @endif
                    </tbody>
                </thead>
                <thead>
                    <tbody>
                        @if ($notice_18 == 2)
{{-- // $notice_18 = 1;   // ８月：「今月、住民税の支払い（２回目）があります」 --}}
{{-- //                  //     ：「今月、予定納税の支払い（消費税）があります」 --}}
{{-- //                  //     ：「今月、個人事業税の支払い（１回目）があります」 --}}
                        <h5 >
                            <span style="color:red">今月、住民税の支払い（２回目）</span>
                            <span style="color:blue"> があります</span>
                        </h5>
                        <h5 >
                            <span style="color:red">今月、予定納税の支払い（消費税）</span>
                            <span style="color:blue"> があります</span>
                        </h5>
                        <h5 >
                            <span style="color:red">今月、個人事業税の支払い（１回目）</span>
                            <span style="color:blue"> があります</span>
                        </h5>
                        @endif
                    </tbody>
                </thead>
                <thead>
                    <tbody>
                        @if ($notice_20 == 2)
{{-- // $notice_20 = 1;  // 10月：「今月、住民税の支払い（３回目）があります」 --}}
                        <h5 >
                            <span style="color:red">今月、住民税の支払い（３回目）</span>
                            <span style="color:blue"> があります</span>
                        </h5>
                        @endif
                    </tbody>
                </thead>
                <thead>
                    <tbody>
                        @if ($notice_21 == 2)
{{-- // $notice_21 = 1;  // 11月：「今月、予定納税の支払い（所得税：２回目）があります」 --}}
{{-- //                  //     ：「今月、個人事業税の支払い（２回目）があります」 --}}
                        <h5 >
                            <span style="color:red">今月、予定納税の支払い（所得税：２回目）</span>
                            <span style="color:blue"> があります</span>
                        </h5>
                        <h5 >
                            <span style="color:red">今月、個人事業税の支払い（２回目）</span>
                            <span style="color:blue"> があります</span>
                        </h5>
                        @endif
                    </tbody>
                </thead>

                <thead>
                    <tbody>
                        @if ($notice_2 == 2)
                        {{-- 2022/08/25 --}}
                        {{-- <td>中間納付の納付書が届く時期です</td> --}}
                        {{-- <td>予定納税の納付書が届く頃です。支払したら、納付書の画像を送ってください</td> --}}
                        <h5 >
                            <span style="color:red">予定納税の納付書が届く頃</span>
                            <span style="color:blue"> です。支払いをしたら、納付書の画像を送ってください</span>
                        </h5>
                        @endif
                    </tbody>
                </thead>

                <thead>
                    <tbody>
                        @if ($notice_4 == 2)
                            {{-- <td>会計データが最近提出されてません</td> --}}
                        <h5 >
                            <span style="color:red">会計データが最近提出されてません</span>
                            <span style="color:blue"></span>
                        </h5>
                        @endif
                    </tbody>
                </thead>
            </table>

        {{-- <h2>通知なし</h2> --}}
        @else
            <div class="row">
                <div class="col-md-8 order-md-1">
                    <thead>
                        <tr>
                            <th scope="col"><h6>事務所からの通知はありません</h6></th>
                        </tr>
                        <tbody>
                            <td></td>
                        </tbody>
                    </thead>
                </div>
            </div>
            <hr class="mb-4">  {{-- // line --}}
        @endif
    </div>

    <div class="container">
        <h6 class="jumbotron" >ファイルアップロード</h6>

        <form action="{{ route('postUpload', $customer_id )}}" id="file-upload" method="POST" enctype="multipart/form-data">
            <div id="upload-container" style="border: 1px solid; padding: 30px;">
                @csrf
            {{-- 複数法人 --}}
            <a style="color:blue">From: </a>
            <select style="margin-bottom:5px; background-color:rgb(214, 209, 209)" class="custom-select" id="customer_id" name="customer_id">
                @foreach ($customer_findrec as $customer_findrec2)
                    @if ($customer_findrec2['id']==$customer_id)
                <option value="{{ $customer_findrec2['id'] }}">{{ $customer_findrec2['business_name'] }}</option>
                    @else
                    <option disabled value="{{ $customer_findrec2['id'] }}">{{ $customer_findrec2['business_name'] }}</option>
                    @endif
                @endforeach
            </select>
        {{-- 2022/11/30 --}}
        <a >
            {{-- 2023/08/20 --}}
            {{-- <span style="margin-left:5px;" style="color:rgb(0, 0, 0)"> アップロード後は、左の </span> --}}
            <span style="color:red"> アップロード後は、左の </span>
            <span style="color:blue">「送信データ確認ページ」</span>
            {{-- 2023/08/20 --}}
            {{-- <span style="color:rgb(0, 0, 0)"> からファイルが送信できているか確認してください</span> --}}
            <span style="color:red"> からファイルが送信できているか確認してください。</span>
        </a>
            <div class="flow-error">
                <div class="alert alert-danger">
                </div>
                <div class="alert alert-success">
                </div>
            </div>

            <div class="flow-drop mb10" ondragenter="jQuery(this).addClass('flow-dragover');" ondragend="jQuery(this).
            removeClass('flow-dragover');" ondrop="jQuery(this).removeClass('flow-dragover');">
            <a class="ml10 flow-browse btn btn-primary">ファイル選択</a>　または、ここにファイルをドロップしてください
            </div>

            <div class="progress flow-progress" style="margin-top:10px; height: 30px;" >
                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span class="sr-only">45% Complete</span>
                </div>
            </div>
            <div class="progress flow-list" style="margin-top:10px; height: 30px;" >
                <div class="progress-flow-list flow-list-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span class="sr-only">sec</span>
                </div>
            </div>
        </form>
            {{-- //プログレスバーの実行 --}}
            {{-- //flow.progress() で進捗が取得できるのでそれを利用してプログレスバーを設定 --}}
            {{-- $('.bar').css({width:Math.floor(flow.progress()*100) + '%'}); --}}
        </div>

    <style>
            .ml10{
                margin-left: 10px;
            }
            .mr10{
                margin-right: 10px;
            }
            .mb10{
                margin-bottom: 10px;
            }
            /* Uploader: Drag & Drop */
            .flow-error { font-size:14px;display: none; }
            .flow-error >div{ display: none; }
            /* .flow-drop {padding:30px 15px; font-size:13px; text-align:center; color:#666; background-color:#fafafa; border:2px dashed #ccc; border-radius:4px; margin-top:40px; z-index:9999; display:none;} */
            /* drop display:none-->textセンター表示 display:flex--->text左表示 */
            .flow-drop {padding:30px 15px; font-size:14px; text-align:center; color:#505050; background-color:#fafafa; border:2px dashed #ccc; border-radius:6px; margin-top:40px; z-index:9999; display:flex;}
            .flow-dragover {padding:30px; color:#555; background-color:#eef1c3; border:1px solid rgb(244, 245, 233);}
            .flow-dragleave {padding:30px 15px; font-size:13px; text-align:center; color:#666; background-color:#fafafa; border:2px dashed #ccc; border-radius:4px; margin-top:40px; z-index:9999; display:none;}

            /* ---Uploader: Progress bar--- */
            .flow-progress{width:Math.floor(flow.progress()*100) + '%'};

            .is-paused .progress-resume-link {display:inline;}
            .is-paused .progress-pause-link {display:none;}
            .is-complete .progress-pause {display:none;}

            /* ---Uploader: List of items being uploaded--- */
            /* --これでは表示されない */
            /* .flow-list {margin-top: 30px;} width:'0'*/

            /* .flow-list {display:none;} 2021/12/10 */
            /* --display:noneでは連続で操作すると表示されないのでinlineで下に追加される */
            .flow-list {display:inline;}

            .uploader-item {width:148px; height:90px; background-color:#666; position:relative; border:2px solid black; float:left; margin:0 6px 6px 0;}
            .uploader-item-thumbnail {width:100%; height:100%; position:absolute; top:0; left:0;}
            .uploader-item img.uploader-item-thumbnail {opacity:0;}
            .uploader-item-creating-thumbnail {padding:0 5px; font-size:9px; color:white;}
            .uploader-item-title {position:absolute; font-size:9px; line-height:11px; padding:3px 50px 3px 5px; bottom:0; left:0; right:0; color:white; background-color:rgba(0,0,0,0.6); min-height:27px;}
            .uploader-item-status {position:absolute; bottom:3px; right:3px;}

            /* Uploader: Hover & Active status */
            .uploader-item:hover, .is-active .uploader-item {border-color:#4a873c; cursor:pointer; }
            .uploader-item:hover .uploader-item-title, .is-active .uploader-item .uploader-item-title {background-color:rgba(74,135,60,0.8);}

            /* Uploader: Error status */
            .is-error .uploader-item:hover, .is-active.is-error .uploader-item {border-color:#900;}
            .is-error .uploader-item:hover .uploader-item-title, .is-active.is-error .uploader-item .uploader-item-title {background-color:rgba(153,0,0,0.6);}
            .is-error .uploader-item-creating-thumbnail {display:none;}

        </style>


        <hr class="mb-4">  {{-- // line --}}

    </div>

@endsection


@section('scripts')
<!-- Scripts -->
<script src="{{ asset('js/flow.min.js') }}"></script>

<?php
    // 初期設定 2021/12/14
    // auth_customer_findrecの多重読み込みを1回で行う。jsonを利用
    $status = false;
    $arr = array(
        "res" => array(
            "info" => array(
                [
                    "status"     => $status
                ]
            )
        )
    );

    $arr = json_encode($arr);
    // file_put_contents("customer_info_status.json" , $arr); <===Orijinal
    // $jsonfileは、Controllerのindexより取得
    file_put_contents($jsonfile , $arr);
?>

<script type="text/javascript">

    (function () {
    var customer_id = $("#customer_id" + " option:selected").val();
    var isImage = true;
    var r = new Flow({
        simultaneousUploads : 5,
        // target: '/uploads',
        target: "{{ route('postUpload', $customer_id ) }}",
        permanentErrors:[404, 500, 501],
        headers: { 'X-CSRF-TOKEN': '{{csrf_token()}}'},
        testChunks:false
    });
      // Flow.js isn't supported, fall back on a different method
    if (!r.support) {
        $('.flow-error').show();
        return ;
    }
    // Show a place for dropping/selecting files
    $('.flow-drop').show();
    r.assignDrop($('.flow-drop')[0]);
    r.assignBrowse($('.flow-browse')[0]);
    // Handle file add event
    r.on('fileAdded', function(file){
        isImage = true;

        if( isImage ) {
            // Show progress bar
            $('.flow-progress, .flow-list').show();

            $('.flow-drop').css({color:'#220477'}); /* 青にする */

            // Add the file to the list
            $('.flow-list').append(
                '<li class="flow-file list-group-item flow-file-'+file.uniqueIdentifier+'">' +
                'Uploading <span class="flow-file-name mr10"></span> ' +
                '<span class="flow-file-size mr10"></span> ' +
                '<span class="flow-file-progress mr10"></span> '
            );
            var $self = $('.flow-file-'+file.uniqueIdentifier);
            $self.find('.flow-file-name').text(file.name);
            $self.find('.flow-file-size').text(readablizeBytes(file.size));
        }

    });
    r.on('filesSubmitted', function(file) {
        if( isImage ) {
            r.upload();
        }
    });

    r.on('fileSuccess', function(file,message){
        setTimeout(function(){
        $('.flow-progress').fadeOut(400,function(){
            // 2023/08/20 以下コメント
            // $(this).hide();

            $('.progress-bar').css({width:'0'});
            $('.flow-list').css({width:'0'});
            $('.flow-drop').css({color:'#505050'}); /* 元に戻す */

            // 2023/08/20 以下追加 リストファイル名を消す
            // $('.flow-file-'+file.uniqueIdentifier).hide();

            message = file.name + " のアップロードが正常に終了しました。";

            // 2023/08/20 1秒->5秒表示
            // alert('success',message,1000);
            alert('success',message,5000);

            // 2023/08/20 以下追加 flow-progressを再表示
            $('.flow-progress').show();
            // 表示5sec javasrripterror対応で[client.blade]のjquery.min.jsをcommentoutする
        })

    },5000)

    });
    r.on('fileError', function(file, message){
        // Reflect that the file upload has resulted in error
        $('.flow-progress').hide();
        $('.flow-file-'+file.uniqueIdentifier).hide();
        // 2021/12/19
        var response = JSON.parse(message || "null");
        // console.log(response);
        if(response == null) {
            message = file.name + ' のアップロードが出来ませんでした。';
            // 2023/08/17
            // alert('danger',message,1000);    // 表示1sec
            alert('danger',message,5000);    // 表示5sec
        } else {
            if(response.status == "BG") {
                // 2023/08/17
                // alert('danger',response.error,1000);    // 表示1sec
                alert('danger',response.error,5000);    // 表示5sec
            } else {
                message = file.name + ' のアップロードが出来ませんでした。';
                // 2023/08/17
                // alert('danger',response.error,1000);    // 表示1sec
                alert('danger',response.error,5000);    // 表示5sec
            }
        }
    });
    r.on('fileProgress', function(file){
        // Handle progress for both the file and the overall upload
        $('.flow-file-'+file.uniqueIdentifier+' .flow-file-progress')
        .html(Math.floor(file.progress()*100) + '% '
            + readablizeBytes(file.averageSpeed) + '/s '
            // + secondsToStr(file.timeRemaining()) + ' remaining') ;
            // + secondsToStr(file.timeRemaining()) + ' 残り') ;
            + ' 残り 約 ' + secondsToStr(file.timeRemaining()) ) ;
        $('.progress-bar').css({width:Math.floor(r.progress()*100) + '%'});
    });

    function alert(type,message,timeout){
        $('.flow-error').find('.alert').hide();
        $('.flow-error').show();
        $('.flow-error').find('.alert-' + type).text(message).fadeIn(400,function(){
                setTimeout(function(){
                    $(this).fadeOut(400,function(){
                    $(this).hide();
                    $('.flow-error').hide();
                });
            },timeout)
            });
        }
    })();
    function readablizeBytes(bytes) {
        var s = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB'];
        var e = Math.floor(Math.log(bytes) / Math.log(1024));
        // 2022/11/04 0(-Infinity)対応
        if(e == -Infinity) return(0).toFixed(2)+ " " + s[0];;
        return (bytes / Math.pow(1024, e)).toFixed(2) + " " + s[e];
    }
    function secondsToStr (temp) {
            function numberEnding (number) {
        //     return (number > 1) ? 's' : '';
        //   }
        //   var years = Math.floor(temp / 31536000);
        //   if (years) {
        //     return years + ' year' + numberEnding(years);
        //   }
        //   var days = Math.floor((temp %= 31536000) / 86400);
        //   if (days) {
        //     return days + ' day' + numberEnding(days);
        //   }
        //   var hours = Math.floor((temp %= 86400) / 3600);
        //   if (hours) {
        //     return hours + ' hour' + numberEnding(hours);
        //   }
        //   var minutes = Math.floor((temp %= 3600) / 60);
        //   if (minutes) {
        //     return minutes + ' minute' + numberEnding(minutes);
        //   }
        //   var seconds = temp % 60;
        //   return seconds + ' second' + numberEnding(seconds);
            return (number > 1) ? '' : '';
        }
        var years = Math.floor(temp / 31536000);
        if (years) {
            return years + ' 年' + numberEnding(years);
        }
        var days = Math.floor((temp %= 31536000) / 86400);
        if (days) {
            return days + ' 日' + numberEnding(days);
        }
        var hours = Math.floor((temp %= 86400) / 3600);
        if (hours) {
            return hours + ' 時間' + numberEnding(hours);
        }
        var minutes = Math.floor((temp %= 3600) / 60);
        if (minutes) {
            return minutes + ' 分' + numberEnding(minutes);
        }
        var seconds = temp % 60;
        return seconds + ' 秒' + numberEnding(seconds);
    }
</script>
@endsection
