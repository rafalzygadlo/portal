<?php

namespace App\Livewire\Profile\Article;

use Livewire\Component;
use App\Models\Article;

class Index extends Component
{
    public function render()
    {
        $articles = auth()->user()->articles()->latest()->get();
        
        return view('livewire.profile.article.index', [
            'articles' => $articles
        ]);
    }
}
