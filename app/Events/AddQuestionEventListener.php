<?php

namespace App\Events;

use App\Events\AddQuestionEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddQuestionEventListener
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
     * @param  AddQuestionEvent  $event
     * @return void
     */
    public function handle(AddQuestionEvent $event)
    {
        //
    }
}
