<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Todo;
use Illuminate\Support\Facades\Auth;

class Board extends Component
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

        $this->reset(['title', 'description']);
        session()->flash('message', 'Twój pomysł został dodany!');
    }

    public function render()
    {
        return view('livewire.board', [
            'todos' => Todo::with('user')->withCount('comments')->latest()->get()
        ]);
    }
}
