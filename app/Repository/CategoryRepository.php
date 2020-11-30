<?php


namespace App\Repository;


use App\Exceptions\CategoryNotFoundException;
use App\Model\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryRepository implements CategoryRepositoryInterface
{

    public function create(string $name){
       $slug = str_slug($name);
       return Category::create(["name" => $name,"slug" => $slug]);
    }


    public function findByName(string $name)
    {
        try {
            $category = Category::where("name", $name)->firstOrFail();
            return new \App\Entity\Category($category->id, $category->name,$category->slug);
        } catch (ModelNotFoundException $e) {
            throw new CategoryNotFoundException($e->getMessage());
        }
    }

    public function findById(int $id)
    {
        try {
            $category = Category::where("id", $id)->firstOrFail();
            return new \App\Entity\Category($category->id, $category->name,$category->slug);
        } catch (ModelNotFoundException $e) {
            throw new CategoryNotFoundException($e->getMessage());
        }
    }

}
