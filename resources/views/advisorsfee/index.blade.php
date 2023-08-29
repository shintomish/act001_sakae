{{-- @extends('layouts.app') --}}
@extends('layouts.common_index')

@section('content')
    {{-- <h2>顧問料一覧</h2> --}}
    <div class="text-right">
        <a class="btn btn-success btn-sm mr-auto" href="{{route('advisorsfee.create')}}">新規登録</a>
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

                    <th scope="col" class ="col-xs-3 col-md-1 text-end bg-secondary text-right">ID</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">@sortablelink('business_code', '事業者コード')</th>
                    <th scope="col" class ="col-xs-3 col-md-4 bg-info text-right">@sortablelink('business_name', '顧客名')</th>
                    <th scope="col" class ="col-xs-3 col-md-2 bg-info text-right">代表者</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">契約</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">顧問料金</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">紹介先</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">決算月</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">法人個人</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">01月</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">02月</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">03月</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">04月</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">05月</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">06月</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">07月</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">08月</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">09月</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">10月</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">11月</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">12月</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-secondary  text-right">操作</th>
                </tr>
            </thead>

            <tbody>
                @if($advisorsfees->count())
                    @foreach($advisorsfees as $advisorsfee)
                    <tr>
                        {{-- ID --}}
                        <td>{{ $advisorsfee->id }}</td>

                        {{-- 事業者コード --}}
                        @foreach ($customers as $customers2)
                            @if ($customers2->id==$advisorsfee->custm_id)
                                <td>{{ $customers2->business_code }}</td>
                            @endif
                        @endforeach

                        {{-- 社名/氏名 --}}
                        @foreach ($customers as $customers2)
                            @if ($customers2->id==$advisorsfee->custm_id)
                                <td>{{ $customers2->business_name }}</td>
                            @endif
                        @endforeach

                        {{-- 代表者 --}}
                        @foreach ($customers as $customers2)
                            @if ($customers2->id==$advisorsfee->custm_id)
                                <td>{{ $customers2->represent_name }}</td>
                            @endif
                        @endforeach

                        {{-- 契約`active_cancel`  int(11) DEFAULT 1 COMMENT 'アクティブ/解約 1:アクティブ 2:解約--}}
                        @foreach ($customers as $customers2)
                            @if ($customers2->id==$advisorsfee->custm_id)
                                @foreach ($loop_active_cancel as $loop_active_cancel2)
                                    @if ($loop_active_cancel2['no']==$customers2->individual_class)
                                        <td>{{ $loop_active_cancel2['name'] }}</td>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                        {{-- 顧問料金 --}}
                        <td>{{ number_format($advisorsfee->advisor_fee) }}</td>

                        {{-- 紹介先 --}}
                        @foreach ($customers as $customers2)
                            @if ($customers2->id==$advisorsfee->custm_id)
                                <td>{{ $customers2->referral_destination }}</td>
                            @endif
                        @endforeach

                        {{-- //決算月 --}}
                        @foreach ($customers as $customers2)
                            @if ($customers2->id==$advisorsfee->custm_id)
                                @foreach ($loop_closing_month as $loop_closing_month2)
                                    @if ($loop_closing_month2['no']==$customers2->closing_month)
                                        <td>{{ $loop_closing_month2['name'] }}</td>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                        {{-- //法人・個人 App/Providers/AppServiceProviderのboot--}}
                        @foreach ($customers as $customers2)
                            @if ($customers2->id==$advisorsfee->custm_id)
                                @foreach ($loop_individual_class as $loop_individual_class2)
                                    @if ($loop_individual_class2['no']==$customers2->individual_class)
                                        <td>{{ $loop_individual_class2['name'] }}</td>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                        {{-- 01月 --}}
                        <td>{{ number_format($advisorsfee->fee_01) }}</td>

                        {{-- 02月 --}}
                        <td>{{ number_format($advisorsfee->fee_02) }}</td>

                        {{-- 03月 --}}
                        <td>{{ number_format($advisorsfee->fee_03) }}</td>

                        {{-- 04月 --}}
                        <td>{{ number_format($advisorsfee->fee_04) }}</td>

                        {{-- 05月 --}}
                        <td>{{ number_format($advisorsfee->fee_05) }}</td>

                        {{-- 06月 --}}
                        <td>{{ number_format($advisorsfee->fee_06) }}</td>

                        {{-- 07月 --}}
                        <td>{{ number_format($advisorsfee->fee_07) }}</td>

                        {{-- 08月 --}}
                        <td>{{ number_format($advisorsfee->fee_08) }}</td>

                        {{-- 09月 --}}
                        <td>{{ number_format($advisorsfee->fee_09) }}</td>

                        {{-- 10月 --}}
                        <td>{{ number_format($advisorsfee->fee_10) }}</td>

                        {{-- 11月 --}}
                        <td>{{ number_format($advisorsfee->fee_11) }}</td>

                        {{-- 12月 --}}
                        <td>{{ number_format($advisorsfee->fee_12) }}</td>

                        <td>
                            <div class="btn-toolbar">
                                <div class="btn-group me-2 mb-0">
                                <a class="btn btn-primary btn-sm" href="{{ route('advisorsfee.edit',$advisorsfee->id)}}">編集</a>
                                </div>
                                <div class="btn-group me-2 mb-0">
                                    <form action="{{ route('advisorsfee.destroy', $advisorsfee->id)}}" method="POST">
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
                    </tr>
                @endif

            </tbody>
        </table>

    </div>

     {{-- ページネーション / pagination）の表示 --}}
     <ul class="pagination justify-content-center">
        {{ $advisorsfees->appends(request()->query())->render() }}
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
