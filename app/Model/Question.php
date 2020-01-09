<?php

namespace App\Model;

use App\Model\Tag;
use App\Model\Like;
use App\Model\Reply;
use App\Model\Category;

use Illuminate\Database\Eloquent\Model;
use App\User; //ちゃんとnamespaceを指定しないとうまくいかないのでUser::とかする時は注意

class Question extends Model
{
    protected static function boot()
    {

        parent::boot(); //元のbootもしっかり起動

        static::creating(function ($question) {
            mb_regex_encoding("UTF-8");
            if (preg_match("/^[ぁ-んァ-ヶー一-龠]+$/u", $question->title)) {

                $question->slug = $question->title;
            } else {
                $question->slug = str_slug($question->title);
            }
        }); //boot時にslugを制作
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class)->latest(); //hasManyなのでmethod名をrepliesとしている,戻り値はreplytableオブジェクトの配列、つまりcollectionでいい？
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tag()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    //questionからはtaskを参照しないのでこっちには書かなくていい、書くのはtask側

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // protected $guarded =[];
    //更新したい列を指定↓
    protected $fillable = ['title', 'slug', 'body', 'category_id'];

    protected $with = ['replies'];
    public function getpathAttribute()
    {
        return "/question/$this->slug";
    }
}
