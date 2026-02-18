<div class="business-dashboard container py-4">
    <div class="mb-4">
        <h1 class="h2 fw-bold">{{ $business->name }}</h1>
        <p class="text-muted">Panel zarządzania rezerwacjami</p>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4">
    {{--     
    <li class="nav-item">
            <a href="{{ route('admin.business.services.index') }}" class="nav-link">
                Services
            </a>
        </li>
        
        <li class="nav-item">
            <a href="{{ route('dashboard.business', ['business' => $business, 'tab' => 'reservations']) }}" class="nav-link {{ $tab === 'reservations' ? 'active' : '' }}">
                Rezerwacje
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('business.resources.index', $business) }}" class="nav-link">
                Zasoby
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('dashboard.business', ['business' => $business, 'tab' => 'settings']) }}" class="nav-link {{ $tab === 'settings' ? 'active' : '' }}">
                Ustawienia
            </a>
        </li>
         --}}
    </ul>

