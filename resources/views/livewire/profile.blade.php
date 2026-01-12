<div class="container py-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm text-center p-4">
                <div class="mb-3">
                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center text-primary fw-bold" style="width: 100px; height: 100px; font-size: 2.5rem;">
                        {{ substr($user->first_name, 0, 1) }}
                    </div>
                </div>
                <h3 class="fw-bold">{{ $user->first_name }} {{ $user->last_name }}</h3>
                <p class="text-muted">Dołączył: {{ $user->created_at->format('d.m.Y') }}</p>
                <p class="text-muted">Email: {{ $user->email }}</p>
                <div class="d-flex justify-content-center gap-4 mt-3">
                    <div class="text-center">
                        <h4 class="fw-bold mb-0">{{ $articles->count() }}</h4>
                        <small class="text-muted">Artykuły</small>
                    </div>
                    <div class="text-center">
                        <h4 class="fw-bold mb-0 {{ $reputation >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $reputation > 0 ? '+' : '' }}{{ $reputation }}
                        </h4>
                        <small class="text-muted">Reputacja</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <h4 class="mb-4 pb-2 border-bottom">Artykuły użytkownika</h4>
            
            @forelse($articles as $article)
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <h5 class="card-title mb-1">
                                <a href="{{ route('article.show', $article) }}" class="text-decoration-none text-dark">{{ $article->title }}</a>
                            </h5>
                            <span class="badge {{ ($article->upvotes_count - $article->downvotes_count) >= 0 ? 'bg-success' : 'bg-danger' }}">
                                {{ ($article->upvotes_count - $article->downvotes_count) > 0 ? '+' : '' }}{{ $article->upvotes_count - $article->downvotes_count }} pkt
                            </span>
                        </div>
                        <p class="text-muted small mb-2">{{ $article->created_at->format('d.m.Y H:i') }}</p>
                        <p class="card-text">{{ \Illuminate\Support\Str::limit($article->content, 150) }}</p>
                    </div>
                </div>
            @empty
                <div class="alert alert-light text-center">Ten użytkownik nie napisał jeszcze żadnych artykułów.</div>
            @endforelse
        </div>
    </div>
</div>
