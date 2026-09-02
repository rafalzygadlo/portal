<div class="container-fluid">
    @include('livewire/main/partials/header')

    <div class="mx-auto" style="max-width1: 980px;">
        @if($promotedItems->isNotEmpty())
            <section class="mb-5" aria-labelledby="promoted-heading">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-warning-subtle text-warning" style="width: 2.5rem; height: 2.5rem;">
                        <i class="bi bi-megaphone-fill" aria-hidden="true"></i>
                    </span>
                    <div>
                        <h2 id="promoted-heading" class="h5 mb-1">Promowane</h2>
                        <p class="text-muted small mb-0">Najlepsze propozycje wybrane losowo.</p>
                    </div>
                </div>

                <div class="border-top border-warning-subtle">
                @foreach ($promotedItems as $item)
                    <a href="{{ route($item->type . '.show', $item->slug) }}" class="d-flex align-items-center gap-3 py-3 px-2 border-bottom text-decoration-none text-dark hover-primary">
                        <span class="text-warning" aria-hidden="true"><i class="bi bi-star-fill"></i></span>
                        <span class="flex-grow-1 fw-semibold">{{ $item->title }}</span>
                        <span class="text-muted small text-capitalize">{{ $item->type }}</span>
                        <i class="bi bi-arrow-up-right text-warning" aria-hidden="true"></i>
                    </a>
                @endforeach
                </div>
            </section>
        @endif

        @if($regularItems->isNotEmpty())
            <section aria-labelledby="regular-heading">
                <div class="d-flex align-items-end justify-content-between gap-3 mb-3">
                    <div>
                        <span class="text-primary text-uppercase fw-bold" style="font-size: .7rem; letter-spacing: .08em;">Najnowsze</span>
                        <h2 id="regular-heading" class="h4 mb-1">Zwykłe wpisy</h2>
                        <p class="text-muted small mb-0">Najnowsze publikacje ze społeczności.</p>
                    </div>
                    <span class="text-muted small d-none d-sm-inline">{{ $regularItems->count() }} wpisów z {{ $countAll }}</span>
                </div>

                <div class="border-top">
                @foreach ($regularItems as $item)
                    <div class="d-flex align-items-center gap-3 py-3 border-bottom">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light text-primary flex-shrink-0" style="width: 2.5rem; height: 2.5rem;">
                            <i class="bi bi-{{ $item->type === 'article' ? 'file-text' : ($item->type === 'offer' ? 'tag' : 'grid') }}" aria-hidden="true"></i>
                        </span>
                        <div class="flex-grow-1 min-width-0">
                            <a href="{{ route($item->type . '.show', $item->slug) }}" class="text-decoration-none text-dark hover-primary fw-semibold line-clamp-2">
                                {{ $item->title }}
                            </a>
                            <div class="d-flex flex-wrap gap-2 mt-1 text-muted small">
                                <span class="text-capitalize">{{ $item->type }}</span>
                                <span aria-hidden="true">·</span>
                                <time datetime="{{ $item->created_at->toIso8601String() }}">{{ $item->created_at->diffForHumans() }}</time>
                            </div>
                        </div>
                        <a href="{{ route($item->type . '.show', $item->slug) }}" class="btn btn-sm btn-light rounded-circle flex-shrink-0" aria-label="Otwórz wpis">
                            <i class="bi bi-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                @endforeach
                </div>
            </section>
        @else
            <div class="text-center py-5 border-top border-bottom">
                <i class="bi bi-inbox display-6 text-muted" aria-hidden="true"></i>
                <h2 class="h5 mt-3">Brak wpisów</h2>
                <p class="text-muted small mb-0">Wróć później, aby zobaczyć nowe publikacje.</p>
            </div>
        @endif

        @if($hasMore)
            <div class="text-center py-5">
                <div wire:loading.remove wire:target="loadMore">
                    <button wire:click="loadMore" class="btn btn-outline-primary px-4 rounded-pill fw-semibold">
                        <i class="bi bi-plus-lg me-2" aria-hidden="true"></i> Załaduj więcej
                    </button>
                </div>

                <div wire:loading wire:target="loadMore" class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Ładowanie...</span>
                    </div>
                    <span class="text-secondary small ms-2">Ładowanie kolejnych wpisów...</span>
                </div>
            </div>
        @endif
    </div>
</div>
