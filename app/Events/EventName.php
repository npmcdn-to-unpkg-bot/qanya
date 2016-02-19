<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EventName extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $data;


    public function __construct($num)
    {
        $this->data = array(
            'power' => '10',
            'num'   => 10
        );
    }

    public function broadcastOn()
    {
        return ['test-channel.10'];
    }
}