@extends('layouts.customer')

@section('content')

    <h2>顧客の新規追加</h2>
    <div class="text-right">
        {{-- <a class="btn btn-success btn-sm mr-auto" href="{{route('customer.create')}}">新規登録</a> --}}
    </div>
    @if ($errors->any())
        <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        </div>
    @endif
    <div class="row">

        <div class="col-md-8 order-md-1">
            <form class="needs-validation" novalidate action="{{ route('customer.store')}}" method="POST">
                @csrf
            <h4 class="mb-3">情報</h4>

            <div class="row">
                <div class="col-2">
                    <label for="business_code">00:事業者コード</label>
                </div>
                <div class="col-4">
                    <input id="business_code" type="business_code" class="form-control @error('business_code') is-invalid @enderror" name="business_code" value="{{ old('business_code') }}" required autocomplete="business_code">
                </div>

                <div class="col-2">
                    <label for="start_notification">15:開始届</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="start_notification" name="start_notification">
                        @foreach ($loop_start_notification as $loop_start_notification2)
                            @if ($loop_start_notification2['no']==0)
<option disabled value={{$loop_start_notification2['no']}}>{{ $loop_start_notification2['name'] }}</option>
                            @else
<option value={{$loop_start_notification2['no']}}>{{ $loop_start_notification2['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for="business_name">01:会社名</label>
                </div>
                <div class="col-4">
                    <input id="business_name" type="business_name" class="form-control @error('business_name') is-invalid @enderror" name="business_name" value="{{ old('business_name') }}" required autocomplete="business_name">
                </div>
                {{-- <div class="col-2">
                    <label for="transfer_notification">16:異動届</label>
                </div> --}}
                <div class="col-2 bg-secondary text-right">
                    <label for="transfer_notification">16:異動届</label>
                </div>
                <div class="col-4">
                    {{-- <select class="custom-select d-block w-100" id="transfer_notification" name="transfer_notification">
                        @foreach ($loop_transfer_notification as $loop_transfer_notification2)
                            @if ($loop_transfer_notification2['no']==0)
<option disabled value={{$loop_transfer_notification2['no']}}>{{ $loop_transfer_notification2['name'] }}</option>
                            @else
<option value={{$loop_transfer_notification2['no']}}>{{ $loop_transfer_notification2['name'] }}</option>
                            @endif
                        @endforeach
                    </select> --}}
                </div>

            </div>

            <div class="row">
                <div class="col-2">
                    <label for="individual_class">02:法人/個人</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="individual_class" name="individual_class">
                        {{-- //法人・個人 App/Providers/AppServiceProviderのboot--}}
                        @foreach ($loop_individual_class as $loop_individual_class2)
                        @if ($loop_individual_class2['no']==0)
<option disabled value="{{ $loop_individual_class2['no'] }}">{{ $loop_individual_class2['name'] }}</option>
                        @else
<option value="{{ $loop_individual_class2['no'] }}">{{ $loop_individual_class2['name'] }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>

                <div class="col-2">
                    <label for="blue_declaration">17:青色申告</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="blue_declaration" name="blue_declaration">
                        @foreach ($loop_blue_declaration as $loop_blue_declaration2)
                            @if ($loop_blue_declaration2['no']==0)
<option disabled value={{$loop_blue_declaration2['no']}}>{{ $loop_blue_declaration2['name'] }}</option>
                            @else
<option value={{$loop_blue_declaration2['no']}}>{{ $loop_blue_declaration2['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for="closing_month">03:決算月</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="closing_month" name="closing_month">
                        @foreach ($loop_closing_month as $loop_closing_month2)
                            @if ($loop_closing_month2['no']==0)
<option disabled value={{$loop_closing_month2['no']}}>{{ $loop_closing_month2['name'] }}</option>
                            @else
<option value={{$loop_closing_month2['no']}}>{{ $loop_closing_month2['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="col-2">
                    <label for="special_delivery_date">18:納期特例</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="special_delivery_date" name="special_delivery_date">
                        @foreach ($loop_special_delivery_date as $loop_special_delivery_date2)
                            @if ($loop_special_delivery_date2['no']==0)
<option disabled value={{$loop_special_delivery_date2['no']}}>{{ $loop_special_delivery_date2['name'] }}</option>
                            @else
<option value={{$loop_special_delivery_date2['no']}}>{{ $loop_special_delivery_date2['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="row">
                <div class="col-2">
                    <label for="represent_name">04:代表者名</label>
                </div>
                <div class="col-4">
                    <input id="represent_name" type="represent_name" class="form-control @error('represent_name') is-invalid @enderror" name="represent_name" value="{{ old('represent_name') }}" required autocomplete="represent_name">
                </div>
                <div class="col-2">
                    <label for="interim_payment">19:中間納付</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="interim_payment" name="interim_payment">
                        @foreach ($loop_interim_payment as $loop_interim_payment2)
                            @if ($loop_interim_payment2['no']==0)
<option disabled value={{$loop_interim_payment2['no']}}>{{ $loop_interim_payment2['name'] }}</option>
                            @else
<option value={{$loop_interim_payment2['no']}}>{{ $loop_interim_payment2['name'] }}</option>
                            @endif
                        @endforeach

                    </select>
                </div>

            </div>

            <div class="row">
                <div class="col-2">
                    <label for="industry">05:業種</label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="industry" value="{{ old('industry')}}">
                </div>

                <div class="col-2">
                    <label for="consumption_tax">20:消費税</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="consumption_tax" name="consumption_tax" onchange="changeColor(this)">
                        @foreach ($loop_consumption_tax_flg as $loop_consumption_tax_flg2)
                            @if ($loop_consumption_tax_flg2['no']==0)
                    <option disabled value={{$loop_consumption_tax_flg2['no']}}>{{ $loop_consumption_tax_flg2['name'] }}</option>
                            @else
                    <option value={{$loop_consumption_tax_flg2['no']}}>{{ $loop_consumption_tax_flg2['name'] }}</option>
                            @endif
                        @endforeach

                    </select>
                </div>

            </div>

            <div class="row">
                <div class="col-2">
                    <label for="prev_sales">06:前期売上</label>
                </div>
                <div class="col-4">
<input type="text" class="form-control" name="prev_sales" value="{{ old('prev_sales')}}">
                </div>

                <div class="col-2">
                    <label for="consumption_tax_filing_period">21:消費税申告期間</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="consumption_tax_filing_period" name="consumption_tax_filing_period">
                        @foreach ($loop_consumption_tax_filing_period as $loop_consumption_tax_filing_period2)
                            @if ($loop_consumption_tax_filing_period2['no']==0)
<option disabled value={{$loop_consumption_tax_filing_period2['no']}}>{{ $loop_consumption_tax_filing_period2['name'] }}</option>
                            @else
<option value={{$loop_consumption_tax_filing_period2['no']}}>{{ $loop_consumption_tax_filing_period2['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="row">
                <div class="col-2">
                    <label for="prev_profit">07:前期利益</label>
                </div>
                <div class="col-4">
<input type="text" class="form-control" name="prev_profit" value="{{ old('prev_profit')}}">
                </div>
                <div class="col-2">
                    <label for="advisor_fee">22:顧問料金</label>
                </div>
                <div class="col-4">
<input type="text" class="form-control" name="advisor_fee" value="{{ old('advisor_fee')}}">
                </div>

            </div>

            <div class="row">
                <div class="col-2">
                    <label for="business_zipcode">08:郵便番号</label>
                </div>
                <div class="col-4">
<input type="text" class="form-control" name="business_zipcode" value="{{ old('business_zipcode')}}">
                </div>
                <div class="col-2">
                    <label for="active_cancel">23:契約/解約</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="active_cancel" name="active_cancel">
                        @foreach ($loop_active_cancel as $loop_active_cancel2)
                            @if ($loop_active_cancel2['no']===0)
<option disabled value={{$loop_active_cancel2['no']}}>{{ $loop_active_cancel2['name'] }}</option>
                            @else
<option value={{$loop_active_cancel2['no']}}>{{ $loop_active_cancel2['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="row">
                <div class="col-2">
                    <label for="business_address">09:本社住所</label>
                </div>
                <div class="col-4">
<input type="text" class="form-control" name="business_address" value="{{ old('business_address')}}">
                </div>

                <div class="col-2">
                    <label for="referral_destination">24:紹介先</label>
                </div>
                <div class="col-4">
<input type="text" class="form-control" name="referral_destination" value="{{ old('referral_destination')}}">
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for="business_tell">10:電話番号</label>
                </div>
                <div class="col-4">
<input type="text" class="form-control" name="business_tell" value="{{ old('business_tell')}}">
                </div>

                <div class="col-2">
                    <label for="notificationl_flg">25:News通知</label>
                </div>
                <div class="col-4">
                    {{-- //notificationl_flg 通知しない(1):通知する(2) --}}
                    <select class="custom-select d-block w-100" id="notificationl_flg" name="notificationl_flg">
                        @foreach ($loop_notificationl_flg as $loop_notificationl_flg2)
                            @if ($loop_notificationl_flg2['no']==0)
                    <option disabled value={{$loop_notificationl_flg2['no']}}>{{ $loop_notificationl_flg2['name'] }}</option>
                            @else
                    <option value={{$loop_notificationl_flg2['no']}}>{{ $loop_notificationl_flg2['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="row">
                <div class="col-2">
                    <label for="represent_zipcode">11:郵便番号</label>
                </div>
                <div class="col-4">
<input type="text" class="form-control" name="represent_zipcode" value="{{ old('represent_zipcode')}}">
                </div>

                {{-- //  bill_flg              : 会計フラグ --}}
                <div class="col-2">
                    <label for="bill_flg">26:会計</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="bill_flg" name="bill_flg">
                        @foreach ($loop_circle_cross as $loop_circle_cross2)
                            @if ($loop_circle_cross2['no']==0)
        <option disabled value="{{ $loop_circle_cross2['no'] }}">{{ $loop_circle_cross2['name'] }}</option>
                            @else
        <option value="{{ $loop_circle_cross2['no'] }}">{{ $loop_circle_cross2['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for="represent_address">12:代表住所</label>
                </div>
                <div class="col-4">
<input type="text" class="form-control" name="represent_address" value="{{ old('represent_address')}}">
                </div>
                    {{-- //  adept_flg             : 達人フラグ --}}
                    <div class="col-2">
                        <label for="adept_flg">27:達人</label>
                    </div>
                    <div class="col-4">
                        <select class="custom-select d-block w-100" id="adept_flg" name="adept_flg">
                            @foreach ($loop_circle_cross as $loop_circle_cross2)
                                @if ($loop_circle_cross2['no']==0)
            <option disabled value="{{ $loop_circle_cross2['no'] }}">{{ $loop_circle_cross2['name'] }}</option>
                                @else
            <option value="{{ $loop_circle_cross2['no'] }}">{{ $loop_circle_cross2['name'] }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for="represent_tell">13:電話番号</label>
                </div>
                <div class="col-4">
<input type="text" class="form-control" name="represent_tell" value="{{ old('represent_tell')}}">
                </div>
                {{-- //  confirmation_flg      : 税理士確認フラグ --}}
                <div class="col-2">
                    <label for="confirmation_flg">28:税理士確認</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="confirmation_flg" name="confirmation_flg">
                        @foreach ($loop_circle_cross as $loop_circle_cross2)

                            @if ($loop_circle_cross2['no']==0)
        <option disabled value="{{ $loop_circle_cross2['no'] }}">{{ $loop_circle_cross2['name'] }}</option>
                            @else
        <option value="{{ $loop_circle_cross2['no'] }}">{{ $loop_circle_cross2['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for="tax_office">14:税務署</label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="tax_office" value="{{ old('tax_office')}}">
                </div>
                {{-- //  report_flg            : 申告フラグ --}}
                <div class="col-2">
                    <label for="report_flg">29:申告</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="report_flg" name="report_flg">
                        @foreach ($loop_circle_cross as $loop_circle_cross2)
                            @if ($loop_circle_cross2['no']==0)
        <option disabled value="{{ $loop_circle_cross2['no'] }}">{{ $loop_circle_cross2['name'] }}</option>
                            @else
        <option value="{{ $loop_circle_cross2['no'] }}">{{ $loop_circle_cross2['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for=""></label>
                </div>
                <div class="col-4">
                </div>

                <div class="col-2">
                    <label for="email">30:E-Mail</label>
                </div>
                <div class="col-4">
<input type="text" class="form-control" name="email" value="{{ old('email')}}">
                </div>
            </div>

            {{-- 2022/05/20 消費税 `consumption_tax` int(11)  1:本則 2:簡易(赤) 3:免税', --}}
            <script>
                function changeColor(consumption_tax){
                    if( consumption_tax.value == 2 ){
                        consumption_tax.style.color = 'red';
                    }else{
                        consumption_tax.style.color = '';
                    }
                }
            </script>

            <script type="text/javascript">
                //---------------------------------------------------------------
                //--法人／個人プルダウンイベントハンドラ
                //---------------------------------------------------------------
                $('select[name^="individual_class"]').change( function(e){
                    // alert('法人／個人Click');
                    var this_id           = $(this).attr("id");
                    var individual_class  = $("#"+this_id + " option:selected").val();
                    change_form_month(        this_id            // 対象コントロール
                                            , individual_class   // 法人／個人
                                            , closing_month      // 決算月
                                            , interim_payment    // 中間申告
                                    );
                });

                //---------------------------------------------------------------
                //--決算月プルダウンイベントハンドラ
                //---------------------------------------------------------------
                $('select[name^="closing_month"]').change( function(e){
                    // alert('決算月Click');
                    var this_id          = $(this).attr("id");
                    var closing_month     = $("#"+this_id + " option:selected").val();
                    change_form_month(         this_id            // 対象コントロール
                                            , individual_class   // 法人／個人
                                            , closing_month      // 決算月
                                            , interim_payment    // 中間申告
                                    );
                });

                //---------------------------------------------------------------
                //--中間申告プルダウンイベントハンドラ
                //---------------------------------------------------------------
                $('select[name^="interim_payment"]').change( function(e){
                    // alert('中間申告Click');
                    var this_id          = $(this).attr("id");
                    var interim_payment  = $("#"+this_id + " option:selected").val();
                    change_form_month(         this_id            // 対象コントロール
                                            , individual_class   // 法人／個人
                                            , closing_month      // 決算月
                                            , interim_payment    // 中間申告
                                    );
                });

                /**
                * this_id          : 対象コントロール
                * individual_class : 法人／個人
                * closing_month    : 決算月
                * interim_payment  : 中間申告
                *
                */
                function change_form_month(
                        this_id
                        , individual_class   // 法人／個人
                        , closing_month      // 決算月
                        , interim_payment    // 中間申告
                ){
                    const amonth = closing_month;
                    if(individual_class == 2) {     //個人
                        // alert('individual_class');
                        document.getElementById("closing_month").value = 13;  //「確定申告」
                        document.getElementById("interim_payment").value = 7; //「7月」
                    }
                    // if(individual_class == 1) {     //法人
                        // alert('closing_month');
                        // alert(closing_month);
                        if(closing_month == 1) {
                            document.getElementById("closing_month").value = 1;     //「1月」
                            document.getElementById("interim_payment").value = 8;   //「8月」
                        }
                        if(closing_month == 2 ) {
        document.getElementById("interim_payment").value = 10;   //「8月」
                        }
                        if(closing_month == 3 ) {
        document.getElementById("interim_payment").value = 11;   //「8月」
                        }
                        if(closing_month == 4 ) {
        document.getElementById("interim_payment").value = 12;   //「8月」
                        }
                        if ((closing_month >= 5) && (closing_month <= 12) ) {
        document.getElementById("interim_payment").value = (amonth - 4);   //「8月」
                        }
                    // }

                };


            </script>

            <hr class="mb-4">  {{-- // line --}}
            <div class="row">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">登録</button>
                    <a class="btn btn-primary btn-lg btn-block" href="{{route('customer.index')}}">戻る</a>
                </div>
            </div>
            <hr class="mb-4">  {{-- // line --}}

        </form>
    </div>

@endsection

@section('part_javascript')

@endsection



