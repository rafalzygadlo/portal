<?php

namespace App\Livewire\Business;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Events\VoteCreated;

class Vote extends Component
{
    public $model;
    public $votesCount;
    public $userVote = null; 
    public $isAuthor = false;

    public function mount()
    {
        // Model is already available as $this->model from Livewire property binding
        $this->votesCount = $this->model->getScore();
        
        if (Auth::check()) 
        {
            $vote = $this->model->votes()->where('user_id', Auth::id())->first();
            $this->userVote = $vote ? ($vote->value === 1 ? 'up' : 'down') : null;
            // Sprawdzamy czy user_id istnieje w modelu (niektóre modele mogą nie mieć autora)
            $this->isAuthor = isset($this->model->user_id) && Auth::id() === $this->model->user_id;
        }
    }

    public function vote($type)
    {

        if (!Auth::check()) 
        {
            return redirect()->route('login');
        }

        if (isset($this->model->user_id) && $this->model->user_id === Auth::id()) 
        {
            return;
        }

        $value = $type === 'up' ? 1 : -1;
        $existingVote = $this->model->votes()->where('user_id', Auth::id())->first();

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
                VoteCreated::dispatch(Auth::user(), $this->model, $value);
            }
        } 
        else 
        {
             
            $this->model->votes()->create(['user_id' => Auth::id(), 'value' => $value]);
            $this->userVote = $type;
            VoteCreated::dispatch(Auth::user(), $this->model, $value);
        }
        
        $this->votesCount = $this->model->getScore();

        $this->dispatch('business-voted');
    }

    public function render()
    {
        return view('livewire.business.vote');
    }
}
