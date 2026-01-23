<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use Illuminate\Validation\ValidationException;
use App\Events\CommentCreated;

class Comments extends Component
{
    public $model;
    public $content;
    public $replyToId = null;
    public $honey_pot;

    protected $rules = [
        'content' => 'required|min:3|max:500',
    ];

    private function containsForbiddenWords($text) {
        $forbiddenWords = ['kurwa', 'cholera', 'chuj', 'pierdol'];
        foreach ($forbiddenWords as $word) {
            if (stripos($text, $word) !== false) {
                return true;
            }
        }
        return false;
    }

    public function mount()
    {
        // Model is already available as $this->model from Livewire property binding
    }

    public function postComment()
    {
        if(!empty($this->honey_pot)) {
            return null;
        }

        $this->validate();

        if ($this->containsForbiddenWords($this->content)) {
            throw ValidationException::withMessages([
                'content' => 'Komentarz zawiera niedozwolone słowa.',
            ]);
        }

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $comment = $this->model->comments()->create([
            'user_id' => Auth::id(),
            'parent_id' => $this->replyToId,
            'content' => $this->content,
        ]);

        CommentCreated::dispatch(Auth::user(), $this->model, $comment->content);

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

        return view('livewire.comments', [
            'comments' => $comments,
            'replyingTo' => $this->replyToId ? Comment::find($this->replyToId) : null,
        ]);
    }
}