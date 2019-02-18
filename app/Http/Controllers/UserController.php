<?php

namespace App\Http\Controllers;

use App\User;
use App\Model\Like;
use App\Model\Reply;
use App\Model\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;//これをしっかり入れる

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('JWT', ['except' => ['index','show']]);
    }

    public function getEntireData(){
         $user = Auth::user();
         $user_created_at = $user->created_at;
         $user_email = $user->email;
         $total_likes = Like::where('user_id',$user->id)->count();
         $total_posts = Question::where('user_id',$user->id)->count();
         $total_replies = Reply::where('user_id',$user->id)->count();

         return [
             "created_at" => $user_created_at,
             "email" => $user_email,
             "total_likes" => $total_likes,
             "total_posts" => $total_posts,
             "total_replies" => $total_replies
         ];
    }

    public function getRecent(){
        $user = Auth::user();
        $recent_posts = Question::where('user_id',$user->id)->LIMIT(5)->get();
        $recent_replies = Reply::where('user_id',$user->id)->LIMIT(5)->get();

        return [
          "recent_posts" => $recent_posts,
          "recent_replies" => $recent_replies
        ];
    }
}
