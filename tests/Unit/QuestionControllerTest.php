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
    public function return_question_no_tag()
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

        $this->assertEquals("dummy",$ret["title"]);
        $this->assertEquals("dummy",$ret["slug"]);
        $this->assertEquals("aaa",$ret["body"]);
        $this->assertEquals(1,$ret["category_id"]);
        $this->assertEquals(1,$ret["user_id"]);

    }
}
