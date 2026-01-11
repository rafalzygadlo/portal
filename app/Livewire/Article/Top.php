<?php

namespace App\Livewire\Article;

use Livewire\Component;
use App\Models\Article\Article;

class Top extends Component
{
    public function render()
    {
        $topArticles = Article::with('user')->withCount('votes')
            ->whereDoesntHave('category', function ($query) {
                $query->where('slug', 'spam');
            })
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->orderByDesc('votes_count')
            ->take(10)
            ->get();

        return view('livewire.article.top', [
            'topArticles' => $topArticles
        ]);
    }
}