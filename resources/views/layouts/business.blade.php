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
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('business.domain', ['subdomain' => $business->subdomain]) }}">
                {{ $business->name }}
            </a>

            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('business.booking', ['subdomain' => $business->subdomain]) }}">Zarezerwuj wizytę</a>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto">
                    @auth
                        @can('manage-business', $business->subdomain)
                            <li class="nav-item">
                                <a class="btn btn-outline-primary me-2" href="{{ route('admin.business.dashboard', ['subdomain' => $business->subdomain]) }}">
                                    Panel Admina
                                </a>
                            </li>
                        @endcan

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('user.profile') }}">Moje rezerwacje</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item text-danger">Wyloguj</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Zaloguj się</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main >
            <div class="container-fluid bg-light py-4 px-lg-5">
                {{ $slot }}
            </div>
        </main>

    <footer class="py-4 bg-light mt-5">
        <div class="container text-center">
            <p class="text-muted small">&copy; {{ date('Y') }} {{ $business->name }}. Zasilane przez Twój System Rezerwacji.</p>
        </div>
    </footer>

    @livewireScripts
    <script src="{{ asset('/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>