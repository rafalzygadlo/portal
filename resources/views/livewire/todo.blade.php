<div>
    <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
        <div>
            <h2 class="mb-1">Wishlist / Pomysły</h2>
            <p class="text-muted small mb-0">Zobacz propozycje zmian i dodaj własne pomysły.</p>
        </div>
    </div>

    <div class="row">
        <!-- Add idea form -->
        <div class="col-md-4 mb-4">
            <div class="todo-card section-card sticky-top p-4">
                <div class="mb-4">
                    <h5 class="fw-bold mb-1"><i class="bi bi-lightbulb-fill text-warning"></i> Dodaj pomysł</h5>
                    <p class="text-muted small mb-0">Twoje sugestie pomagają rozwijać portal.</p>
                </div>
                @auth
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label class="form-label small text-muted">Tytuł pomysłu</label>
                            <input type="text" wire:model="title" class="form-control @error('title') is-invalid @enderror" placeholder="Np. Tryb ciemny dla strony">
                            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Szczegóły</label>
                            <textarea wire:model="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Opisz dlaczego to warto dodać..."></textarea>
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
                        <a href="{{ route('login') }}">Zaloguj się</a> aby dodać pomysł.
                    </div>
                @endauth
            </div>
        </div>

        <!-- Ideas list -->
        <div class="col-md-8">
            @foreach($todos as $todo)
                <div class="todo-card section-card mb-3" x-data="{ showComments: false }">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3 gap-3">
                            <div>
                                <h5 class="card-title fw-bold mb-1">{{ $todo->title }}</h5>
                                <div class="text-muted small d-flex flex-wrap gap-2">
                                    <span><i class="bi bi-person"></i> {{ $todo->user->first_name }}</span>
                                    <span><i class="bi bi-calendar-event"></i> {{ $todo->created_at->format('d.m.Y') }}</span>
                                </div>
                            </div>
                            <span class="status-pill {{ $todo->status === 'completed' ? 'bg-success' : ($todo->status === 'planned' ? 'bg-info' : 'bg-secondary') }}">
                                {{ $todo->status === 'pending' ? 'Pending' : ($todo->status === 'planned' ? 'Planned' : 'Completed') }}
                            </span>
                        </div>

                        <p class="card-text text-muted mb-4">{{ $todo->description }}</p>
                        <div class="mb-3">
                            <livewire:article.vote :model="$todo" :key="'vote-todo-'.$todo->id" />
                        </div>
                        <div class="border-top pt-3 mt-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <button class="btn btn-sm btn-outline-primary" @click="showComments = !showComments">
                                <i class="bi bi-chat-left-text me-1"></i>
                                Komentarze ({{ $todo->comments_count }})
                                <i class="bi" :class="showComments ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                            </button>
                        </div>

                        <div x-show="showComments" style="display: none;" class="mt-3 border-top pt-3">
                            <livewire:comments :model="$todo" :key="'todo-comments-'.$todo->id" />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
                      