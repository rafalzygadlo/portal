<div class="business-dashboard max-w-6xl mx-auto p-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold">{{ $business->name }}</h1>
        <p class="text-gray-600">Panel zarządzania rezerwacjami</p>
    </div>

    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabs -->
    <div class="flex gap-4 mb-6 border-b">
        <button 
            wire:click="setTab('services')" 
            :class="{ 'border-b-2 border-blue-600': tab === 'services' }"
            class="px-4 py-2 font-medium {{ $tab === 'services' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600' }}"
        >
            Usługi
        </button>
        <button 
            wire:click="setTab('reservations')"
            class="px-4 py-2 font-medium {{ $tab === 'reservations' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600' }}"
        >
            Rezerwacje
        </button>
        <button 
            wire:click="setTab('settings')"
            class="px-4 py-2 font-medium {{ $tab === 'settings' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600' }}"
        >
            Ustawienia
        </button>
    </div>

    <!-- TAB: USŁUGI -->
    @if ($tab === 'services')
        <div class="space-y-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Twoje usługi</h2>
                <button 
                    wire:click="openServiceModal"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md"
                >
                    + Dodaj usługę
                </button>
            </div>

            @foreach ($services as $service)
                <div class="bg-white p-4 rounded-lg shadow flex justify-between items-start">
                    <div class="flex-1">
                        <h3 class="font-bold text-lg">{{ $service->name }}</h3>
                        <p class="text-gray-600 text-sm">{{ $service->description }}</p>
                        <div class="mt-2 flex gap-4 text-sm text-gray-600">
                            <span>⏱️ {{ $service->duration_minutes }} min</span>
                            @if ($service->price)
                                <span>💰 {{ number_format($service->price, 2) }} zł</span>
                            @endif
                            <span>⏳ Przerwa: {{ $service->buffer_minutes }} min</span>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button 
                            wire:click="toggleServiceActive({{ $service->id }})"
                            class="px-3 py-1 rounded {{ $service->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}"
                        >
                            {{ $service->is_active ? 'Aktywna' : 'Nieaktywna' }}
                        </button>
                        <button 
                            wire:click="openServiceModal({{ $service->id }})"
                            class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200"
                        >
                            Edytuj
                        </button>
                        <button 
                            wire:click="deleteService({{ $service->id }})"
                            class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200"
                            onclick="return confirm('Na pewno usunąć?')"
                        >
                            Usuń
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- TAB: REZERWACJE -->
    @if ($tab === 'reservations')
        <div class="space-y-4">
            <h2 class="text-xl font-bold mb-4">Rezerwacje</h2>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Klient</th>
                            <th class="px-4 py-2 text-left">Usługa</th>
                            <th class="px-4 py-2 text-left">Data i czas</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reservations as $reservation)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2">
                                    <div>{{ $reservation->client_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $reservation->client_email }}</div>
                                </td>
                                <td class="px-4 py-2">{{ $reservation->service->name }}</td>
                                <td class="px-4 py-2">
                                    {{ $reservation->start_time->format('d.m.Y H:i') }}<br>
                                    <span class="text-sm text-gray-500">{{ $reservation->service->duration_minutes }} min</span>
                                </td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded text-sm {{ 
                                        $reservation->status === 'confirmed' ? 'bg-green-100 text-green-700' :
                                        ($reservation->status === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700')
                                    }}">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 space-x-2">
                                    @if ($reservation->status === 'pending')
                                        <button 
                                            wire:click="confirmReservation({{ $reservation->id }})"
                                            class="text-green-600 hover:underline"
                                        >
                                            Potwierdź
                                        </button>
                                    @endif
                                    @if ($reservation->status !== 'cancelled')
                                        <button 
                                            wire:click="cancelReservation({{ $reservation->id }})"
                                            class="text-red-600 hover:underline"
                                        >
                                            Anuluj
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                                    Brak rezerwacji
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $reservations->links() }}
            </div>
        </div>
    @endif

    <!-- TAB: USTAWIENIA -->
    @if ($tab === 'settings')
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4">Ustawienia biznesu</h2>
            <p class="text-gray-600">Godziny pracy i inne ustawienia będą dostępne tutaj.</p>
        </div>
    @endif

    <!-- MODAL: Edycja usługi -->
    @if ($showServiceModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                <h3 class="text-xl font-bold mb-4">
                    {{ $editingService ? 'Edytuj usługę' : 'Nowa usługa' }}
                </h3>

                <form wire:submit="saveService" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Nazwa</label>
                        <input type="text" wire:model="serviceName" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
                        @error('serviceName')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Opis</label>
                        <textarea wire:model="serviceDescription" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Czas trwania (min)</label>
                            <input type="number" wire:model="serviceDuration" min="15" max="480" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
                            @error('serviceDuration')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Przerwa (min)</label>
                            <input type="number" wire:model="serviceBuffer" min="0" max="120" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
                            @error('serviceBuffer')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Cena (PLN)</label>
                        <input type="number" wire:model="servicePrice" step="0.01" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-blue-500">
                    </div>

                    <div class="flex gap-2 pt-4">
                        <button 
                            type="submit"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md"
                        >
                            Zapisz
                        </button>
                        <button 
                            type="button"
                            wire:click="closeServiceModal"
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md"
                        >
                            Anuluj
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
