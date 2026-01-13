<?php

namespace App\Livewire\Article;

use Livewire\Component;
use App\Models\Article\Article;
use Illuminate\Support\Facades\Auth;

class Latest extends Component
{
    protected string $paginationTheme = 'bootstrap';
    public $sort = 'newest';

    public $page = 1;
    protected $listeners = ['article-voted' => '$refresh'];

    public function setSort($sort)
    {
        $this->sort = $sort;
    }

    public function more()
    {
        $this->page++;
    }


    public function render()
    {
           $query = Article::with('user')
            //->whereDoesntHave('category', function ($query) {
            //    $query->where('slug', 'spam');
            //})
            ->withSum('votes', 'value');

            if ($this->sort === 'popular') {
                $query->orderBy('votes_sum_value', 'desc');
            }

            $latestArticles = $query->latest()
            ->paginate(20 * $this->page);

        return view('livewire.article.latest', [
            'latestArticles' => $latestArticles,
    
        ]);
    }
}