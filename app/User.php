<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
//ちゃんとnamespaceを指定しないとうまくいかないのでUser::とかする時は注意
use App\Model\Bookmark;
use App\Model\Category;
use App\Model\Question;
use App\Model\Reply;
use App\Model\Like;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected static function boot()
    {

        parent::boot();

        static::creating(function ($user) {
            $user->img_path = public_path('images') . "/sks.jpeg";
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'img_path'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function question()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function setPasswordAttribute($value)
    {
        //自動的にUsertableのpasswordをエンコード
        $this->attributes['password'] = bcrypt($value);
    }

    public function bookmark()
    {
        return $this->hasMany(Bookmark::class);
    }
}
