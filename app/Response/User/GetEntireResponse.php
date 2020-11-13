<?php


namespace App\Response\User;


class GetEntireResponse
{
    public static function transform($getEntireDTO)
    {
        return [
            "created_at" => $getEntireDTO->getCreatedAt(),
            "email" => $getEntireDTO->getEmail(),
            "total_posts" => $getEntireDTO->getTotalPosts(),
            "total_likes" => $getEntireDTO->getTotalLikes(),
            "total_replies" => $getEntireDTO->getTotalReplies()
        ];
    }
}
