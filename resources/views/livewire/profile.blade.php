<div class="col">
    <div class="row">
        <div class="col-md-9">

            <div class="accordion mb-5" id="profileAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingBusinesses">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBusinesses" aria-expanded="false" aria-controls="collapseBusinesses">
                            User businesses ({{ $user->ownedBusinesses->count() }})
                        </button>
                    </h2>
                    <div id="collapseBusinesses" class="accordion-collapse collapse show" aria-labelledby="headingBusinesses" data-bs-parent="#profileAccordion">
                        <div class="accordion-body">
                            <div class="row mb-5">
                                @forelse($user->ownedBusinesses as $business)
                                    <div class="col-md-3 mb-3">
                                        <div class="card h-100 border-0 shadow">
                                            <div class="card-body">
                                                <h5 class="card-title fw-bold">{{ $business->name }}</h5>
                                                <p class="card-text text-muted small mb-3">
                                                    {{ \Illuminate\Support\Str::limit($business->description, 60) }}</p>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('business.domain', ['subdomain' => $business->subdomain]) }}"
                                                        class="btn btn-sm btn-outline-secondary" target="_blank">
                                                        <i class="bi bi-globe"></i> Page
                                                    </a>
                                                    @if(Auth::id() === $user->id)
                                                        <a href="{{ route('admin.business.dashboard', ['subdomain' => $business->subdomain]) }}"
                                                            class="btn btn-sm btn-primary">
                                                            <i class="bi bi-speedometer2"></i> Dashboard
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="alert alert-light text-center">This user does not manage any businesses yet.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOffers">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOffers" aria-expanded="false" aria-controls="collapseOffers">
                            User offers ({{ $user->offers->count() }})
                        </button>
                    </h2>
                    <div id="collapseOffers" class="accordion-collapse collapse" aria-labelledby="headingOffers" data-bs-parent="#profileAccordion">
                        <div class="accordion-body">
                            <div class="row mb-5">
                                @forelse($user->offers as $offer)
                                    <div class="col-md-3 mb-3">
                                        <div class="card h-100 border-0 shadow">
                                            <div class="card-body">
                                                <h5 class="card-title fw-bold">{{ $offer->title }}</h5>
                                                <p class="card-text text-muted small mb-3">
                                                    {{ \Illuminate\Support\Str::limit($offer->content, 80) }}</p>
                                                <a href="{{ route('offers.show', $offer) }}" class="btn btn-sm btn-outline-primary">
                                                    View offer
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="alert alert-light text-center">This user has not published any offers yet.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingArticles">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseArticles" aria-expanded="false" aria-controls="collapseArticles">
                            User articles ({{ $user->articles->count() }})
                        </button>
                    </h2>
                    <div id="collapseArticles" class="accordion-collapse collapse" aria-labelledby="headingArticles" data-bs-parent="#profileAccordion">
                        <div class="accordion-body">
                            <div class="row mb-5">
                                @forelse($user->articles as $article)
                                    <div class="col mb-3">
                                        <div class="card h-100 border-0 shadow">
                                            <div class="card-body">
                                                <h5 class="card-title fw-bold">{{ $article->title }}</h5>
                                                <p class="card-text text-muted small mb-3">
                                                    {{ \Illuminate\Support\Str::limit($article->content, 80) }}</p>
                                                <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-outline-primary">
                                                    View article
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="alert alert-light text-center">This user has not written any articles yet.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-3 mb-4">

            <div class="card border-0 text-center p-4">
                <div class="mb-3">
                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center text-primary fw-bold"
                        style="width: 100px; height: 100px; font-size: 2.5rem;">
                        {{ substr($user->first_name, 0, 1) }}
                    </div>
                </div>

                <h3 class="fw-bold">{{ $user->first_name }} {{ $user->last_name }}</h3>
                <p class="text-muted">Joined: {{ $user->created_at->format('d.m.Y') }}</p>
                <p>{{ $user->email }}</p>

                <h5 class="card-title fw-bold mb-3">Quick actions</h5>
                <div class="d-grid gap-2">

                    <a href="{{ route('business.create') }}" class="btn btn-outline-primary text-start">
                        <i class="bi bi-briefcase me-2"></i> Create new business
                    </a>

                    <a href="{{ route('todos.create') }}" class="btn btn-outline-primary text-start">
                        <i class="bi bi-lightbulb me-2"></i> Submit idea
                    </a>
                    <a href="{{ route('polls.create') }}" class="btn btn-outline-primary text-start">
                        <i class="bi bi-lightbulb me-2"></i> Add poll
                    </a>
                </div>
            </div>

        </div>




    </div>
</div>