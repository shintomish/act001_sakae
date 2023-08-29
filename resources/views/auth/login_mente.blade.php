@extends('layouts.login')

@section('content')
<style>
    body {
        background-color:rgb(240, 250, 240);
    }
</style>
    {{-- <img class="mb-4" src="{{ asset('img/actver.png') }}" alt="" width="80" height="80"> --}}
    {{-- <i class="fas fa-project-diagram"></i> --}}
    {{-- <i class="fas fa-landmark"></i>
    <h1 class="h3 mb-3 fw-normal">{{ config('app.name', 'Laravel') }}</h1> --}}
    <div class='container'>
        <div class='panel panel-default'>
            <div class='panel-heading text-center panel-relative'>
                <h1 class="h3 mb-3 fw-normal"><i class="fas fa-landmark"></i></h1>
                <h1 class="h3 mb-3 fw-normal"> {{ config('app.name', 'Laravel') }} </h1>
            </div>
        </div>
                                {{-- 2022/10/19 --}}
                                {{-- 2022/11/04 --}}
                                <h5><span style="color:blue">メンテナンス中</span></h5>
                                <h4><span style="color:blue">メンテナンス時間は、11/06(日) 深夜00:00 - PM09:00</span></h4>
                                <h4><span style="color:blue">PM09:00 以降はアップ可能となります。</span></h4>
    </div>
    {{-- <form  id="form-signin" name="form-signin" method="POST" action="{{ route('login') }}">
        @csrf
        <label for="email" class="visually-hidden">{{ __('E-Mail Address') }}</label>

        <input id="email" placeholder={{ __('E-Mail Address') }}  type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

        <label for="password" class="visually-hidden">{{ __('Password') }}</label>

        <input id="password" placeholder={{ __('Password') }} type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

        <div class="checkbox mb-3">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

            <label class="form-check-label" for="remember">{{ __('Remember Me') }}</label>
        </div>

        <button type="submit" class="w-100 btn btn-lg btn-primary">{{ __('Login') }}</button>

        @if (Route::has('password.request'))
            <a class="btn btn-link" href="{{ route('password.request') }}">
                {{ __('Forgot Your Password?') }}
            </a>
        @endif
--}}
        <p class="mt-5 mb-3 text-muted">AizenSolution Inc &copy; 2011-2023</p>

    </form>
@endsection
