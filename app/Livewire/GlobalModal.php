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
        //if($auth && !Auth::check()) {
        //    $this->view = 'auth.login'; 
        //    $this->title = 'Login Required';
        //    $this->isOpen = true;
        //    return;
        //}

        $this->view = $view; 
        $this->title = $title;
        
        $this->isOpen = true;
        
        if (auth()->guest()) {
            // Zapisujemy parametry modala w sesji przed przekierowaniem
                $this->view = 'auth.login'; 
            $this->title = 'Login Required';
        
            session()->put('intended_modal', [
                'component' => $view,
                'title' => $title
            ]);

            return;  //redirect()->guest(route('login'));
        }

        // Sprawdzamy czy użytkownik ma zweryfikowany adres email
        if (! auth()->user()->hasVerifiedEmail()) {
            $this->view = 'auth.login'; 
            $this->title = 'Login Required';
            
        
        session()->put('intended_modal', [
                'component' => $view,
                'title' => $title
            ]);

            //return redirect()->route('verification.notice');
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