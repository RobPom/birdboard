<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-grey-lighter">
    <div id="app">
        <nav class="bg-white">
            <div class="container mx-auto">
                <div class="flex justify-between items-center">
                    <h1>
                        <a class="navbar-brand" href="{{ url('/') }}">
                            <img src="/images/logo.svg" alt="Birdboard">
                        </a>
                    </h1>
                    <ul class="list-reset flex">

                        <!-- Right Side Of Navbar -->
                        
                            @guest
                                <li class="mr-6">
                                    <a class="text-grey text-sm no-underline font-semibold" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                               
                                @if (Route::has('register'))
                                    <li class="mr-6"> 
                                        <a class="text-grey text-sm no-underline font-semibold" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif
                            @else
                             
                            <li class="mr-6 text-grey text-sm">
                                {{ Auth::user()->name }} 
                            </li>
                                    
                            <li class="mr-6 ">
                                <a class="no-underline text-grey text-sm font-semibold" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                                        
                               
                            @endguest
                        </ul>

                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-6 container mx-auto">
            @yield('content')
        </main>
    </div>
</body>
</html>
