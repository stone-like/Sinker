<?php


namespace App\UseCase\Reply;


use App\Repository\QuestionRepositoryInterface;
use App\Repository\UserRepositoryInterface;

class TestPushReplyNotificationUseCase extends PushReplyNotificationUseCase
{
    //test用
    public static $isNotify = true;


    public function __construct(UserRepositoryInterface $userRepository, QuestionRepositoryInterface $questionRepository)
    {
        parent::__construct($userRepository, $questionRepository);
    }

    public function execute(int $question_id, $reply)
    {


        $question = $this->questionRepository->findById($question_id);


        if ($question->getUserId() === $reply->getUserId()) {
            return self::$isNotify = false;
        }

        return self::$isNotify = true;
    }

    public static function teardown()
    {
        self::$isNotify = true;
    }

}
