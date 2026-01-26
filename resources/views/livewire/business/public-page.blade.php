<div class="max-w-6xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg p-8 text-white mb-8">
        <div class="flex justify-between items-start mb-4">
            <div class="flex-1">
                <h1 class="text-4xl font-bold mb-2">{{ $business->name }}</h1>
                <p class="text-blue-100">{{ $business->description }}</p>
            </div>
        </div>

        <!-- Informacje kontaktowe -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-sm opacity-80">📍 Adres</p>
                <p class="font-semibold">{{ $business->address }}</p>
            </div>
            @if($business->phone)
                <div>
                    <p class="text-sm opacity-80">📞 Telefon</p>
                    <p class="font-semibold"><a href="tel:{{ $business->phone }}" class="hover:underline">{{ $business->phone }}</a></p>
                </div>
            @endif
            @if($business->website)
                <div>
                    <p class="text-sm opacity-80">🌐 Strona</p>
                    <p class="font-semibold"><a href="{{ $business->website }}" target="_blank" class="hover:underline">Odwiedź</a></p>
                </div>
            @endif
        </div>
    </div>

    <!-- Usługi rezerwacji -->
    @if(count($services) > 0)
        <div class="mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Dostępne usługi</h2>
                <a href="/book" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
                    Zarezerwuj termin
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($services as $service)
                    <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition">
                        <h3 class="text-lg font-bold mb-2">{{ $service->name }}</h3>
                        <p class="text-gray-600 text-sm mb-4">{{ $service->description }}</p>
                        
                        <div class="space-y-2 text-sm text-gray-700 mb-4">
                            <p>⏱️ <strong>Czas:</strong> {{ $service->duration_minutes }} minut</p>
                            @if($service->price)
                                <p>💰 <strong>Cena:</strong> {{ number_format($service->price, 2) }} zł</p>
                            @endif
                            <p>⏳ <strong>Przerwa:</strong> {{ $service->buffer_minutes }} min</p>
                        </div>

                        <a href="/book" class="block w-full bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium py-2 px-4 rounded text-center">
                            Zarezerwuj
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Godziny pracy -->
    @php
        $dayNames = [
            'mon' => 'Poniedziałek',
            'tue' => 'Wtorek',
            'wed' => 'Środa',
            'thu' => 'Czwartek',
            'fri' => 'Piątek',
            'sat' => 'Sobota',
            'sun' => 'Niedziela',
        ];
    @endphp

    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
        <h3 class="text-xl font-bold mb-4">Godziny pracy</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($dayNames as $key => $dayName)
                @php
                    $hours = $business->getBusinessHours()[$key];
                @endphp
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="font-medium">{{ $dayName }}</span>
                    <span class="text-gray-600">
                        @if($hours['closed'] ?? false)
                            <span class="text-red-600">Zamknięte</span>
                        @else
                            {{ $hours['open'] }} - {{ $hours['close'] }}
                        @endif
                    </span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Opis biznesu -->
    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
        <h3 class="text-xl font-bold mb-4">O biznesie</h3>
        <div class="prose prose-sm max-w-none text-gray-700">
            {{ nl2br(e($business->description)) }}
        </div>
    </div>

    <!-- CTA -->
    <div class="bg-blue-100 border-2 border-blue-600 rounded-lg p-8 text-center">
        <h3 class="text-2xl font-bold text-blue-900 mb-4">Gotów do rezerwacji?</h3>
        <p class="text-blue-800 mb-6">Zarezerwuj preferowany termin w kilka kliknięć</p>
        <a href="/book" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-3 rounded-lg">
            Zarezerwuj teraz
        </a>
    </div>
</div>
