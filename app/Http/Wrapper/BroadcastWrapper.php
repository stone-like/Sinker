<?php


namespace App\Http\Wrapper;


use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BroadcastWrapper implements BroadcastWrapperInterface
{
    private $event;


    public function __construct(ShouldBroadcast $event)
    {
        $this->event = $event;
    }


    public function getEvent()
    {
         return $this->event;
    }
}
