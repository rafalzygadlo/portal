<?php

namespace App\Livewire;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\On;

class Favorite extends AuthComponent
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
        if (!$this->checkAuth([
                'action' => 'toggle_favorite',
                'model_class' => get_class($this->model),
                'model_id' => $this->model->id,
            ])) {
            return;
        }

        if ($this->model->isFavoritedBy(Auth::id())) {
            $this->model->favorites()->where('user_id', Auth::id())->delete();
        } else {
            $this->model->favorites()->create(['user_id' => Auth::id()]);
        }

        $this->loadFavoriteState();
    }

    #[On('executeFavoriteToggle')]
    public function executeToggle($modelClass, $modelId)
    {
        if (get_class($this->model) === $modelClass && $this->model->id === $modelId) {
            if (!$this->model->isFavoritedBy(Auth::id())) {
                $this->model->favorites()->create(['user_id' => Auth::id()]);
            }
            $this->loadFavoriteState();
            $this->dispatch('toast', message: 'Dodano do ulubionych!');
        }
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
