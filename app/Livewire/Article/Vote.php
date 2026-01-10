<?php

namespace App\Livewire\Article;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Article\Article;

class Vote extends Component
{
    public $articleId;
    public $votesCount;
    public $hasVoted = false;
    public $isAuthor = false;

    public function mount($article)
    {
        $this->articleId = $article->id;
        $this->votesCount = $article->votes()->count();
        
        if (Auth::check()) {
            $this->hasVoted = $article->votes()->where('user_id', Auth::id())->exists();
            $this->isAuthor = Auth::id() === $article->user_id;
        }
    }

    public function vote()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $article = Article::findOrFail($this->articleId);

        if ($article->user_id === Auth::id()) {
            return;
        }

        if ($this->hasVoted) {
            $article->votes()->where('user_id', Auth::id())->delete();
            $this->hasVoted = false;
            $this->votesCount--;
        } else {
            $article->votes()->create(['user_id' => Auth::id()]);
            $this->hasVoted = true;
            $this->votesCount++;
        }
    }

    public function render()
    {
        return view('livewire.article.vote');
    }
}