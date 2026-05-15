<div class="container-fluid py-5">
    <div class="row gy-4">
        <hr>
        <div class="col-12 text-center">
            Tytaj bedą wszystkie nowości i mozliwość podbijania ogłoszeń, artykułów i innych rzeczy, ktore bedą się
            pojawiac na portalu. <br>Będę też informowal o nowych funkcjach i innych waznych rzeczach zwiazanych z portalem.
        </div>
        <hr>

        @foreach ($feed as $item)
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card h-100 shadow-sm border-0">

                <a href="{{ route('offers.show', $item) }}" class="text-decoration-none">        
                    @if($item->images->isNotEmpty())               
                    <img loading="lazy" src="{{ asset('storage/' . $item->images->first()->path) }}" class="img-fluid card-img-top"
                            alt="{{ $item->title }}" style="height: 180px; object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center border-bottom"
                            style="height: 180px;">
                            <i class="bi bi-image text-muted" style="font-size: 2.5rem;"></i>
                        </div>
                    @endif
                    </a>

                    <div class="card-body d-flex flex-column">
                        <h5><a href="{{ route('offers.show', $item) }}" class="text-decoration-none text-dark hover-primary mt-3 align-self-start">{{ $item->title }}</a></h5>
                        <p class="card-text flex-grow-1">{{ Str::limit($item->content, 100) }}</p>
                        <div class="mb-2">
                            @foreach($item->categories as $category)
                                <span class="badge bg-light text-dark border me-1">{{ $category->name }}</span>
                            @endforeach
                        </div>
                        <small class="text-muted">{{ $item->created_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>

        @endforeach


    </div>
</div>