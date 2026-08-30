<div class="col py-4">
    <div class="mb-4">
        <h1 class="h2 fw-bold">{{ $business->name }}</h1>
        <p class="text-muted">Reservations management panel</p>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <span class="badge rounded-pill bg-primary-subtle text-primary mb-2">Current plan</span>
                    <h3 class="h5 mb-0 fw-bold">Starter</h3>
                </div>
                <a href="{{ route('admin.business.subscription', ['business' => $business]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                    Manage
                </a>
            </div>

            <div class="d-flex align-items-end gap-2 mb-2">
                <span class="display-6 fw-bold lh-1">29 zł</span>
                <span class="text-muted small">/ month</span>
            </div>

            <div class="progress rounded-pill" style="height: 8px;">
                <div class="progress-bar bg-primary" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
            </div>

            <div class="mt-3 small text-muted d-flex justify-content-between">
                <span>Included services: 3 / 5</span>
                <span>Next billing: 12 Sep</span>
            </div>
        </div>
    </div>

    <!-- Large icon button menu -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3 mb-4">
        <div class="col">
            <a href="{{ route('admin.business.services.index',['business' => $business]) }}" class="btn btn-light border rounded-4 w-100 h-100 p-4 text-start shadow">
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
            <a href="{{ route('admin.business.resources.index', ['business' => $business]) }}" class="btn btn-light border rounded-4 w-100 h-100 p-4 text-start shadow">
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

