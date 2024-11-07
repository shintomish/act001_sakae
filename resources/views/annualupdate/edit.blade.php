{{-- @extends('layouts.app') --}}
@extends('layouts.customer')

@section('content')
    <h2>年度更新処理</h2>
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

        <form class="needs-validation" novalidate action="{{route('annualupdateupdate')}}" method="GET">
                @csrf
                @method('get')

            <h4 class="mb-3"></h4>

            <div class="row">
                <div class="col-2">

                </div>
                <div class="col-4">

                </div>

                <div class="col-2">
                    <label for="year"></label>
                </div>
                <div class="col-4">
                    <select class="custom-select d-block w-100" id="year" name="year">
                        @foreach ($loop_year_flg as $loop_year_flg2)
                        @if ($loop_year_flg2['no']!=$keyword2)
                        {{-- @if ($loop_year_flg2['no']>$keyword2) --}}
                        <option disabled value="{{ $loop_year_flg2['no'] }}">{{ $loop_year_flg2['name'] }}</option>
                        @else
                        <option selected="selected" value="{{$loop_year_flg2['no']}}">{{ $loop_year_flg2['name'] }}</option>
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
                </div>
                <div class="col-4">
                </div>
            </div>

            <hr class="mb-4">  {{-- // line --}}

            <div class="row">
                <div class="col-4">
                </div>
                <div class="col-2">
                    <h5>納期特例</h5>
                </div>

                <div class="col-4">

                </div>
                <div class="col-2">

                </div>
            </div>

            <div class="row">
                <div class="col-4">
                </div>
                <div class="col-2">
                </div>
                <div class="col-4">
                </div>
                <div class="col-2">
                </div>
            </div>

            <div class="row">
                <div class="col-4">
                </div>
                <div class="col-2">
                    <h5>年末調整</h5>
                </div>

                <div class="col-4">

                </div>
                <div class="col-2">
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                </div>
                <div class="col-2">
                </div>
                <div class="col-4">
                </div>
                <div class="col-2">
                </div>
            </div>

            <div class="row">
                <div class="col-4">
                </div>
                <div class="col-2">
                    <h5>顧客管理</h5>
                </div>
                <div class="col-4">
                </div>
                <div class="col-2">
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                </div>
                <div class="col-2">
                </div>
                <div class="col-4">
                </div>
                <div class="col-2">
                </div>
            </div>

            <div class="row">
                <div class="col-4">
                </div>
                <div class="col-2">
                    <h5>業務名</h5>
                </div>
                <div class="col-4">
                </div>
                <div class="col-2">
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                </div>
                <div class="col-2">
                </div>
                <div class="col-4">
                </div>
                <div class="col-2">
                </div>
            </div>

            <div class="row">
                <div class="col-4">
                </div>
                <div class="col-4">
                    <h5>進捗チェック</h5>
                </div>
                <div class="col-4">
                </div>
                <div class="col-2">
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                </div>
                <div class="col-2">
                </div>
                <div class="col-4">
                </div>
                <div class="col-2">
                </div>
            </div>

            <div class="row">
                <div class="col-4">
                </div>
                <div class="col-4">
                    <h5>スケジュール</h5>
                </div>
                <div class="col-4">
                </div>
                <div class="col-2">
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                </div>
                <div class="col-2">
                </div>
                <div class="col-4">
                </div>
                <div class="col-2">
                </div>
            </div>

            <div class="row">
                <div class="col-4">
                </div>
                <div class="col-4">
                    <h5>業務処理簿</h5>
                </div>
                <div class="col-4">
                </div>
                <div class="col-2">
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                </div>
                <div class="col-2">
                </div>
                <div class="col-4">
                </div>
                <div class="col-2">
                </div>
            </div>

            <div class="row">
                <div class="col-4">
                </div>
                <div class="col-8">
                </div>

            </div>

            <hr class="mb-4">  {{-- // line --}}
            <div class="row">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
        @if ($nowstatus == "true")
        <h5 >処理済み</h5>
        @else
        <button class="btn btn-primary btn-lg btn-block"  id="btn_update" type="submit" onclick='return confirm("実行しますか？");'>実行</button>
        @endif
                    <a class="btn btn-primary btn-lg btn-block" href="{{route('top')}}">戻る</a>
                </div>
            </div>
            <hr class="mb-4">  {{-- // line --}}

        </form>
        </div>
    </div>

@endsection

@section('part_javascript')

@endsection
