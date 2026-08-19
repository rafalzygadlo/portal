<div>
    <h6 class="mb-2">Comments ({{ $model->comments()->count() }})</h6>

    @auth
        <div class="card shadow-sm border-1">
            <div class="card-body p-2 p-sm-3">
                <form wire:submit.prevent="postComment">
                    <div style="display: none;">
                        <label for="honey_pot">Don't fill this out if you're human:</label>
                        <input type="text" id="honey_pot" name="honey_pot" wire:model="honey_pot" autocomplete="off">
                    </div>
                    @if($replyToId && isset($replyingTo))
                        <div class="alert alert-info py-2 px-3 mb-2 d-flex justify-content-between align-items-center">
                            <small class="mb-0">Odpowiadasz: <span class="fst-italic">"{{ \Illuminate\Support\Str::limit($replyingTo->content, 40) }}"</span></small>
                            <button type="button" wire:click="$set('replyToId', null)" class="btn-close small" aria-label="Cancel"></button>
                        </div>
                    @endif
                    <div class="mb-2">
                        <textarea wire:model="content" class="form-control" rows="2" placeholder="Write a comment..."></textarea>
                        @error('content') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-chat-dots"></i> {{ $replyToId ? 'Send reply' : 'Add comment' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-light border text-center">
            <a href="">Log in</a> to add a comment.
        </div>
    @endauth

    <div class="comments-list">
        @forelse($comments as $comment)
            @if($comment->trashed())
                <x-comment-item-trashed :comment="$comment" />
            @else
                <x-comment-item-normal :comment="$comment" />
            @endif

            @if($comment->replies->isNotEmpty())
                <button type="button" wire:click="toggleReplies({{ $comment->id }})" class="btn btn-link btn-sm text-decoration-none ps-4 py-0 mb-2">
                    <i class="bi bi-chevron-{{ in_array($comment->id, $expandedReplies) ? 'up' : 'down' }} me-1"></i>
                    {{ in_array($comment->id, $expandedReplies) ? 'Hide' : 'Show' }} {{ $comment->replies->count() }} {{ $comment->replies->count() === 1 ? 'reply' : 'replies' }}
                </button>

                @if(in_array($comment->id, $expandedReplies))
                    @foreach($comment->replies as $reply)
                        @if($reply->trashed())
                            <x-comment-item-trashed :comment="$reply" :isReply="true" />
                        @else
                            <x-comment-item-normal :comment="$reply" :isReply="true" />
                        @endif
                    @endforeach
                @endif
            @endif
        @empty
            <p class="text-muted text-center my-4">No comments yet. Be the first!</p>
        @endforelse
    </div>
</div>