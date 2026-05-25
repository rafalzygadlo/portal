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

    #[Session]
    public $selectedCategories = [];

    public $categorySlug = null;
    public $breadcrumb = [];


    public function mount($categorySlug = null)
    {
        $this->categorySlug = $categorySlug;
    }

    public function render()
    {

        $categories = Category::whereNull('parent_id')->with('children')->withCount('offers')->get();    
     
        // Build breadcrumb path
        $this->breadcrumb = [];
        if ($this->categorySlug) 
        {
            // Eager load 'parent' to avoid N+1 queries when building the path
            $currentCategory = Category::with('parent')->where('slug', $this->categorySlug)->first();
            if ($currentCategory) 
            {
                $path = collect();
                $category = $currentCategory;
                while ($category) 
                {
                    $path->prepend($category);
                    $category = $category->parent;
                }
                $this->breadcrumb = $path->all();
            }

            $categories = Category::where('parent_id',$currentCategory->id)->with('children')->withCount('offers')->get();    
     
        }

        $offers = Offer::with(['user', 'categories', 'images'])
            ->when($this->categorySlug, function ($q) {
                $q->whereHas('categories', function ($query) {
                    $query->where('categories.slug', $this->categorySlug);
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
