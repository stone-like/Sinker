<?php


namespace App\UseCase\User;


use App\Model\Question;
use App\Model\Reply;
use App\QueryService\User\GetRecentServiceInterface;
use App\User;

class GetRecentUseCase
{
    /**
     * @var GetRecentServiceInterface
     */
    private $getRecentService;


    /**
     * GetRecentUseCase constructor.
     */
    public function __construct(GetRecentServiceInterface $getRecentService)
    {
        $this->getRecentService = $getRecentService;
    }

    public function execute(int $id)
    {
        return $this->getRecentService->getRecentService($id);

    }

}
