<?php


namespace App\Repository;


use App\Exceptions\QuestionNotFoundException;
use App\Model\Question;
use App\Util\ConvertEntity\ConvertRepliesToEntities;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class QuestionRepository implements QuestionRepositoryInterface
{
    public function create($request)
    {

        $question = Question::create([
            "title" => $request["title"],
            "slug" => $request["slug"],
            "body" => $request["body"],
            "user_id" => $request["user_id"],
            "category_id" => $request["category_id"]
        ]);


        //作ったばかりの時は当然何のreplyもないのでrepliesのところは[]
        return new \App\Entity\Question(
            $question->id,
            $question->title,
            $question->slug,
            $question->body,
            $question->category_id,
            $question->user_id,
            []);
    }

    public function delete(int $id)
    {

        $question = $this->findByIdToModel($id);
        $question->delete();


    }

    public function attachTags($question_id, $tag_ids)
    {

        $question = $this->findByIdToModel($question_id);
        $question->tag()->attach($tag_ids);
        return $question;

    }

    public function findByIdToModel(int $id)
    {
        try {
            $question = Question::where("id", $id)->firstOrFail();
            return $question;
        } catch (ModelNotFoundException $e) {
            throw new QuestionNotFoundException($e->getMessage());
        }
    }

    public function findById(int $id)
    {
        try {
            $question = Question::where("id", $id)->firstOrFail();
            $replies = ConvertRepliesToEntities::convert($question->replies);
            return new \App\Entity\Question(
                $question->id,
                $question->title,
                $question->slug,
                $question->body,
                $question->category_id,
                $question->user_id,
                $replies
            );
        } catch (ModelNotFoundException $e) {
            throw new QuestionNotFoundException($e->getMessage());
        }
    }


}
