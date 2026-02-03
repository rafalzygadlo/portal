<div wire:key="step4">
    <h3 class="fw-semibold mb-3 h5">Podsumowanie rezerwacji</h3>
    @if($selectedService)
        <div class="alert alert-light">
            <p><strong>Usługa:</strong> {{ $selectedService->name }} ({{ $selectedService->duration_minutes }} min)</p>
            <p><strong>Termin:</strong> {{ \Carbon\Carbon::parse($selectedDate)->format('l, d.m.Y') }} o <strong>{{ $selectedTime }}</strong></p>
            <p><strong>Imię i nazwisko:</strong> {{ $clientName }}</p>
            <p class="mb-0"><strong>Email:</strong> {{ $clientEmail }}</p>
        </div>
    @endif
</div>