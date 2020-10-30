<?php


namespace App\Http\Wrapper;


class FakeBroadcastWrapper implements BroadcastWrapperInterface
{

    public function getEvent()
    {
        var_dump("fakeBroadCast");
        return null;
    }
}
