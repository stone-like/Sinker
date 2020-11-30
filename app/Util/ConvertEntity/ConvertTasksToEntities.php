<?php


namespace App\Util\ConvertEntity;


use App\Entity\Task;

class ConvertTasksToEntities
{
    public static function convert($tasks)
    {
        return $tasks->map(function ($task) {
            return new Task($task->id, $task->bookmark_id, $task->question_id, $task->order);
        });

    }

}
