<?php

namespace App\Http\Controllers;

use App\Factory\UpdateTaskUseCaseFactory;
use App\Model\Task;
use App\UseCase\Task\TaskUpdateUseCase;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class TaskController extends Controller
{


    /**
     * TaskController constructor.
     */
    public function __construct()
    {

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
    public function update(int $id,Request $request)
    {

        $useCase = UpdateTaskUseCaseFactory::create($request);
        return $useCase->execute($request,$id);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Model\Task $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {    //データベース上で削除するときにそれが属していたbookmarkの他のtaskのorderもしっかりいじる
        //削除するタスクよりorderが小さいほうはいじらないでよくて、大きいほうは-1していけばよい
        $target_bookmark_id = $task->bookmark_id;
        $target_order = $task->order;
        $task->Delete();
        //先にdeleteしておけばorderの重複は起きないはず
        $change_task_collection = Task::where('bookmark_id', $target_bookmark_id)->where('order', ">", $target_order)->get();

        foreach ($change_task_collection as $single_task) {
            $single_task->update(['order' => $single_task->order - 1]);
        }


        return response('Deleted', Response::HTTP_NO_CONTENT);
    }



}
