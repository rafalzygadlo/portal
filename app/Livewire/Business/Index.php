<?php

namespace App\Livewire\Business;

use App\Models\Business;
use App\Models\Category;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public ?string $categorySlug = null;
    public ?Category $currentCategory = null;

    #[Url(except: '', as: 'q')]
    public string $search = '';

    public function mount($categorySlug = null): void
    {
        $this->categorySlug = $categorySlug;
        $this->currentCategory = $this->categorySlug
            ? Category::where('slug', $this->categorySlug)->first()
            : null;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.business.index', [
            'businesses' => $this->getQuery()->paginate(20),
        ]);
    }

    private function getQuery()
    {
        $targetIds = $this->currentCategory ? Category::getAllChildrenIds($this->currentCategory->id) : [];

        return Business::query()->with(['categories', 'images'])
            ->when($this->categorySlug, fn($q) => $q->whereHas('categories', fn($query) => $query->whereIn('categories.id', $targetIds)))
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->latest();
    }
}
