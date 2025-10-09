<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserSensitiveDataChanged
{
    use Dispatchable, SerializesModels;

    public $user;
    public $changes;
    public $updatedBy;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, array $changes, $updatedBy)
    {
        $this->user = $user;
        $this->changes = $changes;
        $this->updatedBy = $updatedBy;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
