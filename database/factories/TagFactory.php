<?php

use Faker\Generator as Faker;

$factory->define(App\Model\Tag::class, function (Faker $faker) {
    $word = $faker->unique()->word;
    return [
        'name' => $word
    ];
});
