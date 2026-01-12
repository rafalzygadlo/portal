<?php

namespace App\Livewire\Article;

use Livewire\Component;
use App\Models\Article\Article;

class Top extends Component
{
    public function render()
    {
        $topArticles = Article::with('user')->withSum('votes', 'value')
            //->whereDoesntHave('category', function ($query) {
            //    $query->where('slug', 'spam');
           // })
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->having('votes_sum_value', '>', 10)
            ->orderByDesc('votes_sum_value')
            ->take(10)
            ->get();

        return view('livewire.article.top', [
            'topArticles' => $topArticles
        ]);
    }
}