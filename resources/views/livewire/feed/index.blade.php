<div class="container-fluid">

    @include('livewire/main/partials/header')
    <div class="row g-3 g-md-6">
        @if($promotedItems->isNotEmpty())
            <div class="col-12">
                <div class="mb-4 px-3 py-2 rounded-4 bg-light border border-secondary-subtle">
                    <h2 class="h4 mb-1">Promowane</h2>
                    <p class="text-muted mb-0">Najlepsze propozycje wybrane losowo.</p>
                </div>
            </div>

            <div class="row gx-3 gy-4">
                @foreach ($promotedItems as $item)
                    <div class="col-12 col-md-6">

                       <a href="{{ route($item->type . '.show', $item->slug) }}" class="text-decoration-none text-dark hover-primary line-clamp-2">
                            {{ $item->title }}
                            <i class="bi bi-chevron-right small opacity-50 ms-1"></i>
                        </a>
                       
                    </div>
                @endforeach
            </div>

            <div class="col-12">
                <hr class="my-4">
            </div>
        @endif

        @if($regularItems->isNotEmpty())
            <div class="col-12">
                <div class="mb-4 px-3 py-2 rounded-4 bg-white border border-secondary-subtle">
                    <h2 class="h4 mb-1">Zwykłe</h2>
                    <p class="text-muted mb-0">Najnowsze wpisy z pozostałych kategorii.</p>
                </div>
            </div>

            @foreach ($regularItems as $item)
                @php
                    if ($loop->first) {
                        $colClass = 'col-12 col-lg-8';
                    } elseif ($loop->iteration > 1 && $loop->iteration <= 3) {
                        $colClass = 'col-12 col-md-6 col-lg-4';
                    } else {
                        $colClass = 'col-12 col-sm-6 col-md-4';
                    }
                @endphp

                <div class="{{ $colClass }}">
                    
                     <a href="{{ route($item->type . '.show', $item->slug) }}" class="text-decoration-none text-dark hover-primary line-clamp-2">
                            {{ $item->title }}
                            <i class="bi bi-chevron-right small opacity-50 ms-1"></i>
                        </a>
                    {{-- @include('livewire.feed.item', ['item' => $item, 'isPromoted' => false]) --}}
                  {{--   @include('livewire.feed.item', ['item' => $item, 'isPromoted' => false]) --}} 
                </div>
            @endforeach
        @endif

        @if($hasMore)
            <div class="col-12 text-center p-4">
                <div wire:loading.remove wire:target="loadMore">
                    <button wire:click="loadMore" class="btn btn-outline-primary px-5 rounded-pill shadow-sm fw-bold">
                        Załaduj więcej
                    </button>
                </div>

                <div wire:loading wire:target="loadMore" class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Ładowanie...</span>
                    </div>
                    <div class="mt-2 text-secondary small">Ładowanie kolejnych aktywności...</div>
                </div>
            </div>
        @endif
    </div> 
</div>
