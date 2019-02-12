<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_tag', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tag_id');
            $table->unsignedInteger('question_id');
            //tag_idとquestion_idにindexつけて検索を早く
            $table->index('tag_id');
            $table->index('question_id');
            $table->timestamps();

            $table->foreign('tag_id')->references('id')
                   ->on('tags')->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('question_id')->references('id')
                   ->on('questions')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('question_tag');
    }
}
