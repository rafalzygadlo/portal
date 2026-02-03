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
                @if ($step == 1)
                    <livewire:business.booking.step1 
                        :services="$services" 
                        :selectedServiceId="$selectedServiceId"
                    />
                @elseif ($step == 2)
                    <livewire:business.booking.step2 
                        :business="$business"
                        :selectedServiceId="$selectedServiceId"
                        :selectedDate="$selectedDate"
                        :selectedTime="$selectedTime"
                    />
                @elseif ($step == 3)
                    <livewire:business.booking.step3 
                        wire:model:clientName.live="clientName"
                        wire:model:clientEmail.live="clientEmail"
                        wire:model:clientPhone.live="clientPhone"
                        wire:model:notes.live="notes"
                    />
                @elseif ($step == 4)
                    <livewire:business.booking.step4 
                        :selectedService="$this->selectedService"
                        :selectedDate="$selectedDate"
                        :selectedTime="$selectedTime"
                        :clientName="$clientName"
                        :clientEmail="$clientEmail"
                    />
                @endif

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