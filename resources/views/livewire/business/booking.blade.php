<div class="card booking-widget shadow-sm">
    <div class="card-body p-4">
        <h2 class="card-title fw-bold mb-4">Zarezerwuj termin</h2>

        @if (session()->has('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @else
            <!-- Progress Bar -->
            <div class="mb-4">
                <div class="progress" style="height: 20px;">
                    @php
                        $steps = [1 => 'Usługa', 2 => 'Termin', 3 => 'Twoje dane', 4 => 'Potwierdzenie'];
                        $progress = ($step - 1) * 33.33;
                    @endphp
                    <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                 <div class="d-flex justify-content-between mt-1">
                    @foreach ($steps as $stepNumber => $stepName)
                        <div class="text-center" style="width: 25%;">
                            <button type="button" class="btn btn-link p-0 @if($step >= $stepNumber) text-primary fw-bold @else text-muted @endif" @if($step > $stepNumber) wire:click="goToStep({{ $stepNumber }})" @else disabled @endif>
                                <small>{{ $stepName }}</small>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <style>
                .service-card {
                    cursor: pointer;
                    border: 2px solid #e9ecef;
                    transition: all 0.2s ease-in-out;
                }
                .service-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                    border-color: var(--bs-primary);
                }
                .service-card.active {
                    border-color: var(--bs-primary);
                    background-color: #f0f8ff;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                }
            </style>
            <form wire:submit="book">
                <!-- Krok 1: Wybór usługi -->
                <div @if($step != 1) style="display: none;" @endif wire:key="step1">
                    <h3 class="fw-semibold mb-3 h5">Wybierz usługę</h3>
                    <div class="row g-3">
                        @forelse ($services as $service)
                            <div class="col-md-6">
                                <div 
                                    class="service-card p-3 rounded h-100 d-flex flex-column {{ $selectedServiceId == $service->id ? 'active' : '' }}"
                                    wire:click="selectService('{{ $service->id }}')"
                                >
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-gem fs-2 text-primary me-3"></i>
                                        <div>
                                            <h6 class="fw-bold mb-0">{{ $service->name }}</h6>
                                            <small class="text-muted">{{ $service->duration_minutes }} min</small>
                                        </div>
                                    </div>
                                     @if ($service->price)
                                        <div class="mt-auto text-end fs-5 fw-bold pt-2">
                                            {{ number_format($service->price, 2) }} zł
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-secondary">Brak dostępnych usług do rezerwacji.</div>
                            </div>
                        @endforelse
                    </div>
                    @error('selectedServiceId')
                        <div class="text-danger small mt-2 d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Krok 2: Kalendarz tygodniowy -->
                <div @if($step != 2) style="display: none;" @endif wire:key="step2">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <button type="button" wire:click="previousWeek" class="btn btn-outline-secondary">←</button>
                        <h3 class="h5 fw-semibold mb-0">
                            {{ $weekStart->format('d.m.Y') }} - {{ $weekStart->copy()->addDays(6)->format('d.m.Y') }}
                        </h3>
                        <button type="button" wire:click="nextWeek" class="btn btn-outline-secondary">→</button>
                    </div>

                    <div class="row row-cols-7 g-2 mb-4">
                         @forelse ($weekDays ?? [] as $dateKey => $day)
                        <div class="col">
                            <div class="border rounded p-2 bg-light h-100">
                                <div class="fw-semibold text-center mb-2 small">
                                    <div class="text-muted">{{ substr($day['dayName'], 0, 3) }}</div>
                                    <div class="h6 mb-0">{{ $day['formatted'] }}</div>
                                </div>

                                @if (empty($weekSlots[$dateKey]))
                                    <div class="text-center text-muted small p-3">
                                        Zamknięte
                                    </div>
                                @else
                                    <div class="d-grid gap-1" style="max-height: 18rem; overflow-y: auto;">
                                        @foreach ($weekSlots[$dateKey] as $slot)
                                            <button 
                                                type="button"
                                                wire:click="selectSlot('{{ $slot['fullDateTime'] }}')"
                                                class="btn btn-sm
                                                    @if ($slot['available'])
                                                        @if ($selectedDate === $dateKey && $selectedTime === $slot['time'])
                                                            btn-primary
                                                        @else
                                                            btn-outline-success
                                                        @endif
                                                    @else
                                                        btn-outline-danger disabled
                                                    @endif
                                                "
                                                @if (!$slot['available']) disabled @endif
                                            >
                                                {{ $slot['time'] }}
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-muted p-4">
                            Ładowanie...
                        </div>
                    @endforelse
                    </div>

                    @error('selectedDate') <div class="text-danger small mt-2 d-block">{{ $message }}</div> @enderror
                    @error('selectedTime') <div class="text-danger small mt-2 d-block">{{ $message }}</div> @enderror
                </div>

                <!-- Krok 3: Dane klienta -->
                <div @if($step != 3) style="display: none;" @endif wire:key="step3">
                    <h3 class="fw-semibold mb-3 h5">Twoje dane</h3>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Imię i Nazwisko</label>
                            <input type="text" wire:model="clientName" class="form-control @error('clientName') is-invalid @enderror">
                            @error('clientName') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" wire:model="clientEmail" class="form-control @error('clientEmail') is-invalid @enderror">
                            @error('clientEmail') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Telefon (opcjonalnie)</label>
                            <input type="tel" wire:model="clientPhone" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notatki (opcjonalnie)</label>
                            <textarea wire:model="notes" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Krok 4: Podsumowanie -->
                <div @if($step != 4) style="display: none;" @endif wire:key="step4">
                    <h3 class="fw-semibold mb-3 h5">Podsumowanie rezerwacji</h3>
                    @if($this->selectedService)
                        <div class="alert alert-light">
                            <p><strong>Usługa:</strong> {{ $this->selectedService->name }} ({{ $this->selectedService->duration_minutes }} min)</p>
                            <p><strong>Termin:</strong> {{ \Carbon\Carbon::parse($selectedDate)->format('l, d.m.Y') }} o <strong>{{ $selectedTime }}</strong></p>
                            <p><strong>Imię i nazwisko:</strong> {{ $clientName }}</p>
                            <p class="mb-0"><strong>Email:</strong> {{ $clientEmail }}</p>
                        </div>
                    @endif
                </div>

                <!-- Przyciski nawigacyjne -->
                <div class="d-flex justify-content-between mt-4">
                    @if($step > 1)
                        <button type="button" wire:click="previousStep" class="btn btn-secondary">
                            Wstecz
                        </button>
                    @else
                        <div></div> <!-- Pusty div dla zachowania justowania -->
                    @endif

                    @if($step > 1 && $step < 4)
                        <button type="button" wire:click="nextStep" class="btn btn-primary" @if(($step == 2 && !$selectedTime)) disabled @endif>
                            Dalej
                        </button>
                    @elseif($step == 4)
                        <button type="submit" class="btn btn-success btn-lg">
                            Zarezerwuj
                        </button>
                    @endif
                </div>
            </form>
        @endif
    </div>
</div>
