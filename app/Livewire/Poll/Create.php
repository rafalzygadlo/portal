<?php

namespace App\Livewire\Poll;

use App\Models\Poll\Pool;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Create extends Component
{
    public $question;
    public $options = [''];

    protected $rules = [
        'question' => 'required|min:3',
        'options' => 'required|array|min:2',
        'options.*' => 'required|min:1',
    ];

    public function addOption()
    {
        $this->options[] = '';
    }

    public function removeOption($index)
    {
        unset($this->options[$index]);
        $this->options = array_values($this->options);
    }

    public function createPoll()
    {
        $this->validate();

        $poll = Poll::create([
            'question' => $this->question,
            'user_id' => Auth::id(),
        ]);

        foreach ($this->options as $optionName) {
            $poll->options()->create(['name' => $optionName]);
        }

        session()->flash('status', 'Ankieta została pomyślnie utworzona.');

        return redirect()->route('polls.index');
    }

    public function render()
    {
        return view('livewire.poll.create');
    }
}
