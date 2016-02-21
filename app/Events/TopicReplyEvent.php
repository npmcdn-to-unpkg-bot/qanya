<?php

namespace App\Events;


use App\Events\Event;
use App\TopicReply;
use App\Http\Controllers\TopicController;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;


class TopicReplyEvent extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $data;
    public $topic;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($topic_uuid, TopicReply $data)
    {
        $this->topic = $topic_uuid;
        $this->data = $data;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['reply_append_'.$this->topic];
    }
}
