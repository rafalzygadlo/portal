<div>
    <div class="mb-4">
        <p class="text-muted">Wybierz, na jak długo chcesz wypromować swoją treść. Promowane pozycje pojawiają się wyżej w wynikach wyszukiwania i na stronie głównej.</p>
    </div>

    <div class="mb-4">
        <h6 class="fw-bold">Długość promocji:</h6>
        <div class="list-group">
            @foreach($options as $days => $price)
                <label class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" style="cursor: pointer;">
                    <span>
                        <input type="radio" wire:model.live="duration" name="duration" value="{{ $days }}" class="form-check-input me-2">
                        <strong>{{ $days }} dni</strong>
                    </span>
                    <span class="badge bg-primary rounded-pill">{{ number_format($price, 2) }} PLN</span>
                </label>
            @endforeach
        </div>
    </div>

    <div class="alert alert-info d-flex justify-content-between align-items-center">
        <span class="fw-bold">Całkowity koszt:</span>
        <span class="fs-4 fw-bolder">{{ number_format($cost, 2) }} PLN</span>
    </div>

    <div class="d-flex justify-content-end mt-4">
        <button type="button" class="btn btn-secondary me-2" wire:click="$dispatch('closeModal')">
            Anuluj
        </button>
        <button type="button" wire:click="submitPromotion" wire:loading.attr="disabled" class="btn btn-success">
            <span wire:loading.remove wire:target="submitPromotion">
                <i class="bi bi-rocket-launch me-1"></i>
                Promuj i Zapłać
            </span>
            <span wire:loading wire:target="submitPromotion">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Przetwarzanie...
            </span>
        </button>
    </div>
</div>