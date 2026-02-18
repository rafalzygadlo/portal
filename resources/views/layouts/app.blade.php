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
<body>
    <div>
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm sticky-top">
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
                            <a class="nav-link" href="{{ route('todos.index') }}">{{ __('todos.index') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('business.index') }}">{{ __('business.index') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('polls.index') }}">{{ __('polls.index') }}</a>
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
                                <livewire:notifications />
                            </li>
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bi bi-person-circle"></i>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('user.profile', Auth::user()) }}"> {{ __('profile.link') }}</a>
                                <div class="dropdown-divider"></div>
                                     <a class="dropdown-item" href="{{ route('logout') }}"> {{ __('global.logout') }}</a>
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

        <main class="container-fluid py-5">
            {{$slot}}
        </main>

  <footer class="text-white bg-dark">
    <div class="container py-4 py-md-5 px-4 px-md-3">
        <div class="row">
            <div class="col-lg-3 mb-3">
                <a class="d-inline-flex align-items-center mb-2 text-decoration-none" href="/" aria-label="Portal">
                    <span class="fs-5 text-white">{{ config('app.name', 'Portal') }}</span>
                </a>
                <ul class="list-unstyled small">
                    <li class="mb-2 text-white">{{ __('main.footer.about') }}</li>
                    <li class="mb-2 text-white">© {{date('Y')}}</li>
                </ul>
            </div>
            <div class="col-6 col-lg-2 offset-lg-1 mb-3">
                <h5 class="text-white">{{ __('main.footer.sections') }}</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a class="text-white text-decoration-none" href="{{ route('todos.index') }}">{{ __('todos.index') }}</a></li>
                    <li class="mb-2"><a class="text-white text-decoration-none" href="{{ route('business.index') }}">{{ __('business.index') }}</a></li>
                    <li class="mb-2"><a class="text-white text-decoration-none" href="{{ route('polls.index') }}">{{ __('polls.index') }}</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2 mb-3">
                <h5 class="text-white">{{ __('main.footer.legal') }}</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a class="text-white text-decoration-none" href="{{ route('page', ['page' => 'privacy'] ) }}">{{ __('global.privacy') }}</a></li>
                    <li class="mb-2"><a class="text-white text-decoration-none" href="{{ route('page', ['page' => 'terms'] ) }}">{{ __('global.terms') }}</a></li>
                    <li class="mb-2"><a class="text-white text-decoration-none" href="{{ route('page', ['page' => 'faq']) }}">{{ __('global.faq') }}</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2 mb-3">
                <h5 class="text-white">{{ __('main.footer.social') }}</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a class="text-white text-decoration-none" href="#">Facebook</a></li>
                    <li class="mb-2"><a class="text-white text-decoration-none" href="#">Twitter</a></li>
                    <li class="mb-2"><a class="text-white text-decoration-none" href="#">Instagram</a></li>
                </ul>
            </div>
        </div>
    </div>
  </footer>

</div>

    @livewireScripts
    <script src="{{ asset('/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
