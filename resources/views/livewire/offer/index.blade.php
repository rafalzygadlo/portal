<div class="col">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Offers</h2>
        
        <button wire:click="handleCreateOffer" 
                class="btn {{ auth()->check() && !auth()->user()->hasVerifiedEmail() ? 'btn-warning' : (auth()->check() ? 'btn-primary' : 'btn-outline-primary') }}">
            @guest
                Login to Add Offer
            @else
                @if(!auth()->user()->hasVerifiedEmail())
                    Verify Email to Add Offer
                @else
                    Add Offer
                @endif
            @endguest
        </button>
    
    </div>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    {{-- Breadcrumb --}}
    @if (!empty($breadcrumb))
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-light p-2 rounded">
                <li class="breadcrumb-item">
                    <a href="{{ route('offers.index') }}" class="text-decoration-none">All Offers</a>
                </li>
                @foreach ($breadcrumb as $crumb)
                    <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}" @if($loop->last) aria-current="page" @endif>
                        @if (!$loop->last)
                            <a href="{{ route('offers.index', $crumb->slug) }}" class="text-decoration-none">{{ $crumb->name }}</a>
                        @else
                            {{ $crumb->name }}
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>
    @elseif (request()->has('category'))
        {{-- If currentCategoryId is set but breadcrumb is empty (e.g., category not found) --}}
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-light p-2 rounded">
                <li class="breadcrumb-item">
                    <a href="{{ route('offers.index') }}" class="text-decoration-none">All Offers</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Category Not Found</li>
            </ol>
        </nav>
    @else
        {{-- Default breadcrumb for "All Offers" when no category is selected --}}
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-light p-2 rounded">
                <li class="breadcrumb-item active" aria-current="page">All Offers</li>
            </ol>
        </nav>
    @endif

    <div class="row">
        {{-- Sidebar z Kategoriami --}}
        <aside class="col-md-3 mb-4">
            <div class="card shadow border-0 category-sidebar sticky-top" style="top: 20px; z-index: 1000;">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-list-ul me-2"></i>Categories
                </div>
                <div class="card-body p-3">
                    <ul class="list-unstyled mb-0">
                        @include('livewire.offer.category-item', ['categories' => $categories])
                    </ul>
                </div>
            </div>
        </aside>

        {{-- Główna Treść --}}
        <div class="col-md-9">
            <div class="row">
                @php $currentDay = null; @endphp
                
                @forelse ($offers as $offer)
                    {{--
                    @php
                        $offerDay = $offer->created_at->format('Y-m-d');
                        $label = match (true) {
                            $offer->created_at->isToday() => 'Today',
                            $offer->created_at->isYesterday() => 'Yesterday',
                            default => $offer->created_at->translatedFormat('l, d F Y'),
                        };
                    @endphp

                    @if ($offerDay == $currentDay)
                    <div class="col-12">
                        <div class="vertical-separator">
                            <div class="badge rounded-pill bg-secondary fw-semibold">
                                {{ $label }}
                            </div>
                        </div>
                    </div>
                        @php $currentDay = $offerDay; @endphp
                    @endif
                    --}}
                    <div class="col-md-6 mb-4 fade1-in-card" wire:key="offer-{{ $offer->id }}">
                        <div class="card h-100 border-0 overflow-h">
                            <a href="{{ route('offers.show', $offer) }}">                    
                             @if($offer->images->isNotEmpty())
                                <img loading="lazy" src="{{ asset('storage/' . $offer->images->first()->path) }}" class="card-img-top" alt="{{ $offer->title }}" style="height: 180px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center border "style="height: 180px;">
                                    <i class="bi bi-image text-muted" style="font-size: 2.5rem;"></i>
                                </div>
                            @endif
                            </a>
                            <div class="card-body d-flex flex-column">
                                <h3 class="card-title h6">
                                  
                                    <a href="{{ route('offers.show', $offer) }}" class="text-decoration-none text-dark hover-primary">
                                        {{ Str::limit($offer->title, 50) }}
                                    </a>
                                </h3>
                                
                                <div class="text-muted small mb-2" style="font-size: 0.8rem;">
                                    <i class="bi bi-clock"></i> {{ $offer->created_at->diffForHumans() }}
                                </div>

                                <p class="card-text flex-grow-1">
                                    {!! nl2br(strip_tags(Str::limit($offer->content, $loop->first ? 180 : 100), '<a>')) !!}
                                </p>
                                  @if($offer->categories->isNotEmpty())
                                <div class="mb-2">
                                    @foreach($offer->categories as $category)
                                        <a href="{{ route('offers.index', $category->slug) }}" class="text-decoration-none">
                                            <span class="badge bg-light text-dark border me-1">{{ $category->name }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                                <div class="mt-auto pt-2 border-top small text-muted">
                                        <i class="bi bi-calendar-event me-1"></i>
                                         
                                    {{ $offer->created_at->translatedFormat('l, d F Y') }}
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    
                @empty
                    <div class="col-12">
                        <div class="alert alert-secondary text-center py-5">
                            <i class="bi bi-inbox display-4 d-block mb-3"></i>
                            No offers available at the moment.
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $offers->links() }}
            </div>
        </div>
    </div>

    {{-- Komponent musi być wyrenderowany, aby nasłuchiwać zdarzeń --}}
    @livewire('offer.create')

</div>
