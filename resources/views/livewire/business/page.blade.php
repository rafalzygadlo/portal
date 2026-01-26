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

    <!-- Kategorie -->
    @if($business->categories->isNotEmpty())
        <div class="mb-8">
            <h3 class="text-lg font-semibold mb-3">Kategorie</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($business->categories as $category)
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                        {{ $category->name }}
                    </span>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Usługi rezerwacji -->
    @if(count($services) > 0)
        <div class="mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Dostępne usługi</h2>
                @if($business->subdomain)
                    <a href="http://{{ $business->subdomain }}.localhost/book" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
                        Zarezerwuj termin
                    </a>
                @endif
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

                        @if($business->subdomain)
                            <a href="http://{{ $business->subdomain }}.localhost/book" class="block w-full bg-blue-100 hover:bg-blue-200 text-blue-700 font-medium py-2 px-4 rounded text-center">
                                Zarezerwuj
                            </a>
                        @endif
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

    <!-- Panel administracyjny dla właściciela -->
    @if($isOwner)
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8">
            <h3 class="font-bold text-yellow-800 mb-2">Panel Administracyjny</h3>
            <a href="{{ route('dashboard.business', $business) }}" class="inline-block bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded">
                Zarządzaj rezerwacjami
            </a>
        </div>
    @endif

    <!-- Opis biznesu -->
    <div class="bg-white border border-gray-200 rounded-lg p-6 mb-8">
        <h3 class="text-xl font-bold mb-4">O biznesie</h3>
        <div class="prose prose-sm max-w-none text-gray-700">
            {{ nl2br(e($business->description)) }}
        </div>
    </div>

    <!-- Informacja o właścicielu -->
    <div class="bg-gray-50 rounded-lg p-6 mb-8">
        <p class="text-sm text-gray-600">
            Biznes dodany: <strong>{{ $business->created_at->format('d.m.Y H:i') }}</strong><br>
            Właściciel: <strong><a href="{{ route('user.profile', $business->user) }}" class="text-blue-600 hover:underline">{{ $business->user->first_name }} {{ $business->user->last_name }}</a></strong>
        </p>
    </div>

    <!-- Komentarze -->
    <div class="mb-8">
        <h3 class="text-2xl font-bold mb-6">Komentarze i opinie</h3>
        <livewire:comments :model="$business" />
    </div>
</div>
