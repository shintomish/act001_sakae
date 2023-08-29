{{-- @extends('layouts.upload') -> 日付検索用--}}
@extends('layouts.upload_index')

@section('content')
    {{-- <h2>アップロードユーザー一覧</h2> --}}

    {{-- <div class="table-responsive"> --}}
        {{-- <div class="btn-toolbar">
            <div class="btn-group ml-auto">
                <a class="btn btn-success btn-sm ms-auto" href="{{route('user.create')}}">新規登録</a>
            </div>
        </div> --}}
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
            .fixed03{
                color: red;
                font-weight: bold;
            }
        </style>

        {{-- <table class="table table-striped table-borderd"> --}}
        {{-- <table class="table table-striped table-borderd table_sticky"> --}}
        {{-- table-responsive text-nowrap add scope=row 2022/11/09--}}
        <table class="table table-responsive text-nowrap table-striped table-borderd table_sticky">
            <thead>
                <tr>
                    <th scope="col" class="fixed01">顧客ID</th>
                    <th scope="col" class="fixed01">@sortablelink('business_name', '顧客名')</th>
                    {{-- 2023/08/25  --}}
                    {{-- <th scope="col" class="fixed01">@sortablelink('updated_at', '受信日')</th> --}}
                    <th scope="col" class="fixed01">@sortablelink('updated_at', '更新日')</th>
                    <th scope="col" class="fixed01">@sortablelink('yearmonth','年月')</th>
                    <th scope="col" class="fixed01">@sortablelink('check_flg','ファイル有無')</th>
                    <th scope="col" class="fixed01">@sortablelink('prime_flg','優先順位')</th>
                    {{-- //20232/08/18 --}}
                    {{-- <th scope="col" class ="fixed01">　　 メ　　　モ　　　欄 　　</th> --}}
                    <th scope="col" class ="fixed01">@sortablelink('foldername','フォルダ名')</th>
                </tr>
            </thead>
            <tbody>
                @if($uploadusers->count())
                    @foreach($uploadusers as $uploaduser)
                    <tr>
                        @php
                            $str  = sprintf("%04d", $uploaduser->customer_id);
                        @endphp
                        {{-- <td class="text-end">{{ number_format($uploaduser->customer_id) }}</td> --}}
                        <td class="">{{ $str }}</td>
                        @php
                            if($uploaduser->prime_flg == 4 ||$uploaduser->prime_flg == 5){
                                $colvalue = "color:red";
                            }else{
                                $colvalue = "";
                            }
                        @endphp
                        <td style={{ $colvalue }} id="bname_{{$uploaduser->id}}" name="bname_{{$uploaduser->id}}" >
                            {{ $uploaduser->business_name }}
                        </td>
                        {{-- <td>{{ $uploaduser->created_at }}</td> 2022/12/16 --}}

                        <td>{{ $uploaduser->updated_at }}</td>
                        <td>{{ $uploaduser->yearmonth }}</td>

                        {{-- ファイル無し(1):ファイル有り(2) --}}
                        @foreach ($loop_file_check_flg as $loop_file_check_flg2)
                            @if ($loop_file_check_flg2['no']==$uploaduser->check_flg)
                                <td>{{ $loop_file_check_flg2['name'] }}</td>
                            @endif
                        @endforeach

                        {{-- 優先順位 低(1):中(2):高(3) --}}
                        <div >
                        <td >
                        <select id="prime_flg_{{$uploaduser->id}}" name="prime_flg_{{$uploaduser->id}}">
                            <option value="1" {{ $uploaduser->prime_flg == 1 ? 'selected' : '' }}>―</option>
                            <option value="2" {{ $uploaduser->prime_flg == 2 ? 'selected' : '' }}>低</option>
                            <option value="3" {{ $uploaduser->prime_flg == 3 ? 'selected' : '' }}>中</option>
                            <option class="fixed03" value="4" {{ $uploaduser->prime_flg == 4 ? 'selected' : '' }}>高</option>
                            {{-- 2022/12/05 --}}
                            <option class="fixed03" value="5" {{ $uploaduser->prime_flg == 5 ? 'selected' : '' }}>急</option>
                        </select>
                        </td>
                        </div>

                        {{-- //20232/08/25 反映させないので不要 --}}
                        {{-- @php
                            $str = "";
                            if (isset($uploaduser->txt_memo)) {
                                $str = $uploaduser->txt_memo;
                            }
                        @endphp --}}
                        {{-- <td> --}}
        {{-- <textarea class="row-1" id="memo_{{$uploaduser->id}}" name="memo_{{$uploaduser->id}}" disabled>{{$str}}</textarea> --}}
{{-- <input type="text" class="form-control" id="memo_{{$uploaduser->id}}" name="memo_{{$uploaduser->id}}" value="{{$str}}"> --}}
                            {{-- {{ $str }} --}}
                        {{-- </td> --}}
                        {{-- //20232/08/25 反映させないので不要 --}}

                        <td>
                            <form action="{{ route('filemngpost' ) }}" method="POST">
                                @csrf
                                <div class="btn-toolbar">
                                    <div class="btn-group me-2 mb-0">
                                        <input type="hidden" name="name" value ="{{ $uploaduser->customer_id }}">
                                        <input type="submit" class="btn btn-primary btn-sm" value="{{ $uploaduser->foldername }}">
                                        {{-- <input type="hidden"  name="_token" value="{{ csrf_token() }}"> --}}
                                    </div>
                                </div>

                            </form>
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
                        {{-- //20232/08/18 --}}
                        {{-- <td><p> </p></td>    --}}
                    </tr>
                @endif
                <style>
                    /** ３行ピッタシに調整 */
                    .row-1 {
                        height: calc( 1.6em * 1 );
                        line-height: 1.6;
                        width: 200px;
                    }
                </style>
                <script type="text/javascript">
                    // 2023/08/18
                    //---------------------------------------------------------------
                    //--メモテキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('textarea[name^="memo_"]').change( function(e){
                        // alert('メモClick');
                        var wok_id           = $(this).attr("name").replace('memo_', '');
                        var this_id          = $(this).attr("id");
                        var txt_memo         = $(this).val();
                        change_uploaduser_info(   this_id       // 対象コントロール
                                                , wok_id        // uploaduserテーブルのID
                                                , txt_memo      // メモ
                                                , null          // 優先順位
                                                );
                    });

                    //---------------------------------------------------------------
                    //--優先順位プルダウンイベントハンドラ
                    //---------------------------------------------------------------
                    $('select[name^="prime_flg_"]').change( function(e){
                        // alert('優先順位Click');
                        var wok_id           = $(this).attr("name").replace('prime_flg_', '');
                        var this_id          = $(this).attr("id");
                        var prime_flg        = $("#"+this_id + " option:selected").val();
                        var selname          = "prime_flg_" + wok_id;
                        var buiname          = "bname_" + wok_id;
                        // alert(selname);
                        // alert(buiname);
                        if(prime_flg == 0){
                            document.getElementById(selname).style.color="";
                            // document.getElementById(buiname).style.color="";
                        }
                        if(prime_flg == 1){
                            document.getElementById(selname).style.color="";
                            // document.getElementById(buiname).style.color="";
                        }
                        if(prime_flg == 2){
                            document.getElementById(selname).style.color="";
                            // document.getElementById(buiname).style.color="";
                        }
                        if(prime_flg == 3){
                            document.getElementById(selname).style.color="";
                            // document.getElementById(buiname).style.color="red";
                        }
                        if(prime_flg == 4){
                            document.getElementById(selname).style.color="red";
                            // document.getElementById(buiname).style.color="red";
                        }
                        if(prime_flg == 5){
                            document.getElementById(selname).style.color="red";
                            // document.getElementById(buiname).style.color="red";
                        }
                        change_uploaduser_info(   this_id       // 対象コントロール
                                                , wok_id        // uploaduserテーブルのID
                                                , null          // メモ
                                                , prime_flg     // 優先順位
                                            );
                    });

                    /**
                    * this_id         : 対象コントロール
                    * wok_id          : uploaduserテーブルのID
                    * txt_memo        : メモ
                    * prime_flg       : 優先順位
                    *
                    */
                    function change_uploaduser_info(  this_id
                                                , wok_id           // wok_id  uploaduserテーブルのID
                                                , txt_memo         // メモ
                                                , prime_flg        // 優先順位
                                                )
                    {
                        var reqData = new FormData();
                                                    reqData.append( "id"           , wok_id    );
                        if( null != txt_memo      ) reqData.append( "txt_memo"     , txt_memo );
                        if( null != prime_flg     ) reqData.append( "prime_flg"    , prime_flg );

                        // console.log(txt_memo);
                        // console.log(prime_flg);

                        // Ajax通信呼出(データファイルのアップロード)
                        AjaxAPI.callAjax(
                            "{{ route('uploaduser.update_api') }}",
                            reqData,
                            function (res) {
                                $('#'+this_id).effect("pulsate", { times:2 }, 500);
                            }
                        )
                    };

                </script>
            </tbody>
        </table>
    {{-- </div> --}}

    {{-- ページネーション / pagination）の表示 --}}
    <ul class="pagination justify-content-center">
        {{-- {{ $imageuploads->appends(request()->query())->links() }} --}}
        {{ $uploadusers->appends(request()->query())->render() }}
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
