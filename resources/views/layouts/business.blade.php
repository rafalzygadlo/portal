<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $business->name }} - Rezerwacje online</title>

    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap-icons.css') }}" rel="stylesheet">
    @livewireStyles
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-light bg-light border-bottom">
        <div class="container-fluid px-4 px-lg-5">
            <a class="navbar-brand fw-bold"
                href="{{ route('business.domain', ['business' => $business]) }}">
                {{ $business->name }}
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                    <span class="navbar-toggler-icon"></span>
             </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto">
                                        

                </ul>

                <ul class="navbar-nav ms-auto">
                    @auth
                        @can('manage', $business)
                            <li class="nav-item">
                                <a class="btn btn-outline-primary me-2"
                                    href="{{ route('admin.business.dashboard', ['business' => $business]) }}">
                                    Panel Admina
                                </a>
                            </li>
                        @endcan

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown">
                                <i class="bi bi-person"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('user.profile') }}">Moje rezerwacje</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item text-danger">Wyloguj</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <a href="{{ route('login.subdomain', ['business' => $business]) }}"
                           class="btn btn-primary fw-semibold"
>
                            Login
                        </a>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <div class="container-fluid bg-light py-4 px-lg-5">
            {{ $slot }}
        </div>
    </main>

    <footer class="py-4 bg-light mt-5">
        <div class="container text-center">
            <p class="text-muted small">&copy; {{ date('Y') }} {{ $business->name }}. Zasilane przez Twój System
                Rezerwacji.</p>
        </div>
    </footer>

    @livewire('global-modal')
    @livewireScripts
    <script src="{{ asset('/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>