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
        dd("authChanged event received in GlobalModal");
        dd($this->view);
    }

    #[On('openModal')]
    public function open($view, $title = '', $auth = true, $params = [])
    {
        
        $this->view = $view;
        $this->title = $title;
        $this->params = $params;
        $this->isOpen = true;


        if (auth()->guest()) {
            $this->view = 'auth.login';
            $this->title = 'Login Required';

            session()->put('intended_modal', [
                'component' => $view,
                'title' => $title,
                'params' => $params,
            ]);

            return;
        }

        if (! auth()->user()->hasVerifiedEmail()) {
            $this->view = 'auth.login';
            $this->title = 'Login Required';

            session()->put('intended_modal', [
                'component' => $view,
                'title' => $title,
                'params' => $params,
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