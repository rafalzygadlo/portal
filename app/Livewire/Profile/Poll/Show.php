<?php

namespace App\Livewire\Profile\Poll;

use App\Models\Poll\Poll;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Show extends Component
{
    use AuthorizesRequests;

    public Poll $poll;
    public $question;
    public $options = [];

    protected $rules = [
        'question' => 'required|min:3',
        'options' => 'required|array|min:2',
        'options.*' => 'required|min:1',
    ];

    public function mount(Poll $poll)
    {
        $this->authorize('update', $poll);
        
        $this->poll = $poll;
        $this->question = $poll->question;
        $this->options = $poll->options()->pluck('name', 'id')->toArray();
    }

    public function addOption()
    {
        $this->options[] = '';
    }

    public function removeOption($index)
    {
        unset($this->options[$index]);
        $this->options = array_values($this->options);
    }

    public function save()
    {
        $this->validate();

        $this->poll->update([
            'question' => $this->question,
        ]);

        $this->poll->options()->delete();
        foreach ($this->options as $optionName) {
            $this->poll->options()->create(['name' => $optionName]);
        }

        session()->flash('status', 'Ankieta została zaktualizowana!');
        return $this->redirect(route('user.profile'));
    }

    public function delete()
    {
        $this->authorize('delete', $this->poll);
        $this->poll->delete();
        
        session()->flash('status', 'Ankieta została usunięta!');
        return $this->redirect(route('user.profile'));
    }

    public function render()
    {
        return view('livewire.profile.poll.show');
    }
}
