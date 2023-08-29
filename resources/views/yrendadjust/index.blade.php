{{-- @extends('layouts.app') --}}
@extends('layouts.common_index')

@section('content')
    {{-- <h2>年末調整一覧</h2> --}}
    <div class="text-right">
        <a class="btn btn-success btn-sm mr-auto" href="{{route('yrendadjust.create')}}">新規登録</a>
    </div>

    <div class="row">
        <!-- 検索エリア -->
        <!-- 検索エリア -->
    </div>

    {{-- Line --}}
    <hr class="mb-4">

    <div class="table-responsive">
    {{-- <div class="alt-table-responsive"> 狭くすると崩れる--}}

        <table class="table table-striped table-borderd table-scroll">
            <thead>
                <tr>
                    <th scope="col" class ="col-xs-3 col-md-1 text-end bg-secondary text-right">ID</th>
                    <th scope="col" class ="col-xs-3 col-md-1  bg-info text-right">@sortablelink('business_code', '事業者コード')</th>
                    <th scope="col" class ="col-xs-3 col-md-4  bg-info text-right">@sortablelink('business_name', '顧客名')</th>
                    <th scope="col" class ="col-xs-3 col-md-2  bg-info text-right">代表者</th>
                    <th scope="col" class ="col-xs-3 col-md-1  bg-info text-right">年調有無</th>
                    <th scope="col" class ="col-xs-3 col-md-1  bg-info text-right">受託人数</th>
                    <th scope="col" class ="col-xs-3 col-md-1  bg-info text-right">伝達手段</th>
                    <th scope="col" class ="col-xs-3 col-md-5  bg-info text-right">通知日</th>
                    <th scope="col" class ="col-xs-3 col-md-5  bg-info text-right">案内日</th>
                    <th scope="col" class ="col-xs-3 col-md-5  bg-info text-right">回収日</th>
                    <th scope="col" class ="col-xs-3 col-md-5  bg-info text-right">再請求</th>
                    <th scope="col" class ="col-xs-3 col-md-5  bg-warning text-right">作成日</th>
                    <th scope="col" class ="col-xs-3 col-md-1  bg-warning text-right">給与</th>
                    <th scope="col" class ="col-xs-3 col-md-1  bg-info text-right">備考01</th>
                    <th scope="col" class ="col-xs-3 col-md-1  bg-info text-right">備考02</th>
                    <th scope="col" class ="col-xs-3 col-md-1  bg-success text-right">納期特例</th>
                    <th scope="col" class ="col-xs-3 col-md-1  bg-success text-right">申請還付</th>
                    <th scope="col" class ="col-xs-3 col-md-1  bg-success text-right">納付申告</th>
                    <th scope="col" class ="col-xs-3 col-md-1  bg-success text-right">年調申告</th>
                    <th scope="col" class ="col-xs-3 col-md-1  bg-success text-right">源泉徴収</th>
                    <th scope="col" class ="col-xs-3 col-md-1  bg-success text-right">請求確認</th>
                    <th scope="col" class ="col-xs-3 col-md-1  bg-success text-right">入金確認</th>
                    <th scope="col" class ="col-xs-3 col-md-1  bg-secondary text-right">操作</th>
                </tr>
            </thead>

            <tbody>
                @if($yrendadjusts->count())
                    @foreach($yrendadjusts as $yrendadjust)
                    <tr>
                        {{-- ID --}}
                        <td>{{ $yrendadjust->id }}</td>

                        {{-- 事業者コード --}}
                        @foreach ($customers as $customers2)
                            @if ($customers2->id==$yrendadjust->custm_id)
                                <td>{{ $customers2->business_code }}</td>
                            @endif
                        @endforeach

                        {{-- 社名/氏名 --}}
                        @foreach ($customers as $customers2)
                            @if ($customers2->id==$yrendadjust->custm_id)
                                <td>{{ $customers2->business_name }}</td>
                            @endif
                        @endforeach

                        {{-- 代表者 --}}
                        @foreach ($customers as $customers2)
                            @if ($customers2->id==$yrendadjust->custm_id)
                                <td>{{ $customers2->represent_name }}</td>
                            @endif
                        @endforeach

                        {{-- /'年調の有無 1:無 2:有'--}}
                        @foreach ($loop_absence_flg as $loop_absence_flg2)
                            @if ($loop_absence_flg2['no']==$yrendadjust->absence_flg)
                                <td>{{ $loop_absence_flg2['name'] }}</td>
                            @endif
                        @endforeach

                        {{-- 受託人数 --}}
                        <td>{{ $yrendadjust->trustees_no }}</td>

                        {{-- 伝達手段 1:CHAT 2:LINE 3:MAIL 4:TELL' --}}
                        @foreach ($loop_communica_flg as $loop_communica_flg2)
                            @if ($loop_communica_flg2['no']==$yrendadjust->communica_flg)
                                <td>{{ $loop_communica_flg2['name'] }}</td>
                            @endif
                        @endforeach

                        {{-- /アナウンス日 --}}
                        @php
                            $str = "";
                            if (isset($yrendadjust->announce_at)) {
                                $str = ( new DateTime($yrendadjust->announce_at))->format('Y-m-d');
                            }
                        @endphp
                        <td>{{ $str }}</td>

                        {{-- /書類の案内日 --}}
                        @php
                            $str = "";
                            if (isset($yrendadjust->docinfor_at)) {
                                $str = ( new DateTime($yrendadjust->docinfor_at))->format('Y-m-d');
                            }
                        @endphp
                        <td>{{ $str }}</td>

                        {{-- /資料回収日 --}}
                        @php
                            $str = "";
                            if (isset($yrendadjust->doccolle_at)) {
                                $str = ( new DateTime($yrendadjust->doccolle_at))->format('Y-m-d');
                            }
                        @endphp
                        <td>{{ $str }}</td>

                        {{-- /資料再請求日 --}}
                        @php
                            $str = "";
                            if (isset($yrendadjust->rrequest_at)) {
                                $str = ( new DateTime($yrendadjust->rrequest_at))->format('Y-m-d');
                            }
                        @endphp
                        <td>{{ $str }}</td>

                        {{-- /資料作成日 --}}
                        @php
                            $str = "";
                            if (isset($yrendadjust->matecret_at)) {
                                $str = ( new DateTime($yrendadjust->matecret_at))->format('Y-m-d');
                            }
                        @endphp

                        {{-- 給与情報 1:未 2:済' --}}
                        @foreach ($loop_salary_flg as $loop_salary_flg2)
                            @if ($loop_salary_flg2['no']==$yrendadjust->salary_flg)
                                @if ($yrendadjust->salary_flg==2)
                                {{-- <div class="opacit-40">rgba(255, 255, 0, 0.4)黄色 --}}
                                <td class ="text-right" style="background-color: rgba(255, 255, 0, 0.4); ">{{ $str }}</td>
                                <td class ="text-right" style="background-color: rgba(255, 255, 0, 0.4); ">{{ $loop_salary_flg2['name'] }}</td>
                                @else
                                <td>{{ $str }}</td>
                                <td >{{ $loop_salary_flg2['name'] }}</td>
                                @endif
                            @endif
                        @endforeach

                        {{-- 備考1 --}}
                        <td>{{ $yrendadjust->remark_1 }}</td>

                        {{-- 備考2 --}}
                        <td>{{ $yrendadjust->remark_2 }}</td>

                        {{-- 納特納付書の連携 --}}
                        <td>{{ $yrendadjust->cooperat }}</td>

                        {{-- 申請すれば還付あり 1:× 2:○' --}}
                        @foreach ($loop_refund_flg as $loop_refund_flg2)
                            @if ($loop_refund_flg2['no']==$yrendadjust->refund_flg)
                                <td>{{ $loop_refund_flg2['name'] }}</td>
                            @endif
                        @endforeach

                        {{-- 0円納付申告 1:× 2:○', --}}
                        @foreach ($loop_declaration_flg as $loop_declaration_flg2)
                            @if ($loop_declaration_flg2['no']==$yrendadjust->declaration_flg)
                                <td>{{ $loop_declaration_flg2['name'] }}</td>
                            @endif
                        @endforeach

                        {{-- 年調申告 1:× 2:○', --}}
                        @foreach ($loop_annual_flg as $loop_annual_flg2)
                            @if ($loop_annual_flg2['no']==$yrendadjust->annual_flg)
                                <td>{{ $loop_annual_flg2['name'] }}</td>
                            @endif
                        @endforeach

                        {{-- 源泉徴収票 1:× 2:○', --}}
                        @foreach ($loop_withhold_flg as $loop_loop_withhold_flg2)
                            @if ($loop_loop_withhold_flg2['no']==$yrendadjust->withhold_flg)
                                <td>{{ $loop_loop_withhold_flg2['name'] }}</td>
                            @endif
                        @endforeach

                        {{-- 請求フラグ 1:× 2:○', --}}
                        @foreach ($loop_claim_flg as $loop_claim_flg2)
                            @if ($loop_claim_flg2['no']==$yrendadjust->claim_flg)
                                <td>{{ $loop_claim_flg2['name'] }}</td>
                            @endif
                        @endforeach

                        {{-- 入金確認フラグ 1:× 2:○', --}}
                        @foreach ($loop_payment_flg as $loop_payment_flg2)
                            @if ($loop_payment_flg2['no']==$yrendadjust->payment_flg)
                                <td>{{ $loop_payment_flg2['name'] }}</td>
                            @endif
                        @endforeach

                       <td>
                            <div class="btn-toolbar">
                                <div class="btn-group me-2 mb-0">
                                <a class="btn btn-primary btn-sm" href="{{ route('yrendadjust.edit',$yrendadjust->id)}}">編集</a>
                                </div>
                                <div class="btn-group me-2 mb-0">
                                    <form action="{{ route('yrendadjust.destroy', $yrendadjust->id)}}" method="POST">
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
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
                        <td><p> </p></td>
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

    </div>

     {{-- ページネーション / pagination）の表示 --}}
     <ul class="pagination justify-content-center">
        {{ $yrendadjusts->appends(request()->query())->render() }}
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
