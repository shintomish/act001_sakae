@extends('layouts.apphome')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                {{-- <div class="card-header">{{ __('Dashboard') }}</div> --}}

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @else
                        <div class="alert alert-danger" role="alert">
                            {{-- {{ session('status') }} --}}
                <h5 >
                    <span style="color:red">ログイン済です。<br>
                    <span style="color:red">右上のログアウトをクリックしてログアウトしてください。</span>
                </h5>
                        </div>
                    @endif

                    {{-- {{ __('You are logged in!') }} --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
