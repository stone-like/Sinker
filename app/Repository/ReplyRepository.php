<?php


namespace App\Repository;


use App\Model\Reply;

class ReplyRepository implements ReplyRepositoryInterface
{
    public function create($id,$request)
    {
        $reply =  Reply::create([
            "body" => $request->body,
            "user_id" => $request->user_id,
            "question_id" => $id
        ]);

        return new \App\Entity\Reply(
            $reply->id,
            $reply->body,
            $reply->question_id,
            $reply->user_id
        );
    }
}
