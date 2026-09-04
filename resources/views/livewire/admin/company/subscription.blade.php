<div class="col py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Subscription plans</h1>
            <p class="text-muted mb-0">Choose a plan that fits your company and booking needs.</p>
        </div>
        <a href="{{ route('admin.company.dashboard', ['company' => $company]) }}" class="btn btn-sm btn-outline-secondary rounded-pill">
            Back to dashboard
        </a>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h5 fw-bold mb-0">Starter</h2>
                        <span class="badge bg-light text-dark">Current</span>
                    </div>

                    <div class="mb-3">
                        <span class="display-6 fw-bold">29 zł</span>
                        <span class="text-muted">/ month</span>
                    </div>

                    <ul class="list-unstyled small text-muted mb-4">
                        <li class="mb-2">✓ 3 active services</li>
                        <li class="mb-2">✓ Basic calendar</li>
                        <li class="mb-2">✓ Email reminders</li>
                    </ul>

                    <button type="button" class="btn btn-primary w-100 rounded-pill">Selected plan</button>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 border border-primary shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h5 fw-bold mb-0">Growth</h2>
                        <span class="badge bg-primary-subtle text-primary">Best value</span>
                    </div>

                    <div class="mb-3">
                        <span class="display-6 fw-bold">59 zł</span>
                        <span class="text-muted">/ month</span>
                    </div>

                    <ul class="list-unstyled small text-muted mb-4">
                        <li class="mb-2">✓ Unlimited services</li>
                        <li class="mb-2">✓ Team members</li>
                        <li class="mb-2">✓ Advanced analytics</li>
                    </ul>

                    <button type="button" class="btn btn-outline-primary w-100 rounded-pill">Choose Growth</button>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h5 fw-bold mb-0">Premium</h2>
                        <span class="badge bg-dark-subtle text-dark">Pro</span>
                    </div>

                    <div class="mb-3">
                        <span class="display-6 fw-bold">99 zł</span>
                        <span class="text-muted">/ month</span>
                    </div>

                    <ul class="list-unstyled small text-muted mb-4">
                        <li class="mb-2">✓ All Growth features</li>
                        <li class="mb-2">✓ Priority support</li>
                        <li class="mb-2">✓ API access</li>
                    </ul>

                    <button type="button" class="btn btn-outline-dark w-100 rounded-pill">Choose Premium</button>
                </div>
            </div>
        </div>
    </div>
</div>
