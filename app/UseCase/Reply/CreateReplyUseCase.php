<?php

namespace App\UseCase\Reply;

use App\Model\Reply;

class CreateReplyUseCase
{
    public function execute(int $id,$request){
        return Reply::create([
            "body" => $request->body,
            "user_id" => $request->user_id,
            "question_id" => $id
        ]);
    }

}
