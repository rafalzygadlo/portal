<?php

namespace App\Livewire\Profile\Article;

use App\Models\Article;

class Show extends Edit
{
    public function mount(Article $article)
    {
        parent::mount($article);
    }
}
