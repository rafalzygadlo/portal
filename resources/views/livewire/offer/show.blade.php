<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('offers.index') }}" class="text-decoration-none">Offers</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($offer->title, 30) }}</li>
                </ol>
            </nav>

            <div class="card border-0 overflow-hidden">
                <div class="card-body p-0 p-md-0">
                    <h1 class="fw-bold mb-3">{{ $offer->title }}</h1>
                    
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                        <div class="text-muted">
                            <i class="bi bi-person-circle me-1"></i> 
                            @if($offer->user)
                                <a href="{{ route('user.profile', $offer->user) }}" class="text-decoration-none text-muted">{{ $offer->user->name }}</a>
                            @else
                                Anonymous
                            @endif
                            <span class="mx-2">&bull;</span>
                            <i class="bi bi-calendar3 me-1"></i> {{ $offer->created_at->format('d.m.Y H:i') }}
                            @if($offer->category)
                                <span class="badge bg-secondary ms-2">{{ $offer->category->name }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="content mb-5">
                        {!! nl2br(e($offer->content)) !!}
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('offers.index') }}" class="btn btn-outline-secondary">Back to Offers</a>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="comments-section">
                <h3>Comments</h3>
                <livewire:comments :model="$offer" />
            </div>
        </div>
    </div>
</div>
