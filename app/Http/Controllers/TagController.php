<?php

namespace App\Http\Controllers;

use App\Factory\CreateTagUseCaseFactory;
use App\Http\Wrapper\BroadcastWrapper;
use App\Http\Wrapper\BroadcastWrapperInterface;
use App\Model\Tag;
use App\Model\Category;
use App\Model\Question;
use App\Events\EditTagEvent;
use App\UseCase\Question\AttachTagsToQuestionUseCase;
use App\UseCase\Question\FindQuestionUseCase;
use App\UseCase\Tag\SearchTagsUseCase;
use App\Util\QuestionToTag;
use Illuminate\Http\Request;
use App\Http\Resources\TagResource;
use App\Http\Resources\QuestionResource;

use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TagController extends Controller
{
    use QuestionToTag;

    /**
     * @var AttachTagsToQuestionUseCase
     */
    private $attachTagsToQuestionUseCase;
    /**
     * @var FindQuestionUseCase
     */
    private $findQuestionUseCase;
    /**
     * @var SearchTagsUseCase
     */
    private $searchTagsUseCase;

    public function __construct(AttachTagsToQuestionUseCase $attachTagsToQuestionUseCase,FindQuestionUseCase $findQuestionUseCase,SearchTagsUseCase $searchTagsUseCase,$fire=true)
    {
        if($fire){
            $this->fireMiddleware();
        }
        $this->attachTagsToQuestionUseCase = $attachTagsToQuestionUseCase;
        $this->findQuestionUseCase = $findQuestionUseCase;
        $this->searchTagsUseCase = $searchTagsUseCase;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Question $question)
    {
        return $question->tag->toArray();
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
    public function store(Question $question,Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question,Tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(int $question_id,Request $request)
    {
        //tag自体はtableから消さないでいい、そのquestionと紐づいているtagを変更したいのでもってきた$request->tags_stringを中間テーブルに紐づけてアップデートするだけ、全デリートしてからattachすればいいかな？
        //あとは新しく追加されたtagを全体のTagに追加するのを忘れずに
        $tags_array = $this->createTagsArray($request);
        $this->syncTagsToQuestion($question_id, $tags_array);
        $this->broadcast(new BroadcastWrapper(new EditTagEvent($tags_array)));

        return response('Update',Response::HTTP_ACCEPTED);
    }



    public function broadcast(BroadcastWrapperInterface $wrapper)
    {

        broadcast($wrapper->getEvent())->toOthers();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Question $question,Request $request, Tag $tag)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question,Tag $tag)
    {
        //
    }

    public function searchQuestionFromTag(Request $request){

        return $this->searchTagsUseCase->execute($request->searchterm);

    }

    public function fireMiddleware(): void
    {
        $this->middleware('JWT', ['except' => ['index', 'show', 'TagToQuestion']]);
    }
}
