<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateTicketStatus
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $request;
    public $ticket;

    public function __construct($ticket,$request)
    {
        $this->ticket  = $ticket;
        $this->request = $request;
    }
}
