<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\User; //ちゃんとnamespaceを指定しないとうまくいかないのでUser::とかする時は注意
use App\Model\Category;
use App\Model\Question;
use App\Model\Reply;

class Like extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $guarded = [];
}
