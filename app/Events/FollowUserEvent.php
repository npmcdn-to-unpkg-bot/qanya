<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class FollowUserEvent extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $follow_uuid;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user,$to_follow_uuid)
    {
        $this->follow_uuid = $to_follow_uuid;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['notification_'.$this->follow_uuid];
    }
}
