<?php

namespace App\Livewire\Article;

use Livewire\Component;
use App\Models\Article\Article;

class Top extends Component
{
    public function render()
    {
        $topArticles = Article::with('user')->withSum('votes', 'value')
            ->whereDoesntHave('category', function ($query) {
                $query->where('slug', 'spam');
            })
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->having('votes_sum_value', '>', 100)
            ->orderByDesc('votes_sum_value')
            ->take(10)
            ->get();

        return view('livewire.article.top', [
            'topArticles' => $topArticles
        ]);
    }
}