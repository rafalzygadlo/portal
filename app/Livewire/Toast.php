<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class Toast extends Component
{
    public $show = false;
    public $message = '';
    public $type = 'success';

    #[On('toast'), On('showToast')]
    public function showToast($message, $type = 'success')
    {
        $this->message = $message;
        $this->type = $type;
        $this->show = true;
    }

    public function render()
    {
        return view('livewire.toast');
    }
}
