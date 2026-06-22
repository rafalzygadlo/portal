<?php

namespace App\Livewire\Profile\Comment;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Offer;
use App\Models\Article;
use App\Models\Business;
use App\Models\Todo;
use App\Models\Poll\Poll;

class Index extends Component
{
    use WithPagination;

    public string $type = 'all';
    protected $queryString = ['type'];
    protected $paginationTheme = 'bootstrap';

    public function updatingType(): void
    {
        $this->resetPage();
    }

    private function resolveTypeClass(string $type): ?string
    {
        return match ($type) {
            'offer' => Offer::class,
            'article' => Article::class,
            'business' => Business::class,
            'todo' => Todo::class,
            'poll' => Poll::class,
            default => null,
        };
    }

    public function render()
    {
        $typeClass = $this->resolveTypeClass($this->type);

        $comments = auth()->user()
            ->comments()
            ->with('commentable')
            ->when($typeClass, fn($q) => $q->where('commentable_type', $typeClass))
            ->latest()
            ->paginate(10);

        return view('livewire.profile.comment.index', [
            'comments' => $comments,
        ]);
    }
}
