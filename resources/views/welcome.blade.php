@extends('layouts.login')

@section('content')

    <body class="antialiased">

    <style>
        body {
            background-color:rgb(239, 247, 208);
        }
    </style>
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center py-4 sm:pt-0">

            <div class='container'>
                <div class='panel panel-default'>
                    <div class='panel-heading text-center panel-relative'>

                    </div>
                </div>
            </div>

            <div class='container'>
                <div class='panel panel-default'>
                    <div class='panel-heading text-center panel-relative'>
                        @if (Route::has('login'))
                        {{-- <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block"> --}}
                        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                            @auth
                                <a href="{{ url('/home') }}" class="text-sm text-gray-700 underline">Home</a>
                            @else
                            <p>
                                <a href="{{ route('login') }}" class="text-center underline">{{ __('Login') }}</a>
                            </p>

                                @if (Route::has('register'))
                                    {{-- <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Register</a> --}}
                                @endif
                            @endauth
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            </div>

            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
                {{-- <img class="mb-4" src="{{ asset('img/actver.png') }}" alt="" width="80" height="80"> --}}

                <div class='container'>
                    <div class='panel panel-default'>
                        <div class='panel-heading text-center panel-relative'>

                        </div>
                    </div>
                </div>
                <div class='container'>
                    <div class='panel panel-default'>
                        <div class='panel-heading text-center panel-relative'>
                            {{-- <h1 class="h3 mb-3 fw-normal"><i class="fas fa-globe"></i></h1> --}}
                            <h1 class="h3 mb-3 fw-normal"><i class="fas fa-globe"></i> {{ config('app.name', 'Laravel') }} </h1>
                        </div>
                    </div>
                </div>
                <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2">
                        <div class="p-6">
                            <div class="flex items-center">

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </body>

    <p class="mt-5 mb-3 text-muted">AizenSolution Inc &copy; 2011-2023</p>

@endsection
