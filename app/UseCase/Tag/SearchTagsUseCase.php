<?php


namespace App\UseCase\Tag;


use App\Model\Question;
use App\Repository\QuestionRepositoryInterface;
use App\Repository\TagRepositoryInterface;

class SearchTagsUseCase
{
    /**
     * @var TagRepositoryInterface
     */
    private $tagRepository;
    /**
     * @var QuestionRepositoryInterface
     */
    private $questionRepository;


    /**
     * SearchTagsUseCase constructor.
     */
    public function __construct(TagRepositoryInterface $tagRepository,QuestionRepositoryInterface $questionRepository)
    {
        $this->tagRepository = $tagRepository;
        $this->questionRepository = $questionRepository;
    }

    public function execute($keyword){
        $tagsArray = $this->tagRepository->searchTags($keyword);
        return $this->questionRepository->searchQuestionByTags($tagsArray);

    }
}
