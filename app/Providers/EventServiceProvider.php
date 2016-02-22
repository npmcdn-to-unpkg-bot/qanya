<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\TopicReplyEvent' => [
            'App\Listeners\TopicReplyListeners',
        ],
        'App\Events\TopicPostEvent' => [
            'App\Listeners\TopicPostListeners',
        ],
        'App\Events\FollowCateEvent' => [
            'App\Listeners\FollowCateListeners',
        ],
        'App\Events\FollowUserEvent' => [
            'App\Listeners\FollowUserListeners',
        ],
    ];

    protected $subscribe = [
        'App\Listeners\TopicReplyListeners',
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        $events->listen('*', function ($message) {
            //
        });
    }
}
