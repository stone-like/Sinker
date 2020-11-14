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
        return ConvertTasksToEntities::convert(Task::where("bookmark_id", $bookmark_id)->whereBetween("order", [$fromOrder, $toOrder])->get());
    }

    public function getByOrder($bookmark_id, $position)
    {
        return ConvertTasksToEntities::convert(Task::where("bookmark_id", $bookmark_id)->where("order", ">", $position)->get());
    }

    public function findByIdToModel(int $id)
    {
        try {
            return Task::where("id", $id)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            throw new TaskNotFoundException($e->getMessage());
        }
    }

    public function findById(int $id)
    {
        try {
            $task = Task::where("id", $id)->firstOrFail();
            return new \App\Entity\Task($task->id, $task->bookmark_id, $task->question_id, $task->order);
        } catch (ModelNotFoundException $e) {
            throw new TaskNotFoundException($e->getMessage());
        }
    }

    public function delete($id)
    {
        $task = $this->findByIdToModel($id);
        $task->delete();
    }

    public function update($id, $request)
    {
        $task = $this->findByIdToModel($id);
        return $task->update(
            ['bookmark_id' => $request->toTask_id, 'order' => $request->newPosition]);
    }

    public function updateOrder($id, $order)
    {
        $task = $this->findByIdToModel($id);
        $task->update([
            "order" => $order
        ]);
    }
}
