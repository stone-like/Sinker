<?php

namespace Tests\Unit;

use App\Http\Controllers\QuestionController;
use App\Http\Controllers\Test\FakeQuestionController;

use App\Http\Wrapper\FakeBroadcastWrapper;
use App\Model\Category;
use App\Model\Question;
use App\Model\Tag;
use Mockery;
use ReflectionClass;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;


//testもframeworkに依存しない場合、refreshDatabaseとか使えなくなりそうなんだけどどうするんだろ？
//依存しないとなればRepositoryをDIで差し替えて取ってくることになるのか？
class QuestionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        //毎回これをやるとtestが遅くなるのでやっぱりRepositoryができ次第Mockに変えた方がいいかもしれない
        parent::setUp();
        factory(Category::class, 5)->create();
        factory(Tag::class, 10)->create();
        $tags = Tag::all();
        $questions = Question::all();
        foreach ($questions as $question) {
            $question->tag()->attach(
            //ここでのrandomはmodelが返る
                $tags->random(rand(1, 3))->pluck('id')->toArray()
            );
        }
    }

    /** @test */
    public function return_question_only_category_name_tag()
    {
        $fakeBroadcastWrapper = new FakeBroadcastWrapper();
        $fakeMock = Mockery::mock(QuestionController::class)
            ->makePartial()
            ->shouldReceive("broadcast")
            ->withAnyArgs($fakeBroadcastWrapper)
            ->andReturn("")
            ->getMock();
        $this->app->bind(QuestionController::class, function () use ($fakeMock) {
            return $fakeMock;
        });

        $user = $this->signIn();

        $data = [
            "title" => "dummy",
            "slug" => "dummy",
            "body" => "aaa",
            "user_id" => $user->id,
            "category_id" => 1
        ];
        $ret = json_decode($this->post("/api/question", $data)->content(), true);

        $this->assertEquals("dummy", $ret["title"]);
        $this->assertEquals("dummy", $ret["slug"]);
        $this->assertEquals("aaa", $ret["body"]);
        $this->assertEquals(1, $ret["category_id"]);
        $this->assertEquals(1, $ret["user_id"]);

        $category = Category::where("id",1)->first();
        $tag = Tag::where("name", $category->name)->first();

        $questionTags = Question::find($ret["id"])->tag->pluck("id");

        $this->assertCount(1, $questionTags);
        $this->assertContains($tag->id, $questionTags);

    }

    /** @test */
    public function tag_filled_when_requested()
    {
        $fakeBroadcastWrapper = new FakeBroadcastWrapper();
        $fakeMock = Mockery::mock(QuestionController::class)
            ->makePartial()
            ->shouldReceive("broadcast")
            ->withAnyArgs($fakeBroadcastWrapper)
            ->andReturn("")
            ->getMock();
        $this->app->bind(QuestionController::class, function () use ($fakeMock) {
            return $fakeMock;
        });

        $user = $this->signIn();

        $data = [
            "title" => "dummy",
            "slug" => "dummy",
            "body" => "aaa",
            "user_id" => $user->id,
            "category_id" => 1,
            "tags_string" => "test1,test2"
        ];


        $ret = json_decode($this->post("/api/question", $data)->content(), true);

        $this->assertEquals("dummy", $ret["title"]);
        $this->assertEquals("dummy", $ret["slug"]);
        $this->assertEquals("aaa", $ret["body"]);
        $this->assertEquals(1, $ret["category_id"]);
        $this->assertEquals(1, $ret["user_id"]);

        $tag1 = Tag::where("name", "test1")->first();
        $tag2 = Tag::where("name", "test2")->first();
        $questionTags = Question::find($ret["id"])->tag->pluck("id");

        $this->assertCount(2, $questionTags);
        $this->assertContains($tag1->id, $questionTags);
        $this->assertContains($tag2->id, $questionTags);


    }
}
