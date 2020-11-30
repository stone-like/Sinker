<?php

namespace App\UseCase\Reply;

use App\Model\Reply;
use App\Repository\ReplyRepositoryInterface;

class CreateReplyUseCase
{
    /**
     * @var ReplyRepositoryInterface
     */
    private $replyRepository;

    /**
     * CreateReplyUseCase constructor.
     */
    public function __construct(ReplyRepositoryInterface $replyRepository)
    {
        $this->replyRepository = $replyRepository;
    }

    public function execute(int $id, $request){
        return $this->replyRepository->create($id,$request);
    }

}
