<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Article\Article;

class Profile extends Component
{
    public User $user;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->user->load('ownedBusinesses');
    }

    public function render()
    {
        $articles = Article::where('user_id', $this->user->id)
            ->withCount(['votes as upvotes_count' => function ($query) {
                $query->where('value', 1);
            }, 'votes as downvotes_count' => function ($query) {
                $query->where('value', -1);
            }])
            ->latest()
            ->get();

        $reputation = $articles->sum(function ($article) {
            return $article->upvotes_count - $article->downvotes_count;
        });

        return view('livewire.profile', [
            'articles' => $articles,
            'reputation' => $reputation
        ]);
    }
}