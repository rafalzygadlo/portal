<?php

namespace App\Livewire\Poll;

use App\Models\Poll\Poll;
use App\Models\Vote;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Show extends Component
{
    public Poll $poll;
    public $selectedOptionId;

    public function mount(Poll $poll)
    {
        $this->poll = $poll->load('options.votes');
        if (Auth::check()) {
            $this->selectedOptionId = $this->poll->options
                ->firstWhere('votes.user_id', Auth::id())?->id;
        }
    }

    public function vote()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate([
            'selectedOptionId' => 'required|exists:poll_options,id',
        ]);

        $userVote = Vote::where('user_id', Auth::id())
            ->whereIn('voteable_id', $this->poll->options->pluck('id'))
            ->where('voteable_type', \App\Models\PollOption::class)
            ->first();

        if ($userVote) {
            $userVote->delete();
        }

        Vote::create([
            'user_id' => Auth::id(),
            'voteable_id' => $this->selectedOptionId,
            'voteable_type' => \App\Models\PollOption::class,
            'value' => 1,
        ]);
        
        $this->poll->refresh();
        $this->selectedOptionId = $this->poll->options
            ->firstWhere('votes.user_id', Auth::id())?->id;

        session()->flash('status', 'Dziękujemy za oddanie głosu!');
    }

    public function render()
    {
        return view('livewire.poll.show');
    }
}
