<?php


namespace App\Repository;


use Illuminate\Notifications\Notification;

interface UserRepositoryInterface
{
   public function notify(int $user_id,Notification $notification);
}
