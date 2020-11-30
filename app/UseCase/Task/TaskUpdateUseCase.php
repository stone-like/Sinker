<?php

namespace App\UseCase\Task;

use App\Model\Task;
use App\Repository\TaskRepositoryInterface;
use Illuminate\Http\Request;

class TaskUpdateUseCase
{
    /**
     * @var TaskRepositoryInterface
     */
    protected $taskRepository;

    /**
     * TaskUpdateUseCase constructor.
     */
    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function execute($request, $id)
    {

        $this->updater($request);


        $status = $this->taskRepository->update($id,$request);
        //自分自身を周辺のupdateより上に持ってきちゃうと自分もその影響を受けてしまうので


        return response()->json([
            'status' => $status,
            "message" => $status ? 'Task Updated' : 'Error Updating'
        ]);
    }


    /**
     * @param $to_tasks
     */
    public function taskUpdateToOrderPlusOne($to_tasks): void
    {
        foreach ($to_tasks as $to_task) {
              $this->taskRepository->updateOrder($to_task->getId(),$to_task->getOrder()+1);
        }
    }

    /**
     * @param $from_tasks
     */
    public function taskUpdateToOrderMinusOne($from_tasks): void
    {
        foreach ($from_tasks as $from_task) {
            $this->taskRepository->updateOrder($from_task->getId(),$from_task->getOrder()-1);
        }
    }


}
