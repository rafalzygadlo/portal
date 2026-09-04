<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Emit;
use Auth;

class GlobalModal extends Component
{
    public $isOpen = false;
    public $view = '';
    public $title = '';
    public $params = [];

    
    #[On('authChanged')]
    public function authChanged()
    {
        $intendedModal = session()->pull('intended_modal');

        if (!$intendedModal) {
            return;
        }

        $this->view = $intendedModal['component'];
        $this->title = $intendedModal['title'] ?? '';
        $this->params = $intendedModal['params'] ?? [];
        $this->isOpen = true;
    }

    #[On('openModal')]
    public function open($view, $title = '', $auth = true, $modalParams = [])
    {
        
        $this->view = $view;
        $this->title = $title;
        $this->params = $modalParams;
        $this->isOpen = true;


        if (auth()->guest()) {
            $this->view = 'auth.login';
            $this->title = 'Login Required';

            session()->put('intended_modal', [
                'component' => $view,
                'title' => $title,
                'params' => $modalParams,
            ]);

            return;
        }

        if (! auth()->user()->hasVerifiedEmail()) {
            $this->view = 'auth.login';
            $this->title = 'Login Required';

            session()->put('intended_modal', [
                'component' => $view,
                'title' => $title,
                'params' => $modalParams,
            ]);
        }
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