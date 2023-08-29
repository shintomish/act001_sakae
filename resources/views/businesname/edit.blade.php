{{-- @extends('layouts.app') --}}
@extends('layouts.customer')

@section('content')
    <h2>業務名</h2>
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
    {{-- 2022/09/10 --}}
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <div class="row">
        <div class="col-md-8 order-md-1">
            <form class="needs-validation" novalidate action="{{ route('businesname.update',$businesname->id)}}" method="POST">
                @csrf
                @method('PUT')

            <h4 class="mb-3">情報</h4>

            <div class="row">
                <div class="col-2">

                </div>
                <div class="col-4">

                </div>

                <div class="col-2">
                    <label for="year">年</label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="year" name="year">
                        @foreach ($loop_year_flg as $loop_year_flg2)
                        @if ($loop_year_flg2['no']==$businesname->year)
                        <option selected="selected" value={{$businesname->year}}>{{ $loop_year_flg2['name'] }}</option>
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
                            @if($customers2->id==$businesname->custm_id)
                    <option selected="selected" value={{$businesname->custm_id}}>{{ $customers2->business_name }}</option>
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
                    <label for="businm_01">業務名01</label>
                </div>
                <div class="col-4">
                <input type="text" class="form-control" name="businm_01" value="{{ old('businm_01',$businesname->businm_01) }}">
                </div>

                <div class="col-2">
                    <label for="businm_02">業務名02</label>
                </div>
                <div class="col-4">
                <input type="text" class="form-control" name="businm_02" value="{{ old('businm_02',$businesname->businm_02)}}">
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for="businm_03">業務名03</label>
                </div>
                <div class="col-4">
                <input type="text" class="form-control" name="businm_03" value="{{ old('businm_03',$businesname->businm_03) }}">
                </div>

                <div class="col-2">
                    <label for="businm_04">業務名04</label>
                </div>
                <div class="col-4">
                <input type="text" class="form-control" name="businm_04" value="{{ old('businm_04',$businesname->businm_04)}}">
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for="businm_05">業務名05</label>
                </div>
                <div class="col-4">
                <input type="text" class="form-control" name="businm_05" value="{{ old('businm_05',$businesname->businm_05) }}">
                </div>

                <div class="col-2">
                    <label for="businm_06">業務名06</label>
                </div>
                <div class="col-4">
                <input type="text" class="form-control" name="businm_06" value="{{ old('businm_06',$businesname->businm_06)}}">
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for="businm_07">業務名07</label>
                </div>
                <div class="col-4">
                <input type="text" class="form-control" name="businm_07" value="{{ old('businm_07',$businesname->businm_07) }}">
                </div>

                <div class="col-2">
                    <label for="businm_08">業務名08</label>
                </div>
                <div class="col-4">
                <input type="text" class="form-control" name="businm_08" value="{{ old('businm_08',$businesname->businm_08)}}">
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for="businm_09">業務名09</label>
                </div>
                <div class="col-4">
                <input type="text" class="form-control" name="businm_09" value="{{ old('businm_09',$businesname->businm_09) }}">
                </div>

                <div class="col-2">
                    <label for="businm_10">業務名10</label>
                </div>
                <div class="col-4">
                <input type="text" class="form-control" name="businm_10" value="{{ old('businm_10',$businesname->businm_10)}}">
                </div>
            </div>

            <hr class="mb-4">  {{-- // line --}}
            <div class="row">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button class="btn btn-primary btn-lg btn-block" type="submit">保存</button>
                    <a class="btn btn-primary btn-lg btn-block" href="{{route('businesname.index')}}">戻る</a>
                </div>
            </div>
            <hr class="mb-4">  {{-- // line --}}

        </form>
        </div>
    </div>

@endsection

@section('part_javascript')

@endsection
