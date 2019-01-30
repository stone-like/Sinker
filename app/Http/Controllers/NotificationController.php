<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\NotificationResource;

class NotificationController extends Controller
{
    public function index(){
        return [
            "read" => NotificationResource::collection( auth()->user()->readNotifications()->get()),
            "unread" => NotificationResource::collection(auth()->user()->unreadNotifications()->get())//返ってくるのはnotificationtableのcollection...でいい？
        ];
    }

    public function markAsRead(Request $request){
        //auth()->user()でどのnotificationidか決めた？,でwhereではnotificationtable自体のidでどのリプライを見たか決めた？、だとすると二つ目は01baf095-40a2-4c7d-8c23-704452fa80eaみたいなのを指定していることになる

        return auth()->user()->notifications->where('id',$request->id)->markAsRead();
    }
}
