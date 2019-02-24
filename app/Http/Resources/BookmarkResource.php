<?php

namespace App\Http\Resources;

use App\Http\Resources\QuestionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BookmarkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {   $task_detail_array = [];
        $tasks_array = $this->task()->where('bookmark_id',$this->id)->get();
        // dump($tasks_array);
        foreach( $tasks_array as $task){
           array_push($task_detail_array,["id" => $task->id,"order" => $task->order,"bookmark_id" => $task->bookmark_id,"path" => $task->question->path,"title" => $task->question->title]);
        }
        //order順にsort
        // dump($task_detail_array);

        array_multisort(array_column($task_detail_array,'order'),SORT_ASC,$task_detail_array);

        // dump($task_detail_array);
        return [
            "id" => $this->id,
            "name" => $this->name,
            "tasks" => $task_detail_array
        ];
    }
}
