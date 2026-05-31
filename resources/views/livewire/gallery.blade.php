<div>
@if($images->isNotEmpty())
    <div class="position-relative mb-4 bg-light overflow-hidden d-flex align-items-center justify-content-center rounded-4 shadow-sm"
        style="min-height: 400px; max-height: 600px;">
        <a href="{{ asset('storage/' . $activeImage) }}" target="_blank">
            <img loading="lazy" src="{{ asset('storage/' . $activeImage) }}"
                class="img-fluid" alt="Podgląd oferty" style="max-height: 600px; object-fit: contain;">
        </a>
        <div class="position-absolute bottom-0 start-50 translate-middle-x mb-3">
            <span class="badge bg-dark bg-opacity-50 px-3 py-2 fs-6 shadow-sm">{{ $images->search(fn($img) => $img->path === $activeImage) + 1 }} / {{ $images->count() }}</span>
        </div>
    </div>

@endif


@if($images->count() > 1)
    <div class="mb-2">
        <span class="badge bg-secondary">{{ $images->count() }} zdjęć</span>
    </div>
    <div class="d-flex flex-nowrap overflow-x-auto gap-2 pb-3 custom-scrollbar">
        @foreach($images as $image)
            <div style="flex: 0 0 120px;" wire:key="thumb-{{ $image->id }}">
                <div 
                    wire:click="setActiveImage('{{ $image->path }}')"
                    role="button"
                    class="ratio ratio-1x1 position-relative shadow-sm rounded border @if($activeImage == $image->path) border-primary border-3 @else border-light @endif"
                    style="cursor: pointer;"
                >
                    
                    <img 
                        loading="lazy" 
                        src="{{ asset('storage/' . $image->getThumbnailPath()) }}"
                        class="img-fluid rounded hover-opacity" 
                        alt="{{ $image->title }}"
                        style="object-fit: cover;">
                  
                </div>
            </div>
        @endforeach
    </div>
@endif

</div>