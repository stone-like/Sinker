<?php


namespace App\Repository;


use App\Exceptions\UserNotFoundException;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Notifications\Notification;

class UserRepository implements UserRepositoryInterface
{
  public function notify(int $user_id,Notification $notification){
       $user = $this->findByIdToModel($user_id);
       $user->notify($notification);
  }

  public function findByIdToModel(int $id){
      try {
          $category = User::where("id", $id)->firstOrFail();
          return new \App\Entity\Category($category->id, $category->name,$category->slug);
      } catch (ModelNotFoundException $e) {
          throw new UserNotFoundException($e->getMessage());
      }
  }
}
