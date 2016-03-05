<?php

namespace App\Events;

use App\Events\Event;
use App\Notification;
use App\Topic;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TopicUpvote extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $user;
    public $count;
    public $upv_cnt;
    public $topic;
    public $is_upvote;

    /**
     * Create a new event instance.
     *
     * @return void
     *
     * $flg -> increment or decrement
     */
    public function __construct($notify_user,$topics_uuid,$is_upvote)
    {

        $this->user = $notify_user;

        $notification = new Notification();
        $this->count        = $notification->countNotification($notify_user);

        $this->is_upvote    =   $is_upvote;

        //Get the most upvote count*/
        $tp = new Topic();
        $this->upv_cnt = $tp->upvoteTopic($topics_uuid,$is_upvote);
        $this->topic= $topics_uuid;

    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['topic_upv_'.$this->user,
                'upv_cnt_'.$this->topic];
    }
}
