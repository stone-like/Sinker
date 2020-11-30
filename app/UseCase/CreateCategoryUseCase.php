<?php


namespace App\UseCase;


use App\Model\Category;
use App\Model\Tag;
use App\Repository\CategoryRepositoryInterface;
use App\Repository\TagRepositoryInterface;

class CreateCategoryUseCase extends CreateQuestionTagsUseCase
{
    private $cateRepo;
    private $tagRepo;

    /**
     * CreateCategoryUseCase constructor.
     * @param $cateRepo
     */
    public function __construct(CategoryRepositoryInterface $cateRepo, TagRepositoryInterface $tagRepo)
    {
        $this->cateRepo = $cateRepo;
        $this->tagRepo = $tagRepo;
    }

    public function create($request): array
    {
        $tags_id_array = array();


        //タグが書かれていなかったらcategory名だけを追加するようにする

        $category_name = $this->cateRepo->findById($request->category_id)->getName();
        $tag = $this->tagRepo->create($category_name);
        array_push($tags_id_array, $tag->getId());

        return $tags_id_array;
    }
}
