<div class="col">


{{-- Header --}}
<div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">

    <div>
        <h1 class="h3 fw-semibold mb-1">
            My tasks
        </h1>

        <p class="text-muted mb-0">
            Reservations assigned to your resources.
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


{{-- Service reservations --}}
<div class="mb-5">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h2 class="h5 fw-semibold mb-1">
                Service reservations
            </h2>

            <p class="small text-muted mb-0">
                Appointments assigned to you.
            </p>
        </div>

        <span class="badge rounded-pill bg-light text-dark border px-3 py-2">
            {{ $reservations->count() }}
            {{ $reservations->count() === 1 ? 'task' : 'tasks' }}
        </span>

    </div>


    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="bg-light">

                    <tr>

                        <th class="px-4 py-3 border-0 small text-uppercase text-muted fw-semibold">
                            Service
                        </th>

                        <th class="py-3 border-0 small text-uppercase text-muted fw-semibold">
                            Client
                        </th>

                        <th class="py-3 border-0 small text-uppercase text-muted fw-semibold">
                            Employee
                        </th>

                        <th class="py-3 border-0 small text-uppercase text-muted fw-semibold">
                            Period
                        </th>

                        <th class="py-3 border-0 small text-uppercase text-muted fw-semibold">
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($reservations as $reservation)

                        <tr>

                            {{-- Service --}}
                            <td class="px-4 py-3">

                                <div class="d-flex align-items-center gap-3">

                                    <div
                                        class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                                        style="width: 40px; height: 40px;"
                                    >
                                        <i class="bi bi-scissors" aria-hidden="true"></i>
                                    </div>

                                    <div class="fw-semibold text-dark">
                                        {{ $reservation->service?->name ?? 'Service' }}
                                    </div>

                                </div>

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


                            {{-- Employee --}}
                            <td>

                                @if ($reservation->companyUser?->user)

                                    <div class="d-flex align-items-center gap-2">

                                        <div
                                            class="rounded-circle bg-light text-secondary d-flex align-items-center justify-content-center flex-shrink-0"
                                            style="width: 34px; height: 34px;"
                                        >
                                            <i class="bi bi-person" aria-hidden="true"></i>
                                        </div>

                                        <span class="fw-medium">
                                            {{ $reservation->companyUser->user->name }}
                                        </span>

                                    </div>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Period --}}
                            <td>

                                <div class="fw-semibold text-dark">
                                    {{ $reservation->start_time->format('d.m.Y H:i') }}
                                </div>

                                <div class="small text-muted mt-1">
                                    <i class="bi bi-clock me-1"></i>
                                    to {{ $reservation->end_time->format('H:i') }}
                                </div>

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
                                        {{ ucfirst($reservation->status) }}
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5" class="px-4">

                                <div class="text-center py-5">

                                    <div
                                        class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3"
                                        style="width: 64px; height: 64px;"
                                    >
                                        <i class="bi bi-calendar-check fs-3 text-muted"></i>
                                    </div>

                                    <h3 class="h6 fw-semibold mb-2">
                                        No service tasks
                                    </h3>

                                    <p class="text-muted small mb-0">
                                        You don't have any service reservations assigned to you.
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


{{-- Resource bookings --}}
<div>

    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h2 class="h5 fw-semibold mb-1">
                Resource bookings
            </h2>

            <p class="small text-muted mb-0">
                Resource reservations assigned to you.
            </p>
        </div>

        <span class="badge rounded-pill bg-light text-dark border px-3 py-2">
            {{ $bookings->count() }}
            {{ $bookings->count() === 1 ? 'task' : 'tasks' }}
        </span>

    </div>


    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

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
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($bookings as $booking)

                        <tr>

                            {{-- Resource --}}
                            <td class="px-4 py-3">

                                <div class="d-flex align-items-center gap-3">

                                    <div
                                        class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                                        style="width: 40px; height: 40px;"
                                    >
                                        <i class="bi bi-box-seam" aria-hidden="true"></i>
                                    </div>

                                    <div class="fw-semibold text-dark">
                                        {{ $booking->resource?->name ?? '-' }}
                                    </div>

                                </div>

                            </td>


                            {{-- Client --}}
                            <td>

                                <div class="fw-semibold text-dark">
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

                                <div class="fw-semibold text-dark">
                                    {{ $booking->start_time->format('d.m.Y H:i') }}
                                </div>

                                <div class="small text-muted mt-1">
                                    <i class="bi bi-clock me-1"></i>
                                    to {{ $booking->end_time->format('H:i') }}
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

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4" class="px-4">

                                <div class="text-center py-5">

                                    <div
                                        class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3"
                                        style="width: 64px; height: 64px;"
                                    >
                                        <i class="bi bi-box-seam fs-3 text-muted"></i>
                                    </div>

                                    <h3 class="h6 fw-semibold mb-2">
                                        No resource tasks
                                    </h3>

                                    <p class="text-muted small mb-0">
                                        You don't have any resource bookings assigned to you.
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

</div>
