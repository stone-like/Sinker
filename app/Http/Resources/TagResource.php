<?php

namespace App\Http\Resources;

use App\Http\Resources\ReplyResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
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
            'title' => $this->title,
            'question_id' => $this->id,
            'path' => $this->path,
            'replies' => ReplyResource::collection($this->replies),
            'reply_count' => $this->replies->count(),
            'slug' => $this->slug,
           'body' => $this->body,
           'created_at' => $this->created_at->diffForHumans(),
           'user' => $this->user->name,
           'user_id' => $this->user_id,
           'category_id' => $this->category_id

        ];
    }
}
