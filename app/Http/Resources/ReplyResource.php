<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReplyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $image_path =  asset("/images/".basename($this->user->img_path));
        return [
            'id' => $this->id,
            'reply'=>$this->body,
            'question_slug' => $this->question->slug,
            'question_id' => $this->question->id,
            'like_count' => $this->like->count(),
            'liked' => !! $this->like()->where('user_id',auth()->id())->count(),
            'user' =>$this->user->name,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at->diffForHumans(),
            'user_img' => $image_path
        ];
    }
}
