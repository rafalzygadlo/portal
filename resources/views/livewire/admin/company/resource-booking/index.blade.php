<div class="col">


{{-- Header --}}
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">

    <div>
        <h1 class="h3 fw-semibold mb-1">
            Resource bookings
        </h1>

        <p class="text-muted mb-0">
            View and manage all reserved resource periods.
        </p>
    </div>

    <a
        href="{{ route('admin.company.dashboard', ['company' => $company]) }}"
        class="btn btn-light border d-inline-flex align-items-center"
    >
        <i class="bi bi-arrow-left me-2" aria-hidden="true"></i>
        Back
    </a>

</div>


{{-- Success message --}}
@if(session('success'))

    <div
        class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-center mb-4"
        role="alert"
    >
        <i class="bi bi-check-circle me-2" aria-hidden="true"></i>
        <span>{{ session('success') }}</span>
    </div>

@endif


{{-- Pending bookings --}}
@php
$pendingCount = $bookings->where('status', 'pending')->count();
@endphp

@if ($pendingCount > 0)

    <div
        class="alert alert-warning border-0 shadow-sm rounded-3 d-flex align-items-start gap-3 mb-4"
        role="alert"
    >

        <div
            class="rounded-circle bg-warning-subtle text-warning-emphasis d-flex align-items-center justify-content-center flex-shrink-0"
            style="width: 38px; height: 38px;"
        >
            <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
        </div>

        <div>

            <div class="fw-semibold mb-1">
                Attention required
            </div>

            <div class="small">
                {{ $pendingCount }}
                resource booking{{ $pendingCount === 1 ? '' : 's' }}
                await your decision.
                Please confirm or cancel them.
            </div>

        </div>

    </div>

@endif


{{-- Bookings --}}
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">

    <div class="card-header bg-white border-bottom px-4 py-3">

        <div class="d-flex justify-content-between align-items-center">

            <div>
                <h2 class="h6 fw-semibold mb-1">
                    Reservations
                </h2>

                <p class="small text-muted mb-0">
                    Resource reservations grouped by date.
                </p>
            </div>

            <span class="badge rounded-pill bg-light text-dark border px-3 py-2">
                {{ $bookings->count() }}
                {{ $bookings->count() === 1 ? 'booking' : 'bookings' }}
            </span>

        </div>

    </div>


    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">

            <thead class="bg-light">

                <tr>

                    <th class="px-4 py-3 border-0 small text-uppercase text-muted fw-semibold">
                        Resource
                    </th>

                    <th class="py-3 border-0 small text-uppercase text-muted fw-semibold">
                        Client
                    </th>

                    <th class="py-3 border-0 small text-uppercase text-muted fw-semibold">
                        Period
                    </th>

                    <th class="py-3 border-0 small text-uppercase text-muted fw-semibold">
                        Price
                    </th>

                    <th class="py-3 border-0 small text-uppercase text-muted fw-semibold">
                        Status
                    </th>

                    <th class="px-4 py-3 border-0 text-end small text-uppercase text-muted fw-semibold">
                        Actions
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse ($bookings->groupBy(fn ($booking) => $booking->start_time->toDateString()) as $date => $dayBookings)

                    {{-- Date group --}}
                    <tr class="bg-light">

                        <th
                            colspan="6"
                            class="px-4 py-3 border-bottom"
                            wire:click="toggleDate('{{ $date }}')"
                            style="cursor: pointer;"
                        >

                            <div class="d-flex align-items-center justify-content-between">

                                <div class="d-flex align-items-center gap-3">

                                    <div
                                        class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                                        style="width: 40px; height: 40px;"
                                    >
                                        <i class="bi bi-calendar3" aria-hidden="true"></i>
                                    </div>

                                    <div>

                                        <div class="fw-semibold text-dark text-capitalize">
                                            {{ $dayBookings->first()->start_time->locale('pl')->translatedFormat('l, j F Y') }}
                                        </div>

                                        <div class="small text-muted mt-1">
                                            {{ $dayBookings->count() }}
                                            {{ $dayBookings->count() === 1 ? 'booking' : 'bookings' }}
                                        </div>

                                    </div>

                                </div>


                                <div class="d-flex align-items-center gap-2">

                                    <span class="badge rounded-pill bg-white text-dark border">
                                        {{ $dayBookings->count() }}
                                    </span>

                                    <i
                                        class="bi {{ in_array($date, $expandedDates, true) ? 'bi-chevron-up' : 'bi-chevron-down' }} text-muted"
                                        aria-hidden="true"
                                    ></i>

                                </div>

                            </div>

                        </th>

                    </tr>


                    {{-- Bookings for date --}}
                    @if (in_array($date, $expandedDates, true))

                        @foreach ($dayBookings as $booking)

                            @php
                                $bookingResources = $booking->resource_ids
                                    ? \App\Models\Resource::whereIn('id', $booking->resource_ids)->pluck('name')
                                    : collect([$booking->resource?->name]);
                            @endphp

                            <tr>

                                {{-- Resource --}}
                                <td class="px-4 py-3">

                                    <div class="d-flex align-items-center gap-3">

                                        <div
                                            class="rounded-3 bg-light text-secondary d-flex align-items-center justify-content-center flex-shrink-0"
                                            style="width: 40px; height: 40px;"
                                        >
                                            <i class="bi bi-box-seam" aria-hidden="true"></i>
                                        </div>

                                        <div>

                                            <div class="fw-semibold text-dark">
                                                {{ $bookingResources->join(', ') }}
                                            </div>

                                            @if ($bookingResources->count() > 1)

                                                <div class="small text-muted mt-1">
                                                    {{ $bookingResources->count() }} resources
                                                </div>

                                            @endif

                                        </div>

                                    </div>

                                </td>


                                {{-- Client --}}
                                <td>

                                    <div class="fw-medium text-dark">
                                        {{ $booking->client_name }}
                                    </div>

                                    @if ($booking->client_email)

                                        <div class="small text-muted mt-1">
                                            <i class="bi bi-envelope me-1"></i>
                                            {{ $booking->client_email }}
                                        </div>

                                    @endif

                                </td>


                                {{-- Period --}}
                                <td>

                                    <div class="fw-semibold text-dark text-capitalize">
                                        {{ $booking->start_time->locale('pl')->translatedFormat('l') }}
                                    </div>

                                    <div class="small text-muted mt-1">
                                        {{ $booking->start_time->format('j F Y') }}
                                    </div>

                                    <span class="badge bg-light text-dark border mt-2 px-3 py-2">

                                        <i
                                            class="bi bi-clock me-1 text-muted"
                                            aria-hidden="true"
                                        ></i>

                                        {{ $booking->start_time->format('H:i') }}
                                        –
                                        {{ $booking->end_time->format('H:i') }}

                                    </span>

                                </td>


                                {{-- Price --}}
                                <td>

                                    <div class="fw-semibold text-dark">
                                        {{ number_format($booking->total_price ?? 0, 2) }} PLN
                                    </div>

                                </td>


                                {{-- Status --}}
                                <td>

                                    @if ($booking->status === 'pending')

                                        <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-2">
                                            <i class="bi bi-hourglass-split me-1"></i>
                                            Pending
                                        </span>

                                    @elseif ($booking->status === 'cancelled')

                                        <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2">
                                            <i class="bi bi-x-circle me-1"></i>
                                            Cancelled
                                        </span>

                                    @else

                                        <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3 py-2">
                                            <i class="bi bi-check-circle me-1"></i>
                                            {{ ucfirst($booking->status) }}
                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td class="px-4 py-3 text-end">

                                    <div class="d-flex justify-content-end gap-2">

                                        @if ($booking->status === 'pending')

                                            <button
                                                type="button"
                                                wire:click="confirmBooking({{ $booking->id }})"
                                                class="btn btn-sm btn-outline-success"
                                            >
                                                <i class="bi bi-check-lg me-1"></i>
                                                Confirm
                                            </button>

                                        @endif


                                        @if ($booking->status !== 'cancelled')

                                            <button
                                                type="button"
                                                wire:click="cancelBooking({{ $booking->id }})"
                                                wire:confirm="Cancel this resource booking?"
                                                class="btn btn-sm btn-outline-danger"
                                            >
                                                <i class="bi bi-x-lg me-1"></i>
                                                Cancel
                                            </button>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    @endif


                @empty

                    <tr>

                        <td colspan="6" class="px-4">

                            <div class="text-center py-5">

                                <div
                                    class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3"
                                    style="width: 72px; height: 72px;"
                                >
                                    <i class="bi bi-calendar-x fs-2 text-muted"></i>
                                </div>

                                <h3 class="h6 fw-semibold mb-2">
                                    No resource bookings yet
                                </h3>

                                <p class="text-muted small mb-0">
                                    Resource reservations will appear here once customers make bookings.
                                </p>

                            </div>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


</div>
