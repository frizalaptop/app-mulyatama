<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event ini dipicu ketika instance User merubah data sensitif (email atau password)
 * @see app\Listeners\LogUserSensitiveDataChange.php sebagai event yang didengar
 * @see app\Services\UserListService.php sebagai event yang dibuat
 */
class UserSensitiveDataChanged
{
    use Dispatchable, SerializesModels;

    public $user;
    public $changes;
    public $updatedBy;

    /**
     * Membuat instance event baru.
     * @param User $user instance user
     * @param array $change menampung kolom yang berubah
     * @param string|int $updatedBy menyimpan id pengubah data
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
