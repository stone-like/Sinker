<?php

namespace App\Http\Controllers;


use App\Http\Requests\CreateReplyRequest;
use App\Http\Wrapper\BroadcastWrapper;
use App\Http\Wrapper\BroadcastWrapperInterface;
use App\Model\Reply;
use App\Model\Question;
use App\UseCase\Question\FindQuestionUseCase;
use App\UseCase\Question\PushNotificationUseCase;
use App\UseCase\Reply\CreateReplyUseCase;
use App\UseCase\Reply\PushReplyNotificationUseCase;
use Illuminate\Http\Request;
use App\Events\AddReplyEvent;
use App\Events\DeleteReplyEvent;
use App\Http\Resources\ReplyResource;
use App\Notifications\NewReplyNotification;
use Symfony\Component\HttpFoundation\Response;

class ReplyController extends Controller
{
    private $findQuestionUseCase;

    private $createReplyUseCase;
    /**
     * @var PushReplyNotificationUseCase
     */
    private $pushReplyNotificationUseCase;

    /**
     * @var PushNotificationUseCase
     */


    public function __construct(FindQuestionUseCase $findQuestionUseCase, CreateReplyUseCase $createReplyUseCase, PushReplyNotificationUseCase $pushReplyNotificationUseCase, $fire = true)
    {
        $this->findQuestionUseCase = $findQuestionUseCase;
        $this->createReplyUseCase = $createReplyUseCase;
        $this->pushReplyNotificationUseCase = $pushReplyNotificationUseCase;

        if ($fire) {
            $this->fireMiddleware();
        }


    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Question $question)
    {
        return ReplyResource::collection(Reply::latest()->get());
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
    public function store(int $id, CreateReplyRequest $request)
    {


        $question = $this->findQuestionUseCase->execute($id);
        $reply = $this->createReplyUseCase->execute($id, $request);

        $this->pushNotification($question, $reply);

        $this->broadcast(new BroadcastWrapper(new AddReplyEvent($reply)));

        return response(['reply' => [
            "reply" => $reply->body,
            "user_id" => $reply->user_id,
            "question_id" => $reply->question_id
        ]], Response::HTTP_CREATED);
    }

    //もしかしたらtraitに抜き出した方がいいかも
    public function broadcast(BroadcastWrapperInterface $wrapper)
    {
        broadcast($wrapper->getEvent())->toOthers();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Model\Reply $reply
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question, Reply $reply)
    {
        return new ReplyResource($reply);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Model\Reply $reply
     * @return \Illuminate\Http\Response
     */
    public function edit(Reply $reply)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Model\Reply $reply
     * @return \Illuminate\Http\Response
     */
    public function update(Question $question, Request $request, Reply $reply)
    {
        $reply->update($request->all());
        return response('Update', Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Model\Reply $reply
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question, Reply $reply)
    {
        $reply->delete();
        broadcast(new DeleteReplyEvent($reply->id, $question->id))->toOthers();
        return response('Deleted', Response::HTTP_NO_CONTENT);
    }

    public function fireMiddleware(): void
    {
        $this->middleware('JWT', ['except' => ['index', 'show']]);
    }

    /**
     * @param $question
     * @param $reply
     */
    public function pushNotification($question, $reply): void
    {
        $this->pushReplyNotificationUseCase->execute($question->getId(), $reply);
    }
}
