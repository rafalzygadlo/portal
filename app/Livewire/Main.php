<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Article\Article;

class Main extends Component
{
    public function render()
    {
        $latestArticles = Article::with('user')
            ->whereDoesntHave('category', function ($query) {
                $query->where('slug', 'spam');
            })
            ->latest()
            ->take(11)
            ->get();

        $topArticles = Article::with('user')->withCount('votes')
            ->whereDoesntHave('category', function ($query) {
                $query->where('slug', 'spam');
            })
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->orderByDesc('votes_count')
            ->take(10)
            ->get();

        return view('livewire.main', [
            'latestArticles' => $latestArticles,
            'topArticles' => $topArticles,
        ]);
    }
}
