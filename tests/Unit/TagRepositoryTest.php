<?php

namespace Tests\Unit;


use App\Repository\TagRepositoryInterface;
use App\Repository\TestTagRepository;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class TagRepositoryTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
   public function it_can_create(){

       $tag = $this->tagRepo->create("dummy");
       $this->assertContains("dummy",$tag->getName());
   }

   /** @test */
    public function it_can_find_by_id(){

        $tag = $this->tagRepo->create("dummy");
        $tag = $this->tagRepo->findById($tag->getId());

        $this->assertEquals("dummy",$tag->getName());
    }

    /** @test */
    public function it_can_find_by_name(){

        $tag = $this->tagRepo->create("dummy");
        $tag = $this->tagRepo->findByName($tag->getName());

        $this->assertEquals("dummy",$tag->getName());
    }

    /** @test */
    public function it_can_find_by_names(){
        $data = [
            "dummy",
            "dummy2"
        ];

        $tag = $this->tagRepo->create("dummy");
        $tag = $this->tagRepo->create("dummy2");

        $tagEntityList = $this->tagRepo->findByNames(["dummy","dummy2"]);

        self::assertCount(2,$tagEntityList);

        foreach ($tagEntityList as $index => $tagEntity){
            $this->assertEquals($data[$index],$tagEntity->getName());
        }
    }
}
