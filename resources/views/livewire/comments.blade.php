<div class="mt-5">
    <h3 class="mb-4">Komentarze ({{ $model->comments()->count() }})</h3>

    @auth
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body">
                <form wire:submit.prevent="postComment">
                    <div style="display: none;">
                        <label for="honey_pot">Don't fill this out if you're human:</label>
                        <input type="text" id="honey_pot" name="honey_pot" wire:model="honey_pot" autocomplete="off">
                    </div>
                    @if($replyToId && isset($replyingTo))
                        <div class="alert alert-info py-2 px-3 mb-3 d-flex justify-content-between align-items-center">
                            <small class="mb-0">Odpowiadasz: <span class="fst-italic">"{{ \Illuminate\Support\Str::limit($replyingTo->content, 40) }}"</span></small>
                            <button type="button" wire:click="$set('replyToId', null)" class="btn-close small" aria-label="Anuluj"></button>
                        </div>
                    @endif
                    <div class="mb-3">
                        <textarea wire:model="content" class="form-control" rows="3" placeholder="Napisz komentarz..."></textarea>
                        @error('content') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-chat-dots"></i> {{ $replyToId ? 'Wyślij odpowiedź' : 'Dodaj komentarz' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-light border text-center">
            <a href="{{ route('login') }}">Zaloguj się</a>, aby dodać komentarz.
        </div>
    @endauth

    <div class="comments-list">
        @forelse($comments as $comment)
            <div class="card mb-3 border-0 bg-light">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="fw-bold">
                            <i class="bi bi-person-circle text-secondary"></i> {{ $comment->user->first_name ?? 'Użytkownik' }}
                        </div>
                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                    </div>
                    <p class="mb-1">{{ $comment->content }}</p>
                    
                    <div class="d-flex gap-3 mt-2">
                        @auth
                            <button wire:click="$set('replyToId', {{ $comment->id }})" class="btn btn-link text-primary btn-sm p-0 text-decoration-none">
                                <i class="bi bi-reply"></i> Odpowiedz
                            </button>
                        @endauth

                        @if(Auth::id() === $comment->user_id)
                            <button wire:click="delete({{ $comment->id }})" class="btn btn-link text-danger btn-sm p-0 text-decoration-none" onclick="confirm('Czy na pewno usunąć?') || event.stopImmediatePropagation()">
                                Usuń
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            @foreach($comment->replies as $reply)
                <div class="card mb-3 border-0 ms-5" style="background-color: #f8f9fa;">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div class="fw-bold small">
                                <i class="bi bi-arrow-return-right text-secondary me-1"></i> {{ $reply->user->first_name ?? 'Użytkownik' }}
                            </div>
                            <small class="text-muted" style="font-size: 0.8rem;">{{ $reply->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1 small">{{ $reply->content }}</p>
                        
                        @if(Auth::id() === $reply->user_id)
                            <button wire:click="delete({{ $reply->id }})" class="btn btn-link text-danger btn-sm p-0 text-decoration-none" style="font-size: 0.8rem;" onclick="confirm('Czy na pewno usunąć?') || event.stopImmediatePropagation()">
                                Usuń
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        @empty
            <p class="text-muted text-center">Brak komentarzy. Bądź pierwszy!</p>
        @endforelse
    </div>
</div>