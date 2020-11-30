<?php


namespace App\QueryService\User;


use App\Model\Question;
use App\Model\Reply;
use App\User;
use Illuminate\Support\Facades\DB;

class MySQLGetRecentService implements GetRecentServiceInterface
{
    public function getRecentService(int $id)
    {

        //これは正直Repositoryでよかった、joinをする案件でもないし、limitしているので速度的にも大丈夫なはず
        $recent_posts = DB::table("questions")
            ->select(
                "*"
            )
            ->where("user_id", "=", $id)
            ->limit(5)
            ->get();
        $recent_replies = DB::table("replies")
            ->select(
                "*"
            )
            ->where("user_id", "=", $id)
            ->limit(5)
            ->get();

        return [
            "recent_posts" => $recent_posts,
            "recent_replies" => $recent_replies
        ];

    }
}
