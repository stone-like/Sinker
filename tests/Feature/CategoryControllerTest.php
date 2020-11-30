<?php

namespace Tests\Feature;

use App\Http\Controllers\CategoryController;
use App\Model\Category;
use App\UseCase\Category\CreateCategoryUsecase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;
    private $category;
    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        //        factory(User::class, 10)->create();
        //        factory(Category::class, 5)->create();
        $this->category = factory(Category::class, 1)->create(["name" => "dummy"]);
    }

    /** @test */
    public function category_can_store()
    {
        $usecase =  $this->app->make(CreateCategoryUsecase::class);
        $this->app->bind(CreateCategoryUsecase::class, function () use ($usecase) {
            return $usecase;
        });
        $data = [
            "name" => "dummy2"
        ];
        $res = json_decode($this->post("/api/category", $data)->content(), true);
        $this->assertSame($res["name"], "dummy2");
    }
}
