@extends('layouts.customer')

@section('content')

    <h2>業務名</h2>
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
        {{-- 2022/09/10 --}}
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- 検索エリア -->
        {{-- <form class="needs-validation" novalidate action="{{ route('businesname.store')}}" method="POST"> --}}

        <div class="col-md-8 order-md-1">
            <h4 class="mb-3">新規追加</h4>
            <form action="{{ route('businesname.store')}}" method="POST">
                @csrf
                @method('post')
            <div class="row">
                <div class="col-2">
                </div>
                <div class="col-4">
                </div>
                <div class="col-4 mb-2">
<input style="margin-right:5px;" type="text" value="{{$keyword2}}" name="keyword" class="form-control" placeholder="会社名検索">
                </div>
                <div class="col-2 mb-2">
<button  name="submit_new" value="submit_new" type="submit" class="btn btn-secondary" formaction="{!! route('businesname_cus_id') !!}">検索</button>
                </div>
            </div>

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
                            @if ($loop_year_flg2['no']==$nowyear)
                                <option selected="selected" value="{{ $loop_year_flg2['no'] }}">{{ $loop_year_flg2['name'] }}</option>
                            @else
                                <option  disabled value="{{ $loop_year_flg2['no'] }}">{{ $loop_year_flg2['name'] }}</option>
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
                        @foreach($customers as $customer)
{{-- <option value="{{$customer->id}}" @if(old('custm_id')==$customer->id) 'selected' @endif > {{$customer->business_name}} </option> --}}
{{-- 2022/09/10 Error時にもとに戻す --}}
{{-- old('custm_id', 11) 11->MusicBank (name  ascの先頭11) --}}
<option value="{{$customer->id}}" {{(old('custm_id', 11) == $customer->id ? 'selected' : '')}} > {{$customer->business_name}} </option>

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
                <input type="text" class="form-control" name="businm_01" value="{{ old('businm_01','預金・現金管理') }}">
                </div>

                <div class="col-2">
                    <label for="businm_02">業務名02</label>
                </div>
                <div class="col-4">
                <input type="text" class="form-control" name="businm_02" value="{{ old('businm_02','経費精算')}}">
                </div>
            </div>
            <div class="row">
                <div class="col-2">
                    <label for="businm_03">業務名03</label>
                </div>
                <div class="col-4">
                <input type="text" class="form-control" name="businm_03" value="{{ old('businm_03','仮払金の管理')}}">
                </div>

                <div class="col-2">
                    <label for="businm_04">業務名04</label>
                </div>
                <div class="col-4">
                <input type="text" class="form-control" name="businm_04" value="{{ old('businm_04','売掛金・買掛金')}}">
                </div>
            </div>
            <div class="row">
                <div class="col-2">
                    <label for="businm_05">業務名05</label>
                </div>
                <div class="col-4">
                <input type="text" class="form-control" name="businm_05" value="{{ old('businm_05','領収書・請求書')}}">
                </div>

                <div class="col-2">
                    <label for="businm_06">業務名06</label>
                </div>
                <div class="col-4">
                <input type="text" class="form-control" name="businm_06" value="{{ old('businm_06','在庫管理')}}">
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for="businm_07">業務名07</label>
                </div>
                <div class="col-4">
                <input type="text" class="form-control" name="businm_07" value="{{ old('businm_07','給与計算')}}">
                </div>

                <div class="col-2">
                    <label for="businm_08">業務名08</label>
                </div>
                <div class="col-4">
                <input type="text" class="form-control" name="businm_08" value="{{ old('businm_08','月次試算表')}}">
                </div>
            </div>

            <div class="row">
                <div class="col-2">
                    <label for="businm_09">業務名09</label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="businm_09" value="{{ old('businm_09')}}">
                </div>

                <div class="col-2">
                    <label for="businm_10">業務名10</label>
                </div>
                <div class="col-4">
                    <input type="text" class="form-control" name="businm_10" value="{{ old('businm_10')}}">
                </div>
            </div>

            <hr class="mb-4">  {{-- // line --}}
            <div class="row">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
<button  name="submit" value="submit" type="submit" class="btn btn-primary btn-lg btn-block">登録</button>
                    <a class="btn btn-primary btn-lg btn-block" href="{{route('businesname.index')}}">戻る</a>
                </div>
            </div>
            <hr class="mb-4">  {{-- // line --}}

        </form>
    </div>

@endsection

@section('part_javascript')

@endsection



