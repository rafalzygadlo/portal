<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', '') }}</title>

    <!-- CSS Files -->
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap-icons.css') }}" rel="stylesheet">

    @livewireStyles
</head>
<body>
    <div id="app">
        <!-- Header/Navbar -->
        <nav class="navbar navbar-expand-lg bg-white sticky-top shadow">
            <div class="container-fluid px-4 px-lg-5">
                <span class="logo-r">R</span>
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <span class="fw-bold tracking-tight">{{ config('app.name', '') }}</span>
                </a>

                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                
                        <li class="nav-item px-lg-2">
                            <a class="nav-link fw-semibold" href="{{ route('offers.index') }}">Ogłoszenia</a>
                        </li>
                        <li class="nav-item px-lg-2">
                            <a class="nav-link fw-semibold" href="{{ route('business.index') }}">Firmy</a>
                        </li>
                    </ul>

                    <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                        @guest
                            <a class="nav-link fw-semibold" href="{{ route('login') }}">Login</a>
                        @else
                            <div class="dropdown">
                                <button class="btn btn-outline-primary btn-pill btn-sm dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->first_name ?? Auth::user()->name }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                    <li><a class="dropdown-item" href="{{ route('user.profile', Auth::user()) }}">Profil</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endguest

                        <a class="btn btn-primary btn-pill fw-semibold" href="{{ route('todos.index') }}">
                            <i class="bi bi-rocket-takeoff me-1"></i> Projekt R
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- MAIN CONTENT -->
        <main >
            <div class="container-fluid bg-light py-4 px-lg-5">
                {{ $slot }}
            </div>
        </main>

        <!-- FOOTER -->
        <footer class="bg-dark text-white pt-5 pb-4">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-4">
                        <h5 class="fw-bold mb-4 d-flex align-items-center">
                            <span class="logo-r shadow">R</span> {{ config('app.name', '') }}
                        </h5>
                        <p class="text-secondary small" style="max-width: 300px;">
                            Portal budowany z rzetelnością dla mieszkańców Bolesławca. Wszystko co lokalne w jednym miejscu.
                        </p>
                        <div class="d-flex gap-3 fs-5 mt-4">
                            <a href="#" class="text-secondary"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="text-secondary"><i class="bi bi-instagram"></i></a>
                        </div>
                    </div>
                    <div class="col-6 col-lg-2 offset-lg-2">
                        <h6 class="fw-bold mb-3 small text-uppercase tracking-wider">Gazeta</h6>
                        <ul class="list-unstyled small">
                            <li class="mb-2"><a href="{{ route('articles.index') }}" class="text-secondary text-decoration-none">Artykuły</a></li>
                            <li class="mb-2"><a href="{{ route('business.index') }}" class="text-secondary text-decoration-none">Firmy</a></li>
                            <li class="mb-2"><a href="{{ route('offers.index') }}" class="text-secondary text-decoration-none">Ogłoszenia</a></li>
                            <li class="mb-2"><a href="{{ route('todos.index') }}" class="text-secondary text-decoration-none">Roadmapa</a></li>
                            <li class="mb-2"><a href="{{ route('polls.index') }}" class="text-secondary text-decoration-none">Sondaże</a></li>
                        </ul>
                    </div>
                    <div class="col-6 col-lg-2">
                        <h6 class="fw-bold mb-3 small text-uppercase tracking-wider">Pomoc</h6>
                        <ul class="list-unstyled small">
                            <li class="mb-2"><a href="{{ route('page', ['page' => 'privacy'] ) }}" class="text-secondary text-decoration-none">Prywatność</a></li>
                            <li class="mb-2"><a href="{{ route('page', ['page' => 'terms'] ) }}" class="text-secondary text-decoration-none">Regulamin</a></li>
                            <li class="mb-2"><a href="{{ route('page', ['page' => 'faq']) }}" class="text-secondary text-decoration-none">FAQ</a></li>
                        </ul>
                    </div>
                </div>
                <hr class="mt-5 border-secondary opacity-25">
                <div class="text-center text-secondary small">
                    © {{ date('Y') }} {{ config('app.name') }}. Rzetelnie od 2026.
                </div>
            </div>
        </footer>

    </div>
    {{-- Global Modal --}}
    @livewire('global-modal')
    
    <!-- Scripts -->
    <script src="{{ asset('/js/bootstrap.bundle.min.js') }}"></script>
    @livewireScripts
</body>
</html>