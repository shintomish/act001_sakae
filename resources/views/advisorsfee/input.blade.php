{{-- @extends('layouts.app') --}}
@extends('layouts.api_index')

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
                    <th scope="col" class ="col-xs-3 col-md-1 text-end bg-secondary text-left">ID</th>
                    {{-- <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">@sortablelink('business_code', '事業者コード')</th> --}}
                    <th scope="col" class ="col-xs-3 col-md-4 bg-info text-right">@sortablelink('business_name', '顧客名')</th>
                    {{-- <th scope="col" class ="col-xs-3 col-md-2 bg-info text-right">代表者</th> --}}
                    {{-- <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">契約</th> --}}
                    <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">法人個人</th>
                    <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">顧問料金</th>
                    {{-- <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">紹介先</th> --}}
                    {{-- <th scope="col" class ="col-xs-3 col-md-1 bg-info text-right">決算月</th> --}}
{{-- debug --}}
@php
    // $nowmonth = 5;
@endphp
                    @if($nowmonth == 1)
                        <th scope="col" class ="col-xs-3 col-md-1 bg-warning text-right">01月</th>
                    @else
                        {{-- <th scope="col" type="hidden"  class ="col-xs-3 col-md-1 bg-info text-right">01月</th> --}}
                    @endif

                    @if($nowmonth == 2)
                        <th scope="col" class ="col-xs-3 col-md-1 bg-warning text-right">02月</th>
                    @else
                        {{-- <th scope="col" type="hidden"  class ="col-xs-3 col-md-1 bg-info text-right">02月</th> --}}
                    @endif

                    @if($nowmonth == 3)
                        <th scope="col" class ="col-xs-3 col-md-1 bg-warning text-right">03月</th>
                    @else
                        {{-- <th scope="col" type="hidden"  class ="col-xs-3 col-md-1 bg-info text-right">03月</th> --}}
                    @endif

                    @if($nowmonth == 4)
                        <th scope="col" class ="col-xs-3 col-md-1 bg-warning text-right">04月</th>
                    @else
                        {{-- <th scope="col" type="hidden"  class ="col-xs-3 col-md-1 bg-info text-right">04月</th> --}}
                    @endif

                    @if($nowmonth == 5)
                        <th scope="col" class ="col-xs-3 col-md-1 bg-warning text-right">05月</th>
                    @else
                        {{-- <th scope="col" type="hidden"  class ="col-xs-3 col-md-1 bg-info text-right">05月</th> --}}
                    @endif

                    @if($nowmonth == 6)
                        <th scope="col" class ="col-xs-3 col-md-1 bg-warning text-right">06月</th>
                    @else
                        {{-- <th scope="col" type="hidden"  class ="col-xs-3 col-md-1 bg-info text-right">06月</th> --}}
                    @endif

                    @if($nowmonth == 7)
                        <th scope="col" class ="col-xs-3 col-md-1 bg-warning text-right">07月</th>
                    @else
                        {{-- <th scope="col" type="hidden"  class ="col-xs-3 col-md-1 bg-info text-right">07月</th> --}}
                    @endif

                    @if($nowmonth == 8)
                        <th scope="col" class ="col-xs-3 col-md-1 bg-warning text-right">08月</th>
                    @else
                        {{-- <th scope="col" type="hidden"  class ="col-xs-3 col-md-1 bg-info text-right">08月</th> --}}
                    @endif

                    @if($nowmonth == 9)
                        <th scope="col" class ="col-xs-3 col-md-1 bg-warning text-right">09月</th>
                    @else
                        {{-- <th scope="col" type="hidden"  class ="col-xs-3 col-md-1 bg-info text-right">09月</th> --}}
                    @endif

                    @if($nowmonth == 10)
                        <th scope="col" class ="col-xs-3 col-md-1 bg-warning text-right">10月</th>
                    @else
                        {{-- <th scope="col" type="hidden"  class ="col-xs-3 col-md-1 bg-info text-right">10月</th> --}}
                    @endif

                    @if($nowmonth == 11)
                        <th scope="col" class ="col-xs-3 col-md-1 bg-warning text-right">10月</th>
                    @else
                        {{-- <th scope="col" type="hidden"  class ="col-xs-3 col-md-1 bg-info text-right">11月</th> --}}
                    @endif

                    @if($nowmonth == 12)
                        <th scope="col" class ="col-xs-3 col-md-1 bg-warning text-right">12月</th>
                    @else
                        {{-- <th scope="col" type="hidden"  class ="col-xs-3 col-md-1 bg-info text-right">12月</th> --}}
                    @endif

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
                        {{-- @foreach ($customers as $customers2)
                            @if ($customers2->id==$advisorsfee->custm_id)
                                <td>{{ $customers2->business_code }}</td>
                            @endif
                        @endforeach --}}

                        {{-- 社名/氏名 --}}
                        @foreach ($customers as $customers2)
                            @if ($customers2->id==$advisorsfee->custm_id)
                                <td>{{ $customers2->business_name }}</td>
                            @endif
                        @endforeach

                        {{-- 代表者 --}}
                        {{-- @foreach ($customers as $customers2)
                            @if ($customers2->id==$advisorsfee->custm_id)
                                <td>{{ $customers2->represent_name }}</td>
                            @endif
                        @endforeach --}}

                        {{-- 契約`active_cancel`  int(11) DEFAULT 1 COMMENT 'アクティブ/解約 1:アクティブ 2:解約--}}
                        {{-- @foreach ($customers as $customers2)
                            @if ($customers2->id==$advisorsfee->custm_id)
                                @foreach ($loop_active_cancel as $loop_active_cancel2)
                                    @if ($loop_active_cancel2['no']==$customers2->individual_class)
                                        <td>{{ $loop_active_cancel2['name'] }}</td>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach --}}

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

                        {{-- 顧問料金 --}}
                        {{-- <td>{{ number_format($advisorsfee->advisor_fee) }}</td> --}}
                        @php
                            $str = sprintf("%s", $advisorsfee->advisor_fee);
                        @endphp
                        <td>
    <input type="text" class="form-control"  id="advisor_fee_{{$advisorsfee->id}}" name="advisor_fee_{{$advisorsfee->id}}" value="{{$str}}">
                        </td>
                        {{-- 紹介先 --}}
                        {{-- @foreach ($customers as $customers2)
                            @if ($customers2->id==$advisorsfee->custm_id)
                                <td>{{ $customers2->referral_destination }}</td>
                            @endif
                        @endforeach --}}

                        {{-- //決算月 --}}
                        {{-- @foreach ($customers as $customers2)
                            @if ($customers2->id==$advisorsfee->custm_id)
                                @foreach ($loop_closing_month as $loop_closing_month2)
                                    @if ($loop_closing_month2['no']==$customers2->closing_month)
                                        <td>{{ $loop_closing_month2['name'] }}</td>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach --}}

                        {{-- 01月 --}}
                        {{-- <td>{{ number_format($advisorsfee->fee_01) }}</td> --}}
                        @if($nowmonth == 1)
                            @php
                                $str = sprintf("%s", $advisorsfee->fee_01);
                            @endphp
                            <td>
                    <input type="text" class="form-control"  id="fee_01_{{$advisorsfee->id}}" name="fee_01_{{$advisorsfee->id}}" value="{{$str}}">
                            </td>
                        @else
                                {{-- {{ number_format($advisorsfee->fee_01) }} --}}
                        @endif

                        {{-- 02月 --}}
                        {{-- <td>{{ number_format($advisorsfee->fee_02) }}</td> --}}
                        @if($nowmonth == 2)
                            @php
                                $str = sprintf("%s", $advisorsfee->fee_02);
                            @endphp
                            <td>
                    <input type="text" class="form-control"  id="fee_02_{{$advisorsfee->id}}" name="fee_02_{{$advisorsfee->id}}" value="{{$str}}">
                            </td>
                        @else
                                {{-- {{ number_format($advisorsfee->fee_02) }} --}}
                        @endif

                        {{-- 03月 --}}
                        {{-- <td>{{ number_format($advisorsfee->fee_03) }}</td> --}}
                        @if($nowmonth == 3)
                            @php
                                $str = sprintf("%s", $advisorsfee->fee_03);
                            @endphp
                            <td>
                    <input type="text" class="form-control"  id="fee_03_{{$advisorsfee->id}}" name="fee_03_{{$advisorsfee->id}}" value="{{$str}}">
                            </td>
                        @else
                                {{-- {{ number_format($advisorsfee->fee_03) }} --}}
                        @endif

                        {{-- 04月 --}}
                        {{-- <td>{{ number_format($advisorsfee->fee_04) }}</td> --}}
                        @if($nowmonth == 4)
                            @php
                                $str = sprintf("%s", $advisorsfee->fee_04);
                            @endphp
                            <td>
                    <input type="text" class="form-control"  id="fee_04_{{$advisorsfee->id}}" name="fee_04_{{$advisorsfee->id}}" value="{{$str}}">
                            </td>
                        @else
                                {{-- {{ number_format($advisorsfee->fee_04) }} --}}
                        @endif

                        {{-- 05月 --}}
                        {{-- <td>{{ number_format($advisorsfee->fee_05) }}</td> --}}
                        @if($nowmonth == 5)
                            @php
                                $str = sprintf("%s", $advisorsfee->fee_05);
                            @endphp
                            <td>
                    <input type="text" class="form-control"  id="fee_05_{{$advisorsfee->id}}" name="fee_05_{{$advisorsfee->id}}" value="{{$str}}">
                            </td>
                        @else
                                {{-- {{ number_format($advisorsfee->fee_05) }} --}}
                        @endif

                        {{-- 06月 --}}
                        {{-- <td>{{ number_format($advisorsfee->fee_06) }}</td> --}}
                        @if($nowmonth == 6)
                            @php
                                $str = sprintf("%s", $advisorsfee->fee_06);
                            @endphp
                            <td>
                    <input type="text" class="form-control"  id="fee_06_{{$advisorsfee->id}}" name="fee_06_{{$advisorsfee->id}}" value="{{$str}}">
                            </td>
                        @else
                                {{-- {{ number_format($advisorsfee->fee_06) }} --}}
                        @endif

                        {{-- 07月 --}}
                        {{-- <td>{{ number_format($advisorsfee->fee_07) }}</td> --}}
                        @if($nowmonth == 7)
                            @php
                                $str = sprintf("%s", $advisorsfee->fee_07);
                            @endphp
                            <td>
                    <input type="text" class="form-control"  id="fee_07_{{$advisorsfee->id}}" name="fee_07_{{$advisorsfee->id}}" value="{{$str}}">
                            </td>
                        @else
                                {{-- {{ number_format($advisorsfee->fee_07) }} --}}
                        @endif

                        {{-- 08月 --}}
                        {{-- <td>{{ number_format($advisorsfee->fee_08) }}</td> --}}
                        @if($nowmonth == 8)
                            @php
                                $str = sprintf("%s", $advisorsfee->fee_08);
                            @endphp
                            <td>
                    <input type="text" class="form-control"  id="fee_08_{{$advisorsfee->id}}" name="fee_08_{{$advisorsfee->id}}" value="{{$str}}">
                            </td>
                        @else
                                {{-- {{ number_format($advisorsfee->fee_08) }} --}}
                        @endif

                        {{-- 09月 --}}
                        {{-- <td>{{ number_format($advisorsfee->fee_09) }}</td> --}}
                        @if($nowmonth == 9)
                            @php
                                $str = sprintf("%s", $advisorsfee->fee_09);
                            @endphp
                            <td>
                    <input type="text" class="form-control"  id="fee_09_{{$advisorsfee->id}}" name="fee_09_{{$advisorsfee->id}}" value="{{$str}}">
                            </td>
                        @else
                                {{-- {{ number_format($advisorsfee->fee_09) }} --}}
                        @endif

                        {{-- 10月 --}}
                        {{-- <td>{{ number_format($advisorsfee->fee_10) }}</td> --}}
                        @if($nowmonth == 10)
                            @php
                                $str = sprintf("%s", $advisorsfee->fee_10);
                            @endphp
                            <td>
                    <input type="text" class="form-control"  id="fee_10_{{$advisorsfee->id}}" name="fee_10_{{$advisorsfee->id}}" value="{{$str}}">
                            </td>
                        @else
                                {{-- {{ number_format($advisorsfee->fee_10) }} --}}
                        @endif

                        {{-- 11月 --}}
                        {{-- <td>{{ number_format($advisorsfee->fee_11) }}</td> --}}
                        @if($nowmonth == 11)
                            @php
                                $str = sprintf("%s", $advisorsfee->fee_11);
                            @endphp
                            <td>
                    <input type="text" class="form-control"  id="fee_11_{{$advisorsfee->id}}" name="fee_11_{{$advisorsfee->id}}" value="{{$str}}">
                            </td>
                        @else
                                {{-- {{ number_format($advisorsfee->fee_11) }} --}}
                        @endif

                        {{-- 12月 --}}
                        {{-- <td>{{ number_format($advisorsfee->fee_12) }}</td> --}}
                        @if($nowmonth == 12)
                            @php
                                $str = sprintf("%s", $advisorsfee->fee_12);
                            @endphp
                            <td>
                    <input type="text" class="form-control"  id="fee_12_{{$advisorsfee->id}}" name="fee_12_{{$advisorsfee->id}}" value="{{$str}}">
                            </td>
                        @else
                                {{-- {{ number_format($advisorsfee->fee_12) }} --}}
                        @endif

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
                        {{-- <td><p> </p></td> --}}
                        {{-- <td><p> </p></td> --}}
                        {{-- <td><p> </p></td> --}}
                        {{-- <td><p> </p></td> --}}
                        {{-- <td><p> </p></td> --}}
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
                <script type="text/javascript">
                    //---------------------------------------------------------------
                    //--顧問料テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="advisor_fee_"]').change( function(e){
                        // alert('顧問料Click');
                        var wok_id           = $(this).attr("name").replace('advisor_fee_', '');
                        var advisor_fee      = $(this).val();
                        var this_id          = $(this).attr("id");
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // advisorsfeeテーブルのID
                                                , advisor_fee   // 顧問料
                                                , null          // 顧問料01
                                                , null          // 顧問料02
                                                , null          // 顧問料03
                                                , null          // 顧問料04
                                                , null          // 顧問料05
                                                , null          // 顧問料06
                                                , null          // 顧問料07
                                                , null          // 顧問料08
                                                , null          // 顧問料09
                                                , null          // 顧問料10
                                                , null          // 顧問料11
                                                , null          // 顧問料12
                                            );
                    });

                    //---------------------------------------------------------------
                    //--顧問料01テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="fee_01_"]').change( function(e){
                        // alert('顧問料01Click');
                        var wok_id           = $(this).attr("name").replace('fee_01_', '');
                        var fee_01           = $(this).val();
                        var this_id          = $(this).attr("id");
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // advisorsfeeテーブルのID
                                                , null          // 顧問料
                                                , fee_01        // 顧問料01
                                                , null          // 顧問料02
                                                , null          // 顧問料03
                                                , null          // 顧問料04
                                                , null          // 顧問料05
                                                , null          // 顧問料06
                                                , null          // 顧問料07
                                                , null          // 顧問料08
                                                , null          // 顧問料09
                                                , null          // 顧問料10
                                                , null          // 顧問料11
                                                , null          // 顧問料12
                                            );
                    });

                    //---------------------------------------------------------------
                    //--顧問料02テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="fee_02_"]').change( function(e){
                        // alert('顧問料02Click');
                        var wok_id           = $(this).attr("name").replace('fee_02_', '');
                        var fee_02           = $(this).val();
                        var this_id          = $(this).attr("id");
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // advisorsfeeテーブルのID
                                                , null          // 顧問料
                                                , null          // 顧問料01
                                                , fee_02        // 顧問料02
                                                , null          // 顧問料03
                                                , null          // 顧問料04
                                                , null          // 顧問料05
                                                , null          // 顧問料06
                                                , null          // 顧問料07
                                                , null          // 顧問料08
                                                , null          // 顧問料09
                                                , null          // 顧問料10
                                                , null          // 顧問料11
                                                , null          // 顧問料12
                                            );
                    });

                    //---------------------------------------------------------------
                    //--顧問料03テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="fee_03_"]').change( function(e){
                        // alert('顧問料03Click');
                        var wok_id           = $(this).attr("name").replace('fee_03_', '');
                        var fee_03           = $(this).val();
                        var this_id          = $(this).attr("id");
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // advisorsfeeテーブルのID
                                                , null          // 顧問料
                                                , null          // 顧問料01
                                                , null          // 顧問料02
                                                , fee_03        // 顧問料03
                                                , null          // 顧問料04
                                                , null          // 顧問料05
                                                , null          // 顧問料06
                                                , null          // 顧問料07
                                                , null          // 顧問料08
                                                , null          // 顧問料09
                                                , null          // 顧問料10
                                                , null          // 顧問料11
                                                , null          // 顧問料12
                                            );
                    });

                    //---------------------------------------------------------------
                    //--顧問料04テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="fee_04_"]').change( function(e){
                        // alert('顧問料04Click');
                        var wok_id           = $(this).attr("name").replace('fee_04_', '');
                        var fee_04           = $(this).val();
                        var this_id          = $(this).attr("id");
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // advisorsfeeテーブルのID
                                                , null          // 顧問料
                                                , null          // 顧問料01
                                                , null          // 顧問料02
                                                , null          // 顧問料03
                                                , fee_04        // 顧問料04
                                                , null          // 顧問料05
                                                , null          // 顧問料06
                                                , null          // 顧問料07
                                                , null          // 顧問料08
                                                , null          // 顧問料09
                                                , null          // 顧問料10
                                                , null          // 顧問料11
                                                , null          // 顧問料12
                                            );
                    });

                    //---------------------------------------------------------------
                    //--顧問料05テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="fee_05_"]').change( function(e){
                        // alert('顧問料05Click');
                        var wok_id           = $(this).attr("name").replace('fee_05_', '');
                        var fee_05           = $(this).val();
                        var this_id          = $(this).attr("id");
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // advisorsfeeテーブルのID
                                                , null          // 顧問料
                                                , null          // 顧問料01
                                                , null          // 顧問料02
                                                , null          // 顧問料03
                                                , null          // 顧問料04
                                                , fee_05        // 顧問料05
                                                , null          // 顧問料06
                                                , null          // 顧問料07
                                                , null          // 顧問料08
                                                , null          // 顧問料09
                                                , null          // 顧問料10
                                                , null          // 顧問料11
                                                , null          // 顧問料12
                                            );
                    });

                    //---------------------------------------------------------------
                    //--顧問料06テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="fee_06_"]').change( function(e){
                        // alert('顧問料06Click');
                        var wok_id           = $(this).attr("name").replace('fee_06_', '');
                        var fee_06           = $(this).val();
                        var this_id          = $(this).attr("id");
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // advisorsfeeテーブルのID
                                                , null          // 顧問料
                                                , null          // 顧問料01
                                                , null          // 顧問料02
                                                , null          // 顧問料03
                                                , null          // 顧問料04
                                                , null          // 顧問料05
                                                , fee_06        // 顧問料06
                                                , null          // 顧問料07
                                                , null          // 顧問料08
                                                , null          // 顧問料09
                                                , null          // 顧問料10
                                                , null          // 顧問料11
                                                , null          // 顧問料12
                                            );
                    });

                    //---------------------------------------------------------------
                    //--顧問料07テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="fee_07_"]').change( function(e){
                        // alert('顧問料07Click');
                        var wok_id           = $(this).attr("name").replace('fee_07_', '');
                        var fee_07           = $(this).val();
                        var this_id          = $(this).attr("id");
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // advisorsfeeテーブルのID
                                                , null          // 顧問料
                                                , null          // 顧問料01
                                                , null          // 顧問料02
                                                , null          // 顧問料03
                                                , null          // 顧問料04
                                                , null          // 顧問料05
                                                , null          // 顧問料06
                                                , fee_07        // 顧問料07
                                                , null          // 顧問料08
                                                , null          // 顧問料09
                                                , null          // 顧問料10
                                                , null          // 顧問料11
                                                , null          // 顧問料12
                                            );
                    });

                    //---------------------------------------------------------------
                    //--顧問料08テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="fee_08_"]').change( function(e){
                        // alert('顧問料08Click');
                        var wok_id           = $(this).attr("name").replace('fee_08_', '');
                        var fee_08           = $(this).val();
                        var this_id          = $(this).attr("id");
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // advisorsfeeテーブルのID
                                                , null          // 顧問料
                                                , null          // 顧問料01
                                                , null          // 顧問料02
                                                , null          // 顧問料03
                                                , null          // 顧問料04
                                                , null          // 顧問料05
                                                , null          // 顧問料06
                                                , null          // 顧問料07
                                                , fee_08        // 顧問料08
                                                , null          // 顧問料09
                                                , null          // 顧問料10
                                                , null          // 顧問料11
                                                , null          // 顧問料12
                                            );
                    });

                    //---------------------------------------------------------------
                    //--顧問料09テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="fee_09_"]').change( function(e){
                        // alert('顧問料09Click');
                        var wok_id           = $(this).attr("name").replace('fee_09_', '');
                        var fee_09           = $(this).val();
                        var this_id          = $(this).attr("id");
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // advisorsfeeテーブルのID
                                                , null          // 顧問料
                                                , null          // 顧問料01
                                                , null          // 顧問料02
                                                , null          // 顧問料03
                                                , null          // 顧問料04
                                                , null          // 顧問料05
                                                , null          // 顧問料06
                                                , null          // 顧問料07
                                                , null          // 顧問料08
                                                , fee_09        // 顧問料09
                                                , null          // 顧問料10
                                                , null          // 顧問料11
                                                , null          // 顧問料12
                                            );
                    });

                    //---------------------------------------------------------------
                    //--顧問料10テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="fee_10_"]').change( function(e){
                        // alert('顧問料10Click');
                        var wok_id           = $(this).attr("name").replace('fee_10_', '');
                        var fee_10           = $(this).val();
                        var this_id          = $(this).attr("id");
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // advisorsfeeテーブルのID
                                                , null          // 顧問料
                                                , null          // 顧問料01
                                                , null          // 顧問料02
                                                , null          // 顧問料03
                                                , null          // 顧問料04
                                                , null          // 顧問料05
                                                , null          // 顧問料06
                                                , null          // 顧問料07
                                                , null          // 顧問料08
                                                , null          // 顧問料09
                                                , fee_10        // 顧問料10
                                                , null          // 顧問料11
                                                , null          // 顧問料12
                                            );
                    });

                    //---------------------------------------------------------------
                    //--顧問料11テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="fee_11_"]').change( function(e){
                        // alert('顧問料11Click');
                        var wok_id           = $(this).attr("name").replace('fee_11_', '');
                        var fee_11           = $(this).val();
                        var this_id          = $(this).attr("id");
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // advisorsfeeテーブルのID
                                                , null          // 顧問料
                                                , null          // 顧問料01
                                                , null          // 顧問料02
                                                , null          // 顧問料03
                                                , null          // 顧問料04
                                                , null          // 顧問料05
                                                , null          // 顧問料06
                                                , null          // 顧問料07
                                                , null          // 顧問料08
                                                , null          // 顧問料09
                                                , null          // 顧問料10
                                                , fee_11        // 顧問料11
                                                , null          // 顧問料12
                                            );
                    });

                    //---------------------------------------------------------------
                    //--顧問料12テキストボックスイベントハンドラ
                    //---------------------------------------------------------------
                    $('input[name^="fee_12_"]').change( function(e){
                        // alert('顧問料12Click');
                        var wok_id           = $(this).attr("name").replace('fee_12_', '');
                        var fee_12           = $(this).val();
                        var this_id          = $(this).attr("id");
                        change_wokproc_info(     this_id        // 対象コントロール
                                                , wok_id        // advisorsfeeテーブルのID
                                                , null          // 顧問料
                                                , null          // 顧問料01
                                                , null          // 顧問料02
                                                , null          // 顧問料03
                                                , null          // 顧問料04
                                                , null          // 顧問料05
                                                , null          // 顧問料06
                                                , null          // 顧問料07
                                                , null          // 顧問料08
                                                , null          // 顧問料09
                                                , null          // 顧問料10
                                                , null          // 顧問料11
                                                , fee_12        // 顧問料12
                                            );
                    });

                    /**
                    * this_id         : 対象コントロール
                    * wok_id          : advisorsfeeテーブルのID
                    * advisor_fee     : 顧問料
                    * fee_01          : 顧問料01
                    * fee_02          : 顧問料02
                    * fee_03          : 顧問料03
                    *
                    */
                    function change_wokproc_info(  this_id
                                                , wok_id        // wok_id  advisorsfeeテーブルのID
                                                , advisor_fee   // 顧問料
                                                , fee_01     // 顧問料01
                                                , fee_02     // 顧問料02
                                                , fee_03     // 顧問料03
                                                , fee_04     // 顧問料04
                                                , fee_05     // 顧問料05
                                                , fee_06     // 顧問料06
                                                , fee_07     // 顧問料07
                                                , fee_08     // 顧問料08
                                                , fee_09     // 顧問料09
                                                , fee_10     // 顧問料10
                                                , fee_11     // 顧問料11
                                                , fee_12     // 顧問料12
                                              ){
                            var reqData = new FormData();
                                                        reqData.append( "id"              , wok_id   );
                            if( null != advisor_fee   ) reqData.append( "advisor_fee"     , advisor_fee   );
                            if( null != fee_01        ) reqData.append( "fee_01"          , fee_01   );
                            if( null != fee_02        ) reqData.append( "fee_02"          , fee_02   );
                            if( null != fee_03        ) reqData.append( "fee_03"          , fee_03   );
                            if( null != fee_04        ) reqData.append( "fee_04"          , fee_04   );
                            if( null != fee_05        ) reqData.append( "fee_05"          , fee_05   );
                            if( null != fee_06        ) reqData.append( "fee_06"          , fee_06   );
                            if( null != fee_07        ) reqData.append( "fee_07"          , fee_07   );
                            if( null != fee_08        ) reqData.append( "fee_08"          , fee_08   );
                            if( null != fee_09        ) reqData.append( "fee_09"          , fee_09   );
                            if( null != fee_10        ) reqData.append( "fee_10"          , fee_10   );
                            if( null != fee_11        ) reqData.append( "fee_11"          , fee_11   );
                            if( null != fee_12        ) reqData.append( "fee_12"          , fee_12   );

                            console.log(advisor_fee);
                            // console.log(filing_date);

                            // Ajax通信呼出(データファイルのアップロード)
                            AjaxAPI.callAjax(
                                "{{ route('advisorsfee.update_api') }}",
                                reqData,
                                function (res) {
                                    $('#'+this_id).effect("pulsate", { times:2 }, 500);

                                }
                            )
                    };
                </script>

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
