<?php

namespace App\Http\Controllers;

use App\Factory\UpdateTaskUseCaseFactory;
use App\Model\Task;
use App\UseCase\Task\DeleteTaskUseCase;
use App\UseCase\Task\TaskUpdateUseCase;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class TaskController extends Controller
{
    /**
     * @var DeleteTaskUseCase
     */
    private $deleteTaskUseCase;


    /**
     * TaskController constructor.
     */
    public function __construct(DeleteTaskUseCase $deleteTaskUseCase)
    {

        $this->deleteTaskUseCase = $deleteTaskUseCase;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $task = Task::create(["bookmark_id" => $request->bookmark_id, "order" => $request->order, "question_id" => $request->question_id]);

        return $task;
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Model\Task $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Model\Task $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Model\Task $task
     * @return \Illuminate\Http\Response
     */
    public function update(int $id, Request $request)
    {

        $useCase = UpdateTaskUseCaseFactory::create($request);
        return $useCase->execute($request, $id);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Model\Task $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $this->deleteTaskUseCase->execute($id);

    }


}
