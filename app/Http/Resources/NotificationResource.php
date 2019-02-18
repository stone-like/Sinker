<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'id' => $this->id,//これをmarkAsReadで使う,notificationのid　01baf095-40a2-4c7d-8c23-704452fa80eaみたいなやつ
            'replyBy' => $this->data['replyBy'],
            'question' => $this->data['question'],
            'path' => $this->data['path'],
            'class' => "replied",
            'created_at' => $this->created_at
        ];
    }
}
