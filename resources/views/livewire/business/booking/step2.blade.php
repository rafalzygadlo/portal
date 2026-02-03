<div wire:key="step2">
    <p><strong>Termin:</strong> {{ \Carbon\Carbon::parse($selectedDate)->format('l, d.m.Y') }} o <strong>{{ $selectedTime }}</strong></p>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <button type="button" wire:click="previousWeek" class="btn btn-outline-secondary">←</button>
        <h3 class="h5 fw-semibold mb-0">
            {{ $weekStart->format('d.m.Y') }} - {{ $weekStart->copy()->addDays(6)->format('d.m.Y') }}
        </h3>
        <button type="button" wire:click="nextWeek" class="btn btn-outline-secondary">→</button>
    </div>

    <div class="row row-cols-7 g-2 mb-4">
        @forelse ($weekDays ?? [] as $dateKey => $day)
        <div class="col">
            <div 
                class="border rounded p-2 h-100 {{ $day['isPast'] ? 'bg-light text-muted' : '' }}"
                @if($day['isPast']) style="cursor: not-allowed; opacity: 0.7;" @endif
            >
                <div class="fw-semibold text-center mb-2 small">
                    <div class="text-muted">{{ substr($day['dayName'], 0, 3) }}</div>
                    <div class="h6 mb-0">{{ $day['formatted'] }}</div>
                </div>

                @if ($day['isPast'])
                        <div class="text-center text-muted small p-3">
                        Minął
                    </div>
                @elseif (empty($weekSlots[$dateKey]))
                    <div class="text-center text-muted small p-3">
                        Zamknięte
                    </div>
                @else
                    <div class="d-grid gap-1" style="max-height: 18rem; overflow-y: auto;">
                        @foreach ($weekSlots[$dateKey] as $slot)
                            <button 
                                type="button"
                                wire:click="selectSlot('{{ $slot['fullDateTime'] }}')"
                                class="btn btn-sm
                                    @if ($slot['available'])
                                        @if ($selectedDate === $dateKey && $selectedTime === $slot['time'])
                                            btn-primary
                                        @else
                                            btn-outline-success
                                        @endif
                                    @else
                                        btn-outline-secondary disabled
                                    @endif
                                "
                                @if (!$slot['available']) disabled @endif
                            >
                                {{ $slot['time'] }}
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="col-12 text-center text-muted p-4">
            Ładowanie...
        </div>
    @endforelse
    </div>

    @error('selectedDate') <div class="text-danger small mt-2 d-block">{{ $message }}</div> @enderror
    @error('selectedTime') <div class="text-danger small mt-2 d-block">{{ $message }}</div> @enderror
</div>