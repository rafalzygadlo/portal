<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class GlobalModal extends Component
{
    public $isOpen = false;
    public $view = '';
    public $title = '';
    public $params = [];

    
    #[On('openModal')]
    public function open($view, $title, $params = [])
    {
        $this->view = $view;
        $this->title = $title;
        $this->params = $params;
        $this->isOpen = true;

    }

    #[On('closeModal')]
    public function close()
    {
        $this->isOpen = false;
        $this->reset(['view','title','params']);
    }

    public function render()
    {
        return view('livewire.global-modal');
    }
}