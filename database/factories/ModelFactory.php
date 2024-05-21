<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/use Faker\Generator as Faker;

$factory->define(App\Models\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
    ];
});

$factory->define(App\Models\Book::class, function (Faker $faker) {
    $title = $faker->sentence(rand(3, 10));

    return [
        'title' => rtrim($title, '.'),
        'description' => $faker->text,
    ];
});

$factory->define(App\Models\Author::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'biography' => join(" ", $faker->sentences(rand(3, 5))),
        'gender' => $faker->randomElement(['male', 'female']),
    ];
});