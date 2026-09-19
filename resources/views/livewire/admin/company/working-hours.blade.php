
<div class="col">

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Working hours</h1>
            <p class="text-muted mb-0">
                Set when customers can book your services.
            </p>
        </div>

        <a href="{{ route('admin.company.dashboard', ['company' => $company]) }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2" aria-hidden="true"></i>
            Back
        </a>
    </div>

    {{-- Success --}}
    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    {{-- Working hours --}}
    <form wire:submit="save">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-0 p-4 pb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 bg-primary-subtle text-primary d-flex align-items-center justify-content-center"
                         style="width: 42px; height: 42px;">
                        <i class="bi bi-clock fs-5"></i>
                    </div>

                    <div>
                        <h2 class="h5 fw-bold mb-1">Business hours</h2>
                        <p class="small text-muted mb-0">
                            Define the hours available for customer bookings.
                        </p>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">

                @foreach ($workingHours as $day => $hours)
                    <div class="px-4 py-3 {{ !$loop->last ? 'border-bottom' : '' }}">

                        <div class="row align-items-center g-3">

                            {{-- Day --}}
                            <div class="col-md-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-muted"
                                         style="width: 36px; height: 36px;">
                                        <i class="bi bi-calendar3"></i>
                                    </div>

                                    <div>
                                        <div class="fw-semibold text-capitalize">
                                            {{ $day }}
                                        </div>

                                        @if ($hours['closed'] ?? false)
                                            <span class="small text-muted">Closed today</span>
                                        @else
                                            <span class="small text-success">Open for bookings</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Opens --}}
                            <div class="col-md-3">
                                <label class="form-label small text-muted mb-1"
                                       for="{{ $day }}-open">
                                    Opens
                                </label>

                                <input
                                    id="{{ $day }}-open"
                                    type="time"
                                    wire:model="workingHours.{{ $day }}.open"
                                    class="form-control @error("workingHours.$day.open") is-invalid @enderror"
                                    @disabled($hours['closed'] ?? false)
                                >

                                @error("workingHours.$day.open")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Closes --}}
                            <div class="col-md-3">
                                <label class="form-label small text-muted mb-1"
                                       for="{{ $day }}-close">
                                    Closes
                                </label>

                                <input
                                    id="{{ $day }}-close"
                                    type="time"
                                    wire:model="workingHours.{{ $day }}.close"
                                    class="form-control @error("workingHours.$day.close") is-invalid @enderror"
                                    @disabled($hours['closed'] ?? false)
                                >

                                @error("workingHours.$day.close")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Closed --}}
                            <div class="col-md-3">
                                <div class="form-check d-flex align-items-center gap-2 mt-md-3">
                                    <input
                                        id="{{ $day }}-closed"
                                        type="checkbox"
                                        wire:model="workingHours.{{ $day }}.closed"
                                        class="form-check-input"
                                    >

                                    <label for="{{ $day }}-closed"
                                           class="form-check-label fw-medium">
                                        Closed
                                    </label>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach

            </div>

            {{-- Footer --}}
            <div class="card-footer bg-light border-0 p-4">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">

                    <div class="small text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Customers can only book within these hours.
                    </div>

                    <button
                        type="submit"
                        class="btn btn-primary px-4"
                        wire:loading.attr="disabled"
                    >
                        <i class="bi bi-save me-2" aria-hidden="true"></i>
                        Save working hours
                    </button>

                </div>
            </div>

        </div>

    </form>
</div>
