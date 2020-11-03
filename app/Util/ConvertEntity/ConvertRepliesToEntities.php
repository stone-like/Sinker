<?php

namespace App\Util\ConvertEntity;

use App\Entity\Reply;

class ConvertRepliesToEntities
{
   public static function convert($replies){
      return $replies->map(function($reply){
          return new Reply($reply->id,$reply->body,$reply->question_id,$reply->user_id);
      });

   }
}
