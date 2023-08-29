{{-- @extends('layouts.app') --}}
@extends('layouts.customer')

@section('content')
    <h2>スケジュール</h2>
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
            <form class="needs-validation" novalidate action="{{ route('schedule.update',$schedule->id)}}" method="POST">
                @csrf
                @method('PUT')

            <h4 class="mb-3">情報</h4>

            <div class="row">
                <div class="col-2">
                    <label for="businm_no">業務名</label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="businm_no" value="{{ old('businm_no',$schedule->businm_no) }}">
                </div>

                <div class="col-2">
                    <label for="year">年</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="year" name="year">
                        @foreach ($loop_year_flg as $loop_year_flg2)
                        @if ($loop_year_flg2['no']==$schedule->year)
                        <option selected="selected" value={{$schedule->year}}>{{ $loop_year_flg2['name'] }}</option>
                        @else
                            <option disabled value="{{ $loop_year_flg2['no'] }}">{{ $loop_year_flg2['name'] }}</option>
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
                <div class="col-2">
                    <label for="custm_id">会社名</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="custm_id" name="custm_id">
                        @foreach($customers as $customers2)
                            @if($customers2->id==$schedule->custm_id)
                    <option selected="selected" value={{$schedule->custm_id}}>{{ $customers2->business_name }}</option>
                            @else
                                <option disabled value={{$customers2->id}}>{{ $customers2->business_name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

            </div>

            <hr class="mb-4">  {{-- // line --}}

            <div class="row">
                <div class="col-2">
                    <label for="decision_01">01月</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="decision_01" name="decision_01">
                    @foreach ($loop_decision_flg as $loop_decision_flg2)
                        @if ($loop_decision_flg2['no']==$schedule->decision_01)
                            <option selected="selected" value={{$schedule->decision_01}}>{{ $loop_decision_flg2['name'] }}</option>
                        @else
                            <option value="{{ $loop_decision_flg2['no'] }}">{{ $loop_decision_flg2['name'] }}</option>
                        @endif
                    @endforeach
                    </select>
                </div>

                <div class="col-2">
                    <label for="decision_02">02月</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="decision_02" name="decision_02">
                        @foreach ($loop_decision_flg as $loop_decision_flg2)
                            @if ($loop_decision_flg2['no']==$schedule->decision_02)
                                <option selected="selected" value={{$schedule->decision_02}}>{{ $loop_decision_flg2['name'] }}</option>
                            @else
                                <option value="{{ $loop_decision_flg2['no'] }}">{{ $loop_decision_flg2['name'] }}</option>
                            @endif
                        @endforeach
                        </select>
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for="decision_03">03月</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="decision_03" name="decision_03">
                        @foreach ($loop_decision_flg as $loop_decision_flg2)
                            @if ($loop_decision_flg2['no']==$schedule->decision_03)
                                <option selected="selected" value={{$schedule->decision_03}}>{{ $loop_decision_flg2['name'] }}</option>
                            @else
                                <option value="{{ $loop_decision_flg2['no'] }}">{{ $loop_decision_flg2['name'] }}</option>
                            @endif
                        @endforeach
                        </select>
                </div>

                <div class="col-2">
                    <label for="decision_04">04月</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="decision_04" name="decision_04">
                        @foreach ($loop_decision_flg as $loop_decision_flg2)
                            @if ($loop_decision_flg2['no']==$schedule->decision_04)
                                <option selected="selected" value={{$schedule->decision_04}}>{{ $loop_decision_flg2['name'] }}</option>
                            @else
                                <option value="{{ $loop_decision_flg2['no'] }}">{{ $loop_decision_flg2['name'] }}</option>
                            @endif
                        @endforeach
                        </select>
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for="decision_05">05月</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="decision_05" name="decision_05">
                        @foreach ($loop_decision_flg as $loop_decision_flg2)
                            @if ($loop_decision_flg2['no']==$schedule->decision_05)
                                <option selected="selected" value={{$schedule->decision_05}}>{{ $loop_decision_flg2['name'] }}</option>
                            @else
                                <option value="{{ $loop_decision_flg2['no'] }}">{{ $loop_decision_flg2['name'] }}</option>
                            @endif
                        @endforeach
                        </select>
                </div>

                <div class="col-2">
                    <label for="decision_06">06月</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="decision_06" name="decision_06">
                        @foreach ($loop_decision_flg as $loop_decision_flg2)
                            @if ($loop_decision_flg2['no']==$schedule->decision_06)
                                <option selected="selected" value={{$schedule->decision_06}}>{{ $loop_decision_flg2['name'] }}</option>
                            @else
                                <option value="{{ $loop_decision_flg2['no'] }}">{{ $loop_decision_flg2['name'] }}</option>
                            @endif
                        @endforeach
                        </select>
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for="decision_07">07月</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="decision_07" name="decision_07">
                        @foreach ($loop_decision_flg as $loop_decision_flg2)
                            @if ($loop_decision_flg2['no']==$schedule->decision_07)
                                <option selected="selected" value={{$schedule->decision_07}}>{{ $loop_decision_flg2['name'] }}</option>
                            @else
                                <option value="{{ $loop_decision_flg2['no'] }}">{{ $loop_decision_flg2['name'] }}</option>
                            @endif
                        @endforeach
                        </select>
                </div>

                <div class="col-2">
                    <label for="decision_08">08月</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="decision_08" name="decision_08">
                        @foreach ($loop_decision_flg as $loop_decision_flg2)
                            @if ($loop_decision_flg2['no']==$schedule->decision_08)
                                <option selected="selected" value={{$schedule->decision_08}}>{{ $loop_decision_flg2['name'] }}</option>
                            @else
                                <option value="{{ $loop_decision_flg2['no'] }}">{{ $loop_decision_flg2['name'] }}</option>
                            @endif
                        @endforeach
                        </select>
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for="decision_09">09月</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="decision_09" name="decision_09">
                        @foreach ($loop_decision_flg as $loop_decision_flg2)
                            @if ($loop_decision_flg2['no']==$schedule->decision_09)
                                <option selected="selected" value={{$schedule->decision_09}}>{{ $loop_decision_flg2['name'] }}</option>
                            @else
                                <option value="{{ $loop_decision_flg2['no'] }}">{{ $loop_decision_flg2['name'] }}</option>
                            @endif
                        @endforeach
                        </select>
                </div>

                <div class="col-2">
                    <label for="decision_10">10月</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="decision_10" name="decision_10">
                        @foreach ($loop_decision_flg as $loop_decision_flg2)
                            @if ($loop_decision_flg2['no']==$schedule->decision_10)
                                <option selected="selected" value={{$schedule->decision_10}}>{{ $loop_decision_flg2['name'] }}</option>
                            @else
                                <option value="{{ $loop_decision_flg2['no'] }}">{{ $loop_decision_flg2['name'] }}</option>
                            @endif
                        @endforeach
                        </select>
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for="decision_11">11月</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="decision_11" name="decision_11">
                        @foreach ($loop_decision_flg as $loop_decision_flg2)
                            @if ($loop_decision_flg2['no']==$schedule->decision_11)
                                <option selected="selected" value={{$schedule->decision_11}}>{{ $loop_decision_flg2['name'] }}</option>
                            @else
                                <option value="{{ $loop_decision_flg2['no'] }}">{{ $loop_decision_flg2['name'] }}</option>
                            @endif
                        @endforeach
                        </select>
                </div>

                <div class="col-2">
                    <label for="decision_12">12月</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="decision_12" name="decision_12">
                        @foreach ($loop_decision_flg as $loop_decision_flg2)
                            @if ($loop_decision_flg2['no']==$schedule->decision_12)
                                <option selected="selected" value={{$schedule->decision_12}}>{{ $loop_decision_flg2['name'] }}</option>
                            @else
                                <option value="{{ $loop_decision_flg2['no'] }}">{{ $loop_decision_flg2['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <hr class="mb-4">  {{-- // line --}}
            <div class="row">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button class="btn btn-primary btn-lg btn-block" type="submit">保存</button>
                    <a class="btn btn-primary btn-lg btn-block" href="{{route('schedule.index')}}">戻る</a>
                </div>
            </div>
            <hr class="mb-4">  {{-- // line --}}

        </form>
        </div>
    </div>

@endsection

@section('part_javascript')

@endsection
