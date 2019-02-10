<?php

namespace App\Events;

use App\Events\DeleteQuestionEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteQuestionEventListener
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
     * @param  DeleteQuestionEvent  $event
     * @return void
     */
    public function handle(DeleteQuestionEvent $event)
    {
        //
    }
}
