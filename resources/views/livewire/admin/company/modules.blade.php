<div class="py-4">

    {{-- Nagłówek --}}
    <div class="mb-4">
        <h1 class="h2 fw-semibold mb-1">Moduły</h1>
        <p class="text-muted mb-0">
            Wybierz funkcje, które chcesz mieć w swojej firmie.
        </p>
    </div>

    {{-- Lista modułów --}}
    <div class="row g-4">

        @foreach ($modules as $module)

            @php
                $active = $company->hasModule($module->slug());
            @endphp

            <div class="col-12 col-md-6 col-lg-4">

                <div class="card h-100 shadow-sm">

                    <div class="card-body d-flex flex-column">

                        <div class="d-flex justify-content-between gap-3">

                            <div>

                                <div class="fs-2 mb-2">
                                    {{ $module->icon() }}
                                </div>

                                <h2 class="h5 fw-semibold mb-2">
                                    {{ $module->name() }}
                                </h2>

                                <p class="text-muted small mb-0">
                                    {{ $module->description() }}
                                </p>
                                <div class="fw-semibold">
    {{ number_format($module->price() / 100, 2, ',', ' ') }} zł
    <span class="text-muted fw-normal small">/ miesiąc</span>
</div>
                            </div>

                            <div class="flex-shrink-0">

                                @if ($active)

                                    <span class="badge text-bg-success">
                                        Aktywny
                                    </span>

                                @else

                                    <span class="badge text-bg-secondary">
                                        Nieaktywny
                                    </span>

                                @endif

                            </div>

                        </div>

                        <div class="mt-auto pt-4">

                            @if ($active)

                                <a href="#" class="btn btn-dark">
                                    Otwórz
                                </a>

                            @else

                                            <button type="button" wire:click="toggleModule('{{ $module->slug() }}')" class="btn {{ in_array($module->slug(), $cart, true)
                                ? 'btn-primary'
                                : 'btn-outline-secondary' }}">
                                                {{ in_array($module->slug(), $cart, true)
                                ? 'W koszyku'
                                : 'Dodaj do koszyka' }}
                                            </button>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        @endforeach

    </div>


    {{-- Koszyk --}}
    @if (count($cartItems) > 0)

        <div class="card shadow-sm mt-5">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h2 class="h5 fw-semibold mb-1">
                            Koszyk
                        </h2>

                        <p class="text-muted small mb-0">
                            Wybrane moduły
                        </p>
                    </div>

                    <div class="text-end">

                        <div class="fs-4 fw-semibold">
                            {{ number_format($this->cartTotal() / 100, 2, ',', ' ') }} zł
                        </div>

                        <div class="text-muted small">
                            miesięcznie
                        </div>

                    </div>

                </div>


                {{-- Produkty w koszyku --}}
                <div class="mt-4">

                    @foreach ($cartItems as $module)

                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">

                            <span class="small">
                                {{ $module->icon() }}
                                {{ $module->name() }}
                                
                            </span>

                            <span class="small">
                                {{ number_format($module->price() / 100, 2, ',', ' ') }} zł / mies.
                            </span>

                        </div>

                    @endforeach

                </div>


                {{-- Zamówienie --}}
                <div class="mt-4">

                    <button type="button" class="btn btn-primary w-100" wire:click="checkout">
                        Przejdź do zamówienia
                    </button>

                </div>

            </div>

        </div>

    @endif

</div>