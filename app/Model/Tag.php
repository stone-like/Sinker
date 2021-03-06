<?php

namespace App\Model;

use App\Model\Question;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function question(){
        return $this->belongsToMany(Question::class)->withTimestamps();
    }

    protected $fillable = ['name'];

    public function getRouteKeyName(){
        return 'name';
    }
}
