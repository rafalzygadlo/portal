<?php

namespace App\Traits;

use App\Models\Vote;

trait Voteable
{
    public function votes()
    {
        return $this->morphMany(Vote::class, 'voteable');
    }

    public function upvotes()
    {
        return $this->morphMany(Vote::class, 'voteable')->where('value', 1);
    }

    public function downvotes()
    {
        return $this->morphMany(Vote::class, 'voteable')->where('value', -1);
    }

    public function getScore()
    {
        // Suma wartości głosów (1 dla up, -1 dla down) daje wynik
        return (int) $this->votes()->sum('value');
    }
}