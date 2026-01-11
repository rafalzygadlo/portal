<div>
    <div class="container-fluid">
        <div class="mb-4">
            <div class="container-fluid py-3">
                <h3 class="fw-bold">Bolesławiec - Twórz z nami portal miasta!</h3>
                <p class="col-md-12 fs-10 mt-1">
                    Masz ciekawe informacje, zdjęcia lub opinię o wydarzeniach w Bolesławcu?
                    Napisz artykuł i podziel się nim z mieszkańcami.
                </p>
                
                <p class="col-md-12 fs-10">
                    <div>
                        <h4 class="alert-heading fw-bold mb-1">Wygraj 100 PLN!</h4>
                        <p class="mb-0">Co tydzień autor najlepszego artykułu otrzymuje nagrodę pieniężną.</p>
                    </div>
                </div>

                <div class="mt-4">
                    @auth
                        <a href="{{ route('article.create') }}" class="btn btn-primary btn-lg px-4 me-md-2">
                            <i class="bi bi-pencil-square"></i> Dodaj artykuł
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4 me-md-2">Zaloguj się, aby pisać</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg px-4">Zarejestruj się</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <livewire:article.latest />
            <livewire:article.top />
        </div>
    </div>
</div>
