<div class="col py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">My tasks</h1>
            <p class="text-muted mb-0">Reservations assigned to your resources.</p>
        </div>
        <a href="{{ route('admin.company.dashboard', ['company' => $company]) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2" aria-hidden="true"></i>Back
        </a>
    </div>

    <div class="mb-5">
        <h2 class="h5 fw-bold mb-3">Service reservations</h2>
        <div class="table-responsive border shadow bg-white">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Service</th>
                        <th>Client</th>
                        <th>Resource</th>
                        <th>Period</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reservations as $reservation)
                        <tr>
                            <td class="fw-semibold">{{ $reservation->service?->name ?? 'Service' }}</td>
                            <td>{{ $reservation->client_name }}<div class="small text-muted">{{ $reservation->client_email }}</div></td>
                            <td>{{ $reservation->resource?->name ?? '-' }}</td>
                            <td>
                                {{ $reservation->start_time->format('d.m.Y H:i') }}<br>
                                <span class="text-muted">to {{ $reservation->end_time->format('H:i') }}</span>
                            </td>
                            <td><span class="badge bg-warning text-dark">{{ ucfirst($reservation->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No service tasks assigned.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <h2 class="h5 fw-bold mb-3">Resource bookings</h2>
        <div class="table-responsive border shadow bg-white">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Resource</th>
                        <th>Client</th>
                        <th>Period</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $booking)
                        <tr>
                            <td class="fw-semibold">{{ $booking->resource?->name ?? '-' }}</td>
                            <td>{{ $booking->client_name }}<div class="small text-muted">{{ $booking->client_email }}</div></td>
                            <td>
                                {{ $booking->start_time->format('d.m.Y H:i') }}<br>
                                <span class="text-muted">to {{ $booking->end_time->format('H:i') }}</span>
                            </td>
                            <td><span class="badge bg-warning text-dark">{{ ucfirst($booking->status) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">No resource tasks assigned.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>