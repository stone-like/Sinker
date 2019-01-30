<?php

namespace App\Http\Controllers;

use App\Model\Like;
use App\Model\Reply;
use App\Events\LikeEvent;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('JWT');
    }
    public function likeIt(Reply $reply)
    { //特定のreply_idのlike(複数）を返してもらって、その特定idという条件下でcreate
     $reply->like()->create(
         ['user_id' => auth()->id()]
          );

          broadcast(new LikeEvent($reply->id,1))->toOthers();
    }

    public function unLikeIt(Reply $reply)
    { //そのreplyのlikeが既にログインしているユーザーによって押されていたらdelete
      $reply->like()->where('user_id',auth()->id())->first()->delete();

      broadcast(new LikeEvent($reply->id,0))->toOthers();
    }
}
