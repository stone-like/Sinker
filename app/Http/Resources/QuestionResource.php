<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'title' =>$this->title,
            'path' => $this->path,
            'replies' => ReplyResource::collection($this->replies),//user_idを使ってusertableに飛んだように、今の場合は特定の質問からreplyをとってきてるからeagerloadingいらないような...
            'reply_count' => $this->replies->count(),
            'slug' => $this->slug,
            'body' => $this->body,
            'created_at' => $this->created_at->diffForHumans(),
            'user' => $this->user->name,//質問をした人の名前
            'user_id' => $this->user_id//質問をした人のid
        ];
    }
}
