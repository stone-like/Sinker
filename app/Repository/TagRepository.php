<?php


namespace App\Repository;


use App\Exceptions\TagNotFoundException;
use App\Model\Tag;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TagRepository implements TagRepositoryInterface
{
    public function create(string $name)
    {
        $tag = Tag::firstOrCreate(["name" => $name]);
        return new \App\Entity\Tag($tag->id, $tag->name);
    }

    public function findById(int $id)
    {
        try {
            $tag = Tag::where("id", $id)->firstOrFail();
            return new \App\Entity\Tag($tag->id, $tag->name);
        } catch (ModelNotFoundException $e) {
            throw new TagNotFoundException($e->getMessage());
        }
    }

    public function findByName(string $name)
    {
        try {
            $tag = Tag::where("name", $name)->firstOrFail();
            return new \App\Entity\Tag($tag->id, $tag->name);
        } catch (ModelNotFoundException $e) {
            throw new TagNotFoundException($e->getMessage());
        }
    }

    public function findByNames(array $nameList)
    {
        $tagEntityList = Tag::whereIn("name", $nameList)->get()->map(function($tag){
            return new \App\Entity\Tag($tag->id, $tag->name);
        });
        return $tagEntityList;
    }
}
