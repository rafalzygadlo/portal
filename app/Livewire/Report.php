<?php

namespace App\Livewire\Article;

use Livewire\Component;
use App\Models\Article\Article;
use Illuminate\Support\Facades\Auth;

class Report extends Component
{
    public Article $article;
    public $reason;
    public $showForm = false;
    public $reported = false;

    protected $rules = [
        'reason' => 'required|min:5|max:500',
    ];

    public function toggleForm()
    {
        if (!Auth::check()) 
        {
            return redirect()->route('login');
        }
        $this->showForm = !$this->showForm;
    }

    public function save()
    {
        if (!Auth::check()) 
        {
            return redirect()->route('login');
        }

        $this->validate();

        $this->article->reports()->create([
            'user_id' => Auth::id(),
            'reason' => $this->reason,
        ]);

        $this->reported = true;
        $this->showForm = false;
        $this->reason = '';
    }

    public function render()
    {
        return view('livewire.article.report');
    }
}