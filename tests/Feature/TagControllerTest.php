<?php

namespace Tests\Feature;

use App\Http\Controllers\QuestionController;
use App\Http\Controllers\TagController;
use App\Http\Wrapper\FakeBroadcastWrapper;
use App\Model\Category;
use App\Model\Like;
use App\Model\Question;
use App\Model\Reply;
use App\Model\Tag;
use App\UseCase\Question\AttachTagsToQuestionUseCase;
use App\UseCase\Question\CreateQuestionUseCase;
use App\UseCase\Question\DeleteQuestionUseCase;
use App\UseCase\Question\FindQuestionUseCase;
use App\UseCase\Question\TestPushNotificationUseCase;
use App\User;
use Mockery;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagControllerTest extends TestCase
{


    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        factory(User::class, 10)->create();
        factory(Category::class, 5)->create();
        factory(Question::class, 50)->create();
        factory(Reply::class, 50)->create()->each(function ($reply) {
            return $reply->like()->save(factory(Like::class)->make());
        });

        //replyTest用
        $this->question = factory(Question::class, 1)->create([
            "title" => "testQuestion",
            "category_id" => 1
        ])->first();
        factory(Reply::class, 3)->create([
            "question_id" => $this->question->id
        ])
            ->each(function ($reply) {
                return $reply->like()->save(factory(Like::class)->make());
            });
    }

    /** @test */
    public function successfully_sync_question_and_tag_when_no_request_tag()
    {
        $this->withoutExceptionHandling();
        $fakeBroadcastWrapper = new FakeBroadcastWrapper();
        $fakeMock = Mockery::mock(TagController::class,
            [
                $this->app->make(AttachTagsToQuestionUseCase::class),
                $this->app->make(FindQuestionUseCase::class),
                false
            ])
            ->makePartial();

        $fakeMock->shouldReceive("broadcast")
            ->withAnyArgs($fakeBroadcastWrapper)
            ->andReturn("");


        $this->app->bind(TagController::class, function () use ($fakeMock) {
            return $fakeMock;
        });

        $user = $this->signIn();

        $data = [
            "category_id" => 1
        ];

        $this->patch("/api/" . $this->question->id . "/tag", $data);


        $category = Category::where("id", 1)->first();
        $tag = Tag::where("name", $category->name)->first();

        $questionTags = $this->question->fresh()->tag->pluck("id");

        $this->assertCount(1, $questionTags);
        $this->assertContains($tag->id, $questionTags);

    }

    /** @test */
    public function successfully_sync_question_and_tag_when_request_tag_exist()
    {
        $this->withoutExceptionHandling();


        $fakeBroadcastWrapper = new FakeBroadcastWrapper();
        $fakeMock = Mockery::mock(TagController::class,
            [

                $this->app->make(AttachTagsToQuestionUseCase::class),
                $this->app->make(FindQuestionUseCase::class),

                false
            ])
            ->makePartial();

        $fakeMock->shouldReceive("broadcast")
            ->withAnyArgs($fakeBroadcastWrapper)
            ->andReturn("");


        $this->app->bind(TagController::class, function () use ($fakeMock) {
            return $fakeMock;
        });

        $user = $this->signIn();

        $data = [
            "category_id" => 1,
            "tags_string" => "test1,test2"
        ];

        $this->patch("/api/" . $this->question->id . "/tag", $data);


        $tag1 = Tag::where("name", "test1")->first();
        $tag2 = Tag::where("name", "test2")->first();
        $questionTags = $this->question->fresh()->tag->pluck("id");
        $this->assertCount(2, $questionTags);
        $this->assertContains($tag1->id, $questionTags);
        $this->assertContains($tag2->id, $questionTags);


    }
}
