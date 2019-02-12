<?php

namespace App\Http\Controllers;

use App\Model\Tag;
use App\Model\Question;
use Illuminate\Http\Request;
use App\Events\AddQuestionEvent;
use App\Events\DeleteQuestionEvent;
use App\Http\Resources\QuestionResource;
use Symfony\Component\HttpFoundation\Response;
use App\Notifications\DeleteQuestionNotification;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('JWT', ['except' => ['index','show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return  QuestionResource::collection(Question::latest()->get());
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
        $question = auth()->user()->question()->create($request->except("tags_string"));
        info($question);
        $tags_id_array = array();

        if($request->filled("tags_string")){
            $clean = trim($request->tags_string," ");
            $tags_array = explode(" ",$clean);
            //[anime,music,sports]とか
            foreach($tags_array as $tag){
                 if(Tag::all()->where('name',$tag)->exists()){
                      //もうtagがすでにあるなら新たにtagsに追加はしない
                 }else{
                     //まだないなら追加
                     Tag::all()->create( ['name' => $tag]);
                 }
            }

            //最後に中間テーブルにすべて追加する、tags自体重複気にしたけどこれは質問自体のtagなのですべて,それで上で新しいのも追加したからそれ含めてtagsのidをとってくる,なのでtagsの名前からidに変える作業に入る　今tags_arrayには名前が入っている

            foreach($tags_array as $tag){
                //上ではまだ新しいのを追加していなかったのでwhereで取得できなかったが今はできる,まぁインスタンス使ってid取得を直後にすれば行けるけど統一したかった
                array_push($tags_id_array,Tag::all()->where('name',$tag)->id);
            }
            $question->tag()->attach($tags_id_array);

        }else{

        }


        broadcast(new AddQuestionEvent(new QuestionResource($question)))->toOthers();
        return response(new QuestionResource($question),Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        return new QuestionResource($question);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        $question->update($request->all());
        return response('Update',Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {   $user_array=[];


        foreach($question->replies()->get() as $reply){
            $user = $reply->user;
            error_log($question->user->id);

            if($user->id != $question->user->id && !in_array($user->id,$user_array)){
                error_log($user->id);
                $user->notify(new DeleteQuestionNotification($reply));
                array_push($user_array,$user->id);
             }
        }
        //deleteするquestionの全情報をこの$question(tableobject)から引き出せる
        broadcast(new DeleteQuestionEvent(new QuestionResource($question)))->toOthers();
        $question->Delete();
        return response('Deleted',Response::HTTP_NO_CONTENT);
    }
}
