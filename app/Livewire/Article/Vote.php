<?php

namespace App\Livewire\Article;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Article\Article;

class Vote extends Component
{
    public $articleId;
    public $votesCount;
    public $userVote = null; // 'up', 'down', or null
    public $isAuthor = false;
    public $article;

    public function mount($article)
    {
        $this->article = $article;
        $this->articleId = $article->id;
        $this->votesCount = $article->getScore();
        
        if (Auth::check()) 
        {
            $vote = $article->votes()->where('user_id', Auth::id())->first();
            $this->userVote = $vote ? ($vote->value === 1 ? 'up' : 'down') : null;
            $this->isAuthor = Auth::id() === $article->user_id;
        }
    }

    public function vote($type)
    {

        if (!Auth::check()) 
        {
            return redirect()->route('login');
        }

        $article = Article::findOrFail($this->articleId);

        if ($article->user_id === Auth::id()) 
        {
            return;
        }

        $value = $type === 'up' ? 1 : -1;
        $existingVote = $article->votes()->where('user_id', Auth::id())->first();

        if ($existingVote) 
        {
            if ($existingVote->value === $value) 
            {
                // Toggle off if clicking the same vote
                $existingVote->delete();
                $this->userVote = null;
            } else {
                // Change vote type
                $existingVote->update(['value' => $value]);
                $this->userVote = $type;
            }
        } 
        else 
        {
             
            $article->votes()->create(['user_id' => Auth::id(), 'value' => $value]);
            $this->userVote = $type;
        }
        
        $this->votesCount = $article->getScore();
        $this->dispatch('article-voted');
    }

    public function render()
    {
        return view('livewire.article.vote');
    }
}