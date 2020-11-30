<?php


namespace App\UseCase\Question;


use App\Repository\QuestionRepositoryInterface;

class AttachTagsToQuestionUseCase
{
   private $questionRepo;

    /**
     * AttachTagsToQuestionUseCase constructor.
     * @param $questionRepo
     */
    public function __construct(QuestionRepositoryInterface $questionRepo)
    {
        $this->questionRepo = $questionRepo;
    }

    public function execute($question_id,$tag_ids){
        $this->questionRepo->attachTags($question_id,$tag_ids);
    }


}
