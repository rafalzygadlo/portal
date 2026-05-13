<?php

namespace App\Livewire\Offer;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Offer\Offer;
use App\Models\Category;
use Livewire\Attributes\Session;
use Illuminate\Http\Request;

class Index extends Component
{
    use WithPagination;

    // #[Session] - This attribute is not standard Livewire for URL parameters.
    // If you intend to filter by URL parameters, consider using #[Url].
    // For breadcrumbs, we'll derive the current category from the request.
    #[Session]
    public $selectedCategories = [];

    public $categorySlug = null;
    public $breadcrumb = [];
    public $filterCategoryId = null;

    protected $listeners = [
        'categorySelected' => 'selectCategoryFilter'
    ];

    public function selectCategoryFilter($categoryId)
    {
        $this->filterCategoryId = $categoryId;
        $this->resetPage();
    }

    public function mount($categorySlug = null)
    {
        $this->categorySlug = $categorySlug;
    }

    public function handleCreateOffer()
    {
        if (auth()->guest()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        $this->dispatch('openOfferModal');
    }

    public function render()
    {

        // Pobieramy tylko te główne kategorie, które same mają oferty 
        // LUB których dowolne dziecko (lub wnuk) ma oferty
        $categories = Category::whereNull('parent_id')
            ->where(function ($query) {
                $query->has('offers')
                      ->orWhereHas('children', function ($q) {
                          $q->has('offers');
                      });
            })
            ->with(['children' => function ($query) {
                $query->has('offers');
            }])
            ->get();

        // Build breadcrumb path
        $this->breadcrumb = [];
        if ($this->categorySlug) {
            // Eager load 'parent' to avoid N+1 queries when building the path
            $currentCategory = Category::with('parent')->where('slug', $this->categorySlug)->first();
            if ($currentCategory) {
                $path = collect();
                $category = $currentCategory;
                while ($category) {
                    $path->prepend($category);
                    $category = $category->parent;
                }
                $this->breadcrumb = $path->all();
            }
        }

        $offers = Offer::with(['user', 'categories', 'images'])
            ->when(!empty($this->selectedCategories), function ($q) {
                $q->whereHas('categories', function ($query) {
                    $query->whereIn('slug', $this->selectedCategories);
                });
            })
            ->when($this->categorySlug, function ($q) {
                $q->whereHas('categories', function ($query) {
                    $query->where('categories.slug', $this->categorySlug);
                });
            })
            ->when($this->filterCategoryId, function ($q) {
                $q->whereHas('categories', function ($query) {
                    $query->where('categories.id', $this->filterCategoryId);
                });
            })
            ->latest()
            ->paginate(12);

        return view('livewire.offer.index', [
            'offers' => $offers,
            'categories' => $categories,
            'breadcrumb' => $this->breadcrumb, // Pass breadcrumb to the view
        ]);

    }
}
