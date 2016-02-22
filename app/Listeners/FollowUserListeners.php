<?php

namespace App\Listeners;

use App\Events\FollowUserEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class FollowUserListeners
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
     * @param  FollowUserEvent  $event
     * @return void
     */
    public function handle(FollowUserEvent $event)
    {
        //
    }
}
