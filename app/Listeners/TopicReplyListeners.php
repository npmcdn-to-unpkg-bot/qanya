<?php

namespace App\Listeners;

use App\Events\TopicReply;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

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
        User::incrementPost(Auth::user()->uuid);
    }

    public function subscribe($events)
    {
        $events->listen(
            'App\Events\TopicReply',
            'App\Listeners\TopicReplyListeners@notifyPoster'
        );

    }
}
