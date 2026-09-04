<div class="col py-4">
    <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Rent equipment</h1>
            <p class="text-muted mb-0">Choose equipment and the time you need it.</p>
        </div>
        <a href="{{ route('business.domain', ['business' => $business]) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2" aria-hidden="true"></i>Back
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif ($step === 1)
        <div class="row g-3">
            @forelse ($equipment as $resource)
                <div class="col-md-6 col-lg-4">
                    <button type="button" wire:click="toggleResource({{ $resource->id }})" class="card w-100 h-100 text-start border shadow-sm p-0 {{ in_array($resource->id, $resourceIds) ? 'border-primary bg-light' : '' }}">
                        <div class="card-body">
                            <i class="bi {{ in_array($resource->id, $resourceIds) ? 'bi-check-circle-fill' : 'bi-tools' }} fs-2 text-primary"></i>
                            <h2 class="h5 mt-3 mb-1">{{ $resource->name }}</h2>
                            <span class="text-muted small">{{ $resource->hourly_rate ? number_format($resource->hourly_rate, 2) . ' PLN / hour' : 'Price on request' }}</span>
                        </div>
                    </button>
                </div>
            @empty
                <div class="alert alert-secondary">No equipment is currently available for rent.</div>
            @endforelse
        </div>
        @error('resourceIds') <div class="text-danger small mt-2">Choose at least one item.</div> @enderror
        <div class="d-flex justify-content-end mt-4"><button type="button" wire:click="continueResources" class="btn btn-primary">Continue</button></div>
    @elseif ($step === 2)
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h2 class="h5 mb-2">Selected equipment</h2>
                <p class="text-muted mb-4">{{ $selectedResources->pluck('name')->join(', ') }}</p>
                <div class="mb-3">
                    <label for="duration-hours" class="form-label fw-semibold">How long do you need it?</label>
                    <div class="input-group">
                        <input id="duration-hours" type="number" min="1" max="24" wire:model.live="durationHours" class="form-control @error('durationHours') is-invalid @enderror">
                        <span class="input-group-text">hours</span>
                    </div>
                    @error('durationHours') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Choose available time</label>
                    <div class="d-flex align-items-stretch gap-2">
                        <button type="button" wire:click="shiftAvailableTimes(-1)" class="btn btn-outline-secondary px-2" @disabled($availabilityOffset === 0)><i class="bi bi-chevron-left"></i></button>
                        <div class="row g-2 flex-grow-1">
                            @forelse ($availableTimes as $availableTime)
                                @php($availableDate = \Carbon\Carbon::createFromFormat('Y-m-d\\TH:i', $availableTime)->locale('pl'))
                                <div class="col-sm-6 col-lg">
                                    <button type="button" wire:click="selectTime('{{ $availableTime }}')" class="btn {{ $startTime === $availableTime ? 'btn-primary' : 'btn-outline-primary' }} w-100 h-100 py-2">
                                        <span class="d-block small">{{ $availableDate->translatedFormat('l') }}</span>
                                        <span class="d-block fw-semibold">{{ $availableDate->translatedFormat('j F') }}</span>
                                        <span class="d-block">{{ $availableDate->format('H:i') }}</span>
                                    </button>
                                </div>
                            @empty
                                <div class="col"><div class="text-muted border rounded p-3 text-center">No available times.</div></div>
                            @endforelse
                        </div>
                        <button type="button" wire:click="shiftAvailableTimes(1)" class="btn btn-outline-secondary px-2"><i class="bi bi-chevron-right"></i></button>
                    </div>
                    @error('startTime') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" wire:click="$set('step', 1)" class="btn btn-outline-secondary">Back</button>
                    <button type="button" wire:click="continueBooking" class="btn btn-primary">Continue</button>
                </div>
            </div>
        </div>
    @elseif ($step === 3)
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h2 class="h5 mb-4">Booking summary</h2>
                <dl class="row mb-0">
                    <dt class="col-sm-4">Equipment</dt>
                    <dd class="col-sm-8">{{ $selectedResources->pluck('name')->join(', ') }}</dd>
                    <dt class="col-sm-4">Start</dt>
                    <dd class="col-sm-8">{{ \Carbon\Carbon::createFromFormat('Y-m-d\\TH:i', $startTime)->locale('pl')->translatedFormat('l, j F Y, H:i') }}</dd>
                    <dt class="col-sm-4">End</dt>
                    <dd class="col-sm-8">{{ \Carbon\Carbon::createFromFormat('Y-m-d\\TH:i', $startTime)->addHours((int) $durationHours)->locale('pl')->translatedFormat('l, j F Y, H:i') }}</dd>
                    <dt class="col-sm-4">Account</dt>
                    <dd class="col-sm-8">{{ auth()->user()->email }}</dd>
                    <dt class="col-sm-4">Total price</dt>
                    <dd class="col-sm-8 fw-bold">{{ number_format($selectedResources->sum('hourly_rate') * (int) $durationHours, 2) }} PLN</dd>
                </dl>
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" wire:click="$set('step', 2)" class="btn btn-outline-secondary">Change</button>
                    <button type="button" wire:click="confirmBooking" class="btn btn-primary">Confirm booking</button>
                </div>
            </div>
        </div>
    @elseif ($step === 4)
        <div class="alert alert-success">Your booking request has been sent successfully.</div>
    @endif
</div>