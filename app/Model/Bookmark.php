<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Task;
use App\User;

class Bookmark extends Model
{

    protected $fillable = ['name'];
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function task(){
        return $this->hasMany(Task::class);
    }
}
