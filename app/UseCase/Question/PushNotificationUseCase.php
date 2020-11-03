<?php


namespace App\UseCase\Question;


use App\Notifications\DeleteQuestionNotification;
use App\Repository\QuestionRepositoryInterface;

class PushNotificationUseCase
{
    protected $questionRepo;

    /**
     * FindQuestionUseCase constructor.
     * @param $questionRepo
     */
    public function __construct(QuestionRepositoryInterface $questionRepo)
    {
        $this->questionRepo = $questionRepo;
    }

    public function execute(int $id)
    {
        $this->notify($id);
    }


    /**
     * @param $user
     * @param $reply
     */
    public function pushNotification($user, $reply): void
    {
        $user->notify(new DeleteQuestionNotification($reply));
    }


    public function notify($id)
    {
        $question = $this->questionRepo->findById($id);

        $userAndReplyList = $this->getUserList($question);

        foreach ($userAndReplyList as $single) {
            $this->pushNotification($single["user_id"], $single["reply"]);
        }
        return array_column($userAndReplyList, "user_id");
    }

    public function getUserList($question)
    {
        $user_array = [];

        foreach ($question->getReplies() as $reply) {
            $user_id = $reply->getUserId();
            //先にuserlistを作ってあげる
            if ($user_id != $question->getUserId() && !in_array($user_id, array_column($user_array, "user_id"))) {
                array_push($user_array, ["user_id" => $user_id, "reply" => $reply]);
            }
        }

        return $user_array;
    }


}
