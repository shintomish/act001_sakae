@extends('layouts.customer')

@section('content')

    <h2>納期特例</h2>
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
        <form class="needs-validation" novalidate action="{{ route('spedelidate.store')}}" method="POST">
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
                <div class="col-2">
                    <label for=""></label>
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
                    <label for=""></label>
                </div>
                <div class="col-4">
                </div>
            </div>

            <div class="row">
                <div class="col-2 bg-info text-right">
                    <label for="officecompe">役員報酬</label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="officecompe" value="0">
                </div>

                <div class="col-2 bg-info text-right">
                    <label for="employee">従業員</label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="employee" value="0">
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
                    <label for="paymenttype">納付種別</label>
                </div>

                <div class="col-4">
                    <input type="text" class="form-control" name="paymenttype" value="{{ old('paymenttype')}}" placeholder="0円納付">
                </div>

                <div class="col-2 bg-success text-right">
                    <label for="adept_flg">達人入力</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="adept_flg" name="adept_flg">
                        @foreach ($loop_circle_cross as $loop_circle_cross2)
                            @if ($loop_circle_cross2['no']==0)
                                <option disabled value={{$loop_circle_cross2['no']}}>{{ $loop_circle_cross2['name'] }}</option>
                            @else
                                <option value={{$loop_circle_cross2['no']}}>{{ $loop_circle_cross2['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

            </div>
            <div class="row">

                <div class="col-2 bg-success text-right">
                    <label for="payslip_flg">納付書作成</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="payslip_flg" name="payslip_flg">
                        @foreach ($loop_payslip_flg as $loop_payslip_flg2)
                            @if ($loop_payslip_flg2['no']==0)
                                <option disabled value={{$loop_payslip_flg2['no']}}>{{ $loop_payslip_flg2['name'] }}</option>
                            @else
                                <option value={{$loop_payslip_flg2['no']}}>{{ $loop_payslip_flg2['name'] }}</option>
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

                <div class="col-2 bg-warning text-right">
                    <label for="paydate_att">支払日注意</label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="paydate_att" value="{{ old('paydate_att')}}" placeholder="末締め/当月末払い">
                </div>

                <div class="col-2 bg-warning text-right">
                    <label for="checklist">確認事項</label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="checklist" value="{{ old('checklist')}}">
                </div>
            </div>

            <div class="row">
                <div class="col-2 bg-success text-right">
                    <label for="chaneg_flg">役員報酬変更</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="chaneg_flg" name="chaneg_flg">
                        @foreach ($loop_payslip_flg as $loop_payslip_flg2)
                            @if ($loop_payslip_flg2['no']==0)
                                <option disabled value={{$loop_payslip_flg2['no']}}>{{ $loop_payslip_flg2['name'] }}</option>
                            @else
                                <option value={{$loop_payslip_flg2['no']}}>{{ $loop_payslip_flg2['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="col-2 bg-success text-right">
                    <label for="after_change">変更後</label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="after_change" value="{{ old('after_change')}}">
                </div>
            </div>

            <div class="row">
                <div class="col-2 bg-success text-right">
                    <label for="change_time">変更時期</label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="change_time" value="{{ old('change_time')}}">
                </div>

                <div class="col-2 bg-success text-right">
                    <label for="linkage_pay">納付書データ</label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="linkage_pay" value="{{ old('linkage_pay')}}">
                </div>
            </div>

            <hr class="mb-4">  {{-- // line --}}
            <div class="row">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary btn-lg btn-block">登録</button>
                    <a class="btn btn-primary btn-lg btn-block" href="{{route('spedelidate.input')}}">戻る</a>
                </div>
            </div>
            <hr class="mb-4">  {{-- // line --}}

        </form>
    </div>

@endsection

@section('part_javascript')

@endsection



