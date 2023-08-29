@extends('layouts.app')

@section('content')
<v-main>
    <v-container>
        <h1 class="h4 text-center pt-8 font-weight-bold">
            {{ __('Login') }}
        </h1>
        <v-row justify="center">
            <v-col cols="12" md="6">
                <v-card elevation="0">
                    <div class="pa-8">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <p class="mb-0 font-weight-bold">{{ __('E-Mail Address') }}</p>
                            <v-text-field
                                outlined
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                v-bind:error="@error('email') true @else false @enderror"
                                error-messages="@error('email') {{$message}} @enderror"
                                required
                                autocomplete="email"
                                placeholder="example@example.com"
                                autofocus
                            ></v-text-field>

                            <p class="mb-0 font-weight-bold">{{ __('Password') }}</p>
                            <v-text-field
                                outlined
                                type="password"
                                name="password"
                                v-bind:error="@error('password') true @else false @enderror"
                                error-messages="@error('password') {{$message}} @enderror"
                                required
                                autocomplete="current-password"
                            ></v-text-field>

                            <v-checkbox
                                name="remember"
                                label="{{ __('Remember Me') }}"
                                value="{{ old('remember') ? 'true' : 'false' }}"
                                class="mb-12"
                            ></v-checkbox>

                            <div class="px-4 mb-4">
                                <v-btn type="submit" color="primary" elevation="0" block large>
                                    {{ __('Login') }}
                                </v-btn>
                            </div>

                            @if (Route::has('password.request'))
                                <div class="text-center">
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                </div>
                            @endif
                        </form>
                    </div>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</v-main>
@endsection
