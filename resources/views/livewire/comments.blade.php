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
            <x-livewire.components.comment-item :comment="$comment" />

            @foreach($comment->replies as $reply)
                <x-livewire.components.comment-item :comment="$reply" :isReply="true" />
            @endforeach
        @empty
            <p class="text-muted text-center">Brak komentarzy. Bądź pierwszy!</p>
        @endforelse
    </div>
</div>