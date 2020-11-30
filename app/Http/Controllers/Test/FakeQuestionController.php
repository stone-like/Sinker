<?php

namespace App\Http\Controllers\Test;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class FakeQuestionController extends \App\Http\Controllers\QuestionController
{
    public function __construct()
    {
       parent::__construct();
    }

    public function broadcast(ShouldBroadcast $event)
    {
        var_dump("aaaaaa");
        return;
    }

}
