<div>
    @guest
        <a wire:click="$dispatch('openModal', 'auth.login')" class="btn btn-primary fw-semibold nav-link" style="cursor: pointer;">
            Login
        </a>
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
</div>
