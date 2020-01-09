<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User; //ちゃんとnamespaceを指定しないとうまくいかないのでUser::とかする時は注意
use App\Model\Category;
use App\Model\Question;
use App\Model\Like;

class Reply extends Model
{
    // public static function boot(){
    // //    parent::boot();
    // //    //ここでuser_idの登録を行ってしまう
    // //    static::creating(function($reply){
    // //        $reply->user_id = auth()->id();
    // //    });
    // }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function like()
    {
        return $this->hasMany(Like::class);
    }


    protected $guarded = [];
}
