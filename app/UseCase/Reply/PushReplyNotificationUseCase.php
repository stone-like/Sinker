<?php


namespace App\UseCase\Reply;


use App\Notifications\NewReplyNotification;
use App\Repository\QuestionRepositoryInterface;
use App\Repository\UserRepositoryInterface;

class PushReplyNotificationUseCase
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;
    /**
     * @var QuestionRepositoryInterface
     */
    private $questionRepository;


    /**
     * PushReplyNotificationUseCase constructor.
     */
    public function __construct(UserRepositoryInterface $userRepository,QuestionRepositoryInterface $questionRepository)
    {
        $this->userRepository = $userRepository;
        $this->questionRepository = $questionRepository;
    }

    public function execute(int $question_id,$reply){
           //ここのreplyもEntityに変更すること
           $question = $this->questionRepository->findById($question_id);
           if($question->getUserId() == $reply->user_id){
               return;
           }
           //ここのNotificationは多分laravelに依存しないようにWrapperにした方がいい
           $this->userRepository->notify($question->getUserId(),new NewReplyNotification($reply));
    }
}
