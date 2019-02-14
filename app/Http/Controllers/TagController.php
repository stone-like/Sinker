<?php

namespace App\Http\Controllers;

use App\Model\Tag;
use App\Model\Category;
use App\Model\Question;
use App\Events\EditTagEvent;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TagController extends Controller
{

    public function __construct()
    {
        $this->middleware('JWT', ['except' => ['index','show','TagToQuestion']]);
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
    public function edit(Question $question,Request $request,Tag $tag)
    {
        //tag自体はtableから消さないでいい、そのquestionと紐づいているtagを変更したいのでもってきた$request->tags_stringを中間テーブルに紐づけてアップデートするだけ、全デリートしてからattachすればいいかな？
        //あとは新しく追加されたtagを全体のTagに追加するのを忘れずに

        $tags_id_array = array();

        if($request->filled("tags_string")){
            $clean = trim($request->tags_string," ");
            $tags_array = explode(",",$clean);
            dump($clean);
            //[anime,music,sports]とか
            foreach($tags_array as $tag_single){

                //ここでインスタンスを作りここからattach用のid取得
                $tag = Tag::firstOrCreate(['name' => $tag_single]);
                array_push($tags_id_array,$tag->id);

            }


        }else{
            //すべて削除してしまったならcategory名だけ入れておく
            $category_name = Category::where('id',$request->category_id)->first()->name;


            $tag = Tag::firstOrCreate(['name' => $category_name]);
            array_push($tags_id_array,$tag->id);
        }

         //上から最大十個登録するようにする
         $cnt = count($tags_id_array);
         if($cnt > 10){
             $tags_id_array = array_slice($tags_id_array,0,10);
         }else{

         }

        //idを取得したのちdelete and attach,syncは両方いっぺんに行ってくれる
        $question->tag()->sync($tags_id_array);


        broadcast(new EditTagEvent($question->tag->toArray()))->toOthers();

        return response('Update',Response::HTTP_ACCEPTED);
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

    public function TagToQuestion(Request $request){
        //これがうまくいかなかったのは多分名前を送ってうまくやったつもりだったけどdefaultではidで判別するのでたぶんダメだった
        $tags = Tag::where('name','LIKE',"%{$request->keywords}%")->get();
        $tags_question_array=array();
        foreach($tags as $tag){
            //とってきたtag一つ一つからさらに複数のquestionを得るんだけど、それがnestしているので[tag1:question5つ,tag2:question３つ]とか
            //そうじゃなくて[question1,question2...question5//ここまでtag1の分question6...]みたいにしたいので単に$tag->questionとするだけではだめ
            foreach($tag->question as $single_question){
                array_push($tags_question_array,$single_question);
            }
        }
        return $tags_question_array;
    }
}
