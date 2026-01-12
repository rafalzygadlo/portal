<?php

namespace App\Livewire\Article;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;

class Comments extends Component
{
    public $model;
    public $content;
    public $replyToId = null;

    protected $rules = [
        'content' => 'required|min:3|max:500',
    ];

    public function mount(Model $model)
    {
        $this->model = $model;
    }

    public function postComment()
    {
        $this->validate();

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->model->comments()->create([
            'user_id' => Auth::id(),
            'parent_id' => $this->replyToId,
            'content' => $this->content,
        ]);

        $this->content = '';
        $this->replyToId = null;
    }

    public function delete($commentId)
    {
        $comment = Comment::find($commentId);
        if ($comment && $comment->user_id === Auth::id()) {
            $comment->delete();
        }
    }

    public function render()
    {
        $comments = $this->model->comments()
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->latest()
            ->get();

        return view('livewire.article.comments', [
            'comments' => $comments,
            'replyingTo' => $this->replyToId ? Comment::find($this->replyToId) : null,
        ]);
    }
}