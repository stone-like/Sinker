<?php


namespace App\UseCase\Task;


use App\Model\Task;

class InTaskUpdateUseCase extends TaskUpdateUseCase
{
    public function updater($request): void
    {
        if ($request->oldPosition > $request->newPosition) {

            //[2,4)をとってきたい,なのでoldPosition-1としている(weherebetweenは閉区間なので)
//            $fromTaskList = Task::where("bookmark_id", $request->fromTask_id)->whereBetWeen("order", [$request->newPosition, $request->oldPosition - 1])->get();
            $fromTaskList = $this->taskRepository->getBetWeenOrder($request->fromTask_id,$request->newPosition,$request->oldPosition - 1);
            $this->taskUpdateToOrderPlusOne($fromTaskList);
            //例えば1,2,3,4,5とあったとして4→2とするとしたら、2と3がそれぞれ3、4になってほしい
            //なのでorderがnewPosition~oldPositionのやつをwhereBetweenでとってくればいい


        } else {

//            $fromTaskList = Task::where("bookmark_id", $request->fromTask_id)->whereBetWeen("order", [$request->oldPosition + 1, $request->newPosition])->get();
            $fromTaskList = $this->taskRepository->getBetWeenOrder($request->fromTask_id,$request->oldPosition + 1,$request->newPosition);

            $this->taskUpdateToOrderMinusOne($fromTaskList);
            //例えば1,2,3,4,5とあったとして2->4とするとしたら、3,4がそれぞれ2,3になってほしい
            //betweenでは3,4がほしい
            //(2,4]がほしいのでoldPositionの２を+1とする
        }
    }
}
