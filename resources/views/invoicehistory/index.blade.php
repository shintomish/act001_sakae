{{-- @extends('layouts.app') --}}
@extends('layouts.clienttrans')

@section('content')
    <h5>請求書データダウンロードページ</h5>
    <div class="row">

        <!-- 検索エリア -->

        <!-- 検索エリア -->
    </div>

    <div class="table-responsive">

        <table class="table table-striped table-borderd">
            <thead>
                <tr>
                    <th class="text-left"scope="col">ID</th>
                    <th scope="col">ダウンロードファイル</th>
                    <th scope="col">受信日</th>
                    <th class="text-left" scope="col">ファイルサイズ</th>
                    <th scope="col">会社名</th>
                    <th scope="col"> </th>
                    <th scope="col">操作</th>

                </tr>
            </thead>

            <tbody id="table" >
                @if($invoices->count())
                    @foreach($invoices as $invoice)
                    <tr>
                        <td class="text-left">{{ number_format($invoice->id) }}</td>
                        {{-- invoice_pdf01 --}}
                        <td>{{ $invoice->filename }}</td>
                            @php
                                $str = "";
                                if (isset($invoice->created_at)) {
                                    $str = ( new DateTime($invoice->created_at))->format('Y-m-d');
                                }

                                $insize = $invoice->filesize;
                                if ($insize >= 1073741824) {
                                    $fileSize = round($insize / 1024 / 1024 / 1024,1) . ' GB';
                                } elseif ($insize >= 1048576) {
                                    $fileSize = round($insize / 1024 / 1024,1) . ' MB';
                                } elseif ($insize >= 1024) {
                                    $fileSize = round($insize / 1024,1) . ' KB';
                                } else {
                                    $fileSize = $insize . ' bytes';
                                }
                                $temp = $fileSize;

                                // 至急フラグ(1):通常 (2):至急
                                if($invoice->urgent_flg == 2) {
                                    $strvalue = "ダウンロード";
                                    $clsvalue = "btn btn-danger btn-lg";
                                    $strstyle = "color:red";
                                    $strnews  = "NEW";
                                    $clslight = "light_box";    //点滅
                                } else {
                                    $strvalue = "ダウンロード";
                                    $clsvalue = "btn btn-secondary btn-lg";
                                    $strstyle = "";
                                    $strnews  = "";
                                    $clslight = "";
                                }
                        @endphp
                        <td >{{ $str }}</td>
                        <td class="text-left">{{ $temp }}</td>

                        <td>
                            @foreach ($customer_findrec as $customer_findrec2)
                                @if ($customer_findrec2->id==$invoice->customer_id)
                                    {{$customer_findrec2['business_name']}}
                                @endif
                            @endforeach
                        </td>
                        <td>
                            <h6 >
                                <div name="shine_{{$invoice->id}}" class="{{$clslight}}" ><label name="label_{{$invoice->id}}" style="margin-top:10px;" >{{$strnews}}</label>
                                </div>
                            </h6>
                        </td>
                        <td>
                            <div class="btn-toolbar">
                                <div class="btn-group me-2 mb-0">
                                {{-- <a class="btn btn-primary btn-sm" href="{{ route('invoice_pdf01',$invoice->id)}}">ダウンロード</a> --}}
                                {{--OK <a class="{{$clsvalue}}" href="{{ route('invoice_pdf01',$invoice->id)}}">{{$strvalue}}</a> --}}
                                </div>
                            </div>
                    <input class="{{$clsvalue}}" type="submit" id="btn_del_{{$invoice->id}}" name="btn_del_{{$invoice->id}}" id2="btn_del_{{$invoice->urgent_flg}}" value="{{$strvalue}}" >
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td><p> </p></td>
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
            <style>
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
            <script type="text/javascript">
                $('input[name^="btn_del_"]').click( function(e){
                    // alert('詳細Click');
                    var wok_id       = $(this).attr("name").replace('btn_del_', '');
                    var this_id      = $(this).attr("id");
                    var urgent_flg   = $(this).attr("id2").replace('btn_del_', '');
                    var url      = "invoicehistory/pdf/" + wok_id;
                    $('#temp_form').method = 'POST';
                    $('#temp_form').submit();
                    var popup = window.open(url,"preview","width=800, height=600, top=200, left=500 scrollbars=yes");
                    change_invoice_info( this_id        // 対象コントロール
                                        , wok_id        // invoiceテーブルのID
                                        , urgent_flg    // invoiceテーブルのurgent_flgの値
                                        );
                });
                /**
                * this_id         : 対象コントロール
                * wok_id          : invoiceテーブルのID
                *
                */
                function change_invoice_info( this_id
                                            , wok_id        // invoiceテーブルのID
                                            , urgent_flg    // invoiceテーブルのurgent_flgの値
                                            ) {

                    // console.log('urgent_flg');
                    // console.log(urgent_flg);
                    
                    // Ajax通信呼出(データファイルのアップロード)
                    $.ajax({
                    // AjaxAPI.callAjax(
                        url:'{{ route('invoicehistory.update_api') }}',
                        type:'POST',
                        dataType:'json',
                        data : { id:wok_id },
                        context: document.body,
                        headers: {'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')},
                    }).done(function(json) {
                        // console.log(json);
                        $(this).addClass("done");
                        // 1秒ごとに更新
                        // setTimeout("change_invoice_info()", 1000);
                    }).fail(function() {
                        // alert('エラーが起きました');
                        console.log('ajax error');
                    });

                    // 至急フラグ(1):通常 (2):至急
                    if(urgent_flg == 1) {
                        console.log('no repaint');
                        return;
                    } else {
                        // // 2秒ごとに更新
                        // setTimeout("change_invoice_info()", 2000);
                        // function (res) {
                        //     $('#'+this_id).effect("pulsate", { times:2 }, 500);
                        // }
                    }

 
                    // 再表示
                    // $.ajax({
                    //     url: '{{ route('invoicehistory.more') }}',
                    //     type: 'get',
                    //     dataType: 'JSON',
                    //     data : { id:wok_id,flg:urgent_flg},
                    //     // data : null,
                    // })
                    // .done(function(data){

                    //     // Ajaxで取得した要素を追加する場所を指定
                    //     // $('#table').append(data['view']);
                    //     // 取得成功
                    //     //取得jsonデータ
                    //     var data_stringify = JSON.stringify(data);
                    //     var data_json = JSON.parse(data_stringify);
                    //     console.log(data_json);                    
                    //     //jsonデータから各データを取得
                    //     // var id = "";
                    //     // var user_name = "";
                    
                    //     // if (data_json[0] != null){
                    //     //     id = data_json[0]["id"];
                    //     //     user_name = data_json[0]["user_name"];
                    //     // }
                    
                    //     // $(userid).next('input').val(user_name);

                    // })
                    // .fail(function(){
                    //     alert('repaint error');
                    // });
                };
            </script>

        </table>
    </div>

    {{-- ページネーション / pagination）の表示 --}}
    <ul class="pagination justify-content-center">
    {{-- {{ $invoices->render() }} --}}
    {{-- {{ $invoices->appends(request()->query())->links() }} --}}
        {{ $invoices->appends(request()->query())->render() }}
    </ul>

@endsection

@section('part_javascript')
{{-- ChangeSideBar("nav-item-system-user");
    <script type="text/javascript">
            $('.btn_del').click(function()
                if( !confirm('本当に削除しますか？') ){
                    /* キャンセルの時の処理 */
                    return false;
                }
                else{
                    /*　OKの時の処理 */
                    return true;
                }
            });
    </script> --}}
@endsection
