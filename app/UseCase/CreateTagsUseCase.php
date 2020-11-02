<?php


namespace App\UseCase;


use App\Model\Category;
use App\Model\Tag;
use App\Repository\TagRepositoryInterface;

class CreateTagsUseCase extends CreateQuestionTagsUseCase
{
    protected $tagRepo;

    /**
     * CreateTagsUseCase constructor.
     * @param $tagRepo
     */
    public function __construct(TagRepositoryInterface $tagRepo)
    {
        $this->tagRepo = $tagRepo;
    }

    public function create($request): array
    {
        $this->createTags($request);

        return $this->pushTagsToTagsArray($request);
    }

    public function extractTags($request)
    {
        //"anime,music,sports"みたいに送られてくる
        $clean = trim($request["tags_string"], " ");
        //[anime,music,sports]とか
        $tags_array = explode(",", trim($clean, " "));
        return $tags_array;
    }

    /**
     * @param $request
     * @return mixed|string
     */
    public function createTags($request)
    {
        foreach ($this->extractTags($request) as $tag_single) {
            $this->tagRepo->create(trim($tag_single, " "));
        }

    }

    /**
     * @param $request
     * @param array $tags_id_array
     * @return array
     */

    //複数Entityをgetする形に切り替えていく
    public function pushTagsToTagsArray($request): array
    {
        return $this->getTags($request)->map(function($tagEntity){
            return $tagEntity->getId();
        })->toArray();
    }

    public function getTags($request){

        return $this->tagRepo->findByNames($this->extractTags($request));
    }
}
