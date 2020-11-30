<?php


namespace App\UseCase\Question;


use App\Repository\QuestionRepositoryInterface;

class CreateQuestionUseCase
{
    private $questionRepo;

    /**
     * CreateQuestionUseCase constructor.
     * @param $questionRepo
     */
    public function __construct(QuestionRepositoryInterface $questionRepo)
    {
        $this->questionRepo = $questionRepo;
    }


    public function execute($request){
        return $this->questionRepo->create($request->except("tags_string"));
//      return auth()->user()->question()->create($request->except("tags_string"));
  }
}
