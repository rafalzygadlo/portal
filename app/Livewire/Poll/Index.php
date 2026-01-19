<?php

namespace App\Livewire\Poll;

use App\Models\Poll\Poll;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        $polls = Poll::with('user')->latest()->paginate(10);

        return view('livewire.poll.index', [
            'polls' => $polls,
        ]);
    }
}
