<div class="col">
    <div class="row justify-content-center">
        <div class="col-lg-12 col-xl-12">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($article->title, 30) }}</li>
                </ol>
            </nav>

            <div class="card border-0 shadow overflow-hidden mb-4">
                @if($article->image_path)
                    <img loading="lazy" src="{{ asset('storage/' . $article->image_path) }}" class="card-img-top" alt="{{ $article->title }}" style="max-height: 450px; object-fit: cover;">
                @endif
                
                <div class="card-body p-4 p-md-5">
                    <h1 class="display-5 fw-bold mb-3">{{ $article->title }}</h1>
                    
                    <div class="d-flex flex-wrap justify-content-between align-items-center border-bottom pb-3 mb-4">
                        <div class="text-muted">
                            <span class="me-3">
                                <i class="bi bi-person-circle me-1"></i> 
                            @if($article->user)
                                    <a href="{{ route('user.profile', $article->user) }}" class="text-decoration-none text-muted fw-bold">{{ $article->user->first_name }}</a>
                            @else
                                Anonymous
                            @endif
                            </span>
                            <span class="me-3">
                                <i class="bi bi-calendar3 me-1"></i> {{ $article->created_at->format('d.m.Y H:i') }}
                            </span>
                            @if($article->category)
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10">{{ $article->category->name }}</span>
                            @endif
                        </div>
                        <div>
                            {{-- 
                                <livewire:article.vote :model="$article" :key="'vote-single-'.$article->id" />
                            --}}
                        </div>
                    </div>

                    <div class="article-content fs-5 lh-base text-dark mb-5">
                        {!! nl2br(e($article->content)) !!}
                    </div>

                    <div class="pt-4 border-top">
                        <livewire:article.report :article="$article" />
                        <div class="mt-4">
                            <livewire:comments :model="$article" />
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light border-top-0 p-4">
                     <a href="/" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Powrót do listy
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>