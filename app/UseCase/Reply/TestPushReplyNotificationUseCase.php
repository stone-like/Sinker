<?php


namespace App\UseCase\Reply;


use App\Repository\QuestionRepositoryInterface;
use App\Repository\UserRepositoryInterface;

class TestPushReplyNotificationUseCase extends PushReplyNotificationUseCase
{
    //test用
    public static $boolean;
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;
    /**
     * @var QuestionRepositoryInterface
     */
    private $questionRepository;


    public function __construct(UserRepositoryInterface $userRepository, QuestionRepositoryInterface $questionRepository)
    {
        parent::__construct($userRepository, $questionRepository);
    }

    public function execute(int $question_id, $reply)
    {
        $question = $this->questionRepository->findById($question_id);
        if($question->getUserId() == $reply->user_id){
            return self::$boolean = true;
        }

        return self::$boolean = false;
    }

}
