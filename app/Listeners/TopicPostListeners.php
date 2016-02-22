<?php

namespace App\Listeners;

use Auth;
use App\Events\TopicPost;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TopicPostListeners
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

    public function handle($event)
    {
        $user = new User();
        $user->incrementPost(Auth::user()->uuid);
    }
}
