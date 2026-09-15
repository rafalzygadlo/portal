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
        if(session()->has('favorite_redirect')) {
            
            $data =session()->pull('favorite_redirect');
            $modelId = $data['model_id'] ?? null;
            $modelName = $data['model_name'] ?? null;
            $model = $modelName::Find($modelId);
            if ($model->isFavoritedBy(Auth::id())) 
            {
                $model->favorites()->where('user_id', Auth::id())->delete();
            } 
            else 
            {
                $model->favorites()->create(['user_id' => Auth::id()]);
            }

            $this->dispatch('showToast', message: $model->isFavoritedBy(Auth::id()) ? 'Dodano do ulubionych!' : 'Usunięto z ulubionych!');
            
            
        }
        $this->loadFavoriteState();
    }

    public function toggle()
    {
        if (!Auth::check()) 
        {
            //dd($this->model);
               session()->put('favorite_redirect', [
                'model_id' => $this->model->id,
                'model_name' => get_class($this->model),
                //'start_time' => $this->startTime,
            ]);
            
            

            return redirect()->guest(route('login'));
            
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
