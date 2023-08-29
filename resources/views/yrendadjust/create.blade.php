@extends('layouts.customer')

@section('content')

    <h2>年末調整</h2>
    <div class="text-right">
        {{-- <a class="btn btn-success btn-sm mr-auto" href="{{route('customer.create')}}">新規登録</a> --}}
    </div>

    <div class="row">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form class="needs-validation" novalidate action="{{ route('yrendadjust.store')}}" method="POST">
            @csrf

        <div class="col-md-8 order-md-1">
            <h4 class="mb-3">新規追加</h4>

            <div class="row">
                <div class="col-2">

                </div>
                <div class="col-4">

                </div>

                <div class="col-2 bg-secondary text-right">
                    <label for="year">年</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="year" name="year">
                        @foreach ($loop_year_flg as $loop_year_flg2)
                            @if ($loop_year_flg2['no']==$nowyear)
                                <option selected="selected" value="{{ $loop_year_flg2['no'] }}">{{ $loop_year_flg2['name'] }}</option>
                            @else
                                <option disabled value="{{ $loop_year_flg2['no'] }}">{{ $loop_year_flg2['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-2 bg-info text-right">
                    <label for="custm_id">会社名</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="custm_id" name="custm_id">
                        @foreach($customers as $customer)
                            <option value={{$customer->id}}>{{ $customer->business_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-2 bg-info text-right">
                    <label for="absence_flg">年調の有無</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="absence_flg" name="absence_flg">
                        @foreach ($loop_absence_flg as $loop_absence_flg2)
                            @if ($loop_absence_flg2['no']==0)
                                <option disabled value={{$loop_absence_flg2['no']}}>{{ $loop_absence_flg2['name'] }}</option>
                            @else
                                <option value={{ $loop_absence_flg2['no'] }}>{{ $loop_absence_flg2['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-2 bg-info text-right">
                    <label for="trustees_no">受託人数</label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="trustees_no" value="{{ old('trustees_no')}}">
                </div>

                <div class="col-2 bg-info text-right">
                    <label for="communica_flg">伝達手段</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="communica_flg" name="communica_flg">
                        @foreach ($loop_communica_flg as $loop_communica_flg2)
                            @if ($loop_communica_flg2['no']==0)
                                <option disabled value={{$loop_communica_flg2['no']}}>{{ $loop_communica_flg2['name'] }}</option>
                            @else
                                <option value={{$loop_communica_flg2['no']}}>{{ $loop_communica_flg2['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">

                <div class="col-2 bg-info text-right">
                    <label for="announce_at">アナウンス日</label>
                </div>
                <div class="col-4">
                    <input type="date" class="form-control" name="announce_at" value="{{ old('announce_at')}}">
                </div>

                <div class="col-2 bg-info text-right">
                    <label for="filing_date">書類案内日</label>
                </div>
                <div class="col-4">
                    <input type="date" class="form-control" name="docinfor_at" value="{{ old('docinfor_at')}}">
                </div>

            </div>
            <div class="row">

                <div class="col-2 bg-info text-right">
                    <label for="doccolle_at">資料回収日</label>
                </div>
                <div class="col-4">
                    <input type="date" class="form-control" name="doccolle_at" value="{{ old('doccolle_at')}}">
                </div>

                <div class="col-2 bg-info text-right">
                    <label for="rrequest_at">資料再請求</label>
                </div>
                <div class="col-4">
                    <input type="date" class="form-control" name="rrequest_at" value="{{ old('rrequest_at')}}">
                </div>
            </div>

            <div class="row">

                <div class="col-2 bg-warning text-right">
                    <label for="matecret_at">資料作成日</label>
                </div>
                <div class="col-4">
                    <input type="date" class="form-control" name="matecret_at" value="{{ old('matecret_at')}}">
                </div>

                <div class="col-2 bg-warning text-right">
                    <label for="salary_flg">給与情報</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="salary_flg" name="salary_flg">
                        @foreach ($loop_salary_flg as $loop_salary_flg2)
                            @if ($loop_salary_flg2['no']==0)
                                <option disabled value={{$loop_salary_flg2['no']}}>{{ $loop_salary_flg2['name'] }}</option>
                            @else
                                <option value={{$loop_salary_flg2['no']}}>{{ $loop_salary_flg2['name'] }}</option>
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
                    <label for=""></label>
                </div>
                <div class="col-4">
                </div>
            </div>
            <div class="row">
                <div class="col-2 bg-info text-right">
                    <label for="remark_1">備考1</label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="remark_1" value="{{ old('remark_1')}}">
                </div>

                <div class="col-2 bg-info text-right">
                    <label for="remark_2">備考2</label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="remark_2" value="{{ old('remark_2')}}">
                </div>
            </div>

            <div class="row">
                <div class="col-2 bg-success text-right">
                    <label for="cooperat">納期特例</label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="cooperat" value="{{ old('cooperat')}}">
                </div>

                <div class="col-2">
                    <label for=""></label>
                </div>
                <div class="col-4">
                </div>
            </div>
            <div class="row">
                <div class="col-2 bg-success text-right">
                    <label for="refund_flg">申請還付</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="refund_flg" name="refund_flg">
                        @foreach ($loop_refund_flg as $loop_refund_flg2)
                            @if ($loop_refund_flg2['no']==0)
                                <option disabled value={{$loop_refund_flg2['no']}}>{{ $loop_refund_flg2['name'] }}</option>
                            @else
                                <option value={{$loop_refund_flg2['no']}}>{{ $loop_refund_flg2['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="col-2 bg-success text-right">
                    <label for="declaration_flg">0円納付申告</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="declaration_flg" name="declaration_flg">
                        @foreach ($loop_declaration_flg as $loop_declaration_flg2)
                            @if ($loop_declaration_flg2['no']==0)
                                <option disabled value={{$loop_declaration_flg2['no']}}>{{ $loop_declaration_flg2['name'] }}</option>
                            @else
                                <option value={{$loop_declaration_flg2['no']}}>{{ $loop_declaration_flg2['name'] }}</option>
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
                    <label for=""></label>
                </div>
                <div class="col-4">
                </div>
            </div>
            <div class="row">
                <div class="col-2 bg-success text-right">
                    <label for="annual_flg">年調申告</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="annual_flg" name="annual_flg">
                        @foreach ($loop_annual_flg as $loop_annual_flg2)
                            @if ($loop_annual_flg2['no']==0)
                                <option disabled value={{$loop_annual_flg2['no']}}>{{ $loop_annual_flg2['name'] }}</option>
                            @else
                                <option value={{$loop_annual_flg2['no']}}>{{ $loop_annual_flg2['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="col-2 bg-success text-right">
                    <label for="withhold_flg">源泉徴収票</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="withhold_flg" name="withhold_flg">
                        @foreach ($loop_withhold_flg as $loop_withhold_flg2)
                            @if ($loop_withhold_flg2['no']==0)
                                <option disabled value={{$loop_withhold_flg2['no']}}>{{ $loop_withhold_flg2['name'] }}</option>
                            @else
                                <option value={{$loop_withhold_flg2['no']}}>{{ $loop_withhold_flg2['name'] }}</option>
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
                    <label for=""></label>
                </div>
                <div class="col-4">
                </div>
            </div>
            <div class="row">
                <div class="col-2 bg-success text-right">
                    <label for="claim_flg">請求確認</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="claim_flg" name="claim_flg">
                        @foreach ($loop_claim_flg as $loop_claim_flg2)
                            @if ($loop_claim_flg2['no']==0)
                                <option disabled value={{$loop_claim_flg2['no']}}>{{ $loop_claim_flg2['name'] }}</option>
                            @else
                                <option value={{$loop_claim_flg2['no']}}>{{ $loop_claim_flg2['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="col-2 bg-success text-right">
                    <label for="payment_flg">入金確認</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="payment_flg" name="payment_flg">
                        @foreach ($loop_payment_flg as $loop_payment_flg2)
                            @if ($loop_payment_flg2['no']==0)
                                <option disabled value={{$loop_payment_flg2['no']}}>{{ $loop_payment_flg2['name'] }}</option>
                            @else
                                <option value={{$loop_payment_flg2['no']}}>{{ $loop_payment_flg2['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <hr class="mb-4">  {{-- // line --}}
            <div class="row">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">登録</button>
                    <a class="btn btn-primary btn-lg btn-block" href="{{route('yrendadjust.input')}}">戻る</a>
                </div>
            </div>
            <hr class="mb-4">  {{-- // line --}}

        </form>
    </div>

@endsection

@section('part_javascript')

@endsection



