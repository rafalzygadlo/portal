<div class="col py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Service reservations</h1>
            <p class="text-muted mb-0">Confirm or cancel appointments with your team.</p>
        </div>
        <a href="{{ route('admin.business.dashboard', ['business' => $business]) }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Back</a>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="resource-filter" class="form-label small text-muted mb-1">Filter by employee</label>
                    <select id="resource-filter" wire:model.live="resourceFilter" class="form-select">
                        <option value="">All employees</option>
                        @foreach ($people as $person)
                            <option value="{{ $person->id }}">{{ $person->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="status-filter" class="form-label small text-muted mb-1">Filter by status</label>
                    <select id="status-filter" wire:model.live="statusFilter" class="form-select">
                        <option value="">All statuses</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3 text-md-end">
                    <span class="text-muted small">{{ $reservations->count() }} reservation(s)</span>
                </div>
            </div>
        </div>
    </div>

    <h2 class="h5 fw-bold mb-3">Reservations by date</h2>
    @php($pendingCount = $reservations->where('status', 'pending')->count())
    @if ($pendingCount > 0)
        <div class="alert alert-warning d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
            <span><strong>Attention:</strong> {{ $pendingCount }} service reservation{{ $pendingCount === 1 ? '' : 's' }} await your decision. Please confirm or cancel them.</span>
        </div>
    @endif

    <div class="table-responsive border shadow bg-white">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Service</th><th>Person</th><th>Client</th><th>Period</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                @forelse ($reservations->groupBy(fn ($reservation) => $reservation->start_time->toDateString()) as $date => $dayReservations)
                    <tr class="table-light">
                        <th colspan="6" class="py-2 text-capitalize" wire:click="toggleDate('{{ $date }}')" style="cursor: pointer;">
                            <i class="bi bi-calendar3 me-2 text-primary" aria-hidden="true"></i>
                            {{ $dayReservations->first()->start_time->locale('pl')->translatedFormat('l, j F Y') }}
                            <span class="float-end"><i class="bi {{ in_array($date, $expandedDates, true) ? 'bi-chevron-up' : 'bi-chevron-down' }}" aria-hidden="true"></i> {{ $dayReservations->count() }}</span>
                        </th>
                    </tr>
                    @if (in_array($date, $expandedDates, true))
                        @foreach ($dayReservations as $reservation)
                        <tr>
                        <td class="fw-semibold">{{ $reservation->services->pluck('name')->join(', ') ?: $reservation->service?->name }}</td>
                        <td>
                            @if ($reservation->resource)
                            <div class="fw-semibold text-capitalize">{{ $reservation->start_time->locale('pl')->translatedFormat('l') }}</div>
                            <div>{{ $reservation->start_time->format('j F Y') }}</div>
                            <span class="badge bg-light text-dark border mt-1">
                                <i class="bi bi-clock me-1" aria-hidden="true"></i>
                                {{ $reservation->start_time->format('H:i') }} - {{ $reservation->end_time->format('H:i') }}
                            </span>
                                <span class="text-muted">Any available person</span>
                            @endif
                        </td>
                        <td>{{ $reservation->client_name }}<div class="small text-muted">{{ $reservation->client_email }}</div></td>
                        <td>
                            <span class="fw-semibold">{{ $reservation->start_time->locale('pl')->translatedFormat('l, j F Y') }}</span><br>
                            <span class="text-muted">{{ $reservation->start_time->format('H:i') }} - {{ $reservation->end_time->format('H:i') }}</span>
                        </td>
                        <td><span class="badge {{ $reservation->status === 'cancelled' ? 'bg-secondary' : ($reservation->status === 'pending' ? 'bg-warning text-dark' : 'bg-success') }}">{{ ucfirst($reservation->status) }}</span></td>
                        <td class="text-end">
                            @if ($reservation->status === 'pending')
                                <button wire:click="confirmReservation({{ $reservation->id }})" class="btn btn-sm btn-outline-success me-1">Confirm</button>
                            @endif
                            @if ($reservation->status !== 'cancelled')
                                <button wire:click="cancelReservation({{ $reservation->id }})" wire:confirm="Cancel this reservation?" class="btn btn-sm btn-outline-danger">Cancel</button>
                            @endif
                        </td>
                        </tr>
                        @endforeach
                    @endif
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No service reservations yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

