<?php


namespace App\UseCase\Question;


use App\Repository\QuestionRepositoryInterface;

class TestPushNotificationUseCase extends PushNotificationUseCase
{
    //test用
    public static $notificationArray = [];

    /**
     * TestPushNotificationUseCase constructor.
     */
    public function __construct(QuestionRepositoryInterface $questionRepo)
    {

        parent::__construct($questionRepo);
    }

    public function execute(int $id){

       $userArray = $this->notify($id);
        //test用
        $this->pushToNotificationArray($userArray);
    }



    public function pushToNotificationArray($userArray){

        foreach ($userArray as $user_id){
            array_push(self::$notificationArray,$user_id);
        }
    }

    public function pushNotification($user_id, $reply): void
    {
        return;
    }

    public static function teardown(){
        self::$notificationArray = [];
    }

}
