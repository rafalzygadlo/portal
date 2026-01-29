<!DOCTYPE html>
<html  lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarezerwuj - {{ $business->name }}</title>
    
     <!-- CSS Files -->
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/bootstrap-icons.css') }}" rel="stylesheet">
    @livewireStyles
</head>
<body class="bg-light">
    <nav class="bg-white shadow-sm">
        <div class="container py-4 d-flex justify-content-between align-items-center">
            <div>
                <a href="/" class="text-muted"></a>
                <h1 class="h5 fw-bold">{{ $business->name }}</h1>
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
                                     <a class="dropdown-item" href="{{ route('logout') }}"> {{ __('logout') }}</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>

                                </div>
                            </li>
                        @endif
                    </ul>
        
    </nav>

    <div class="container py-5">
        {{ $slot }}
    </div>

    @livewireScripts
</body>
</html>
