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
        // Check if vote sum has already been loaded (eager loaded)
        if (isset($this->votes_sum_value)) {
            return (int) $this->votes_sum_value;
        }
        // The sum of vote values (1 for up, -1 for down) yields the score
        return (int) $this->votes()->sum('value');
    }
}