<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Favorite extends Component
{
    public $model;
    public $isFavorite = false;
    public $count = 0;

    public function mount()
    {
        $this->loadFavoriteState();
    }

    public function toggle()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if ($this->model->isFavoritedBy(Auth::id())) {
            $this->model->favorites()->where('user_id', Auth::id())->delete();
        } else {
            $this->model->favorites()->create(['user_id' => Auth::id()]);
        }

        $this->loadFavoriteState();
    }

    protected function loadFavoriteState(): void
    {
        $this->isFavorite = $this->model->isFavoritedBy(Auth::id());
        $this->count = $this->model->favorites()->count();
    }

    public function render()
    {
        return view('livewire.favorite');
    }
}
