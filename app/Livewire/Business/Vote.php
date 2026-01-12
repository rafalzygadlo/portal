<?php

namespace App\Livewire\Business;

use App\Models\Business;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Vote extends Component
{
    public Business $business;
    public int $score;
    public ?int $userVote = null;

    public function mount(Business $business)
    {
        $this->business = $business;
        $this->score = $this->business->getScore();
        $this->updateUserVote();
    }

    public function render()
    {
        return view('livewire.business.vote');
    }

    public function upvote()
    {
        if (!Auth::check()) {
            return $this->redirect(route('login'));
        }

        $this->toggleVote(1);
    }

    public function downvote()
    {
        if (!Auth::check()) {
            return $this->redirect(route('login'));
        }

        $this->toggleVote(-1);
    }

    private function toggleVote(int $value)
    {
        // Jeśli użytkownik już tak zagłosował, usuń głos (ustaw na 0)
        if ($this->userVote === $value) {
            $this->business->votes()->where('user_id', Auth::id())->delete();
        } else {
            // W przeciwnym razie, utwórz lub zaktualizuj głos
            $this->business->votes()->updateOrCreate(
                ['user_id' => Auth::id()],
                ['value' => $value]
            );
        }
        
        $this->score = $this->business->getScore();
        $this->updateUserVote();
    }

    private function updateUserVote(): void
    {
        $vote = $this->business->votes()->where('user_id', Auth::id())->first();
        $this->userVote = $vote ? (int)$vote->value : null;
    }
}
