<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;//ここをしっかりtrueにしておかないとthis　action　is　unauthorization　
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = Auth::user();
        dump($user->name,$user->email);
         //今ログインしている自分の変更前の情報だけは重複許す
        return [

            'name' => ['required',
                      'between:2,25',
                       Rule::unique('users')->ignore($user->name,'name')],
            'email' => ['required',
                         'email',
                         'max:50',
                         Rule::unique('users')->ignore($user->email,'email')],
            'password' => 'required|confirmed|alpha_num|between:6,30'

        ];
    }
}
