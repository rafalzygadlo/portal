<?php

namespace App\Livewire\Article;

use Livewire\Component;
use App\Models\Article\Article;

class Latest extends Component
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

        return view('livewire.article.latest', [
            'latestArticles' => $latestArticles
        ]);
    }
}