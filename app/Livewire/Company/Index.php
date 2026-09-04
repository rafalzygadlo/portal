<?php

namespace App\Livewire\Company;

use App\Models\Company;
use App\Models\Category;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public  $categorySlug = null;


    #[Url(except: '', as: 'q')]
    public string $search = '';

     protected $listeners = [
        'company-category-selected' => 'selectCategory',
    ];

     public function selectCategory($slug = null)
    {
        $this->categorySlug = $slug;
        $this->resetPage();
    }
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    private function getQuery(?Category $currentCategory)
    {
        $targetIds = $currentCategory ? Category::getAllChildrenIds($currentCategory->id) : [];

        return Company::query()->with(['categories', 'images'])
            ->when($this->categorySlug, fn($q) => $q->whereHas('categories', fn($query) => $query->whereIn('categories.id', $targetIds)))
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->latest();
    }

    public function render()
    {
        $currentCategory = $this->categorySlug
            ? Category::where('slug', $this->categorySlug)->first()
            : null;

        return view('livewire.company.index', [
            'currentCategory' => $currentCategory,
            'companies' => $this->getQuery($currentCategory)->paginate(20),
        ]);
    }


}
