<?php

namespace App\Events;

use App\Events\EditTagEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EditTagEventListener
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
     * @param  EditTagEvent  $event
     * @return void
     */
    public function handle(EditTagEvent $event)
    {
        //
    }
}
