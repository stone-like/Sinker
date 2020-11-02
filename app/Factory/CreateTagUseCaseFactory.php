<?php


namespace App\Factory;


use App\UseCase\CreateCategoryUseCase;
use App\UseCase\CreateTagsUseCase;

class CreateTagUseCaseFactory
{

    //ここでDIコンテナを使いたいんだけどつかってもいいのかどうか・・・
    public static function create($request)
    {
        if($request->filled("tags_string")){
            return app()->make(CreateTagsUseCase::class);

        }

        return app()->make(CreateCategoryUseCase::class);
    }
}
