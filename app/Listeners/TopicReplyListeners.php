<?php

namespace App\Listeners;

use App\Events\TopicReply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TopicReplyListeners
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function notifyPoster($event)
    {
        return "notify poster";
    }

    /**
     * Handle the event.
     *
     * @param  TopicReply  $event
     * @return void
     */
    public function handle(TopicReply $event)
    {
    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\TopicReply',
            'App\Listeners\TopicReplyListeners@notifyPoster'
        );

    }
}
