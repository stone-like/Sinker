<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create()
    {
        $this->cateRepo->create("dummy");

        $this->assertDatabaseHas("categories", ["name" => "dummy"]);
    }
    /** @test */
    public function it_can_find_by_name()
    {
        $this->cateRepo->create("dummy");
        $tag = $this->cateRepo->findByName("dummy");
        $this->assertEquals("dummy",$tag->getName());
    }

}
