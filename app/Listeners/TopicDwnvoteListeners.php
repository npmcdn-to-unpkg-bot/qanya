<?php

namespace App\Listeners;

use App\Events\TopicDwnvote;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TopicDwnvoteListeners
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
     * @param  TopicDwnvote  $event
     * @return void
     */
    public function handle(TopicDwnvote $event)
    {
        //
    }
}
