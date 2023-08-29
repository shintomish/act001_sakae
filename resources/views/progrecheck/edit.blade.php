{{-- @extends('layouts.app') --}}
@extends('layouts.customer')

@section('content')
    <h2>進捗チェック</h2>
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
            <form class="needs-validation" novalidate action="{{ route('progrecheck.update',$progrecheck->id)}}" method="POST">
                @csrf
                @method('PUT')

            <h4 class="mb-3">情報</h4>

            <div class="row">
                <div class="col-2">
                    <label for="businm_no">業務名</label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="businm_no" value="{{ $progrecheck->businm_no }}">
                </div>

                <div class="col-2">
                    <label for="year">年</label>
                </div>
                <div class="col-4">
                    {{-- @foreach ($loop_year_flg as $loop_year_flg2)
                        @if ($loop_year_flg2['no']==$progrecheck->year)
                            <input type="text"  class="form-control" name="year" value="{{ $progrecheck->year }}">
                        @endif
                    @endforeach --}}
                    <select class="custom-select d-block w-100" id="year" name="year">
                        @foreach ($loop_year_flg as $loop_year_flg2)
                            @if ($loop_year_flg2['no']==$progrecheck->year)
            <option selected="selected" value={{$loop_year_flg2['no']}}>{{ $loop_year_flg2['name'] }}</option>
                            @else
                                <option disabled value={{$loop_year_flg2['no']}}>{{ $loop_year_flg2['name'] }}</option>
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
                    @foreach($customers as $customers2)
                        @if($customers2->id==$progrecheck->custm_id)
                            <input type="text" disabled class="form-control" name="custm_id" value="{{ $customers2->business_name }}">
                        @endif
                    @endforeach
                </div>

            </div>

            <hr class="mb-4">  {{-- // line --}}

            <div class="row">
                <div class="col-2">
                    <label for="check_01">01月</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="check_01" name="check_01">
                    @foreach ($loop_check_flg as $loop_check_flg2)
                        @if ($loop_check_flg2['no']==$progrecheck->check_01)
                            <option selected="selected" value={{$progrecheck->check_01}}>{{ $loop_check_flg2['name'] }}</option>
                        @else
                            <option value="{{ $loop_check_flg2['no'] }}">{{ $loop_check_flg2['name'] }}</option>
                        @endif
                    @endforeach
                    </select>
                </div>

                <div class="col-2">
                    <label for="check_02">02月</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="check_02" name="check_02">
                        @foreach ($loop_check_flg as $loop_check_flg2)
                            @if ($loop_check_flg2['no']==$progrecheck->check_02)
                                <option selected="selected" value={{$progrecheck->check_02}}>{{ $loop_check_flg2['name'] }}</option>
                            @else
                                <option value="{{ $loop_check_flg2['no'] }}">{{ $loop_check_flg2['name'] }}</option>
                            @endif
                        @endforeach
                        </select>
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for="check_03">03月</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="check_03" name="check_03">
                        @foreach ($loop_check_flg as $loop_check_flg2)
                            @if ($loop_check_flg2['no']==$progrecheck->check_03)
                                <option selected="selected" value={{$progrecheck->check_03}}>{{ $loop_check_flg2['name'] }}</option>
                            @else
                                <option value="{{ $loop_check_flg2['no'] }}">{{ $loop_check_flg2['name'] }}</option>
                            @endif
                        @endforeach
                        </select>
                </div>

                <div class="col-2">
                    <label for="check_04">04月</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="check_04" name="check_04">
                        @foreach ($loop_check_flg as $loop_check_flg2)
                            @if ($loop_check_flg2['no']==$progrecheck->check_04)
                                <option selected="selected" value={{$progrecheck->check_04}}>{{ $loop_check_flg2['name'] }}</option>
                            @else
                                <option value="{{ $loop_check_flg2['no'] }}">{{ $loop_check_flg2['name'] }}</option>
                            @endif
                        @endforeach
                        </select>
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for="check_05">05月</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="check_05" name="check_05">
                        @foreach ($loop_check_flg as $loop_check_flg2)
                            @if ($loop_check_flg2['no']==$progrecheck->check_05)
                                <option selected="selected" value={{$progrecheck->check_05}}>{{ $loop_check_flg2['name'] }}</option>
                            @else
                                <option value="{{ $loop_check_flg2['no'] }}">{{ $loop_check_flg2['name'] }}</option>
                            @endif
                        @endforeach
                        </select>
                </div>

                <div class="col-2">
                    <label for="check_06">06月</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="check_06" name="check_06">
                        @foreach ($loop_check_flg as $loop_check_flg2)
                            @if ($loop_check_flg2['no']==$progrecheck->check_06)
                                <option selected="selected" value={{$progrecheck->check_06}}>{{ $loop_check_flg2['name'] }}</option>
                            @else
                                <option value="{{ $loop_check_flg2['no'] }}">{{ $loop_check_flg2['name'] }}</option>
                            @endif
                        @endforeach
                        </select>
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for="check_07">07月</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="check_07" name="check_07">
                        @foreach ($loop_check_flg as $loop_check_flg2)
                            @if ($loop_check_flg2['no']==$progrecheck->check_07)
                                <option selected="selected" value={{$progrecheck->check_07}}>{{ $loop_check_flg2['name'] }}</option>
                            @else
                                <option value="{{ $loop_check_flg2['no'] }}">{{ $loop_check_flg2['name'] }}</option>
                            @endif
                        @endforeach
                        </select>
                </div>

                <div class="col-2">
                    <label for="check_08">08月</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="check_08" name="check_08">
                        @foreach ($loop_check_flg as $loop_check_flg2)
                            @if ($loop_check_flg2['no']==$progrecheck->check_08)
                                <option selected="selected" value={{$progrecheck->check_08}}>{{ $loop_check_flg2['name'] }}</option>
                            @else
                                <option value="{{ $loop_check_flg2['no'] }}">{{ $loop_check_flg2['name'] }}</option>
                            @endif
                        @endforeach
                        </select>
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for="check_09">09月</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="check_09" name="check_09">
                        @foreach ($loop_check_flg as $loop_check_flg2)
                            @if ($loop_check_flg2['no']==$progrecheck->check_09)
                                <option selected="selected" value={{$progrecheck->check_09}}>{{ $loop_check_flg2['name'] }}</option>
                            @else
                                <option value="{{ $loop_check_flg2['no'] }}">{{ $loop_check_flg2['name'] }}</option>
                            @endif
                        @endforeach
                        </select>
                </div>

                <div class="col-2">
                    <label for="check_10">10月</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="check_10" name="check_10">
                        @foreach ($loop_check_flg as $loop_check_flg2)
                            @if ($loop_check_flg2['no']==$progrecheck->check_10)
                                <option selected="selected" value={{$progrecheck->check_10}}>{{ $loop_check_flg2['name'] }}</option>
                            @else
                                <option value="{{ $loop_check_flg2['no'] }}">{{ $loop_check_flg2['name'] }}</option>
                            @endif
                        @endforeach
                        </select>
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for="check_11">11月</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="check_11" name="check_11">
                        @foreach ($loop_check_flg as $loop_check_flg2)
                            @if ($loop_check_flg2['no']==$progrecheck->check_11)
                                <option selected="selected" value={{$progrecheck->check_11}}>{{ $loop_check_flg2['name'] }}</option>
                            @else
                                <option value="{{ $loop_check_flg2['no'] }}">{{ $loop_check_flg2['name'] }}</option>
                            @endif
                        @endforeach
                        </select>
                </div>

                <div class="col-2">
                    <label for="check_12">12月</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="check_12" name="check_12">
                        @foreach ($loop_check_flg as $loop_check_flg2)
                            @if ($loop_check_flg2['no']==$progrecheck->check_12)
                                <option selected="selected" value={{$progrecheck->check_12}}>{{ $loop_check_flg2['name'] }}</option>
                            @else
                                <option value="{{ $loop_check_flg2['no'] }}">{{ $loop_check_flg2['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <hr class="mb-4">  {{-- // line --}}
            <div class="row">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button class="btn btn-primary btn-lg btn-block" type="submit">保存</button>
                    <a class="btn btn-primary btn-lg btn-block" href="{{route('progrecheck.input')}}">戻る</a>
                </div>
            </div>
            <hr class="mb-4">  {{-- // line --}}

        </form>
        </div>
    </div>

@endsection

@section('part_javascript')

@endsection
