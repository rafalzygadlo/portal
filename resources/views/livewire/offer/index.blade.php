<div class="col">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Offers</h2>
        <a href="{{ route('offers.create') }}" class="btn btn-primary">Add Offer</a>
    </div>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="row">
        @php $currentDay = null; @endphp
        
        @forelse ($offers as $offer)
            @php
                // Logika daty
                $offerDay = $offer->created_at->format('Y-m-d');
                $label = match (true) {
                    $offer->created_at->isToday() => 'Today',
                    $offer->created_at->isYesterday() => 'Yesterday',
                    default => $offer->created_at->translatedFormat('l, d F Y'),
                };

                // Logika siatki (grid)
                $colClass = 'col-12';
                if ($loop->iteration > 1 && $loop->iteration <= 5) {
                    $colClass = 'col-md-6';
                } elseif ($loop->iteration > 5) {
                    $colClass = 'col-md-4';
                }
            @endphp

            {{-- Separator Daty jako pełna szerokość --}}
            @if ($offerDay !== $currentDay)
                <div class="col-12">
                    <div class="d-flex align-items-center my-4">
                        <div class="flex-grow-1 border-top"></div>
                        <div class="badge rounded-pill bg-primary px-3 fw-semibold mx-2">
                            {{ $label }}
                        </div>
                        <div class="flex-grow-1 border-top"></div>
                    </div>
                </div>
                @php $currentDay = $offerDay; @endphp
            @endif
    
            {{-- Karta Oferty --}}
            <div class="{{ $colClass }} mb-4" wire:key="offer-{{ $offer->id }}">
                <div class="card h-100 border-1 shadow-sm overflow-hidden">                    
                    <div class="card-body d-flex flex-column">
                        <h3 class="card-title h5">
                            <span class="badge rounded-pill bg-primary me-1">{{ $offer->votes_sum_value ?? 0 }}</span>
                            <a href="{{ route('offers.show', $offer->id) }}" class="text-decoration-none text-dark hover-primary">
                                {{ $offer->title }}
                            </a>
                        </h3>
                        
                        <div class="text-muted small mb-2">
                            <i class="bi bi-clock"></i> {{ $offer->created_at->diffForHumans() }}
                            <span class="ms-2"><i class="bi bi-person"></i> {{ $offer->user->name }}</span>
                        </div>

                        <p class="card-text flex-grow-1">
                            {{ Str::limit($offer->content, $loop->first ? 250 : 150) }}
                        </p>

                        @if($offer->categories->isNotEmpty())
                            <div class="mb-3">
                                @foreach($offer->categories as $category)
                                    <span class="badge bg-light text-dark border me-1">{{ $category->name }}</span>
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-auto pt-3 border-top">
                            {{ $offer->created_at->format('Y-m-d') }} | {{ $offer->created_at->format('H:i') }}
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