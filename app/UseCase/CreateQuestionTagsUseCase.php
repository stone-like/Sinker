<?php


namespace App\UseCase;


use App\Model\Category;
use App\Model\Tag;

abstract class CreateQuestionTagsUseCase
{
    public function execute($request)
    {
        return $this->limitTagsArray($this->create($request));

    }



    /**
     * @param array $tags_id_array
     * @return array
     */
    public function limitTagsArray(array $tags_id_array): array
    {
//上から最大十個登録するようにする

        return count($tags_id_array) > 10 ? array_slice($tags_id_array, 0, 10) : $tags_id_array;
    }




}
