<div class="employee-calendar max-w-4xl mx-auto p-6 bg-white rounded-lg shadow">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Plan na dzień</h2>
        <input type="date" wire:model.live="selectedDate" class="px-3 py-2 border border-gray-300 rounded-md">
    </div>

    @if (count($reservations) === 0)
        <p class="text-gray-500 text-center py-8">Brak rezerwacji na wybrany dzień</p>
    @else
        <div class="space-y-3">
            @foreach ($reservations as $reservation)
                <div class="border-l-4 border-blue-500 pl-4 py-3 bg-gray-50 rounded">
                    <div class="font-semibold text-lg">{{ $reservation['service']['name'] }}</div>
                    <div class="text-sm text-gray-600">
                        <p>📅 {{ \Carbon\Carbon::parse($reservation['start_time'])->format('H:i') }} - {{ \Carbon\Carbon::parse($reservation['end_time'])->format('H:i') }}</p>
                        <p>👤 {{ $reservation['client_name'] }}</p>
                        <p>📞 {{ $reservation['client_phone'] ?? 'Brak numeru' }}</p>
                    </div>
                    <div class="mt-2">
                        <span class="px-2 py-1 rounded text-xs {{ 
                            $reservation['status'] === 'confirmed' ? 'bg-green-100 text-green-700' :
                            ($reservation['status'] === 'completed' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700')
                        }}">
                            {{ ucfirst($reservation['status']) }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
