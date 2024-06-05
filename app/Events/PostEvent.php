<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class PostEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels,Queueable;

    public $post;
    /**
     * Create a new event instance.
     */
    public function __construct($post)
    {
        //
        
        $this->post = $post;
    }

   
}
