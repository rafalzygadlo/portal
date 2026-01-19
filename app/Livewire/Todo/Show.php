<?php

namespace App\Livewire\Todo;

use App\Models\Todo;
use Livewire\Component;

class Show extends Component
{
    public Todo $todo;

    public function mount(Todo $todo)
    {
        $this->todo = $todo->load('user', 'comments.user', 'comments.replies');
    }

    public function render()
    {
        return view('livewire.todo.show');
    }
}
