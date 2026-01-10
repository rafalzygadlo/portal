<div class="container-fluid my-5">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Strona główna</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($article->title, 30) }}</li>
                </ol>
            </nav>

            <div class="card border-0 overflow-hidden">
                @if($article->image_path)
                    <img src="{{ asset('storage/' . $article->image_path) }}" class="card-img-top" alt="{{ $article->title }}" style="max-height: 500px; object-fit: cover;">
                @endif
                
                <div class="card-body p-4 p-md-5">
                    <h1 class="fw-bold mb-3">{{ $article->title }}</h1>
                    
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                        <div class="text-muted">
                            <i class="bi bi-person-circle me-1"></i> {{ $article->user->first_name ?? 'Autor nieznany' }}
                            <span class="mx-2">&bull;</span>
                            <i class="bi bi-calendar3 me-1"></i> {{ $article->created_at->format('d.m.Y H:i') }}
                        </div>
                        <div>
                            <livewire:article.vote :article="$article" :key="'vote-single-'.$article->id" />
                        </div>
                    </div>

                    <div class="article-content fs-5 lh-lg">
                        {!! nl2br(e($article->content)) !!}
                    </div>
                </div>
                <div class="card-footer bg-white p-4 border-top-0">
                     <a href="/" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> Wróć do listy
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>