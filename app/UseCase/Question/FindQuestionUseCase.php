<?php


namespace App\UseCase\Question;


use App\Repository\QuestionRepositoryInterface;

class FindQuestionUseCase
{
    private $questionRepo;

    /**
     * FindQuestionUseCase constructor.
     * @param $questionRepo
     */
    public function __construct(QuestionRepositoryInterface $questionRepo)
    {
        $this->questionRepo = $questionRepo;
    }

    public function execute(int $id){
        return $this->questionRepo->findById($id);
    }


}
