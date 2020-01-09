<?php

use App\Model\Tag;
use App\Model\Like;
use App\Model\Reply;
use App\Model\Category;
use App\Model\Question;
use Illuminate\Database\Seeder;
use App\User; //ちゃんとnamespaceを指定しないとうまくいかないのでUser::とかする時は注意

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        factory(User::class, 10)->create();
        factory(Category::class, 5)->create();
        factory(Question::class, 50)->create();
        factory(Reply::class, 50)->create()->each(function ($reply) {
            return $reply->like()->save(factory(Like::class)->make());
        });


        factory(Tag::class, 100)->create();

        $tags = Tag::all();
        // factory(Question::class,10)->create()->each(function($question) use($tags){
        //     return $question->tag()->attach(
        //         $tags->random(rand(1,15))->pluck('id')->toArray()
        //     );
        // });
        $questions = Question::all();
        foreach ($questions as $question) {
            $question->tag()->attach(
                //ここでのrandomはmodelが返る
                $tags->random(rand(1, 15))->pluck('id')->toArray()
            );
        }
    }
}
