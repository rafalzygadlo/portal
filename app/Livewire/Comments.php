<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use Illuminate\Validation\ValidationException;
use App\Events\CommentCreated;

use App\Rules\Profanity;

class Comments extends Component
{
    public $model;
    public $content;
    public $replyToId = null;
    public $honey_pot;

    protected function rules()
    {
        return [
            'content' => ['required', 'min:3', 'max:500', /*new Profanity */],
        ];
    }

    public function postComment()
    {
        if(!empty($this->honey_pot)) {
            return null;
        }

        $this->validate();

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $comment = $this->model->comments()->create([
            'user_id' => Auth::id(),
            'parent_id' => $this->replyToId,
            'content' => $this->content,
        ]);

        
        $this->content = '';
        $this->replyToId = null;
    }

    public function delete($commentId)
    {
        $comment = Comment::findOrFail($commentId);
        $this->authorize('delete', $comment);
        $comment->delete('Usunięte przez autora lub moderatora');
    }

    public function render()
    {
        $comments = $this->model->comments()->withTrashed()
            ->whereNull('parent_id')
            ->with(['user', 'replies' => function ($query) {
                $query->withTrashed()->with('user');
            }])
            ->latest()
            ->get();

        return view('livewire.comments', [
            'comments' => $comments,
            'replyingTo' => $this->replyToId ? Comment::find($this->replyToId) : null,
        ]);
    }
}