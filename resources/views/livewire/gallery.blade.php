<div>

    @if($images->isNotEmpty())
        <div class="position-relative mb-4 bg-light overflow-hidden d-flex align-items-center justify-content-center rounded-4 shadow-sm"
            style="min-height: 400px; max-height: 600px;">

            {{--  button prevImage  --}}
            <button wire:click="prevImage"
                class="btn btn-dark position-absolute top-50 start-0 translate-middle-y ms-2 ms-md-3 opacity-75 hover-opacity-100"
                style="z-index: 20; padding: 10px 15px;">
                &#10094;
            </button>

            <a href="{{ asset('storage/' . $this->activeImagePath) }}" target="_blank"
                class="w1-100 h1-100 d-flex align-items-center justify-content-center">
                <img loading="lazy" src="{{ asset('storage/' . $this->activeImagePath) }}" class="img-fluid"
                    alt="Podgląd oferty" style="max-height: 500px; object-fit: contain;">
            </a>
            
            {{--  button nextImage  --}}
            <button wire:click="nextImage"
                class="btn btn-dark position-absolute top-50 end-0 translate-middle-y me-2 me-md-3 opacity-75 hover-opacity-100"
                style="z-index: 20; padding: 10px 15px;">
                &#10095;
            </button>

            <div class="position-absolute bottom-0 start-50 translate-middle-x mb-3" style="z-index: 10;">
                <span class="badge bg-dark bg-opacity-50 px-3 py-2 fs-6 shadow-sm">
                    {{ $this->currentIndex + 1 }} / {{ $images->count() }}
                </span>
            </div>
        </div>
    @endif


    @if($images->count() > 1)
        <div class="mb-2">
            <span class="badge bg-secondary">{{ $images->count() }} zdjęć</span>
        </div>
        <div class="d-flex flex-nowrap overflow-x-auto gap-2 pb-3 custom-scrollbar">
            @foreach($images as $index => $image)
                <div style="flex: 0 0 120px;" wire:key="thumb-{{ $image->id }}">
                    <div wire:click="$set('currentIndex', {{ $index }})" role="button"
                        class="ratio ratio-1x1 position-relative shadow-sm rounded border @if($index == $this->currentIndex) border-primary border-3 @else border-light @endif"
                        style="cursor: pointer;">

                        <img loading="lazy" src="{{ asset('storage/' . $image->getThumbnailPath()) }}"
                            class="img-fluid rounded hover-opacity" alt="{{ $image->title }}" style="object-fit: cover;">

                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>