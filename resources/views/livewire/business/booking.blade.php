<div class="booking-widget max-w-6xl mx-auto p-6 bg-white rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-6">Zarezerwuj termin</h2>

    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit="book" class="space-y-6">
        <!-- Wybор usługi -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Usługa</label>
            <select wire:model.live="selectedServiceId" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
                <option value="">Wybierz usługę</option>
                @forelse ($services as $service)
                    <option value="{{ $service->id }}">
                        {{ $service->name }} - {{ $service->duration_minutes }} min
                        @if ($service->price)
                            - {{ number_format($service->price, 2) }} zł
                        @endif
                    </option>
                @empty
                    <option disabled>Brak dostępnych usług</option>
                @endforelse
            </select>
            @error('selectedServiceId')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Kalendarz tygodniowy -->
        @if ($this->selectedService)
            <div>
                <div class="flex justify-between items-center mb-4">
                    <button type="button" wire:click="previousWeek" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded">← Poprzedni tydzień</button>
                    <h3 class="text-lg font-semibold">
                        {{ $weekStart->format('d.m.Y') }} - {{ $weekStart->copy()->addDays(6)->format('d.m.Y') }}
                    </h3>
                    <button type="button" wire:click="nextWeek" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded">Następny tydzień →</button>
                </div>

                <!-- Dni tygodnia z godzinami -->
                <div class="grid grid-cols-7 gap-2 mb-6">
                    @forelse ($weekDays ?? [] as $dateKey => $day)
                        <div class="border border-gray-200 rounded-lg p-3 bg-gray-50">
                            <div class="font-semibold text-center mb-3 text-sm">
                                <div class="text-gray-600">{{ substr($day['dayName'], 0, 3) }}</div>
                                <div class="text-lg">{{ $day['formatted'] }}</div>
                            </div>

                            @if (empty($weekSlots[$dateKey]))
                                <div class="text-center text-gray-500 text-sm p-3">
                                    Zamknięte
                                </div>
                            @else
                                <div class="space-y-1 max-h-96 overflow-y-auto">
                                    @foreach ($weekSlots[$dateKey] as $slot)
                                        <button 
                                            type="button"
                                            wire:click="selectSlot('{{ $slot['fullDateTime'] }}')"
                                            class="w-full py-2 px-2 text-xs rounded transition
                                                @if ($slot['available'])
                                                    @if ($selectedDate === $dateKey && $selectedTime === $slot['time'])
                                                        bg-blue-600 text-white
                                                    @else
                                                        bg-green-100 text-green-800 hover:bg-green-200
                                                    @endif
                                                @else
                                                    bg-red-100 text-red-500 cursor-not-allowed opacity-50
                                                @endif
                                            "
                                            @if (!$slot['available']) disabled @endif
                                        >
                                            {{ $slot['time'] }}-{{ $slot['endTime'] }}
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center text-gray-500 p-4">
                            Proszę wybrać usługę
                        </div>
                    @endforelse
                </div>

                <!-- Podsumowanie wybranego terminu -->
                @if ($selectedDate && $selectedTime)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <h4 class="font-semibold text-blue-900 mb-2">Wybrany termin:</h4>
                        <p class="text-blue-800">
                            {{ \Carbon\Carbon::parse($selectedDate)->format('l, d.m.Y') }} o godzinie <strong>{{ $selectedTime }}</strong>
                            <br>
                            <span class="text-sm">Usługa: <strong>{{ $this->selectedService->name }}</strong> ({{ $this->selectedService->duration_minutes }} minut)</span>
                        </p>
                    </div>
                @endif
            </div>

            @error('selectedDate')
                <span class="text-red-500 text-sm block">{{ $message }}</span>
            @enderror
            @error('selectedTime')
                <span class="text-red-500 text-sm block">{{ $message }}</span>
            @enderror
        @endif

        <!-- Dane klienta -->
        <div class="border-t pt-6">
            <h3 class="font-semibold mb-4 text-lg">Twoje dane</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Imię i Nazwisko</label>
                    <input type="text" wire:model="clientName" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
                    @error('clientName')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" wire:model="clientEmail" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
                    @error('clientEmail')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Telefon (opcjonalnie)</label>
                <input type="tel" wire:model="clientPhone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Notatki (opcjonalnie)</label>
                <textarea wire:model="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500"></textarea>
            </div>
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition">
            Zarezerwuj
        </button>
    </form>
    <style>
    .booking-widget1 {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    
    .booking-widget1 button:disabled {
        cursor: not-allowed;
    }
</style>

</div>

