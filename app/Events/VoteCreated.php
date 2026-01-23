<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class VoteCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public User $voter,
        public Model $model,
        public int $value // 1 for up, -1 for down
    ) {}
}
