<?php

namespace App\Events;


use App\Events\Event;
use App\Notification;
use App\TopicReply;
use App\Http\Controllers\TopicController;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;


class TopicReplyEvent extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $data;
    public $topic;
    public $count;
    public $author;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($topic_uuid, $topics_uid, TopicReply $data)
    {
        $notification   = new Notification();
        $this->count    = $notification->countNotification($topics_uid);

        $this->author   = $topics_uid;
        $this->topic    = $topic_uuid;
        $this->data     = $data;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['reply_append_'.$this->topic,
               'reply_to_'.$this->author];
    }
}