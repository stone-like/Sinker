<?php


namespace App\UseCase\Task;


use App\Model\Task;

use App\Repository\TaskRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class DeleteTaskUseCase
{

    protected $taskRepository;

    /**
     * DeleteTaskUseCase constructor.
     */
    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function execute($id)
    {
        //データベース上で削除するときにそれが属していたbookmarkの他のtaskのorderもしっかりいじる
        //削除するタスクよりorderが小さいほうはいじらないでよくて、大きいほうは-1していけばよい
        $taskEntity = $this->taskRepository->findById($id);
        $this->taskRepository->delete($id);

        //先にdeleteしておけばorderの重複は起きないはず

        $this->updateOtherTasks($taskEntity);


        return response('Deleted', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param \App\Entity\Task $taskEntity
     * @param $task
     */
    public function updateOtherTasks(\App\Entity\Task $taskEntity): void
    {
        foreach ($this->taskRepository->getByOrder($taskEntity->getBookmarkId(), $taskEntity->getOrder()) as $task) {

            $this->taskRepository->updateOrder($task->getId(), $task->getOrder() - 1);
        }
    }
}
