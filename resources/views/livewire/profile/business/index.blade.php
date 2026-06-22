<div class="card border-0">
    <div class="card-body">
        @if($businesses->count() > 0)
            <div class="list-group list-group-flush">
                @foreach($businesses as $business)
                    <div class="list-group-item d-flex justify-content-between align-items-start px-0 py-3">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold">{{ $business->name }}</h6>
                            <p class="text-muted small mb-2">{{ Str::limit($business->description, 100) }}</p>
                            <small class="text-muted">Subdomain: <code>{{ $business->subdomain }}</code></small>
                        </div>
                        <div class="d-flex gap-2 ms-2">
                            <a href="https://{{ $business->subdomain }}.{{ config('app.business_domain') }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                <i class="bi bi-globe"></i> View
                            </a>
                            <a href="{{ route('profile.business.show', $business) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-light text-center py-5">
                <i class="bi bi-building text-muted" style="font-size: 2rem;"></i>
                <p class="mt-3 text-muted">You haven't created any businesses yet.</p>
                <a href="{{ route('profile.business.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Create your first business
                </a>
            </div>
        @endif
    </div>
</div>
