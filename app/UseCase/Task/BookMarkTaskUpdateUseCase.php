<?php


namespace App\UseCase\Task;


use App\Model\Task;
use Illuminate\Http\Request;

class BookMarkTaskUpdateUseCase extends TaskUpdateUseCase
{
    public function updater($request): void
    {
        $this->fromBookmarkTaskChange($request);
        $this->toBookmarkTaskChange($request);
    }

    /**
     * @param Request $request
     */
    public function fromBookmarkTaskChange(Request $request): void
    {
//両方いじる
        //まずはfrom側
        //移動したやつのもともとのpositionはoldpositionで、そこより数字が上のorderを-1
        $from_tasks = $this->taskRepository->getByOrder($request->fromTask_id,$request->oldPosition);
        //oldPosition側は変更前の値を使ってok

        $this->taskUpdateToOrderMinusOne($from_tasks);
    }

    /**
     * @param Request $request
     */
    public function toBookmarkTaskChange(Request $request): void
    {
//to側
        //ここのorderはposition変更前なため不等号にするかnewpositionを-1するかどちらか
        $to_tasks = $this->taskRepository->getByOrder($request->toTask_id,$request->newPosition - 1);
        $this->taskUpdateToOrderPlusOne($to_tasks);
    }
}
