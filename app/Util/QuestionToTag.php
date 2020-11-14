<?php

namespace App\Util;

use App\Factory\CreateTagUseCaseFactory;
use Illuminate\Http\Request;

trait QuestionToTag
{
    public function createTagsArray(Request $request): array
    {
        $usecase = CreateTagUseCaseFactory::create($request);
        return $usecase->execute($request);

    }

    public function syncTagsToQuestion($question_id, array $tags_id_array): void
    {
        $this->attachTagsToQuestionUseCase->execute($question_id, $tags_id_array);
    }

}
