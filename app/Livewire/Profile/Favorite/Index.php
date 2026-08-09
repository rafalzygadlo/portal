<?php

namespace App\Livewire\Profile\Favorite;

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
            'App\Models\Offer' => Offer::class,
            'App\Models\Article' => Article::class,
            'App\Models\Business' => Business::class,
            'App\Models\Todo' => Todo::class,
            'App\Models\Poll' => Poll::class,
            default => null,
        };
    }

    public function render()
    {
        //$typeClass = $this->resolveTypeClass($this->type);

        $favorites = auth()->user()
            ->favorites()
            ->with('favoritable')
            //->when($typeClass, fn($q) => $q->where('favoritable_type', $typeClass))
            ->latest()
            ->paginate(10);

        return view('livewire.profile.favorite.index', [
            'favorites' => $favorites,
        ]);
    }
}
