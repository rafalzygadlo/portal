 <div class="row" x-data="{ expanded: localStorage.getItem('header_expanded') !== 'false' }" x-init="$watch('expanded', val => localStorage.setItem('header_expanded', val))">

        <div class="d-flex justify-content-between align-items-center">
            <h6 class="fw-bold">Bolesławiec - Twórz z nami portal miasta!</h6>
            <button @click="expanded = !expanded" class="btn btn-sm text-secondary border-0 p-0">
                <i class="bi" :class="expanded ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
            </button>
        </div>
        <div x-show="expanded">
            <p class="col-md-12">
                Masz ciekawe informacje, zdjęcia lub opinię o wydarzeniach w Bolesławcu?
                Napisz artykuł i podziel się nim z mieszkańcami.
                Wygraj <b>100 PLN</b>
                Co miesiac autor najlepszego artykułu otrzymuje nagrodę pieniężną.
            </p>
        </div>

        <div class="mt-0">
            @auth
            <a href="{{ route('article.create') }}">
                <i class="bi bi-pencil-square"></i> Dodaj artykuł
            </a>
            @else
            <a href="{{ route('login') }}">Zaloguj się, aby pisać</a>
            @if (Route::has('register'))
            <a href="{{ route('register') }}">Zarejestruj się</a>
            @endif
            @endauth
        </div>
    </div>