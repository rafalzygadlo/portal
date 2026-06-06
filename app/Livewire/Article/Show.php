<?php

namespace App\Livewire\Article;

use Livewire\Component;
use App\Models\Article;

class Show extends Component
{
    public Article $article;

    public function mount(Article $article)
    {
        $this->article = $article;
    }

    public function render()
    {
        return view('livewire.article.show');
    }
}
