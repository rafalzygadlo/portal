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
        $this->votesCount = $article->score;
        
        if (Auth::check()) 
        {
            $vote = $article->votes()->where('user_id', Auth::id())->first();
            dd($vote);
            $this->userVote = $vote ? $vote->type : null;
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

        $existingVote = $article->votes()->where('user_id', Auth::id())->first();

        if ($existingVote) 
        {
            if ($existingVote->type === $type) 
            {
                // Toggle off if clicking the same vote
                $existingVote->delete();
                $this->userVote = null;
            } else {
                // Change vote type
                $existingVote->update(['type' => $type]);
                $this->userVote = $type;
            }
        } 
        else 
        {
             //       dd ($type);
            $article->votes()->create(['user_id' => Auth::id(), 'type' => $type]);
            $this->userVote = $type;
        }
        
        $this->article->refresh();
        $this->votesCount = $this->article->score;
    }

    public function render()
    {
        return view('livewire.article.vote');
    }
}