<div class="col py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start gap-3 mb-4">
        <div>
            <h1 class="h2 fw-bold">{{ $business->name }}</h1>
            <p class="text-muted mb-0">Reservations management panel</p>
        </div>

        <a href="{{ route('admin.business.subscription', ['business' => $business]) }}" class="d-flex align-items-center gap-3 text-decoration-none border rounded-3 px-3 py-2 bg-light">
            <div class="text-end">
                <span class="d-block text-muted small">Current plan</span>
                <span class="fw-bold text-dark">Starter <span class="fw-normal text-muted">29 zł / month</span></span>
            </div>
            <i class="bi bi-arrow-up-right text-primary" aria-hidden="true"></i>
        </a>
    </div>

    <div class="mb-3">
        <h2 class="h4 fw-bold mb-1">Business modules</h2>
        <p class="text-muted small mb-0">Choose an area to manage</p>
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3 mb-4">
        <div class="col">
            <a href="{{ route('admin.business.services',['business' => $business]) }}" class="btn btn-light border rounded-4 w-100 h-100 p-4 text-start shadow">
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-list-check display-6 text-primary"></i>
                </div>
                <div>
                    <h3 class="h5 fw-bold mb-1">Services</h3>
                    <p class="text-muted small mb-0">Manage service offerings</p>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('admin.business.resources', ['business' => $business]) }}" class="btn btn-light border rounded-4 w-100 h-100 p-4 text-start shadow">
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-people display-6 text-primary"></i>
                </div>
                <div>
                    <h3 class="h5 fw-bold mb-1">Resources</h3>
                    <p class="text-muted small mb-0">Assign equipment and staff</p>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('admin.business.reservations.resources', ['business' => $business]) }}" class="btn btn-light border rounded-4 w-100 h-100 p-4 text-start shadow">
                <div class="d-flex align-items-center mb-3"><i class="bi bi-calendar-check display-6 text-primary"></i></div>
                <div><h3 class="h5 fw-bold mb-1">Resource bookings</h3><p class="text-muted small mb-0">View reserved periods</p></div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('admin.business.reservations.services', ['business' => $business]) }}" class="btn btn-light border rounded-4 w-100 h-100 p-4 text-start shadow">
                <div class="d-flex align-items-center mb-3"><i class="bi bi-calendar-event display-6 text-primary"></i></div>
                <div><h3 class="h5 fw-bold mb-1">Service reservations</h3><p class="text-muted small mb-0">Confirm or cancel bookings</p></div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('admin.business.settings.working-hours', ['business' => $business]) }}" class="btn btn-light border rounded-4 w-100 h-100 p-4 text-start shadow">
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-clock display-6 text-primary"></i>
                </div>
                <div>
                    <h3 class="h5 fw-bold mb-1">Working hours</h3>
                    <p class="text-muted small mb-0">Set your opening hours</p>
                </div>
            </a>
        </div>
        <div class="col">
            {{-- 
            <a href="{{ route('dashboard.business', ['business' => $business, 'tab' => 'reservations']) }}" class="btn btn-light border rounded-4 w-100 h-100 p-4 text-start shadow">
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-calendar-check display-6 text-primary"></i>
                </div>
                <div>
                    <h3 class="h5 fw-bold mb-1">Reservations</h3>
                    <p class="text-muted small mb-0">View and confirm bookings</p>
                </div>
            </a>
        </div>
        <div class="col">
            <a href="{{ route('dashboard.business', ['business' => $business, 'tab' => 'settings']) }}" class="btn btn-light border rounded-4 w-100 h-100 p-4 text-start shadow">
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-sliders display-6 text-primary"></i>
                </div>
                <div>
                    <h3 class="h5 fw-bold mb-1">Settings</h3>
                    <p class="text-muted small mb-0">Update business details</p>
                </div>
            </a>
        </div>
            --}}
        </div>
    </div>

