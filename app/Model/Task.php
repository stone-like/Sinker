<?php

namespace App\Model;
use App\Model\Bookmark;

use App\Model\Question;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['bookmark_id','question_id','order'];
    public function bookmark(){
        return $this->belongsTo(Bookmark::class);
    }

    public function question(){
        return $this->belongsTo(Question::class);
    }

    // public function getRouteKeyName(){
    //     return 'name';
    // }
}
