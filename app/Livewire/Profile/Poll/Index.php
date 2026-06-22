<?php

namespace App\Livewire\Profile\Poll;

use Livewire\Component;
use App\Models\Poll\Poll;

class Index extends Component
{
    public function render()
    {
        $polls = auth()->user()->polls()->latest()->get();
        
        return view('livewire.profile.poll.index', [
            'polls' => $polls
        ]);
    }
}
