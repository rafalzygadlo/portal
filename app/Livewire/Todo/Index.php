<?php

namespace App\Livewire\Todo;

use App\Models\Todo;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.todo.index', [
            'todos' => Todo::with('user')->withCount('comments')->latest()->paginate(10)
        ]);
    }
}
