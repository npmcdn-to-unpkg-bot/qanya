<?php

namespace App\Listeners;

use App\Events\FollowCateEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class FollowCateListeners
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
     * @param  FollowCateEvent  $event
     * @return void
     */
    public function handle(FollowCateEvent $event)
    {
        //
    }
}
