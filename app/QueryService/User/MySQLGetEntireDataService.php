<?php

namespace App\QueryService\User;

use App\DTO\UserEntireDataDTO;
use Illuminate\Support\Facades\DB;

class MySQLGetEntireDataService implements GetEntireDataServiceInterface
{
    public function getEntireData(int $id)
    {
        //ユーザーの指定があり、groupByを使う、実行順序はwhere→groupBy→havingで、groupByから得られる集計とかの情報を使って制限する場合Having
        //そうでなくユーザーid等を使う場合はwhereでいい
        $userData = DB::table("users")->leftJoin("likes", "likes.user_id", "=", "users.id")
            ->leftJoin("questions", "questions.user_id", "=", "users.id")
            ->leftJoin("replies", "replies.user_id", "=", "users.id")
            ->where("users.id", "=", $id)
            ->select("users.created_at as created_at",
                "users.email as email",
                DB::raw("count(likes.id) as total_likes"),
                DB::raw("count(questions.id) as total_posts"),
                DB::raw("count(replies.id) as total_replies")
            )
            ->groupBy("users.id")
            ->first();

        return new UserEntireDataDTO(
            $userData->created_at,
            $userData->email,
            $userData->total_posts,
            $userData->total_likes,
            $userData->total_replies
        );

    }

}
