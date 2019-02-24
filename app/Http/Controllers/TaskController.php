<?php

namespace App\Http\Controllers;

use App\Model\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {

        // //自分自身の位置をアップデート
        // $status = $task->update(
        //     $request->only(['bookmark_id','order'])
        // );//自分自身を周辺のupdateより上に持ってきちゃうと自分もその影響を受けてしまうので


        //変化に伴いfromとtoのbookmarkもアップデート
        //bookmark_id == fromTask_idなら変化していないのでfromTaskの方だけいじればよくてそうじゃなければ両方いじる
        if($request->bookmark_id !== $request->fromTask_id){
            //両方いじる
            //まずはfrom側
            //移動したやつのもともとのpositionはoldpositionで、そこより数字が上のorderを-1
            $from_tasks = Task::where('bookmark_id',$request->fromTask_id)->where('order',">",$request->oldPosition)->get();
            //oldPosition側は変更前の値を使ってok

            foreach( $from_tasks as $from_task){
                $from_task->update(['order' => $from_task->order-1]);
            }

            //to側
            $to_tasks = Task::where('bookmark_id',$request->toTask_id)->where('order',">",$request->newPosition-1)->get();
            //ここのorderはposition変更前なため不等号にするかnewpositionを-1するかどちらか

            foreach( $to_tasks as $to_task){
                $to_task->update(['order' => $to_task->order+1]);
            }
        }else{
            //横移動はしていないのでfromだけいじる
            //updateさせたい範囲をとってくる
            if($request->oldPosition > $request->newPosition){
                //fromからtoへ上側に上がるとき +1
                $length = $request->oldPosition - $request->newPosition;

                for($i=0; $i<$length;$i++){
                    $from_task = Task::where('bookmark_id',$request->fromTask_id)->where('order',$request->newPosition+$i)->first();//とってくるのは毎回一つなのでfirst
                    //これは変化前のorderをとってくる、だから移動したやつのnewPositionと同じでいい
                    dump($from_task->order);
                    $from_task->update(['order' => $from_task->order+1]);
                }
            }else{
                //fromからtoへ下側に下がるとき　-1
                $length = $request->newPosition - $request->oldPosition;

                for($i=0; $i<$length;$i++){
                    $from_task = Task::where('bookmark_id',$request->fromTask_id)->where('order',$request->oldPosition+$i+1)->first();//こっちは元居た場所の一つ下からスタートするから最初に一つ場所を下にしておく
                    dump($from_task);
                    $from_task->update(['order' => $from_task->order-1]);
                }
            }

        }

        //  dump($task,$request->newPosition);
        //自分自身の位置をアップデート
        $status = $task->update(
           ['bookmark_id' => $request->bookmark_id,'order' => $request->newPosition]);
        //自分自身を周辺のupdateより上に持ってきちゃうと自分もその影響を受けてしまうので




        return response()->json([
            'status' => $status,
            "message" => $status ? 'Task Updated' : 'Error Updating'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        //
    }
}
