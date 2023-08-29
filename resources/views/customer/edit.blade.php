{{-- @extends('layouts.app') --}}
@extends('layouts.customer')

@section('content')
    <h2>顧客情報</h2>
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
        <div class="col-md-4 order-md-2 mb-4">
        <form class="needs-validation" novalidate action="{{ route('customer.update',$customer->id)}}" method="POST">
            @csrf
            @method('PUT')

            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-success">進捗報告</span>
                {{-- <span class="badge badge-secondary badge-pill">3</span> --}}
            </h4>
            <table class="table table-striped table-borderd">
                <thead>
                    {{-- <tr>
                        <th scope="col">日付</th>
                        <th scope="col">内容</th>
                        <th scope="col">顛末</th>
                    </tr> --}}
                </thead>

                <tbody>
                    <hr class="mb-4">  {{-- // line --}}
                    <tr>
                        {{-- //処理年月日 --}}
                        <div class="row">
                            <div class="col-4 bg-primary">
                                <label for="proc_date">処理日</label>
                            </div>
                            <div class="col-8">
<input id="proc_date" type="date" class="form-control @error('proc_date') is-invalid @enderror" name="proc_date" value="{{ old('proc_date') }}" required autocomplete="proc_date">
                                {{-- <input type="date" class="form-control" name="proc_date" value=""> --}}
                            </div>
                        </div>
                        {{-- //内容（税目等）1～ --}}
                        <div class="row">
                            <div class="col-4">
                                <label for="contents_class">内容</label>
                            </div>
                            <div class="col-8">
                                <select class="custom-select d-block w-100" id="contents_class" name="contents_class">
                                    @foreach ($loop_contents_class as $loop_contents_class2)
                                    @if ($loop_contents_class2['no']==0)
                                    <option disabled value={{$loop_contents_class2['no']}}>{{ $loop_contents_class2['name'] }}</option>
                                    @else
                                    <option value={{$loop_contents_class2['no']}}>{{ $loop_contents_class2['name'] }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- //顛末 --}}
                        <div class="row">
                            <div class="col-4">
                                <label for="facts_class">顛末</label>
                            </div>
                            <div class="col-8">
                                <select class="custom-select d-block w-100" id="facts_class" name="facts_class">
                                    @foreach ($loop_facts_class as $loop_facts_class2)
                                    @if ($loop_facts_class2['no']==0)
                                    <option disabled value={{$loop_facts_class2['no']}}>{{ $loop_facts_class2['name'] }}</option>
                                    @else
                                    <option value={{$loop_facts_class2['no']}}>{{ $loop_facts_class2['name'] }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <label for=""></label>
                            </div>
                            <div class="col-8">
                            </div>
                        </div>
                    </tr>

                    @if($wokprocbooks->count())
                        @foreach($wokprocbooks as $wokprocbooks2)
                            <tr>
                                {{-- //処理年月日 --}}
                                <div class="row">
                                    <div class="col-4">
                                        <label for="proc_date1">処理日</label>
                                    </div>
                                    <div class="col-8">
                                        @php
                                            $str = "";
                                            if (isset($wokprocbooks2->proc_date)) {
                                                $str = ( new DateTime($wokprocbooks2->proc_date))->format('Y-m-d');
                                            }
                                        @endphp
                                        {{ $str }}
                                        {{-- <input type="date" class="form-control" name="proc_date" value="{{$str}}"> --}}
                                    </div>
                                </div>
                                {{-- //内容（税目等）1～ --}}
                                <div class="row">
                                    <div class="col-4">
                                        <label for="contents_class1">内容</label>
                                    </div>
                                    <div class="col-8">
                                        <select class="custom-select d-block w-100" id="contents_class1" name="contents_class1">
                                            @foreach ($loop_contents_class as $loop_contents_class2)
                                            @if ($loop_contents_class2['no']==$wokprocbooks2->contents_class)
                                            {{-- <option selected="selected" value={{$loop_contents_class2['no']}}>{{ $loop_contents_class2['name'] }}</option> --}}
                                            <option value={{$loop_contents_class2['no']}}>{{ $loop_contents_class2['name'] }}</option>
                                            @else
                                                @if ($loop_contents_class2['no']==0)
                                            <option disabled value={{$loop_contents_class2['no']}}>{{ $loop_contents_class2['name'] }}</option>
                                                @else
                                                <option disabled value={{$loop_contents_class2['no']}}>{{ $loop_contents_class2['name'] }}</option>
                                                @endif
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                {{-- //顛末 --}}
                                <div class="row">
                                    <div class="col-4">
                                        <label for="facts_class1">顛末</label>
                                    </div>
                                    <div class="col-8">
                                        <select class="custom-select d-block w-100" id="facts_class1" name="facts_class1">
                                            @foreach ($loop_facts_class as $loop_facts_class2)
                                            @if ($loop_facts_class2['no']==$wokprocbooks2->facts_class)
                                            {{-- <option selected="selected" value={{$loop_facts_class2['no']}}>{{ $loop_facts_class2['name'] }}</option> --}}
                                            <option value={{$loop_facts_class2['no']}}>{{ $loop_facts_class2['name'] }}</option>
                                            @else
                                                @if ($loop_facts_class2['no']==0)
                                            <option disabled value={{$loop_facts_class2['no']}}>{{ $loop_facts_class2['name'] }}</option>
                                                @else
                                                <option disabled value={{$loop_facts_class2['no']}}>{{ $loop_facts_class2['name'] }}</option>
                                                @endif
                                            @endif
                                        @endforeach

                                        </select>
                                    </div>
                                </div>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <th scope="col">日付</th>
                            <th scope="col">内容</th>
                            <th scope="col">顛末</th>
                        </tr>
                        <tr>
                            <td>進捗なし</td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endif

                </tbody>
            </table>
    {{-- ページネーション / pagination）の表示 2022/08/26 --}}
    <ul class="pagination justify-content-center">
        {{ $wokprocbooks->appends(request()->query())->render() }}
    </ul>

            <hr class="mb-4">  {{-- // line --}}
            <div class="row">
                <div class="col-4">
                    <label for="final_accounting_at">最終会計処理日</label>
                </div>
                <div class="col-8">
                    {{-- <input type="date" class="form-control" name="final_accounting_at" value="{{ old('final_accounting_at',date($customer->final_accounting_at))}}"> --}}
                    @php
                        $str = "";
                        if (isset($customer->final_accounting_at)) {
                            $str = ( new DateTime($customer->final_accounting_at))->format('Y-m-d');
                        }
                    @endphp
                    <input type="date" class="form-control" name="final_accounting_at" value="{{$str}}">
                </div>
            </div>
            <hr class="mb-4">  {{-- // line --}}
            <div class="row">
                <div class="col-4">
                    <label for="memo_1">Memo</label>
                </div>
                <div class="col-8">
                    <input type="text" class="form-control" name="memo_1" value="{{ old('memo_1',$customer->memo_1)}}">
                </div>
                <div class="col-4">
                    <label for="memo_2"></label>
                </div>
                <div class="col-8">
                    <input type="text" class="form-control" name="memo_2" value="{{ old('memo_2',$customer->memo_2)}}">
                </div>
                <div class="col-4">
                    <label for="memo_3"></label>
                </div>
                <div class="col-8">
                    <input type="text" class="form-control" name="memo_3" value="{{ old('memo_3',$customer->memo_3)}}">
                </div>
            </div>
        </div>

        <div class="col-md-8 order-md-1">
            <h4 class="text-success">詳細</h4>

                <div class="row">
                    <div class="col-2">
                        <label for="business_code">00:事業者コード</label>
                    </div>
                    <div class="col-4">
                        <input type="text" class="form-control" name="business_code" value="{{ old('business_code',$customer->business_code)}}">
                    </div>

                    <div class="col-2">
                        <label for="start_notification">15:開始届</label>
                    </div>
                    <div class="col-4">
                        <select class="custom-select d-block w-100" id="start_notification" name="start_notification">
                            @foreach ($loop_start_notification as $loop_start_notification2)
                                @if ($loop_start_notification2['no']==$customer->start_notification)
<option selected="selected" value={{$loop_start_notification2['no']}}>{{ $loop_start_notification2['name'] }}</option>
                                @else
                                    @if ($loop_start_notification2['no']==0)
                                <option disabled value={{$loop_start_notification2['no']}}>{{ $loop_start_notification2['name'] }}</option>
                                    @else
                                    <option value={{$loop_start_notification2['no']}}>{{ $loop_start_notification2['name'] }}</option>
                                    @endif
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
                        <input type="text" class="form-control" name="business_name" value="{{ old('business_name',$customer->business_name)}}">
                    </div>
                    {{-- <div class="col-2"> --}}
                    <div class="col-2 bg-secondary text-right">
                        <label for="transfer_notification">16:異動届</label>
                    </div>
                    <div class="col-4">
                        {{-- <select class="custom-select d-block w-100" id="transfer_notification" name="transfer_notification">
                            @foreach ($loop_transfer_notification as $loop_transfer_notification2)
                                @if ($loop_transfer_notification2['no']==$customer->transfer_notification)
                <option selected="selected" value={{$loop_transfer_notification2['no']}}>{{ $loop_transfer_notification2['name'] }}</option>
                                @else
            <option disabled value={{$loop_transfer_notification2['no']}}>{{ $loop_transfer_notification2['name'] }}</option>
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
                            @if ($loop_individual_class2['no']==$customer->individual_class)
<option selected="selected" value="{{ $loop_individual_class2['no'] }}">{{ $loop_individual_class2['name'] }}</option>
                            @else
                                @if ($loop_individual_class2['no']==0)
                                <option disabled value={{$loop_individual_class2['no']}}>{{ $loop_individual_class2['name'] }}</option>
                                @else
                                <option value="{{ $loop_individual_class2['no'] }}">{{ $loop_individual_class2['name'] }}</option>
                                @endif
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
                                @if ($loop_blue_declaration2['no']==$customer->blue_declaration)
<option selected="selected" value={{$loop_blue_declaration2['no']}}>{{ $loop_blue_declaration2['name'] }}</option>
                                @else
                                    @if ($loop_blue_declaration2['no']==0)
                                    <option disabled value={{$loop_blue_declaration2['no']}}>{{ $loop_blue_declaration2['name'] }}</option>
                                    @else
                                    <option value={{$loop_blue_declaration2['no']}}>{{ $loop_blue_declaration2['name'] }}</option>
                                    @endif
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
                                @if ($loop_closing_month2['no']==$customer->closing_month)
<option selected="selected" value={{$loop_closing_month2['no']}}>{{ $loop_closing_month2['name'] }}</option>
                                @else
                                    @if ($loop_closing_month2['no']==0)
                                    <option disabled value={{$loop_closing_month2['no']}}>{{ $loop_closing_month2['name'] }}</option>
                                    @else
                                    <option value={{$loop_closing_month2['no']}}>{{ $loop_closing_month2['name'] }}</option>
                                    @endif
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
                                @if ($loop_special_delivery_date2['no']==$customer->special_delivery_date)
<option selected="selected" value={{$loop_special_delivery_date2['no']}}>{{ $loop_special_delivery_date2['name'] }}</option>
                                @else
                                    @if ($loop_special_delivery_date2['no']==0)
                                    <option disabled value={{$loop_special_delivery_date2['no']}}>{{ $loop_special_delivery_date2['name'] }}</option>
                                    @else
                                    <option value={{$loop_special_delivery_date2['no']}}>{{ $loop_special_delivery_date2['name'] }}</option>
                                    @endif
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
                        <input type="text" class="form-control" name="represent_name" value="{{ old('represent_name',$customer->represent_name)}}">
                    </div>
                    <div class="col-2">
                        <label for="interim_payment">19:中間納付</label>
                    </div>
                    <div class="col-4">
                        <select class="custom-select d-block w-100" id="interim_payment" name="interim_payment">
                            @foreach ($loop_interim_payment as $loop_interim_payment2)
                                @if ($loop_interim_payment2['no']==$customer->interim_payment)
                    <option selected="selected" value={{$loop_interim_payment2['no']}}>{{ $loop_interim_payment2['name'] }}</option>
                                @else
                                    @if ($loop_interim_payment2['no']==0)
                                    <option disabled value={{$loop_interim_payment2['no']}}>{{ $loop_interim_payment2['name'] }}</option>
                                    @else
                                    <option value={{$loop_interim_payment2['no']}}>{{ $loop_interim_payment2['name'] }}</option>
                                    @endif
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
                        <input type="text" class="form-control" name="industry" value="{{ old('industry',$customer->industry)}}">
                    </div>

                    <div class="col-2">
                        <label for="consumption_tax">20:消費税</label>
                    </div>
                    <div class="col-4">
                        {{-- // `consumption_tax` int(11) DEFAULT 1 COMMENT '消費税 1:簡易 2:本則 3:免税', --}}
                        <select class="custom-select d-block w-100" id="consumption_tax" name="consumption_tax" onchange="changeColor(this)">
                            @foreach ($loop_consumption_tax_flg as $loop_consumption_tax_flg2)
                                @if ($loop_consumption_tax_flg2['no']==$customer->consumption_tax)
                    <option selected="selected" value={{$loop_consumption_tax_flg2['no']}}>{{ $loop_consumption_tax_flg2['name'] }}</option>
                                @else
                                    @if ($loop_consumption_tax_flg2['no']==0)
                                    <option disabled value={{$loop_consumption_tax_flg2['no']}}>{{ $loop_consumption_tax_flg2['name'] }}</option>
                                    @else
                                    <option value={{$loop_consumption_tax_flg2['no']}}>{{ $loop_consumption_tax_flg2['name'] }}</option>
                                    @endif
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
                        <input type="text" class="form-control" name="prev_sales" value="{{ old('prev_sales',$customer->prev_sales)}}">
                    </div>

                    <div class="col-2">
                        <label for="consumption_tax_filing_period">21:消費税申告期間</label>
                    </div>
                    <div class="col-4">
                        <select class="custom-select d-block w-100" id="consumption_tax_filing_period" name="consumption_tax_filing_period">
                            @foreach ($loop_consumption_tax_filing_period as $loop_consumption_tax_filing_period2)
                                @if ($loop_consumption_tax_filing_period2['no']==$customer->consumption_tax_filing_period)
<option selected="selected" value={{$loop_consumption_tax_filing_period2['no']}}>{{ $loop_consumption_tax_filing_period2['name'] }}</option>
                                @else
                                    @if ($loop_consumption_tax_filing_period2['no']==0)
            <option disabled value={{$loop_consumption_tax_filing_period2['no']}}>{{ $loop_consumption_tax_filing_period2['name'] }}</option>
                                    @else
            <option value={{$loop_consumption_tax_filing_period2['no']}}>{{ $loop_consumption_tax_filing_period2['name'] }}</option>
                                    @endif
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
                        <input type="text" class="form-control" name="prev_profit" value="{{ old('prev_profit',$customer->prev_profit)}}">
                    </div>
                    <div class="col-2">
                        <label for="advisor_fee">22:顧問料金</label>
                    </div>
                    <div class="col-4">
                        <input type="text" class="form-control" name="advisor_fee" value="{{ old('advisor_fee',$customer->advisor_fee)}}">
                    </div>

                </div>

                <div class="row">
                    <div class="col-2">
                        <label for="business_zipcode">08:郵便番号</label>
                    </div>
                    <div class="col-4">
                        <input type="text" class="form-control" name="business_zipcode" value="{{ old('business_zipcode',$customer->business_zipcode)}}">
                    </div>
                    <div class="col-2">
                        <label for="active_cancel">23:契約/解約</label>
                    </div>
                    <div class="col-4">
                        <select class="custom-select d-block w-100" id="active_cancel" name="active_cancel">
                            @foreach ($loop_active_cancel as $loop_active_cancel2)
                                @if ($loop_active_cancel2['no']===$customer->active_cancel)
<option selected="selected" value={{$loop_active_cancel2['no']}}>{{ $loop_active_cancel2['name'] }}</option>
                                @else
                                    @if ($loop_active_cancel2['no']==0)
            <option disabled value={{$loop_active_cancel2['no']}}>{{ $loop_active_cancel2['name'] }}</option>
                                    @else
                                <option value={{$loop_active_cancel2['no']}}>{{ $loop_active_cancel2['name'] }}</option>
                                    @endif
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
                        <input type="text" class="form-control" name="business_address" value="{{ old('business_address',$customer->business_address)}}">
                    </div>
                    <div class="col-2">
                        <label for="referral_destination">24:紹介先</label>
                    </div>
                    <div class="col-4">
                        <input type="text" class="form-control" name="referral_destination" value="{{ old('referral_destination',$customer->referral_destination)}}">
                    </div>

                </div>

                <div class="row">
                    <div class="col-2">
                        <label for="business_tell">10:電話番号</label>
                    </div>
                    <div class="col-4">
                    <input type="text" class="form-control" name="business_tell" value="{{ old('business_tell',$customer->business_tell)}}">
                    </div>

                    <div class="col-2">
                        <label for="notificationl_flg">25:News通知</label>
                    </div>
                    <div class="col-4">
                        <select class="custom-select d-block w-100" id="notificationl_flg" name="notificationl_flg">
                            @foreach ($loop_notificationl_flg as $loop_notificationl_flg2)
                                @if ($loop_notificationl_flg2['no']==$customer->notificationl_flg)
            <option selected="selected" value={{$customer->notificationl_flg}}>{{ $loop_notificationl_flg2['name'] }}</option>
                                @else
                                    @if ($loop_notificationl_flg2['no']==0)
                <option disabled value="{{ $loop_notificationl_flg2['no'] }}">{{ $loop_notificationl_flg2['name'] }}</option>
                                    @else
                <option value="{{ $loop_notificationl_flg2['no'] }}">{{ $loop_notificationl_flg2['name'] }}</option>
                                    @endif
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
                        <input type="text" class="form-control" name="represent_zipcode" value="{{ old('represent_zipcode',$customer->represent_zipcode)}}">
                    </div>

                    {{-- //  bill_flg              : 会計フラグ --}}
                    <div class="col-2">
                        <label for="bill_flg">26:会計</label>
                    </div>
                    <div class="col-4">
                        <select class="custom-select d-block w-100" id="bill_flg" name="bill_flg">
                            @foreach ($loop_circle_cross as $loop_circle_cross2)
                                @if ($loop_circle_cross2['no']==$customer->bill_flg)
            <option selected="selected" value={{$customer->bill_flg}}>{{ $loop_circle_cross2['name'] }}</option>
                                @else
                                    @if ($loop_circle_cross2['no']==0)
                <option disabled value="{{ $loop_circle_cross2['no'] }}">{{ $loop_circle_cross2['name'] }}</option>
                                    @else
                <option value="{{ $loop_circle_cross2['no'] }}">{{ $loop_circle_cross2['name'] }}</option>
                                    @endif
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
                        <input type="text" class="form-control" name="represent_address" value="{{ old('represent_address',$customer->represent_address)}}">
                    </div>
                    {{-- //  adept_flg             : 達人フラグ --}}
                    <div class="col-2">
                        <label for="adept_flg">27:達人</label>
                    </div>
                    <div class="col-4">
                        <select class="custom-select d-block w-100" id="adept_flg" name="adept_flg">
                            @foreach ($loop_circle_cross as $loop_circle_cross2)
                                @if ($loop_circle_cross2['no']==$customer->adept_flg)
            <option selected="selected" value={{$customer->adept_flg}}>{{ $loop_circle_cross2['name'] }}</option>
                                @else
                                    @if ($loop_circle_cross2['no']==0)
                <option disabled value="{{ $loop_circle_cross2['no'] }}">{{ $loop_circle_cross2['name'] }}</option>
                                    @else
                <option value="{{ $loop_circle_cross2['no'] }}">{{ $loop_circle_cross2['name'] }}</option>
                                    @endif
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
                        <input type="text" class="form-control" name="represent_tell" value="{{ old('represent_tell',$customer->represent_tell)}}">
                    </div>

                    {{-- //  report_flg            : 申告フラグ --}}
                    <div class="col-2">
                        <label for="confirmation_flg">28:税理士確認</label>
                    </div>
                    <div class="col-4">
                        <select class="custom-select d-block w-100" id="confirmation_flg" name="confirmation_flg">
                            @foreach ($loop_circle_cross as $loop_circle_cross2)
                                @if ($loop_circle_cross2['no']==$customer->confirmation_flg)
            <option selected="selected" value={{$customer->confirmation_flg}}>{{ $loop_circle_cross2['name'] }}</option>
                                @else
                                    @if ($loop_circle_cross2['no']==0)
                <option disabled value="{{ $loop_circle_cross2['no'] }}">{{ $loop_circle_cross2['name'] }}</option>
                                    @else
                <option value="{{ $loop_circle_cross2['no'] }}">{{ $loop_circle_cross2['name'] }}</option>
                                    @endif
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
                        <input type="text" class="form-control" name="tax_office" value="{{ old('tax_office',$customer->tax_office)}}">
                    </div>
                    {{-- //  report_flg            : 申告フラグ --}}
                    <div class="col-2">
                        <label for="report_flg">29:申告</label>
                    </div>
                    <div class="col-4">
                        <select class="custom-select d-block w-100" id="report_flg" name="report_flg">
                            @foreach ($loop_circle_cross as $loop_circle_cross2)
                                @if ($loop_circle_cross2['no']==$customer->report_flg)
            <option selected="selected" value={{$customer->report_flg}}>{{ $loop_circle_cross2['name'] }}</option>
                                @else
                                    @if ($loop_circle_cross2['no']==0)
                <option disabled value="{{ $loop_circle_cross2['no'] }}">{{ $loop_circle_cross2['name'] }}</option>
                                    @else
                <option value="{{ $loop_circle_cross2['no'] }}">{{ $loop_circle_cross2['name'] }}</option>
                                    @endif
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-2">
                    </div>
                    <div class="col-4">
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
                        <input type="text" class="form-control" name="email" value="{{ old('email',$customer->email)}}">
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

                <hr class="mb-4">  {{-- // line --}}
                <div class="row">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button class="btn btn-primary btn-lg btn-block" type="submit">保存</button>
                        {{-- 2022/09/20 btn-primary btn-lg--}}
                        <a class="btn btn-secondary btn-sm btn-block" href="{{route('customer.index')}}">戻る</a>
                    </div>
                </div>
                <hr class="mb-4">  {{-- // line --}}

            </form>
        </div>
    </div>

@endsection

@section('part_javascript')

@endsection
