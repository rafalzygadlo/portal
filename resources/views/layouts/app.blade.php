<!doctype html>
<html  lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.user.name', 'User') }}</title>

    <!-- CSS Files -->
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap-icons.css') }}" rel="stylesheet">
    
    @livewireStyles

</head>
<body >
    <div>
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Portal') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('main.index') }}">{{ __('main.index') }}</a>
                        </li>     
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('todo.index') }}">{{ __('todo.index') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('business.index') }}">{{ __('business.index') }}</a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">

                        <!-- Authentication Links -->
                        @if(!Auth::guard('user')->check())

                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('login.link') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('register.link') }}</a>
                                </li>
                            @endif
                        @else
                        <li class="nav-item">

                                

                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bi bi-person-circle"></i>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('user.profile', Auth::user()) }}"> {{ __('profile.link') }}</a>
                                <div class="dropdown-divider"></div>
                                     <a class="dropdown-item" href="{{ route('logout') }}"> {{ __('logout.link') }}</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>

                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <main class="container-fluid py-4">
        {{$slot}}
        </main>

  <footer class="py-3 my-4">
    <ul class="nav justify-content-center border-top  pb-3 mb-3">
      <li class="nav-item">
        <a href="{{ route('page', ['page' => 'privacy'] ) }}" class="nav-link px-2 text-muted"><small>{{ __('global.privacy') }}</small></a>
    </li>
      <li class="nav-item">
        <a href="{{ route('page', ['page' => 'faq']) }}" class="nav-link px-2 text-muted"><small>{{ __('global.faq') }}</small></a>
    </li>
      <li class="nav-item">
        <a href="{{ route('page', ['page' => 'terms'] ) }}" class="nav-link px-2 text-muted"><small>{{ __('global.terms') }}</small></a>
    </li>
    </ul>

  </footer>

</div>

    @livewireScripts
    <script src="{{ asset('/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
