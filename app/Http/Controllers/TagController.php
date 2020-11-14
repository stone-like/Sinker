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
use App\Util\QuestionToTag;
use Illuminate\Http\Request;
use App\Http\Resources\TagResource;
use App\Http\Resources\QuestionResource;

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

    public function __construct(AttachTagsToQuestionUseCase $attachTagsToQuestionUseCase,FindQuestionUseCase $findQuestionUseCase,$fire=true)
    {
        if($fire){
            $this->fireMiddleware();
        }
        $this->attachTagsToQuestionUseCase = $attachTagsToQuestionUseCase;
        $this->findQuestionUseCase = $findQuestionUseCase;
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
        //これがうまくいかなかったのは多分名前を送ってうまくやったつもりだったけどdefaultではidで判別するのでたぶんダメだった
        $tags = Tag::where('name','LIKE',"%{$request->keywords}%")->get();
        $tags_question_array=array();

        $check_question_id_array=array();//重複対策、idをここに入れてすでにidが出たかどうかをこの配列でcheck
        foreach($tags as $tag){
            //とってきたtag一つ一つからさらに複数のquestionを得るんだけど、それがnestしているので[tag1:question5つ,tag2:question３つ]とか
            //そうじゃなくて[question1,question2...question5//ここまでtag1の分question6...]みたいにしたいので単に$tag->questionとするだけではだめ

            //ここでquestionの重複対策もしなければダメみたい
            foreach($tag->question as $single_question){
                if(!in_array($single_question->id,$check_question_id_array)){

                    array_push($tags_question_array,new TagResource($single_question));

                    array_push($check_question_id_array,$single_question->id);
                }else{

                }
            }
        }
        return $tags_question_array;
    }

    public function fireMiddleware(): void
    {
        $this->middleware('JWT', ['except' => ['index', 'show', 'TagToQuestion']]);
    }
}
