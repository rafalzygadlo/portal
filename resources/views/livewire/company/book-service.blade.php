<div class="col py-4">
    <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-1">Book a service</h1>
            <p class="text-muted mb-0">Choose a service, a person and a convenient time.</p>
        </div>
        <a href="{{ route('company.domain', ['company' => $company]) }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Back</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif ($step === 1)
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="h5 fw-bold mb-1">Choose services</h2>
                <p class="text-muted small mb-0">Select one or more services for your visit.</p>
            </div>
            @if ($serviceIds)
                <span class="badge rounded-pill bg-primary-subtle text-primary">{{ count($serviceIds) }} selected</span>
            @endif
        </div>
        <div class="row g-3">
            @forelse ($services as $service)
                <div class="col-md-6 col-lg-4">
                    <button type="button" wire:click="toggleService({{ $service->id }})" class="card w-100 h-100 text-start border shadow-sm p-0 {{ in_array($service->id, $serviceIds) ? 'border-primary bg-light' : '' }}">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start">
                                <i class="bi bi-gem fs-2 text-primary"></i>
                                <i class="bi {{ in_array($service->id, $serviceIds) ? 'bi-check-circle-fill' : 'bi-circle' }} fs-5 text-primary"></i>
                            </div>
                            <h2 class="h5 mt-3 mb-1">{{ $service->name }}</h2>
                            <p class="text-muted small mb-3">{{ $service->description }}</p>
                            <div class="mt-auto d-flex justify-content-between align-items-end">
                                <span class="text-muted small"><i class="bi bi-clock me-1" aria-hidden="true"></i>{{ $service->duration }} min</span>
                                @if ($service->price)
                                    <span class="fw-semibold">{{ number_format($service->price, 2) }} PLN</span>
                                @endif
                            </div>
                        </div>
                    </button>
                </div>
            @empty
                <div class="alert alert-secondary">No services are currently available.</div>
            @endforelse
        </div>
        @error('serviceIds') <div class="text-danger small mt-2">Choose at least one service.</div> @enderror
        <div class="d-flex justify-content-end mt-4">
            <button type="button" wire:click="continueServices" class="btn btn-primary">Continue</button>
        </div>
    @elseif ($step === 2)
        <div class="card border-0 shadow-sm"><div class="card-body p-4">
            <h2 class="h5 mb-2">Selected services</h2>
            <p class="text-muted mb-4">{{ $selectedServices->pluck('name')->join(', ') }}</p>
            <div class="mb-3">
                <label class="form-label fw-semibold">Choose a person</label>
                <div class="row g-2">
                    @forelse ($availablePeople as $personOption)
                        @if ($personOption['nextStart'])
                            <div class="col-md-6">
                                <button type="button" wire:click="selectPerson({{ $personOption['companyUser']->id }}, '{{ $personOption['nextStart'] }}')" class="btn {{ $companyUserId == $personOption['companyUser']->id ? 'btn-primary' : 'btn-outline-primary' }} w-100 text-start">
                                    <span class="d-block fw-semibold">{{ $personOption['companyUser']->user->name }}</span>
                                    <span class="small">First available: {{ \Carbon\Carbon::createFromFormat('Y-m-d\\TH:i', $personOption['nextStart'])->locale('pl')->translatedFormat('l, j F Y, H:i') }}</span>
                                </button>
                            </div>
                        @endif
                    @empty
                        <div class="col-12"><div class="alert alert-secondary">No people are assigned to this service.</div></div>
                    @endforelse
                </div>
                @error('companyUserId') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Choose a day</label>
                @include('livewire.company.partials.calendar')
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Choose an available time</label>
                <div class="d-flex flex-wrap gap-2">
                    @forelse ($availableTimes as $availableTime)
                        @php($availableDate = \Carbon\Carbon::createFromFormat('Y-m-d\\TH:i', $availableTime)->locale('pl'))
                        <button type="button" wire:click="selectTime('{{ $availableTime }}')" class="btn btn-sm rounded-pill {{ $startTime === $availableTime ? 'btn-primary' : 'btn-outline-primary' }} px-3">
                            {{ $availableDate->format('H:i') }}
                        </button>
                    @empty
                        <div class="text-muted border rounded p-3 text-center w-100">{{ $selectedDate ? 'No available times on this day.' : 'Choose a day to see available times.' }}</div>
                    @endforelse
                </div>
                @error('startTime') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>
            <div class="d-flex justify-content-between mt-4">
                <button type="button" wire:click="$set('step', 1)" class="btn btn-outline-secondary">Back</button>
                <button type="button" wire:click="continueBooking" class="btn btn-primary">Continue</button>
            </div>
        </div></div>
    @elseif ($step === 3)
        <div class="card border-0 shadow-sm"><div class="card-body p-4">
            <h2 class="h5 mb-4">Booking summary</h2>
            <dl class="row mb-0">
                <dt class="col-sm-4">Services</dt><dd class="col-sm-8">{{ $selectedServices->pluck('name')->join(', ') }}</dd>
                <dt class="col-sm-4">Person</dt><dd class="col-sm-8">{{ $selectedService->companyUsers()->whereKey($companyUserId)->first()->user->name }}</dd>
                <dt class="col-sm-4">Start</dt><dd class="col-sm-8">{{ \Carbon\Carbon::createFromFormat('Y-m-d\\TH:i', $startTime)->locale('pl')->translatedFormat('l, j F Y, H:i') }}</dd>
                <dt class="col-sm-4">Account</dt><dd class="col-sm-8">{{ auth()->user()->email }}</dd>
            </dl>
            <div class="d-flex justify-content-between mt-4">
                <button type="button" wire:click="$set('step', 2)" class="btn btn-outline-secondary">Change</button>
                <button type="button" wire:click="confirmBooking" class="btn btn-primary">Confirm booking</button>
            </div>
        </div></div>
    @elseif ($step === 4)
        <div class="alert alert-success">Your booking request has been sent successfully.</div>
    @endif
</div>