<?php

namespace App\Livewire;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;


class Favorite extends Component
{
    public Model $model;
    public $isFavorite = false;
    public $count = 0;

    public function mount()
    {
        $this->loadFavoriteState();
    }

    public function toggle()
    {
        if (!Auth::check()) 
        {
            session(['url.intended' => request()->url()]);
            return $this->redirect(route('login'), navigate: true);
        }

        if ($this->model->isFavoritedBy(Auth::id())) 
        {
            $this->model->favorites()->where('user_id', Auth::id())->delete();
        } 
        else 
        {
            $this->model->favorites()->create(['user_id' => Auth::id()]);
        }

        $this->dispatch('showToast', message: $this->model->isFavoritedBy(Auth::id()) ? 'Dodano do ulubionych!' : 'Usunięto z ulubionych!');
        $this->loadFavoriteState();
    }

    protected function loadFavoriteState(): void
    {
        if (Auth::check()) {
            $this->isFavorite = $this->model->isFavoritedBy(Auth::id());
        } else {
            $this->isFavorite = false;
        }
        $this->count = $this->model->favorites()->count();
    }

    public function render()
    {
        return view('livewire.favorite');
    }
}
