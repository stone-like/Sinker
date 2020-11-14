<?php


namespace App\Factory;


use App\Model\Task;
use App\UseCase\Task\BookMarkTaskUpdateUseCase;
use App\UseCase\Task\InTaskUpdateUseCase;

class UpdateTaskUseCaseFactory implements UpdateTaskUseCaseFactoryInterface
{
    public static function create($request)
    {
        if ($request->toTask_id !== $request->fromTask_id) {
           return app()->make(BookMarkTaskUpdateUseCase::class);

        }

        return app()->make(InTaskUpdateUseCase::class);
    }
}
