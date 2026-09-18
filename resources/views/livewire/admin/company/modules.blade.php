<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-semibold">Moduły</h1>
        <p class="text-gray-500">
            Wybierz funkcje, które chcesz mieć w swojej firmie.
        </p>
    </div>

    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($modules as $module)
            @php
                $active = $company->hasModule($module->slug());
            @endphp

            <div class="rounded-xl border bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="text-2xl">
                            {{ $module->icon() }}
                        </div>

                        <h2 class="mt-2 text-lg font-semibold">
                            {{ $module->name() }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            {{ $module->description() }}
                        </p>
                    </div>

                    @if ($active)
                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                            Aktywny
                        </span>
                    @else
                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">
                            Nieaktywny
                        </span>
                    @endif
                </div>

                <div class="mt-5">
                    @if ($active)
                        <a href="#" class="inline-flex rounded-lg bg-gray-900 px-4 py-2 text-sm font-medium text-white">
                            Otwórz
                        </a>
                    @else
                                <button type="button" wire:click="toggleModule('{{ $module->slug() }}')" class="inline-flex rounded-lg px-4 py-2 text-sm font-medium
                        {{ in_array($module->slug(), $cart, true)
                            ? 'bg-blue-600 text-white'
                            : 'border bg-white text-gray-700' }}">
                                    {{ in_array($module->slug(), $cart, true)
                            ? 'W koszyku'
                            : 'Dodaj do koszyka' }}
                                </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

@if (count($cartItems) > 0)
    <div class="rounded-xl border bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold">
                    Koszyk
                </h2>

                <p class="text-sm text-gray-500">
                    Wybrane moduły
                </p>
            </div>

            <div class="text-right">
                <div class="text-xl font-semibold">
                    {{ number_format($this->cartTotal() / 100, 2, ',', ' ') }} zł
                </div>

                <div class="text-sm text-gray-500">
                    miesięcznie
                </div>
            </div>
        </div>

        <div class="mt-4 space-y-2">
            @foreach ($cartItems as $module)
                <div class="flex items-center justify-between text-sm">
                    <span>
                        {{ $module->icon() }}
                        {{ $module->name() }}
                    </span>

                    <span>
                        {{ number_format($module->price() / 100, 2, ',', ' ') }} zł / mies.
                    </span>
                </div>
            @endforeach
        </div>

        <div class="mt-5">
          <button
    type="button"
    wire:click="checkout"
    class="w-full rounded-lg bg-gray-900 px-4 py-3 text-sm font-medium text-white"
>
    Przejdź do zamówienia
</button>
        </div>
    </div>
@endif


</div>