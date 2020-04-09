<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Design::class, function (Faker $faker) {
    return [
        'user_id' => random_int(1, 10),
        'image' => random_int(1,10).'jpeg',
        'title' => $title = $faker->title,
//        'text' => $faker->sentence,
        'slug' => \Illuminate\Support\Str::slug($title),
        'is_live' => $faker->boolean,
        'upload_successful' => $faker->boolean,
    ];
});
