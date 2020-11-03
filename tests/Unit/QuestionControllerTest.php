<?php

namespace Tests\Unit;

use App\Http\Controllers\QuestionController;
use App\Http\Controllers\Test\FakeQuestionController;

use App\Http\Wrapper\FakeBroadcastWrapper;
use App\Model\Category;
use App\Model\Like;
use App\Model\Question;
use App\Model\Reply;
use App\Model\Tag;
use App\Repository\QuestionRepository;
use App\Repository\QuestionRepositoryInterface;
use App\UseCase\Question\AttachTagsToQuestionUseCase;
use App\UseCase\Question\CreateQuestionUseCase;
use App\UseCase\Question\DeleteQuestionUseCase;
use App\UseCase\Question\FindQuestionUseCase;
use App\UseCase\Question\TestPushNotificationUseCase;
use App\User;
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

    private $question;

    public function setUp(): void
    {
        //毎回これをやるとtestが遅くなるのでやっぱりRepositoryができ次第Mockに変えた方がいいかもしれない
        parent::setUp();


//        factory(Category::class, 5)->create();
//
//        factory(Question::class, 10)->create();
//        factory(Reply::class, 10)->create()->each(function ($reply) {
//            return $reply->like()->save(factory(Like::class)->make());
//        });
//
//
//        factory(Tag::class, 10)->create();
//        $tags = Tag::all();
//        $questions = Question::all();
//        foreach ($questions as $question) {
//            $question->tag()->attach(
//            //ここでのrandomはmodelが返る
//                $tags->random(rand(1, 3))->pluck('id')->toArray()
//            );
//        }
        factory(User::class, 10)->create();
        factory(Category::class, 5)->create();
        factory(Question::class, 50)->create();
        factory(Reply::class, 50)->create()->each(function ($reply) {
            return $reply->like()->save(factory(Like::class)->make());
        });

        //replyTest用
        $this->question = factory(Question::class, 1)->create([
            "title" => "testQuestion"
        ])->first();
        factory(Reply::class, 3)->create([
            "question_id" => $this->question->id
        ])
            ->each(function ($reply) {
                return $reply->like()->save(factory(Like::class)->make());
            });

//        factory(Tag::class, 100)->create();

//        $tags = Tag::all();

//        $questions = Question::all();
//        foreach ($questions as $question) {
//            $question->tag()->attach(
//            //ここでのrandomはmodelが返る
//                $tags->random(rand(1, 15))->pluck('id')->toArray()
//            );
//        }

    }

    public function tearDown()
    {
        parent::tearDown();

        TestPushNotificationUseCase::teardown();
    }

    /** @test */
    public function return_question_only_category_name_tag()
    {
        $this->withoutExceptionHandling();
        $fakeBroadcastWrapper = new FakeBroadcastWrapper();
        $fakeMock = Mockery::mock(QuestionController::class,
            [
                $this->app->make(CreateQuestionUseCase::class),
                $this->app->make(AttachTagsToQuestionUseCase::class),
                $this->app->make(FindQuestionUseCase::class),
                $this->app->make(DeleteQuestionUseCase::class),
                $this->app->make(TestPushNotificationUseCase::class),
                false
            ])
            ->makePartial();

        $fakeMock->shouldReceive("broadcast")
            ->withAnyArgs($fakeBroadcastWrapper)
            ->andReturn("");


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
        $this->assertEquals($user->id, $ret["user_id"]);

        $category = Category::where("id", 1)->first();
        $tag = Tag::where("name", $category->name)->first();

        $questionTags = Question::find($ret["id"])->tag->pluck("id");

        $this->assertCount(1, $questionTags);
        $this->assertContains($tag->id, $questionTags);

    }

    /** @test */
    public function tag_filled_when_requested()
    {
        $this->withoutExceptionHandling();

//        $question = Question::where("title","testQuestion")->first();
//        var_dump($this->questionRepo->findById($question->id));

        $fakeBroadcastWrapper = new FakeBroadcastWrapper();
        $fakeMock = Mockery::mock(QuestionController::class,
            [
                $this->app->make(CreateQuestionUseCase::class),
                $this->app->make(AttachTagsToQuestionUseCase::class),
                $this->app->make(FindQuestionUseCase::class),
                $this->app->make(DeleteQuestionUseCase::class),
                $this->app->make(TestPushNotificationUseCase::class),
                false
            ])
            ->makePartial();

        $fakeMock->shouldReceive("broadcast")
            ->withAnyArgs($fakeBroadcastWrapper)
            ->andReturn("");


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
        $this->assertEquals($user->id, $ret["user_id"]);

        $tag1 = Tag::where("name", "test1")->first();
        $tag2 = Tag::where("name", "test2")->first();
        $questionTags = Question::find($ret["id"])->tag->pluck("id");
        $this->assertCount(2, $questionTags);
        $this->assertContains($tag1->id, $questionTags);
        $this->assertContains($tag2->id, $questionTags);


    }

    /** @test */
    public function can_delete_question()
    {
        $this->withoutExceptionHandling();
        $fakeBroadcastWrapper = new FakeBroadcastWrapper();
        $fakeMock = Mockery::mock(QuestionController::class,
            [
                $this->app->make(CreateQuestionUseCase::class),
                $this->app->make(AttachTagsToQuestionUseCase::class),
                $this->app->make(FindQuestionUseCase::class),
                $this->app->make(DeleteQuestionUseCase::class),
                $this->app->make(TestPushNotificationUseCase::class),
                false
            ])
            ->makePartial();

        $fakeMock->shouldReceive("broadcast")
            ->withAnyArgs($fakeBroadcastWrapper)
            ->andReturn("");
        $fakeMock->shouldReceive("pushNotification")
            ->withAnyArgs("")
            ->andReturn("");

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

        $this->assertDatabaseHas("questions", ["title" => "dummy", "body" => "aaa"]);


        $this->delete("/api/question/" . $ret["id"]);

        $this->assertDatabaseMissing("questions", ["title" => "dummy", "body" => "aaa"]);

    }

    /** @test */
    public function can_notificate_on_delete_question()
    {
        $this->withoutExceptionHandling();
        $fakeBroadcastWrapper = new FakeBroadcastWrapper();

        $fakeMock = Mockery::mock(QuestionController::class,
            [
                $this->app->make(CreateQuestionUseCase::class),
                $this->app->make(AttachTagsToQuestionUseCase::class),
                $this->app->make(FindQuestionUseCase::class),
                $this->app->make(DeleteQuestionUseCase::class),
                $this->app->make(TestPushNotificationUseCase::class),
                false
            ])
            ->makePartial();

        $fakeMock->shouldReceive("broadcast")
            ->withAnyArgs($fakeBroadcastWrapper)
            ->andReturn("");


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


        //array_diffは第一引数を起点として第二引数を引いたものが出る
        $targetDiff = array_diff($this->questionRepo->findById($this->question->id)->getReplies()->map(function ($reply) {
            return $reply->getUserId();
        })->toArray(), [$this->question->user_id]);

        $this->delete("/api/question/" . $this->question->id);



        foreach (array_map(null, $targetDiff, TestPushNotificationUseCase::$notificationArray) as [$val1, $val2]) {
            $this->assertEquals($val1, $val2);
        }


    }
}
