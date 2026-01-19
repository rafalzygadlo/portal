<?php

namespace App\Livewire\Todo;

use App\Models\Todo;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Create extends Component
{
    public $title;
    public $description;

    protected $rules = [
        'title' => 'required|min:5|max:100',
        'description' => 'required|min:10|max:500',
    ];

    public function save()
    {
        $this->validate();

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        Todo::create([
            'user_id' => Auth::id(),
            'title' => $this->title,
            'description' => $this->description,
            'status' => 'pending',
        ]);

        return redirect()->route('todo.index')->with('message', 'Twój pomysł został dodany!');
    }

    public function render()
    {
        return view('livewire.todo.create');
    }
}
