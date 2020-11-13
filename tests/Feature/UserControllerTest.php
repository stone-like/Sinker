<?php

namespace Tests\Feature;

use App\Http\Controllers\UserController;
use App\Model\Category;
use App\Model\Like;
use App\Model\Question;
use App\Model\Reply;
use App\UseCase\User\GetEntireDataUseCase;
use App\UseCase\User\GetRecentUseCase;
use App\User;
use Mockery;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        factory(User::class, 10)->create();
        factory(Category::class, 5)->create();
        factory(Question::class, 50)->create();
        factory(Reply::class, 50)->create()->each(function ($reply) {
            return $reply->like()->save(factory(Like::class)->make());
        });

        $this->question = factory(Question::class, 1)->create([
            "title" => "testQuestion"
        ])->first();
        factory(Reply::class, 3)->create([
            "question_id" => $this->question->id
        ])
            ->each(function ($reply) {
                return $reply->like()->save(factory(Like::class)->make());
            });
    }

    /** @test */
    public function it_can_get_EntireData()
    {
        $this->withoutExceptionHandling();
        $user = $this->signIn();
        factory(Question::class, 1)->create([
            "user_id" => $user->id
        ]);
        factory(Reply::class, 1)->create([
            "user_id" => $user->id,
            "question_id" => $this->question->id
        ]);

        $fakeMock = Mockery::mock(UserController::class,
            [
                $this->app->make(GetEntireDataUseCase::class),
                $this->app->make(GetRecentUseCase::class),

                false
            ])
            ->makePartial();

        $this->app->bind(UserController::class, function () use ($fakeMock) {
            return $fakeMock;
        });

        $res = json_decode($this->get("/api/user/" . $user->id)->content(), true);
        //上記でこのuserのquestionとreplyを一つずつつくって、likeはしていない

        self::assertEquals(1, $res["total_posts"]);
        self::assertEquals(1, $res["total_replies"]);
        self::assertEquals(0, $res["total_likes"]);

    }

    /** @test */
    public function it_can_get_Recent()
    {
        $this->withoutExceptionHandling();
        $user = $this->signIn();
        factory(Question::class, 6)->create([
            "user_id" => $user->id
        ]);
        factory(Reply::class, 6)->create([
            "user_id" => $user->id,
            "question_id" => $this->question->id
        ]);

        $fakeMock = Mockery::mock(UserController::class,
            [
                $this->app->make(GetEntireDataUseCase::class),
                $this->app->make(GetRecentUseCase::class),


                false
            ])
            ->makePartial();

        $this->app->bind(UserController::class, function () use ($fakeMock) {
            return $fakeMock;
        });

        $res = json_decode($this->get("/api/user/" . $user->id."/recent")->content(), true);
        //上記でこのuserのquestionとreplyを一つずつつくって、likeはしていない

        self::assertCount(5,$res["recent_posts"]);
        self::assertCount(5,$res["recent_replies"]);


    }
}
