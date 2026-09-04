<div class="col py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Working hours</h1>
            <p class="text-muted mb-0">Set when customers can book your services.</p>
        </div>
        <a href="{{ route('admin.business.dashboard', ['business' => $business]) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2" aria-hidden="true"></i>Back
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form wire:submit="save" class="card border-0 shadow-sm">
        <div class="card-body p-4">
            @foreach ($workingHours as $day => $hours)
                <div class="row align-items-center g-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="col-md-3 fw-semibold text-capitalize">{{ $day }}</div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted mb-1" for="{{ $day }}-open">Opens</label>
                        <input id="{{ $day }}-open" type="time" wire:model="workingHours.{{ $day }}.open"
                            class="form-control @error("workingHours.$day.open") is-invalid @enderror"
                            @disabled($hours['closed'] ?? false)>
                        @error("workingHours.$day.open") <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted mb-1" for="{{ $day }}-close">Closes</label>
                        <input id="{{ $day }}-close" type="time" wire:model="workingHours.{{ $day }}.close"
                            class="form-control @error("workingHours.$day.close") is-invalid @enderror"
                            @disabled($hours['closed'] ?? false)>
                        @error("workingHours.$day.close") <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <div class="form-check mt-md-4">
                            <input id="{{ $day }}-closed" type="checkbox" wire:model="workingHours.{{ $day }}.closed" class="form-check-input">
                            <label for="{{ $day }}-closed" class="form-check-label">Closed</label>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="card-footer bg-white border-0 p-4 pt-0 text-end">
            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                <i class="bi bi-save me-2" aria-hidden="true"></i>Save working hours
            </button>
        </div>
    </form>
</div>