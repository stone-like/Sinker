<?php


namespace App\UseCase\User;


use App\Model\Like;
use App\Model\Question;
use App\Model\Reply;

use App\QueryService\User\GetEntireDataServiceInterface;
use App\User;


class GetEntireDataUseCase
{

    private $getEntireDataService;

    /**
     * GetEntireDataUseCase constructor.
     */
    public function __construct(GetEntireDataServiceInterface $getEntireDataService)
    {
        $this->getEntireDataService = $getEntireDataService;
    }

    public function execute(int $id)
    {

        return $this->getEntireDataService->getEntireData($id);
    }

}
