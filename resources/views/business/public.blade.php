<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $business->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">{{ $business->name }}</h1>
            <a href="/book" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                Zarezerwuj termin
            </a>
        </div>
    </nav>

    <div class="max-w-6xl mx-auto px-4 py-12">
        <!-- Hero section -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg p-12 text-white mb-12">
            <h2 class="text-4xl font-bold mb-4">{{ $business->name }}</h2>
            <p class="text-lg opacity-90 mb-6">{{ $business->description }}</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <h3 class="font-semibold mb-2">📍 Adres</h3>
                    <p>{{ $business->address }}</p>
                </div>
                <div>
                    <h3 class="font-semibold mb-2">📞 Telefon</h3>
                    <p>{{ $business->phone }}</p>
                </div>
                <div>
                    <h3 class="font-semibold mb-2">🌐 Strona</h3>
                    <p><a href="{{ $business->website }}" target="_blank" class="hover:underline">{{ $business->website }}</a></p>
                </div>
            </div>
        </div>

        <!-- Usługi -->
        <div class="mb-12">
            <h2 class="text-3xl font-bold mb-6">Nasze usługi</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($business->services()->where('is_active', true)->get() as $service)
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                        <h3 class="text-xl font-bold mb-2">{{ $service->name }}</h3>
                        <p class="text-gray-600 mb-4">{{ $service->description }}</p>
                        <div class="flex justify-between items-center pt-4 border-t">
                            <div class="text-sm text-gray-500">
                                <p>⏱️ {{ $service->duration_minutes }} minut</p>
                                @if ($service->price)
                                    <p>💰 {{ number_format($service->price, 2) }} zł</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-600 col-span-full">Brak dostępnych usług.</p>
                @endforelse
            </div>
        </div>

        <!-- Godziny pracy -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-12">
            <h2 class="text-2xl font-bold mb-4">Godziny pracy</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                @foreach ($dayNames as $key => $dayName)
                    @php
                        $hours = $business->getBusinessHours()[$key];
                    @endphp
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="font-medium">{{ $dayName }}</span>
                        <span>
                            @if ($hours['closed'] ?? false)
                                Zamknięte
                            @else
                                {{ $hours['open'] }} - {{ $hours['close'] }}
                            @endif
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- CTA -->
        <div class="bg-blue-100 border-2 border-blue-600 rounded-lg p-8 text-center">
            <h3 class="text-2xl font-bold text-blue-900 mb-4">Gotów do rezerwacji?</h3>
            <p class="text-blue-800 mb-6">Zarezerwuj preferowany termin w kilka kliknięć</p>
            <a href="/book" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-3 rounded-md">
                Zarezerwuj teraz
            </a>
        </div>
    </div>
</body>
</html>
