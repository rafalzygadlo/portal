<div class="col py-4">


{{-- Header --}}
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">

    <div>
        <h1 class="h3 fw-semibold mb-1">
            Service reservations
        </h1>

        <p class="text-muted mb-0">
            Confirm or cancel appointments with your team.
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


{{-- Filters --}}
<div class="card border-0 shadow-sm rounded-4 mb-4">

    <div class="card-body p-4">

        <div class="row g-3 align-items-end">

            <div class="col-md-5">

                <label
                    for="resource-filter"
                    class="form-label small text-muted mb-1"
                >
                    Filter by employee
                </label>

                <select
                    id="resource-filter"
                    wire:model.live="companyUserFilter"
                    class="form-select"
                >
                    <option value="">All employees</option>

                    @foreach ($people as $person)
                        <option value="{{ $person->id }}">
                            {{ $person->display_name }}
                        </option>
                    @endforeach

                </select>

            </div>


            <div class="col-md-4">

                <label
                    for="status-filter"
                    class="form-label small text-muted mb-1"
                >
                    Filter by status
                </label>

                <select
                    id="status-filter"
                    wire:model.live="statusFilter"
                    class="form-select"
                >
                    <option value="">All statuses</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="cancelled">Cancelled</option>
                </select>

            </div>


            <div class="col-md-3">

                <div class="text-md-end">

                    <div class="small text-muted mb-1">
                        Total reservations
                    </div>

                    <div class="fw-semibold">
                        {{ $reservations->count() }}
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- Section header --}}
<div class="d-flex justify-content-between align-items-center mb-3">

    <div>
        <h2 class="h5 fw-semibold mb-1">
            Reservations by date
        </h2>

        <p class="small text-muted mb-0">
            Review appointments and manage their status.
        </p>
    </div>

</div>


{{-- Pending reservations --}}
@php($pendingCount = $reservations->where('status', 'pending')->count())

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
                service reservation{{ $pendingCount === 1 ? '' : 's' }}
                await your decision.
                Please confirm or cancel them.
            </div>

        </div>

    </div>

@endif


{{-- Reservations --}}
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">

    <div class="card-header bg-white border-bottom px-4 py-3">

        <div class="d-flex justify-content-between align-items-center">

            <div>
                <h3 class="h6 fw-semibold mb-1">
                    Appointments
                </h3>

                <p class="small text-muted mb-0">
                    Service reservations grouped by date.
                </p>
            </div>

            <span class="badge rounded-pill bg-light text-dark border px-3 py-2">
                {{ $reservations->count() }}
                {{ $reservations->count() === 1 ? 'reservation' : 'reservations' }}
            </span>

        </div>

    </div>


    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">

            <thead class="bg-light">

                <tr>

                    <th class="px-4 py-3 border-0 small text-uppercase text-muted fw-semibold">
                        Service
                    </th>

                    <th class="py-3 border-0 small text-uppercase text-muted fw-semibold">
                        Person
                    </th>

                    <th class="py-3 border-0 small text-uppercase text-muted fw-semibold">
                        Client
                    </th>

                    <th class="py-3 border-0 small text-uppercase text-muted fw-semibold">
                        Period
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

                @forelse ($reservations->groupBy(fn ($reservation) => $reservation->start_time->toDateString()) as $date => $dayReservations)

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
                                            {{ $dayReservations->first()->start_time->locale('pl')->translatedFormat('l, j F Y') }}
                                        </div>

                                        <div class="small text-muted mt-1">
                                            {{ $dayReservations->count() }}
                                            {{ $dayReservations->count() === 1 ? 'reservation' : 'reservations' }}
                                        </div>

                                    </div>

                                </div>


                                <div class="d-flex align-items-center gap-2">

                                    <span class="badge rounded-pill bg-white text-dark border">
                                        {{ $dayReservations->count() }}
                                    </span>

                                    <i
                                        class="bi {{ in_array($date, $expandedDates, true) ? 'bi-chevron-up' : 'bi-chevron-down' }} text-muted"
                                        aria-hidden="true"
                                    ></i>

                                </div>

                            </div>

                        </th>

                    </tr>


                    {{-- Reservations for date --}}
                    @if (in_array($date, $expandedDates, true))

                        @foreach ($dayReservations as $reservation)

                            <tr>

                                {{-- Service --}}
                                <td class="px-4 py-3">

                                    <div class="d-flex align-items-center gap-3">

                                        <div
                                            class="rounded-3 bg-light text-secondary d-flex align-items-center justify-content-center flex-shrink-0"
                                            style="width: 40px; height: 40px;"
                                        >
                                            <i class="bi bi-scissors" aria-hidden="true"></i>
                                        </div>

                                        <div>

                                            <div class="fw-semibold text-dark">
                                                {{ $reservation->services->pluck('name')->join(', ') ?: $reservation->service?->name }}
                                            </div>

                                        </div>

                                    </div>

                                </td>


                                {{-- Person --}}
                                <td>

                                    @if ($reservation->companyUser)

                                        <div class="d-flex align-items-center gap-2">

                                            <div
                                                class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                                                style="width: 34px; height: 34px;"
                                            >
                                                <i class="bi bi-person" aria-hidden="true"></i>
                                            </div>

                                            <span class="fw-semibold">
                                                {{ $reservation->companyUser->display_name }}
                                            </span>

                                        </div>

                                    @else

                                        <span class="text-muted">
                                            No person assigned
                                        </span>

                                    @endif

                                </td>


                                {{-- Client --}}
                                <td>

                                    <div class="fw-semibold text-dark">
                                        {{ $reservation->client_name }}
                                    </div>

                                    @if ($reservation->client_email)

                                        <div class="small text-muted mt-1">
                                            <i class="bi bi-envelope me-1"></i>
                                            {{ $reservation->client_email }}
                                        </div>

                                    @endif

                                </td>


                                {{-- Period --}}
                                <td>

                                    <div class="fw-semibold text-dark text-capitalize">
                                        {{ $reservation->start_time->locale('pl')->translatedFormat('l, j F Y') }}
                                    </div>

                                    <span class="badge bg-light text-dark border mt-2 px-3 py-2">

                                        <i
                                            class="bi bi-clock me-1 text-muted"
                                            aria-hidden="true"
                                        ></i>

                                        {{ $reservation->start_time->format('H:i') }}
                                        –
                                        {{ $reservation->end_time->format('H:i') }}

                                    </span>

                                </td>


                                {{-- Status --}}
                                <td>

                                    @if ($reservation->status === 'pending')

                                        <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle px-3 py-2">
                                            <i class="bi bi-hourglass-split me-1"></i>
                                            Pending
                                        </span>

                                    @elseif ($reservation->status === 'cancelled')

                                        <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2">
                                            <i class="bi bi-x-circle me-1"></i>
                                            Cancelled
                                        </span>

                                    @else

                                        <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3 py-2">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Confirmed
                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td class="px-4 py-3 text-end">

                                    <div class="d-flex justify-content-end gap-2">

                                        @if ($reservation->status === 'pending')

                                            <button
                                                type="button"
                                                wire:click="confirmReservation({{ $reservation->id }})"
                                                class="btn btn-sm btn-outline-success"
                                            >
                                                <i class="bi bi-check-lg me-1"></i>
                                                Confirm
                                            </button>

                                        @endif


                                        @if ($reservation->status !== 'cancelled')

                                            <button
                                                type="button"
                                                wire:click="cancelReservation({{ $reservation->id }})"
                                                wire:confirm="Cancel this reservation?"
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
                                    No service reservations yet
                                </h3>

                                <p class="text-muted small mb-0">
                                    Service appointments will appear here once customers make reservations.
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
