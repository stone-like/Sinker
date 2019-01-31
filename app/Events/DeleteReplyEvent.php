<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DeleteReplyEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $id;//どこのreplyを削除するかに使う
    public $question_id;//ShowQuestionでリアルタイムに値を増減するときにすべてのquestionで変更が起きては困るのでquestionの特定のため

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($id,$question_id)
    {
        $this->id = $id;
        $this->question_id = $question_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('deleteReplyChannel');
    }
}
