
<div class="col ">

    {{-- Header --}}
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-5">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">
                    Company dashboard
                </span>
            </div>

            <h1 class="h2 fw-bold mb-1">{{ $company->name }}</h1>
            <p class="text-muted mb-0">
                Reservations management panel
            </p>
        </div>

        {{-- Current plan --}}
        <a href="{{ route('admin.company.subscription', ['company' => $company]) }}"
           class="text-decoration-none">

            <div class="card border shadow-sm rounded-4">
                <div class="card-body d-flex align-items-center gap-3 px-4 py-3">

                    <div>
                        <span class="d-block text-muted small mb-1">
                            Current plan
                        </span>

                        <div class="fw-bold text-dark">
                            Starter
                            <span class="fw-normal text-muted">
                                · 29 zł / month
                            </span>
                        </div>
                    </div>

                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                         style="width: 38px; height: 38px;">
                        <i class="bi bi-arrow-up-right"></i>
                    </div>

                </div>
            </div>

        </a>
    </div>


    {{-- Section heading --}}
    <div class="mb-4">
        <div class="d-flex align-items-center gap-2 mb-1">
            <h2 class="h4 fw-bold mb-0">Company modules</h2>
            <span class="badge bg-light text-dark border">
                7
            </span>
        </div>

        <p class="text-muted small mb-0">
            Choose an area to manage
        </p>
    </div>


    {{-- Modules --}}
    <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-4 g-4">

        {{-- My tasks --}}
        <div class="col">
            <a href="{{ route('admin.company.my-tasks', ['company' => $company]) }}"
               class="card h-100 border shadow-sm rounded-4 text-decoration-none text-dark">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                             style="width: 48px; height: 48px;">
                            <i class="bi bi-clipboard-check fs-4"></i>
                        </div>

                        <i class="bi bi-arrow-up-right text-muted"></i>
                    </div>

                    <h3 class="h5 fw-bold mb-2">My tasks</h3>
                    <p class="text-muted small mb-0">
                        View your assigned reservations
                    </p>

                </div>
            </a>
        </div>


        {{-- Services --}}
        <div class="col">
            <a href="{{ route('admin.company.services', ['company' => $company]) }}"
               class="card h-100 border shadow-sm rounded-4 text-decoration-none text-dark">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                             style="width: 48px; height: 48px;">
                            <i class="bi bi-list-check fs-4"></i>
                        </div>

                        <i class="bi bi-arrow-up-right text-muted"></i>
                    </div>

                    <h3 class="h5 fw-bold mb-2">Services</h3>
                    <p class="text-muted small mb-0">
                        Manage service offerings
                    </p>

                </div>
            </a>
        </div>


        {{-- Resources --}}
        <div class="col">
            <a href="{{ route('admin.company.resources', ['company' => $company]) }}"
               class="card h-100 border shadow-sm rounded-4 text-decoration-none text-dark">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                             style="width: 48px; height: 48px;">
                            <i class="bi bi-people fs-4"></i>
                        </div>

                        <i class="bi bi-arrow-up-right text-muted"></i>
                    </div>

                    <h3 class="h5 fw-bold mb-2">Resources</h3>
                    <p class="text-muted small mb-0">
                        Assign equipment and staff
                    </p>

                </div>
            </a>
        </div>


        {{-- Users --}}
        <div class="col">
            <a href="{{ route('admin.company.users', ['company' => $company]) }}"
               class="card h-100 border shadow-sm rounded-4 text-decoration-none text-dark">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                             style="width: 48px; height: 48px;">
                            <i class="bi bi-person-vcard fs-4"></i>
                        </div>

                        <i class="bi bi-arrow-up-right text-muted"></i>
                    </div>

                    <h3 class="h5 fw-bold mb-2">Users</h3>
                    <p class="text-muted small mb-0">
                        View assigned users
                    </p>

                </div>
            </a>
        </div>


        {{-- Resource bookings --}}
        <div class="col">
            <a href="{{ route('admin.company.reservations.resources', ['company' => $company]) }}"
               class="card h-100 border shadow-sm rounded-4 text-decoration-none text-dark">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                             style="width: 48px; height: 48px;">
                            <i class="bi bi-calendar-check fs-4"></i>
                        </div>

                        <i class="bi bi-arrow-up-right text-muted"></i>
                    </div>

                    <h3 class="h5 fw-bold mb-2">Resource bookings</h3>
                    <p class="text-muted small mb-0">
                        View reserved periods
                    </p>

                </div>
            </a>
        </div>


        {{-- Service reservations --}}
        <div class="col">
            <a href="{{ route('admin.company.reservations.services', ['company' => $company]) }}"
               class="card h-100 border shadow-sm rounded-4 text-decoration-none text-dark">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                             style="width: 48px; height: 48px;">
                            <i class="bi bi-calendar-event fs-4"></i>
                        </div>

                        <i class="bi bi-arrow-up-right text-muted"></i>
                    </div>

                    <h3 class="h5 fw-bold mb-2">Service reservations</h3>
                    <p class="text-muted small mb-0">
                        Confirm or cancel bookings
                    </p>

                </div>
            </a>
        </div>


        {{-- Working hours --}}
        <div class="col">
            <a href="{{ route('admin.company.settings.working-hours', ['company' => $company]) }}"
               class="card h-100 border shadow-sm rounded-4 text-decoration-none text-dark">

                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                             style="width: 48px; height: 48px;">
                            <i class="bi bi-clock fs-4"></i>
                        </div>

                        <i class="bi bi-arrow-up-right text-muted"></i>
                    </div>

                    <h3 class="h5 fw-bold mb-2">Working hours</h3>
                    <p class="text-muted small mb-0">
                        Set your opening hours
                    </p>

                </div>
            </a>
        </div>

    </div>

</div>

