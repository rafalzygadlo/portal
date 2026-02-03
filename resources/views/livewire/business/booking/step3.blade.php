<div wire:key="step3">
    <h3 class="fw-semibold mb-3 h5">Twoje dane</h3>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Imię i Nazwisko</label>
            <input type="text" wire:model.live="clientName" class="form-control @error('clientName') is-invalid @enderror">
            @error('clientName') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" wire:model.live="clientEmail" class="form-control @error('clientEmail') is-invalid @enderror">
            @error('clientEmail') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-12">
            <label class="form-label">Telefon (opcjonalnie)</label>
            <input type="tel" wire:model.live="clientPhone" class="form-control">
        </div>
        <div class="col-12">
            <label class="form-label">Notatki (opcjonalnie)</label>
            <textarea wire:model.live="notes" rows="3" class="form-control"></textarea>
        </div>
    </div>
</div>