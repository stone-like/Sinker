<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "question_id" => ["required","exists:questions,id"]
        ];
    }

    public function validationData()
    {


        return array_merge($this->request->all(), [
            "question_id" => (int)$this->id
        ]);
    }
}
