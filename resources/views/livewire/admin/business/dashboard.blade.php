<div class="business-dashboard container py-4">
    <div class="mb-4">
        <h1 class="h2 fw-bold">{{ $business->name }}</h1>
        <p class="text-muted">Reservations management panel</p>
    </div>

    <!-- Large icon button menu -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3 mb-4">
        <div class="col">
            <a href="{{ route('admin.business.services.index',['subdomain' => $business->subdomain]) }}" class="btn btn-light border rounded-4 w-100 h-100 p-4 text-start shadow-sm">
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
            <a href="{{ route('admin.business.resources.index', ['subdomain' => $business->subdomain]) }}" class="btn btn-light border rounded-4 w-100 h-100 p-4 text-start shadow-sm">
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
            <a href="{{ route('dashboard.business', ['business' => $business, 'tab' => 'reservations']) }}" class="btn btn-light border rounded-4 w-100 h-100 p-4 text-start shadow-sm">
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
            <a href="{{ route('dashboard.business', ['business' => $business, 'tab' => 'settings']) }}" class="btn btn-light border rounded-4 w-100 h-100 p-4 text-start shadow-sm">
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

