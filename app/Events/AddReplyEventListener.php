<?php

namespace App\Events;

use App\Events\AddReplyEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddReplyEventListener
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
     * @param  AddReplyEvent  $event
     * @return void
     */
    public function handle(AddReplyEvent $event)
    {
        //
    }
}
