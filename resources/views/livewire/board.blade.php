<div class="container py-5">
    <h2 class="mb-4 border-bottom pb-2">Lista życzeń / Pomysły</h2>
    
    <div class="row">
        <!-- Formularz dodawania -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 sticky-top" style="top: 20px; z-index: 1;">
                <div class="card-body">
                    <h5 class="card-title mb-3 fw-bold"><i class="bi bi-lightbulb"></i> Zgłoś pomysł</h5>
                    @auth
                        <form wire:submit.prevent="save">
                            <div class="mb-3">
                                <label class="form-label small text-muted">Tytuł pomysłu</label>
                                <input type="text" wire:model="title" class="form-control @error('title') is-invalid @enderror" placeholder="Np. Ciemny motyw strony">
                                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label small text-muted">Opis szczegółowy</label>
                                <textarea wire:model="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Opisz dlaczego to jest potrzebne..."></textarea>
                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Wyślij pomysł</button>
                        </form>
                        @if (session()->has('message'))
                            <div class="alert alert-success mt-3 small">
                                {{ session('message') }}
                            </div>
                        @endif
                    @else
                        <div class="alert alert-light text-center border">
                            <a href="{{ route('login') }}">Zaloguj się</a>, aby dodać pomysł.
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Lista pomysłów -->
        <div class="col-md-8">
            @foreach($todos as $todo)
                <div class="card mb-3 shadow-sm border-0" x-data="{ showComments: false }">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <h5 class="card-title fw-bold">{{ $todo->title }}</h5>
                            <span class="badge rounded-pill {{ $todo->status === 'completed' ? 'bg-success' : ($todo->status === 'planned' ? 'bg-info' : 'bg-secondary') }}">
                                {{ $todo->status === 'pending' ? 'Oczekujący' : ($todo->status === 'planned' ? 'Planowany' : 'Zrealizowany') }}
                            </span>
                        </div>
                        <p class="card-text text-muted small mb-2">
                            <i class="bi bi-person"></i> {{ $todo->user->first_name }} &bull; {{ $todo->created_at->format('d.m.Y') }}
                        </p>
                        <p class="card-text">{{ $todo->description }}</p>
                        
                        <div class="border-top pt-2 mt-3">
                            <button class="btn btn-sm btn-link text-decoration-none ps-0" @click="showComments = !showComments">
                                <i class="bi bi-chat-left-text"></i> Komentarze ({{ $todo->comments_count }}) <i class="bi" :class="showComments ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                            </button>
                        </div>
                        
                        <div x-show="showComments" style="display: none;" class="mt-3 border-top pt-3">
                            <livewire:article.comments :model="$todo" :key="'todo-comments-'.$todo->id" />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
                        </p>
                        <p class="card-text">{{ $todo->description }}</p>
                        
                        <div class="mb-2">
                            <livewire:article.vote :model="$todo" :key="'vote-todo-'.$todo->id" />
                        </div>

                        <div class="border-top pt-2 mt-3">
                            <button class="btn btn-sm btn-link text-decoration-none ps-0" @click="showComments = !showComments">
                                <i class="bi bi-chat-left-text"></i> Komentarze ({{ $todo->comments_count }}) <i class="bi" :class="showComments ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
