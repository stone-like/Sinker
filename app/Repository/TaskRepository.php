<?php


namespace App\Repository;


use App\Exceptions\TaskNotFoundException;
use App\Model\Task;
use App\Util\ConvertEntity\ConvertTasksToEntities;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TaskRepository implements TaskRepositoryInterface
{
    public function getBetWeenOrder($bookmark_id, $fromOrder, $toOrder)
    {
        //-1とか+1は事前に済ませておくこと
        return ConvertTasksToEntities::convert(Task::where("bookmark_id", $bookmark_id)->whereBetween("order",[$fromOrder, $toOrder])->get());
    }

    public function getByOrder($bookmark_id, $position)
    {
        return ConvertTasksToEntities::convert(Task::where("bookmark_id", $bookmark_id)->where("order", ">", $position)->get());
    }

    public function findById(int $id)
    {
        try {
            return Task::where("id", $id)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new TaskNotFoundException($e->getMessage());
        }
    }

    public function update($id, $request)
    {
        $task = $this->findById($id);
        return $task->update(
            ['bookmark_id' => $request->toTask_id, 'order' => $request->newPosition]);
    }

    public function updateOrder($id, $order)
    {
       $task = $this->findById($id);
       $task->update([
           "order"=>$order
       ]);
    }
}
