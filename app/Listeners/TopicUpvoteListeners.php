<?php

namespace App\Listeners;

use App\Events\TopicUpvote;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TopicUpvoteListeners
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

    /**
     * Handle the event.
     *
     * @param  TopicUpvote  $event
     * @return void
     */
    public function handle(TopicUpvote $event)
    {
        //
    }
}
