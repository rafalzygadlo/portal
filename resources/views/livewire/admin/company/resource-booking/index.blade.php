<div class="col py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Resource bookings</h1>
            <p class="text-muted mb-0">View and manage all reserved resource periods.</p>
        </div>
        <a href="{{ route('admin.company.dashboard', ['company' => $company]) }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Back</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @php($pendingCount = $bookings->where('status', 'pending')->count())
    @if ($pendingCount > 0)
        <div class="alert alert-warning d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
            <span><strong>Attention:</strong> {{ $pendingCount }} resource booking{{ $pendingCount === 1 ? '' : 's' }} await your decision. Please confirm or cancel them.</span>
        </div>
    @endif

    <div class="table-responsive border shadow bg-white">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Resource</th><th>Client</th><th>Period</th><th>Price</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                @forelse ($bookings->groupBy(fn ($booking) => $booking->start_time->toDateString()) as $date => $dayBookings)
                    <tr class="table-light">
                        <th colspan="6" class="py-2 text-capitalize" wire:click="toggleDate('{{ $date }}')" style="cursor: pointer;">
                            <i class="bi bi-calendar3 me-2 text-primary" aria-hidden="true"></i>
                            {{ $dayBookings->first()->start_time->locale('pl')->translatedFormat('l, j F Y') }}
                            <span class="float-end"><i class="bi {{ in_array($date, $expandedDates, true) ? 'bi-chevron-up' : 'bi-chevron-down' }}" aria-hidden="true"></i> {{ $dayBookings->count() }}</span>
                        </th>
                    </tr>
                    @if (in_array($date, $expandedDates, true))
                        @foreach ($dayBookings as $booking)
                        <tr>
                        <td class="fw-semibold">
                            @php($bookingResources = $booking->resource_ids ? \App\Models\Resource::whereIn('id', $booking->resource_ids)->pluck('name') : collect([$booking->resource?->name]))
                            {{ $bookingResources->join(', ') }}
                        </td>
                        <td>{{ $booking->client_name }} @if($booking->client_email)<div class="small text-muted">{{ $booking->client_email }}</div>@endif</td>
                        <td>
                            <div class="fw-semibold text-capitalize">{{ $booking->start_time->locale('pl')->translatedFormat('l') }}</div>
                            <div>{{ $booking->start_time->format('j F Y') }}</div>
                            <span class="badge bg-light text-dark border mt-1">
                                <i class="bi bi-clock me-1" aria-hidden="true"></i>
                                {{ $booking->start_time->format('H:i') }} - {{ $booking->end_time->format('H:i') }}
                            </span>
                        </td>
                        <td class="fw-semibold">{{ number_format($booking->total_price ?? 0, 2) }} PLN</td>
                        <td><span class="badge {{ $booking->status === 'cancelled' ? 'bg-secondary' : ($booking->status === 'pending' ? 'bg-warning text-dark' : 'bg-success') }}">{{ ucfirst($booking->status) }}</span></td>
                        <td class="text-end">
                            @if($booking->status === 'pending')
                                <button wire:click="confirmBooking({{ $booking->id }})" class="btn btn-sm btn-outline-success me-1">Confirm</button>
                            @endif
                            @if($booking->status !== 'cancelled')
                                <button wire:click="cancelBooking({{ $booking->id }})" wire:confirm="Cancel this resource booking?" class="btn btn-sm btn-outline-danger">Cancel</button>
                            @endif
                        </td>
                        </tr>
                        @endforeach
                    @endif
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No resource bookings yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>