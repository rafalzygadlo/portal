<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Portal Bolesławiec') }}</title>

    <!-- CSS Files -->
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap-icons.css') }}" rel="stylesheet">

    <style>
        :root {
            --r-blue: #0d6efd;
            --r-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            overflow-x: hidden;
        }

        /* NAVBAR & MEGA MENU FIXES */
        .navbar {
            padding: 0.8rem 0;
            background: #fff !important;
            z-index: 1050 !important; /* Wyżej niż reszta strony */
        }

        @media (min-width: 992px) {
            .navbar .nav-item.dropdown.position-static {
                position: static !important;
            }

            /* Mostek bezpieczeństwa: zapobiega uciekaniu menu */
            .navbar .nav-item.dropdown {
                padding-bottom: 15px; 
                margin-bottom: -15px;
            }

            .navbar .dropdown-menu.w-100 {
                left: 0 !important;
                right: 0 !important;
                width: 100% !important;
                top: 100%;
                border: none;
                border-top: 1px solid #f1f1f1;
                box-shadow: var(--r-shadow) !important;
                display: none;
                z-index: 1100 !important;
                animation: fadeIn 0.2s ease-out;
            }

            .navbar .nav-item.dropdown:hover > .dropdown-menu {
                display: block !important;
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* STYLIZACJA ELEMENTÓW MENU */
        .dropdown-item {
            font-size: 0.92rem;
            color: #444;
            padding: 0.6rem 1rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: #f0f7ff;
            color: var(--r-blue);
            transform: translateX(5px);
        }

        .mega-menu-title {
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #adb5bd;
            margin-bottom: 1rem;
            display: block;
        }

        /* LOGO R */
        .logo-r {
            background: var(--r-blue);
            color: white;
            font-weight: 900;
            padding: 0.3rem 0.8rem;
            border-radius: 10px;
            margin-right: 12px;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.25);
        }

        /* MAIN CONTENT LAYER */
        main {
            position: relative;
            z-index: 1; /* Niżej niż navbar */
        }

        /* MOBILE FIXES */
        @media (max-width: 991px) {
            .navbar-collapse {
                background: white;
                padding: 1.5rem;
                margin-top: 0.5rem;
                border-radius: 15px;
                box-shadow: var(--r-shadow);
            }
            .dropdown-menu {
                border: none !important;
                padding-left: 0.5rem !important;
                box-shadow: none !important;
            }
            .navbar .nav-item.dropdown {
                margin-bottom: 1rem;
            }
        }
    </style>

    @livewireStyles
</head>
<body>
    <div id="app">

        <!-- Header/Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light sticky-top shadow-sm">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="/">
                    <span class="logo-r">R</span>
                    <span class="fw-bold tracking-tight">Portal Bolesławiec</span>
                </a>

                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainNav">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        
                        <!-- ŻYCIE MIASTA -->
                        <li class="nav-item dropdown position-static">
                            <a class="nav-link dropdown-toggle fw-semibold px-lg-3" href="#" data-bs-toggle="dropdown">Miasto</a>
                            <div class="dropdown-menu w-100 p-4">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <span class="mega-menu-title">Aktualności</span>
                                            <a class="dropdown-item" href="#"><i class="bi bi-newspaper me-2 text-primary"></i> Wiadomości</a>
                                            <a class="dropdown-item" href="#"><i class="bi bi-calendar-event me-2 text-primary"></i> Kultura i Wydarzenia</a>
                                            <a class="dropdown-item" href="#"><i class="bi bi-trophy me-2 text-primary"></i> Sport Lokalny</a>
                                        </div>
                                        <div class="col-lg-3 border-start">
                                            <span class="mega-menu-title">Użyteczne</span>
                                            <a class="dropdown-item" href="#"><i class="bi bi-bus-front me-2 text-primary"></i> Komunikacja</a>
                                            <a class="dropdown-item" href="#"><i class="bi bi-exclamation-octagon me-2 text-danger"></i> Alert Miasto</a>
                                        </div>
                                        <div class="col-lg-6 d-none d-lg-block border-start ps-4">
                                            <div class="bg-light p-4 rounded-4 h-100 d-flex align-items-center">
                                                <div>
                                                    <h6 class="fw-bold">Puls Bolesławca</h6>
                                                    <p class="small text-muted mb-0">Wszystkie najważniejsze informacje z regionu zebrane rzetelnie w jednym miejscu.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <!-- BIZNES -->
                        <li class="nav-item dropdown position-static">
                            <a class="nav-link dropdown-toggle fw-semibold px-lg-3" href="#" data-bs-toggle="dropdown">Biznes</a>
                            <div class="dropdown-menu w-100 p-4">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <span class="mega-menu-title text-success">Katalog Firm</span>
                                            <a class="dropdown-item" href="{{ route('business.index') }}"><i class="bi bi-buildings me-2 text-success"></i> Zakłady i Fabryki</a>
                                            <a class="dropdown-item" href="#"><i class="bi bi-shop me-2 text-success"></i> Usługi i Handel</a>
                                        </div>
                                        <div class="col-lg-3 border-start">
                                            <span class="mega-menu-title text-success">Zasoby i Praca</span>
                                            <a class="dropdown-item" href="#"><i class="bi bi-box-seam me-2 text-success"></i> Kartony i Materiały</a>
                                            <a class="dropdown-item" href="#"><i class="bi bi-person-badge me-2 text-success"></i> Oferty Pracy</a>
                                        </div>
                                        <div class="col-lg-6 d-none d-lg-block border-start ps-4">
                                            <div class="p-3 bg-light rounded-4">
                                                <small class="text-uppercase fw-bold text-muted d-block mb-2">Polecane firmy</small>
                                                <div class="d-flex gap-2">
                                                    <div class="bg-white p-2 rounded border small">Ceramika Artystyczna</div>
                                                    <div class="bg-white p-2 rounded border small">Strefa Ekonomiczna</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <!-- ODKRYWAJ -->
                        <li class="nav-item dropdown position-static">
                            <a class="nav-link dropdown-toggle fw-semibold px-lg-3" href="#" data-bs-toggle="dropdown">Odkrywaj</a>
                            <div class="dropdown-menu w-100 p-4">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <span class="mega-menu-title text-danger">Gastronomia</span>
                                            <a class="dropdown-item" href="#"><i class="bi bi-egg-fried me-2 text-danger"></i> Restauracje</a>
                                            <a class="dropdown-item" href="#"><i class="bi bi-cup-hot me-2 text-danger"></i> Kawiarnie</a>
                                        </div>
                                        <div class="col-lg-4 border-start">
                                            <span class="mega-menu-title text-danger">Handel</span>
                                            <a class="dropdown-item" href="#"><i class="bi bi-bag-check me-2 text-danger"></i> Galerie Handlowe</a>
                                            <a class="dropdown-item" href="#"><i class="bi bi-gem me-2 text-danger"></i> Lokalne Rzemiosło</a>
                                        </div>
                                        <div class="col-lg-4 border-start text-dark">
                                            <span class="mega-menu-title text-dark">Dla Ciebie</span>
                                            <a class="dropdown-item" href="#"><i class="bi bi-scissors me-2"></i> Fryzjerzy / Barberzy</a>
                                            <a class="dropdown-item" href="#"><i class="bi bi-lightning-charge me-2"></i> Siłownie / Fitness</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="nav-item px-lg-2">
                            <a class="nav-link fw-semibold" href="#">Ogłoszenia</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link fw-bold text-primary border border-primary rounded-pill px-4 ms-lg-3 mt-2 mt-lg-0 d-inline-block transition" href="{{ route('todos.index') }}">
                                <i class="bi bi-rocket-takeoff me-1"></i> Projekt R
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- MAIN CONTENT -->
        <main class="py-5">
            <div class="container">
                {{ $slot }}
            </div>
        </main>

        <!-- FOOTER -->
        <footer class="bg-dark text-white pt-5 pb-4">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-4">
                        <h5 class="fw-bold mb-4 d-flex align-items-center">
                            <span class="logo-r shadow-none">R</span> Bolesławiec
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
                        <h6 class="fw-bold mb-3 small text-uppercase tracking-wider">Portal</h6>
                        <ul class="list-unstyled small">
                            <li class="mb-2"><a href="{{ route('business.index') }}" class="text-secondary text-decoration-none">Firmy</a></li>
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
                    © {{ date('Y') }} Portal Bolesławiec. Rzetelnie od 2026.
                </div>
            </div>
        </footer>

    </div>

    <!-- Scripts -->
    <script src="{{ asset('/js/bootstrap.bundle.min.js') }}"></script>
    @livewireScripts
</body>
</html>