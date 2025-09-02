<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreateTicket
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $request;
    public $ticket;

    public function __construct($ticket,$request)
    {
        $this->request = $request;
        $this->ticket  = $ticket;
    }
}
