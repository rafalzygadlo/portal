<div class="mb-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <button type="button" wire:click="previousMonth" class="btn btn-sm btn-light border rounded-circle" style="width: 2rem; height: 2rem;">
            <i class="bi bi-chevron-left" aria-hidden="true"></i>
        </button>
        <span class="fw-bold text-capitalize">{{ \Carbon\Carbon::createFromFormat('Y-m', $calendarMonth, 'Europe/Warsaw')->locale('pl')->translatedFormat('F Y') }}</span>
        <button type="button" wire:click="nextMonth" class="btn btn-sm btn-light border rounded-circle" style="width: 2rem; height: 2rem;">
            <i class="bi bi-chevron-right" aria-hidden="true"></i>
        </button>
    </div>
    <div class="d-grid text-center small text-muted fw-semibold mb-1" style="grid-template-columns: repeat(7, 1fr);">
        @foreach (['Pn', 'Wt', 'Śr', 'Cz', 'Pt', 'So', 'Nd'] as $weekdayLabel)
            <span>{{ $weekdayLabel }}</span>
        @endforeach
    </div>
    <div class="d-grid gap-1" style="grid-template-columns: repeat(7, 1fr);">
        @foreach ($calendarDays as $index => $calendarDay)
            @if ($index === 0)
                @for ($i = 1; $i < $calendarDay['weekday']; $i++)
                    <span></span>
                @endfor
            @endif
            <button type="button" wire:click="selectDate('{{ $calendarDay['date'] }}')"
                @disabled($calendarDay['isPast'] || $calendarDay['isClosed'])
                title="{{ $calendarDay['isClosed'] ? 'Closed' : '' }}"
                class="btn btn-sm rounded-circle p-0 mx-auto {{ $selectedDate === $calendarDay['date'] ? 'btn-primary' : 'btn-outline-secondary border-0' }} {{ $calendarDay['isPast'] || $calendarDay['isClosed'] ? 'text-muted opacity-50' : '' }}"
                style="width: 2.25rem; height: 2.25rem;">
                {{ $calendarDay['day'] }}
            </button>
        @endforeach
    </div>
</div>
