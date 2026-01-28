<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarezerwuj - {{ $business->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
            <div>
                <a href="/" class="text-gray-600 hover:text-gray-900"></a>
                <h1 class="text-xl font-bold">{{ $business->name }}</h1>
            </div>
        </div>
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
        
    </nav>

    <div class="max-w-6xl mx-auto px-4 py-8">
        {{ $slot }}
    </div>

    @livewireScripts
</body>
</html>
