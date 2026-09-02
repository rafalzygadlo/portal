<div class="container">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card border-0 bg- shadow">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi {{ $isEdit ? 'bi-pencil' : 'bi-plus-lg' }} me-2"></i> {{ $isEdit ? 'Edit' : 'Create' }} Offer</h5>
                </div>
                @if (session()->has('status'))
                    <div class="alert alert-success m-4">
                        {{ session('status') }}
                    </div>
                @endif
                
                <div class="card-body p-4">
                    @php
                        $existingCount = $isEdit ? count($existingPhotos ?? []) : 0;
                    @endphp

                    <form wire:submit.prevent="save">
                        {{-- Block displaying all validation errors --}}
                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <h5 class="alert-heading">There were errors!</h5>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">{{ __('offers.title') }}</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" wire:model.defer="title" placeholder="Offer title">
                            @error('title') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label fw-semibold">{{ __('offers.content') }}</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" rows="6" wire:model.defer="content" placeholder="Describe your offer..."></textarea>
                            @error('content') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Category</label>
                            <livewire:category-selector  wire:model.defer="categories" wire:key="category-selector-{{ $isEdit ? 'edit' : 'create' }}" />
                            @error('categories') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('offers.photos') }}</label>
                            <livewire:upload wire:model.live="allPhotos" inputId="offer-photos" :existingPhotos="$existingPhotos" :errorFields="['allPhotos', 'allPhotos.*']" :key="'offer-upload-' . ($isEdit ? 'edit' : 'create')" />
                        </div>

                        <div class="d-flex justify-content-between gap-2 mt-4">
                            <a href="{{ route('user.profile') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <span wire:loading.remove><i class="bi {{ $isEdit ? 'bi-save' : 'bi-send' }} me-2"></i> {{ $isEdit ? 'Save changes' : __('offers.add_offer') }}</span>
                                <span wire:loading><span class="spinner-border spinner-border-sm me-2" role="status"></span> {{ __('offers.saving') }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>