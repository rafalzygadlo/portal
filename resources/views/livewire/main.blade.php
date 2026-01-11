<div>
    <div class="container-fluid" x-data="{ expanded: localStorage.getItem('header_expanded') !== 'false' }" x-init="$watch('expanded', val => localStorage.setItem('header_expanded', val))">
        
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
                    </p>
                    
                    <div class="col-md-12">
                        <div>
                            <h5 class="fw-bold">Wygraj 100 PLN!</h5>
                            <p class="mb-0">Co tydzień autor najlepszego artykułu otrzymuje nagrodę pieniężną.</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        @auth
                            <a href="{{ route('article.create') }}">
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
