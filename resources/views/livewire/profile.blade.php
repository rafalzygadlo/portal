<div class="container py-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            
            <div class="card border-0 text-center p-4">
                <div class="mb-3">
                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center text-primary fw-bold" style="width: 100px; height: 100px; font-size: 2.5rem;">
                        {{ substr($user->first_name, 0, 1) }}
                    </div>
                </div>
                <h3 class="fw-bold">{{ $user->first_name }} {{ $user->last_name }}</h3>
                <p class="text-muted">Dołączył: {{ $user->created_at->format('d.m.Y') }}</p>
                <p class="text-muted">Email: {{ $user->email }}</p>
                
            </div>

            @if(Auth::id() === $user->id)
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">Szybkie akcje</h5>
                        <div class="d-grid gap-2">
                             
                            <a href="{{ route('business.create') }}" class="btn btn-outline-primary text-start">
                                <i class="bi bi-briefcase me-2"></i> Załóż nowy biznes
                            </a>
                            
                            <a href="{{ route('todos.create') }}"  class="btn btn-outline-primary text-start">
                                <i class="bi bi-lightbulb me-2"></i> Zgłoś pomysł
                            </a>
                            <a href="{{ route('polls.create') }}"  class="btn btn-outline-primary text-start">
                                <i class="bi bi-lightbulb me-2"></i> Dodaj ankiete
                            </a>
                        </div>
                    </div>
                </div>
            @endif

        </div>

        <div class="col-md-8">
            <h4 class="mb-4 pb-2 border-bottom">Biznesy użytkownika</h4>
            <div class="row mb-5">
                    @forelse($user->ownedBusinesses as $business)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold">{{ $business->name }}</h5>
                                    <p class="card-text text-muted small mb-3">{{ \Illuminate\Support\Str::limit($business->description, 60) }}</p>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('business.domain', ['subdomain' => $business->subdomain]) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                                            <i class="bi bi-globe"></i> Strona
                                        </a>
                                        @if(Auth::id() === $user->id)
                                            <a href="{{ route('dashboard.business', ['subdomain' => $business->subdomain, 'business' => $business->id]) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-speedometer2"></i> Dashboard
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty

                <div class="alert alert-light text-center">Ten użytkownik nie zarządza jeszcze żadnym biznesem.</div>
            @endforelse
                </div>

          
    </div>
</div>
