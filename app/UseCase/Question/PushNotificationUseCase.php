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

    public function execute(int $id){
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


    public function notify($id){
        $question = $this->questionRepo->findById($id);
        $user_array = [];

        foreach ($question->getReplies() as $reply) {
            $user_id = $reply->getUserId();

            if ($user_id != $question->getUserId() && !in_array($user_id, $user_array)) {

                $this->pushNotification($user_id, $reply);
                array_push($user_array, $user_id);
            }
        }
        return $user_array;
    }


}
