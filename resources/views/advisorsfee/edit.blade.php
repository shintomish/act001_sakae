{{-- @extends('layouts.app') --}}
@extends('layouts.customer')

@section('content')
    <h2>顧問料</h2>
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
            <form class="needs-validation" novalidate action="{{ route('advisorsfee.update',$advisorsfee->id)}}" method="POST">
                @csrf
                @method('PUT')

            <h4 class="mb-3">情報</h4>

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
                        @if ($loop_year_flg2['no']==$advisorsfee->year)
                            <option selected="selected" value={{$advisorsfee->year}}>{{ $loop_year_flg2['name'] }}</option>
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
                        @foreach($customers as $customers2)
                            @if($customers2->id==$advisorsfee->custm_id)
                    <option selected="selected" value={{$advisorsfee->custm_id}}>{{ $customers2->business_name }}</option>
                            @else
                                <option disabled value={{$customers2->id}}>{{ $customers2->business_name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="col-2 bg-info text-right">
                    <label for="advisor_fee">顧問料金</label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="advisor_fee" value="{{ old('advisor_fee',$advisorsfee->advisor_fee) }}">
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
                    <label for="fee_01">01月</label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="fee_01" value="{{ old('fee_01',$advisorsfee->fee_01) }}">
                </div>

                <div class="col-2 bg-info text-right">
                    <label for="fee_02">02月</label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="fee_02" value="{{ old('fee_02',$advisorsfee->fee_02) }}">
                </div>

            </div>

            <div class="row">
                <div class="col-2 bg-info text-right">
                    <label for="fee_03">03月</label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="fee_03" value="{{ old('fee_03',$advisorsfee->fee_03) }}">
                </div>

                <div class="col-2 bg-info text-right">
                    <label for="fee_04">04月</label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="fee_04" value="{{ old('fee_04',$advisorsfee->fee_04) }}">
                </div>

            </div>

            <div class="row">
                <div class="col-2 bg-info text-right">
                    <label for="fee_05">05月</label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="fee_05" value="{{ old('fee_05',$advisorsfee->fee_05) }}">
                </div>

                <div class="col-2 bg-info text-right">
                    <label for="fee_06">06月</label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="fee_06" value="{{ old('fee_06',$advisorsfee->fee_06) }}">
                </div>

            </div>

            <div class="row">
                <div class="col-2 bg-info text-right">
                    <label for="fee_07">07月</label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="fee_07" value="{{ old('fee_07',$advisorsfee->fee_07) }}">
                </div>

                <div class="col-2 bg-info text-right">
                    <label for="fee_08">08月</label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="fee_08" value="{{ old('fee_08',$advisorsfee->fee_08) }}">
                </div>

            </div>

            <div class="row">
                <div class="col-2 bg-info text-right">
                    <label for="fee_09">09月</label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="fee_09" value="{{ old('fee_09',$advisorsfee->fee_09) }}">
                </div>

                <div class="col-2 bg-info text-right">
                    <label for="fee_10">10月</label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="fee_10" value="{{ old('fee_10',$advisorsfee->fee_10) }}">
                </div>

            </div>

            <div class="row">
                <div class="col-2 bg-info text-right">
                    <label for="fee_11">11月</label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="fee_11" value="{{ old('fee_11',$advisorsfee->fee_11) }}">
                </div>

                <div class="col-2 bg-info text-right">
                    <label for="fee_12">12月</label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="fee_12" value="{{ old('fee_12',$advisorsfee->fee_12) }}">
                </div>

            </div>

            <hr class="mb-4">  {{-- // line --}}
            <div class="row">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button class="btn btn-primary btn-lg btn-block" type="submit">保存</button>
                    <a class="btn btn-primary btn-lg btn-block" href="{{route('advisorsfee.input')}}">戻る</a>
                </div>
            </div>
            <hr class="mb-4">  {{-- // line --}}

        </form>
        </div>
    </div>

@endsection

@section('part_javascript')

@endsection
