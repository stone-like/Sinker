<?php

namespace App\Http\Controllers;

use App\Factory\CreateTagUseCaseFactory;
use App\Factory\CreateTagUseCaseFactoryInterface;
use App\Http\Wrapper\BroadcastWrapper;
use App\Http\Wrapper\BroadcastWrapperInterface;
use App\Model\Tag;
use App\Model\Category;
use App\Model\Question;
use App\UseCase\CreateTagsUseCase;
use App\UseCase\Question\AttachTagsToQuestionUseCase;
use App\UseCase\Question\CreateQuestionUseCase;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Http\Request;
use App\Events\AddQuestionEvent;
use App\Events\DeleteQuestionEvent;
use App\Http\Resources\QuestionResource;
use Symfony\Component\HttpFoundation\Response;
use App\Notifications\DeleteQuestionNotification;

class QuestionController extends Controller
{
    private $createQuestionUseCase;
    private $attachTagsToQuestionUseCase;
    public function __construct(CreateQuestionUseCase $createQuestionUseCase,AttachTagsToQuestionUseCase $attachTagsToQuestionUseCase,$fire=true)
    {
        $this->createQuestionUseCase = $createQuestionUseCase;
        $this->attachTagsToQuestionUseCase = $attachTagsToQuestionUseCase;


        //testでfalseにすればmiddlewareを回避できる
        if($fire){
            $this->fireMiddleware();
        }
    }

    public function fireMiddleware(){
        $this->middleware('JWT', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return QuestionResource::collection(Question::latest()->get());
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
     */

    public function store(Request $request)
    {
        //tagをここで登録するのはおかしいので方針としてはQuestionRepoとTagRepoを作ってそれぞれの責務に分ける？

        //これundefinedと出てるけどこれで動作してるんだけど・・・？
        $question = $this->createQuestion($request);

        //commandとqueryを分割した方がよさそう・・・？

        $this->syncTagsToQuestion($question->getId(), $this->createTagsArray($request));


        $this->broadcast(new BroadcastWrapper(new AddQuestionEvent(new QuestionResource($question))));
        return [
            "id" => $question->getId(),
            "title" => $question->getTitle(),
            "slug" => $question->getSlug(),
            "body" => $question->getBody(),
            "user_id" => $question->getUserId(),
            "category_id" => $question->getCategoryId()
        ];
//        return response(new QuestionResource($question), Response::HTTP_CREATED);
    }

    public function broadcast(BroadcastWrapperInterface $wrapper)
    {

        broadcast($wrapper->getEvent())->toOthers();
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Model\Question $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        return new QuestionResource($question);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Model\Question $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Model\Question $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        $question->update($request->all());
        return response('Update', Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Model\Question $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        $user_array = [];


        foreach ($question->replies()->get() as $reply) {
            $user = $reply->user;
            error_log($question->user->id);

            if ($user->id != $question->user->id && !in_array($user->id, $user_array)) {
                error_log($user->id);
                $user->notify(new DeleteQuestionNotification($reply));
                array_push($user_array, $user->id);
            }
        }
        //deleteするquestionの全情報をこの$question(tableobject)から引き出せる
        broadcast(new DeleteQuestionEvent(new QuestionResource($question)))->toOthers();
        $question->Delete();
        return response('Deleted', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function createQuestion(Request $request)
    {
        //ここのauthを剥がさないとうまく責務の分割ができないので、requestにuser_idを含めることにする
        //元のコード
        $question = $this->createQuestionUseCase->execute($request);

        return $question;
    }

    /**
     * @param Request $request
     * @param array $tags_id_array
     * @return array
     */
    public function createTagsArray(Request $request): array
    {
        $usecase = CreateTagUseCaseFactory::create($request);
        return $usecase->execute($request);

    }

    /**
     * @param $question
     * @param array $tags_id_array
     */
    public function syncTagsToQuestion($question_id, array $tags_id_array): void
    {
        $this->attachTagsToQuestionUseCase->execute($question_id,$tags_id_array);
//        $question->tag()->attach($tags_id_array);
    }
}
